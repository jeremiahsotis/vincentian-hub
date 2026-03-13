<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class VincentianHubFoundationContractsTest extends TestCase
{
    public function test_canonical_plugin_entrypoint_exists(): void
    {
        $this->assertFileExists(SVDP_PLUGIN_ROOT . '/vincentian-hub.php');
    }

    public function test_canonical_post_types_are_registered_in_registry(): void
    {
        $this->assertSame(
            ['svdp_conf', 'svdp_dash_item', 'svdp_announcement', 'svdp_doc', 'svdp_event'],
            array_keys(vincentian_hub_post_type_registry())
        );
    }

    public function test_canonical_taxonomy_is_registered_in_registry(): void
    {
        $this->assertSame(['svdp_doc_cat'], array_keys(vincentian_hub_taxonomy_registry()));
    }

    public function test_shared_targeting_keys_match_contract(): void
    {
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
            array_keys(vincentian_hub_shared_targeting_meta())
        );
    }

    public function test_required_user_meta_keys_match_contract(): void
    {
        $this->assertSame(
            [
                'svdp_account_scope',
                'svdp_approval_status',
                'svdp_conference_id',
                'svdp_role_profiles',
                'svdp_phone',
                'svdp_google_sub',
                'svdp_directory_source',
                'svdp_last_login',
                'svdp_onboarding_completed',
                'svdp_can_self_change_conference',
                'svdp_calendar_feed_token',
                'svdp_calendar_feed_token_rotated_at',
                'svdp_admin_notes',
            ],
            array_keys(vincentian_hub_user_meta_registry())
        );
    }

    public function test_directory_table_name_uses_runtime_prefix(): void
    {
        $this->assertSame('custom_svdp_directory', vincentian_hub_directory_table_name('custom_'));
    }
}
