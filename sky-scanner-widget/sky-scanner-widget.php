<?php
/**
 * Plugin Name:       Sky Scanner widget
 * Plugin URI:        https://codecanyon.net/user/skyengineers/
 * Description:       Simple Flight Search Widget
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            SkyEngineers
 * Author URI:        https://codecanyon.net/user/skyengineers/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       sky-scanner-widget
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once 'includes/sky-scanner-plugin.php';

/**
 * Initializing the main class
 */
new SkSknr_InitPlugin([
    'version' => '1.0.0',
    'icon_menu' => plugins_url('admin/images/icon_menu.png', __FILE__),
    'text_domain' => 'sky-scanner-widget',
    'plugin_name' => esc_html__('Sky Scanner'),
    'plugin_slug' => esc_html__('skyscannerwidget'),
    'plugin_path' => __FILE__,
    'plugin_basename' => plugin_basename(__FILE__)
]);
