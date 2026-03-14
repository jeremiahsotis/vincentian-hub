<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\\register_dashboard_routes', 20);
add_action('init', __NAMESPACE__ . '\\register_document_routes', 21);
add_action('init', __NAMESPACE__ . '\\register_event_and_calendar_routes', 22);

function register_dashboard_routes() {
    add_rewrite_tag('%svdp_dashboard_kind%', '([^&]+)');
    add_rewrite_tag('%svdp_conference_name%', '([^&]+)');

    add_rewrite_rule(
        '^district-resources/district/?$',
        'index.php?svdp_dashboard_kind=district',
        'top'
    );

    add_rewrite_rule(
        '^district-resources/([^/]+)/?$',
        'index.php?svdp_dashboard_kind=conference&svdp_conference_name=$matches[1]',
        'top'
    );
}

function register_document_routes() {
    add_rewrite_tag('%svdp_doc_slug%', '([^&]+)');
    add_rewrite_tag('%svdp_document_action%', '([^&]+)');

    add_rewrite_rule(
        '^resource-library/([^/]+)/?$',
        'index.php?svdp_doc_slug=$matches[1]',
        'top'
    );
}

function register_event_and_calendar_routes() {
    add_rewrite_tag('%svdp_event_slug%', '([^&]+)');
    add_rewrite_tag('%svdp_calendar_token%', '([^&]+)');
    add_rewrite_tag('%svdp_calendar_event_id%', '([0-9]+)');

    add_rewrite_rule(
        '^events/([^/]+)/?$',
        'index.php?svdp_event_slug=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^portal-calendar/feed/([^/]+)/?$',
        'index.php?svdp_calendar_token=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^portal-calendar/event/([0-9]+)/download/?$',
        'index.php?svdp_calendar_event_id=$matches[1]',
        'top'
    );
}

function resolve_dashboard_route_from_path($path) {
    $path = trim((string) $path);

    if (preg_match('#^/district-resources/district/?$#', $path)) {
        return [
            'dashboard_kind' => 'district',
            'conference_name' => '',
        ];
    }

    if (preg_match('#^/district-resources/([^/]+)/?$#', $path, $matches)) {
        return [
            'dashboard_kind' => 'conference',
            'conference_name' => normalize_conference_page_slug($matches[1]),
        ];
    }

    return [];
}

function resolve_document_route_from_path($path) {
    $parts = parse_url((string) $path);
    $route_path = $parts['path'] ?? '';
    $query = [];

    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }

    if (preg_match('#^/resource-library/([^/]+)/?$#', $route_path, $matches)) {
        return [
            'doc_slug' => sanitize_text_field($matches[1]),
            'document_action' => sanitize_text_field((string) ($query['document_action'] ?? 'detail')),
        ];
    }

    return [];
}

function resolve_event_route_from_path($path) {
    if (preg_match('#^/events/([^/]+)/?$#', (string) $path, $matches)) {
        return [
            'event_slug' => sanitize_text_field($matches[1]),
        ];
    }

    return [];
}

function resolve_calendar_feed_route_from_path($path) {
    if (preg_match('#^/portal-calendar/feed/([^/]+)/?$#', (string) $path, $matches)) {
        return [
            'token' => sanitize_text_field($matches[1]),
        ];
    }

    return [];
}

function resolve_event_export_route_from_path($path) {
    $parts = parse_url((string) $path);
    $route_path = $parts['path'] ?? '';
    $query = [];

    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }

    if (preg_match('#^/portal-calendar/event/([0-9]+)/download/?$#', $route_path, $matches)) {
        return [
            'event_id' => (int) $matches[1],
            'token' => sanitize_text_field((string) ($query['token'] ?? '')),
        ];
    }

    return [];
}

function render_gate_template_response($template_name, array $context) {
    $template_path = SVDP_PORTAL_DIR . 'templates/' . $template_name . '.php';

    if (!file_exists($template_path)) {
        return '';
    }

    ob_start();
    include $template_path;
    return (string) ob_get_clean();
}

