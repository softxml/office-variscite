<?php
/**********************************
** DYNAMIC EXCERPT
**********************************/
function sg_dynamic_excerpt($length) {

	global $post;
	$text = $post->post_excerpt;
	
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
	}
	
	$text = strip_shortcodes($text);
	$text = strip_tags($text);
	$text = substr($text,0,$length).'...';
	
	return $text; // Use this is if you want a unformatted text block
	//echo apply_filters('the_excerpt',$text);
}




/**********************************
** CONTENT TO EXCERPT
**********************************/
function content_to_excerpt($string, $length) {

	if ( '' == $string ) {
		$string = get_the_content('');
		$string = apply_filters('the_content', $string);
		$string = str_replace(']]>', ']]>', $string);
	}
	
	$string 	= strip_shortcodes($string);
	$string 	= preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $string);
	$string		= strip_tags($string);

	if(is_rtl() || ICL_LANGUAGE_CODE == 'it' || ICL_LANGUAGE_CODE == 'de') {
		if(mb_strlen($string) > $length) {$string = mb_substr($string, 0, $length).'...';
		} else {
			$string = $string;
		}
	}
	else {
		if(strlen($string) > $length) {
			$string = substr($string, 0, $length).'...';
		} else {
			$string = $string;
		}
	}
	return $string;
	
}



/**********************************
** CLEAN URL
**********************************/
function url_to_base($url) {
	$url	=	parse_url($url);
	$url	=	$url['host'];
	$url	=	str_replace(array('/', 'www.'), '', $url);
	
	return $url;
}



/*** COUNT WORDS ***/
function sg_count_words($string) {
    $wordsCount = count(preg_split('~[^\p{L}\p{N}\']+~u',$string));
    return $wordsCount;
}

/*** COUNT CHARS ***/
function sg_count_chars($string) {
    $charsCount = strlen($string);
    return $charsCount;
}



/**********************************
** FORMAT BIG NUMBERS WITH SUFFIX
**********************************/
function formatNumSuffix($input) {
    $suffixes = array('', 'k', 'm', 'g', 't');
    $suffixIndex = 0;

    while(abs($input) >= 1000 && $suffixIndex < sizeof($suffixes))
    {
        $suffixIndex++;
        $input /= 1000;
    }

    return (
        $input > 0
            // precision of 3 decimal places
            ? floor($input * 1000) / 1000
            : ceil($input * 1000) / 1000
        )
        . $suffixes[$suffixIndex];
}
?>