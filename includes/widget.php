<?php

namespace Vision6\Forms;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


/**
 * Allow the form iframe to be added to a widget
 *
 * Class Widget
 *
 * @package Vision6\Forms
 */
class Widget extends \WP_Widget {
	/**
	 * Load all requirements for widgets
	 */
	public static function loaded() {

		// Only run tasks if shortcodes are enabled
		if (!Shortcode::enabled()) {
			return;
		}

		add_action( 'widgets_init', array( __CLASS__, 'register' ) );
	}


	/**
	 * Register this widget class with WordPress
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}


	/**
	 * Run the registration of the widget
	 *
	 * Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'v6_forms_widget',
			__( V6_FORMS_BRAND_NAME . ' Form', 'v6_forms_widget_domain' ),
			array( 'description' => __( 'A form from ' . V6_FORMS_BRAND_NAME, 'v6_forms_widget_domain' ), )
		);
	}


	/**
	 * Echoes the widget content.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// Use the shortcode code to create the iframe
		$url      = array_key_exists( 'v6_form_url', $instance ) ? $instance['v6_form_url'] : '';
		$template = Shortcode::addFormIframe( [ 'url' => $url ] );

		if ( ! $template ) {
			return;
		}


		// Create the widget string        
		$output = $args['before_widget'];

		$title_text = array_key_exists( 'title', $instance ) ? $instance['title'] : '';
		$title      = apply_filters( 'widget_title', $title_text );

		if ( ! empty( $title ) ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}

		$output .= $template;
		$output .= $args['after_widget'];

		echo $output;
	}


	/**
	 * Updates a particular instance of a widget.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) )
			? strip_tags( $new_instance['title'] )
			: '';

		// if the user enters the shortcode or the url this should get the correct one.
		$v6_form_url = '';
		if ( ! empty( $new_instance['v6_form_url'] ) ) {
			$v6_form_url_matches = wp_extract_urls( $new_instance['v6_form_url'] );
			$v6_form_url         = ( count( $v6_form_url_matches ) > 0 )
				? esc_url( $v6_form_url_matches[0] )
				: '';
		}

		$instance['v6_form_url'] = $v6_form_url;

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance
	 *
	 * @return string
	 */
	public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? $instance['title'] : '';
		$v6_form_url = isset( $instance['v6_form_url'] ) ? $instance['v6_form_url'] : '';

		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'v6_form_url' ); ?>"><?php _e( 'Form URL or Shortcode:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'v6_form_url' ); ?>"
                   name="<?php echo $this->get_field_name( 'v6_form_url' ); ?>" type="text"
                   value="<?php echo esc_attr( $v6_form_url ); ?>"/>
        </p>
		<?php

		return 'vision6-forms';
	}
}