<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\handle_dashboard_route_request;
use function VincentianHub\register_dashboard_routes;
use function VincentianHub\resolve_dashboard_route_from_path;

require_once dirname(__DIR__) . '/bootstrap.php';

final class DashboardRoutesTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 10:00:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];

        $GLOBALS['svdp_test_posts'][801] = (object) [
            'ID' => 801,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
        ];
        $GLOBALS['svdp_test_post_meta'][801] = [
            'svdp_conf_page_slug' => 'st-francis',
            'svdp_conf_linked_page_id' => 3001,
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => false,
        ];

        $GLOBALS['svdp_test_posts'][901] = (object) [
            'ID' => 901,
            'post_type' => 'svdp_dash_item',
            'post_status' => 'publish',
            'post_title' => 'Dashboard Item',
            'post_content' => 'Dashboard content',
        ];
        $GLOBALS['svdp_test_post_meta'][901] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [801],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
        ];
    }

    public function test_registers_canonical_dashboard_routes_from_routes_php(): void {
        register_dashboard_routes();

        $this->assertCount(2, $GLOBALS['svdp_test_rewrite_tags']);
        $this->assertCount(2, $GLOBALS['svdp_test_rewrite_rules']);
        $this->assertSame('^district-resources/district/?$', $GLOBALS['svdp_test_rewrite_rules'][0]['regex']);
        $this->assertSame('^district-resources/([^/]+)/?$', $GLOBALS['svdp_test_rewrite_rules'][1]['regex']);
    }

    public function test_resolves_and_renders_conference_dashboard_route(): void {
        $GLOBALS['svdp_test_users'][701] = (object) ['ID' => 701];
        $GLOBALS['svdp_test_user_meta'][701] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 801,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $route = resolve_dashboard_route_from_path('/district-resources/st-francis/');
        $response = handle_dashboard_route_request(701, $route);

        $this->assertSame(200, $response['status']);
        $this->assertSame('conference', $response['dashboard_kind']);
        $this->assertStringContainsString('assets/css/hub.css', $response['body']);
        $this->assertStringContainsString('assets/js/hub.js', $response['body']);
        $this->assertStringContainsString('Conference Dashboard', $response['body']);
        $this->assertStringContainsString('Dashboard Item', $response['body']);
    }
}
