<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function normalize_shortcode_conference_context(array $conference_context) {
    if (empty($conference_context['conference_id'])) {
        return [];
    }

    return [
        'conference_id' => (int) ($conference_context['conference_id'] ?? 0),
        'page_slug' => normalize_conference_page_slug($conference_context['page_slug'] ?? ''),
        'linked_page_id' => (int) ($conference_context['linked_page_id'] ?? 0),
        'is_active' => !empty($conference_context['is_active']),
        'conference_flags' => array_values($conference_context['conference_flags'] ?? []),
    ];
}

function build_shortcode_context(array $args = []) {
    if (!empty($args['conference_context']) && is_array($args['conference_context'])) {
        return normalize_shortcode_conference_context($args['conference_context']);
    }

    if (!empty($args['conference_id'])) {
        return build_conference_context((int) $args['conference_id']);
    }

    if (!empty($args['linked_page_id'])) {
        return get_conference_context_by_linked_page_id((int) $args['linked_page_id']);
    }

    return [];
}
