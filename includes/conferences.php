<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
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
