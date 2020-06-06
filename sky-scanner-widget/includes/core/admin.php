<?php

if (!defined('ABSPATH')) {
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
<!-- =================== Start SkyScannerWidget (main block) =================== -->
<script>
    window.pluginParams = {
        restApiUrl: '<?php echo rest_url($this->plugSlug . '/admin/widgets/') ?>',
    }
</script>
<!-- =================== Start widget block =================== -->
<div class="skyscanner_widget">
    
    <!-- =================== Start Menu =================== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"><?php echo esc_html__($this->plugName . ' Widget', $this->textDomain); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://www.partners.skyscanner.net/affiliates/widgets-documentation/simple-flight-search-widget"><?php echo esc_html__('Documentation', $this->textDomain); ?></a> </li>
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://codecanyon.net/user/skyengineers/"><?php echo esc_html__('Need Help?', $this->textDomain); ?></a> </li>
                <li class="nav-item"> <a class="nav-link" target="_blank" href="https://codecanyon.net/user/skyengineers/"><?php echo esc_html__('Author', $this->textDomain); ?></a> </li>
                <div class="dropdown-divider"></div>
                <li class="nav-item"><a class="nav-link disabled" href="#"><?php echo esc_html__('Version', $this->textDomain) . ' ' . $this->version; ?></a></li>
            </ul>
        </div>
    </nav>
    <!-- =================== End Menu =================== -->

    <div class="main_background">
        
        <!-- =================== Start white-box =================== -->
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
                
                <!-- =================== Start description =================== -->
                <div class="row">
                    <div class="col">
                        <h2 class="h2_logo">
                        <?php echo esc_html__('Widget Builder', $this->textDomain); ?>
                        </h2>
                        <p class="descr_build"><?php echo esc_html__('On this page, you can customize the flight search widget as you wish.', $this->textDomain); ?><span class="descr_build__panel">&nbsp;<?php echo esc_html__('Just use the panel on the left!', $this->textDomain); ?></span>
                        <br>
                        <?php echo esc_html__('There are many setting options—make sure you try them all!', $this->textDomain); ?></p>
                    </div>
                </div>
                <!-- =================== End description =================== -->

                <div class="row">
                    <div class="col">
                        <hr>
                    </div>
                </div>

                <!-- =================== Start block with settings widget =================== -->
                <div class="row">
                    <div class="col">
                        <oc-component href="https://gateway.skyscanner.net/oc-registry/affiliate-widgets-constructor/?defaultWidget=SearchWidget&amp;paramSet=public&amp;hiddenParams=skyscannerWidget"></oc-component>
                        <script src="https://gateway.skyscanner.net/oc-registry/oc-client/client.js"></script>
                    </div>
                </div>
                <!-- =================== End block with settings widget =================== -->

                <!-- =================== Start block with messages and save,reload button =================== -->
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
                        <?php if (current_user_can('manage_options')) { ?>
                        <button type="button" id="save_button" class="btn btn-info act_button"><?php echo esc_html__('Save widget', $this->textDomain); ?></button>
                        <button type="button" id="reset_button" class="btn btn-info act_button"><?php echo esc_html__('Reset widget', $this->textDomain); ?></button>
                        <?php } ?>
                    </div>
                </div>
                <!-- =================== End block with messages and save,reload button =================== -->

                <!-- =================== Start Terms and Conditions =================== -->
                <div class="row">
                    <div class="col">
                    <p><?php esc_html__("By using Skyscanner's Widgets you agree to our", $this->textDomain); ?> <a target="_blank" href="https://www.partners.skyscanner.net/affiliates/travel-widgets-terms-and-conditions/"><?php esc_html__("Terms and Conditions.", $this->textDomain); ?></a></p>
                    </div>
                </div>
                <!-- =================== End Terms and Conditions =================== -->

            </div>
        </div>
         <!-- =================== End white-box =================== -->
    </div>
</div>
<!-- =================== End widget block =================== -->
<!-- =================== End SkyScannerWidget (main block) =================== -->
<?php
        }

        /**
         * Add script and css
         */
        public function registerAssets() {
            wp_register_style('bootstrap', plugins_url('/admin/css/bootstrap.min.css', $this->plugPath), [], '4.3.1');
            wp_register_style($this->plugSlug . '-main-admin-css', plugins_url('/admin/css/main.css', $this->plugPath), [], $this->version);
            wp_register_style($this->plugSlug . '-loader-admin', plugins_url('/admin/css/loader.css', $this->plugPath), [], $this->version);
            wp_register_script('bootstrap', plugins_url('admin/js/bootstrap.min.js', $this->plugPath), ['jquery'], '4.0.0', true);
            wp_register_script($this->plugSlug . '-main-admin', plugins_url('admin/js/main.js', $this->plugPath), ['jquery', 'wp-api'], $this->version, true);
        }

        public function enqueueAssets($hook) {
            if ($hook && $hook == $this->menuId) {
                wp_enqueue_style('bootstrap');
                wp_enqueue_style($this->plugSlug . '-main-admin-css');
                add_action('admin_footer', [$this, 'setInFooter']);
            }
        }

        /**
         * Set .css,.js in footer
         */
        public function setInFooter() {
            wp_enqueue_style($this->plugSlug . '-loader-admin');
            wp_enqueue_script('bootstrap');
            wp_enqueue_script($this->plugSlug . '-main-admin');
        }

        private function getOptNm($prm) {
            return str_replace('-', '_', $this->plugSlug) . '_' . $prm;
        }
    } // class SkSknr_PluginAdmin
}
