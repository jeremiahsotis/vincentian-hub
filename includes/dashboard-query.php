<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_dashboard_items() {
    return get_posts([
        'post_type' => 'svdp_dash_item',
        'post_status' => 'publish',
    ]);
}

function get_dashboard_item_targeting_meta($post_id) {
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

function build_dashboard_dataset($user, array $route_context) {
    $visibility_context = build_visibility_context_for_user($user);
    $items = [];

    foreach (get_dashboard_items() as $item) {
        if (!isset($item->ID) || !user_can_view_targeted_object($visibility_context, get_dashboard_item_targeting_meta((int) $item->ID))) {
            continue;
        }

        $items[] = [
            'id' => (int) $item->ID,
            'title' => (string) ($item->post_title ?? ''),
            'content' => (string) ($item->post_content ?? ''),
        ];
    }

    return [
        'dashboard_kind' => (string) ($route_context['dashboard_kind'] ?? ''),
        'conference_context' => $route_context['conference_context'] ?? [],
        'visibility_context' => $visibility_context,
        'items' => $items,
    ];
}
