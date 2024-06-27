<?php
class sgLocalBlogSearch extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_title_widg', 
			'description'	=> __('A custom blog search Widget By SITEIT', 'siteitsob')
		);

		parent::__construct('sgLocalBlogSearch', __('Blog Search Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
            'show_search',
            'search_title',
            'search_placeholder',
            'pickcats_optionspage',
            'show_categories',
            'catgories_title',
            'cats_list',
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
        $prefix = '';


		$formFields = '
		<div class="admin-row">
			<div class="col-md-12">
				<h4 class="row-title">'.__('Search Section', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'show_search').'"><input class="widefat" id="'.$this->get_field_id($prefix.'show_search').'" type="checkbox" name="'.$this->get_field_name($prefix.'show_search').'" '.($instance[$prefix.'show_search'] == 'on' ? 'checked' : '').' /> '.__('Show Search Section', 'siteitsob').'</label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'search_title').'">'.__('Section Title', 'siteitsob').':
                                <input class="widefat" id="'.$this->get_field_id($prefix.'search_title').'" name="'.$this->get_field_name($prefix.'search_title').'" type="text" value="'.esc_attr($instance[$prefix.'search_title']).'" />
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'search_placeholder').'">'.__('Input Placeholder', 'siteitsob').':
                                <input class="widefat" id="'.$this->get_field_id($prefix.'search_placeholder').'" name="'.$this->get_field_name($prefix.'search_placeholder').'" type="text" value="'.esc_attr($instance[$prefix.'search_placeholder']).'" />
                            </label>
                        </div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<label for="'.$this->get_field_id($prefix.'pickcats_optionspage').'"><input class="widefat" id="'.$this->get_field_id($prefix.'pickcats_optionspage').'" type="checkbox" name="'.$this->get_field_name($prefix.'pickcats_optionspage').'" '.($instance[$prefix.'pickcats_optionspage'] == 'on' ? 'checked' : '').' /> '.__('Ignore & use categories picked in <a href="https://wordpress-689526-3817782.cloudwaysapps.com/wp-admin/admin.php?page=acf-options">options page</a>', 'siteitsob').'</label>

			</div>
			<div class="col-md-12">
				<h4 class="row-title">'.__('Categories Section', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'show_categories').'"><input class="widefat" id="'.$this->get_field_id($prefix.'show_categories').'" type="checkbox" name="'.$this->get_field_name($prefix.'show_categories').'" '.($instance[$prefix.'show_categories'] == 'on' ? 'checked' : '').' /> '.__('Show Categories Section', 'siteitsob').'</label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'catgories_title').'">'.__('Section Title', 'siteitsob').':
                                <input class="widefat" id="'.$this->get_field_id($prefix.'catgories_title').'" name="'.$this->get_field_name($prefix.'catgories_title').'" type="text" value="'.esc_attr($instance[$prefix.'catgories_title']).'" />
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'cats_list').'">'.$labelfix.__('Include Categories', 'siteitsob').':
                                <select multiple class="widefat multiselect" id="'.$this->get_field_id($prefix.'cats_list').'" name="'.$this->get_field_name($prefix.'cats_list').'[]">
                                    '.$this->multi_select($instance[$prefix.'cats_list'], '', 'cats').'
                                </select>
                            </label>
                        </div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
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
		<div>'.$this->form_fileds_looper($instance).'</div>

		<script>
		jQuery(function($){
			$(".main_text").trumbowyg({
				resetCss: true,
				btns: [
					["viewHTML"],
					["formatting"],
					"btnGrp-semantic",
					["link"],
					["insertImage"],
					"btnGrp-justify",
					"btnGrp-lists",
					["fontsize"],
					["foreColor", "backColor"],
					["horizontalRule"],
					["removeformat"],
					["fullscreen"]
				]
			});
		});
		</script>
		';
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


        // SEARCH SECTION
        if( $instance[$prefix.'show_search'] == 'on' ) {
            $searchSection = '
            <div class="search-section">
                <h3>'.$instance[$prefix.'search_title'].'</h3>
                <form method="get" id="'. $prefix .'search_form" action="'.home_url().'">
                    <input type="text" value="" name="s" id="'. $prefix .'s" class="form-control" placeholder="'.$instance[$prefix.'search_placeholder'].'" />
                    <input type="hidden" value="<?php echo $term->term_id; ?>" name="cat" />
                    <input type="hidden" value="<?php echo $term->name; ?>" name="catname" />
                    <input type="submit" id="'. $prefix .'search_submit" name="Search" class="fa-search" value=""/>
                </form>
            </div>
            ';
        }


        // CATEGORIES SECTION
        if( $instance[$prefix.'show_categories'] == 'on' ) {

			$catItems = '';
			
			if( $instance[$prefix.'pickcats_optionspage'] == 'on' ) {
				$cat_list = get_field('blog_sidebar_categories', 'option');

				foreach($cat_list as $cat){
					$cterm_data	= get_term( $cat['picked_blog_category'], 'category' );
					$cterm_link = get_term_link( $cterm_data );
					$catItems 	.=  '<li><a href="'.esc_url($cterm_link).'">'.$cterm_data->name.' <span class="pull-right">('.$cterm_data->count.')</span></a></li>';
				}
			}
			else {
				foreach($instance[$prefix.'cats_list'] as $cat){
					$cterm_data	= get_term( $cat, 'category' );
					$cterm_link = get_term_link( $cterm_data );
					$catItems 	.=  '<li><a href="'.esc_url($cterm_link).'">'.$cterm_data->name.' <span class="pull-right">('.$cterm_data->count.')</span></a></li>';
				}
			}
							

            $catsSection = '
            <div class="categories-section">
				<h3>'.$instance[$prefix.'catgories_title'].'</h3>
				<ul class="cat-list">'.$catItems.'</ul>
            </div>
			';

        }
        

		$result = '
		<div class="singleWidget sitBuilderWidget sitBuilderBlogSearchWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">
            '.$searchSection.'
            '.$catsSection.'
		</div>
		';
		
	
		echo $before_widget.$result.$after_widget;

	}
  
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgLocalBlogSearch");') );
add_action( 'widgets_init', function () {
    return register_widget('sgLocalBlogSearch');
}, 1 );
?>