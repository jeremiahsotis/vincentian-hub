<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_taxonomy_definitions() {
    return [
        'svdp_doc_cat' => [
            'object_type' => ['svdp_doc'],
            'label' => 'Document Categories',
            'singular' => 'Document Category',
            'capabilities' => [
                'manage_terms' => 'svdp_publish_documents',
                'edit_terms' => 'svdp_edit_documents',
                'delete_terms' => 'svdp_delete_documents',
                'assign_terms' => 'svdp_edit_documents',
            ],
        ],
    ];
}

function register_taxonomies() {
    foreach (get_taxonomy_definitions() as $taxonomy => $definition) {
        register_taxonomy($taxonomy, $definition['object_type'], [
            'labels' => [
                'name' => $definition['label'],
                'singular_name' => $definition['singular'],
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'hierarchical' => true,
            'rewrite' => false,
            'capabilities' => $definition['capabilities'],
        ]);
    }
}
