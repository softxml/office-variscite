<?php
echo 'test';
function multi_select($value, $label, $type) {
	$data = '';
	
	$typeArr = $this->sgDataArr($type);
	
	if($label) {
		$data .= '<option value="">'.$label.'</option>';
	}
	
	foreach($typeArr as $key => $opt) {
		if($key == $value) {$selected = 'selected';} else {$selected = '';}
		$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
	}
	
	return $data;
}


//class BaseWidgetFunc {


	/*********************************************
	** PADDING / MARGIN AUTOMATOR
	********************************************
	function zeroStayFilter($var){ return ($var !== NULL && $var !== FALSE && $var !== ''); }

	function build_margpad_array(string $top, string $right, string $bottom, string $left, string $type) {
		$result  				= '';
		$arr[$type.'-top'] 		= $top;
		$arr[$type.'-right'] 	= $right;
		$arr[$type.'-bottom'] 	= $bottom;
		$arr[$type.'-left'] 	= $left;
		$arr 					= array_filter($arr, array($this, 'zeroStayFilter'));


		if(!empty($arr)) { foreach($arr as $key => $value) { $result .= $key.':'.$value.'px;'; } }
		return $result;
	}
	
	*/


	/*********************************************
	** MULTI SELECT GENERATOR
	********************************************
	function multi_select($value, $label, $type) {
		$data = '';
		$typeArr = $this->sgDataArr($type);
		if($label) { $data .= '<option value="">'.$label.'</option>'; }
		
		foreach($typeArr as $key => $opt) {
            if(is_array($value) && in_array($key, $value)) {$selected = 'selected';}
			elseif($key == $value) {$selected = 'selected';} 
            else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}

	*/
	
//}

?>