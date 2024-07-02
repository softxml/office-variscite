<?php
defined('ABSPATH') || exit;
$attr['titleTag'] = in_array($attr['titleTag'],  wopb_function()->allowed_block_tags() ) ? $attr['titleTag'] : 'h3';
$title_data .= '<'.esc_attr($attr['titleTag']).' class="wopb-block-title"><a href="'.esc_url($titlelink).'">'.wp_kses_post($title).'</a></'.esc_attr($attr['titleTag']).'>';