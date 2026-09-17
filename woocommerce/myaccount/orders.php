<?php
/**
 * Orders template
 *
 * Overrides WooCommerce default orders.php template
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="rs-myaccount-orders-wrap">
    <div class="rs-orders-header-row">
        <div>
            <h2 class="rs-orders-heading"><?php esc_html_e( 'My Orders', 'rajaskin-wp-theme' ); ?></h2>
            <p class="rs-orders-subtext"><?php esc_html_e( 'Review your past purchases, download invoices, or track real-time delivery status.', 'rajaskin-wp-theme' ); ?></p>
        </div>
        <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="rs-btn-live-track">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span><?php esc_html_e( 'Open Live Tracking Page', 'rajaskin-wp-theme' ); ?></span>
        </a>
    </div>

    <?php if ( $has_orders ) : ?>

        <div class="rs-orders-table-container">
            <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table rs-luxury-orders-table">
                <thead>
                    <tr>
                        <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ( $customer_orders->orders as $customer_order ) {
                        $order      = wc_get_order( $customer_order );
                        $item_count = $order->get_item_count();
                        $status     = $order->get_status();
                        $order_id   = $order->get_id();
                        $order_num  = $order->get_order_number();

                        // Pill color class
                        $pill_class = 'status-processing';
                        if ( $status === 'completed' ) {
                            $pill_class = 'status-completed';
                        } elseif ( $status === 'cancelled' || $status === 'failed' ) {
                            $pill_class = 'status-cancelled';
                        } elseif ( $status === 'on-hold' || $status === 'pending' ) {
                            $pill_class = 'status-pending';
                        }
                        ?>
                        <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $status ); ?> order">
                            <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                                <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                                    <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                        <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                                    <?php elseif ( 'order-number' === $column_id ) : ?>
                                        <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="rs-order-num-link">
                                            <strong>#RJ-<?php echo esc_html( $order_num ); ?></strong>
                                        </a>

                                    <?php elseif ( 'order-date' === $column_id ) : ?>
                                        <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
                                            <?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'M d, Y' ) ); ?>
                                        </time>

                                    <?php elseif ( 'order-status' === $column_id ) : ?>
                                        <span class="rs-status-pill <?php echo esc_attr( $pill_class ); ?>">
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                        </span>

                                    <?php elseif ( 'order-total' === $column_id ) : ?>
                                        <div class="rs-order-total-cell">
                                            <span class="rs-order-price-val"><?php echo $order->get_formatted_order_total(); ?></span>
                                            <span class="rs-order-items-qty"><?php echo sprintf( _n( '%s item', '%s items', $item_count, 'rajaskin-wp-theme' ), $item_count ); ?></span>
                                        </div>

                                    <?php elseif ( 'order-actions' === $column_id ) : ?>
                                        <div class="rs-order-actions-wrap">
                                            <!-- Live Tracking Direct Button -->
                                            <a href="<?php echo esc_url( add_query_arg( 'order_id', $order_id, function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ) ); ?>" class="rs-btn-track-mini" title="<?php esc_attr_e( 'Track Delivery', 'rajaskin-wp-theme' ); ?>">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <span><?php esc_html_e( 'Track', 'rajaskin-wp-theme' ); ?></span>
                                            </a>

                                            <?php
                                            $actions = wc_get_account_orders_actions( $order );
                                            if ( ! empty( $actions ) ) {
                                                foreach ( $actions as $key => $action ) {
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . ' rs-btn-details-mini">' . esc_html( $action['name'] ) . '</a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

        <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
            <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination rs-orders-pagination">
                <?php if ( 1 !== $current_page ) : ?>
                    <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'rajaskin-wp-theme' ); ?></a>
                <?php endif; ?>

                <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                    <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'rajaskin-wp-theme' ); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>

        <!-- Empty State -->
        <div class="rs-empty-orders-card">
            <div class="rs-empty-icon-wrap">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <h3 class="rs-empty-title"><?php esc_html_e( 'No orders placed yet', 'rajaskin-wp-theme' ); ?></h3>
            <p class="rs-empty-desc"><?php esc_html_e( 'Explore our award-winning clinical skincare collection and begin your journey to healthy, glowing skin.', 'rajaskin-wp-theme' ); ?></p>
            <div class="rs-empty-action-wrap">
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="rs-btn-explore">
                    <span><?php esc_html_e( 'Discover Products', 'rajaskin-wp-theme' ); ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <!-- Trust Perks -->
            <div class="rs-empty-orders-perks">
                <div class="rs-order-perk">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    <span><?php esc_html_e( 'Free delivery over Rp500k', 'rajaskin-wp-theme' ); ?></span>
                </div>
                <div class="rs-order-perk">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span><?php esc_html_e( '100% BPOM Certified', 'rajaskin-wp-theme' ); ?></span>
                </div>
                <div class="rs-order-perk">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span><?php esc_html_e( 'Live courier tracking', 'rajaskin-wp-theme' ); ?></span>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
