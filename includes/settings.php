<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_branding_logo_option_key() {
    return 'vincentian_hub_logo_attachment_id';
}

function get_branding_settings_page_slug() {
    return 'vincentian-hub-settings';
}

function get_branding_settings_capability() {
    return 'svdp_manage_settings';
}

function get_branding_logo_context() {
    $attachment_id = (int) get_option(get_branding_logo_option_key(), 0);

    return [
        'attachment_id' => $attachment_id,
        'logo_url' => '',
        'fallback_text' => 'Vincentian Hub',
    ];
}

function update_branding_logo_attachment_id($attachment_id) {
    if (!current_user_can(get_branding_settings_capability())) {
        return false;
    }

    update_option(get_branding_logo_option_key(), (int) $attachment_id);

    return true;
}
