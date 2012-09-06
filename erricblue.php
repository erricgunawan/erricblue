<?php
/**
 * Plugin Name: Erric Blue
 * Plugin URI: http://www.wpbeginner.com/beginners-guide/what-why-and-how-tos-of-creating-a-site-specific-wordpress-plugin/
 * Description: Site specific code changes for Eric Gunawan in Blue
 * Version: 0.1
 * Author: erricgunawan
 * Author URI: http://erricgunawan.com

/* Start Adding Functions Below this Line */



/**
 * Enqueue styles
 */
function erric_styles() {
	wp_enqueue_style('erricblue', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/css/erricblue.css');
}
add_action('wp_print_styles', 'erric_styles');

/**
 * Enqueue scripts
 *
function erric_scripts() {
	wp_enqueue_script('erricblue-js', plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) ) . '/js/erricblue.js', array('jquery'));
}
add_action('wp_print_scripts', 'erric_scripts'); */



/**
 * Extending The Recent Comments Widget
 * http://www.ghostpool.com/freebies/creating-a-recent-comments-widget/
 */
add_action( 'widgets_init', 'erric_widgets_init' );

function erric_widgets_init() {
	register_widget('Recent_Comments');
}

class Recent_Comments extends WP_Widget {
	
	function Recent_Comments() {
        $widget_ops = array('classname' => 'recent_comments', 'description' => __('Displays recent comments with avatars and comment excerpts.', 'erric'));
        $this->WP_Widget('recent-comments', __('Recent Comments', 'erric'), $widget_ops);
    }
	
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];

		// Begin Widget
		echo $before_widget;

		if ($title)
			echo $before_title . $title . $after_title;
		?>
			
		<div id="recent-comments">
			
		    <ul>
				
				<?php
				global $wpdb;
				$request = "SELECT * FROM $wpdb->comments";
				$request .= " JOIN $wpdb->posts ON ID = comment_post_ID";
				$request .= " WHERE comment_approved = '1' AND post_status = 'publish' AND post_password =''";
				$request .= " AND user_id != 1 AND comment_type != 'pingback'";
				$request .= " ORDER BY comment_date DESC LIMIT $number";
				$comments = $wpdb->get_results($request);
				if ($comments) {
					foreach ($comments as $comment) {
						ob_start();
				?>
							
					<li>

						<?php echo get_avatar($comment, $size = '40'); ?>

						<div class="comment-excerpt">
							<a href="<?php echo get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID; ?>"><?php echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, 30)); ?>...</a>
							<br/><span class="comment-author"><?php echo get_comment_author_link($comment->comment_ID); ?> <?php _e('on'); ?> <?php echo get_the_title($comment->comment_post_ID) ?></span>
						</div>

					</li>
								
				<?php ob_end_flush();
					}
				} else { // If no comments  ?>
						
					<li><?php _e('No comments.', 'erric'); ?></li>
							
			<?php } ?>
					
		    </ul>
				
		</div>
			
		<?php
		echo $after_widget;
		// End Widget
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
 
	function form( $instance ) {
		$defaults = array('title' => __('Recent Comments', 'erric'), 'number' => '5'); $instance = wp_parse_args((array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'erric'); ?></label>
			<br/><input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Comments:', 'erric'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" size="3" value="<?php echo $instance['number']; ?>" />
		</p>

		<?php
	}
	
}



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
 * remove filter for excerpt; put it on after_setup_theme hook
 * http://codex.wordpress.org/Customizing_the_Read_More#Displaying_a_.22more.E2.80.A6.22_link_without_a_.3C--more--.3E_tag
 * 
 * later on, add a new filter for excerpt_more 
 */
function erric_theme_setup() {
	// override parent theme's 'more' text for excerpts
	remove_filter( 'excerpt_more', 'tiga_auto_excerpt_more' ); 
//	remove_filter( 'get_the_excerpt', 'tiga_custom_excerpt_more' );
}
add_action( 'after_setup_theme', 'erric_theme_setup' );

add_filter( 'excerpt_more', 'erric_auto_excerpt_more' );
function erric_auto_excerpt_more( $more ) {
	return ' &hellip;' . ' <a href="'. esc_url( get_permalink() ) . '">' . sprintf(__( 'More on %s <span class="meta-nav">&rarr;</span>', 'erric' ), get_the_title())  . '</a>';
}



/**
 * Choose whether to show the_content() or the_excerpt() in the loop
 * if post has a custom more text, then use the_content(), otherwise, use the_excerpt()
 * inspired by http://digwp.com/2010/01/wordpress-more-tag-tricks/
 */
function erric_content_preview() {
	global $post, $page, $pages;
//	$custom_more = get_post_meta($post->ID, "custom_more_text", true);
	$content = $pages[$page-1];	
	preg_match('/<!--more(.*?)?-->/', $content, $matches);

	if (isset($matches[1])) {
		return the_content();
	}
	return the_excerpt();
}


/* Stop Adding Functions Below this Line */