<?php
/**
 * Plugin Name: Erric Blue
 * Plugin URI: http://www.wpbeginner.com/beginners-guide/what-why-and-how-tos-of-creating-a-site-specific-wordpress-plugin/
 * Description: Site specific code changes for Eric Gunawan in Blue
 * Version: 1.1
 * Author: erricgunawan
 * Author URI: http://erricgunawan.com

/* Start Adding Functions Below this Line */

if (!function_exists('plugin_dir_path')) { die('no direct access allowed'); }

// CONSTANT
define('ERRICBLUE_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ERRICBLUE_PLUGIN_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('ERRICBLUE_INCLUDE', ERRICBLUE_PLUGIN_PATH . '/includes/');


require_once ERRICBLUE_INCLUDE . 'erric-rcew.php';

/**
 * Enqueue styles
 *
function erric_styles() {
	wp_enqueue_style('erricblue', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/css/erricblue.css');
}
add_action('wp_print_styles', 'erric_styles'); */

/**
 * Enqueue scripts
 *
function erric_scripts() {
	wp_enqueue_script('erricblue-js', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/js/erricblue.js', array('jquery'));
}
add_action('wp_print_scripts', 'erric_scripts'); */






/**
 * Change Email Headers 
 * http://www.wprecipes.com/change-wordpress-from-email-header
 */
function erric_fromemail($email) {
    $wpfrom = get_option('admin_email');
    return $wpfrom;
}
 
function erric_fromname($email){
    $wpfrom = get_option('blogname');
    return $wpfrom;
}

add_filter('wp_mail_from', 'erric_fromemail');
add_filter('wp_mail_from_name', 'erric_fromname');



/**
 * Limit tags number in Tag Cloud Widget
 * http://wpshock.com/customize-default-wordpress-tag-cloud-widget-wordpress-filter/
 * @param type $args 
 */
function erric_widget_custom_tag_cloud($args) {
	// Control number of tags to be displayed - 0 no tags
	$args['number'] = 25;

	// Outputs our edited widget
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'erric_widget_custom_tag_cloud' );



/**
 * remove filter for excerpt, then add new filters for excerpt
 *

// remove theme filters; put it on after_setup_theme hook
// http://codex.wordpress.org/Customizing_the_Read_More#Displaying_a_.22more.E2.80.A6.22_link_without_a_.3C--more--.3E_tag
function erric_theme_setup() {
	// override parent theme's 'more' text for excerpts
	remove_filter( 'excerpt_more', 'tiga_auto_excerpt_more' ); 
	remove_filter( 'get_the_excerpt', 'tiga_custom_excerpt_more' );
}
add_action( 'after_setup_theme', 'erric_theme_setup' );

// create new reading link; use get_the_title() for more dynamic link
function erric_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . sprintf(__( 'More on %s <span class="meta-nav">&rarr;</span>', 'erric' ), get_the_title())  . '</a>';
}

// filter to auto excerpt
add_filter( 'excerpt_more', 'erric_auto_excerpt_more' );
function erric_auto_excerpt_more( $more ) {
	return ' &hellip;' . erric_continue_reading_link();
}

// filter to manual/custom excerpt
add_filter( 'get_the_excerpt', 'erric_custom_excerpt_more' );
function erric_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= ' &hellip;' . erric_continue_reading_link();
	}
	return $output;
}
*/


/**
 * Choose whether to show the_content() or the_excerpt() in the loop
 * if post has a custom more text, then use the_content(), otherwise, use the_excerpt()
 * inspired by http://digwp.com/2010/01/wordpress-more-tag-tricks/
 */
function erric_content_preview() {
	global $post, $page, $pages;

	$content = $pages[$page-1];	
	preg_match('/<!--more(.*?)?-->/', $content, $matches);

	if ( (isset($matches[1])) && (!empty($matches[1])) ) {
		return the_content();
	} elseif (str_word_count(get_the_excerpt()) < 35) {
		echo get_the_excerpt() . ' &hellip;' . erric_continue_reading_link();
	} else {
		return the_excerpt();
	}
}


/* Stop Adding Functions Below this Line */