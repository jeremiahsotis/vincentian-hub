<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function normalize_conference_page_slug($slug) {
    return trim(sanitize_text_field((string) $slug));
}

function get_conference_posts() {
    return get_posts([
        'post_type' => 'svdp_conf',
        'post_status' => 'publish',
    ]);
}

function get_conference_flag_meta_map() {
    return [
        'svdp_conf_is_urban' => 'urban',
        'svdp_conf_is_rural' => 'rural',
        'svdp_conf_is_new_haven' => 'new_haven',
        'svdp_conf_is_allen_county' => 'allen_county',
    ];
}

function get_conference_meta_snapshot($conference_id) {
    if (!$conference_id) {
        return [];
    }

    $meta = [
        'svdp_conf_active' => (bool) get_post_meta($conference_id, 'svdp_conf_active', true),
    ];

    foreach (array_keys(get_conference_flag_meta_map()) as $meta_key) {
        $meta[$meta_key] = (bool) get_post_meta($conference_id, $meta_key, true);
    }

    return $meta;
}

function get_conference_id_by_page_slug($page_slug) {
    $page_slug = normalize_conference_page_slug($page_slug);

    if ($page_slug === '') {
        return 0;
    }

    $matches = [];

    foreach (get_conference_posts() as $conference) {
        if (!isset($conference->ID)) {
            continue;
        }

        $candidate_slug = normalize_conference_page_slug(
            get_post_meta((int) $conference->ID, 'svdp_conf_page_slug', true)
        );

        if ($candidate_slug === $page_slug) {
            $matches[] = (int) $conference->ID;
        }
    }

    return count($matches) === 1 ? $matches[0] : 0;
}

function get_linked_page_id_for_conference($conference_id) {
    return (int) get_post_meta((int) $conference_id, 'svdp_conf_linked_page_id', true);
}

function build_conference_context($conference_id) {
    $conference_id = (int) $conference_id;
    if ($conference_id <= 0 || !get_post($conference_id)) {
        return [];
    }

    return [
        'conference_id' => $conference_id,
        'page_slug' => normalize_conference_page_slug(get_post_meta($conference_id, 'svdp_conf_page_slug', true)),
        'linked_page_id' => get_linked_page_id_for_conference($conference_id),
        'is_active' => (bool) get_post_meta($conference_id, 'svdp_conf_active', true),
        'conference_flags' => get_conference_flags_for_conference($conference_id),
    ];
}

function get_conference_context_by_page_slug($page_slug) {
    $conference_id = get_conference_id_by_page_slug($page_slug);

    if ($conference_id <= 0) {
        return [];
    }

    return build_conference_context($conference_id);
}

function get_conference_context_by_linked_page_id($linked_page_id) {
    $linked_page_id = (int) $linked_page_id;

    if ($linked_page_id <= 0) {
        return [];
    }

    $matches = [];

    foreach (get_conference_posts() as $conference) {
        if (!isset($conference->ID)) {
            continue;
        }

        if (get_linked_page_id_for_conference((int) $conference->ID) === $linked_page_id) {
            $matches[] = (int) $conference->ID;
        }
    }

    if (count($matches) !== 1) {
        return [];
    }

    return build_conference_context($matches[0]);
}

function get_conference_flags_for_conference($conference_id) {
    $meta = get_conference_meta_snapshot($conference_id);

    if ($meta === [] || empty($meta['svdp_conf_active'])) {
        return [];
    }

    $flags = [];
    foreach (get_conference_flag_meta_map() as $meta_key => $flag) {
        if (!empty($meta[$meta_key])) {
            $flags[] = $flag;
        }
    }

    return $flags;
}
