<?php
/**
 * Initial Setup.
 *
 * @package WOPB\Notice
 * @since v.2.4.4
 */
namespace WOPB;

defined( 'ABSPATH' ) || exit;

class ProPlugins {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'pro_addons_data' ) );
	}

	/**
	 * Admin Init
	 *  * @since 3.0.0
	 * @return NULL
	 */
	public function pro_addons_data() {
        if ( ! wopb_function()->isPro() ) {
            return add_filter( 'wopb_addons_config', function($arr) {
                $pro_addons = array(
                    'wopb_backorder' => array(
                        'name' => __( 'Backorder', 'product-blocks' ),
                        'desc' => __( 'Keep getting orders for the products that are currently out of stock and will be restocked soon.', 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/backorder.svg',
                        'is_pro' => true,
                        'live' => 'https://www.wpxpo.com/productx/addons/backorder/live_demo_args',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/back-order-addon/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=wRLNZoOVM7o',
                        'type' => 'sales',
                        'priority' => 40
                    ),
                    'wopb_call_for_price' => array(
                        'name' => __( 'Call for Price', 'product-blocks' ),
                        'desc' => __( " Display a calling button instead of add to cart button for the products that don't have prices.", 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/call-for-price.svg',
                        'is_pro' => true,
                        'live' => 'https://www.wpxpo.com/productx/addons/call-for-price/live_demo_args',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/call-for-price-addon/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=Lk4rm9GFQ_M',
                        'type' => 'sales',
                        'priority' => 50
                    ),
                    'wopb_currency_switcher' => array(
                        'name' => __( 'Currency Switcher', 'product-blocks' ),
                        'desc' => __( ' Allows customers to switch currencies to see the product prices and make payments in their local currencies.', 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/currency_switcher.svg',
                        'is_pro' => true,
                        'live' => '',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/currency-switcher-addon/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=nhqFZmCzfow',
                        'type' => 'sales',
                        'priority' => 60
                    ),
                    'wopb_partial_payment' => array(
                        'name' => __( 'Partial Payment', 'product-blocks' ),
                        'desc' => __( 'It allows customers to pay a deposit amount while making orders and later they can pay the due amount from the order dashboard.', 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/partial-payment.svg',
                        'is_pro' => true,
                        'live' => 'https://www.wpxpo.com/productx/addons/partial-payment/live_demo_args',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/partial-payment/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=QW0RnzMTvJs',
                        'type' => 'sales',
                        'priority' => 55
                    ),
                    'wopb_preorder' => array(
                        'name' => __( 'Pre-Orders', 'product-blocks' ),
                        'desc' => __( 'Display upcoming products as regular products to get orders for the products that are not released yet.                ', 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/pre-order.svg',
                        'is_pro' => true,
                        'live' => 'https://www.wpxpo.com/productx/addons/pre-order/live_demo_args',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/pre-order-addon/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=jeGZYsDyPfI',
                        'type' => 'sales',
                        'priority' => 35
                    ),
                    'wopb_stock_progress_bar' => array(
                        'name' => __( 'Stock Progress Bar', 'product-blocks' ),
                        'desc' => __( 'Visually highlight the total and remaining stocks of products to encourage shoppers to purchase them before stock out.', 'product-blocks' ),
                        'img' => WOPB_URL.'/assets/img/addons/stock-progress-bar.svg',
                        'is_pro' => true,
                        'live' => 'https://www.wpxpo.com/productx/addons/stock-progress-bar/live_demo_args',
                        'docs' => 'https://wpxpo.com/docs/productx/add-ons/stock-progress-bar-addon/addon_doc_args',
                        'video' => 'https://www.youtube.com/watch?v=y0kKdffis-A',
                        'type' => 'sales',
                        'priority' => 45
                    ),
                );
                return array_merge($arr, $pro_addons);
            });
        }
	}
}