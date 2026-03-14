<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_admin_menu_definitions() {
    return [
        [
            'type' => 'menu',
            'page_title' => 'Vincentian Hub',
            'menu_title' => 'Vincentian Hub',
            'capability' => 'svdp_view_portal_admin',
            'menu_slug' => 'vincentian-hub',
        ],
        [
            'type' => 'submenu',
            'parent_slug' => 'vincentian-hub',
            'page_title' => 'Branding Settings',
            'menu_title' => 'Settings',
            'capability' => get_branding_settings_capability(),
            'menu_slug' => get_branding_settings_page_slug(),
        ],
        [
            'type' => 'submenu',
            'parent_slug' => 'vincentian-hub',
            'page_title' => 'Drive Imports',
            'menu_title' => 'Drive Imports',
            'capability' => 'svdp_manage_drive_imports',
            'menu_slug' => 'vincentian-hub-drive-imports',
        ],
    ];
}

function register_admin_menus() {
    foreach (get_admin_menu_definitions() as $menu) {
        if ($menu['type'] === 'menu') {
            add_menu_page(
                $menu['page_title'],
                $menu['menu_title'],
                $menu['capability'],
                $menu['menu_slug'],
                '__return_true'
            );
            continue;
        }

        add_submenu_page(
            $menu['parent_slug'],
            $menu['page_title'],
            $menu['menu_title'],
            $menu['capability'],
            $menu['menu_slug'],
            '__return_true'
        );
    }
}
