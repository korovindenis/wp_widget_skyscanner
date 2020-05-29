<?php

if (!defined('ABSPATH')) exit;


if (!class_exists('RestApiPlugin')) {
    class RestApiPlugin {
        private $plugSlug;
        private $plugPath;
        //private $tableName;

        public function __construct($parameters){
            $this->plugSlug = $parameters->plugSlug;
            $this->plugPath = $parameters->plugPath;
            //$this->tableName = $this->getTableName();

            register_activation_hook($this->plugPath, array($this, 'upgrade'));
            add_action('rest_api_init', array($this, 'registerRoutes'));
            add_shortcode($this->plugSlug, array($this, 'addShortcode'));

        }

        public function registerRoutes() {
            register_rest_route($this->plugSlug, '/admin/widgets/(?P<endpoint>[\w-]+)', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'restApi'),
                'args' => array(
                    'endpoint' => array(
                        'required' => true
                    )
                )
            ));
        }

        public function restApi(\WP_REST_Request $request) {
            $result = array();

            $method = $request->get_method();
            $endpoint = $request->get_param('endpoint');
            $endpoint_handler_name = strtolower($method) . ucfirst($endpoint);

            if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
                return new \WP_REST_Response(array(
                    'status' => false,
                    'error' => __('Scrape nonce check failed. Please try again.')
                ), 400);
            }

            if (!method_exists($this, $endpoint_handler_name)) {
                return new \WP_REST_Response(array(
                    'status' => false,
                    'error' => sprintf('Unknown endpoint "%s/%s"', $method, $endpoint)
                ), 400);
            }

            call_user_func_array(array($this, $endpoint_handler_name), array(&$result));

            return new \WP_REST_Response($result, 200);
        }

        public function getWidgetFormSettings(&$result) {
            global $wpdb;

            $table_name = $this->getTableName();

            $wdgetCode = $wpdb->get_var("SELECT `frontend_options` FROM $table_name WHERE 1");
            if(is_null($wdgetCode) != true && $wdgetCode != "NULL"){
                $result['default'] = false;
                $result['wdgt'] = $wdgetCode;
            } else {
                $result['default'] = true;
            }
            $result['default']=  $wdgetCode;
        }

        public function postWidgetFormSettings(&$result) {
            global $wpdb;

            $table_name = $this->getTableName();
            $data = $this->getPayloadData();
            $status = $wpdb->query($wpdb->prepare("UPDATE $table_name SET frontend_options='%s'",$data['wdgt']));
            $result = array("status"=>$status);
        }

        public function addShortcode() {
            global $wpdb;

            $table_name = $this->getTableName();

            $wdgetCode = $wpdb->get_var("SELECT `frontend_options` FROM $table_name WHERE 1");
            if(is_null($wdgetCode) != true && $wdgetCode != "NULL"){
                return $wdgetCode;
            } else {
                return '<div data-skyscanner-widget="SearchWidget"></div><script src="https://widgets.skyscanner.net/widget-server/js/loader.js" async></script>';
            }
        }

        public function upgrade() {
            if ($this->tableExists()) {
                $this->alterTable();
            } else {
                $this->createTable();
            }
        }

        public function alterTable() {
            global $wpdb;

            $is_old_sql = version_compare($wpdb->db_version(), '5.5.3', '<=');
            $table_collate = $is_old_sql ? 'utf8_general_ci' : 'utf8mb4_general_ci';
            $table_name = $this->getTableName();

            $wpdb->query("ALTER TABLE $table_name MODIFY `frontend_options` longtext COLLATE $table_collate NOT NULL;");
        }

        public function tableExists() {
            global $wpdb;

            return !!$wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $this->getTableName()
            ));
        }

        public function createTable() {
            global $wpdb;

            $is_old_sql = version_compare($wpdb->db_version(), '5.5.3', '<=');
            $table_collate = $is_old_sql ? 'utf8_general_ci' : 'utf8mb4_general_ci';
            $table_name = $this->getTableName();

            $wpdb->query(
                "CREATE TABLE $table_name (
                    `frontend_options` longtext COLLATE $table_collate NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;"
            );
            $wpdb->insert($table_name, array('frontend_options' => 'NULL'));
        }

        public function getTableName() {
            global $wpdb;

            return esc_sql($wpdb->prefix . str_replace('-', '_', $this->plugSlug) . '_widgets');
        }

        protected function getPayloadData() {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        }
    }
}