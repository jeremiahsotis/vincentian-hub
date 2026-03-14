<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_document_preview_types() {
    return [
        'pdf',
        'office_embed',
        'download_only',
        'html_summary',
    ];
}

function get_document_sources() {
    return [
        'google_drive',
        'local_upload',
        'external',
    ];
}

function get_document_targeting_meta($post_id) {
    return [
        'svdp_scope' => get_post_meta($post_id, 'svdp_scope', true),
        'svdp_audience_profiles' => get_post_meta($post_id, 'svdp_audience_profiles', true),
        'svdp_target_conference_mode' => get_post_meta($post_id, 'svdp_target_conference_mode', true),
        'svdp_target_conference_ids' => get_post_meta($post_id, 'svdp_target_conference_ids', true),
        'svdp_target_group_flags' => get_post_meta($post_id, 'svdp_target_group_flags', true),
        'svdp_is_active' => get_post_meta($post_id, 'svdp_is_active', true),
        'svdp_publish_start' => get_post_meta($post_id, 'svdp_publish_start', true),
        'svdp_publish_end' => get_post_meta($post_id, 'svdp_publish_end', true),
    ];
}

function get_document_post_by_slug($slug) {
    $slug = sanitize_text_field((string) $slug);

    foreach (get_posts([
        'post_type' => 'svdp_doc',
        'post_status' => 'publish',
    ]) as $document) {
        if (($document->post_name ?? '') === $slug) {
            return $document;
        }
    }

    return null;
}

function normalize_document_mode($mode, $document_id) {
    $mode = sanitize_text_field((string) $mode);
    if (!in_array($mode, ['detail', 'preview', 'download'], true)) {
        $mode = 'detail';
    }

    $preview_type = (string) get_post_meta((int) $document_id, 'svdp_doc_preview_type', true);
    $force_download = (bool) get_post_meta((int) $document_id, 'svdp_doc_force_download', true);

    if ($mode === 'preview' && ($preview_type === 'download_only' || $force_download)) {
        return 'download';
    }

    return $mode;
}

function user_can_access_document($user, $document_id) {
    return user_can_access_targeted_object($user, get_document_targeting_meta((int) $document_id));
}

function build_document_detail_context($document_id) {
    $document_id = (int) $document_id;
    $document = get_post($document_id);

    if (!$document) {
        return [];
    }

    return [
        'document_id' => $document_id,
        'title' => (string) ($document->post_title ?? ''),
        'content' => (string) ($document->post_content ?? ''),
        'doc_source' => (string) get_post_meta($document_id, 'svdp_doc_source', true),
        'preview_type' => (string) get_post_meta($document_id, 'svdp_doc_preview_type', true),
        'force_download' => (bool) get_post_meta($document_id, 'svdp_doc_force_download', true),
        'file_path' => (string) get_post_meta($document_id, 'svdp_doc_local_cache_path', true),
    ];
}

function build_document_delivery_response($document_id, $mode) {
    $context = build_document_detail_context((int) $document_id);

    if ($context === []) {
        return [
            'status' => 404,
            'template' => 'not-found',
            'mode' => '',
            'body' => '',
        ];
    }

    $mode = normalize_document_mode($mode, (int) $document_id);

    if ($mode === 'detail') {
        return [
            'status' => 200,
            'template' => 'document-detail',
            'mode' => 'detail',
            'body' => render_document_detail($context),
        ];
    }

    return [
        'status' => 200,
        'template' => 'document-file',
        'mode' => $mode,
        'file_path' => $context['file_path'],
        'body' => '',
    ];
}

function render_document_detail(array $context) {
    $template_path = SVDP_PORTAL_DIR . 'templates/document-detail.php';

    if (!file_exists($template_path)) {
        return '';
    }

    ob_start();
    include $template_path;
    return (string) ob_get_clean();
}
