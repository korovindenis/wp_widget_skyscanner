<?php

if ((!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) || !is_admin()) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
 * Delete table from database
 */
global $wpdb;
$table_name = $wpdb->prefix . 'skyscannerwidget_widgets';
$wpdb->query("DROP TABLE IF EXISTS `$table_name`");
