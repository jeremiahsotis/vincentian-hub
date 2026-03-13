<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_allowed_directory_sources() {
    return [
        'trusted_directory',
        'manual_admin_entry',
        'oauth_self_registration',
        'imported',
    ];
}

function get_allowed_approval_statuses() {
    return [
        'approved',
        'pending',
        'disabled',
    ];
}

function get_allowed_account_scopes() {
    return [
        'conference',
        'district',
    ];
}

function sync_google_auth_user_state($user_id, array $state) {
    $user_id = (int) $user_id;

    if ($user_id <= 0) {
        return [
            'success' => false,
            'gate' => 'login',
        ];
    }

    $google_sub = sanitize_text_field($state['google_sub'] ?? '');
    if ($google_sub !== '') {
        update_user_meta($user_id, 'svdp_google_sub', $google_sub);
    }

    $directory_source = sanitize_text_field($state['directory_source'] ?? 'oauth_self_registration');
    if (in_array($directory_source, get_allowed_directory_sources(), true)) {
        update_user_meta($user_id, 'svdp_directory_source', $directory_source);
    }

    $approval_status = sanitize_text_field($state['approval_status'] ?? '');
    if (in_array($approval_status, get_allowed_approval_statuses(), true)) {
        update_user_meta($user_id, 'svdp_approval_status', $approval_status);
    }

    $account_scope = sanitize_text_field($state['account_scope'] ?? '');
    if (in_array($account_scope, get_allowed_account_scopes(), true)) {
        update_user_meta($user_id, 'svdp_account_scope', $account_scope);
    }

    if (array_key_exists('role_profiles', $state)) {
        update_user_meta(
            $user_id,
            'svdp_role_profiles',
            normalize_role_profiles($state['role_profiles'])
        );
    }

    if (array_key_exists('onboarding_completed', $state)) {
        update_user_meta($user_id, 'svdp_onboarding_completed', (bool) $state['onboarding_completed']);
    }

    if ($account_scope === 'district') {
        update_user_meta($user_id, 'svdp_conference_id', 0);
    } elseif ($account_scope === 'conference') {
        $conference_id = (int) ($state['conference_id'] ?? 0);
        update_user_meta($user_id, 'svdp_conference_id', $conference_id > 0 ? $conference_id : 0);
    }

    update_user_meta($user_id, 'svdp_last_login', current_time('mysql'));

    return [
        'success' => true,
        'gate' => get_portal_access_state($user_id),
    ];
}
