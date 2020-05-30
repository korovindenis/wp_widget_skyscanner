<?php

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

// delete database table
global $wpdb;
$table_name = $wpdb->prefix . 'skyscannerwidget_widgets';
$wpdb->query("DROP TABLE IF EXISTS `$table_name`");
