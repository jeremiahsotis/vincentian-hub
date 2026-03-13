<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_taxonomy_registry(): array
{
    return [
        'svdp_doc_cat' => [
            'object_type' => ['svdp_doc'],
            'args' => [
                'labels' => [
                    'name' => 'Document Categories',
                    'singular_name' => 'Document Category',
                ],
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'hierarchical' => true,
            ],
        ],
    ];
}

function vincentian_hub_register_taxonomies(): void
{
    if (! function_exists('register_taxonomy')) {
        return;
    }

    foreach (vincentian_hub_taxonomy_registry() as $taxonomy => $config) {
        register_taxonomy($taxonomy, $config['object_type'], $config['args']);
    }
}

if (function_exists('add_action')) {
    add_action('init', 'vincentian_hub_register_taxonomies', 5);
}
