<?php
/**
 * View Order
 *
 * Overrides WooCommerce default view-order.php template
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $order ) || ! $order || ! is_a( $order, 'WC_Order' ) ) :
?>
    <div class="rs-view-order-empty-wrap">
        <div class="rs-view-order-empty-card">
            <div class="rs-view-empty-icon">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h2 class="rs-view-empty-title"><?php esc_html_e( 'Order Not Found', 'rajaskin-wp-theme' ); ?></h2>
            <p class="rs-view-empty-desc"><?php esc_html_e( 'The order details you requested could not be found or you do not have permission to view them. Please check your order history or return to the shop.', 'rajaskin-wp-theme' ); ?></p>
            <div class="rs-view-empty-actions">
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="rs-btn-back-primary">
                    &larr; <?php esc_html_e( 'Back to My Orders', 'rajaskin-wp-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="rs-btn-explore-secondary">
                    <?php esc_html_e( 'Discover Products', 'rajaskin-wp-theme' ); ?>
                </a>
            </div>
        </div>
    </div>
<?php
    return;
endif;

$order_id   = $order->get_id();
$order_num  = $order->get_order_number();
$status     = $order->get_status();
$created    = $order->get_date_created() ? wc_format_datetime( $order->get_date_created(), 'M d, Y' ) : '';

$pill_class = 'status-processing';
if ( $status === 'completed' ) {
    $pill_class = 'status-completed';
} elseif ( $status === 'cancelled' || $status === 'failed' ) {
    $pill_class = 'status-cancelled';
} elseif ( $status === 'on-hold' || $status === 'pending' ) {
    $pill_class = 'status-pending';
}
?>

<div class="rs-view-order-wrap">

    <!-- Header bar with status pill & back button -->
    <div class="rs-view-order-header">
        <div>
            <div class="rs-order-title-row">
                <h2 class="rs-view-order-title">Order #RJ-<?php echo esc_html( $order_num ); ?></h2>
                <span class="rs-status-pill <?php echo esc_attr( $pill_class ); ?>">
                    <?php echo esc_html( wc_get_order_status_name( $status ) ); ?>
                </span>
            </div>
            <p class="rs-view-order-meta">
                Placed on <strong><?php echo esc_html( $created ); ?></strong>
            </p>
        </div>

        <div class="rs-view-order-actions">
            <?php if ( $order->needs_payment() ) : ?>
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="rs-btn-pay-direct" style="background:#111111; color:#ffffff; padding:8px 16px; border-radius:6px; font-weight:600; font-size:0.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    <span><?php esc_html_e( 'Pay Now', 'rajaskin-wp-theme' ); ?></span>
                </a>
            <?php endif; ?>
            <!-- Link to Order History Tracking page -->
            <a href="<?php echo esc_url( add_query_arg( 'order_id', $order_id, function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ) ); ?>" class="rs-btn-view-track">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Live Tracking View</span>
            </a>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="rs-btn-back-orders">
                &larr; Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Items Section -->
    <div class="rs-order-detail-card">
        <h3 class="rs-card-subheading">Items in this Order</h3>

        <div class="rs-order-items-list">
            <?php foreach ( $order->get_items() as $item_id => $item ) :
                $product   = $item->get_product();
                $item_name = $item->get_name();
                $qty       = $item->get_quantity();
                $subtotal  = $order->get_formatted_line_subtotal( $item );
                $thumb     = $product && $product->get_image_id() ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : get_template_directory_uri() . '/assets/images/prod_pink_jar.jpg';
                $permalink = $product ? $product->get_permalink() : '#';
                ?>
                <div class="rs-view-item-row">
                    <div class="rs-view-item-left">
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $item_name ); ?>" class="rs-view-item-thumb">
                        <div>
                            <a href="<?php echo esc_url( $permalink ); ?>" class="rs-view-item-name"><?php echo esc_html( $item_name ); ?></a>
                            <p class="rs-view-item-qty">Quantity: <?php echo esc_html( $qty ); ?></p>
                            <?php wc_display_item_meta( $item ); ?>
                        </div>
                    </div>
                    <div class="rs-view-item-right">
                        <div class="rs-view-item-price"><?php echo $subtotal; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Totals breakdown -->
        <div class="rs-order-totals-table-wrap">
            <table class="rs-order-totals-table">
                <tbody>
                    <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                        <tr class="<?php echo esc_attr( $key ); ?>">
                            <th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
                            <td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Addresses Side-by-side -->
    <div class="rs-addresses-grid">
        <div class="rs-address-box">
            <h4 class="rs-address-box-title">Billing Address</h4>
            <div class="rs-address-content">
                <?php echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'rajaskin-wp-theme' ) ) ); ?>
                <?php if ( $order->get_billing_phone() ) : ?>
                    <p class="rs-address-phone"><strong>Phone:</strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
                <?php endif; ?>
                <?php if ( $order->get_billing_email() ) : ?>
                    <p class="rs-address-email"><strong>Email:</strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="rs-address-box">
            <h4 class="rs-address-box-title">Shipping Address</h4>
            <div class="rs-address-content">
                <?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'rajaskin-wp-theme' ) ) ); ?>
            </div>
        </div>
    </div>

</div>
