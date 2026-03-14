<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_drive_import_capability() {
    return 'svdp_manage_drive_imports';
}

function get_drive_import_shared_targeting_keys() {
    return [
        'svdp_scope',
        'svdp_audience_profiles',
        'svdp_target_conference_mode',
        'svdp_target_conference_ids',
        'svdp_target_group_flags',
        'svdp_is_active',
        'svdp_publish_start',
        'svdp_publish_end',
    ];
}

function import_drive_document_record(array $record) {
    if (!current_user_can(get_drive_import_capability())) {
        return 0;
    }

    $post_id = wp_insert_post([
        'post_type' => 'svdp_doc',
        'post_status' => 'publish',
        'post_title' => sanitize_text_field($record['title'] ?? ''),
        'post_content' => (string) ($record['content'] ?? ''),
        'post_name' => sanitize_text_field($record['slug'] ?? ''),
    ]);

    if ($post_id <= 0) {
        return 0;
    }

    update_post_meta($post_id, 'svdp_doc_source', 'google_drive');
    update_post_meta($post_id, 'svdp_drive_file_id', sanitize_text_field($record['drive_file_id'] ?? ''));
    update_post_meta($post_id, 'svdp_drive_parent_ref', sanitize_text_field($record['drive_parent_ref'] ?? ''));
    update_post_meta($post_id, 'svdp_doc_preview_type', sanitize_text_field($record['preview_type'] ?? 'pdf'));
    update_post_meta($post_id, 'svdp_doc_local_cache_path', sanitize_text_field($record['local_cache_path'] ?? ''));

    $allowed_shared_targeting = array_flip(get_drive_import_shared_targeting_keys());

    foreach (($record['shared_targeting'] ?? []) as $meta_key => $meta_value) {
        if (!isset($allowed_shared_targeting[$meta_key])) {
            continue;
        }

        update_post_meta($post_id, $meta_key, $meta_value);
    }

    return (int) $post_id;
}
