<?php

if(!class_exists('MenuLinksAcfWidget')) {

  class MenuLinksAcfWidget extends WP_Widget {

    /**
    * Sets up the widgets name etc
    */
    public function __construct() {
		$widget_ops = array(
			'classname' => 'menulinksacf_widget',
			'description' => 'Menu Links for use with ACF Pro',
		);
      	parent::__construct( 'menulinksacf_widget', 'Menu Links ACF Widget', $widget_ops );
    }

    /**
    * Outputs the content of the widget
    *
    * @param array $args
    * @param array $instance
    */
    public function widget( $args, $instance ) {

		// outputs the content of the widget
		if ( !isset($args['widget_id']) ) { 
			$args['widget_id'] = $this->id; 
		}

		
		// PRESET VARIABLES
		$links 				= '';
		$before_widget		= $args['before_widget'];
		$after_widget		= $args['after_widget'];

		
		// widget ID with prefix for use in ACF API functions
		$widget_id 		= 'widget_' . $args['widget_id'];
		$widget_title	= get_field( 'menulinks_widget_title', $widget_id );
		$linksArr		= get_field( 'menulinks_widget_links', $widget_id );


		// if widget title
		if( !empty($widget_title) ) {
			$widget_title = '<div class="widgettitle">'.$widget_title.'</div>';
		} else {$widget_title = '';}

		// build menu
		if(!empty($linksArr)) {
			foreach($linksArr as $item) {
				$link 	= $item['menulinks_link'];

				if( !empty($link) ) {
					$url 	= $link['url'];
					$target	= $link['target'];
					$label 	= $link['title'];

					$links 	.= '<li><a href="'.$url.'" target="'.$target.'">'.$label.'</a></li>';
				}
			}
			$ul = '<ul>'.$links.'</ul>';
		} else {$ul = '';}


		echo $before_widget.$widget_title.$ul.$after_widget;


    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
    	// outputs the options form on admin
    }

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

  }

}

/**
 * Register our CTA Widget
 */
function register_menulinksacf_widget()
{
  register_widget( 'MenuLinksAcfWidget' );
}
add_action( 'widgets_init', 'register_menulinksacf_widget' );