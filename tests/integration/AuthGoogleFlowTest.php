<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_portal_access_state;
use function VincentianHub\sync_google_auth_user_state;

require_once dirname(__DIR__) . '/bootstrap.php';

final class AuthGoogleFlowTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-13 09:30:00';
    }

    public function test_google_auth_updates_only_canonical_user_state_inputs(): void {
        $GLOBALS['svdp_test_users'][21] = (object) ['ID' => 21];

        $result = sync_google_auth_user_state(21, [
            'google_sub' => 'google-sub-21',
            'directory_source' => 'oauth_self_registration',
            'approval_status' => 'pending',
            'account_scope' => 'conference',
            'conference_id' => 88,
            'role_profiles' => ['member', 'invalid-profile'],
            'onboarding_completed' => false,
        ]);

        $this->assertSame('google-sub-21', $GLOBALS['svdp_test_user_meta'][21]['svdp_google_sub']);
        $this->assertSame('oauth_self_registration', $GLOBALS['svdp_test_user_meta'][21]['svdp_directory_source']);
        $this->assertSame('pending', $GLOBALS['svdp_test_user_meta'][21]['svdp_approval_status']);
        $this->assertSame('conference', $GLOBALS['svdp_test_user_meta'][21]['svdp_account_scope']);
        $this->assertSame(88, $GLOBALS['svdp_test_user_meta'][21]['svdp_conference_id']);
        $this->assertSame(['member'], $GLOBALS['svdp_test_user_meta'][21]['svdp_role_profiles']);
        $this->assertFalse($GLOBALS['svdp_test_user_meta'][21]['svdp_onboarding_completed']);
        $this->assertSame('2026-03-13 09:30:00', $GLOBALS['svdp_test_user_meta'][21]['svdp_last_login']);
        $this->assertSame('pending-access', $result['gate']);
    }

    public function test_directory_source_does_not_control_access_decisions(): void {
        $GLOBALS['svdp_test_users'][31] = (object) ['ID' => 31];
        $GLOBALS['svdp_test_users'][32] = (object) ['ID' => 32];

        sync_google_auth_user_state(31, [
            'google_sub' => 'google-sub-31',
            'directory_source' => 'trusted_directory',
            'approval_status' => 'approved',
            'account_scope' => 'district',
            'role_profiles' => ['district_staff'],
            'onboarding_completed' => true,
        ]);

        sync_google_auth_user_state(32, [
            'google_sub' => 'google-sub-32',
            'directory_source' => 'oauth_self_registration',
            'approval_status' => 'approved',
            'account_scope' => 'district',
            'role_profiles' => ['district_staff'],
            'onboarding_completed' => true,
        ]);

        $this->assertSame('ready', get_portal_access_state(31));
        $this->assertSame('ready', get_portal_access_state(32));
    }
}
