<?php
class sgBlogRssSubscribe extends WP_Widget {


	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_rsssubscribeblog_widg', 
			'description'	=> __('Super simple RSS Subscribe widget SITEIT', 'siteitsob')
		);

		parent::__construct('sgBlogRssSubscribe', __('Rss Subscribe (SiteIT)', 'siteitsob'), $widget_ops);
	}


	// auto build select array
	function sghttibgw_create_numstring_array($startNum, $endNum, $jumps, $sideString = NULL) {

		if($startNum && $endNum) {

			$data       = array();
			$counter    = $startNum;

	
			while($endNum > $counter ) {
				$data[$counter] = $counter.' '.$sideString;
				$counter        = $counter + $jumps;
			}

			return $data;
		}
	}



	// BUILD MARGIN or PADDING ARRAY
	function build_margpad_array($top, $right, $bottom, $left, $type) {
		$result  				= '';
		$arr[$type.'-top'] 		= $top;
		$arr[$type.'-right'] 	= $right;
		$arr[$type.'-bottom'] 	= $bottom;
		$arr[$type.'-left'] 	= $left;
		$arr 					= array_filter($arr);

		if(!empty($arr)) {
			foreach($arr as $key => $value) {
				$result .= $key.':'.$value.'px;';
			}
		}

		return $result;
    }
    

    function buildCategoriesOptions() {
        $catsOpts   = array();
        $catsArr    = get_terms( 'category', array('hide_empty' => false) );

        foreach($catsArr as $cat) {
            $catsOpts[$cat->term_id] = $cat->name;
        }

        $catsOpts = array_filter($catsOpts);

        return $catsOpts;
    }

 
 
	// MULTI SELECT
	function sgDataArr($type) {

		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
        elseif($type == 'cats') {
			$type = $this->buildCategoriesOptions();
		}
		return $type;
	}



	function multi_select($value, $label, $type) {
		$data = '';
		
		$typeArr = $this->sgDataArr($type);
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		foreach($typeArr as $key => $opt) {
            if(is_array($value) && in_array($key, $value)) {$selected = 'selected';}
			elseif($key == $value) {$selected = 'selected';} 
            else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}





	function siteit_widget_fields() {
		return array( 
            'widget_title',
            'text_label',
            'rss_url',
			'widget_classes',
			'widget_mt',
			'widget_mr',
			'widget_mb',
			'widget_ml',
			'widget_pt',
			'widget_pr',
			'widget_pb',
			'widget_pl',
			'use_mobile', 
		);
	}


	function form_fileds_looper($instance) {


		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';


		$formFields = '
		<div class="admin-row">
			<div class="col-md-12 col-xs-12">
				<h4 class="row-title">'.__('Widget Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'widget_title').'">'.__('Widget Title', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.esc_attr($instance[$prefix.'widget_title']).'" />
							</label>
						</div>
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'text_label').'">'.__('Text Label', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'text_label').'" name="'.$this->get_field_name($prefix.'text_label').'" type="text" value="'.esc_attr($instance[$prefix.'text_label']).'" />
							</label>
						</div>
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'rss_url').'">'.__('RSS Url (link)', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'rss_url').'" name="'.$this->get_field_name($prefix.'rss_url').'" type="text" value="'.esc_attr($instance[$prefix.'rss_url']).'" />
							</label>
						</div>
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-12 col-xs-12">
							<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.__('Widget Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.__('Widget Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mt').'" name="'.$this->get_field_name($prefix.'widget_mt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mr').'" name="'.$this->get_field_name($prefix.'widget_mr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mb').'" name="'.$this->get_field_name($prefix.'widget_mb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_ml').'" name="'.$this->get_field_name($prefix.'widget_ml').'" type="number" value="'.esc_attr($instance[$prefix.'widget_ml']).'" placeholder="0" /></div>
							</div>
						</div>
								
					</div>
				</div>
			</div>
		</div>
		';

		return $formFields;
	}


 
	// BUILDING THE WIDGET FORM
	function form($instance) {

		$defaults = array();

		foreach($this->siteit_widget_fields() as $f) {
			$defaults[$f] = '';
		}

	
		$instanceArray 		= $defaults; // set default values
		$instance 			= wp_parse_args( (array) $instance, $instanceArray );
		
		// widget title
		echo '
		<div>'.$this->form_fileds_looper($instance).'</div>';
		
	}
 
 
	// SAVE FORM VALUES
	function update($new_instance, $old_instance) {

		$instance	= $old_instance;
		$fieldNames = $this->siteit_widget_fields();

		foreach($fieldNames as $field) {
			$instance[$field] =	$new_instance[$field];
		}

		return $instance;
	}



	
	// DISPLAY THE WIDGET
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		// BASIC SETTINGS
		$result 		= '';
		$before_widget 	= '';
		$after_widget  	= '';
		$searchSection  = '';
		$catsSection    = '';
        $prefix = '';


		// FIX MARGINS & PADDINGS
		$widgetCss 		= '';
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');



		$before_widget 	= '<div class="singleWidget sitBuilderWidget sitRssSubscribeWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">';
		
		$after_widget  	= '</div>';

		$result = '
			'.( !empty($instance[$prefix.'widget_title']) ? '<h3 class="widgettitle">'.$instance[$prefix.'widget_title'].'</h3>' : '').'
			'.( !empty($instance[$prefix.'text_label']) ? '<div class="text-label">	<a href="'.$instance[$prefix.'rss_url'].'" id="blogRssNewLink" target="_blank"><span>'.$instance[$prefix.'text_label'].'</span></a> </div>' : '').'
		';
		
		$script = '
		<script>
		jQuery(function($){
			$("#blogRssNewLink").click(function() {
				dataLayer.push({"event": "rssSignupBlog"});
				console.log("EVENT RAN");
			})
		});
		</script>
		';
	
		echo $before_widget.$result.$script.$after_widget;

	}
  
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgBlogRssSubscribe");') );
add_action( 'widgets_init', function () {
    return register_widget('sgBlogRssSubscribe');
}, 1 );
?>