<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\handle_calendar_feed_request;
use function VincentianHub\handle_event_export_request;
use function VincentianHub\handle_event_route_request;
use function VincentianHub\register_event_and_calendar_routes;
use function VincentianHub\resolve_calendar_feed_route_from_path;
use function VincentianHub\resolve_event_export_route_from_path;
use function VincentianHub\resolve_event_route_from_path;

require_once dirname(__DIR__) . '/bootstrap.php';

final class EventCalendarAccessTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 15:00:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];

        $GLOBALS['svdp_test_posts'][1501] = (object) [
            'ID' => 1501,
            'post_type' => 'svdp_event',
            'post_status' => 'publish',
            'post_name' => 'spring-training',
            'post_title' => 'Spring Training',
            'post_content' => 'Event detail body',
        ];
        $GLOBALS['svdp_test_post_meta'][1501] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [77],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_event_type' => 'training',
            'svdp_event_status' => 'scheduled',
            'svdp_event_start' => '2026-03-20 18:00:00',
            'svdp_event_end' => '2026-03-20 20:00:00',
            'svdp_show_in_calendar' => true,
            'svdp_event_calendar_export_enabled' => true,
        ];

        $GLOBALS['svdp_test_posts'][1502] = (object) [
            'ID' => 1502,
            'post_type' => 'svdp_event',
            'post_status' => 'publish',
            'post_name' => 'district-meeting',
            'post_title' => 'District Meeting',
            'post_content' => 'District event',
        ];
        $GLOBALS['svdp_test_post_meta'][1502] = [
            'svdp_scope' => 'district',
            'svdp_audience_profiles' => ['district_staff'],
            'svdp_target_conference_mode' => 'district_only',
            'svdp_target_conference_ids' => [],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_event_type' => 'meeting',
            'svdp_event_status' => 'scheduled',
            'svdp_event_start' => '2026-03-22 10:00:00',
            'svdp_event_end' => '2026-03-22 12:00:00',
            'svdp_show_in_calendar' => true,
            'svdp_event_calendar_export_enabled' => true,
        ];
    }

    public function test_registers_canonical_event_and_calendar_routes(): void {
        register_event_and_calendar_routes();

        $this->assertCount(3, $GLOBALS['svdp_test_rewrite_tags']);
        $this->assertCount(3, $GLOBALS['svdp_test_rewrite_rules']);
        $this->assertSame('^events/([^/]+)/?$', $GLOBALS['svdp_test_rewrite_rules'][0]['regex']);
        $this->assertSame('^portal-calendar/feed/([^/]+)/?$', $GLOBALS['svdp_test_rewrite_rules'][1]['regex']);
        $this->assertSame('^portal-calendar/event/([0-9]+)/download/?$', $GLOBALS['svdp_test_rewrite_rules'][2]['regex']);
    }

    public function test_approved_eligible_user_can_access_event_detail(): void {
        $GLOBALS['svdp_test_users'][1601] = (object) ['ID' => 1601];
        $GLOBALS['svdp_test_user_meta'][1601] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
            'svdp_calendar_feed_token' => 'feed-1601',
        ];

        $response = handle_event_route_request(1601, resolve_event_route_from_path('/events/spring-training/'));

        $this->assertSame(200, $response['status']);
        $this->assertSame('event-detail', $response['template']);
        $this->assertStringContainsString('Spring Training', $response['body']);
    }

    public function test_ineligible_and_pending_users_are_denied_event_detail(): void {
        $GLOBALS['svdp_test_users'][1602] = (object) ['ID' => 1602];
        $GLOBALS['svdp_test_user_meta'][1602] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_role_profiles' => ['district_staff'],
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_users'][1603] = (object) ['ID' => 1603];
        $GLOBALS['svdp_test_user_meta'][1603] = [
            'svdp_approval_status' => 'pending',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $ineligible = handle_event_route_request(1602, resolve_event_route_from_path('/events/spring-training/'));
        $pending = handle_event_route_request(1603, resolve_event_route_from_path('/events/spring-training/'));

        $this->assertSame(403, $ineligible['status']);
        $this->assertSame('forbidden', $ineligible['template']);
        $this->assertSame(403, $pending['status']);
        $this->assertSame('pending-access', $pending['template']);
    }

    public function test_calendar_feed_uses_personalized_token_and_visibility_rules(): void {
        $GLOBALS['svdp_test_users'][1604] = (object) ['ID' => 1604];
        $GLOBALS['svdp_test_user_meta'][1604] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
            'svdp_calendar_feed_token' => 'feed-1604',
        ];

        $feed = handle_calendar_feed_request(resolve_calendar_feed_route_from_path('/portal-calendar/feed/feed-1604/'));
        $invalid = handle_calendar_feed_request(resolve_calendar_feed_route_from_path('/portal-calendar/feed/not-valid/'));

        $this->assertSame(200, $feed['status']);
        $this->assertCount(1, $feed['events']);
        $this->assertSame('Spring Training', $feed['events'][0]['title']);
        $this->assertSame(403, $invalid['status']);
    }

    public function test_single_event_export_enforces_token_owner_visibility(): void {
        $GLOBALS['svdp_test_users'][1605] = (object) ['ID' => 1605];
        $GLOBALS['svdp_test_user_meta'][1605] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
            'svdp_calendar_feed_token' => 'feed-1605',
        ];

        $allowed = handle_event_export_request(resolve_event_export_route_from_path('/portal-calendar/event/1501/download/?token=feed-1605'));
        $denied = handle_event_export_request(resolve_event_export_route_from_path('/portal-calendar/event/1502/download/?token=feed-1605'));

        $this->assertSame(200, $allowed['status']);
        $this->assertSame('BEGIN:VCALENDAR', strtok($allowed['body'], "\n"));
        $this->assertSame(403, $denied['status']);
    }
}
