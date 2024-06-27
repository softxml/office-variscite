<?php
class webuilders_custom_menu extends WP_Widget {

	/*constructor*/
	function __construct() {
		parent::__construct(false, $name = __('Custom Menu (SiteIT)', THEME_NAME));
	}



	/* display widget in widgets panel */
	function form($instance) {  

		$nav_menu1 	= isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$menus1 	= get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if( !empty($instance['title']) ) {echo esc_attr($instance['title']); } ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_url'); ?>"><?php _e('Title URL:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_url'); ?>" name="<?php echo $this->get_field_name('title_url'); ?>" type="text" value="<?php if( !empty($instance['title_url']) ) {echo esc_attr($instance['title_url']); } ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('classes'); ?>"><?php _e('Widget Classes:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('classes'); ?>" name="<?php echo $this->get_field_name('classes'); ?>" type="text" value="<?php if( !empty($instance['classes']) ) {echo esc_attr($instance['classes']); } ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<?php
					foreach ( $menus1 as $menu1 ) {
						echo '<option value="' . $menu1->term_id . '"'
							. selected( $nav_menu1, $menu1->term_id, false )
							. '>'. $menu1->name . '</option>';
					}
				?>
			</select>
		</p>

		<?php
	}



	// UPDATE WIDGET OPTIONS
	function update($new_instance, $old_instance) {
		$instance 				= $old_instance;
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['title_url']	= $new_instance['title_url'];
		$instance['nav_menu']	= (int) $new_instance['nav_menu'];
		$instance['classes']	= strip_tags($new_instance['classes']);
		return $instance;
	}

	

	// DISPLAY WIDGET
	function widget($args, $instance) {
		extract( $args );

		// widget options
		$title 			= apply_filters('widget_title', $instance['title']);
		$title_url		= $instance['title_url'];
		$nav_menu		= !empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		$classes		= $instance['classes'];
		

		
		// BEFORE & AFTER
		$before_widget	= '<div class="singleWidget cmenu-wrap '.$classes.'">';
		$after_widget	= '</div>';

		echo $before_widget;    

		if ($title) {
			if($title_url) {$ab = '<a href="'.$title_url.'">'; $aa = '</a>';} else {$ab = ''; $aa = '';} 
			echo $before_title . $ab.$title.$aa . $after_title;
		}


		if ($nav_menu) {
			echo wp_nav_menu(
				array( 
					'fallback_cb'	=> '',
					'menu_class' 	=> 'menu ',
					'menu'			=> $nav_menu
				) 
			);
		}

		echo $after_widget;
	}

}

// register widget
//add_action('widgets_init', create_function('', 'return register_widget("webuilders_custom_menu");'));
add_action( 'widgets_init', function () {
    return register_widget('webuilders_custom_menu');
}, 1 );
?>