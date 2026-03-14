<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\\register_dashboard_routes', 20);

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
