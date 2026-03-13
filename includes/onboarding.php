<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_portal_access_state($user) {
    $context = build_normalized_user_context($user);

    if (empty($context['user_id'])) {
        return 'login';
    }

    $approval_status = $context['approval_status'];
    if ($approval_status === 'pending') {
        return 'pending-access';
    }

    if ($approval_status === 'disabled') {
        return 'disabled';
    }

    if ($approval_status !== 'approved') {
        return 'login';
    }

    $onboarding_completed = (bool) get_user_meta((int) $context['user_id'], 'svdp_onboarding_completed', true);
    $account_scope = $context['account_scope'];

    if (!in_array($account_scope, get_allowed_account_scopes(), true)) {
        return 'onboarding';
    }

    if ($account_scope === 'conference' && (int) $context['conference_id'] <= 0) {
        return 'onboarding';
    }

    if (!$onboarding_completed) {
        return 'onboarding';
    }

    return 'ready';
}

function get_portal_gate_template($user) {
    $state = get_portal_access_state($user);

    if ($state === 'ready') {
        return '';
    }

    if (in_array($state, ['pending-access', 'disabled'], true)) {
        return 'pending-access';
    }

    return $state;
}

function user_can_access_portal_after_gates($user) {
    return get_portal_access_state($user) === 'ready';
}

function complete_onboarding($user_id, array $input) {
    $user_id = (int) $user_id;
    $state = get_portal_access_state($user_id);

    if ($user_id <= 0 || in_array($state, ['login', 'pending-access', 'disabled'], true)) {
        return [
            'success' => false,
            'gate' => get_portal_gate_template($user_id),
            'errors' => ['User is not eligible to complete onboarding.'],
        ];
    }

    $account_scope = sanitize_text_field($input['account_scope'] ?? '');
    if (!in_array($account_scope, get_allowed_account_scopes(), true)) {
        return [
            'success' => false,
            'gate' => 'onboarding',
            'errors' => ['Account scope must be conference or district.'],
        ];
    }

    $conference_id = 0;
    if ($account_scope === 'conference') {
        $conference_id = (int) ($input['conference_id'] ?? 0);
        if ($conference_id <= 0) {
            return [
                'success' => false,
                'gate' => 'onboarding',
                'errors' => ['Conference users require exactly one conference assignment.'],
            ];
        }
    }

    update_user_meta($user_id, 'svdp_account_scope', $account_scope);
    update_user_meta($user_id, 'svdp_conference_id', $account_scope === 'conference' ? $conference_id : 0);
    update_user_meta($user_id, 'svdp_onboarding_completed', true);

    return [
        'success' => true,
        'gate' => get_portal_access_state($user_id),
        'errors' => [],
    ];
}

function get_gate_template_context($user, array $errors = []) {
    $state = get_portal_access_state($user);
    $context = build_normalized_user_context($user);
    $user_id = (int) ($context['user_id'] ?? 0);

    if ($state === 'login') {
        return [
            'title' => 'Portal Sign In',
            'message' => 'Use Google sign-in to continue to Vincentian Hub.',
            'button_label' => 'Sign in with Google',
            'button_url' => '/oauth/google',
        ];
    }

    if (in_array($state, ['pending-access', 'disabled'], true)) {
        return [
            'title' => $state === 'disabled' ? 'Access Disabled' : 'Access Pending',
            'message' => $state === 'disabled'
                ? 'Your portal access is currently disabled.'
                : 'Your account is authenticated, but portal access is still pending approval.',
            'status' => $state === 'disabled' ? 'disabled' : 'pending',
        ];
    }

    return [
        'title' => 'Finish Setup',
        'message' => 'Confirm your account scope before protected portal content is available.',
        'account_scope' => (string) get_user_meta($user_id, 'svdp_account_scope', true),
        'conference_id' => (int) get_user_meta($user_id, 'svdp_conference_id', true),
        'requires_conference_assignment' => (string) get_user_meta($user_id, 'svdp_account_scope', true) !== 'district',
        'errors' => $errors,
    ];
}
