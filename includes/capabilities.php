<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_capability_registry(): array
{
    return [
        'portal' => [
            'svdp_view_portal_admin',
            'svdp_manage_settings',
            'svdp_manage_conferences',
            'svdp_manage_user_profiles',
            'svdp_approve_users',
        ],
        'dashboard_items' => [
            'svdp_edit_dashboard_items',
            'svdp_edit_others_dashboard_items',
            'svdp_publish_dashboard_items',
            'svdp_delete_dashboard_items',
        ],
        'announcements' => [
            'svdp_edit_announcements',
            'svdp_edit_others_announcements',
            'svdp_publish_announcements',
            'svdp_delete_announcements',
        ],
        'documents' => [
            'svdp_edit_documents',
            'svdp_edit_others_documents',
            'svdp_publish_documents',
            'svdp_delete_documents',
        ],
        'events' => [
            'svdp_edit_events',
            'svdp_edit_others_events',
            'svdp_publish_events',
            'svdp_delete_events',
        ],
        'imports' => [
            'svdp_manage_drive_imports',
        ],
    ];
}

function vincentian_hub_flatten_capabilities(): array
{
    return array_values(
        array_unique(
            array_merge(...array_values(vincentian_hub_capability_registry()))
        )
    );
}

function vincentian_hub_role_capability_map(): array
{
    return [
        'svdp_member' => [],
        'svdp_district_staff' => [],
        'svdp_district_announcements_editor' => [
            'svdp_view_portal_admin',
            'svdp_edit_announcements',
            'svdp_edit_others_announcements',
            'svdp_publish_announcements',
            'svdp_delete_announcements',
        ],
        'svdp_district_editor' => [
            'svdp_view_portal_admin',
            'svdp_edit_dashboard_items',
            'svdp_edit_others_dashboard_items',
            'svdp_publish_dashboard_items',
            'svdp_delete_dashboard_items',
            'svdp_edit_announcements',
            'svdp_edit_others_announcements',
            'svdp_publish_announcements',
            'svdp_delete_announcements',
            'svdp_edit_documents',
            'svdp_edit_others_documents',
            'svdp_publish_documents',
            'svdp_delete_documents',
            'svdp_edit_events',
            'svdp_edit_others_events',
            'svdp_publish_events',
            'svdp_delete_events',
        ],
        'svdp_district_admin' => vincentian_hub_flatten_capabilities(),
    ];
}

function vincentian_hub_register_capabilities(): void
{
    if (! function_exists('get_role') || ! function_exists('wp_roles')) {
        return;
    }

    foreach (vincentian_hub_role_capability_map() as $roleName => $capabilities) {
        $role = get_role($roleName);

        if (! $role) {
            continue;
        }

        foreach ($capabilities as $capability) {
            $role->add_cap($capability);
        }
    }
}
