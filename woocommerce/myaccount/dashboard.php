<?php
/**
 * My Account Dashboard
 *
 * Overrides WooCommerce default dashboard.php template
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$display_name = $current_user->display_name ?: $current_user->user_login;

// Fetch customer total orders
$customer_orders = wc_get_orders( array(
    'customer' => $current_user->ID,
    'limit'    => -1,
    'return'   => 'ids',
) );
$order_count = count( $customer_orders );

// Fetch latest order if exists
$latest_order = null;
if ( $order_count > 0 ) {
    $latest_order = wc_get_order( $customer_orders[0] );
}

// Shipping address summary
$shipping_city    = get_user_meta( $current_user->ID, 'shipping_city', true ) ?: get_user_meta( $current_user->ID, 'billing_city', true );
$shipping_country = get_user_meta( $current_user->ID, 'shipping_country', true ) ?: get_user_meta( $current_user->ID, 'billing_country', true );
$address_summary  = ( ! empty( $shipping_city ) ) ? $shipping_city . ( $shipping_country ? ', ' . $shipping_country : '' ) : __( 'No address saved yet', 'rajaskin-wp-theme' );

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );
?>

<div class="rs-dashboard-wrap">

    <!-- Personalized Welcome Banner -->
    <div class="rs-dashboard-welcome-banner">
        <div class="rs-welcome-text">
            <span class="rs-welcome-badge"><?php esc_html_e( 'RajaSkin Member', 'rajaskin-wp-theme' ); ?></span>
            <h2 class="rs-welcome-heading">
                <?php printf( esc_html__( 'Hello, %s!', 'rajaskin-wp-theme' ), '<span>' . esc_html( $display_name ) . '</span>' ); ?>
            </h2>
            <p class="rs-welcome-desc">
                <?php esc_html_e( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.', 'rajaskin-wp-theme' ); ?>
            </p>
        </div>
    </div>

    <!-- 3 Quick Stat Summary Cards -->
    <div class="rs-dashboard-stats-grid">
        
        <!-- Card 1: Orders -->
        <div class="rs-stat-card">
            <div class="rs-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
            <div class="rs-stat-info">
                <span class="rs-stat-number"><?php echo esc_html( $order_count ); ?></span>
                <h4 class="rs-stat-title"><?php esc_html_e( 'Total Orders', 'rajaskin-wp-theme' ); ?></h4>
            </div>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="rs-stat-link">
                <span><?php esc_html_e( 'View Orders', 'rajaskin-wp-theme' ); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>

        <!-- Card 2: Live Tracking -->
        <div class="rs-stat-card">
            <div class="rs-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="rs-stat-info">
                <span class="rs-stat-number"><?php echo $latest_order ? ( '#' . esc_html( $latest_order->get_order_number() ) ) : __( 'None', 'rajaskin-wp-theme' ); ?></span>
                <h4 class="rs-stat-title"><?php esc_html_e( 'Latest Order Tracking', 'rajaskin-wp-theme' ); ?></h4>
            </div>
            <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="rs-stat-link">
                <span><?php esc_html_e( 'Track Status', 'rajaskin-wp-theme' ); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>

        <!-- Card 3: Saved Address -->
        <div class="rs-stat-card">
            <div class="rs-stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <div class="rs-stat-info">
                <span class="rs-stat-text-val"><?php echo esc_html( $address_summary ); ?></span>
                <h4 class="rs-stat-title"><?php esc_html_e( 'Primary Address', 'rajaskin-wp-theme' ); ?></h4>
            </div>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="rs-stat-link">
                <span><?php esc_html_e( 'Manage Address', 'rajaskin-wp-theme' ); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>

    </div>

    <!-- Quick Action Shortcuts -->
    <div class="rs-dashboard-quick-actions">
        <h3 class="rs-section-title"><?php esc_html_e( 'Quick Actions', 'rajaskin-wp-theme' ); ?></h3>
        <div class="rs-actions-button-group">
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="rs-btn-action">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span><?php esc_html_e( 'Edit Profile & Password', 'rajaskin-wp-theme' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="rs-btn-action secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span><?php esc_html_e( 'Shop New Skincare Drops', 'rajaskin-wp-theme' ); ?></span>
            </a>
            <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="rs-btn-action logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span><?php esc_html_e( 'Log Out', 'rajaskin-wp-theme' ); ?></span>
            </a>
        </div>
    </div>

</div>
