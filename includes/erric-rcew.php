<?php
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
