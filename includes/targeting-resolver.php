<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_allowed_role_profiles() {
    return [
        'member',
        'president',
        'executive_leadership',
        'spiritual_advisor',
        'assistance_line',
        'district_staff',
        'district_announcements_editor',
        'district_editor',
        'district_admin',
    ];
}

function normalize_user_object($user) {
    if (is_object($user) && isset($user->ID)) {
        return $user;
    }

    if (is_numeric($user) && (int) $user > 0) {
        return get_userdata((int) $user);
    }

    return false;
}

function normalize_role_profiles($profiles) {
    if (!is_array($profiles)) {
        return [];
    }

    $allowed = array_flip(get_allowed_role_profiles());
    $normalized = [];

    foreach ($profiles as $profile) {
        $profile = (string) $profile;
        if ($profile !== '' && isset($allowed[$profile])) {
            $normalized[] = $profile;
        }
    }

    return array_values(array_unique($normalized));
}

function build_normalized_user_context($user) {
    $user_object = normalize_user_object($user);

    if (!$user_object) {
        return [
            'user_id' => 0,
            'approval_status' => '',
            'account_scope' => '',
            'conference_id' => 0,
            'role_profiles' => [],
            'conference_flags' => [],
            'calendar_feed_token' => '',
        ];
    }

    $user_id = (int) $user_object->ID;
    $account_scope = (string) get_user_meta($user_id, 'svdp_account_scope', true);
    $conference_id = (int) get_user_meta($user_id, 'svdp_conference_id', true);

    return [
        'user_id' => $user_id,
        'approval_status' => (string) get_user_meta($user_id, 'svdp_approval_status', true),
        'account_scope' => $account_scope,
        'conference_id' => $conference_id,
        'role_profiles' => normalize_role_profiles(get_user_meta($user_id, 'svdp_role_profiles', true)),
        'conference_flags' => $account_scope === 'conference' ? get_conference_flags_for_conference($conference_id) : [],
        'calendar_feed_token' => (string) get_user_meta($user_id, 'svdp_calendar_feed_token', true),
    ];
}

function current_time_for_resolver() {
    return current_time('mysql');
}

function is_within_publish_window(array $object_meta, $now) {
    $start = (string) ($object_meta['svdp_publish_start'] ?? '');
    $end = (string) ($object_meta['svdp_publish_end'] ?? '');

    if ($start !== '' && strcmp($now, $start) < 0) {
        return false;
    }

    if ($end !== '' && strcmp($now, $end) > 0) {
        return false;
    }

    return true;
}

function does_scope_match(array $context, array $object_meta) {
    $scope = $object_meta['svdp_scope'] ?? '';
    $account_scope = $context['account_scope'] ?? '';

    if ($scope === 'both') {
        return in_array($account_scope, ['conference', 'district'], true);
    }

    return $scope !== '' && $scope === $account_scope;
}

function does_audience_match(array $context, array $object_meta) {
    $audiences = $object_meta['svdp_audience_profiles'] ?? [];

    if (!is_array($audiences) || $audiences === []) {
        return true;
    }

    return array_intersect($context['role_profiles'] ?? [], $audiences) !== [];
}

function does_conference_targeting_match(array $context, array $object_meta) {
    $mode = $object_meta['svdp_target_conference_mode'] ?? 'none';
    $scope = $object_meta['svdp_scope'] ?? '';
    $conference_id = (int) ($context['conference_id'] ?? 0);

    switch ($mode) {
        case 'none':
            return true;
        case 'district_only':
            return ($context['account_scope'] ?? '') === 'district' && $scope === 'district';
        case 'all':
            return ($context['account_scope'] ?? '') === 'conference' && $conference_id > 0;
        case 'selected':
            return ($context['account_scope'] ?? '') === 'conference'
                && in_array($conference_id, array_map('intval', $object_meta['svdp_target_conference_ids'] ?? []), true);
        case 'group_flags':
            return ($context['account_scope'] ?? '') === 'conference'
                && array_intersect($context['conference_flags'] ?? [], $object_meta['svdp_target_group_flags'] ?? []) !== [];
        default:
            return false;
    }
}

function user_can_view_targeted_object($context, array $object_meta) {
    if (empty($context['user_id'])) {
        return false;
    }

    if (($context['approval_status'] ?? '') !== 'approved') {
        return false;
    }

    if (empty($object_meta['svdp_is_active'])) {
        return false;
    }

    if (!is_within_publish_window($object_meta, current_time_for_resolver())) {
        return false;
    }

    if (!does_scope_match($context, $object_meta)) {
        return false;
    }

    if (!does_audience_match($context, $object_meta)) {
        return false;
    }

    return does_conference_targeting_match($context, $object_meta);
}
