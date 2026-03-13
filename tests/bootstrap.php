<?php

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

if (!defined('SVDP_PORTAL_VERSION')) {
    define('SVDP_PORTAL_VERSION', '0.1.0');
}

if (!defined('SVDP_PORTAL_DIR')) {
    define('SVDP_PORTAL_DIR', dirname(__DIR__) . '/');
}

if (!defined('SVDP_PORTAL_FILE')) {
    define('SVDP_PORTAL_FILE', dirname(__DIR__) . '/vincentian-hub.php');
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return dirname($file) . '/';
    }
}

if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url($file) {
        return 'http://example.test/' . basename(dirname($file)) . '/';
    }
}

if (!function_exists('add_action')) {
    function add_action() {
    }
}

if (!function_exists('register_activation_hook')) {
    function register_activation_hook() {
    }
}

if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook() {
    }
}

if (!function_exists('register_post_type')) {
    function register_post_type() {
    }
}

if (!function_exists('register_taxonomy')) {
    function register_taxonomy() {
    }
}

if (!function_exists('register_post_meta')) {
    function register_post_meta() {
    }
}

if (!function_exists('register_meta')) {
    function register_meta() {
    }
}

if (!function_exists('add_role')) {
    function add_role() {
    }
}

if (!function_exists('remove_role')) {
    function remove_role() {
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can() {
        return true;
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($value) {
        return trim((string) $value);
    }
}

if (!function_exists('__return_true')) {
    function __return_true() {
        return true;
    }
}

if (!function_exists('get_option')) {
    function get_option() {
        return null;
    }
}

if (!function_exists('update_option')) {
    function update_option() {
        return true;
    }
}

if (!function_exists('flush_rewrite_rules')) {
    function flush_rewrite_rules() {
    }
}

$GLOBALS['svdp_test_user_meta'] = [];
$GLOBALS['svdp_test_post_meta'] = [];
$GLOBALS['svdp_test_users'] = [];
$GLOBALS['svdp_test_now'] = null;

if (!function_exists('get_user_meta')) {
    function get_user_meta($user_id, $key = '', $single = false) {
        $meta = $GLOBALS['svdp_test_user_meta'][$user_id] ?? [];

        if ($key === '') {
            return $meta;
        }

        $value = $meta[$key] ?? ($single ? '' : []);

        return $single ? $value : [$value];
    }
}

if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false) {
        $meta = $GLOBALS['svdp_test_post_meta'][$post_id] ?? [];

        if ($key === '') {
            return $meta;
        }

        $value = $meta[$key] ?? ($single ? '' : []);

        return $single ? $value : [$value];
    }
}

if (!function_exists('get_userdata')) {
    function get_userdata($user_id) {
        return $GLOBALS['svdp_test_users'][$user_id] ?? false;
    }
}

if (!function_exists('current_time')) {
    function current_time(...$args) {
        return $GLOBALS['svdp_test_now'] ?? gmdate('Y-m-d H:i:s');
    }
}

$GLOBALS['wpdb'] = new class {
    public $prefix = 'wp_';

    public function get_charset_collate() {
        return 'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
    }
};

require_once dirname(__DIR__) . '/includes/capabilities.php';
require_once dirname(__DIR__) . '/includes/roles.php';
require_once dirname(__DIR__) . '/includes/post-types.php';
require_once dirname(__DIR__) . '/includes/taxonomies.php';
require_once dirname(__DIR__) . '/includes/meta-registration.php';
require_once dirname(__DIR__) . '/includes/user-meta.php';
require_once dirname(__DIR__) . '/includes/directory-table.php';
require_once dirname(__DIR__) . '/includes/conferences.php';
require_once dirname(__DIR__) . '/includes/targeting-resolver.php';
require_once dirname(__DIR__) . '/includes/permissions.php';
require_once dirname(__DIR__) . '/includes/bootstrap.php';
