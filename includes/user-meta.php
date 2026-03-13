<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_required_user_meta_registry() {
    return [
        'svdp_account_scope' => 'string',
        'svdp_approval_status' => 'string',
        'svdp_conference_id' => 'integer',
        'svdp_role_profiles' => 'array',
        'svdp_phone' => 'string',
        'svdp_google_sub' => 'string',
        'svdp_directory_source' => 'string',
        'svdp_last_login' => 'string',
        'svdp_onboarding_completed' => 'boolean',
        'svdp_can_self_change_conference' => 'boolean',
        'svdp_calendar_feed_token' => 'string',
        'svdp_calendar_feed_token_rotated_at' => 'string',
        'svdp_admin_notes' => 'string',
    ];
}

function register_user_meta_keys() {
    foreach (get_required_user_meta_registry() as $meta_key => $type) {
        register_meta('user', $meta_key, build_user_meta_args($type));
    }
}

function build_user_meta_args($type) {
    $show_in_rest = false;

    if ($type === 'array') {
        $show_in_rest = [
            'schema' => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'default' => [],
            ],
        ];
    }

    return [
        'type' => $type === 'array' ? 'array' : $type,
        'single' => true,
        'show_in_rest' => $show_in_rest,
        'sanitize_callback' => get_user_meta_sanitize_callback($type),
        'auth_callback' => function () {
            return current_user_can('edit_users');
        },
    ];
}

function get_user_meta_sanitize_callback($type) {
    switch ($type) {
        case 'boolean':
            return function ($value) {
                return (bool) $value;
            };
        case 'integer':
            return function ($value) {
                return (int) $value;
            };
        case 'array':
            return function ($value) {
                if (!is_array($value)) {
                    return [];
                }

                return array_values(array_map('strval', $value));
            };
        default:
            return 'sanitize_text_field';
    }
}
