<?php

namespace Vision6\Forms;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


/**
 * Class Shortcode
 *
 * @package Vision6\Forms
 */
class Shortcode {

	/**
	 * The name of the form iframe template
	 *
	 * @var string
	 */
	public static $template_name = 'webforms_iframe.php';


	/**
	 * Load all requirements for shortcodes
	 */
	public static function loaded() {

		// Only run tasks if shortcodes are enabled
		if (!self::enabled()) {
			return;
		}

		// Allow the form iframe template to be added via the [webform]shortcode
		add_shortcode( 'webform', array( __CLASS__, 'addFormIframe' ) );
	}


	/**
	 * Are shortcodes enabled within this plugin? This primarily checks whether any other Vision6 plugins are enabled,
	 * and therefore should take priority when replacing shortcodes.
	 *
	 * @return bool
	 */
	public static function enabled() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return !is_plugin_active( 'webforms_plugin/webforms_plugin.php' );
	}


	/**
	 * Create the form iframe to show on the page
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function addFormIframe( $atts = [] ) {
		// Update the url, if valid
		$url = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : '';

		if ( ! $url ) {
			return '';
		}

		$query = parse_url( $url, PHP_URL_QUERY );
		$url   .= ( $query ? '&' : '?' ) . 'ar=1&wp=1';


		// Build the parameters
		$referrer  = get_site_url();
		$variables = array(
			'{{webform_url}}'   => $url,
			'{{webform_id}}'    => 'webform-' . uniqid(),
			'{{brand_website}}' => V6_FORMS_BASE_URL,
			'{{referrer}}'      => $referrer
		);


		// Once we know that the iframe will show, enqueue the iframe resize script
		wp_enqueue_script( 'v6_forms_iframe_resize' );


		// Search for the most appropriate template. If a copy of the template file is found within
		// the current theme, then use that file instead. This allows for custom styles
		$template = locate_template( array( self::$template_name ) );

		if ( $template == '' ) {
			$template = V6_FORMS_BASE_TEMPLATE_PATH . self::$template_name;
		}

		return str_replace( array_keys( $variables ), array_values( $variables ), file_get_contents( $template ) );
	}

}

