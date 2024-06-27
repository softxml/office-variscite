<?php
class sgRecentPostsWidget extends WP_Widget {

 
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_recentposts_widg', 
			'description'	=> __('A complete solution to show recent posts by SITEIT', 'siteitsob')
		);
		parent::__construct('sgRecentPostsWidget', __('Recent Posts Widget (SiteIT)', 'siteitsob'), $widget_ops);
	}




	// BUILD MARGIN or PADDING ARRAY
	function build_margpad_array($top, $right, $bottom, $left, $type) {
		$result  				= '';
		$arr[$type.'-top'] 		= $top;
		$arr[$type.'-right'] 	= $right;
		$arr[$type.'-bottom'] 	= $bottom;
		$arr[$type.'-left'] 	= $left;
		$arr 					= array_filter($arr, 'is_numeric');

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
		elseif($type == 'linkTargets') {
			$type = array('_blank'=>__('New Window', 'siteitsob'), '_self'=>__('Same Window', 'siteitsob'));
		}
		elseif($type == 'btnSizes') {
			$type = array('' => __('Standard', 'siteitsob'), 'btn-large'=>__('Large Button'), 'btn-huge'=>__('huge Button'));
		}
		elseif($type == 'btnColors') {
			$type = array( '' => __('No PreDefined Color'), 'btn-blue' => __('Blue Button', 'siteitsob'), 'btn-green'=>__('Green Button', 'siteitsob'), 'btn-turkoise'=>__('Turkoise Button', 'siteitsob'), 'btn-orange'=>__('Orange Button', 'siteitsob'), 'btn-red'=>__('Red Button', 'siteitsob'), 'btn-dark'=>__('Dark Button', 'siteitsob'), 'btn-black'=>__('Black Button', 'siteitsob'), 'btn-link'=>__('Link Button', 'siteitsob') );
		}
		elseif($type == 'btnAlign') {
			$type = array('' => __('Default', 'siteitsob'), 'text-right'=>__('Right', 'siteitsob'), 'text-center'=>__('Center', 'siteitsob'), 'text-left' => __('Left', 'siteitsob'));
		}
		elseif($type == 'ttype') {
			$type = array('h3'=>__('h3'),'h1'=>__('h1'),'h2'=>__('h2'), 'h4'=>__('h4'), 'h5'=>__('h5'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', 'siteitsob'), 'text-center' => __('Center', 'siteitsob'), 'text-right' => __('Right', 'siteitsob'));
		}
		elseif($type == 'valigns') {
			$type = array('top' => __('Top', 'siteitsob'), 'middle' => __('Middle', 'siteitsob'), 'bottom' => __('Bottom', 'siteitsob'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'iconside') {
			$type = array('pull-left'=>__('Left', 'siteitsob'), 'pull-right'=>__('Right', 'siteitsob'));
        }
		elseif($type == 'order') {
			$type = array('DESC'=>__('Descending'), 'ASC'=>__('Ascending'));
		}
		elseif($type == 'orderby') {
			$type = array('date'=>__('Date'), 'rand'=>__('Random'), 'title' => __('By Title', 'siteitsob'));
        }
		elseif($type == 'borderadius') {
			$type = array('nobrad'=>__('No Border Radius', 'siteitsob'), 'brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
        }
        elseif($type == 'cats') {
			$type = $this->buildCategoriesOptions();
		}  
        elseif($type == 'itemsrowsize') {
			$type = array( 'col-md-12'=>__('1 Item', 'siteitsob'), 'col-md-6'=>__('2 Items', 'siteitsob'), 'col-md-4'=>__('3 Items', 'siteitsob'), 'col-md-3'=>__('4 Items', 'siteitsob'), 'col-md-2'=>__('6 Items', 'siteitsob') );
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

            if(is_array($value) &&  in_array($key, $value)) {$selected = 'selected';}
            elseif($key == $value) {$selected = 'selected';} 
            else {$selected = '';}

			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}

	function select2_select($value) {

		$values = array();
		
		if(!is_array($value)) { $values[] = $value; }
		else { $values = $value; }

		$items = '';
		foreach($values as $postid) {
			$items .= '<option value="'.$postid.'" selected="selected">'.get_the_title($postid).'</option>';
		}

		return $items;
	}


	function return_fixed_farray() {
		include( plugin_dir_path( __FILE__ ) . '../functions/fontAwesomeArray.php');
		return array_flip($faIconsArr);
	}


	function faicons_select($value, $label = NULL) {

		$data = '';
		$faIconsArr = $this->return_fixed_farray();
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		foreach($faIconsArr as $key => $icon) {
			if($key == $value) {$selected = 'selected';} else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>&#x'.$key.' '.$icon.'</option>';
		}
		
		return $data;
	}


	function siteit_widget_fields() {
		return array(
            'structure_type',
            'post_amount',
            'post_order',
            'post_orderby',
            'posts_per_row',
            'gtext_color',
            'gbg_color',
            
            'hide_image',
            'hide_title',
            'hide_date',
            'date_structure',
            'hide_author',
            'hide_excerpt',
            'hide_readmore',
            'use_parentcat',
            'cats_list',

            'cpost_1',
            'cpost_2',
            'cpost_3',
            
            'default_image',
            'image_nocls',
            'image_width',
            'image_height',
            'image_border',
            'image_bradius',
            'image_bcolor',
            'image_pt',
            'image_pr',
            'image_pb',
            'image_pl',
            'image_mt',
            'image_mr',
            'image_mb',
            'image_ml',
            
            'title_type',
            'title_size',
            'title_align',
            'title_width',
            'title_fweight',
            'title_color',
            'limit_title_on',
            'limit_title_length',
            'title_pt',
            'title_pr',
            'title_pb',
            'title_pl',
            'title_mt',
            'title_mr',
            'title_mb',
            'title_ml',
            
            'text_fweight',
            'text_size',
            'text_color',
            'text_length',
            'text_pt',
            'text_pr',
            'text_pb',
            'text_pl',
            'text_mt',
            'text_mr',
            'text_mb',
            'text_ml',
            
            'btn_label',
            'btn_textalign',
            'btn_align',
            'btn_color',
            'btn_fsize',
            'btn_fontweight',
            'btn_size',
            'btn_borderadius',
            
            'btn_transbg',
            'btn_custom_bg',
            'btn_custom_color',
            'btn_custom_hoverbg',
            'btn_custom_hovercolor',
            'btn_pt',
            'btn_pr',
            'btn_pb',
            'btn_pl',
            'btn_mt',
            'btn_mr',
            'btn_mb',
            'btn_ml',

            'widget_pt',
            'widget_pr',
            'widget_pb',
            'widget_pl',
            'widget_mt',
            'widget_mr',
            'widget_mb',
            'widget_ml',
            'widget_classes',

			'use_mobile',
		);
	}


	function form_fileds_looper($instance) {

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix = '';

		$formFields = '
		<div class="admin-row">
			<div class="col-md-3">

				<h4 class="row-title">'.__('Design Template', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
                        <div class="col-md-5 mb0 pr0">
                            <label for="'.$this->get_field_name($prefix.'structure_001').'"> <input type="radio" class="radio1 radio-middle1" name="'.$this->get_field_name($prefix.'structure_type').'" id="'.$this->get_field_name($prefix.'structure_001').'" value="style_001" '.($instance[$prefix.'structure_type'] == 'style_001' ? 'checked' : '').' > <img src="'.IMG_URL . '/rp-design-01.jpg" alt="">  </label>
                        </div>
                        <div class="col-md-5 mb0 pr0 pl0">
                            <label for="'.$this->get_field_name($prefix.'structure_002').'"> <input type="radio" class="radio1 radio-middle1" name="'.$this->get_field_name($prefix.'structure_type').'" id="'.$this->get_field_name($prefix.'structure_002').'" value="style_002" '.($instance[$prefix.'structure_type'] == 'style_002' ? 'checked' : '').' > <img src="'.IMG_URL . '/rp-design-02.jpg" alt=""> </label>
                        </div>
					</div>
				</div>

				<h4 class="row-title">'.__('Basic Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'post_amount').'"><span class="label-wrap">'.__('Post Amount', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'post_amount').'" name="'.$this->get_field_name($prefix.'post_amount').'" type="number" value="'.esc_attr($instance[$prefix.'post_amount']).'" />
                            </label>
                        </div>
						<div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'post_order').'"><span class="label-wrap">'.__('Order', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'post_order').'" name="'.$this->get_field_name($prefix.'post_order').'">
                                    '.$this->multi_select($instance[$prefix.'post_order'], '', 'order').'
                                </select>	
                            </label>
                        </div>
						<div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'post_orderby').'"><span class="label-wrap">'.__('Order By', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'post_orderby').'" name="'.$this->get_field_name($prefix.'post_orderby').'">
                                    '.$this->multi_select($instance[$prefix.'post_orderby'], '', 'orderby').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'posts_per_row').'"><span class="label-wrap pl0 pr0">'.__('Items Per Row', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'posts_per_row').'" name="'.$this->get_field_name($prefix.'posts_per_row').'">
                                    '.$this->multi_select($instance[$prefix.'posts_per_row'], '', 'itemsrowsize').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'gtext_color').'"><span class="label-wrap">'.$labelfix.__('Text Color', 'siteitsob').':</span>
                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'gtext_color').'" type="text" name="'.$this->get_field_name($prefix.'gtext_color').'" value="'.(esc_attr($instance[$prefix.'gtext_color']) ? esc_attr($instance[$prefix.'gtext_color']) : '').'" />
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'gbg_color').'"><span class="label-wrap">'.$labelfix.__('Background', 'siteitsob').':</span>
                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'gbg_color').'" type="text" name="'.$this->get_field_name($prefix.'gbg_color').'" value="'.(esc_attr($instance[$prefix.'gbg_color']) ? esc_attr($instance[$prefix.'gtext_color']) : '').'" />
                            </label>
                        </div>
					</div>
                </div>
                
                <h4 class="row-title">'.__('Advance Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-6 col-xs-12">
                            <label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.$labelfix.__('Widget Classes', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('Example: mt2 pl3', 'siteitsob').'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'date_structure').'"><span class="label-wrap">'.$labelfix.__('Date Structure', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'date_structure').'" name="'.$this->get_field_name($prefix.'date_structure').'" type="text" value="'.(!$instance[$prefix.'date_structure'] ? 'l, F j, Y' : '').'" placeholder="'.__('Example: l, F j, Y', 'siteitsob').'" />
                            </label>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.$labelfix.__('Widget Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.$labelfix.__('Widget Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mt').'" name="'.$this->get_field_name($prefix.'widget_mt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mr').'" name="'.$this->get_field_name($prefix.'widget_mr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mb').'" name="'.$this->get_field_name($prefix.'widget_mb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_ml').'" name="'.$this->get_field_name($prefix.'widget_ml').'" type="number" value="'.esc_attr($instance[$prefix.'widget_ml']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                    </div>
                </div>

			</div>
			<div class="col-md-2">

                <h4 class="row-title">'.__('Custom Post Selection', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">  

                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'cpost_1').'"><span class="label-wrap">'.__('Search for products', 'siteitsob').':</span>
                                <select class="widefat select2posts" id="'.$this->get_field_id($prefix.'cpost_1').'" name="'.$this->get_field_name($prefix.'cpost_1').'">
                                    '.$this->select2_select($instance[$prefix.'cpost_1']).'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'cpost_2').'"><span class="label-wrap">'.__('Search for products', 'siteitsob').':</span>
                                <select class="widefat select2posts" id="'.$this->get_field_id($prefix.'cpost_2').'" name="'.$this->get_field_name($prefix.'cpost_2').'">
                                    '.$this->select2_select($instance[$prefix.'cpost_2']).'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'cpost_3').'"><span class="label-wrap">'.__('Search for products', 'siteitsob').':</span>
                                <select class="widefat select2posts" id="'.$this->get_field_id($prefix.'cpost_3').'" name="'.$this->get_field_name($prefix.'cpost_3').'">
                                    '.$this->select2_select($instance[$prefix.'cpost_3']).'
                                </select>	
                            </label>
                        </div>

			        </div>
			    </div>

                <h4 class="row-title">'.__('Show Hide Sections', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">  
                        <div class="col-md-12">
                            <ul class="lsnone m0 p0">
                                <li><label for="'.$this->get_field_id($prefix.'hide_image').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_image').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_image').'" '.($instance[$prefix.'hide_image'] == 'on' ? 'checked' : '').' /> '.__('Hide Image Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'hide_title').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_title').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_title').'" '.($instance[$prefix.'hide_title'] == 'on' ? 'checked' : '').' /> '.__('Hide Title Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'hide_date').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_date').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_date').'" '.($instance[$prefix.'hide_date'] == 'on' ? 'checked' : '').' /> '.__('Hide Date Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'hide_author').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_author').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_author').'" '.($instance[$prefix.'hide_author'] == 'on' ? 'checked' : '').' /> '.__('Hide Author Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'hide_excerpt').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_excerpt').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_excerpt').'" '.($instance[$prefix.'hide_excerpt'] == 'on' ? 'checked' : '').' /> '.__('Hide Excerpt Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'hide_readmore').'"><input class="widefat" id="'.$this->get_field_id($prefix.'hide_readmore').'" type="checkbox" name="'.$this->get_field_name($prefix.'hide_readmore').'" '.($instance[$prefix.'hide_readmore'] == 'on' ? 'checked' : '').' /> '.__('Hide Read More Section', 'siteitsob').'</label></li>
                                <li><label for="'.$this->get_field_id($prefix.'use_parentcat').'"><input class="widefat" id="'.$this->get_field_id($prefix.'use_parentcat').'" type="checkbox" name="'.$this->get_field_name($prefix.'use_parentcat').'" '.($instance[$prefix.'use_parentcat'] == 'on' ? 'checked' : '').' /> '.__('Get Post From Parent Category', 'siteitsob').'</label></li>                                
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'cats_list').'">'.$labelfix.__('Include Categories', 'siteitsob').':
                                <select multiple class="widefat multiselect" id="'.$this->get_field_id($prefix.'cats_list').'" name="'.$this->get_field_name($prefix.'cats_list').'">
                                    '.$this->multi_select($instance[$prefix.'cats_list'], '', 'cats').'
                                </select>
                            </label>
                        </div>
			        </div>
			    </div>
			</div>
            <div class="col-md-4">

                <h4 class="row-title">'.__('Image Settings', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-8">
                            <label for="'.$this->get_field_name($prefix.'default_image').'"><span class="label-wrap">'. __('Default Image:', 'siteitsob').'</span></label><br>
                            <input name="'.$this->get_field_name($prefix.'default_image').'" id="'.$this->get_field_id($prefix.'default_image').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'default_image']).'" style="width: 62%;" /> 
                            <input data-input="#'.$this->get_field_id($prefix.'default_image').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'image_nocls').'"><span class="label-wrap">'.__('Responsive?', 'siteitsob').'</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'image_nocls').'" name="'.$this->get_field_name($prefix.'image_nocls').'">
                                    '.$this->multi_select($instance[$prefix.'image_nocls'], '', 'yesno').'
                                </select>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'image_width').'"><span class="label-wrap">'.$labelfix.__('Image Width (optional)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'image_width').'" type="number" name="'.$this->get_field_name($prefix.'image_width').'" value="'.esc_attr($instance[$prefix.'image_width']).'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'image_height').'"><span class="label-wrap">'.$labelfix.__('Image Height (optional)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'image_height').'" type="number" name="'.$this->get_field_name($prefix.'image_height').'" value="'.esc_attr($instance[$prefix.'image_height']).'" />
                            </label>
                        </div>


                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'image_border').'"><span class="label-wrap">'.$labelfix.__('Image Border', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'image_border').'" name="'.$this->get_field_name($prefix.'image_border').'">
                                    '.$this->multi_select($instance[$prefix.'image_border'], __('Default', 'siteitsob'), 'yesno').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'image_bradius').'"><span class="label-wrap">'.$labelfix.__('Border Radius', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'image_bradius').'" name="'.$this->get_field_name($prefix.'image_bradius').'">
                                    '.$this->multi_select($instance[$prefix.'image_bradius'], __('No Radius', 'siteitsob'), 'borderadius').'
                                </select>	
                            </label>						
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'image_bcolor').'"><span class="label-wrap">'.$labelfix.__('Border Color', 'siteitsob').':</span>
                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'image_bcolor').'" type="text" name="'.$this->get_field_name($prefix.'image_bcolor').'" value="'.(esc_attr($instance[$prefix.'image_bcolor']) ? esc_attr($instance[$prefix.'image_bcolor']) : '#212121').'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'image_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pr').'" name="'.$this->get_field_name($prefix.'image_pr').'" type="number" value="'.esc_attr($instance[$prefix.'image_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pb').'" name="'.$this->get_field_name($prefix.'image_pb').'" type="number" value="'.esc_attr($instance[$prefix.'image_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pl').'" name="'.$this->get_field_name($prefix.'image_pl').'" type="number" value="'.esc_attr($instance[$prefix.'image_pl']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'image_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mt').'" name="'.$this->get_field_name($prefix.'image_mt').'" type="number" value="'.esc_attr($instance[$prefix.'image_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mr').'" name="'.$this->get_field_name($prefix.'image_mr').'" type="number" value="'.esc_attr($instance[$prefix.'image_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mb').'" name="'.$this->get_field_name($prefix.'image_mb').'" type="number" value="'.esc_attr($instance[$prefix.'image_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_ml').'" name="'.$this->get_field_name($prefix.'image_ml').'" type="number" value="'.esc_attr($instance[$prefix.'image_ml']).'" placeholder="0" /></div>
                            </div>	
                        </div>	

                    </div>
                </div>


				<h4 class="row-title">'.__('Title Settings', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_type').'"><span class="label-wrap">'.__('Title Type', 'siteitsob').':</span>
                            <select class="widefat" id="'.$this->get_field_id($prefix.'title_type').'" name="'.$this->get_field_name($prefix.'title_type').'">
                                '.$this->multi_select($instance[$prefix.'title_type'], '', 'ttype').'
                            </select>	
                            </label>	
                        </div>
						<div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'title_size').'" name="'.$this->get_field_name($prefix.'title_size').'" type="number" value="'.esc_attr($instance[$prefix.'title_size']).'" />
                            </label>	
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_align').'"><span class="label-wrap">'.__('Title Align', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'title_align').'" name="'.$this->get_field_name($prefix.'title_align').'">
                                    '.$this->multi_select($instance[$prefix.'title_align'], __('Default', 'siteitsob'), 'talign').'
                                </select>	
                            </label>	
                        </div>
						<div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_width').'"><span class="label-wrap">'.__('Width (PX or %)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'title_width').'" name="'.$this->get_field_name($prefix.'title_width').'" type="text" value="'.esc_attr($instance[$prefix.'title_width']).'" />
                            </label>	
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'title_fweight').'" name="'.$this->get_field_name($prefix.'title_fweight').'">
                                    '.$this->multi_select($instance[$prefix.'title_fweight'], __('Default', 'siteitsob'), 'fontweight').'
                                </select>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'title_color').'"><span class="label-wrap">'.__('Title Color', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'title_color').'" name="'.$this->get_field_name($prefix.'title_color').'" type="text" value="'.esc_attr($instance[$prefix.'title_color']).'" />
                            </label>
                        </div>

                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'limit_title_on').'"><span class="label-wrap">'.__('Limit Title', 'siteitsob').':</span></label>
                            <label for="'.$this->get_field_id($prefix.'limit_title_on').'"><input class="widefat" id="'.$this->get_field_id($prefix.'limit_title_on').'" type="checkbox" name="'.$this->get_field_name($prefix.'limit_title_on').'" '.($instance[$prefix.'limit_title_on'] == 'on' ? 'checked' : '').' /> '.__('Limit Title Length', 'siteitsob').'</label>
                        </div>
                        <div class="col-md-8">
                            <label for="'.$this->get_field_id($prefix.'limit_title_length').'"><span class="label-wrap">'.__('Max Title Length (Characters)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'limit_title_length').'" name="'.$this->get_field_name($prefix.'limit_title_length').'" type="number" value="'.esc_attr($instance[$prefix.'limit_title_length']).'" />
                            </label>
                        </div>

                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'title_pt').'" type="number" value="'.esc_attr($instance[$prefix.'title_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'title_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mt').'" name="'.$this->get_field_name($prefix.'title_mt').'" type="number" value="'.esc_attr($instance[$prefix.'title_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mr').'" name="'.$this->get_field_name($prefix.'title_mr').'" type="number" value="'.esc_attr($instance[$prefix.'title_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mb').'" name="'.$this->get_field_name($prefix.'title_mb').'" type="number" value="'.esc_attr($instance[$prefix.'title_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_ml').'" name="'.$this->get_field_name($prefix.'title_ml').'" type="number" value="'.esc_attr($instance[$prefix.'title_ml']).'" placeholder="0" /></div>
                            </div>
                        </div>
					</div>
				</div>
            </div>
            
            <div class="col-md-3">
            

                <h4 class="row-title">'.__('Excerpt Settings', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'text_fweight').'"><span class="label-wrap">'.$labelfix.__('Font Weight', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'text_fweight').'" name="'.$this->get_field_name($prefix.'text_fweight').'">
                                    '.$this->multi_select($instance[$prefix.'text_fweight'], __('Default', 'siteitsob'), 'fontweight').'
                                </select>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'text_size').'"><span class="label-wrap">'.__('Font Size (px)', 'siteitsob').':</span>
                            <input class="widefat" id="'.$this->get_field_id($prefix.'text_size').'" name="'.$this->get_field_name($prefix.'text_size').'" type="number" value="'.esc_attr($instance[$prefix.'text_size']).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'text_color').'"><span class="label-wrap">'.__('Color', 'siteitsob').':</span>
                            <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'text_color').'" name="'.$this->get_field_name($prefix.'text_color').'" type="text" value="'.esc_attr($instance[$prefix.'text_color']).'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'text_length').'"><span class="label-wrap">'.__('Text Length', 'siteitsob').':</span>
                            <input class="widefat" id="'.$this->get_field_id($prefix.'text_length').'" name="'.$this->get_field_name($prefix.'text_length').'" type="number" value="'.esc_attr($instance[$prefix.'text_length']).'" />
                            </label>
                        </div>
						<div class="col-md-6 mb0">
                           <label for="'.$this->get_field_id($prefix.'text_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mt').'" name="'.$this->get_field_name($prefix.'text_mt').'" type="number" value="'.esc_attr($instance[$prefix.'text_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mr').'" name="'.$this->get_field_name($prefix.'text_mr').'" type="number" value="'.esc_attr($instance[$prefix.'text_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mb').'" name="'.$this->get_field_name($prefix.'text_mb').'" type="number" value="'.esc_attr($instance[$prefix.'text_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_ml').'" name="'.$this->get_field_name($prefix.'text_ml').'" type="number" value="'.esc_attr($instance[$prefix.'text_ml']).'" placeholder="0" /></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'text_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pt').'" name="'.$this->get_field_name($prefix.'text_pt').'" type="number" value="'.esc_attr($instance[$prefix.'text_pt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pr').'" name="'.$this->get_field_name($prefix.'text_pr').'" type="number" value="'.esc_attr($instance[$prefix.'text_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pb').'" name="'.$this->get_field_name($prefix.'text_pb').'" type="number" value="'.esc_attr($instance[$prefix.'text_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pl').'" name="'.$this->get_field_name($prefix.'text_pl').'" type="number" value="'.esc_attr($instance[$prefix.'text_pl']).'" placeholder="0" /></div>
                            </div>
                        </div>                        
                    </div>
                </div>


				<h4 class="row-title">'.__('Read More Button', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_label').'"><span class="label-wrap">'.$labelfix.__('Button Text', 'siteitsob').':</span>
                            <input class="widefat" id="'.$this->get_field_id($prefix.'btn_label').'" name="'.$this->get_field_name($prefix.'btn_label').'" type="text" value="'.esc_attr($instance[$prefix.'btn_label']).'" />
                            </label>	
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_textalign').'"><span class="label-wrap">'.$labelfix.__('Text Align', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_textalign').'" name="'.$this->get_field_name($prefix.'btn_textalign').'">
                                    '.$this->multi_select($instance[$prefix.'btn_textalign'], '', 'btnAlign').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_align').'"><span class="label-wrap">'.$labelfix.__('Button Align', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_align').'" name="'.$this->get_field_name($prefix.'btn_align').'">
                                    '.$this->multi_select($instance[$prefix.'btn_align'], '', 'btnAlign').'
                                </select>	
                            </label>
                        </div>
						<div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_color').'"><span class="label-wrap">'.$labelfix.__('Preset Button Color', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_color').'" name="'.$this->get_field_name($prefix.'btn_color').'">
                                    '.$this->multi_select($instance[$prefix.'btn_color'], '', 'btnColors').'
                                </select>	
                            </label>	
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_fsize').'"><span class="label-wrap">'.$labelfix.__('Font Size', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'btn_fsize').'" name="'.$this->get_field_name($prefix.'btn_fsize').'" type="number" value="'.esc_attr($instance[$prefix.'btn_fsize']).'" placeholder="'.__('Empty means default', 'siteitsob').'" />
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_fontweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_fontweight').'" name="'.$this->get_field_name($prefix.'btn_fontweight').'">
                                    '.$this->multi_select($instance[$prefix.'btn_fontweight'], __('Default', 'siteitsob'), 'fontweight').'
                                </select>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_size').'"><span class="label-wrap">'.__('Button Size', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_size').'" name="'.$this->get_field_name($prefix.'btn_size').'">
                                    '.$this->multi_select($instance[$prefix.'btn_size'], __('Default', 'siteitsob'), 'btnSizes').'
                                </select>	
                            </label>						
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_borderadius').'"><span class="label-wrap">'.__('Border Radius', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_borderadius').'" name="'.$this->get_field_name($prefix.'btn_borderadius').'">
                                    '.$this->multi_select($instance[$prefix.'btn_borderadius'], '', 'borderadius').'
                                </select>	
                            </label>						
                        </div>
                        <div class="col-md-12">
                            <div class=" fullbED" style="padding: 15px 20px 0; ">
                                <div class="admin-row">
                                    <div class="col-md-12 tooltip" data-tip="'.__('Reset Pre Defined color to use this', 'siteitsob').'">
                                        <h5 class="bold m0">'.__('Custom Colors', 'siteitsob').'</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="'.$this->get_field_id($prefix.'btn_transbg').'"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_transbg').'" type="checkbox" name="'.$this->get_field_name($prefix.'btn_transbg').'" '.($instance[$prefix.'btn_transbg'] == 'on' ? 'checked' : '').' /> '.__('Use Transparent Background', 'siteitsob').'</label>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><strong>'.__('Base State').'</strong></h6>
                                        <div class="form-group">
                                            <label for="'.$this->get_field_id($prefix.'btn_custom_bg').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span>
                                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'btn_custom_bg').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_bg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_bg']) ? esc_attr($instance[$prefix.'btn_custom_bg']) : '#ECF0F1').'" />
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="'.$this->get_field_id($prefix.'btn_custom_color').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span>
                                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'btn_custom_color').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_color').'" value="'.esc_attr($instance[$prefix.'btn_custom_color']).'" />
                                            </label>											
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><strong>'.__('Hover State').'</strong></h6>
                                        <div class="form-group">
                                            <label for="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'"><span class="label-wrap">'.$labelfix.__('Background Color', 'siteitsob').':</span>
                                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_hoverbg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_hoverbg']) ? esc_attr($instance[$prefix.'btn_custom_hoverbg']) : '#ECF0F1').'" />
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'"><span class="label-wrap">'.$labelfix.__('Text Color', 'siteitsob').':</span>
                                                <input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_hovercolor').'" value="'.esc_attr($instance[$prefix.'btn_custom_hovercolor']).'" />
                                            </label>											
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pr').'" name="'.$this->get_field_name($prefix.'btn_pr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pb').'" name="'.$this->get_field_name($prefix.'btn_pb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pl').'" name="'.$this->get_field_name($prefix.'btn_pl').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pl']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mt').'" name="'.$this->get_field_name($prefix.'btn_mt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mr').'" name="'.$this->get_field_name($prefix.'btn_mr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mb').'" name="'.$this->get_field_name($prefix.'btn_mb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_ml').'" name="'.$this->get_field_name($prefix.'btn_ml').'" type="number" value="'.esc_attr($instance[$prefix.'btn_ml']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                        
                    </div>
                </div>

            </div>

            <div class="col-md-12">

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
		<script src="'.plugin_dir_url( __FILE__ ) . '../lib/backend/inside-widget.js"></script>
		';	
		
	}
 
 
	// SAVE FORM VALUES
	function update($new_instance, $old_instance) {

		$instance	= $old_instance;
		$fieldNames = $this->siteit_widget_fields();

		foreach($fieldNames as $field) {
			$instance[$field]			=	$new_instance[$field];
		}

		return $instance;
	}

	
	// DISPLAY THE WIDGET
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
        $prefix = '';


        // FIX WIDGET WRAP CLASSES & MARGIN / PADDING
        $widget_css = '';
        $widget_css .= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
        $widget_css .= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


        // additional widget styles
        if( $instance[$prefix.'gtext_color'] ) {$widget_css = 'color: '.$instance[$prefix.'gtext_color'].';';}
        if( $instance[$prefix.'gbg_color'] ) {$widget_css = 'background: '.$instance[$prefix.'gbg_color'].';';}


		// BASIC SETTINGS
		$result					= '';
		$posts					= '';
        $counter                = 1;
        $cpostsIds              = array();
		$before_widget 			= '<div class="singleWidget  sitBuilderWidget sitRecentPostWidget '.$instance[$prefix.'structure_type'].' '.$instance[$prefix.'widget_classes'].' '.$instance[$prefix.'btn_align'].'" style="'.$widget_css.'">';
		$after_widget 			= '</div>';


        // date structure
        if( empty($instance[$prefix.'date_structure']) ) {$instance[$prefix.'date_structure'] = 'l, F j, Y';}


        // FIX IMAGE CLASSES CSS
        if($instance[$prefix.'hide_image'] != 'on') {
            $img_css = '';
            $img_cls = '';

            if($instance[$prefix.'image_nocls'] == 'yes') {$img_cls .= 'img-responsive ';}
            if($instance[$prefix.'image_border'] == 'yes') {$img_css .= 'border: 1px solid '.$instance[$prefix.'image_bcolor'];}
            if($instance[$prefix.'image_bradius']) {$img_cls .= $instance[$prefix.'image_bradius'];}

            $img_css .= $this->build_margpad_array($instance[$prefix.'image_pt'], $instance[$prefix.'image_pr'], $instance[$prefix.'image_pb'], $instance[$prefix.'image_pl'], 'padding');
            $img_css .= $this->build_margpad_array($instance[$prefix.'image_mt'], $instance[$prefix.'image_mr'], $instance[$prefix.'image_mb'], $instance[$prefix.'image_ml'], 'margin');

        } 
        else {$img_css = ''; $img_cls = '';}


        // FIX TITLE CLASSES CSS
        if($instance[$prefix.'hide_title'] != 'on') {
            $title_css = '';
            $title_cls = '';

            if($instance[$prefix.'title_size']) {$title_css .= 'font-size: '.$instance[$prefix.'title_size'].'px;';} else {$title_css .= 'font-size: 30px;';}
            if($instance[$prefix.'title_width']) {$title_css .= 'width: '.$instance[$prefix.'title_width'].';';}
            if($instance[$prefix.'title_color']) {$title_css .= 'color: '.$instance[$prefix.'title_color'].';';}
            if($instance[$prefix.'title_fweight']) {$title_cls .= $instance[$prefix.'title_fweight'];}

            $title_css .= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
            $title_css .= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

        } 
        else {$title_css = ''; $title_cls = '';}



        // FIX TEXT 
        if($instance[$prefix.'hide_excerpt'] != 'on') {
            $text_css = '';
            $text_cls = '';

            if($instance[$prefix.'text_size']) {$text_css .= 'font-size: '.$instance[$prefix.'text_size'].'px;';}
            if($instance[$prefix.'text_color']) {$text_css .= 'color: '.$instance[$prefix.'text_color'].';';}
            if($instance[$prefix.'text_fweight']) {$text_cls .= $instance[$prefix.'text_fweight'];}

            $text_css .= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
            $text_css .= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');

        } 
        else {$text_css = ''; $text_cls = '';}


        // FIX BUTTON
        if($instance[$prefix.'hide_readmore'] != 'on') {
            $btn_css = '';
            $btn_cls = '';

            if($instance[$prefix.'btn_textalign']) {$btn_css .= 'text-align: '.$instance[$prefix.'btn_textalign'].';';}
            if($instance[$prefix.'btn_fsize']) {$btn_css .= 'font-size: '.$instance[$prefix.'btn_fsize'].'px;';}
            if($instance[$prefix.'btn_fontweight']) {$btn_cls .= $instance[$prefix.'btn_fontweight'].' ';}
            if($instance[$prefix.'btn_size']) {$btn_cls .= $instance[$prefix.'btn_size'].' ';}
            if($instance[$prefix.'btn_borderadius']) {$btn_cls .= $instance[$prefix.'btn_borderadius'].' ';}

            if(!$instance[$prefix.'btn_color']) {
                $btn_css .= 'background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_bg']).'; color: '.$instance[$prefix.'btn_custom_color'].';';
                $hoverStyles = '<style>#btn-'.$this->id_base.'-'.$this->number.':hover {background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_hoverbg']).' !important; color: '.$instance[$prefix.'btn_custom_hovercolor'].' !important;}</style>';
            }
            else { $btn_cls .= $instance[$prefix.'btn_color'].' '; $hoverStyles = '';}


            $btn_css .= $this->build_margpad_array($instance[$prefix.'btn_pt'], $instance[$prefix.'btn_pr'], $instance[$prefix.'btn_pb'], $instance[$prefix.'btn_pl'], 'padding');
            $btn_css .= $this->build_margpad_array($instance[$prefix.'btn_mt'], $instance[$prefix.'btn_mr'], $instance[$prefix.'btn_mb'], $instance[$prefix.'btn_ml'], 'margin');

        } 
        else {$btn_css = ''; $btn_cls = ''; $hoverStyles = '';}




        // SOME DEFAULTS
        if(!$instance[$prefix.'post_amount']) {$instance[$prefix.'post_amount'] = 4;}
        if(!$instance[$prefix.'post_order']) {$instance[$prefix.'post_amount'] = 'ASC';}
        if(!$instance[$prefix.'post_orderby']) {$instance[$prefix.'post_amount'] = 'date';}


        // USE POST CATEGORIES AS PARENT CATEGORY?
        if($instance[$prefix.'use_parentcat']) {$catIdsArr = array(); $catsIdsobj = wp_get_post_terms( get_the_ID(), 'category' );  foreach($catsIdsobj as $cat) { $catIdsArr[] = $cat->term_id; } }
        else { $catIdsArr[] = $instance[$prefix.'cats_list']; }


        // IF CUSTOM POSTS WERE SELECTED
        if($instance[$prefix.'cpost_1']) {$cpostsIds[] = $instance[$prefix.'cpost_1'];}
        if($instance[$prefix.'cpost_2']) {$cpostsIds[] = $instance[$prefix.'cpost_2'];}
        if($instance[$prefix.'cpost_3']) {$cpostsIds[] = $instance[$prefix.'cpost_3'];}

        if( empty($cpostsIds) ){
            // QUERY POSTS
            $args = array(
                'posts_per_page'=> $instance[$prefix.'post_amount'],
                'order'         => $instance[$prefix.'post_order'],
                'orderby'       => $instance[$prefix.'post_orderby'],
                'tax_query'     => array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $catIdsArr,
                ),
            );
        }
        else {
            // QUERY POSTS
            $args = array(
                'post__in'      => $cpostsIds,
                'posts_per_page'=> $instance[$prefix.'post_amount'],
            );
        }

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                
                // GET POST DATA 
		        $dateAuthor = '';
                $pid        = get_the_ID();
                $title      = get_the_title();
                $excerpt    = get_the_content();
                $date       = get_the_date($instance[$prefix.'date_structure']);
                $author     = get_the_author(); 
                $link       = get_permalink();


                if($instance[$prefix.'structure_type'] == 'style_001') {
                    
                    // FIX IMAGE
                    if($instance[$prefix.'hide_image'] != 'on') {
                        $image = '<a href="'.$link.'"><img src="'.siteitsob_smart_thumbnail_url($pid, $instance[$prefix.'image_width'], $instance[$prefix.'image_height'], $instance[$prefix.'default_image']).'" alt="'.$title.'" class="'.$img_cls.'" style="'.$img_css.'"></a>';
                    } else {$image = '';}
        
        
                    // FIX TITLE
                    if($instance[$prefix.'hide_title'] != 'on') {

                        // LIMIT TITLE LENGTH?
                        if($instance[$prefix.'limit_title_on'] == 'on') {
                            $title = sitsob_text_to_excerpt($title, $instance[$prefix.'limit_title_length']);
                        }

                        $title = '<'.$instance[$prefix.'title_type'].' class="item-title '.$title_cls.'" style="'.$title_css.'"><a href="'.$link.'">'.$title.'</a></'.$instance[$prefix.'title_type'].'>';
                    } else {$title = '';}
        
                    // FIX AUTHOR
                    if($instance[$prefix.'hide_author'] != 'on') {
                        $dateAuthor .= '<li class="author">'.$author.'</li>';
                    }
        
                    // FIX DATE
                    if($instance[$prefix.'hide_date'] != 'on') {
                        $dateAuthor .= '<li class="date">'.$date.'</li>';
                    }
        
                    // FIX EXCERPT
                    if($instance[$prefix.'hide_excerpt'] != 'on') {
                        if(!$instance[$prefix.'text_length']) {$instance[$prefix.'text_length'] = 165;}
                        $excerpt = '<div class="excerpt-wrap">'.content_to_excerpt($excerpt, $instance[$prefix.'text_length']).'</div>';
                    } else {$excerpt = '';}
        
                    // FIX READ MORE
                    if($instance[$prefix.'hide_readmore'] != 'on') {
                        $readmore = '<div class="rm-wrap '.$instance[$prefix.'btn_align'].'"><a href="'.$link.'" class="btn '.$btn_cls.'" style="'.$btn_css.'">'.($instance[$prefix.'btn_label'] ? $instance[$prefix.'btn_label'] : __('Read More', THEME_NAME)).'</a></div>';
                    } else {$readmore = '';}



                    // TURN DATE & AUTHOR INTO LIST
                    if($dateAuthor != '') { $dateAuthor = '<ul class="meta-list list-inline p0 m0">'.$dateAuthor.'</ul>'; }



                    $result .= '
                    <div class="item item-'.$counter.' '.$instance[$prefix.'posts_per_row'].'">
                        <div class="inner">
                            <div class="img-wrap">'.$image.'</div>
                            <div class="text-wrap">'.$title.$dateAuthor.$excerpt.$readmore.'</div>
                        </div>
                    </div>  
                    ';

                }
                elseif($instance[$prefix.'structure_type'] == 'style_002') {

                    // FIX IMAGE
                    if($instance[$prefix.'hide_image'] != 'on') {
                        $image = '<a href="'.$link.'"><img src="'.siteitsob_smart_thumbnail_url($pid, $instance[$prefix.'image_width'], $instance[$prefix.'image_height'], $instance[$prefix.'default_image']).'" alt="'.$title.'" class="'.$img_cls.'" style="'.$img_css.'"></a>';
                    } else {$image = '';}
        
        
                    // FIX TITLE
                    if($instance[$prefix.'hide_title'] != 'on') {
                        $title = '<'.$instance[$prefix.'title_type'].' class="item-title '.$title_cls.'" style="'.$title_css.'"><a href="'.$link.'">'.$title.'</a></'.$instance[$prefix.'title_type'].'>';
                    } else {$title = '';}
        
        
                    // FIX DATE
                    if($instance[$prefix.'hide_date'] != 'on') {
                        $dateAuthor .= '<li class="date">'.$date.'</li>';
                    }
                    
                    // FIX AUTHOR
                    if($instance[$prefix.'hide_author'] != 'on') {
                        $dateAuthor .= '<li class="author">'.$author.'</li>';
                    }
        
                    // FIX EXCERPT
                    if($instance[$prefix.'hide_excerpt'] != 'on') {
                        if(!$instance[$prefix.'text_length']) {$instance[$prefix.'text_length'] = 165;}
                        $excerpt = '<div class="excerpt-wrap">'.content_to_excerpt($excerpt, $instance[$prefix.'text_length']).'</div>';
                    } else {$excerpt = '';}
        
                    // FIX READ MORE
                    if($instance[$prefix.'hide_readmore'] != 'on') {
                        $readmore = '<div class="rm-wrap '.$instance[$prefix.'btn_align'].'"><a href="'.$link.'" class="btn '.$btn_cls.'" style="'.$btn_css.'">'.($instance[$prefix.'btn_label'] ? $instance[$prefix.'btn_label'] : __('Read More', THEME_NAME)).'</a></div>';
                    } else {$readmore = '';}



                    // TURN DATE & AUTHOR INTO LIST
                    if($dateAuthor != '') { $dateAuthor = '<ul class="meta-list list-inline p0 m0">'.$dateAuthor.'</ul>'; }



                    $result .= '
                    <div class="item item-'.$counter.' '.$instance[$prefix.'posts_per_row'].'">
                        <div class="inner">
                            <div class="row">
                                '.($instance[$prefix.'hide_image'] != 'on' ? '<div class="col-md-4 col-xs-12">'.$image.'</div>' : '').'
                                <div class="'.($instance[$prefix.'hide_image'] != 'on' ? 'col-md-8' : 'col-md-12 col-xs-12').'">'.$title.$dateAuthor.$excerpt.$readmore.'</div>
                            </div>
                        </div>
                    </div>  
                    ';
                }

                
                $counter++;

            }
            wp_reset_postdata();
        } else {
            // no posts found
        }


        $result = '<div class="row">'.$result.'</div>';



		echo $before_widget.$result.$hoverStyles.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgRecentPostsWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgRecentPostsWidget');
}, 1 );
?>