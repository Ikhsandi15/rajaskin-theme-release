<?php
/**
 * Thankyou page - RajaSkin Order Confirmed
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order rs-thankyou-page-container">

    <!-- Top Step Progress Bar (All 4 Steps Completed) -->
    <div class="checkout-step-tracker rs-thankyou-step-tracker">
        <div class="step-tracker-item completed" data-step="1">
            <div class="step-circle">1</div>
            <span class="step-label">Shipping</span>
        </div>
        <div class="step-line completed" data-line="1"></div>
        <div class="step-tracker-item completed" data-step="2">
            <div class="step-circle">2</div>
            <span class="step-label">Delivery</span>
        </div>
        <div class="step-line completed" data-line="2"></div>
        <div class="step-tracker-item completed" data-step="3">
            <div class="step-circle">3</div>
            <span class="step-label">Payment</span>
        </div>
        <div class="step-line completed" data-line="3"></div>
        <div class="step-tracker-item completed" data-step="4">
            <div class="step-circle">4</div>
            <span class="step-label">Review</span>
        </div>
    </div>

    <?php if ( $order ) : ?>

        <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>

            <div class="rs-order-confirmed-card rs-order-failed-card">
                <div class="rs-confirmed-icon rs-icon-failed">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>

                <h1 class="rs-confirmed-title"><?php esc_html_e( 'Order Failed', 'woocommerce' ); ?></h1>
                <p class="rs-confirmed-desc"><?php esc_html_e( 'Unfortunately your order cannot be processed as the transaction was declined. Please attempt your purchase again.', 'woocommerce' ); ?></p>

                <div class="rs-order-actions-row">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn-track-order"><?php esc_html_e( 'Pay Now', 'woocommerce' ); ?></a>
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-track-order" style="background:#f4f4f5; color:#111 !important; margin-top:10px;"><?php esc_html_e( 'My Account', 'woocommerce' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php else : ?>

            <?php
            // Order details extraction
            $order_number   = $order->get_order_number();
            $order_date_raw = $order->get_date_created();
            $order_date     = $order_date_raw ? wc_format_datetime( $order_date_raw, 'j F Y' ) : date( 'j F Y' );
            $order_total    = $order->get_formatted_order_total();
            $billing_email  = $order->get_billing_email() ?: 'your email';
            $payment_method = $order->get_payment_method_title() ?: 'Bank Transfer';

            // Extract Shipping/Courier or default Est. Delivery
            $shipping_methods = $order->get_shipping_methods();
            $est_delivery     = '2-3 business days';
            if ( ! empty( $shipping_methods ) ) {
                $shipping_item = reset( $shipping_methods );
                $method_name   = $shipping_item->get_name();
                if ( ! empty( $method_name ) ) {
                    $est_delivery = $method_name;
                }
            }

            // Track Order URL -> RajaSkin Order History Page
            $track_url = function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' );
            ?>

            <div class="rs-order-confirmed-card">
                <!-- Checkmark Icon in Light Gray Circle -->
                <div class="rs-confirmed-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>

                <!-- Title & Description -->
                <h1 class="rs-confirmed-title">Order Confirmed!</h1>
                <p class="rs-confirmed-desc">
                    Your order <strong>#<?php echo esc_html( $order_number ); ?></strong> has been placed successfully. A confirmation email has been sent to <strong><?php echo esc_html( $billing_email ); ?></strong>
                </p>

                <!-- Order Details List -->
                <div class="rs-confirmed-details">
                    <div class="rs-detail-row">
                        <span class="rs-detail-label">Order Number</span>
                        <span class="rs-detail-value">#<?php echo esc_html( $order_number ); ?></span>
                    </div>
                    <div class="rs-detail-row">
                        <span class="rs-detail-label">Order Date</span>
                        <span class="rs-detail-value"><?php echo esc_html( $order_date ); ?></span>
                    </div>
                    <div class="rs-detail-row">
                        <span class="rs-detail-label">Total Amount</span>
                        <span class="rs-detail-value"><?php echo $order_total; ?></span>
                    </div>
                    <div class="rs-detail-row">
                        <span class="rs-detail-label">Payment Method</span>
                        <span class="rs-detail-value"><?php echo esc_html( $payment_method ); ?></span>
                    </div>
                    <div class="rs-detail-row">
                        <span class="rs-detail-label">Est. Delivery</span>
                        <span class="rs-detail-value"><?php echo esc_html( $est_delivery ); ?></span>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="<?php echo esc_url( $track_url ); ?>" class="btn-track-order">Track Order</a>
            </div>

            <!-- Client-Side Order Persistence for Guests -->
            <script>
            (function() {
                try {
                    var orderData = {
                        id: <?php echo json_encode( $order->get_id() ); ?>,
                        order_number: <?php echo json_encode( $order_number ); ?>,
                        order_key: <?php echo json_encode( $order->get_order_key() ); ?>,
                        date: <?php echo json_encode( $order_date ); ?>,
                        total: <?php echo json_encode( $order_total ); ?>,
                        status: <?php echo json_encode( $order->get_status() ); ?>,
                        est_delivery: <?php echo json_encode( $est_delivery ); ?>
                    };

                    var currentList = JSON.parse(localStorage.getItem('rajaskin_orders') || '[]');
                    var idx = currentList.findIndex(function(o) { return o.id === orderData.id; });
                    if (idx !== -1) {
                        currentList[idx] = orderData;
                    } else {
                        currentList.unshift(orderData);
                    }
                    localStorage.setItem('rajaskin_orders', JSON.stringify(currentList));

                    var cookieData = currentList.map(function(o) { return { id: o.id, key: o.order_key }; });
                    document.cookie = "rajaskin_guest_orders=" + encodeURIComponent(JSON.stringify(cookieData)) + "; path=/; max-age=" + (365*24*60*60);
                } catch (e) {
                    console.warn('LocalStorage error:', e);
                }
            })();
            </script>

        <?php endif; ?>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

    <?php else : ?>

        <div class="rs-order-confirmed-card">
            <div class="rs-confirmed-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="rs-confirmed-title">Order Confirmed!</h1>
            <p class="rs-confirmed-desc">
                <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?>
            </p>
            <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="btn-track-order">Track Order</a>
        </div>

    <?php endif; ?>

</div>
