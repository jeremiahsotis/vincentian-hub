<?php

use PHPUnit\Framework\TestCase;
use function VincentianHub\get_post_type_definitions;
use function VincentianHub\get_taxonomy_definitions;

require_once dirname(__DIR__) . '/bootstrap.php';

final class ContentRegistrationTest extends TestCase {
    public function test_post_types_match_the_contract_registry(): void {
        $definitions = get_post_type_definitions();

        $this->assertSame(
            ['svdp_conf', 'svdp_dash_item', 'svdp_announcement', 'svdp_doc', 'svdp_event'],
            array_keys($definitions)
        );
    }

    public function test_document_taxonomy_matches_the_contract_registry(): void {
        $definitions = get_taxonomy_definitions();

        $this->assertArrayHasKey('svdp_doc_cat', $definitions);
        $this->assertSame(['svdp_doc'], $definitions['svdp_doc_cat']['object_type']);
    }
}
