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
 * modified from http://wordpress.org/plugins/facebook-thumb-fixer/
 */
add_action( 'wp_head', 'erric_fbfiximage' );

function erric_fbfiximage() {

	// check Featured Image first
	if ( has_post_thumbnail() ) {
		global $post;
		$featuredimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" );
		$fbimage = $featuredimg[0];

	// then check if any first image exist; this will be likely used
	} else if ( erric_get_first_image() ) {
		$fbimage = erric_get_first_image();

	// if all fails, fallback to Gravatar
	} else {
		$fbimage = 'http://gravatar.com/avatar/d5726dc48c1feb7e8cbdd5599961c664?s=200';

	}

	// Sanitize for output
	$fbimage = esc_url( $fbimage );

?>
<!--/ Erric Open Graph Tweak /-->
<meta property="og:image" content="<?php echo $fbimage; ?>" />
<?php

}

/**
* Return an HTML img tag for the first image in a post content. Used to draw
* the content for posts of the “image” format.
* http://css-tricks.com/snippets/wordpress/get-the-first-image-from-a-post/#comment-1582091 --> not working
* http://www.wprecipes.com/how-to-get-the-first-image-from-the-post-and-display-it
*
* @return string An HTML img tag for the first image in a post content.
*/
function erric_get_first_image() {

	// Expose information about the current post.
	global $post;

	// We'll trap to see if this stays empty later in the function.
	$src = '';

	// Grab all img src's in the post content
	// $output = preg_match_all( '//i', $post->post_content, $matches ); // not working
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);

	// Grab the first img src returned by our regex.
	if( ! isset ( $matches[1][0] ) ) { return false; }
	$src = $matches[1][0];

	// Make sure there's still something worth outputting after sanitization.
	if( empty( $src ) ) { return false; }

	return $src;

}


/* Stop Adding Functions Below this Line */