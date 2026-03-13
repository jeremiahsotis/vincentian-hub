<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\complete_onboarding;
use function VincentianHub\get_gate_template_context;
use function VincentianHub\get_portal_access_state;
use function VincentianHub\get_portal_gate_template;
use function VincentianHub\user_can_access_portal_after_gates;

require_once dirname(__DIR__) . '/bootstrap.php';

final class OnboardingAccessStatesTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-13 11:00:00';
    }

    public function test_guest_is_sent_to_login_state(): void {
        $this->assertSame('login', get_portal_access_state(0));
        $this->assertSame('login', get_portal_gate_template(0));
    }

    public function test_pending_and_disabled_users_are_blocked(): void {
        $GLOBALS['svdp_test_users'][41] = (object) ['ID' => 41];
        $GLOBALS['svdp_test_users'][42] = (object) ['ID' => 42];
        $GLOBALS['svdp_test_user_meta'][41] = [
            'svdp_approval_status' => 'pending',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 10,
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_user_meta'][42] = [
            'svdp_approval_status' => 'disabled',
            'svdp_account_scope' => 'district',
            'svdp_onboarding_completed' => true,
        ];

        $this->assertSame('pending-access', get_portal_access_state(41));
        $this->assertSame('pending-access', get_portal_gate_template(41));
        $this->assertFalse(user_can_access_portal_after_gates(41));

        $this->assertSame('disabled', get_portal_access_state(42));
        $this->assertSame('pending-access', get_portal_gate_template(42));
        $this->assertFalse(user_can_access_portal_after_gates(42));
    }

    public function test_conference_scope_requires_exactly_one_conference_assignment(): void {
        $GLOBALS['svdp_test_users'][51] = (object) ['ID' => 51];
        $GLOBALS['svdp_test_user_meta'][51] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 0,
            'svdp_onboarding_completed' => false,
        ];

        $result = complete_onboarding(51, [
            'account_scope' => 'conference',
            'conference_id' => 77,
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('ready', $result['gate']);
        $this->assertSame(77, $GLOBALS['svdp_test_user_meta'][51]['svdp_conference_id']);
        $this->assertTrue($GLOBALS['svdp_test_user_meta'][51]['svdp_onboarding_completed']);
        $this->assertTrue(user_can_access_portal_after_gates(51));
    }

    public function test_district_users_do_not_inherit_conference_targeting_behavior(): void {
        $GLOBALS['svdp_test_users'][61] = (object) ['ID' => 61];
        $GLOBALS['svdp_test_user_meta'][61] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_conference_id' => 123,
            'svdp_directory_source' => 'trusted_directory',
            'svdp_onboarding_completed' => false,
        ];

        $result = complete_onboarding(61, [
            'account_scope' => 'district',
            'conference_id' => 456,
        ]);

        $context = get_gate_template_context(61);

        $this->assertTrue($result['success']);
        $this->assertSame('ready', get_portal_access_state(61));
        $this->assertSame(0, $GLOBALS['svdp_test_user_meta'][61]['svdp_conference_id']);
        $this->assertFalse($context['requires_conference_assignment']);
        $this->assertSame('district', $context['account_scope']);
    }

    public function test_gate_templates_render_provided_context_only(): void {
        $login = $this->renderTemplate(dirname(__DIR__, 2) . '/templates/login.php', [
            'title' => 'Portal Sign In',
            'message' => 'Use Google to continue.',
            'button_label' => 'Sign in',
            'button_url' => '/oauth/google',
        ]);
        $onboarding = $this->renderTemplate(dirname(__DIR__, 2) . '/templates/onboarding.php', [
            'title' => 'Finish Setup',
            'message' => 'Select your scope.',
            'account_scope' => 'conference',
            'conference_id' => 99,
            'requires_conference_assignment' => true,
        ]);
        $pending = $this->renderTemplate(dirname(__DIR__, 2) . '/templates/pending-access.php', [
            'title' => 'Access Pending',
            'message' => 'An administrator must approve your account.',
            'status' => 'pending',
        ]);

        $this->assertStringContainsString('Portal Sign In', $login);
        $this->assertStringContainsString('/oauth/google', $login);
        $this->assertStringContainsString('Finish Setup', $onboarding);
        $this->assertStringContainsString('conference', $onboarding);
        $this->assertStringContainsString('Access Pending', $pending);
        $this->assertStringContainsString('pending', $pending);
    }

    private function renderTemplate(string $path, array $context): string {
        ob_start();
        include $path;
        return (string) ob_get_clean();
    }
}
