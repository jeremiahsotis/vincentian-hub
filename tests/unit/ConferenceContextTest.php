<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_conference_context_by_linked_page_id;
use function VincentianHub\get_conference_context_by_page_slug;
use function VincentianHub\get_conference_id_by_page_slug;
use function VincentianHub\get_linked_page_id_for_conference;

require_once dirname(__DIR__) . '/bootstrap.php';

final class ConferenceContextTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = null;
    }

    public function test_resolves_conference_by_svdp_conf_page_slug_only(): void {
        $GLOBALS['svdp_test_posts'][101] = (object) [
            'ID' => 101,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
            'post_name' => 'wrong-post-slug',
            'post_title' => 'Wrong Title',
        ];
        $GLOBALS['svdp_test_post_meta'][101] = [
            'svdp_conf_page_slug' => 'st-mary',
            'svdp_conf_linked_page_id' => 9001,
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => true,
            'svdp_conf_is_rural' => false,
            'svdp_conf_is_new_haven' => false,
            'svdp_conf_is_allen_county' => true,
        ];

        $this->assertSame(101, get_conference_id_by_page_slug('st-mary'));
        $context = get_conference_context_by_page_slug('st-mary');

        $this->assertSame(101, $context['conference_id']);
        $this->assertSame('st-mary', $context['page_slug']);
        $this->assertSame(9001, $context['linked_page_id']);
    }

    public function test_duplicate_page_slug_is_not_treated_as_resolvable(): void {
        $GLOBALS['svdp_test_posts'][101] = (object) [
            'ID' => 101,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
        ];
        $GLOBALS['svdp_test_posts'][102] = (object) [
            'ID' => 102,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
        ];
        $GLOBALS['svdp_test_post_meta'][101] = ['svdp_conf_page_slug' => 'shared-slug'];
        $GLOBALS['svdp_test_post_meta'][102] = ['svdp_conf_page_slug' => 'shared-slug'];

        $this->assertSame(0, get_conference_id_by_page_slug('shared-slug'));
        $this->assertSame([], get_conference_context_by_page_slug('shared-slug'));
    }

    public function test_linked_page_mapping_is_centralized(): void {
        $GLOBALS['svdp_test_posts'][103] = (object) [
            'ID' => 103,
            'post_type' => 'svdp_conf',
            'post_status' => 'publish',
        ];
        $GLOBALS['svdp_test_post_meta'][103] = [
            'svdp_conf_page_slug' => 'sacred-heart',
            'svdp_conf_linked_page_id' => 7007,
            'svdp_conf_active' => true,
            'svdp_conf_is_urban' => false,
            'svdp_conf_is_rural' => true,
            'svdp_conf_is_new_haven' => true,
            'svdp_conf_is_allen_county' => false,
        ];

        $this->assertSame(7007, get_linked_page_id_for_conference(103));

        $context = get_conference_context_by_linked_page_id(7007);

        $this->assertSame(103, $context['conference_id']);
        $this->assertSame('sacred-heart', $context['page_slug']);
        $this->assertSame(['rural', 'new_haven'], $context['conference_flags']);
    }
}
