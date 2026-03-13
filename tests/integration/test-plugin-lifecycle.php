<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class VincentianHubPluginLifecycleTest extends TestCase
{
    public function test_canonical_plugin_entrypoint_exists(): void
    {
        $this->assertFileExists(SVDP_PLUGIN_ROOT . '/vincentian-hub.php');
    }

    public function test_bootstrap_loads_from_canonical_plugin_directory(): void
    {
        $this->assertFileExists(SVDP_PLUGIN_ROOT . '/includes/bootstrap.php');
    }

    public function test_activation_and_deactivation_callbacks_are_defined(): void
    {
        $this->assertTrue(function_exists('vincentian_hub_activate'));
        $this->assertTrue(function_exists('vincentian_hub_deactivate'));
    }

    public function test_directory_table_name_avoids_hardcoded_wp_prefix(): void
    {
        $this->assertSame('network_svdp_directory', vincentian_hub_directory_table_name('network_'));
    }
}
