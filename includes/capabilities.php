<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_capability_registry() {
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

function get_role_capability_matrix() {
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
        'svdp_district_admin' => [
            'svdp_view_portal_admin',
            'svdp_manage_settings',
            'svdp_manage_conferences',
            'svdp_manage_user_profiles',
            'svdp_approve_users',
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
            'svdp_manage_drive_imports',
        ],
    ];
}

function get_all_capabilities() {
    $capabilities = [];

    foreach (get_capability_registry() as $group) {
        foreach ($group as $capability) {
            $capabilities[] = $capability;
        }
    }

    return $capabilities;
}
