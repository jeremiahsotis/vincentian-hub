<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\build_dashboard_dataset;

require_once dirname(__DIR__) . '/bootstrap.php';

final class DashboardQueryTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 09:00:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];
    }

    public function test_build_dashboard_dataset_filters_items_through_the_resolver(): void {
        $GLOBALS['svdp_test_users'][501] = (object) ['ID' => 501];
        $GLOBALS['svdp_test_user_meta'][501] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_post_meta'][77] = [
            'svdp_conf_active' => true,
            'svdp_conf_page_slug' => 'st-john',
            'svdp_conf_linked_page_id' => 900,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => false,
        ];

        $GLOBALS['svdp_test_posts'][601] = (object) [
            'ID' => 601,
            'post_type' => 'svdp_dash_item',
            'post_status' => 'publish',
            'post_title' => 'Allowed Item',
            'post_content' => 'Visible content',
        ];
        $GLOBALS['svdp_test_post_meta'][601] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [77],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
        ];

        $GLOBALS['svdp_test_posts'][602] = (object) [
            'ID' => 602,
            'post_type' => 'svdp_dash_item',
            'post_status' => 'publish',
            'post_title' => 'Blocked Item',
            'post_content' => 'Hidden content',
        ];
        $GLOBALS['svdp_test_post_meta'][602] = [
            'svdp_scope' => 'district',
            'svdp_audience_profiles' => ['district_admin'],
            'svdp_target_conference_mode' => 'district_only',
            'svdp_target_conference_ids' => [],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
        ];

        $dataset = build_dashboard_dataset(501, [
            'dashboard_kind' => 'conference',
            'conference_context' => [
                'conference_id' => 77,
                'page_slug' => 'st-john',
                'linked_page_id' => 900,
                'is_active' => true,
                'conference_flags' => ['urban'],
            ],
        ]);

        $this->assertSame('conference', $dataset['dashboard_kind']);
        $this->assertCount(1, $dataset['items']);
        $this->assertSame('Allowed Item', $dataset['items'][0]['title']);
        $this->assertArrayHasKey('visibility_context', $dataset);
        $this->assertIsArray($dataset['items']);
    }
}
