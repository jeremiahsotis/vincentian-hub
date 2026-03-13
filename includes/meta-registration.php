<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_shared_targeting_meta(): array
{
    return [
        'svdp_scope' => ['type' => 'string', 'single' => true],
        'svdp_audience_profiles' => ['type' => 'array', 'single' => true],
        'svdp_target_conference_mode' => ['type' => 'string', 'single' => true],
        'svdp_target_conference_ids' => ['type' => 'array', 'single' => true],
        'svdp_target_group_flags' => ['type' => 'array', 'single' => true],
        'svdp_is_active' => ['type' => 'boolean', 'single' => true],
        'svdp_publish_start' => ['type' => 'string', 'single' => true],
        'svdp_publish_end' => ['type' => 'string', 'single' => true],
    ];
}

function vincentian_hub_conference_meta(): array
{
    return [
        'svdp_conf_code' => ['type' => 'string', 'single' => true],
        'svdp_conf_page_slug' => ['type' => 'string', 'single' => true],
        'svdp_conf_linked_page_id' => ['type' => 'integer', 'single' => true],
        'svdp_conf_city' => ['type' => 'string', 'single' => true],
        'svdp_conf_county' => ['type' => 'string', 'single' => true],
        'svdp_conf_is_urban' => ['type' => 'boolean', 'single' => true],
        'svdp_conf_is_rural' => ['type' => 'boolean', 'single' => true],
        'svdp_conf_is_new_haven' => ['type' => 'boolean', 'single' => true],
        'svdp_conf_is_allen_county' => ['type' => 'boolean', 'single' => true],
        'svdp_conf_active' => ['type' => 'boolean', 'single' => true],
        'svdp_conf_map_url' => ['type' => 'string', 'single' => true],
        'svdp_conf_resource_context' => ['type' => 'string', 'single' => true],
        'svdp_conf_voucher_context' => ['type' => 'string', 'single' => true],
        'svdp_conf_primary_contact_name' => ['type' => 'string', 'single' => true],
        'svdp_conf_primary_contact_email' => ['type' => 'string', 'single' => true],
        'svdp_conf_primary_contact_phone' => ['type' => 'string', 'single' => true],
        'svdp_conf_help_text_override' => ['type' => 'string', 'single' => true],
    ];
}

