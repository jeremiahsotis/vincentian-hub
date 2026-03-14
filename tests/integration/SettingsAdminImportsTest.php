<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_admin_menu_definitions;
use function VincentianHub\get_branding_logo_context;
use function VincentianHub\get_branding_logo_option_key;
use function VincentianHub\get_branding_settings_page_slug;
use function VincentianHub\import_drive_document_record;
use function VincentianHub\register_admin_menus;
use function VincentianHub\update_branding_logo_attachment_id;

require_once dirname(__DIR__) . '/bootstrap.php';

final class SettingsAdminImportsTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 16:00:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];
        $GLOBALS['svdp_test_admin_menus'] = [];
        $GLOBALS['svdp_test_current_user_caps'] = [];
        $GLOBALS['svdp_test_options'] = [];
        $GLOBALS['svdp_test_next_post_id'] = 2000;
    }

    public function test_branding_setting_key_and_logo_fallback_are_canonical(): void {
        $this->assertSame('vincentian_hub_logo_attachment_id', get_branding_logo_option_key());

        $context = get_branding_logo_context();

        $this->assertSame(0, $context['attachment_id']);
        $this->assertSame('Vincentian Hub', $context['fallback_text']);
        $this->assertSame('', $context['logo_url']);
    }

    public function test_only_district_admin_capabilities_can_manage_branding_setting(): void {
        $GLOBALS['svdp_test_current_user_caps'] = [
            'svdp_manage_settings' => true,
            'svdp_view_portal_admin' => true,
        ];

        $this->assertTrue(update_branding_logo_attachment_id(321));
        $this->assertSame(321, $GLOBALS['svdp_test_options']['vincentian_hub_logo_attachment_id']);

        $GLOBALS['svdp_test_current_user_caps'] = [
            'svdp_view_portal_admin' => true,
        ];

        $this->assertFalse(update_branding_logo_attachment_id(654));
        $this->assertSame(321, $GLOBALS['svdp_test_options']['vincentian_hub_logo_attachment_id']);
    }

    public function test_admin_menu_access_is_capability_gated(): void {
        $menus = get_admin_menu_definitions();

        $this->assertSame('vincentian-hub-settings', get_branding_settings_page_slug());
        $this->assertSame('svdp_view_portal_admin', $menus[0]['capability']);
        $this->assertSame('svdp_manage_settings', $menus[1]['capability']);
        $this->assertSame('svdp_manage_drive_imports', $menus[2]['capability']);

        register_admin_menus();

        $this->assertCount(3, $GLOBALS['svdp_test_admin_menus']);
    }

    public function test_drive_import_behavior_is_capability_gated_and_persists_canonical_storage(): void {
        $GLOBALS['svdp_test_current_user_caps'] = [
            'svdp_manage_drive_imports' => true,
        ];

        $post_id = import_drive_document_record([
            'title' => 'Imported Guide',
            'content' => 'Imported body',
            'slug' => 'imported-guide',
            'drive_file_id' => 'drive-file-1',
            'drive_parent_ref' => 'parent-folder',
            'preview_type' => 'pdf',
            'local_cache_path' => '/cache/imported-guide.pdf',
            'shared_targeting' => [
                'svdp_scope' => 'district',
                'svdp_audience_profiles' => ['district_staff'],
                'svdp_target_conference_mode' => 'district_only',
                'svdp_target_conference_ids' => [],
                'svdp_target_group_flags' => [],
                'svdp_is_active' => true,
                'svdp_publish_start' => '2026-03-01 00:00:00',
                'svdp_publish_end' => '2026-03-31 23:59:59',
                'svdp_alias_scope' => 'blocked',
            ],
        ]);

        $this->assertGreaterThan(0, $post_id);
        $this->assertSame('svdp_doc', $GLOBALS['svdp_test_posts'][$post_id]->post_type);
        $this->assertSame('google_drive', $GLOBALS['svdp_test_post_meta'][$post_id]['svdp_doc_source']);
        $this->assertSame('drive-file-1', $GLOBALS['svdp_test_post_meta'][$post_id]['svdp_drive_file_id']);
        $this->assertSame('district', $GLOBALS['svdp_test_post_meta'][$post_id]['svdp_scope']);
        $this->assertArrayNotHasKey('svdp_alias_scope', $GLOBALS['svdp_test_post_meta'][$post_id]);

        $GLOBALS['svdp_test_current_user_caps'] = [];

        $this->assertSame(0, import_drive_document_record([
            'title' => 'Blocked Import',
            'drive_file_id' => 'drive-file-2',
        ]));
    }
}
