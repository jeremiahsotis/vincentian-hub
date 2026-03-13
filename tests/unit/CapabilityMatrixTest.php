<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_all_capabilities;
use function VincentianHub\get_role_capability_matrix;

require_once dirname(__DIR__) . '/bootstrap.php';

final class CapabilityMatrixTest extends TestCase {
    public function test_all_capabilities_include_contract_locked_values(): void {
        $capabilities = get_all_capabilities();

        $this->assertContains('svdp_manage_settings', $capabilities);
        $this->assertContains('svdp_publish_announcements', $capabilities);
        $this->assertContains('svdp_manage_drive_imports', $capabilities);
    }

    public function test_district_staff_has_no_capabilities_by_default(): void {
        $matrix = get_role_capability_matrix();

        $this->assertSame([], $matrix['svdp_district_staff']);
    }

    public function test_district_admin_has_full_foundation_capabilities(): void {
        $matrix = get_role_capability_matrix();

        $this->assertContains('svdp_manage_settings', $matrix['svdp_district_admin']);
        $this->assertContains('svdp_manage_conferences', $matrix['svdp_district_admin']);
        $this->assertContains('svdp_manage_user_profiles', $matrix['svdp_district_admin']);
    }
}
