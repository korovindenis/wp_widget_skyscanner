<?php

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
/**
 * Adds Sky_Scanner_Widget.
 */
if (!class_exists('SkSknr_MainWidget')) {
    class SkSknr_MainWidget extends WP_Widget {
        private $plugSlug;
        private $textDomain;
        private $plugName;

        /**
         * Register widget with WordPress.
         */
        public function __construct($parameters) {
            $this->plugSlug = $parameters->plugSlug;
            $this->textDomain = $parameters->textDomain;
            $this->plugName = $parameters->plugName;

            parent::__construct(
                $this->plugSlug, // Base ID
                esc_html__($this->plugName, $this->textDomain), // Name
                ['description' => esc_html__($this->plugName, $this->textDomain), ] // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         */
        public function widget($args, $instance) {
            echo $args['before_widget'];
            echo do_shortcode('[' . $this->plugSlug . ']');
        }

        /**
         * Back-end widget form.
         *
         */
        public function form($instance) {
            ?>
            <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugSlug)); ?>"><?php echo esc_html__('Widget settings', $this->textDomain); ?></a>
            </p>
            <?php
        }
    } // class MainWidget
}
