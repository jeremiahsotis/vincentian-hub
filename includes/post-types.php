<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_post_type_definitions() {
    return [
        'svdp_conf' => [
            'label' => 'Conferences',
            'singular' => 'Conference',
            'supports' => ['title', 'editor', 'revisions'],
            'capabilities' => get_conference_capabilities(),
        ],
        'svdp_dash_item' => [
            'label' => 'Dashboard Items',
            'singular' => 'Dashboard Item',
            'supports' => ['title', 'editor', 'revisions'],
            'capabilities' => get_dashboard_item_capabilities(),
        ],
        'svdp_announcement' => [
            'label' => 'Announcements',
            'singular' => 'Announcement',
            'supports' => ['title', 'editor', 'revisions'],
            'capabilities' => get_announcement_capabilities(),
        ],
        'svdp_doc' => [
            'label' => 'Documents',
            'singular' => 'Document',
            'supports' => ['title', 'editor', 'revisions'],
            'capabilities' => get_document_capabilities(),
        ],
        'svdp_event' => [
            'label' => 'Events',
            'singular' => 'Event',
            'supports' => ['title', 'editor', 'revisions'],
            'capabilities' => get_event_capabilities(),
        ],
    ];
}

function register_post_types() {
    foreach (get_post_type_definitions() as $post_type => $definition) {
        register_post_type($post_type, [
            'labels' => [
                'name' => $definition['label'],
                'singular_name' => $definition['singular'],
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'supports' => $definition['supports'],
            'map_meta_cap' => false,
            'capabilities' => $definition['capabilities'],
            'has_archive' => false,
            'rewrite' => false,
        ]);
    }
}

function get_conference_capabilities() {
    return [
        'edit_post' => 'svdp_manage_conferences',
        'read_post' => 'svdp_manage_conferences',
        'delete_post' => 'svdp_manage_conferences',
        'edit_posts' => 'svdp_manage_conferences',
        'edit_others_posts' => 'svdp_manage_conferences',
        'publish_posts' => 'svdp_manage_conferences',
        'read_private_posts' => 'svdp_manage_conferences',
        'delete_posts' => 'svdp_manage_conferences',
        'delete_private_posts' => 'svdp_manage_conferences',
        'delete_published_posts' => 'svdp_manage_conferences',
        'delete_others_posts' => 'svdp_manage_conferences',
        'edit_private_posts' => 'svdp_manage_conferences',
        'edit_published_posts' => 'svdp_manage_conferences',
        'create_posts' => 'svdp_manage_conferences',
    ];
}

function get_dashboard_item_capabilities() {
    return [
        'edit_post' => 'svdp_edit_dashboard_items',
        'read_post' => 'svdp_edit_dashboard_items',
        'delete_post' => 'svdp_delete_dashboard_items',
        'edit_posts' => 'svdp_edit_dashboard_items',
        'edit_others_posts' => 'svdp_edit_others_dashboard_items',
        'publish_posts' => 'svdp_publish_dashboard_items',
        'read_private_posts' => 'svdp_publish_dashboard_items',
        'delete_posts' => 'svdp_delete_dashboard_items',
        'delete_private_posts' => 'svdp_delete_dashboard_items',
        'delete_published_posts' => 'svdp_delete_dashboard_items',
        'delete_others_posts' => 'svdp_delete_dashboard_items',
        'edit_private_posts' => 'svdp_edit_others_dashboard_items',
        'edit_published_posts' => 'svdp_edit_others_dashboard_items',
        'create_posts' => 'svdp_edit_dashboard_items',
    ];
}

function get_announcement_capabilities() {
    return [
        'edit_post' => 'svdp_edit_announcements',
        'read_post' => 'svdp_edit_announcements',
        'delete_post' => 'svdp_delete_announcements',
        'edit_posts' => 'svdp_edit_announcements',
        'edit_others_posts' => 'svdp_edit_others_announcements',
        'publish_posts' => 'svdp_publish_announcements',
        'read_private_posts' => 'svdp_publish_announcements',
        'delete_posts' => 'svdp_delete_announcements',
        'delete_private_posts' => 'svdp_delete_announcements',
        'delete_published_posts' => 'svdp_delete_announcements',
        'delete_others_posts' => 'svdp_delete_announcements',
        'edit_private_posts' => 'svdp_edit_others_announcements',
        'edit_published_posts' => 'svdp_edit_others_announcements',
        'create_posts' => 'svdp_edit_announcements',
    ];
}

function get_document_capabilities() {
    return [
        'edit_post' => 'svdp_edit_documents',
        'read_post' => 'svdp_edit_documents',
        'delete_post' => 'svdp_delete_documents',
        'edit_posts' => 'svdp_edit_documents',
        'edit_others_posts' => 'svdp_edit_others_documents',
        'publish_posts' => 'svdp_publish_documents',
        'read_private_posts' => 'svdp_publish_documents',
        'delete_posts' => 'svdp_delete_documents',
        'delete_private_posts' => 'svdp_delete_documents',
        'delete_published_posts' => 'svdp_delete_documents',
        'delete_others_posts' => 'svdp_delete_documents',
        'edit_private_posts' => 'svdp_edit_others_documents',
        'edit_published_posts' => 'svdp_edit_others_documents',
        'create_posts' => 'svdp_edit_documents',
    ];
}

function get_event_capabilities() {
    return [
        'edit_post' => 'svdp_edit_events',
        'read_post' => 'svdp_edit_events',
        'delete_post' => 'svdp_delete_events',
        'edit_posts' => 'svdp_edit_events',
        'edit_others_posts' => 'svdp_edit_others_events',
        'publish_posts' => 'svdp_publish_events',
        'read_private_posts' => 'svdp_publish_events',
        'delete_posts' => 'svdp_delete_events',
        'delete_private_posts' => 'svdp_delete_events',
        'delete_published_posts' => 'svdp_delete_events',
        'delete_others_posts' => 'svdp_delete_events',
        'edit_private_posts' => 'svdp_edit_others_events',
        'edit_published_posts' => 'svdp_edit_others_events',
        'create_posts' => 'svdp_edit_events',
    ];
}
