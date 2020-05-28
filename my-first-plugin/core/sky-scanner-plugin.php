<?php

if (!defined('ABSPATH')) exit;

require_once(plugin_dir_path(__FILE__) . '/includes/widget.php');
require_once(plugin_dir_path(__FILE__) . '/includes/admin.php');
require_once(plugin_dir_path(__FILE__) . '/includes/widget-api.php');

if (!class_exists('InitPlugin')) {
    class InitPlugin {
        private $widget;
        private $admin;
        public $plugName;
        public $plugSlug;
        public $plugPath;
        public $icon_menu;
        public $version;
        public $widgetApi;

        public function __construct($parameters) {
            $this->plugName = $parameters['plugin_name'];
            $this->plugPath = $parameters['plugin_path'];
            $this->plugSlug = $parameters['plugin_slug'];
            $this->version = $parameters['version'];
            $this->widget = new MainWidget($this);
            $this->admin = new PluginAdmin($this);
            $this->widgetApi = new RestApiPlugin($this);
            add_action('widgets_init', array($this, 'registerWidget'));
            //add_action('plugin_action_links_' . $this->plugName, array($this, 'addLinks'));
        }

        public function registerWidget() {
            if (!empty($this->widget)) {
                if (!get_option($this->getParamEnv('widget_hash'))) {
                    register_widget($this->widget);

                    add_option($this->getParamEnv('widget_hash'), spl_object_hash($this->widget));
                } else {
                    global $wp_widget_factory;

                    $wp_widget_factory->widgets[get_option($this->getParamEnv('widget_hash'))] = $this->widget;
                }
            }
        }

        private function getParamEnv($prm) {
            return str_replace('-', '_', $this->plugName) . '_' . $prm;
        }

        //public function addLinks($links) {
        //    $links[] = '<a href="' . esc_url(admin_url('admin.php?page=' . $this->plugName)) . '">Settings</a>';
           // $links[] = '<a href="http://codecanyon.net/user/elfsight/portfolio?ref=Elfsight" target="_blank">More plugins by Elfsight</a>';

        //    return $links;
        //}
    }
}
