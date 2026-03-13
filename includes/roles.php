<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_role_labels(): array
{
    return [
        'svdp_member' => 'Vincentian Member',
        'svdp_district_staff' => 'District Staff',
        'svdp_district_announcements_editor' => 'District Announcements Editor',
        'svdp_district_editor' => 'District Editor',
        'svdp_district_admin' => 'District Admin',
    ];
}

function vincentian_hub_register_roles(): void
{
    if (! function_exists('add_role') || ! function_exists('remove_role')) {
        return;
    }

    foreach (vincentian_hub_role_labels() as $roleName => $label) {
        remove_role($roleName);
        add_role(
            $roleName,
            $label,
            array_fill_keys(vincentian_hub_role_capability_map()[$roleName], true)
        );
    }
}
