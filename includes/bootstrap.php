<?php
if (!defined('ABSPATH')) {
    exit;
}

$svdp_includes = [
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

foreach ($svdp_includes as $file) {
    $path = SVDP_PORTAL_DIR . 'includes/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
