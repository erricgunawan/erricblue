<?php
/**
 * Plugin Name: Erric Blue
 * Plugin URI: http://www.wpbeginner.com/beginners-guide/what-why-and-how-tos-of-creating-a-site-specific-wordpress-plugin/
 * Description: Site specific code changes for Eric Gunawan in Blue
 * Version: 1.2
 * Author: erricgunawan
 * Author URI: http://erricgunawan.com
 */

/* Start Adding Functions Below this Line */

if ( !function_exists( 'plugin_dir_path' ) ) { die( 'no direct access allowed' ); }

// CONSTANT
define( 'ERRICBLUE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ERRICBLUE_PLUGIN_URL', plugins_url( plugin_basename( dirname( __FILE__ ) ) ) );
define( 'ERRICBLUE_INCLUDE', ERRICBLUE_PLUGIN_PATH . '/includes/' );


require_once ERRICBLUE_INCLUDE . 'erric-rcew.php';

/**
 * Enqueue styles
 *//*
function erric_styles() {
	wp_enqueue_style('erricblue', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/css/erricblue.css');
}
add_action('wp_print_styles', 'erric_styles'); */

/**
 * Enqueue scripts
 *//*
function erric_scripts() {
	wp_enqueue_script('erricblue-js', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/js/erricblue.js', array('jquery'));
}
add_action('wp_print_scripts', 'erric_scripts'); */





/**
 * Change Email Headers
 * http://www.wprecipes.com/change-wordpress-from-email-header
 */
function erric_fromemail( $email ) {
	$wpfrom = get_option( 'admin_email' );
	return $wpfrom;
}

function erric_fromname( $email ) {
	$wpfrom = get_option( 'blogname' );
	return $wpfrom;
}

add_filter( 'wp_mail_from', 'erric_fromemail' );
add_filter( 'wp_mail_from_name', 'erric_fromname' );





/* Stop Adding Functions Below this Line */
