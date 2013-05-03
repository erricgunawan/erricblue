<?php
/**
 * Dump from 1.1
 */

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
 */

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
