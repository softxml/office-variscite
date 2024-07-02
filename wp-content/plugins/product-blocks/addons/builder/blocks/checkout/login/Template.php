<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
    return;
}
$hidden = true;
$message = 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.';

?>
<div class="wopb-checkout-login-container">
	<div class="woocommerce-form-login-toggle">
		<?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'product-blocks' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'product-blocks' ) . '</a>', 'notice' ); ?>
	</div>

	<div class="woocommerce-form woocommerce-form-login login" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

		<?php do_action( 'woocommerce_login_form_start' ); ?>
		<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

        <span class="wopb-form-error"></span>
		<p class="form-row form-row-first">
			<label for="username"><?php esc_html_e( 'Username or Email', 'product-blocks' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
		</p>
		<p class="form-row form-row-last">
			<label for="password"><?php esc_html_e( 'Password', 'product-blocks' ); ?>&nbsp;<span class="required">*</span></label>
			<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
		</p>
		<div class="clear"></div>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<p class="form-row">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
				<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'product-blocks' ); ?></span>
			</label>
			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
			<button class="woocommerce-button button woocommerce-form-login__submit wopb-form-login__submit" name="login" value="<?php esc_attr_e( 'Login', 'product-blocks' ); ?>"><?php esc_html_e( 'Login', 'product-blocks' ); ?></button>
		</p>
		<p class="lost_password">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'product-blocks' ); ?></a>
		</p>

		<div class="clear"></div>

		<?php do_action( 'woocommerce_login_form_end' ); ?>

	</div>
</div>
