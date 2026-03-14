<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\handle_document_route_request;
use function VincentianHub\register_document_routes;
use function VincentianHub\resolve_document_route_from_path;

require_once dirname(__DIR__) . '/bootstrap.php';

final class DocumentAccessTest extends TestCase {
    protected function setUp(): void {
        $GLOBALS['svdp_test_user_meta'] = [];
        $GLOBALS['svdp_test_post_meta'] = [];
        $GLOBALS['svdp_test_users'] = [];
        $GLOBALS['svdp_test_posts'] = [];
        $GLOBALS['svdp_test_now'] = '2026-03-14 14:00:00';
        $GLOBALS['svdp_test_rewrite_tags'] = [];
        $GLOBALS['svdp_test_rewrite_rules'] = [];

        $GLOBALS['svdp_test_posts'][1201] = (object) [
            'ID' => 1201,
            'post_type' => 'svdp_doc',
            'post_status' => 'publish',
            'post_name' => 'resource-guide',
            'post_title' => 'Resource Guide',
            'post_content' => 'Helpful document content',
        ];
        $GLOBALS['svdp_test_post_meta'][1201] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [77],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_doc_source' => 'local_upload',
            'svdp_doc_preview_type' => 'pdf',
            'svdp_doc_local_cache_path' => '/protected/resource-guide.pdf',
            'svdp_doc_force_download' => false,
        ];

        $GLOBALS['svdp_test_posts'][1202] = (object) [
            'ID' => 1202,
            'post_type' => 'svdp_doc',
            'post_status' => 'publish',
            'post_name' => 'download-only-guide',
            'post_title' => 'Download Only Guide',
            'post_content' => 'Download-only document content',
        ];
        $GLOBALS['svdp_test_post_meta'][1202] = [
            'svdp_scope' => 'conference',
            'svdp_audience_profiles' => ['member'],
            'svdp_target_conference_mode' => 'selected',
            'svdp_target_conference_ids' => [77],
            'svdp_target_group_flags' => [],
            'svdp_is_active' => true,
            'svdp_publish_start' => '2026-03-01 00:00:00',
            'svdp_publish_end' => '2026-03-31 23:59:59',
            'svdp_doc_source' => 'local_upload',
            'svdp_doc_preview_type' => 'download_only',
            'svdp_doc_local_cache_path' => '/protected/download-only-guide.pdf',
            'svdp_doc_force_download' => true,
        ];
    }

    public function test_registers_document_route_under_canonical_pattern(): void {
        register_document_routes();

        $this->assertCount(2, $GLOBALS['svdp_test_rewrite_tags']);
        $this->assertCount(1, $GLOBALS['svdp_test_rewrite_rules']);
        $this->assertSame('^resource-library/([^/]+)/?$', $GLOBALS['svdp_test_rewrite_rules'][0]['regex']);
    }

    public function test_approved_eligible_user_can_access_detail_preview_and_download(): void {
        $GLOBALS['svdp_test_users'][1301] = (object) ['ID' => 1301];
        $GLOBALS['svdp_test_user_meta'][1301] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $detail = handle_document_route_request(1301, resolve_document_route_from_path('/resource-library/resource-guide/'));
        $preview = handle_document_route_request(1301, resolve_document_route_from_path('/resource-library/resource-guide/?document_action=preview'));
        $download = handle_document_route_request(1301, resolve_document_route_from_path('/resource-library/resource-guide/?document_action=download'));

        $this->assertSame(200, $detail['status']);
        $this->assertSame('detail', $detail['mode']);
        $this->assertStringContainsString('Resource Guide', $detail['body']);

        $this->assertSame(200, $preview['status']);
        $this->assertSame('preview', $preview['mode']);
        $this->assertSame('/protected/resource-guide.pdf', $preview['file_path']);

        $this->assertSame(200, $download['status']);
        $this->assertSame('download', $download['mode']);
        $this->assertSame('/protected/resource-guide.pdf', $download['file_path']);
    }

    public function test_ineligible_pending_and_disabled_users_are_denied_document_access(): void {
        $GLOBALS['svdp_test_users'][1302] = (object) ['ID' => 1302];
        $GLOBALS['svdp_test_user_meta'][1302] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'district',
            'svdp_role_profiles' => ['district_staff'],
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_users'][1303] = (object) ['ID' => 1303];
        $GLOBALS['svdp_test_user_meta'][1303] = [
            'svdp_approval_status' => 'pending',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];
        $GLOBALS['svdp_test_users'][1304] = (object) ['ID' => 1304];
        $GLOBALS['svdp_test_user_meta'][1304] = [
            'svdp_approval_status' => 'disabled',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $ineligible = handle_document_route_request(1302, resolve_document_route_from_path('/resource-library/resource-guide/?document_action=download'));
        $pending = handle_document_route_request(1303, resolve_document_route_from_path('/resource-library/resource-guide/?document_action=preview'));
        $disabled = handle_document_route_request(1304, resolve_document_route_from_path('/resource-library/resource-guide/'));

        $this->assertSame(403, $ineligible['status']);
        $this->assertSame('forbidden', $ineligible['template']);
        $this->assertSame(403, $pending['status']);
        $this->assertSame('pending-access', $pending['template']);
        $this->assertSame(403, $disabled['status']);
        $this->assertSame('pending-access', $disabled['template']);
    }

    public function test_download_only_preview_type_forces_download_delivery_mode(): void {
        $GLOBALS['svdp_test_users'][1305] = (object) ['ID' => 1305];
        $GLOBALS['svdp_test_user_meta'][1305] = [
            'svdp_approval_status' => 'approved',
            'svdp_account_scope' => 'conference',
            'svdp_conference_id' => 77,
            'svdp_role_profiles' => ['member'],
            'svdp_onboarding_completed' => true,
        ];

        $preview = handle_document_route_request(1305, resolve_document_route_from_path('/resource-library/download-only-guide/?document_action=preview'));

        $this->assertSame(200, $preview['status']);
        $this->assertSame('download', $preview['mode']);
        $this->assertSame('/protected/download-only-guide.pdf', $preview['file_path']);
    }
}