function vincentian_hub_object_meta_registry(): array
{
    return [
        'svdp_dash_item' => [
            'svdp_item_type' => ['type' => 'string', 'single' => true],
            'svdp_item_url' => ['type' => 'string', 'single' => true],
            'svdp_item_shortcode' => ['type' => 'string', 'single' => true],
            'svdp_item_document_id' => ['type' => 'integer', 'single' => true],
            'svdp_item_linked_item_ids' => ['type' => 'array', 'single' => true],
            'svdp_item_open_mode' => ['type' => 'string', 'single' => true],
            'svdp_section_key' => ['type' => 'string', 'single' => true],
            'svdp_priority' => ['type' => 'string', 'single' => true],
            'svdp_display_style' => ['type' => 'string', 'single' => true],
            'svdp_sort_order' => ['type' => 'integer', 'single' => true],
            'svdp_auto_inject_conference_context' => ['type' => 'boolean', 'single' => true],
            'svdp_featured' => ['type' => 'boolean', 'single' => true],
        ],
        'svdp_announcement' => [
            'svdp_announcement_type' => ['type' => 'string', 'single' => true],
            'svdp_priority' => ['type' => 'string', 'single' => true],
            'svdp_display_placement' => ['type' => 'string', 'single' => true],
            'svdp_cta_label' => ['type' => 'string', 'single' => true],
            'svdp_cta_url' => ['type' => 'string', 'single' => true],
            'svdp_created_by_profile' => ['type' => 'string', 'single' => true],
            'svdp_internal_notes' => ['type' => 'string', 'single' => true],
            'svdp_featured' => ['type' => 'boolean', 'single' => true],
        ],
        'svdp_doc' => [
            'svdp_doc_source' => ['type' => 'string', 'single' => true],
            'svdp_drive_file_id' => ['type' => 'string', 'single' => true],
            'svdp_drive_parent_ref' => ['type' => 'string', 'single' => true],
            'svdp_drive_mime_type' => ['type' => 'string', 'single' => true],
            'svdp_drive_modified_time' => ['type' => 'string', 'single' => true],
            'svdp_drive_version' => ['type' => 'string', 'single' => true],
            'svdp_doc_preview_type' => ['type' => 'string', 'single' => true],
            'svdp_doc_local_cache_path' => ['type' => 'string', 'single' => true],
            'svdp_doc_thumbnail_path' => ['type' => 'string', 'single' => true],
            'svdp_doc_search_weight' => ['type' => 'integer', 'single' => true],
            'svdp_doc_featured' => ['type' => 'boolean', 'single' => true],
            'svdp_doc_plain_language_title' => ['type' => 'string', 'single' => true],
            'svdp_doc_help_text' => ['type' => 'string', 'single' => true],
            'svdp_doc_is_recently_updated' => ['type' => 'boolean', 'single' => true],
            'svdp_doc_force_download' => ['type' => 'boolean', 'single' => true],
            'svdp_doc_available_for_meeting_packets' => ['type' => 'boolean', 'single' => true],
        ],
        'svdp_event' => [
            'svdp_event_start' => ['type' => 'string', 'single' => true],
            'svdp_event_end' => ['type' => 'string', 'single' => true],
            'svdp_event_all_day' => ['type' => 'boolean', 'single' => true],
            'svdp_event_timezone' => ['type' => 'string', 'single' => true],
            'svdp_event_location_name' => ['type' => 'string', 'single' => true],
            'svdp_event_location_address' => ['type' => 'string', 'single' => true],
            'svdp_event_virtual_url' => ['type' => 'string', 'single' => true],
            'svdp_event_registration_url' => ['type' => 'string', 'single' => true],
            'svdp_event_type' => ['type' => 'string', 'single' => true],
            'svdp_event_status' => ['type' => 'string', 'single' => true],
            'svdp_show_on_dashboard' => ['type' => 'boolean', 'single' => true],
            'svdp_show_in_calendar' => ['type' => 'boolean', 'single' => true],
            'svdp_show_in_whats_new' => ['type' => 'boolean', 'single' => true],
            'svdp_featured' => ['type' => 'boolean', 'single' => true],
            'svdp_priority' => ['type' => 'string', 'single' => true],
            'svdp_sort_order' => ['type' => 'integer', 'single' => true],
            'svdp_related_document_ids' => ['type' => 'array', 'single' => true],
            'svdp_meeting_packet_document_ids' => ['type' => 'array', 'single' => true],
            'svdp_related_announcement_ids' => ['type' => 'array', 'single' => true],
            'svdp_event_uid' => ['type' => 'string', 'single' => true],
            'svdp_event_last_modified_utc' => ['type' => 'string', 'single' => true],
            'svdp_event_calendar_export_enabled' => ['type' => 'boolean', 'single' => true],
            'svdp_event_single_add_enabled' => ['type' => 'boolean', 'single' => true],
        ],
    ];
}

function vincentian_hub_meta_args(array $definition): array
{
    return [
        'single' => $definition['single'],
        'type' => $definition['type'],
        'show_in_rest' => false,
    ];
}

function vincentian_hub_register_post_meta_keys(): void
{
    if (! function_exists('register_post_meta')) {
        return;
    }

    foreach (vincentian_hub_conference_meta() as $metaKey => $definition) {
        register_post_meta('svdp_conf', $metaKey, vincentian_hub_meta_args($definition));
    }

    foreach (vincentian_hub_shared_targeting_meta() as $metaKey => $definition) {
        foreach (array_keys(vincentian_hub_object_meta_registry()) as $postType) {
            register_post_meta($postType, $metaKey, vincentian_hub_meta_args($definition));
        }
    }

    foreach (vincentian_hub_object_meta_registry() as $postType => $metaKeys) {
        foreach ($metaKeys as $metaKey => $definition) {
            register_post_meta($postType, $metaKey, vincentian_hub_meta_args($definition));
        }
    }
}

if (function_exists('add_action')) {
    add_action('init', 'vincentian_hub_register_post_meta_keys', 15);
}
