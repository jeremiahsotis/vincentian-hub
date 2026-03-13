<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class VincentianHubCapabilityBoundariesTest extends TestCase
{
    public function test_district_announcements_editor_permissions(): void
    {
        $capabilities = vincentian_hub_role_capability_map()['svdp_district_announcements_editor'];

        $this->assertContains('svdp_view_portal_admin', $capabilities);
        $this->assertContains('svdp_publish_announcements', $capabilities);
        $this->assertNotContains('svdp_edit_documents', $capabilities);
    }

    public function test_district_editor_permissions(): void
    {
        $capabilities = vincentian_hub_role_capability_map()['svdp_district_editor'];

        $this->assertContains('svdp_edit_dashboard_items', $capabilities);
        $this->assertContains('svdp_publish_documents', $capabilities);
        $this->assertContains('svdp_publish_events', $capabilities);
        $this->assertNotContains('svdp_manage_settings', $capabilities);
    }

    public function test_district_admin_permissions(): void
    {
        $capabilities = vincentian_hub_role_capability_map()['svdp_district_admin'];

        $this->assertContains('svdp_manage_settings', $capabilities);
        $this->assertContains('svdp_manage_conferences', $capabilities);
        $this->assertContains('svdp_manage_user_profiles', $capabilities);
        $this->assertContains('svdp_manage_drive_imports', $capabilities);
    }
}
