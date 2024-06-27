<?php
/*********************************************
** ADD WOO-COMMERCE CART ICON TO MENU
*********************************************/
add_filter('wp_nav_menu_items','sk_wcmenucart', 10, 2);
function sk_wcmenucart($menu, $args) {

	if($args->menu == 'topmenu') {

		$activePlugins = get_option('active_plugins');

		// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
		if ( !in_array( 'woocommerce/woocommerce.php', $activePlugins) ) {
			return $menu;
		}
		else {
			ob_start();
				global $woocommerce;
				$viewing_cart 			= __('View your shopping cart', 'your-theme-slug');
				$start_shopping 		= __('Start shopping', 'your-theme-slug');
				$cart_url 				= function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
				$shop_page_url 			= get_permalink( wc_get_page_id( 'shop' ) );
				$cart_contents_count 	= $woocommerce->cart->cart_contents_count;
				$cart_contents 			= sprintf(_n('%d item', '%d items', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
				$cart_total 			= $woocommerce->cart->get_cart_total();


				if ($cart_contents_count == 0) {
					$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $shop_page_url .'" title="'. $start_shopping .'">';
				} else {
					$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $cart_url .'" title="'. $viewing_cart .'">';
				}

				$menu_item .= '<img src="'.IMG_URL.'/woo/cart-icon.png" alt="'.__('Cart', THEME_NAME).'">';

				$menu_item .= $cart_contents.' - '. $cart_total;
				$menu_item .= '</a></li>';

				echo $menu_item;

			$social = ob_get_clean();
			return $menu . $social;
		}

	}
	else {
		return $menu;
	}

}




/*************************************************
** LOAD SINGLE PRODUCT TEMPLATE ACCORDING TO CAT
** woocommerce/templates/single-product-accessories.php
** woocommerce/templates/single-product-kits.php
************************************************
add_filter( 'template_include', 'so_25789472_template_include' );

function so_25789472_template_include( $template ) {
  if ( is_singular('product') && (has_term( 'mock', 'product_cat')) ) {
    $template = get_stylesheet_directory() . '/woocommerce/single-product-mock.php';
  } 
  return $template;
}
*/
?>