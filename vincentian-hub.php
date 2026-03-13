<?php
/**
 * Plugin Name: Vincentian Hub
 * Description: MVP scaffold for the Vincentian Hub.
 * Version: 0.1.0
 * Text Domain: vincentian-hub
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SVDP_PORTAL_VERSION', '0.1.0');
define('SVDP_PORTAL_FILE', __FILE__);
define('SVDP_PORTAL_DIR', plugin_dir_path(__FILE__));
define('SVDP_PORTAL_URL', plugin_dir_url(__FILE__));
define('VINCENTIAN_HUB_PLUGIN_SLUG', 'vincentian-hub');
define('VINCENTIAN_HUB_CODE_PREFIX', 'vincentian_hub_');
define('VINCENTIAN_HUB_META_PREFIX', 'svdp_');
define('VINCENTIAN_HUB_NAMESPACE', 'VincentianHub');

require_once SVDP_PORTAL_DIR . 'includes/bootstrap.php';

register_activation_hook(__FILE__, 'VincentianHub\\activate_plugin');
register_deactivation_hook(__FILE__, 'VincentianHub\\deactivate_plugin');

\VincentianHub\bootstrap();
