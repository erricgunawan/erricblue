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



/**
 * Add default image for posting in Facebook
 * http://wordpress.org/plugins/facebook-thumb-fixer/
 */
function erric_fbfiximage() {

	if ( has_post_thumbnail() ) {
		$featuredimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "Full");
		$ftf_head = '
<!--/ Open Graph Tweak /-->
<meta property="og:image" content="' . $featuredimg[0] . '" />
';
	} else {
		$ftf_head = '
<!--/ Open Graph Tweak /-->
<meta property="og:image" content="http://gravatar.com/avatar/d5726dc48c1feb7e8cbdd5599961c664?s=200" />
';
	}
	echo $ftf_head;
	print "\n";

}

add_action( 'wp_head', 'erric_fbfiximage' );



/* Stop Adding Functions Below this Line */