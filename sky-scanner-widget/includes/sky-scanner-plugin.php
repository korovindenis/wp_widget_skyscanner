<?php

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once plugin_dir_path(__FILE__) . '/core/widget.php';
require_once plugin_dir_path(__FILE__) . '/core/admin.php';
require_once plugin_dir_path(__FILE__) . '/core/widget-api.php';

/**
 * Main class - SkSknr_InitPlugin
 */
if (!class_exists('SkSknr_InitPlugin')) {
    class SkSknr_InitPlugin {
        private $widget;
        private $admin;
        public $plugName;
        public $plugSlug;
        public $plugPath;
        public $icon_menu;
        public $version;
        public $widgetApi;
        public $plugBaseName;
        public $textDomain;

        /**
         * Register plugin
         */
        public function __construct($parameters) {
            $this->plugName = $parameters['plugin_name'];
            $this->plugPath = $parameters['plugin_path'];
            $this->plugSlug = $parameters['plugin_slug'];
            $this->plugBaseName = $parameters['plugin_basename'];
            $this->version = $parameters['version'];
            $this->icon_menu = $parameters['icon_menu'];
            $this->textDomain = $parameters['text_domain'];

            $this->widget = new SkSknr_MainWidget($this);
            $this->widgetApi = new SkSknr_RestApiPlugin($this);

            /**
             * Only admin
             */
            if (is_admin()) {
                $this->admin = new SkSknr_PluginAdmin($this);
                add_action('plugin_action_links_' . $this->plugBaseName, [$this, 'addLinks']);
            }
            add_action('plugins_loaded', [$this, 'loadLanguages']);
            add_action('widgets_init', [$this, 'registerWidget']);
        }

        public function loadLanguages() {
            load_plugin_textdomain($this->textDomain, false, dirname(plugin_basename($this->plugPath)) . '/languages/');
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

        /**
         * Filters the list of action links displayed for a specific plugin in the Plugins list table.
         */
        public function addLinks($links) {
            $links[] = '<a href="' . esc_url(admin_url('admin.php?page=' . $this->plugSlug)) . '">' . esc_html__('Settings', $this->textDomain) . '</a>';
            $links[] = '<a href="https://codecanyon.net/user/skyengineers/" target="_blank">' . esc_html__('More plugins by SkyEngineers', $this->textDomain) . '</a>';

            return $links;
        }
    } // class SkSknr_InitPlugin
}
