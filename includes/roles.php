<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_role_definitions() {
    return [
        'svdp_member' => 'Vincentian Hub Member',
        'svdp_district_staff' => 'Vincentian Hub District Staff',
        'svdp_district_announcements_editor' => 'Vincentian Hub Announcements Editor',
        'svdp_district_editor' => 'Vincentian Hub District Editor',
        'svdp_district_admin' => 'Vincentian Hub District Admin',
    ];
}

function register_roles() {
    foreach (get_role_definitions() as $role => $label) {
        remove_role($role);
        add_role($role, $label, get_capabilities_for_role($role));
    }
}

function grant_administrator_capabilities() {
    $administrator = get_role('administrator');

    if (!$administrator) {
        return;
    }

    foreach (get_all_capabilities() as $capability) {
        $administrator->add_cap($capability);
    }
}

function get_capabilities_for_role($role) {
    $matrix = get_role_capability_matrix();
    $capabilities = [];

    foreach ($matrix[$role] ?? [] as $capability) {
        $capabilities[$capability] = true;
    }

    return $capabilities;
}
