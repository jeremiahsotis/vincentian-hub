<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_shared_targeting_meta_keys() {
    return [
        'svdp_scope' => 'string',
        'svdp_audience_profiles' => 'array',
        'svdp_target_conference_mode' => 'string',
        'svdp_target_conference_ids' => 'array',
        'svdp_target_group_flags' => 'array',
        'svdp_is_active' => 'boolean',
        'svdp_publish_start' => 'string',
        'svdp_publish_end' => 'string',
    ];
}

function get_object_meta_registry() {
    return [
        'svdp_conf' => [
            'svdp_conf_code' => 'string',
            'svdp_conf_page_slug' => 'string',
            'svdp_conf_linked_page_id' => 'integer',
            'svdp_conf_city' => 'string',
            'svdp_conf_county' => 'string',
            'svdp_conf_is_urban' => 'boolean',
            'svdp_conf_is_rural' => 'boolean',
            'svdp_conf_is_new_haven' => 'boolean',
            'svdp_conf_is_allen_county' => 'boolean',
            'svdp_conf_active' => 'boolean',
            'svdp_conf_map_url' => 'string',
            'svdp_conf_resource_context' => 'string',
            'svdp_conf_voucher_context' => 'string',
            'svdp_conf_primary_contact_name' => 'string',
            'svdp_conf_primary_contact_email' => 'string',
            'svdp_conf_primary_contact_phone' => 'string',
            'svdp_conf_help_text_override' => 'string',
        ],
        'svdp_dash_item' => array_merge(get_shared_targeting_meta_keys(), [
            'svdp_item_type' => 'string',
            'svdp_item_url' => 'string',
            'svdp_item_shortcode' => 'string',
            'svdp_item_document_id' => 'integer',
            'svdp_item_linked_item_ids' => 'array',
            'svdp_item_open_mode' => 'string',
            'svdp_section_key' => 'string',
            'svdp_priority' => 'string',
            'svdp_display_style' => 'string',
            'svdp_sort_order' => 'integer',
            'svdp_auto_inject_conference_context' => 'boolean',
            'svdp_featured' => 'boolean',
        ]),
        'svdp_announcement' => array_merge(get_shared_targeting_meta_keys(), [
            'svdp_announcement_type' => 'string',
            'svdp_priority' => 'string',
            'svdp_display_placement' => 'string',
            'svdp_cta_label' => 'string',
            'svdp_cta_url' => 'string',
            'svdp_created_by_profile' => 'string',
            'svdp_internal_notes' => 'string',
            'svdp_featured' => 'boolean',
        ]),
        'svdp_doc' => array_merge(get_shared_targeting_meta_keys(), [
            'svdp_doc_source' => 'string',
            'svdp_drive_file_id' => 'string',
            'svdp_drive_parent_ref' => 'string',
            'svdp_drive_mime_type' => 'string',
            'svdp_drive_modified_time' => 'string',
            'svdp_drive_version' => 'string',
            'svdp_doc_preview_type' => 'string',
            'svdp_doc_local_cache_path' => 'string',
            'svdp_doc_thumbnail_path' => 'string',
            'svdp_doc_search_weight' => 'integer',
            'svdp_doc_featured' => 'boolean',
            'svdp_doc_plain_language_title' => 'string',
            'svdp_doc_help_text' => 'string',
            'svdp_doc_is_recently_updated' => 'boolean',
            'svdp_doc_force_download' => 'boolean',
            'svdp_doc_available_for_meeting_packets' => 'boolean',
        ]),
        'svdp_event' => array_merge(get_shared_targeting_meta_keys(), [
            'svdp_event_start' => 'string',
            'svdp_event_end' => 'string',
            'svdp_event_all_day' => 'boolean',
            'svdp_event_timezone' => 'string',
            'svdp_event_location_name' => 'string',
            'svdp_event_location_address' => 'string',
            'svdp_event_virtual_url' => 'string',
            'svdp_event_registration_url' => 'string',
            'svdp_event_type' => 'string',
            'svdp_event_status' => 'string',
            'svdp_show_on_dashboard' => 'boolean',
            'svdp_show_in_calendar' => 'boolean',
            'svdp_show_in_whats_new' => 'boolean',
            'svdp_featured' => 'boolean',
            'svdp_priority' => 'string',
            'svdp_sort_order' => 'integer',
            'svdp_related_document_ids' => 'array',
            'svdp_meeting_packet_document_ids' => 'array',
            'svdp_related_announcement_ids' => 'array',
            'svdp_event_uid' => 'string',
            'svdp_event_last_modified_utc' => 'string',
            'svdp_event_calendar_export_enabled' => 'boolean',
            'svdp_event_single_add_enabled' => 'boolean',
        ]),
    ];
}

function register_object_meta() {
    foreach (get_object_meta_registry() as $object_type => $meta_keys) {
        foreach ($meta_keys as $meta_key => $type) {
            register_post_meta($object_type, $meta_key, build_meta_args($type));
        }
    }
}

function build_meta_args($type) {
    $show_in_rest = true;

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
        'auth_callback' => '__return_true',
        'sanitize_callback' => get_sanitize_callback($type),
    ];
}

function get_sanitize_callback($type) {
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
