<?php

if (!defined('ABSPATH')) exit;

require_once(plugin_dir_path(__FILE__) . '/core/widget.php');
require_once(plugin_dir_path(__FILE__) . '/core/admin.php');
require_once(plugin_dir_path(__FILE__) . '/core/widget-api.php');

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
        public $plugBaseName;

        public function __construct($parameters) {
            $this->plugName = $parameters['plugin_name'];
            $this->plugPath = $parameters['plugin_path'];
            $this->plugSlug = $parameters['plugin_slug'];
            $this->plugBaseName = $parameters['plugin_basename'];
            $this->version = $parameters['version'];
            $this->widget = new MainWidget($this);
            $this->widgetApi = new RestApiPlugin($this);

            if (is_admin()){
                $this->admin = new PluginAdmin($this);
                add_action('plugin_action_links_' . $this->plugBaseName, array($this, 'addLinks'));
            }
            add_action('widgets_init', array($this, 'registerWidget'));
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

        public function addLinks($links) {
            $links[] = '<a href="' . esc_url(admin_url('admin.php?page=' . $this->plugSlug)) . '">Settings</a>';
            //$links[] = '<a href="http://codecanyon.net/user/elfsight/portfolio?ref=Elfsight" target="_blank">More plugins by Elfsight</a>';

            return $links;
        }
    }
}
