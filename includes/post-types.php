<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_post_type_registry(): array
{
    $supports = ['title', 'editor', 'excerpt', 'thumbnail'];

    return [
        'svdp_conf' => [
            'labels' => ['name' => 'Conferences', 'singular_name' => 'Conference'],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => ['title'],
        ],
        'svdp_dash_item' => [
            'labels' => ['name' => 'Dashboard Items', 'singular_name' => 'Dashboard Item'],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => $supports,
        ],
        'svdp_announcement' => [
            'labels' => ['name' => 'Announcements', 'singular_name' => 'Announcement'],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => $supports,
        ],
        'svdp_doc' => [
            'labels' => ['name' => 'Documents', 'singular_name' => 'Document'],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => $supports,
        ],
        'svdp_event' => [
            'labels' => ['name' => 'Events', 'singular_name' => 'Event'],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => $supports,
        ],
    ];
}

function vincentian_hub_register_post_types(): void
{
    if (! function_exists('register_post_type')) {
        return;
    }

    foreach (vincentian_hub_post_type_registry() as $postType => $args) {
        register_post_type($postType, $args);
    }
}

if (function_exists('add_action')) {
    add_action('init', 'vincentian_hub_register_post_types', 5);
}
