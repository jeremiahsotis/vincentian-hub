<?php
declare(strict_types=1);

if (! defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

define('SVDP_TESTS_DIR', __DIR__);
define('SVDP_PLUGIN_ROOT', dirname(__DIR__));

if (! function_exists('plugin_dir_path')) {
    function plugin_dir_path(string $file): string
    {
        return rtrim(dirname($file), '/\\') . '/';
    }
}

if (! function_exists('plugin_dir_url')) {
    function plugin_dir_url(string $file): string
    {
        return 'http://example.test/' . basename(rtrim(dirname($file), '/\\')) . '/';
    }
}

if (! function_exists('register_activation_hook')) {
    function register_activation_hook(string $file, string $callback): void
    {
    }
}

if (! function_exists('register_deactivation_hook')) {
    function register_deactivation_hook(string $file, string $callback): void
    {
    }
}

/**
 * Load the canonical plugin entrypoint when it exists.
 *
 * Setup intentionally avoids coupling the harness to the current non-canonical
 * scaffold entrypoint. Foundation work will make the canonical file real.
 */
function svdp_tests_maybe_load_plugin(): void
{
    $plugin = SVDP_PLUGIN_ROOT . '/vincentian-hub.php';

    if (file_exists($plugin)) {
        require_once $plugin;
    }
}

svdp_tests_maybe_load_plugin();
