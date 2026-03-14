<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_announcement_display_placements;
use function VincentianHub\get_announcement_targeting_meta;
use function VincentianHub\get_announcement_types;
use function VincentianHub\get_visible_announcements_for_dashboard;

require_once dirname(__DIR__) . '/bootstrap.php';

final class AnnouncementsTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 12:00:00';
    }

    public function test_announcement_contract_values_are_locked(): void {
        $this->assertSame(
            ['update', 'alert', 'reminder', 'event', 'grant'],
            get_announcement_types()
        );

        $this->assertSame(
            ['top_banner', 'dashboard_card', 'whats_new', 'announcements_page'],
            get_announcement_display_placements()
        );
    }

    public function test_announcement_visibility_uses_shared_targeting_keys(): void {
        $GLOBALS['svdp_test_users'][880] = (object) ['ID' => 880];
        $GLOBALS['svdp_test_user_meta'][880] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 700,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_post_meta'][700] = [
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => false,
        ];

        $GLOBALS['svdp_test_posts'][990] = (object) [
            'ID' => 990,
            'post_type' => 'svdp_announcement',
            'post_status' => 'publish',
            'post_title' => 'Eligible Announcement',
            'post_content' => 'Visible announcement content',
        ];
        $GLOBALS['svdp_test_post_meta'][990] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [700],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_announcement_type' => 'update',
            'svdp_display_placement' => 'dashboard_card',
        ];

        $meta = get_announcement_targeting_meta(990);
        $this->assertArrayHasKey('svdp_scope', $meta);
        $this->assertArrayHasKey('svdp_audience_profiles', $meta);
        $this->assertArrayHasKey('svdp_target_conference_mode', $meta);
        $this->assertArrayHasKey('svdp_is_active', $meta);

        $announcements = get_visible_announcements_for_dashboard(880);

        $this->assertCount(1, $announcements);
        $this->assertSame('Eligible Announcement', $announcements[0]['title']);
        $this->assertSame('dashboard_card', $announcements[0]['display_placement']);
    }
}
