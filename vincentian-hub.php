<?php
/**
 * Plugin Name: Vincentian Hub
 * Description: Internal WordPress portal for Vincentian volunteers and district staff.
 * Version: 0.1.0
 * Text Domain: vincentian-hub
 */

if (! defined('ABSPATH')) {
    exit;
}

define('VINCENTIAN_HUB_VERSION', '0.1.0');
define('VINCENTIAN_HUB_FILE', __FILE__);
define('VINCENTIAN_HUB_DIR', plugin_dir_path(__FILE__));
define('VINCENTIAN_HUB_URL', plugin_dir_url(__FILE__));

require_once VINCENTIAN_HUB_DIR . 'includes/bootstrap.php';

if (function_exists('register_activation_hook')) {
    register_activation_hook(VINCENTIAN_HUB_FILE, 'vincentian_hub_activate');
}

if (function_exists('register_deactivation_hook')) {
    register_deactivation_hook(VINCENTIAN_HUB_FILE, 'vincentian_hub_deactivate');
}
