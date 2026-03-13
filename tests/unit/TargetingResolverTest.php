<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\current_time_for_resolver;
use function VincentianHub\user_can_view_targeted_object;

require_once dirname(__DIR__) . '/bootstrap.php';

final class TargetingResolverTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-13 12:00:00';
    }

    public function test_denies_when_user_does_not_exist(): void {
        $this->assertFalse(user_can_view_targeted_object(null, $this->makeObject()));
    }

    public function test_denies_when_user_is_not_approved(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext([
            'approval_status' => 'pending',
        ]), $this->makeObject()));
    }

    public function test_denies_when_object_is_inactive(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_is_active' => false,
        ])));
    }

    public function test_denies_when_outside_publish_window(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_publish_end' => '2026-03-12 23:59:59',
        ])));
    }

    public function test_denies_when_scope_does_not_match(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_scope' => 'district',
            'svdp_target_conference_mode' => 'district_only',
        ])));
    }

    public function test_denies_when_audience_does_not_intersect(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_audience_profiles' => ['district_admin'],
        ])));
    }

    public function test_denies_when_selected_conference_does_not_match(): void {
        $this->assertFalse(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [200],
        ])));
    }

    public function test_allows_empty_audience_when_other_constraints_match(): void {
        $this->assertTrue(user_can_view_targeted_object($this->makeContext(), $this->makeObject([
            'svdp_audience_profiles' => [],
        ])));
    }

    public function test_allows_matching_group_flags(): void {
        $this->assertTrue(user_can_view_targeted_object($this->makeContext([
            'conference_flags' => ['urban'],
        ]), $this->makeObject([
            'svdp_target_conference_mode' => 'group_flags',
            'svdp_target_group_flags' => ['urban'],
        ])));
    }

    public function test_uses_test_time_override(): void {
        $this->assertSame('2026-03-13 12:00:00', current_time_for_resolver());
    }

    private function makeContext(array $overrides = []): array {
        return array_merge([
            'user_id' => 55,
            'approval_status' => 'approved',
            'account_scope' => 'conference',
            'conference_id' => 100,
            'role_profiles' => ['member'],
            'conference_flags' => ['urban'],
            'calendar_feed_token' => 'token-1',
        ], $overrides);
    }

    private function makeObject(array $overrides = []): array {
        return array_merge([
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [100],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
        ], $overrides);
    }
}
