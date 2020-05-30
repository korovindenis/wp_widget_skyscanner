<?php
/*
Plugin Name: Sky Scanner Widget
Description: Simple Flight Search Widget
Plugin URI: https://localhost/
Version: 1.0.0
Author: DenKrvn
Author URI: https://localhost/
*/

/**
 * 1Plugin Name:       My Basics Plugin
 * 1Plugin URI:        https://example.com/plugins/the-basics/
 * 1Description:       Handle the basics with this plugin.
 * 1Version:           1.0.0
 * 1Requires at least: 5.2
 * 1Requires PHP:      7.2
 * 1Author:            John Smith
 * 1Author URI:        https://author.example.com/
 * 1License:           GPL v2 or later
 * 1License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * 1Text Domain:       my-basics-plugin
 * 1Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once 'includes/sky-scanner-plugin.php';

/**
 * Initializing the main class
 */
new SkSknr_InitPlugin([
    'version' => '1.0.0',
    'icon_menu' => plugins_url('admin/images/icon_menu.png', __FILE__),
    'plugin_name' => esc_html__('Sky Scanner'),
    'plugin_slug' => esc_html__('skyscannerwidget'),
    'plugin_path' => __FILE__,
    'plugin_basename' => plugin_basename(__FILE__)
]);
