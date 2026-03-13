<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_user_meta_registry(): array
{
    return [
        'svdp_account_scope' => ['type' => 'string', 'single' => true],
        'svdp_approval_status' => ['type' => 'string', 'single' => true],
        'svdp_conference_id' => ['type' => 'integer', 'single' => true],
        'svdp_role_profiles' => ['type' => 'array', 'single' => true],
        'svdp_phone' => ['type' => 'string', 'single' => true],
        'svdp_google_sub' => ['type' => 'string', 'single' => true],
        'svdp_directory_source' => ['type' => 'string', 'single' => true],
        'svdp_last_login' => ['type' => 'string', 'single' => true],
        'svdp_onboarding_completed' => ['type' => 'boolean', 'single' => true],
        'svdp_can_self_change_conference' => ['type' => 'boolean', 'single' => true],
        'svdp_calendar_feed_token' => ['type' => 'string', 'single' => true],
        'svdp_calendar_feed_token_rotated_at' => ['type' => 'string', 'single' => true],
        'svdp_admin_notes' => ['type' => 'string', 'single' => true],
    ];
}

function vincentian_hub_register_user_meta_keys(): void
{
    if (! function_exists('register_meta')) {
        return;
    }

    foreach (vincentian_hub_user_meta_registry() as $metaKey => $definition) {
        register_meta('user', $metaKey, [
            'single' => $definition['single'],
            'type' => $definition['type'],
            'show_in_rest' => false,
        ]);
    }
}

if (function_exists('add_action')) {
    add_action('init', 'vincentian_hub_register_user_meta_keys', 15);
}
