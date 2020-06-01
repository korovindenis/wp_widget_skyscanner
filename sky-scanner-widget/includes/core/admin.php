<?php

if (!defined('ABSPATH') || !is_admin()){
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
/**
 * Admin functions - SkSknr_PluginAdmin
 */
if (!class_exists('SkSknr_PluginAdmin')) {
    class SkSknr_PluginAdmin {
        private $plugName;
        private $plugSlug;
        private $capability;
        private $icon_menu;
        private $version;
        private $plugPath;
        private $menuId;
        private $textDomain;

        private $capabRole = [
            'admin' => 'manage_options',
            'editor' => 'edit_pages',
            'author' => 'publish_posts'
        ];

        /**
         * Register:
         * admin_menu - Fires before the administration menu loads in the admin.
         * admin_init - Fires as an admin screen or script is being initialized.
         * admin_enqueue_scripts - Enqueue admin scripts and styles.
         */
        public function __construct($parameters) {
            $this->plugName = $parameters->plugName;
            $this->plugSlug = $parameters->plugSlug;
            $this->icon_menu = $parameters->icon_menu;
            $this->version = $parameters->version;
            $this->plugPath = $parameters->plugPath;
            $this->textDomain = $parameters->textDomain;

            $this->capability = apply_filters('_admin_capability_', $this->capabRole[get_option($this->getOptNm('access_role'), 'admin')]);

            add_action('admin_menu', [$this, 'addToMenu']);
            add_action('admin_init', [$this, 'registerAssets']);
            add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        }

        public function addToMenu() {
            $this->menuId = add_menu_page($this->plugName, $this->plugName, $this->capability, $this->plugSlug, [$this, 'getPage'], $this->icon_menu);
        }

        /**
         * Admin page content
         */
        public function getPage() {
        ?>
<script>
    window.pluginParams = {
        restApiUrl: '<?php echo rest_url($this->plugSlug . '/admin/widgets/') ?>',
    }

</script>
<div class="skyscanner_widget">
    <nav class="navbar navbar-expand-lg navbar-light bg-light"> <a class="navbar-brand" href="#">
        <?php echo esc_html__($this->plugName . " Widget", $this->textDomain); ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://www.partners.skyscanner.net/affiliates/widgets-documentation/simple-flight-search-widget"><?php echo esc_html__('Documentation', $this->textDomain); ?></a> </li>
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://codecanyon.net/user/skyengineers/"><?php echo esc_html__('Need Help?', $this->textDomain); ?></a> </li>
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://codecanyon.net/user/skyengineers/"><?php echo esc_html__('Author', $this->textDomain); ?></a> </li>
                <div class="dropdown-divider"></div>
                <li class="nav-item"> <a class="nav-link disabled" href="#"><?php echo esc_html__('Version', $this->textDomain) . " " . $this->version; ?></a> </li>
            </ul>
        </div>
    </nav>
    <div class="main_background">
        <div class="main">
            <div class="loader">
                <div class="circle">
                    <div class="inner"></div>
                </div>
                <div class="circle">
                    <div class="inner"></div>
                </div>
                <div class="circle">
                    <div class="inner"></div>
                </div>
                <div class="circle">
                    <div class="inner"></div>
                </div>
                <div class="circle">
                    <div class="inner"></div>
                </div>
            </div>
            <div id="main_container" class="container off_data">
                <div class="row">
                    <div class="col">
                        <h2 class="h2_logo">
                        <?php echo esc_html__('Widget Builder', $this->textDomain); ?>
                        </h2>
                        <?php echo __('<p class="descr_build"> Here you can play around with parameters of widgets and construct <b>Simple Flight Search Widget of your preference</b><span class="descr_build__panel">&nbsp;using the side panel on the left</span>.
                            <br>There are many of them, make sure you find the right one for you! </p>'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <oc-component href="https://gateway.skyscanner.net/oc-registry/affiliate-widgets-constructor/?defaultWidget=SearchWidget&amp;paramSet=public&amp;hiddenParams=skyscannerWidget"></oc-component>
                        <script src="https://gateway.skyscanner.net/oc-registry/oc-client/client.js"></script>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div id="success_msg" class="alert alert-success alert-dismissible" role="alert">
                        <?php echo esc_html__('Successfully Saved', $this->textDomain); ?>
                        </div>
                        <div id="failed_msg" class="alert alert-danger alert-dismissible" role="alert">
                            Saved was failed
                            <hr>
                            <p class="mb-0"><?php echo esc_html__('Please, check plugin settings or reinstall him', $this->textDomain); ?></p>
                        </div>
                        <?php if(current_user_can('manage_options')) { ?>
                        <button type="button" id="save_button" class="btn btn-info act_button"><?php echo esc_html__('Save widget', $this->textDomain); ?></button>
                        <button type="button" id="reset_button" class="btn btn-info act_button"><?php echo esc_html__('Reset widget', $this->textDomain); ?></button>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <?php echo __('<p>By using Skyscanner\'s Widgets you agree to our <a target="_blank" href="https://www.partners.skyscanner.net/affiliates/travel-widgets-terms-and-conditions/">Terms and Conditions</a>.</p>', $this->textDomain); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        }

        /**
         * Add script and css
         */
        public function registerAssets() {
            wp_register_style($this->plugSlug . '-bootstrap-admin', plugins_url('/admin/css/bootstrap.min.css', $this->plugPath), [], '4.3.1');
            wp_register_style($this->plugSlug . '-main-admin', plugins_url('/admin/css/main.css', $this->plugPath), [], $this->version);
            wp_register_style($this->plugSlug . '-loader-admin', plugins_url('/admin/css/loader.css', $this->plugPath), [], $this->version);
            wp_register_script($this->plugSlug . '-bootstrapjs-admin', plugins_url('admin/js/bootstrap.min.js', $this->plugPath), ['jquery'], '4.0.0', true);
            wp_register_script($this->plugSlug . '-jq-admin', plugins_url('admin/js/jquery-3.5.1.min.js', $this->plugPath), ['jquery'], '3.5.1', true);
            wp_register_script($this->plugSlug . '-mainjs-admin', plugins_url('admin/js/main.js', $this->plugPath), ['jquery', 'wp-api', $this->plugSlug . '-jq-admin'], $this->version, true);
        }

        public function enqueueAssets($hook) {
            if ($hook && $hook == $this->menuId) {
                wp_enqueue_style($this->plugSlug . '-bootstrap-admin');
                wp_enqueue_style($this->plugSlug . '-main-admin');

                add_action('admin_footer', [$this, 'setInFooter']);
            }
        }

        public function setInFooter() {
            wp_enqueue_style($this->plugSlug . '-loader-admin');
            wp_enqueue_script($this->plugSlug . '-bootstrapjs-admin');
            wp_enqueue_script($this->plugSlug . '-mainjs-admin');
            wp_enqueue_script($this->plugSlug . '-jq-admin');
        }

        private function getOptNm($prm) {
            return str_replace('-', '_', $this->plugSlug) . '_' . $prm;
        }
    } // class SkSknr_PluginAdmin
}
