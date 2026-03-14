<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_dashboard_template_path($dashboard_kind) {
    $template = $dashboard_kind === 'district'
        ? 'dashboard-district.php'
        : 'dashboard-conference.php';

    return SVDP_PORTAL_DIR . 'templates/' . $template;
}

function get_dashboard_asset_urls() {
    $base_url = plugin_dir_url(SVDP_PORTAL_FILE);

    return [
        'css' => $base_url . 'assets/css/hub.css',
        'js' => $base_url . 'assets/js/hub.js',
    ];
}

function render_dashboard_dataset(array $dataset) {
    $context = $dataset;
    $template_path = get_dashboard_template_path($dataset['dashboard_kind'] ?? '');

    if (!file_exists($template_path)) {
        return '';
    }

    ob_start();
    $asset_urls = get_dashboard_asset_urls();
    ?>
    <link rel="stylesheet" href="<?php echo esc_url($asset_urls['css']); ?>" />
    <?php
    include $template_path;
    ?>
    <script src="<?php echo esc_url($asset_urls['js']); ?>" defer></script>
    <?php
    return (string) ob_get_clean();
}
