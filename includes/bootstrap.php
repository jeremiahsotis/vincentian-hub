<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_include_files(): array
{
    return [
        'capabilities.php',
        'roles.php',
        'post-types.php',
        'taxonomies.php',
        'meta-registration.php',
        'admin-menu.php',
        'user-meta.php',
        'directory-table.php',
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

foreach (vincentian_hub_include_files() as $file) {
    $path = VINCENTIAN_HUB_DIR . 'includes/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

function vincentian_hub_activate(): void
{
    vincentian_hub_register_capabilities();
    vincentian_hub_register_roles();
    vincentian_hub_register_post_types();
    vincentian_hub_register_taxonomies();
    vincentian_hub_register_post_meta_keys();
    vincentian_hub_register_user_meta_keys();
    vincentian_hub_maybe_create_directory_table();

    if (function_exists('flush_rewrite_rules')) {
        flush_rewrite_rules();
    }
}

function vincentian_hub_deactivate(): void
{
    if (function_exists('flush_rewrite_rules')) {
        flush_rewrite_rules();
    }
}
