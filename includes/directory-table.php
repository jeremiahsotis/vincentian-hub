<?php
if (! defined('ABSPATH')) {
    exit;
}

function vincentian_hub_directory_table_name(?string $prefix = null): string
{
    if ($prefix === null) {
        global $wpdb;
        $prefix = isset($wpdb->prefix) ? (string) $wpdb->prefix : '';
    }

    return $prefix . 'svdp_directory';
}

function vincentian_hub_directory_table_columns(): array
{
    return [
        'id bigint(20) unsigned NOT NULL AUTO_INCREMENT',
        'first_name varchar(191) NOT NULL DEFAULT \'\'',
        'last_name varchar(191) NOT NULL DEFAULT \'\'',
        'email varchar(191) NOT NULL DEFAULT \'\'',
        'phone varchar(50) NOT NULL DEFAULT \'\'',
        'conference_id bigint(20) unsigned DEFAULT NULL',
        'account_scope varchar(50) NOT NULL DEFAULT \'\'',
        'default_profiles longtext NULL',
        'auto_approve tinyint(1) NOT NULL DEFAULT 0',
        'source_label varchar(191) NOT NULL DEFAULT \'\'',
        'updated_at datetime NULL',
        'created_at datetime NULL',
        'PRIMARY KEY  (id)',
        'KEY email (email)',
    ];
}

function vincentian_hub_maybe_create_directory_table(): void
{
    global $wpdb;

    if (! isset($wpdb) || ! function_exists('dbDelta')) {
        return;
    }

    $tableName = vincentian_hub_directory_table_name();
    $charsetCollate = method_exists($wpdb, 'get_charset_collate')
        ? $wpdb->get_charset_collate()
        : '';

    $sql = sprintf(
        "CREATE TABLE %s (\n%s\n) %s;",
        $tableName,
        implode(",\n", vincentian_hub_directory_table_columns()),
        $charsetCollate
    );

    dbDelta($sql);
}
