<?php
/**
 * Plugin Name: Vincentian Hub
 * Description: MVP scaffold for the Vincentian Hub.
 * Version: 0.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SVDP_PORTAL_VERSION', '0.1.0');
define('SVDP_PORTAL_FILE', __FILE__);
define('SVDP_PORTAL_DIR', plugin_dir_path(__FILE__));
define('SVDP_PORTAL_URL', plugin_dir_url(__FILE__));

require_once SVDP_PORTAL_DIR . 'includes/bootstrap.php';