function handle_dashboard_route_request($user, array $route) {
    $template = get_portal_gate_template($user);
    if ($template !== '') {
        $context = get_gate_template_context($user);

        return [
            'status' => 403,
            'template' => $template,
            'dashboard_kind' => '',
            'body' => render_gate_template_response($template, $context),
        ];
    }

    $visibility_context = build_visibility_context_for_user($user);
    $dashboard_kind = (string) ($route['dashboard_kind'] ?? '');

    if ($dashboard_kind === 'district') {
        if (($visibility_context['account_scope'] ?? '') !== 'district') {
            return [
                'status' => 403,
                'template' => 'forbidden',
                'dashboard_kind' => 'district',
                'body' => '',
            ];
        }

        $dataset = build_dashboard_dataset($user, [
            'dashboard_kind' => 'district',
            'conference_context' => [],
        ]);

        return [
            'status' => 200,
            'template' => 'dashboard-district',
            'dashboard_kind' => 'district',
            'body' => render_dashboard_dataset($dataset),
        ];
    }

    if ($dashboard_kind === 'conference') {
        $conference_context = get_conference_context_by_page_slug($route['conference_name'] ?? '');

        if ($conference_context === []) {
            return [
                'status' => 404,
                'template' => 'not-found',
                'dashboard_kind' => 'conference',
                'body' => '',
            ];
        }

        if (($visibility_context['account_scope'] ?? '') !== 'conference'
            || (int) ($visibility_context['conference_id'] ?? 0) !== (int) $conference_context['conference_id']) {
            return [
                'status' => 403,
                'template' => 'forbidden',
                'dashboard_kind' => 'conference',
                'body' => '',
            ];
        }

        $dataset = build_dashboard_dataset($user, [
            'dashboard_kind' => 'conference',
            'conference_context' => $conference_context,
        ]);

        return [
            'status' => 200,
            'template' => 'dashboard-conference',
            'dashboard_kind' => 'conference',
            'body' => render_dashboard_dataset($dataset),
        ];
    }

    return [
        'status' => 404,
        'template' => 'not-found',
        'dashboard_kind' => '',
        'body' => '',
    ];
}

function handle_document_route_request($user, array $route) {
    $template = get_portal_gate_template($user);
    if ($template !== '') {
        $context = get_gate_template_context($user);

        return [
            'status' => 403,
            'template' => $template,
            'mode' => '',
            'body' => render_gate_template_response($template, $context),
        ];
    }

    $document = get_document_post_by_slug($route['doc_slug'] ?? '');
    if (!$document || !isset($document->ID)) {
        return [
            'status' => 404,
            'template' => 'not-found',
            'mode' => '',
            'body' => '',
        ];
    }

    if (!user_can_access_document($user, (int) $document->ID)) {
        return [
            'status' => 403,
            'template' => 'forbidden',
            'mode' => '',
            'body' => '',
        ];
    }

    return build_document_delivery_response((int) $document->ID, $route['document_action'] ?? 'detail');
}

function handle_event_route_request($user, array $route) {
    $template = get_portal_gate_template($user);
    if ($template !== '') {
        $context = get_gate_template_context($user);

        return [
            'status' => 403,
            'template' => $template,
            'body' => render_gate_template_response($template, $context),
        ];
    }

    $event = get_event_post_by_slug($route['event_slug'] ?? '');
    if (!$event || !isset($event->ID)) {
        return [
            'status' => 404,
            'template' => 'not-found',
            'body' => '',
        ];
    }

    if (!user_can_access_event($user, (int) $event->ID)) {
        return [
            'status' => 403,
            'template' => 'forbidden',
            'body' => '',
        ];
    }

    return build_event_detail_response((int) $event->ID);
}

function handle_calendar_feed_request(array $route) {
    return build_calendar_feed_response($route['token'] ?? '');
}

function handle_event_export_request(array $route) {
    return build_single_event_export_response((int) ($route['event_id'] ?? 0), $route['token'] ?? '');
}
