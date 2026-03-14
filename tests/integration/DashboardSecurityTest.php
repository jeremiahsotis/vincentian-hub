<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\handle_dashboard_route_request;
use function VincentianHub\resolve_dashboard_route_from_path;

require_once dirname(__DIR__) . '/bootstrap.php';

final class DashboardSecurityTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 10:30:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];

        $GLOBALS['svdp_test_posts'][811] = (object) [
            'ID' => 811,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
        ];
        $GLOBALS['svdp_test_post_meta'][811] = [
            'svdp_conf_page_slug' => 'st-vincent',
            'svdp_conf_linked_page_id' => 3100,
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => false,
            'svdp_conf_is_rural' => true,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => false,
        ];
    }

    public function test_pending_user_is_blocked_before_dashboard_content_is_rendered(): void {
        $GLOBALS['svdp_test_users'][721] = (object) ['ID' => 721];
        $GLOBALS['svdp_test_user_meta'][721] = [
            'svdp_approval_status' => 'pending',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 811,
            'svdp_onboarding_completed' => true,
        ];

        $response = handle_dashboard_route_request(721, resolve_dashboard_route_from_path('/district-resources/st-vincent/'));

        $this->assertSame(403, $response['status']);
        $this->assertSame('pending-access', $response['template']);
        $this->assertStringContainsString('Access Pending', $response['body']);
    }

    public function test_conference_user_cannot_access_district_dashboard_route(): void {
        $GLOBALS['svdp_test_users'][722] = (object) ['ID' => 722];
        $GLOBALS['svdp_test_user_meta'][722] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 811,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $response = handle_dashboard_route_request(722, resolve_dashboard_route_from_path('/district-resources/district/'));

        $this->assertSame(403, $response['status']);
        $this->assertSame('forbidden', $response['template']);
    }
}
