<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\build_shortcode_context;

require_once dirname(__DIR__) . '/bootstrap.php';

final class ShortcodeContextTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = null;
        $_GET = [];
        $_REQUEST = [];
    }

    public function test_shortcode_context_uses_linked_page_mapping_instead_of_request_params(): void {
        $GLOBALS['svdp_test_posts'][201] = (object) [
            'ID' => 201,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
            'post_name' => 'not-the-route-token',
        ];
        $GLOBALS['svdp_test_post_meta'][201] = [
            'svdp_conf_page_slug' => 'holy-family',
            'svdp_conf_linked_page_id' => 8080,
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => false,
        ];

        $_GET['conference'] = 'tampered-request-value';
        $_REQUEST['conference_name'] = 'also-tampered';

        $context = build_shortcode_context([
            'linked_page_id' => 8080,
        ]);

        $this->assertSame(201, $context['conference_id']);
        $this->assertSame('holy-family', $context['page_slug']);
        $this->assertSame(['urban'], $context['conference_flags']);
    }

    public function test_shortcode_context_can_use_provided_canonical_conference_context(): void {
        $context = build_shortcode_context([
            'conference_context' => [
                'conference_id' => 301,
                'page_slug' => 'st-joseph',
                'linked_page_id' => 9900,
                'is_active' => true,
                'conference_flags' => ['allen_county'],
            ],
        ]);

        $this->assertSame(301, $context['conference_id']);
        $this->assertSame('st-joseph', $context['page_slug']);
        $this->assertSame(9900, $context['linked_page_id']);
    }

    public function test_shortcode_context_does_not_accept_arbitrary_request_values_as_resolution_inputs(): void {
        $_GET['conference_id'] = 999;
        $_REQUEST['conference_name'] = 'unsafe-slug';

        $this->assertSame([], build_shortcode_context([]));
    }
}
