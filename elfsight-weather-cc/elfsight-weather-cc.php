<?php
/*
Plugin Name: Elfsight Weather CC
Description: Weather for any location in a simple widget on your site.
Plugin URI: https://elfsight.com/weather-widget/codecanyon/?utm_source=markets&utm_medium=codecanyon&utm_campaign=weather&utm_content=plugin-site
Version: 1.1.0
Author: Elfsight
Author URI: https://elfsight.com/?utm_source=markets&utm_medium=codecanyon&utm_campaign=weather&utm_content=plugins-list
*/

if (!defined('ABSPATH')) exit;


require_once('core/elfsight-plugin.php');

$elfsight_weather_config_path = plugin_dir_path(__FILE__) . 'config.json';
$elfsight_weather_config = json_decode(file_get_contents($elfsight_weather_config_path), true);

new ElfsightWeatherPlugin(
    array(
        'name' => esc_html__('Weather'),
        'description' => esc_html__('Weather for any location in a simple widget on your site.'),
        'slug' => 'elfsight-weather',
        'version' => '1.1.0',
        'text_domain' => 'elfsight-weather',

        'editor_settings' => $elfsight_weather_config['settings'],
        'editor_preferences' => $elfsight_weather_config['preferences'],

        'plugin_name' => esc_html__('Elfsight Weather'),
        'plugin_file' => __FILE__,
        'plugin_slug' => plugin_basename(__FILE__),

        'vc_icon' => plugins_url('assets/img/vc-icon.png', __FILE__),
        'menu_icon' => plugins_url('assets/img/menu-icon.svg', __FILE__),

        'product_url' => esc_url('https://codecanyon.net/item/elfsight-weather-forecast/25311371?ref=Elfsight'),
        'support_url' => esc_url('https://elfsight.ticksy.com/submit/#100016141'),
        'update_url' => esc_url('https://a.elfsight.com/updates/v1/')
    )
);

?>
