<?php
namespace VincentianHub;

if (!defined('ABSPATH')) {
    exit;
}

function get_directory_table_name() {
    global $wpdb;

    return $wpdb->prefix . 'svdp_directory';
}

function get_directory_table_schema() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = get_directory_table_name();

    return "CREATE TABLE {$table_name} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        first_name varchar(191) NOT NULL DEFAULT '',
        last_name varchar(191) NOT NULL DEFAULT '',
        email varchar(191) NOT NULL DEFAULT '',
        phone varchar(50) NOT NULL DEFAULT '',
        conference_id bigint(20) unsigned DEFAULT NULL,
        account_scope varchar(32) NOT NULL DEFAULT '',
        default_profiles longtext NULL,
        auto_approve tinyint(1) NOT NULL DEFAULT 0,
        source_label varchar(100) NOT NULL DEFAULT '',
        updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY email (email),
        KEY conference_id (conference_id)
    ) {$charset_collate};";
}

function create_directory_table() {
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta(get_directory_table_schema());
}
