<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

const FOUNDATION_VERSION_OPTION = 'vincentian_hub_foundation_version';

function get_include_files() {
    return [
        'capabilities.php',
        'roles.php',
        'post-types.php',
        'taxonomies.php',
        'meta-registration.php',
        'user-meta.php',
        'directory-table.php',
        'admin-menu.php',
        'auth-google.php',
        'onboarding.php',
        'conferences.php',
        'targeting-resolver.php',
        'dashboard-query.php',
        'dashboard-renderer.php',
        'announcements.php',
        'documents.php',
        'events.php',
        'calendar-ics.php',
        'routes.php',
        'permissions.php',
        'shortcode-context.php',
        'settings.php',
        'drive-imports.php',
    ];
}

foreach (get_include_files() as $file) {
    $path = SVDP_PORTAL_DIR . 'includes/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

function bootstrap() {
    add_action('init', __NAMESPACE__ . '\\register_foundation_components', 5);
    add_action('init', __NAMESPACE__ . '\\maybe_upgrade_foundation', 1);
    add_action('admin_menu', __NAMESPACE__ . '\\register_admin_menus');
}

function register_foundation_components() {
    register_post_types();
    register_taxonomies();
    register_object_meta();
    register_user_meta_keys();
}

function activate_plugin() {
    register_roles();
    grant_administrator_capabilities();
    register_post_types();
    register_taxonomies();
    register_object_meta();
    register_user_meta_keys();
    create_directory_table();
    update_option(FOUNDATION_VERSION_OPTION, SVDP_PORTAL_VERSION);
    flush_rewrite_rules();
}

function deactivate_plugin() {
    flush_rewrite_rules();
}

function maybe_upgrade_foundation() {
    $current_version = get_option(FOUNDATION_VERSION_OPTION);

    if ($current_version === SVDP_PORTAL_VERSION) {
        grant_administrator_capabilities();
        return;
    }

    register_roles();
    grant_administrator_capabilities();
    create_directory_table();
    update_option(FOUNDATION_VERSION_OPTION, SVDP_PORTAL_VERSION);
}
