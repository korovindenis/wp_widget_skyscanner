<?php
/*
Plugin Name: Sky Scanner Widget
Description: Simple Flight Search Widget
Plugin URI: https://localhost/
Version: 1.0.0
Author: DenKrvn
Author URI: https://localhost/
*/

if (!defined('ABSPATH')) exit;

require_once('core/sky-scanner-plugin.php');

new InitPlugin(array(
    'plugin_name' => esc_html__('Sky Scanner'),
    'plugin_slug' => esc_html__('skyscannerwidget'),
    'icon_menu' => plugins_url('assets/img/icon_menu.svg', __FILE__),
    'plugin_path' => __FILE__,
    'version' => '1.0.0'
));
