<?php
/*************************************************
** SMART POST THUMBNAIL GENERATOR
*************************************************/
function siteitsob_smart_thumbnail($postid, $width = NULL, $height = NULL, $xclasses = NULL, $thumb_alt = NULL, $defaultThumb = NULL) {

	$thumb_id 	= get_post_thumbnail_id($postid);
	$thumb_alt 	= get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

	// IMAGE URL AND ACTUALL SIZE
	if($thumb_id) {
		$thumb_data		= wp_get_attachment_image_src( $thumb_id, 'full' );
		$thumb_url		= $thumb_data[0];
		$thumb_width	= intval($thumb_data[1]);
		$thumb_height	= intval($thumb_data[2]);
	}


	// MAKE SURE IMAGE SIZE IS BIGGET BEFORE RESIZING
	if(!$width || !$height ) {$width = 360; $height = 240;}

	// MAKE SURE SOMETHING IS LOADED
	if(!$thumb_url && $defaultThumb) {$thumb_url = $defaultThumb;}
	elseif(!$thumb_url && !$defaultThumb) {$thumb_url = 'http://placehold.it/'.$width.'x'.$height.'/';}

	if($width < $thumb_width && $height < $thumb_height) {
		$thumb_url = aq_resize($thumb_url, $width, $height);
	}
	
	// BUILD ALT
	if($thumb_url && !$thumb_alt) {
		$thumb_alt		=	explode('/', $thumb_url);
		$thumb_alt		=	end($thumb_alt);
		$thumb_alt		=	str_replace(array('-', '_'), '', $thumb_alt);
	}
	if(!$thumb_alt) {$thumb_alt = '';}

	return '<img src="'.$thumb_url.'" alt="'.$thumb_alt.'" class="img-responsive '.$xclasses.'" />';
	
	
}



/*************************************************
** SMART POST THUMBNAIL - URL - GENERATOR
*************************************************/
function siteitsob_smart_thumbnail_url($postid, $width = NULL, $height = NULL, $defaultThumb = NULL) {

	$thumb_id 	= get_post_thumbnail_id($postid);

	// IMAGE URL AND ACTUALL SIZE
	if($thumb_id) {
		$thumb_data		= wp_get_attachment_image_src( $thumb_id, 'full' );
		$thumb_url		= $thumb_data[0];
		$thumb_width	= intval($thumb_data[1]);
		$thumb_height	= intval($thumb_data[2]);
	}



	// MAKE SURE SOMETHING IS LOADED
	if(!$thumb_url && $defaultThumb) {$thumb_url = $defaultThumb;}
	elseif(!$thumb_url && !$defaultThumb) { if(!$width) {$width = 640;} if(!$height) {$height = 480;} $thumb_url = 'http://placehold.it/'.$width.'x'.$height.'/'; }

	// MAKE SURE IMAGE SIZE IS BIGGET BEFORE RESIZING
	if($width && $height) {
		if($width < $thumb_width && $height < $thumb_height) {
			$thumb_url = aq_resize($thumb_url, $width, $height, true);
		}
	}

	return $thumb_url;
	
}




/*************************************************
** BUILD MARGIN or PADDING ARRAY
*************************************************/
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




/********************************************************
**  EASIER MARGIN PADDIN HELPER
********************************************************/
function easy_margpad_array($instance, $prefix, $partname, $type) {

	$t = $type[0];

	$result  				= '';
	$arr[$type.'-top'] 		= !empty($instance[$prefix.$partname.'_'.$t.'t']) ? $instance[$prefix.$partname.'_'.$t.'t'] : '';
	$arr[$type.'-right'] 	= !empty($instance[$prefix.$partname.'_'.$t.'r']) ? $instance[$prefix.$partname.'_'.$t.'r'] : '';
	$arr[$type.'-bottom'] 	= !empty($instance[$prefix.$partname.'_'.$t.'b']) ? $instance[$prefix.$partname.'_'.$t.'b'] : '';
	$arr[$type.'-left'] 	= !empty($instance[$prefix.$partname.'_'.$t.'l']) ? $instance[$prefix.$partname.'_'.$t.'l'] : '';
	$arr 					= array_filter($arr);

	if(!empty($arr)) {
		foreach($arr as $key => $value) {
			$result .= $key.':'.$value.'px;';
		}
	}

	return $result;
}







/*************************************************
** AUTO BUILD SELECT ARRAY USING NUMBERS
*************************************************/
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

		
		

	   


/*********************************************
** SHOW ON HOVER SHORTCODE
*********************************************/
function siteSonShowOnHoversc( $atts, $content = null ) {   

	extract(shortcode_atts(array(
		"classes" => '',
	), $atts));

    return '<span class="sitShowOnHover '.$classes.' ">' . do_shortcode($content) . '</span>';
}
add_shortcode('showonhover','siteSonShowOnHoversc');



/*********************************************
** BUTTON SHORTCODE
*********************************************/
function siteSobEeasyBtn( $atts, $content = null ) {   

	extract(shortcode_atts(array(
		"url" 		=> '',
		"target" 	=> '_parent',
		"classes" 	=> '',
	), $atts));

    return '<a href="'.$url.'" class="btn '.$classes.' ">' . $content . '</a>';
}
add_shortcode('easybtn','siteSobEeasyBtn');




/**********************************
** CONTENT TO EXCERPT
**********************************/
function sitsob_text_to_excerpt($string, $length) {

	if ( '' == $string ) {
		$string = get_the_content('');
		$string = apply_filters('the_content', $string);
		$string = str_replace(']]>', ']]>', $string);
	}
	
	$string 	= strip_shortcodes($string);
	$string 	= preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $string);
	$string		= strip_tags($string);

	if(is_rtl()) {if(mb_strlen($string) > $length) {$string = mb_substr($string, 0, $length).'...';} else {$string = $string;}}
	else {if(strlen($string) > $length) {$string = substr($string, 0, $length).'...';} else {$string = $string;}}
	return $string;
	
}
?>