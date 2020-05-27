<?php
/**
 * Adds Sky_Scanner_Widget.
 */
if (!class_exists('MainWidget')) {
	class MainWidget extends WP_Widget {
		private $plugPath;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct($parameters) {
			$this->plugPath = $parameters->plugPath;

			parent::__construct(
				'sky_scanner', // Base ID
				esc_html__( 'Sky Scanner Search', 'text_domain' ), // Name
				array( 'description' => esc_html__( 'A SkyScanner Widget', 'text_domain' ), ) // Args
			);
			add_action( 'wp_enqueue_scripts', array($this, 'sky_scanner_frontend_js_load' ) );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			//echo esc_html__( 'Hello, World!', 'text_domain' );
			//echo '<iframe frameborder="0" scrolling="no" src="https://www.radarbox.com/?widget=1&z=4&lat=44.731784681055146&lng=5.675230593595626" width="100%" height="100%"></iframe>';
			echo '<div data-skyscanner-widget="SearchWidget"></div>';
			echo $args['after_widget']; // ????
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
			?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			
			<?php 
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

			return $instance;
		}

		public function sky_scanner_frontend_js_load(){
		 	wp_enqueue_script('skyscanner-frot-end-form', plugins_url('/assets/skyscanner-front-end-form.js',$this->plugPath), array(), false, true);
		}

	} // class MainWidget
}