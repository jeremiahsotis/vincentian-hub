<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_event_targeting_meta($post_id) {
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

function get_event_post_by_slug($slug) {
    $slug = sanitize_text_field((string) $slug);

    foreach (get_posts([
        'post_type' => 'svdp_event',
        'post_status' => 'publish',
    ]) as $event) {
        if (($event->post_name ?? '') === $slug) {
            return $event;
        }
    }

    return null;
}

function get_event_post_by_id($event_id) {
    $event = get_post((int) $event_id);

    if (!$event || ($event->post_type ?? '') !== 'svdp_event') {
        return null;
    }

    return $event;
}

function user_can_access_event($user, $event_id) {
    return user_can_access_targeted_object($user, get_event_targeting_meta((int) $event_id));
}

function build_event_detail_context($event_id) {
    $event_id = (int) $event_id;
    $event = get_event_post_by_id($event_id);

    if (!$event) {
        return [];
    }

    return [
        'event_id' => $event_id,
        'title' => (string) ($event->post_title ?? ''),
        'content' => (string) ($event->post_content ?? ''),
        'event_type' => (string) get_post_meta($event_id, 'svdp_event_type', true),
        'event_status' => (string) get_post_meta($event_id, 'svdp_event_status', true),
        'event_start' => (string) get_post_meta($event_id, 'svdp_event_start', true),
        'event_end' => (string) get_post_meta($event_id, 'svdp_event_end', true),
    ];
}

function render_event_detail(array $context) {
    $template_path = SVDP_PORTAL_DIR . 'templates/event-detail.php';

    if (!file_exists($template_path)) {
        return '';
    }

    ob_start();
    include $template_path;
    return (string) ob_get_clean();
}

function build_event_detail_response($event_id) {
    $context = build_event_detail_context((int) $event_id);

    if ($context === []) {
        return [
            'status' => 404,
            'template' => 'not-found',
            'body' => '',
        ];
    }

    return [
        'status' => 200,
        'template' => 'event-detail',
        'body' => render_event_detail($context),
    ];
}
