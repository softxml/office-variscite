<?php
class sgPostIsotopeLooperWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_postlooper_widg', 
			'description'	=> __('Smart easy to use post loop builder widgets', 'siteitsob')
		);

		parent::__construct('sgPostIsotopeLooperWidget', __('Post Isotope Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
		elseif($type == 'order') {
			$type = array('DESC'=>__('Descending'), 'ASC'=>__('Ascending'));
		}
		elseif($type == 'orderby') {
			$type = array('date'=>__('Date'), 'rand'=>__('Random'), 'title' => __('By Title', 'siteitsob'));
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
		elseif($type == 'topmarg') {
			$type = array( 'mt0' => __('0 (no margin)', 'siteitsob'), 'mt1' => __('1% from top', 'siteitsob'),'mt2' => __('2% from top', 'siteitsob'),'mt3' => __('3% from top', 'siteitsob'),'mt4' => __('4% from top', 'siteitsob'),'mt5' => __('5% from top', 'siteitsob'),'mt10' => __('10% from top', 'siteitsob'),'mt15' => __('15% from top', 'siteitsob'),'mt20' => __('20% from top', 'siteitsob'),'mt30' => __('30% from top', 'siteitsob'),'mt20x' => __('20px from top', 'siteitsob'),'mt30x' => __('30px from top', 'siteitsob'),'mt40x' => __('40px from top', 'siteitsob'),'mt50x' => __('50px from top', 'siteitsob'),'mt60x' => __('60px from top', 'siteitsob'),'mt70x' => __('70px from top', 'siteitsob'),'mt80x' => __('80px from top', 'siteitsob'),'mt90x' => __('90px from top', 'siteitsob'),'mt100x' => __('100px from top', 'siteitsob'),'mt120x' => __('120px from top', 'siteitsob'),'mt150x' => __('150px from top', 'siteitsob'),'mt170x' => __('170px from top', 'siteitsob'),'mt200x' => __('200px from top', 'siteitsob'), );
		}
		elseif($type == 'botmarg') {
			$type = array( 'mb0' => __('0 (no margin)', 'siteitsob'), 'mb1' => __('1% from bottom', 'siteitsob'),'mb2' => __('2% from bottom', 'siteitsob'),'mb3' => __('3% from bottom', 'siteitsob'),'mb4' => __('4% from bottom', 'siteitsob'),'mb5' => __('5% from bottom', 'siteitsob'),'mb10' => __('10% from bottom', 'siteitsob'),'mb15' => __('15% from bottom', 'siteitsob'),'mb20' => __('20% from bottom', 'siteitsob'),'mb30' => __('30% from bottom', 'siteitsob'),'mb20x' => __('20px from bottom', 'siteitsob'),'mb30x' => __('30px from bottom', 'siteitsob'),'mb40x' => __('40px from bottom', 'siteitsob'),'mb50x' => __('50px from bottom', 'siteitsob'),'mb60x' => __('60px from bottom', 'siteitsob'),'mb70x' => __('70px from bottom', 'siteitsob'),'mb80x' => __('80px from bottom', 'siteitsob'),'mb90x' => __('90px from bottom', 'siteitsob'),'mb100x' => __('100px from bottom', 'siteitsob'),'mb120x' => __('120px from bottom', 'siteitsob'),'mb150x' => __('150px from bottom', 'siteitsob'),'mb170x' => __('170px from bottom', 'siteitsob'),'mb200x' => __('200px from bottom', 'siteitsob'), );
		}
		elseif($type == 'fontweight') {
			$type = array( 'lighter' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'cats') {
			$type = $this->buildCategoriesOptions();
		}
		elseif($type == 'borderadius') {
			$type = array('brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
		}
		elseif($type == 'btnColors') {
			$type = array( '' => __('Pick Color', 'siteitsob'), 'btn-blue' => __('Blue Button', 'siteitsob'), 'btn-green'=>__('Green Button', 'siteitsob'), 'btn-turkoise'=>__('Turkoise Button', 'siteitsob'), 'btn-orange'=>__('Orange Button', 'siteitsob'), 'btn-red'=>__('Red Button', 'siteitsob'), 'btn-dark'=>__('Dark Button', 'siteitsob'), 'btn-black'=>__('Black Button', 'siteitsob'), );
		}
		elseif($type == 'btstrpWidths') {
			$type = array( 'col-md-6' => __('2 In A Row', 'siteitsob'), 'col-md-4' => __('3 In A Row', 'siteitsob'), 'col-md-3' => __('4 In A Row', 'siteitsob'), 'col-md-2' => __('6 In A Row', 'siteitsob'), 'col-md-12 col-xs-12'=>__('1 In A Row', 'siteitsob') );
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
			'post_amount',
			'post_orderby',
			'post_order',
			'items_inrow',
			'show_image',
			'show_title',
			'show_date',
			'show_excerpt',
			'show_rmbtn',
			'widget_classes',
			'cats_list',
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
			'title_pt',
			'title_pr',
			'title_pb',
			'title_pl',
			'title_mt',
			'title_mr',
			'title_mb',
			'title_ml',
			'date_size',
			'date_align',
			'date_width',
			'date_fweight',
			'date_color',
			'date_pt',
			'date_pr',
			'date_pb',
			'date_pl',
			'date_mt',
			'date_mr',
			'date_mb',
			'date_ml',
			'excerpt_length',
			'excerpt_size',
			'excerpt_align',
			'excerpt_width',
			'excerpt_fweight',
			'excerpt_color',
			'excerpt_pt',
			'excerpt_pr',
			'excerpt_pb',
			'excerpt_pl',
			'excerpt_mt',
			'excerpt_mr',
			'excerpt_mb',
			'excerpt_ml',
			'btn_label',
			'btn_fsize',
			'btn_align',
			'btn_size',
			'btn_bradius',
			'btn_color',
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
		);
	}


	function form_fileds_looper($instance) {

		// IMG URL
		$imgUrl = plugin_dir_url(dirname(__FILE__)).'lib/images';
        $prefix = '';

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}


		$formFields = '
		<div class="admin-row">

			<div class="col-md-4">
				<h4 class="row-title">'.__('Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'post_amount').'"><span class="label-wrap">'.__('How Many Posts?', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'post_amount').'" name="'.$this->get_field_name($prefix.'post_amount').'" type="number" value="'.$instance[$prefix.'post_amount'].'" />
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
							<label for="'.$this->get_field_id($prefix.'post_order').'"><span class="label-wrap">'.__('Order', 'siteitsob').':</span>
							<select class="widefat" id="'.$this->get_field_id($prefix.'post_order').'" name="'.$this->get_field_name($prefix.'post_order').'">
								'.$this->multi_select($instance[$prefix.'post_order'], '', 'order').'
							</select>	
							</label>
						</div>
						<div class="col-md-12">
							<h6>'.__('Post Struture', 'siteitsob').'</h6>
							<div class="admin-row">
								<div class="col-md-4">
									<ul class="show_list" style="list-style: none; padding: 0;">
										<li><label for="'.$this->get_field_id($prefix.'show_image').'"> <input type="checkbox" name="'.$this->get_field_name($prefix.'show_image').'" id="'.$this->get_field_id($prefix.'show_image').'" '.($instance[$prefix.'show_image'] == 'on' ? 'checked' : '').'> '.__('Show Image', 'siteitsob').'</li>
										<li><label for="'.$this->get_field_id($prefix.'show_title').'"> <input type="checkbox" name="'.$this->get_field_name($prefix.'show_title').'" id="'.$this->get_field_id($prefix.'show_title').'" '.($instance[$prefix.'show_title'] == 'on' ? 'checked' : '').'> '.__('Show Title', 'siteitsob').'</li>
										<li><label for="'.$this->get_field_id($prefix.'show_date').'"> <input type="checkbox" name="'.$this->get_field_name($prefix.'show_date').'" id="'.$this->get_field_id($prefix.'show_date').'" '.($instance[$prefix.'show_date'] == 'on' ? 'checked' : '').'> '.__('Show Date', 'siteitsob').'</li>
									</ul>
								</div>
								<div class="col-md-8">
									<ul class="show_list" style="list-style: none; padding: 0;">
										<li><label for="'.$this->get_field_id($prefix.'show_excerpt').'"> <input type="checkbox" name="'.$this->get_field_name($prefix.'show_excerpt').'" id="'.$this->get_field_id($prefix.'show_excerpt').'" '.($instance[$prefix.'show_excerpt'] == 'on' ? 'checked' : '').'> '.__('Show Excerpt', 'siteitsob').'</li>
										<li><label for="'.$this->get_field_id($prefix.'show_rmbtn').'"> <input type="checkbox" name="'.$this->get_field_name($prefix.'show_rmbtn').'" id="'.$this->get_field_id($prefix.'show_rmbtn').'" '.($instance[$prefix.'show_rmbtn'] == 'on' ? 'checked' : '').'> '.__('Show Read More Button', 'siteitsob').'</li>								
									</ul>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'items_inrow').'">'.__('Items In Row', 'siteitsob').':
								<select class="widefat" id="'.$this->get_field_id($prefix.'items_inrow').'" name="'.$this->get_field_name($prefix.'items_inrow').'">
									'.$this->multi_select($instance[$prefix.'items_inrow'], '', 'btstrpWidths').'
								</select>	
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>

                    </div>
                </div>
			</div>

			<div class="col-md-4">
				<h4 class="row-title">'.__('Categories', 'siteitsob').'  <span class="sitb-icon icon-categories icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
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
			
				<div class="box-'.$this->get_field_id($prefix.'show_image').' '.($instance[$prefix.'show_image'] == 'on' ? '' : 'dnone').'">
					<h4 class="row-title">'.__('Image Settings', 'siteitsob').'</h4>
					<div class="row-wrap">
						<div class="admin-row">			
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
									<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'image_bcolor').'" type="color" name="'.$this->get_field_name($prefix.'image_bcolor').'" value="'.(esc_attr($instance[$prefix.'image_bcolor']) ? esc_attr($instance[$prefix.'image_bcolor']) : '#212121').'" />
								</label>
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'image_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pr').'" name="'.$this->get_field_name($prefix.'image_pr').'" type="number" value="'.esc_attr($instance[$prefix.'image_pr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pb').'" name="'.$this->get_field_name($prefix.'image_pb').'" type="number" value="'.esc_attr($instance[$prefix.'image_pb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pl').'" name="'.$this->get_field_name($prefix.'image_pl').'" type="number" value="'.esc_attr($instance[$prefix.'image_pl']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'image_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mt').'" name="'.$this->get_field_name($prefix.'image_mt').'" type="number" value="'.esc_attr($instance[$prefix.'image_mt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mr').'" name="'.$this->get_field_name($prefix.'image_mr').'" type="number" value="'.esc_attr($instance[$prefix.'image_mr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mb').'" name="'.$this->get_field_name($prefix.'image_mb').'" type="number" value="'.esc_attr($instance[$prefix.'image_mb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_ml').'" name="'.$this->get_field_name($prefix.'image_ml').'" type="number" value="'.esc_attr($instance[$prefix.'image_ml']).'" placeholder="0" /></div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="box-'.$this->get_field_id($prefix.'show_title').' '.($instance[$prefix.'show_title'] == 'on' ? '' : 'dnone').'">
					<h4 class="row-title">'.__('Title Settings', 'siteitsob').'</h4>
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
									<input class="widefat" id="'.$this->get_field_id($prefix.'title_color').'" name="'.$this->get_field_name($prefix.'title_color').'" type="color" value="'.esc_attr($instance[$prefix.'title_color']).'" />
								</label>
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'title_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mt').'" name="'.$this->get_field_name($prefix.'title_mt').'" type="number" value="'.esc_attr($instance[$prefix.'title_mt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mr').'" name="'.$this->get_field_name($prefix.'title_mr').'" type="number" value="'.esc_attr($instance[$prefix.'title_mr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mb').'" name="'.$this->get_field_name($prefix.'title_mb').'" type="number" value="'.esc_attr($instance[$prefix.'title_mb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_ml').'" name="'.$this->get_field_name($prefix.'title_ml').'" type="number" value="'.esc_attr($instance[$prefix.'title_ml']).'" placeholder="0" /></div>
								</div>	
							</div>						
						</div>
					</div>
				</div>
				<div class="box-'.$this->get_field_id($prefix.'show_date').' '.($instance[$prefix.'show_date'] == 'on' ? '' : 'dnone').'">
					<h4 class="row-title">'.__('Date Settings', 'siteitsob').'</h4>
					<div class="row-wrap">
						<div class="admin-row">			
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'date_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'date_size').'" name="'.$this->get_field_name($prefix.'date_size').'" type="number" value="'.esc_attr($instance[$prefix.'date_size']).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'date_align').'"><span class="label-wrap">'.__('Date Align', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'date_align').'" name="'.$this->get_field_name($prefix.'date_align').'">
										'.$this->multi_select($instance[$prefix.'date_align'], __('Default', 'siteitsob'), 'talign').'
									</select>	
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'date_width').'"><span class="label-wrap">'.__('Width (PX or %)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'date_width').'" name="'.$this->get_field_name($prefix.'date_width').'" type="text" value="'.esc_attr($instance[$prefix.'date_width']).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'date_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'date_fweight').'" name="'.$this->get_field_name($prefix.'date_fweight').'">
										'.$this->multi_select($instance[$prefix.'date_fweight'], __('Default', 'siteitsob'), 'fontweight').'
									</select>
								</label>
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'date_color').'"><span class="label-wrap">'.__('Date Color', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'date_color').'" name="'.$this->get_field_name($prefix.'date_color').'" type="color" value="'.esc_attr($instance[$prefix.'date_color']).'" />
								</label>
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'date_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_pr').'" name="'.$this->get_field_name($prefix.'date_pr').'" type="number" value="'.esc_attr($instance[$prefix.'date_pr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_pb').'" name="'.$this->get_field_name($prefix.'date_pb').'" type="number" value="'.esc_attr($instance[$prefix.'date_pb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_pl').'" name="'.$this->get_field_name($prefix.'date_pl').'" type="number" value="'.esc_attr($instance[$prefix.'date_pl']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'date_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_mt').'" name="'.$this->get_field_name($prefix.'date_mt').'" type="number" value="'.esc_attr($instance[$prefix.'date_mt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_mr').'" name="'.$this->get_field_name($prefix.'date_mr').'" type="number" value="'.esc_attr($instance[$prefix.'date_mr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_mb').'" name="'.$this->get_field_name($prefix.'date_mb').'" type="number" value="'.esc_attr($instance[$prefix.'date_mb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'date_ml').'" name="'.$this->get_field_name($prefix.'date_ml').'" type="number" value="'.esc_attr($instance[$prefix.'date_ml']).'" placeholder="0" /></div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="box-'.$this->get_field_id($prefix.'show_excerpt').' '.($instance[$prefix.'show_excerpt'] == 'on' ? '' : 'dnone').'">
					<h4 class="row-title">'.__('Exerpt Settings', 'siteitsob').'</h4>
					<div class="row-wrap">
						<div class="admin-row">			
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_length').'"><span class="label-wrap">'.__('Length (Words)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_length').'" name="'.$this->get_field_name($prefix.'excerpt_length').'" type="number" value="'.(esc_attr($instance[$prefix.'excerpt_length']) ? esc_attr($instance[$prefix.'excerpt_length']) : 25).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_size').'" name="'.$this->get_field_name($prefix.'excerpt_size').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_size']).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_align').'"><span class="label-wrap">'.__('Text Align', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'excerpt_align').'" name="'.$this->get_field_name($prefix.'excerpt_align').'">
										'.$this->multi_select($instance[$prefix.'excerpt_align'], __('Default', 'siteitsob'), 'talign').'
									</select>	
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_width').'"><span class="label-wrap">'.__('Width (PX or %)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_width').'" name="'.$this->get_field_name($prefix.'excerpt_width').'" type="text" value="'.esc_attr($instance[$prefix.'excerpt_width']).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'excerpt_fweight').'" name="'.$this->get_field_name($prefix.'excerpt_fweight').'">
										'.$this->multi_select($instance[$prefix.'excerpt_fweight'], __('Default', 'siteitsob'), 'fontweight').'
									</select>
								</label>
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'excerpt_color').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_color').'" name="'.$this->get_field_name($prefix.'excerpt_color').'" type="color" value="'.esc_attr($instance[$prefix.'excerpt_color']).'" />
								</label>
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'excerpt_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_pr').'" name="'.$this->get_field_name($prefix.'excerpt_pr').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_pr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_pb').'" name="'.$this->get_field_name($prefix.'excerpt_pb').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_pb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_pl').'" name="'.$this->get_field_name($prefix.'excerpt_pl').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_pl']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'excerpt_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_mt').'" name="'.$this->get_field_name($prefix.'excerpt_mt').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_mt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_mr').'" name="'.$this->get_field_name($prefix.'excerpt_mr').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_mr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_mb').'" name="'.$this->get_field_name($prefix.'excerpt_mb').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_mb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'excerpt_ml').'" name="'.$this->get_field_name($prefix.'excerpt_ml').'" type="number" value="'.esc_attr($instance[$prefix.'excerpt_ml']).'" placeholder="0" /></div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="box-'.$this->get_field_id($prefix.'show_rmbtn').' '.($instance[$prefix.'show_rmbtn'] == 'on' ? '' : 'dnone').'">
					<h4 class="row-title">'.__('Button Settings', 'siteitsob').'</h4>
					<div class="row-wrap">
						<div class="admin-row">			
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_label').'"><span class="label-wrap">'.$labelfix.__('Button Text', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_label').'" name="'.$this->get_field_name($prefix.'btn_label').'" type="text" value="'.(esc_attr($instance[$prefix.'btn_label']) ? esc_attr($instance[$prefix.'btn_label']) : __('Read More', 'siteitsob')).'"" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_fsize').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'btn_fsize').'" name="'.$this->get_field_name($prefix.'btn_fsize').'" type="number" value="'.esc_attr($instance[$prefix.'btn_fsize']).'" />
								</label>	
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_align').'"><span class="label-wrap">'.$labelfix.__('Text Align', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'btn_align').'" name="'.$this->get_field_name($prefix.'btn_align').'">
										'.$this->multi_select($instance[$prefix.'btn_align'], '', 'btnAlign').'
									</select>	
								</label>
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_size').'"><span class="label-wrap">'.$labelfix.__('Button Size', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'btn_size').'" name="'.$this->get_field_name($prefix.'btn_size').'">
										'.$this->multi_select($instance[$prefix.'btn_size'], __('Default', 'siteitsob'), 'btnSizes').'
									</select>	
								</label>
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_bradius').'"><span class="label-wrap">'.$labelfix.__('Border Radius', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'btn_bradius').'" name="'.$this->get_field_name($prefix.'btn_bradius').'">
										'.$this->multi_select($instance[$prefix.'btn_bradius'], __('No Radius', 'siteitsob'), 'borderadius').'
									</select>	
								</label>
							</div>
							<div class="col-md-4">
								<label for="'.$this->get_field_id($prefix.'btn_color').'"><span class="label-wrap"><img src="'.$imgUrl.'/colors-01.png" alt="" style="width: 12px; height: 12px;">  '.$labelfix.__('Preset Color', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'btn_color').'" name="'.$this->get_field_name($prefix.'btn_color').'">
										'.$this->multi_select($instance[$prefix.'btn_color'], '', 'btnColors').'
									</select>	
								</label>	
							</div>
							<div class="form-group col-md-12">
								<div class=" fullbED" style="padding: 15px 20px 0; ">
									<div class="admin-row">
										<div class="col-md-12 tooltip" data-tip="'.__('Reset Pre Defined color to use this', 'siteitsob').'">
											<h5 class="bold m0">'.__('Custom Colors', 'siteitsob').'</h5>
										</div>
										<div class="col-md-6">
											<h6><strong><u>'.__('Base State').'</u></strong></h6>
											<div class="form-group">
												<label for="'.$this->get_field_id($prefix.'btn_custom_bg').'"><span class="label-wrap">'.$labelfix.__('Background Color', 'siteitsob').':</span>
													<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_bg').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_bg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_bg']) ? esc_attr($instance[$prefix.'btn_custom_bg']) : '#ECF0F1').'" />
												</label>
											</div>
											<div class="form-group">
												<label for="'.$this->get_field_id($prefix.'btn_custom_color').'"><span class="label-wrap">'.$labelfix.__('Text Color', 'siteitsob').':</span>
													<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_color').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_color').'" value="'.esc_attr($instance[$prefix.'btn_custom_color']).'" />
												</label>											
											</div>
										</div>
										<div class="col-md-6">
											<h6><strong><u>'.__('Hover State').'</u></strong></h6>
											<div class="form-group">
												<label for="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'"><span class="label-wrap">'.$labelfix.__('Background Color', 'siteitsob').':</span>
													<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_hoverbg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_hoverbg']) ? esc_attr($instance[$prefix.'btn_custom_hoverbg']) : '#ECF0F1').'" />
												</label>
											</div>
											<div class="form-group">
												<label for="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'"><span class="label-wrap">'.$labelfix.__('Text Color', 'siteitsob').':</span>
													<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_hovercolor').'" value="'.esc_attr($instance[$prefix.'btn_custom_hovercolor']).'" />
												</label>											
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'btn_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pr').'" name="'.$this->get_field_name($prefix.'btn_pr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pb').'" name="'.$this->get_field_name($prefix.'btn_pb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pl').'" name="'.$this->get_field_name($prefix.'btn_pl').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pl']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-6">
								<label for="'.$this->get_field_id($prefix.'btn_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
								<div class="admin-row small-padding mb0">
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mt').'" name="'.$this->get_field_name($prefix.'btn_mt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mt']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mr').'" name="'.$this->get_field_name($prefix.'btn_mr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mr']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mb').'" name="'.$this->get_field_name($prefix.'btn_mb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mb']).'" placeholder="0" /></div>
									<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_ml').'" name="'.$this->get_field_name($prefix.'btn_ml').'" type="number" value="'.esc_attr($instance[$prefix.'btn_ml']).'" placeholder="0" /></div>
								</div>	
							</div>
							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'btn_classes').'"><span class="label-wrap">'.$labelfix.__('Button Classes', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'btn_classes').'" name="'.$this->get_field_name($prefix.'btn_classes').'" type="text" value="'.esc_attr($instance[$prefix.'btn_classes']).'" />
								</label>							
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
			$(".show_list input[type=checkbox]").on("change", function() {
				var boxcls = ".box-" + $(this).attr("id");

				if ($(this).attr("checked")) {
					$(boxcls).fadeIn();
				} else {
					$(boxcls).fadeOut();
				}
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


		// ENQUEUE ISOTOPE SCRIPT
		wp_enqueue_script('isotope', plugin_dir_url( __FILE__ ) . '../lib/front/isotope.pkgd.min.js', array('jquery'), false, true);



		// BASIC SETTINGS
		$result = '';
        $prefix = '';


		$before_widget  = '<div class="singleWidget sitBuilderWidget postLoopIsotopeWidget '.$instance[$prefix.'widget_classes'].'">';
		$after_widget   = '</div>';


		// SOME DEFAULTS
		if(!$instance[$prefix.'post_amount']) {$instance[$prefix.'post_amount'] = 6;}
		if(!$instance[$prefix.'post_orderby']) {$instance[$prefix.'post_orderby'] = 'date';}
		if(!$instance[$prefix.'post_order']) {$instance[$prefix.'post_order'] = 'DESC';}


		/*********************************************
		** BUILD STYLING FOR DIFFERENT PARTS
		*********************************************/
		
		// IMG STYLING
		if($instance[$prefix.'show_image'] == 'on') {
			$imgStyles 	= '';
			$imgClasses = '';

			if($instance[$prefix.'image_border'] == 'yes') { 
				$imgStyles 	.= 'border: 1px solid '.$instance[$prefix.'image_bcolor'];
				$imgClasses .= $instance[$prefix.'image_bradius']; 
			}
			
			$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_pt'], $instance[$prefix.'image_pr'], $instance[$prefix.'image_pb'], $instance[$prefix.'image_pl'], 'padding');
			$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_mt'], $instance[$prefix.'image_mr'], $instance[$prefix.'image_mb'], $instance[$prefix.'image_ml'], 'margin');
		}


		// TITLE STYLING
		if($instance[$prefix.'show_title'] == 'on') {
			$titleCss 		= '';
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

			if(!$instance[$prefix.'title_type']) {$instance[$prefix.'title_type'] = 'h3';}
			if(!$instance[$prefix.'title_size']) {$instance[$prefix.'title_size'] = '30'; }
			if($instance[$prefix.'title_color']) {$titleCss .= 'color:'.$instance[$prefix.'title_color'].';';}
			if($instance[$prefix.'title_width']) {$titleCss .= 'width:'.$instance[$prefix.'title_width'].';'; }
		}


		// DATE STYLING
		if($instance[$prefix.'show_date'] == 'on') {
			$dateCss 		= '';
			$dateClsses 	= '';

			$dateCss		.= $this->build_margpad_array($instance[$prefix.'date_pt'], $instance[$prefix.'date_pr'], $instance[$prefix.'date_pb'], $instance[$prefix.'date_pl'], 'padding');
			$dateCss		.= $this->build_margpad_array($instance[$prefix.'date_mt'], $instance[$prefix.'date_mr'], $instance[$prefix.'date_mb'], $instance[$prefix.'date_ml'], 'margin');

			if($instance[$prefix.'date_size']) {$dateCss .= 'font-size: '.$instance[$prefix.'date_size'].'px;';}
			if($instance[$prefix.'date_width']) {$dateCss .= 'width: '.$instance[$prefix.'date_width'].';';}
			if($instance[$prefix.'date_color']) {$dateCss .= 'color: '.$instance[$prefix.'date_color'].';';}
			if($instance[$prefix.'date_align']) {$dateClsses .= $instance[$prefix.'date_align'].' ';}
			if($instance[$prefix.'date_fweight']) {$dateClsses .= $instance[$prefix.'date_fweight'].' ';}
		}


		// EXCERPT STYLING
		if($instance[$prefix.'show_excerpt'] == 'on') {
			$xcrptCss 		= '';
			$xcrptClasses	= '';

			$xcrptCss		.= $this->build_margpad_array($instance[$prefix.'excerpt_pt'], $instance[$prefix.'excerpt_pr'], $instance[$prefix.'excerpt_pb'], $instance[$prefix.'excerpt_pl'], 'padding');
			$xcrptCss		.= $this->build_margpad_array($instance[$prefix.'excerpt_mt'], $instance[$prefix.'excerpt_mr'], $instance[$prefix.'excerpt_mb'], $instance[$prefix.'excerpt_ml'], 'margin');

			if(!$instance[$prefix.'excerpt_length']) {$instance[$prefix.'excerpt_length'] = 33;}
			if($instance[$prefix.'excerpt_size']) {$xcrptCss .= 'font-size: '.$instance[$prefix.'excerpt_size'].'px;';}
			if($instance[$prefix.'excerpt_width']) {$xcrptCss .= 'width: '.$instance[$prefix.'excerpt_width'].'px;';}
			if($instance[$prefix.'excerpt_color']) {$xcrptCss .= 'color: '.$instance[$prefix.'excerpt_color'].';';}
			if($instance[$prefix.'excerpt_align']) {$xcrptClasses .= $instance[$prefix.'excerpt_align'].' ';}
			if($instance[$prefix.'excerpt_fweight']) {$xcrptClasses .= $instance[$prefix.'excerpt_fweight'].' ';}

		}


		// READ MORE STYLING
		if($instance[$prefix.'show_rmbtn'] == 'on') {
			$rmoreCss 			= '';
			$rmoreClasses		= '';
			$rmoreInlineStyle	= '';

			$rmoreCss			.= $this->build_margpad_array($instance[$prefix.'btn_pt'], $instance[$prefix.'btn_pr'], $instance[$prefix.'btn_pb'], $instance[$prefix.'btn_pl'], 'padding');
			$rmoreCss			.= $this->build_margpad_array($instance[$prefix.'btn_mt'], $instance[$prefix.'btn_mr'], $instance[$prefix.'btn_mb'], $instance[$prefix.'btn_ml'], 'margin');

			if($instance[$prefix.'btn_size']) {$rmoreClasses .= $instance[$prefix.'btn_size'].' ';}
			if($instance[$prefix.'btn_bradius']) {$rmoreClasses .= $instance[$prefix.'btn_bradius'].' ';}
			if($instance[$prefix.'btn_color']) {$rmoreClasses .= $instance[$prefix.'btn_color'].' ';}

			if(!$instance[$prefix.'btn_color'] && $instance[$prefix.'btn_custom_bg']) {$rmoreCss .= 'background: '.$instance[$prefix.'btn_custom_bg'].';';}
			if(!$instance[$prefix.'btn_color'] && $instance[$prefix.'btn_custom_color']) {$rmoreCss .= 'color: '.$instance[$prefix.'btn_custom_color'].';';}

			if(!$instance[$prefix.'btn_color'] && $instance[$prefix.'$rmoreInlineStyle']) {$rmoreInlineStyle .= '#btn-'.$this->id_base.'-'.$this->number.':hover {color: '.$instance[$prefix.'btn_custom_hovercolor'].'; background: '.$instance[$prefix.'btn_custom_hoverbg'].';}';} else {$rmoreInlineStyle = '';}
			
		}


        /*************************************************
        ** BUILD CATEGORY SORTER
        *************************************************/
        $categories     = '<li class="sort active" data-filter="*"> '.__('All', 'siteitsob').'</li>';
        $terms          = get_terms( 'category', array( 'hide_empty' => true, 'include' => $instance[$prefix.'cats_list'] ) );
		
        foreach($terms as $term) {
            $categories .= '<li class="sort" data-filter=".term-'.$term->term_id.'"> '.$term->name.'</li>';
        }


        /*************************************************
        ** GET POSTS FROM SELECTED CATEGORIES
        *************************************************/
        $posts = '';
        wp_reset_query();

		$args = array(
			'post_type'			=> 'post', 
			'posts_per_page'	=> $instance[$prefix.'post_amount'],
			'orderby'			=> $instance[$prefix.'post_order'],
			'order'				=> $instance[$prefix.'post_orderby'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $instance[$prefix.'cats_list'],
                ),
            ),
		);
        $query = new WP_Query($args);

        if ( $query->have_posts() ) {
            while($query->have_posts()) {
                $query->the_post();
            
                // DEFINE VARIABLES
                $pid		= get_the_ID();
                $title      = get_the_title();
                $excerpt    = get_the_content();
                $plink      = get_permalink();
                $thumb		= get_the_post_thumbnail_url($pid); 
                $date		= get_the_date( 'F d, Y', $pid );
                $cat        = wp_get_post_terms( $pid, 'category' );
                $cat_id     = $cat[0]->term_id;
                $cat_name   = $cat[0]->name;
                $cat_slug   = $cat[0]->slug;
                $cat_id   	= $cat[0]->term_id;
                $cat_sname  = get_term_meta( $cat_id, THEME_PREF.'short_name', true );
                $cat_color  = get_term_meta( $cat_id, THEME_PREF.'highlight_color', true );
                $cat_style  = get_term_meta( $cat_id, THEME_PREF.'slim_design', true );
                
                
                // AUTHOR
                $author_id  = get_post_field ('post_author', $pid);
                $author     = get_userdata( $author_id );
                $author_desc= get_user_meta( $author_id, 'description', true );


                // FIX THUMB
                if(!$thumb) {$thumb = 'http://placehold.it/700x466/&text=No Image';}



                $posts .= '
                <div class="item '.$instance[$prefix.'items_inrow'].' all term-'.$cat_id.'">
                    <div class="relative">
						'.($instance[$prefix.'show_image'] == 'on' ? '<div class="thumb"> <a href="'.$plink.'"><img src="'.$thumb.'" alt="'.$thumb.'" class="img-responsive '.$imgStyles.'"></a> <div class="ribbon" style="background: '.$cat_color.';">'.$cat_sname.'</div> </div>' : '').'
						<div class="inner">
							'.($instance[$prefix.'show_title'] == 'on' ? '<div class="title"><'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].'" style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;">'.$title.'</'.$instance[$prefix.'title_type'].'></div>' : '').'
							'.($instance[$prefix.'show_date'] == 'on' ? '<div class="date" style="'.$dateCss.'">'. $date.'</div>' : '').'
							'.($instance[$prefix.'show_excerpt'] == 'on' ? $instance[$prefix.'excerpt_length'].'<div class="excerpt '.$xcrptClasses.'" style="'.$xcrptCss.'">'.wp_trim_words( $excerpt, $instance[$prefix.'excerpt_length'], '...' ).'</div>' : '').'
                            '.($instance[$prefix.'show_rmbtn'] == 'on' ? '<div class="rmbox '.$instance[$prefix.'btn_align'].'"><a id="btn-'.$this->id_base.'-'.$this->number.'" href="'.$plink.'" class="btn '.$rmoreClasses.'" style="'.$rmoreCss.'">'.$instance[$prefix.'btn_label'].'</a></div>'.$rmoreInlineStyle : '').'
                        </div>
                    </div>
                </div>
                ';
                
			}
			


			$result = '
			<div class="sorter">
				<div class="row">
					<div class="col-md-10">
						<ul class="filter-group">'.$categories.'</ul>
					</div>
					<div class="col-md-2">
						<select name="sortpostsby" id="sortpostsby" class="form-control">
							<option data-sort-by="original-order">'.__('Sort By', 'siteitsob').'</option>
							<option data-sort-by="author">'.__('By Author', 'siteitsob').'</option>
							<option data-sort-by="title">'.__('By Title', 'siteitsob').'</option>
							<option data-sort-by="date">'.__('By Date', 'siteitsob').'</option>
						</select>
					</div>
				</div>
			</div>
			<div class="isotopebox-box">
				<div id="isotope-'.$this->id_base.'-'.$this->number.'" class="isotopebox row">
					'.$posts.'
				</div>
			</div>
			
	
			<style>
			.postLoopIsotopeWidget .sorter { margin: 60px 0; }
			.postLoopIsotopeWidget .sorter input[type="checkbox"] {display: none;}
			.postLoopIsotopeWidget .sorter label {margin-bottom: 0;}
			.postLoopIsotopeWidget .sorter ul {display: inline-block; padding: 0 10px;}
			.postLoopIsotopeWidget .sorter li {font-size: 14px;float: left;text-transform: uppercase; cursor: pointer; color: #565656; font-weight: light; display: inline-block; padding: 10px 70px; border-bottom: 4px solid #F4F4F4;}
			.postLoopIsotopeWidget .sorter li.active, li:hover { color: #00004f; border-bottom: 4px solid #00004f;}
			.postLoopIsotopeWidget #sortpostsby {background-color: #e2e4e7; color: #555555; font-size: 16px; height: 41px; border: none;}
			
			.item {
				transition: opacity 0.5s, background-color 0.25s linear, border-color 0.25s linear;
			}
			'.$rmoreInlineStyle.'
			</style>
			 
			<script>
			jQuery(function($){
	
				var $grid = $("#isotope-'.$this->id_base.'-'.$this->number.'").isotope({
					itemSelector : ".item",
					fitRows: {
						gutter: 25
					},
					getSortData: {
						author: ".author",
						date: ".date parseInt",
						title: ".title"
					}
				});
	
	
				$(".filter-group").on( "click", "li", function() {
					var filterValue = $(this).attr("data-filter");
					$grid.isotope({ filter: filterValue });
	
					$(this).addClass("active");
					
					$(".filter-group").find("li").each(function() {
						$(this).removeClass("active");
					});
				});
	
				// sort items on button click
				$("#sortpostsby").on("change", function() {
					var sortByValue = $("#sortpostsby option:selected").attr("data-sort-by");
					$grid.isotope({ sortBy: sortByValue });
				});
			});
	
	
			jQuery(document).ready(function($) {
				$grid = $("#isotope-'.$this->id_base.'-'.$this->number.'");
				$grid.isotope();
			});
			</script>
			';
	
	
	
			// FIRST BUILD ARRAY OF SELECTED CATEGORIES
			$categories = get_terms( array( 'taxonomy' => 'category', 'object_ids' => $instance[$prefix.'cats_list'], ) );

        }



		echo $before_widget.$result.$after_widget;
	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgPostIsotopeLooperWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgPostIsotopeLooperWidget');
}, 1 );
?>