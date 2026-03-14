<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\build_dashboard_dataset;
use function VincentianHub\handle_dashboard_route_request;
use function VincentianHub\render_dashboard_dataset;
use function VincentianHub\resolve_dashboard_route_from_path;

require_once dirname(__DIR__) . '/bootstrap.php';

final class AnnouncementVisibilityTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 13:00:00';
    }

    public function test_dashboard_dataset_includes_only_resolver_visible_announcements(): void {
        $GLOBALS['svdp_test_users'][881] = (object) ['ID' => 881];
        $GLOBALS['svdp_test_user_meta'][881] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_role_profiles' => ['district_staff'],
            'svdp_onboarding_completed' => true,
        ];

        $GLOBALS['svdp_test_posts'][991] = (object) [
            'ID' => 991,
            'post_type' => 'svdp_announcement',
            'post_status' => 'publish',
            'post_title' => 'District Notice',
            'post_content' => 'District announcement content',
        ];
        $GLOBALS['svdp_test_post_meta'][991] = [
            'svdp_scope' => 'district',
            'svdp_audience_profiles' => ['district_staff'],
            'svdp_target_conference_mode' => 'district_only',
            'svdp_target_conference_ids' => [],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_announcement_type' => 'alert',
            'svdp_display_placement' => 'top_banner',
        ];

        $GLOBALS['svdp_test_posts'][992] = (object) [
            'ID' => 992,
            'post_type' => 'svdp_announcement',
            'post_status' => 'publish',
            'post_title' => 'Blocked Notice',
            'post_content' => 'Should not appear',
        ];
        $GLOBALS['svdp_test_post_meta'][992] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [1],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_announcement_type' => 'grant',
            'svdp_display_placement' => 'dashboard_card',
        ];

        $dataset = build_dashboard_dataset(881, [
            'dashboard_kind' => 'district',
            'conference_context' => [],
        ]);

        $this->assertCount(1, $dataset['announcements']);
        $this->assertSame('District Notice', $dataset['announcements'][0]['title']);
    }

    public function test_dashboard_rendering_integrates_announcements_without_new_route_rules(): void {
        $GLOBALS['svdp_test_users'][882] = (object) ['ID' => 882];
        $GLOBALS['svdp_test_user_meta'][882] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_role_profiles' => ['district_staff'],
            'svdp_onboarding_completed' => true,
        ];

        $GLOBALS['svdp_test_posts'][993] = (object) [
            'ID' => 993,
            'post_type' => 'svdp_announcement',
            'post_status' => 'publish',
            'post_title' => 'Top Banner Notice',
            'post_content' => 'Banner content',
        ];
        $GLOBALS['svdp_test_post_meta'][993] = [
            'svdp_scope' => 'district',
            'svdp_audience_profiles' => [],
            'svdp_target_conference_mode' => 'district_only',
            'svdp_target_conference_ids' => [],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_announcement_type' => 'reminder',
            'svdp_display_placement' => 'top_banner',
        ];

        $response = handle_dashboard_route_request(882, resolve_dashboard_route_from_path('/district-resources/district/'));

        $this->assertSame(200, $response['status']);
        $this->assertStringContainsString('Top Banner Notice', $response['body']);
        $this->assertStringContainsString('svdp-announcements', $response['body']);
    }
}
