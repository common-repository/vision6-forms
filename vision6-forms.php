<?php
/*
Plugin Name: Vision6 Forms
Plugin URI: https://www.vision6.com/wordpress-forms
Description: Add Vision6 forms to populate your lists from your website.
Version: 1.0.7
Author: Vision6
Author URI: http://www.vision6.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vision6
*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


// Create constants
define( 'V6_FORMS_BRAND_NAME', 'Vision6' );
define( 'V6_FORMS_BASE_URL', 'https://www.vision6.com.au' );
define( 'V6_FORMS_BASE_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . '/includes/templates/' );
define( 'V6_FORMS_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public/' );
define( 'V6_FORMS_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );


// Load dependencies
require_once( plugin_dir_path( __FILE__ ) . 'includes/forms.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/widget.php' );


// Register load functions
add_action( 'plugins_loaded', array( '\Vision6\Forms', 'loaded' ) );
add_action( 'plugins_loaded', array( '\Vision6\Forms\Shortcode', 'loaded' ) );
add_action( 'plugins_loaded', array( '\Vision6\Forms\Widget', 'loaded' ) );

