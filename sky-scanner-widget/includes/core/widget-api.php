<?php
/**
 * An REST API is an Application Programming Interface - SkSknr_RestApiPlugin
 */
if (!class_exists('SkSknr_RestApiPlugin')) {
    class SkSknr_RestApiPlugin {
        private $plugSlug;
        private $plugPath;
        private $tableName;
        private $textDomain;

        /**
         * Register:
         * register_activation_hook - When a plugin is activated
         * rest_api_init - Registers rewrite rules for the API
         * add_shortcode - Adds a new shortcode
         */
        public function __construct($parameters) {
            $this->plugSlug = $parameters->plugSlug;
            $this->plugPath = $parameters->plugPath;
            $this->textDomain = $parameters->textDomain;
            $this->tableName = $this->getTableName();
            
            register_activation_hook($this->plugPath, [$this, 'upgrade']);
            add_action('rest_api_init', [$this, 'registerRoutes']);
            add_shortcode($this->plugSlug, [$this, 'addShortcode']);
        }

        /**
         * Wordpress restapi
         */
        public function registerRoutes() {
            register_rest_route($this->plugSlug, '/admin/widgets/(?P<endpoint>[\w-]+)', [
                'methods' => 'GET, POST',
                'callback' => [$this, 'restApi'],
                'args' => [
                    'endpoint' => [
                        'required' => true
                    ]
                ]
            ]);
        }

        public function restApi(\WP_REST_Request $request) {
            $result = [];

            $method = $request->get_method();
            $endpoint = $request->get_param('endpoint');
            $endpoint_handler_name = strtolower($method) . ucfirst($endpoint);

            if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
                return new \WP_REST_Response([
                    'status' => false,
                    'error' => esc_html__('Scrape nonce check failed. Please try again.', $this->textDomain)
                ], 400);
            }

            if (!method_exists($this, $endpoint_handler_name)) {
                return new \WP_REST_Response([
                    'status' => false,
                    'error' => sprintf(esc_html__('Unknown endpoint', $this->textDomain) . ' "%s/%s"', $method, $endpoint)
                ], 400);
            }

            call_user_func_array([$this, $endpoint_handler_name], [&$result]);

            return new \WP_REST_Response($result, 200);
        }

        /**
         * Get widget-html for backend
         */
        public function getWidgetFormSettings(&$result) {
            global $wpdb;

            $wdgetCode = $wpdb->get_var('SELECT `frontend_options` FROM ' . $this->tableName . ' WHERE 1');
            if (is_null($wdgetCode) != true && $wdgetCode != 'NULL') {
                $result['default'] = false;
                $result['wdgt'] = $wdgetCode;
            } else {
                $result['default'] = true;
            }
            $result['default'] = $wdgetCode;
        }

        /**
         * Save widget-html for frontend
         */
        public function postWidgetFormSettings(&$result) {
            global $wpdb;

            $data = $this->getPayloadData();
            $status = $wpdb->query($wpdb->prepare('UPDATE ' . $this->tableName . " SET frontend_options='%s'", $data['wdgt']));
            $result = ['status' => $status];
        }

        /**
        * Add Wordpress shortcode
        */
        public function addShortcode() {
            global $wpdb;

            $wdgetCode = $wpdb->get_var('SELECT `frontend_options` FROM ' . $this->tableName . ' WHERE 1');
            if (is_null($wdgetCode) != true && $wdgetCode != 'NULL') {
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

            $wpdb->query('ALTER TABLE ' . $this->tableName . " MODIFY `frontend_options` longtext COLLATE $table_collate;");
        }

        /**
         * Check exist table in database
         */
        public function tableExists() {
            global $wpdb;

            return !!$wpdb->get_var($wpdb->prepare(
                'SHOW TABLES LIKE %s',
                $this->tableName
            ));
        }

        /**
         * Create table in database
         */
        public function createTable() {
            global $wpdb;

            $is_old_sql = version_compare($wpdb->db_version(), '5.5.3', '<=');
            $table_collate = $is_old_sql ? 'utf8_general_ci' : 'utf8mb4_general_ci';

            $wpdb->query(
                'CREATE TABLE ' . $this->tableName . " (
                    `frontend_options` longtext COLLATE $table_collate
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;"
            );
            $wpdb->query(
                'INSERT INTO ' . $this->tableName . " (`frontend_options`) VALUES ('NULL')"
            );
        }

        /**
         * Get table name from database
         */
        public function getTableName() {
            global $wpdb;

            return esc_sql($wpdb->prefix . str_replace('-', '_', $this->plugSlug) . '_widgets');
        }

        protected function getPayloadData() {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        }
    } // class SkSknr_RestApiPlugin
}
