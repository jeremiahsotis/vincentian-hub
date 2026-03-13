<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_object_meta_registry;
use function VincentianHub\get_required_user_meta_registry;
use function VincentianHub\get_shared_targeting_meta_keys;

require_once dirname(__DIR__) . '/bootstrap.php';

final class MetaRegistrationTest extends TestCase {
    public function test_shared_targeting_keys_match_the_contract(): void {
        $this->assertSame(
            [
                'svdp_scope',
                'svdp_audience_profiles',
                'svdp_target_conference_mode',
                'svdp_target_conference_ids',
                'svdp_target_group_flags',
                'svdp_is_active',
                'svdp_publish_start',
                'svdp_publish_end',
            ],
            array_keys(get_shared_targeting_meta_keys())
        );
    }

    public function test_required_user_meta_includes_role_profiles_and_calendar_token(): void {
        $registry = get_required_user_meta_registry();

        $this->assertArrayHasKey('svdp_role_profiles', $registry);
        $this->assertArrayHasKey('svdp_calendar_feed_token', $registry);
        $this->assertSame('array', $registry['svdp_role_profiles']);
    }

    public function test_object_registry_contains_foundation_conference_keys(): void {
        $registry = get_object_meta_registry();

        $this->assertArrayHasKey('svdp_conf', $registry);
        $this->assertArrayHasKey('svdp_conf_page_slug', $registry['svdp_conf']);
        $this->assertArrayHasKey('svdp_conf_active', $registry['svdp_conf']);
    }
}
