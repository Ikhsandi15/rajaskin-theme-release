<?php
/**
 * Pay for Order Form - RajaSkin Luxury Checkout
 *
 * This template overrides woocommerce/templates/checkout/form-pay.php.
 * Handles /checkout/order-pay/:id with dynamic payment gateways,
 * order summary, and responsive luxury styling.
 *
 * @version 8.2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $order ) || ! $order || ! is_a( $order, 'WC_Order' ) ) {
    ?>
    <div class="container rs-checkout-page-wrap rs-order-pay-page-wrap">
        <div class="rs-order-confirmed-card rs-order-failed-card" style="max-width: 560px; margin: 2rem auto;">
            <div class="rs-confirmed-icon rs-icon-failed">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h1 class="rs-confirmed-title"><?php esc_html_e( 'Order Not Found', 'rajaskin-wp-theme' ); ?></h1>
            <p class="rs-confirmed-desc">
                <?php esc_html_e( 'The requested order could not be loaded or the payment link is invalid. Please check your order history or return to shop.', 'rajaskin-wp-theme' ); ?>
            </p>
            <div class="rs-order-actions-row" style="margin-top: 1.5rem; display:flex; flex-direction:column; gap:10px;">
                <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="btn-track-order">
                    <?php esc_html_e( 'View Order History', 'rajaskin-wp-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-track-order" style="background: #f4f4f5; color: #111111 !important;">
                    <?php esc_html_e( 'Return to Shop', 'rajaskin-wp-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    return;
}

$order_id      = $order->get_id();
$order_number  = $order->get_order_number();
$order_date    = $order->get_date_created() ? wc_format_datetime( $order->get_date_created(), 'j F Y' ) : date( 'j F Y' );
$order_status  = $order->get_status();
$order_total   = $order->get_formatted_order_total();
$billing_email = $order->get_billing_email() ?: 'Customer';
$customer_name = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );
if ( empty( $customer_name ) ) {
    $customer_name = $billing_email;
}

// Shipping method name & est delivery
$shipping_methods = $order->get_shipping_methods();
$shipping_name    = 'Standard Courier';
if ( ! empty( $shipping_methods ) ) {
    $first_shipping = reset( $shipping_methods );
    if ( $first_shipping && $first_shipping->get_name() ) {
        $shipping_name = $first_shipping->get_name();
    }
}

// Check if order does NOT need payment (already completed/processing/cancelled)
if ( ! $order->needs_payment() ) :
    $is_paid = in_array( $order_status, array( 'processing', 'completed' ), true );
    ?>
    <div class="container rs-checkout-page-wrap rs-order-pay-page-wrap">
        <div class="rs-order-confirmed-card" style="max-width: 560px; margin: 2rem auto;">
            <div class="rs-confirmed-icon <?php echo $is_paid ? '' : 'rs-icon-failed'; ?>">
                <?php if ( $is_paid ) : ?>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                <?php else : ?>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                <?php endif; ?>
            </div>

            <h1 class="rs-confirmed-title">
                <?php echo $is_paid ? esc_html__( 'Order Already Paid', 'rajaskin-wp-theme' ) : esc_html__( 'Payment Not Required', 'rajaskin-wp-theme' ); ?>
            </h1>

            <p class="rs-confirmed-desc">
                <?php
                if ( $is_paid ) {
                    printf( esc_html__( 'Order #%s has already been paid and is currently %s.', 'rajaskin-wp-theme' ), esc_html( $order_number ), '<strong>' . esc_html( wc_get_order_status_name( $order_status ) ) . '</strong>' );
                } else {
                    printf( esc_html__( 'Order #%s status is currently %s and does not require payment at this moment.', 'rajaskin-wp-theme' ), esc_html( $order_number ), '<strong>' . esc_html( wc_get_order_status_name( $order_status ) ) . '</strong>' );
                }
                ?>
            </p>

            <div class="rs-confirmed-details" style="margin-bottom: 24px;">
                <div class="rs-detail-row">
                    <span class="rs-detail-label"><?php esc_html_e( 'Order Number', 'rajaskin-wp-theme' ); ?></span>
                    <span class="rs-detail-value">#<?php echo esc_html( $order_number ); ?></span>
                </div>
                <div class="rs-detail-row">
                    <span class="rs-detail-label"><?php esc_html_e( 'Total Amount', 'rajaskin-wp-theme' ); ?></span>
                    <span class="rs-detail-value"><?php echo $order_total; ?></span>
                </div>
                <div class="rs-detail-row">
                    <span class="rs-detail-label"><?php esc_html_e( 'Current Status', 'rajaskin-wp-theme' ); ?></span>
                    <span class="rs-detail-value"><?php echo esc_html( wc_get_order_status_name( $order_status ) ); ?></span>
                </div>
            </div>

            <div class="rs-order-actions-row" style="display:flex; flex-direction:column; gap:10px;">
                <a href="<?php echo esc_url( add_query_arg( 'order_id', $order_id, function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ) ); ?>" class="btn-track-order">
                    <?php esc_html_e( 'Track Order Status', 'rajaskin-wp-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-track-order" style="background: #f4f4f5; color: #111111 !important;">
                    <?php esc_html_e( 'Continue Shopping', 'rajaskin-wp-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    return;
endif;
?>

<div class="container rs-checkout-page-wrap rs-order-pay-page-wrap">

    <!-- Top Step Progress Bar (Step 3: Payment Active) -->
    <div class="checkout-step-tracker rs-pay-step-tracker">
        <div class="step-tracker-item completed" data-step="1">
            <div class="step-circle">✓</div>
            <span class="step-label"><?php esc_html_e( 'Shipping', 'rajaskin-wp-theme' ); ?></span>
        </div>
        <div class="step-line completed" data-line="1"></div>
        <div class="step-tracker-item completed" data-step="2">
            <div class="step-circle">✓</div>
            <span class="step-label"><?php esc_html_e( 'Delivery', 'rajaskin-wp-theme' ); ?></span>
        </div>
        <div class="step-line completed" data-line="2"></div>
        <div class="step-tracker-item active" data-step="3">
            <div class="step-circle">3</div>
            <span class="step-label"><?php esc_html_e( 'Payment', 'rajaskin-wp-theme' ); ?></span>
        </div>
        <div class="step-line" data-line="3"></div>
        <div class="step-tracker-item" data-step="4">
            <div class="step-circle">4</div>
            <span class="step-label"><?php esc_html_e( 'Confirmation', 'rajaskin-wp-theme' ); ?></span>
        </div>
    </div>

    <!-- Main Payment Form -->
    <form id="order_review" method="post" action="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="rs-order-pay-form">

        <div class="checkout-main-grid rs-order-pay-grid">

            <!-- LEFT COLUMN: Order Info & Payment Gateways -->
            <div class="checkout-steps-col rs-order-pay-col-left">

                <!-- Order Header Banner / Notice Card -->
                <div class="rs-pay-order-header-card">
                    <div class="rs-pay-header-top">
                        <div>
                            <div class="rs-pay-order-id-badge">
                                <span><?php esc_html_e( 'Pay For Order', 'rajaskin-wp-theme' ); ?></span>
                                <strong>#RJ-<?php echo esc_html( $order_number ); ?></strong>
                            </div>
                            <p class="rs-pay-order-meta">
                                <?php printf( esc_html__( 'Placed on %s by %s', 'rajaskin-wp-theme' ), esc_html( $order_date ), '<strong>' . esc_html( $customer_name ) . '</strong>' ); ?>
                            </p>
                        </div>
                        <span class="rs-pay-status-badge status-pending">
                            <span class="rs-status-pulse-dot"></span>
                            <?php echo esc_html( wc_get_order_status_name( $order_status ) ); ?>
                        </span>
                    </div>

                    <!-- Destination & Shipping Mini-card -->
                    <div class="rs-pay-dest-summary">
                        <div class="rs-pay-dest-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="rs-pay-dest-info">
                                <span class="rs-pay-dest-label"><?php esc_html_e( 'Delivery Destination', 'rajaskin-wp-theme' ); ?></span>
                                <span class="rs-pay-dest-val"><?php echo esc_html( $order->get_shipping_city() ?: $order->get_billing_city() ?: 'Indonesia' ); ?><?php echo $order->get_shipping_postcode() ? ', ' . esc_html( $order->get_shipping_postcode() ) : ''; ?></span>
                            </div>
                        </div>
                        <div class="rs-pay-dest-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <div class="rs-pay-dest-info">
                                <span class="rs-pay-dest-label"><?php esc_html_e( 'Shipping Method', 'rajaskin-wp-theme' ); ?></span>
                                <span class="rs-pay-dest-val"><?php echo esc_html( $shipping_name ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection Card -->
                <div class="rs-pay-section-block">
                    <h2 class="step-section-heading rs-pay-section-title">
                        <?php esc_html_e( 'Select Payment Method', 'rajaskin-wp-theme' ); ?>
                    </h2>
                    <p class="rs-pay-section-subtitle">
                        <?php esc_html_e( 'Choose your preferred payment method to complete this order securely.', 'rajaskin-wp-theme' ); ?>
                    </p>

                    <div id="payment" class="woocommerce-checkout-payment rs-order-pay-payment-wrapper">
                        <?php if ( $order->needs_payment() ) : ?>
                            <ul class="wc_payment_methods payment_methods methods rs-pay-methods-list">
                                <?php
                                if ( ! empty( $available_gateways ) ) {
                                    $gateway_keys = array_keys( $available_gateways );
                                    $current_gateway = $order->get_payment_method();
                                    if ( empty( $current_gateway ) || ! isset( $available_gateways[ $current_gateway ] ) ) {
                                        $current_gateway = reset( $gateway_keys );
                                    }

                                    foreach ( $available_gateways as $gateway ) {
                                        $is_chosen = ( $gateway->id === $current_gateway );
                                        ?>
                                        <li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> rs-pay-gateway-item <?php echo $is_chosen ? 'is-selected' : ''; ?>">
                                            <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $is_chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

                                            <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="rs-pay-gateway-label">
                                                <span class="rs-gateway-radio-custom"></span>
                                                <div class="rs-gateway-info-box">
                                                    <div class="rs-gateway-title-row">
                                                        <span class="rs-gateway-title"><?php echo $gateway->get_title(); ?></span>
                                                        <?php if ( $gateway->get_icon() ) : ?>
                                                            <span class="rs-gateway-icon"><?php echo $gateway->get_icon(); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </label>

                                            <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
                                                <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> rs-pay-gateway-box" <?php if ( ! $is_chosen ) : ?>style="display:none;"<?php endif; ?>>
                                                    <?php $gateway->payment_fields(); ?>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance.', 'rajaskin-wp-theme' ) . '</li>';
                                }
                                ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Submission Controls -->
                        <div class="form-row rs-pay-actions-wrap">
                            <input type="hidden" name="woocommerce_pay" value="1" />

                            <?php wc_get_template( 'checkout/terms.php' ); ?>

                            <?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

                            <?php
                            $button_text = apply_filters( 'woocommerce_pay_order_button_text', sprintf( __( 'Pay Now &bull; %s', 'rajaskin-wp-theme' ), $order_total ) );
                            ?>
                            <button type="submit" class="button alt btn-step-next rs-btn-pay-now" id="place_order" value="<?php echo esc_attr( $order_button_text ); ?>" data-value="<?php echo esc_attr( $order_button_text ); ?>">
                                <span><?php echo wp_kses_post( $button_text ); ?></span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>

                            <?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

                            <?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>

                            <div class="rs-pay-footer-links">
                                <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="btn-step-back">
                                    &larr; <?php esc_html_e( 'Back to Order History', 'rajaskin-wp-theme' ); ?>
                                </a>
                                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="rs-pay-cancel-link">
                                    <?php esc_html_e( 'Cancel and Return to Shop', 'rajaskin-wp-theme' ); ?>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Trust Badges & Guarantee -->
                <div class="rs-pay-trust-badges">
                    <div class="rs-trust-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <div>
                            <strong><?php esc_html_e( '256-bit SSL Encrypted', 'rajaskin-wp-theme' ); ?></strong>
                            <span><?php esc_html_e( 'Your transaction and personal data are 100% secure.', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                    </div>
                    <div class="rs-trust-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <div>
                            <strong><?php esc_html_e( '100% Original Skincare', 'rajaskin-wp-theme' ); ?></strong>
                            <span><?php esc_html_e( 'Guaranteed authentic formulas with BPOM certification.', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Order Summary -->
            <div class="checkout-summary-col rs-order-pay-col-right">
                <div class="order-summary-card rs-pay-summary-card">
                    <h3 class="summary-card-title"><?php esc_html_e( 'Order Summary', 'rajaskin-wp-theme' ); ?></h3>

                    <!-- Items List -->
                    <div class="summary-items-list rs-pay-items-list">
                        <?php
                        $items = $order->get_items();
                        if ( ! empty( $items ) ) :
                            foreach ( $items as $item_id => $item ) :
                                $product   = $item->get_product();
                                $item_name = $item->get_name();
                                $qty       = $item->get_quantity();
                                $subtotal  = $order->get_formatted_line_subtotal( $item );
                                $thumb     = $product && $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : get_template_directory_uri() . '/assets/images/prod_pink_jar.jpg';
                                ?>
                                <div class="summary-item-row">
                                    <div class="summary-item-thumb">
                                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $item_name ); ?>">
                                    </div>
                                    <div class="summary-item-details">
                                        <span class="summary-item-name" title="<?php echo esc_attr( $item_name ); ?>"><?php echo esc_html( $item_name ); ?></span>
                                        <span class="summary-item-qty"><?php printf( esc_html__( 'Qty: %d', 'rajaskin-wp-theme' ), $qty ); ?></span>
                                        <?php wc_display_item_meta( $item ); ?>
                                    </div>
                                    <span class="summary-item-price"><?php echo $subtotal; ?></span>
                                </div>
                            <?php
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="summary-divider"></div>

                    <!-- Financial Breakdown Rows -->
                    <div class="summary-calc-rows">
                        <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                            <?php if ( 'order_total' === $key ) continue; ?>
                            <div class="summary-calc-row rs-calc-<?php echo esc_attr( $key ); ?>">
                                <span class="calc-label"><?php echo esc_html( $total['label'] ); ?></span>
                                <span class="calc-value"><?php echo wp_kses_post( $total['value'] ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-divider"></div>

                    <!-- Grand Total Row -->
                    <div class="summary-total-row rs-pay-total-row">
                        <div>
                            <span class="total-label-text"><?php esc_html_e( 'Total Amount Due', 'rajaskin-wp-theme' ); ?></span>
                            <span class="rs-pay-tax-note"><?php esc_html_e( 'Includes all taxes & fees', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                        <span class="total-amount-text rs-pay-grand-total"><?php echo $order_total; ?></span>
                    </div>

                </div>
            </div>

        </div>

    </form>

</div>

<script>
(function($) {
    $(function() {
        // Toggle active styling on payment gateway radios
        $(document).on('change', 'input[name="payment_method"]', function() {
            var selectedId = $(this).val();
            $('.rs-pay-gateway-item').removeClass('is-selected');
            $(this).closest('.rs-pay-gateway-item').addClass('is-selected');

            $('.rs-pay-gateway-box').slideUp(200);
            $(this).closest('.rs-pay-gateway-item').find('.rs-pay-gateway-box').slideDown(200);
        });

        // Click on entire gateway row selects the radio
        $(document).on('click', '.rs-pay-gateway-item', function(e) {
            if ($(e.target).is('input, a, button, label, select, textarea')) return;
            var $radio = $(this).find('input[type="radio"]');
            if ($radio.length && !$radio.is(':checked')) {
                $radio.prop('checked', true).trigger('change');
            }
        });
    });
})(jQuery);
</script>
