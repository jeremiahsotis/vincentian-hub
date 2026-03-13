<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\build_normalized_user_context;
use function VincentianHub\get_conference_flags_for_conference;

require_once dirname(__DIR__) . '/bootstrap.php';

final class NormalizedUserContextTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_now'] = null;
    }

    public function test_normalized_context_matches_contract_schema(): void {
        $GLOBALS['svdp_test_users'][7] = (object) ['ID' => 7, 'roles' => ['svdp_district_admin']];
        $GLOBALS['svdp_test_user_meta'][7] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 33,
            'svdp_role_profiles' => ['member', 'president'],
            'svdp_calendar_feed_token' => 'feed-token',
        ];
        $GLOBALS['svdp_test_post_meta'][33] = [
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => true,
            'svdp_conf_is_allen_county' => false,
        ];

        $context = build_normalized_user_context(7);

        $this->assertSame([
            'user_id',
            'approval_status',
            'account_scope',
            'conference_id',
            'role_profiles',
            'conference_flags',
            'calendar_feed_token',
        ], array_keys($context));
        $this->assertSame(['member', 'president'], $context['role_profiles']);
        $this->assertSame(['urban', 'new_haven'], $context['conference_flags']);
    }

    public function test_inactive_conference_contributes_no_flags(): void {
        $GLOBALS['svdp_test_post_meta'][22] = [
            'svdp_conf_active' => false,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => true,
            'svdp_conf_is_new_haven' => true,
            'svdp_conf_is_allen_county' => true,
        ];

        $this->assertSame([], get_conference_flags_for_conference(22));
    }

    public function test_wordpress_roles_do_not_imply_role_profiles(): void {
        $GLOBALS['svdp_test_users'][9] = (object) ['ID' => 9, 'roles' => ['svdp_district_admin']];
        $GLOBALS['svdp_test_user_meta'][9] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_role_profiles' => [],
            'svdp_calendar_feed_token' => 'district-feed',
        ];

        $context = build_normalized_user_context(9);

        $this->assertSame([], $context['role_profiles']);
    }
}
