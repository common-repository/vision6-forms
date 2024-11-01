<?php

namespace Vision6;

use Vision6\Forms\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


/**
 * Class Forms
 *
 * @package Vision6
 */
class Forms {

	/**
	 * Load all requirements for forms
	 */
	public static function loaded() {

		// Only run tasks if shortcodes are enabled
		if (!Shortcode::enabled()) {
			return;
		}

		// Add scripts for this plugin
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );
	}


	/**
	 * Add scripts for this plugin
	 */
	public static function enqueueScripts() {
		// Register the form iframe resize code to the bottom of each page.
		wp_enqueue_script( 'v6_forms_iframe_resize', V6_FORMS_PUBLIC_URL . 'js/iframeResizer.min.js', 'jquery-core', false, true );
	}

}

