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
    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $GLOBALS['svdp_test_actions'][] = [
            'hook_name' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }
}

if (!function_exists('add_menu_page')) {
    function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback = null) {
        $GLOBALS['svdp_test_admin_menus'][] = [
            'page_title' => $page_title,
            'menu_title' => $menu_title,
            'capability' => $capability,
            'menu_slug' => $menu_slug,
            'callback' => $callback,
            'parent' => null,
        ];

        return $menu_slug;
    }
}

if (!function_exists('add_submenu_page')) {
    function add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback = null) {
        $GLOBALS['svdp_test_admin_menus'][] = [
            'page_title' => $page_title,
            'menu_title' => $menu_title,
            'capability' => $capability,
            'menu_slug' => $menu_slug,
            'callback' => $callback,
            'parent' => $parent_slug,
        ];

        return $menu_slug;
    }
}

if (!function_exists('add_rewrite_tag')) {
    function add_rewrite_tag($tag, $regex) {
        $GLOBALS['svdp_test_rewrite_tags'][] = [
            'tag' => $tag,
            'regex' => $regex,
        ];
    }
}

if (!function_exists('add_rewrite_rule')) {
    function add_rewrite_rule($regex, $query, $after = 'bottom') {
        $GLOBALS['svdp_test_rewrite_rules'][] = [
            'regex' => $regex,
            'query' => $query,
            'after' => $after,
        ];
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
    function add_role($role, $display_name, array $capabilities = []) {
        $GLOBALS['svdp_test_roles'][(string) $role] = new class($role, $display_name, $capabilities) {
            public $name;
            public $label;
            public $capabilities;

            public function __construct($name, $label, array $capabilities) {
                $this->name = $name;
                $this->label = $label;
                $this->capabilities = $capabilities;
            }

            public function add_cap($capability, $grant = true) {
                $this->capabilities[(string) $capability] = (bool) $grant;
            }

            public function remove_cap($capability) {
                unset($this->capabilities[(string) $capability]);
            }
        };

        return $GLOBALS['svdp_test_roles'][(string) $role];
    }
}

if (!function_exists('remove_role')) {
    function remove_role($role) {
        unset($GLOBALS['svdp_test_roles'][(string) $role]);
    }
}

if (!function_exists('get_role')) {
    function get_role($role) {
        return $GLOBALS['svdp_test_roles'][(string) $role] ?? null;
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can($capability = '') {
        if ($capability === '') {
            return true;
        }

        return !empty($GLOBALS['svdp_test_current_user_caps'][(string) $capability]);
    }
}

if (!function_exists('update_user_meta')) {
    function update_user_meta($user_id, $key, $value) {
        if (!isset($GLOBALS['svdp_test_user_meta'][$user_id])) {
            $GLOBALS['svdp_test_user_meta'][$user_id] = [];
        }

        $GLOBALS['svdp_test_user_meta'][$user_id][$key] = $value;

        return true;
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($value) {
        return trim((string) $value);
    }
}

if (!function_exists('esc_html')) {
    function esc_html($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_url')) {
    function esc_url($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post($value) {
        return (string) $value;
    }
}

if (!function_exists('__return_true')) {
    function __return_true() {
        return true;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = null) {
        return $GLOBALS['svdp_test_options'][(string) $option] ?? $default;
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value) {
        $GLOBALS['svdp_test_options'][(string) $option] = $value;
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
$GLOBALS['svdp_test_posts'] = [];
$GLOBALS['svdp_test_now'] = null;
$GLOBALS['svdp_test_rewrite_tags'] = [];
$GLOBALS['svdp_test_rewrite_rules'] = [];
$GLOBALS['svdp_test_admin_menus'] = [];
$GLOBALS['svdp_test_actions'] = [];
$GLOBALS['svdp_test_current_user_caps'] = [];
$GLOBALS['svdp_test_options'] = [];
$GLOBALS['svdp_test_next_post_id'] = 2000;
$GLOBALS['svdp_test_roles'] = [
    'administrator' => new class('administrator', 'Administrator', []) {
        public $name;
        public $label;
        public $capabilities;

        public function __construct($name, $label, array $capabilities) {
            $this->name = $name;
            $this->label = $label;
            $this->capabilities = $capabilities;
        }

        public function add_cap($capability, $grant = true) {
            $this->capabilities[(string) $capability] = (bool) $grant;
        }

        public function remove_cap($capability) {
            unset($this->capabilities[(string) $capability]);
        }
    },
];

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

if (!function_exists('get_post')) {
    function get_post($post_id) {
        return $GLOBALS['svdp_test_posts'][$post_id] ?? null;
    }
}

if (!function_exists('get_posts')) {
    function get_posts(array $args = []) {
        $posts = array_values($GLOBALS['svdp_test_posts']);
        $post_type = $args['post_type'] ?? null;
        $meta_key = $args['meta_key'] ?? null;
        $meta_value = $args['meta_value'] ?? null;
        $post_status = $args['post_status'] ?? null;

        $posts = array_filter($posts, function ($post) use ($post_type, $post_status, $meta_key, $meta_value) {
            if ($post_type !== null && (($post->post_type ?? '') !== $post_type)) {
                return false;
            }

            if ($post_status !== null && (($post->post_status ?? '') !== $post_status)) {
                return false;
            }

            if ($meta_key !== null) {
                $value = get_post_meta((int) $post->ID, (string) $meta_key, true);
                if ((string) $value !== (string) $meta_value) {
                    return false;
                }
            }

            return true;
        });

        return array_values($posts);
    }
}

if (!function_exists('wp_insert_post')) {
    function wp_insert_post(array $postarr) {
        $post_id = (int) ($postarr['ID'] ?? 0);
        if ($post_id <= 0) {
            $post_id = (int) $GLOBALS['svdp_test_next_post_id']++;
        }

        $GLOBALS['svdp_test_posts'][$post_id] = (object) [
            'ID' => $post_id,
            'post_type' => $postarr['post_type'] ?? 'post',
            'post_status' => $postarr['post_status'] ?? 'draft',
            'post_title' => $postarr['post_title'] ?? '',
            'post_content' => $postarr['post_content'] ?? '',
            'post_name' => $postarr['post_name'] ?? '',
        ];

        return $post_id;
    }
}

if (!function_exists('update_post_meta')) {
    function update_post_meta($post_id, $meta_key, $meta_value) {
        if (!isset($GLOBALS['svdp_test_post_meta'][(int) $post_id])) {
            $GLOBALS['svdp_test_post_meta'][(int) $post_id] = [];
        }

        $GLOBALS['svdp_test_post_meta'][(int) $post_id][(string) $meta_key] = $meta_value;

        return true;
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
require_once dirname(__DIR__) . '/includes/auth-google.php';
require_once dirname(__DIR__) . '/includes/onboarding.php';
require_once dirname(__DIR__) . '/includes/conferences.php';
require_once dirname(__DIR__) . '/includes/targeting-resolver.php';
require_once dirname(__DIR__) . '/includes/permissions.php';
require_once dirname(__DIR__) . '/includes/shortcode-context.php';
require_once dirname(__DIR__) . '/includes/announcements.php';
require_once dirname(__DIR__) . '/includes/documents.php';
require_once dirname(__DIR__) . '/includes/events.php';
require_once dirname(__DIR__) . '/includes/calendar-ics.php';
require_once dirname(__DIR__) . '/includes/dashboard-query.php';
require_once dirname(__DIR__) . '/includes/dashboard-renderer.php';
require_once dirname(__DIR__) . '/includes/routes.php';
require_once dirname(__DIR__) . '/includes/settings.php';
require_once dirname(__DIR__) . '/includes/admin-menu.php';
require_once dirname(__DIR__) . '/includes/drive-imports.php';
require_once dirname(__DIR__) . '/includes/bootstrap.php';
