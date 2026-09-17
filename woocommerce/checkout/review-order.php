<?php
/**
 * Review Order Template - RajaSkin Luxury Design
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cart_items = WC()->cart->get_cart();
$item_count = count( $cart_items );
?>
<div class="order-summary-card woocommerce-checkout-review-order-table">
    <h3 class="summary-card-title">Order Summary</h3>

    <!-- Products List in Summary -->
    <div class="summary-items-list">
        <?php
        foreach ( $cart_items as $cart_item_key => $cart_item ) :
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) : ?>
                <div class="summary-item-row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                    <div class="summary-item-thumb">
                        <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 52, 52 ) ), $cart_item, $cart_item_key ); ?>
                    </div>
                    <div class="summary-item-details">
                        <span class="summary-item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></span>
                        <span class="summary-item-qty">Qty: <?php echo esc_html( $cart_item['quantity'] ); ?></span>
                    </div>
                    <div class="summary-item-price">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                    </div>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <div class="summary-divider"></div>

    <!-- Subtotal & Shipping Rows -->
    <div class="summary-calc-row">
        <span class="calc-label">Shipping Fee</span>
        <span class="calc-value shipping-fee-val" id="summaryShippingFee">
            <?php
            $shipping_total = ( WC()->cart && WC()->cart->needs_shipping() && WC()->cart->show_shipping() )
                ? WC()->cart->get_shipping_total()
                : 0;

            if ( $shipping_total > 0 ) {
                echo wc_price( $shipping_total );
            } else {
                echo 'Rp0';
            }
            ?>
        </span>
    </div>

    <div class="summary-calc-row">
        <span class="calc-label">Subtotal (<span id="summaryCountLabel"><?php echo sprintf( _n( '%d Product', '%d Products', $item_count, 'woocommerce' ), $item_count ); ?></span>)</span>
        <span class="calc-value" id="summarySubtotal"><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="summary-calc-row discount-row">
            <span class="calc-label"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span class="calc-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <div class="summary-calc-row fee-row">
            <span class="calc-label"><?php echo esc_html( $fee->name ); ?></span>
            <span class="calc-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
        </div>
    <?php endforeach; ?>

    <div class="summary-divider"></div>

    <!-- Total Row -->
    <div class="summary-total-row">
        <span class="total-label-text">TOTAL</span>
        <span class="total-amount-text" id="summaryGrandTotal"><?php echo WC()->cart->get_total(); ?></span>
    </div>
</div>
