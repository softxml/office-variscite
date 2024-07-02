<?php

defined( 'ABSPATH' ) || exit;

$checkout = WC()->checkout();

?>
<div class="wopb-checkout-shipping-container">
    <div class="woocommerce-shipping-fields">
        <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

            <h3 id="ship-to-different-address">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php echo esc_html( $attr["checkboxTitle"], 'product-blocks' ); ?></span>
                </label>
            </h3>
            
            <div class="shipping_address">
                <?php if($attr['showTitle']) {
                    ?>
                    <div class="wopb-shipping-title"><?php echo esc_html($attr['shippingTitle']); ?></div>

                <?php } ?>

                <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

                <div class="woocommerce-shipping-fields__field-wrapper">
                    <?php
                        $fields = $checkout->get_checkout_fields( 'shipping' );
                        
                        foreach ( $fields as $key => $field ) {
                            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                        }
                    ?>
                </div>

                <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

            </div>

        <?php endif; ?>
    </div>
</div>
