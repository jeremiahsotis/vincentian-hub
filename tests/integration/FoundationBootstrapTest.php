<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\bootstrap;
use function VincentianHub\get_directory_table_name;
use function VincentianHub\get_include_files;

require_once dirname(__DIR__) . '/bootstrap.php';

final class FoundationBootstrapTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_actions'] = [];
    }

    public function test_directory_table_uses_runtime_prefix(): void {
        $this->assertSame('wp_svdp_directory', get_directory_table_name());
    }

    public function test_bootstrap_includes_foundation_modules(): void {
        $files = get_include_files();

        $this->assertContains('capabilities.php', $files);
        $this->assertContains('roles.php', $files);
        $this->assertContains('post-types.php', $files);
        $this->assertContains('taxonomies.php', $files);
        $this->assertContains('meta-registration.php', $files);
        $this->assertContains('user-meta.php', $files);
        $this->assertContains('directory-table.php', $files);
    }

    public function test_bootstrap_registers_admin_menu_hook(): void {
        bootstrap();

        $this->assertContains([
            'hook_name' => 'admin_menu',
            'callback' => 'VincentianHub\\register_admin_menus',
            'priority' => 10,
            'accepted_args' => 1,
        ], $GLOBALS['svdp_test_actions']);
    }
}
