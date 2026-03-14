<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_calendar_feed_user_by_token($token) {
    $token = sanitize_text_field((string) $token);

    if ($token === '') {
        return 0;
    }

    foreach ($GLOBALS['svdp_test_users'] ?? [] as $user_id => $user) {
        if ((string) get_user_meta((int) $user_id, 'svdp_calendar_feed_token', true) === $token) {
            return (int) $user_id;
        }
    }

    return 0;
}

function get_visible_calendar_events_for_user($user_id) {
    $events = [];

    foreach (get_posts([
        'post_type' => 'svdp_event',
        'post_status' => 'publish',
    ]) as $event) {
        if (!isset($event->ID)) {
            continue;
        }

        $event_id = (int) $event->ID;
        if (!user_can_access_event($user_id, $event_id)) {
            continue;
        }

        if (empty(get_post_meta($event_id, 'svdp_show_in_calendar', true))) {
            continue;
        }

        if (empty(get_post_meta($event_id, 'svdp_event_calendar_export_enabled', true))) {
            continue;
        }

        $events[] = [
            'event_id' => $event_id,
            'title' => (string) ($event->post_title ?? ''),
            'start' => (string) get_post_meta($event_id, 'svdp_event_start', true),
            'end' => (string) get_post_meta($event_id, 'svdp_event_end', true),
        ];
    }

    return $events;
}

function build_calendar_feed_response($token) {
    $user_id = get_calendar_feed_user_by_token($token);

    if ($user_id <= 0) {
        return [
            'status' => 403,
            'events' => [],
            'body' => '',
        ];
    }

    return [
        'status' => 200,
        'events' => get_visible_calendar_events_for_user($user_id),
        'body' => '',
    ];
}

function render_single_event_ics($event_id) {
    $context = build_event_detail_context((int) $event_id);

    return "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nSUMMARY:" . ($context['title'] ?? '') . "\nEND:VEVENT\nEND:VCALENDAR";
}

function build_single_event_export_response($event_id, $token) {
    $user_id = get_calendar_feed_user_by_token($token);

    if ($user_id <= 0 || !user_can_access_event($user_id, (int) $event_id)) {
        return [
            'status' => 403,
            'body' => '',
        ];
    }

    if (empty(get_post_meta((int) $event_id, 'svdp_event_calendar_export_enabled', true))) {
        return [
            'status' => 403,
            'body' => '',
        ];
    }

    return [
        'status' => 200,
        'body' => render_single_event_ics((int) $event_id),
    ];
}
