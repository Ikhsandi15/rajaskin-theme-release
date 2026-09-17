<?php
/**
 * Template Name: Order History
 * Description: RajaSkin Order History Page (Persistent for guests & logged in users with full interactive order tracking switcher)
 */

get_header();

// 1. Check if a specific order ID was requested in the URL
$requested_order_id = ! empty( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : ( ! empty( $_GET['order'] ) ? absint( $_GET['order'] ) : ( ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : 0 ) );

// 2. Fetch Orders from Database (Logged in user or Guest Cookie)
$orders = array();

if ( is_user_logged_in() ) {
    $orders = wc_get_orders( array(
        'customer' => get_current_user_id(),
        'limit'    => 20,
        'orderby'  => 'date',
        'order'    => 'DESC',
    ) );
}

// If no user orders or logged out, check Guest Cookie
if ( ! empty( $_COOKIE['rajaskin_guest_orders'] ) ) {
    $guest_entries = json_decode( stripslashes( $_COOKIE['rajaskin_guest_orders'] ), true );
    if ( is_array( $guest_entries ) ) {
        foreach ( $guest_entries as $entry ) {
            $order_id = is_array( $entry ) ? ( $entry['id'] ?? 0 ) : $entry;
            if ( $order_id ) {
                $order_obj = wc_get_order( $order_id );
                if ( $order_obj ) {
                    // Avoid duplicate order IDs
                    $already_in = false;
                    foreach ( $orders as $existing_order ) {
                        if ( $existing_order->get_id() === $order_obj->get_id() ) {
                            $already_in = true;
                            break;
                        }
                    }
                    if ( ! $already_in ) {
                        $orders[] = $order_obj;
                    }
                }
            }
        }
    }
}

// If specific order was requested directly but not in list yet, attempt loading it
$order_not_found = false;
if ( $requested_order_id ) {
    $has_req = false;
    foreach ( $orders as $o ) {
        if ( $o->get_id() === $requested_order_id ) {
            $has_req = true;
            break;
        }
    }
    if ( ! $has_req ) {
        $direct_order = wc_get_order( $requested_order_id );
        if ( $direct_order ) {
            array_unshift( $orders, $direct_order );
            // If valid guest order, save into guest cookie as well
            if ( ! is_user_logged_in() && function_exists( 'rajaskin_save_guest_order_cookie' ) ) {
                rajaskin_save_guest_order_cookie( $requested_order_id );
            }
        } else {
            $order_not_found = true;
        }
    }
}

// 3. Determine Active Tracking Order & Previous Orders
$active_order = null;
$previous_orders = array();

if ( ! empty( $orders ) ) {
    if ( $requested_order_id && ! $order_not_found ) {
        foreach ( $orders as $o ) {
            if ( $o->get_id() === $requested_order_id ) {
                $active_order = $o;
                break;
            }
        }
    }

    if ( ! $active_order && ! empty( $orders ) ) {
        $active_order = $orders[0];
    }

    if ( $active_order ) {
        foreach ( $orders as $o ) {
            if ( $o->get_id() !== $active_order->get_id() ) {
                $previous_orders[] = $o;
            }
        }
    }
}

$default_img = get_template_directory_uri() . '/assets/images/prod_pink_jar.jpg';
$page_url = get_permalink();
?>

<div class="rs-order-history-wrap">

    <!-- Breadcrumb -->
    <div class="rs-oh-breadcrumb">
        <a href="<?php echo esc_url( function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/') ); ?>">Catalogue</a>
        <span class="sep">/</span>
        <span class="current">Order History</span>
    </div>

    <!-- Title & Subtitle -->
    <h1 class="rs-oh-title"><?php echo esc_html( get_theme_mod( 'rajaskin_oh_title', 'Order History' ) ); ?></h1>
    <p class="rs-oh-subtitle"><?php echo esc_html( get_theme_mod( 'rajaskin_oh_subtitle', 'Manage, track, and review your skincare orders in one place.' ) ); ?></p>

    <!-- Alert if queried order was not found -->
    <?php if ( $order_not_found && $requested_order_id ) : ?>
        <div class="rs-order-not-found-alert">
            <div class="rs-alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="rs-alert-content">
                <strong><?php esc_html_e( 'Order Not Found', 'rajaskin-wp-theme' ); ?></strong>
                <p><?php echo sprintf( esc_html__( 'We could not find order #RJ-%s. Please check your order ID or browse your orders below.', 'rajaskin-wp-theme' ), esc_html( $requested_order_id ) ); ?></p>
            </div>
            <a href="<?php echo esc_url( $page_url ); ?>" class="rs-alert-close" aria-label="<?php esc_attr_e( 'Dismiss', 'rajaskin-wp-theme' ); ?>">&times;</a>
        </div>
    <?php endif; ?>

    <!-- CONTAINER FOR DYNAMIC ORDER DATA -->
    <div id="rsOrderHistoryContent">

        <?php if ( $active_order ) : ?>
            <?php
            $active_id        = $active_order->get_id();
            $active_num       = $active_order->get_order_number();
            $active_date      = $active_order->get_date_created() ? wc_format_datetime( $active_order->get_date_created(), 'M d, Y' ) : 'Jan 22, 2026';
            $active_status    = $active_order->get_status();
            $active_total     = $active_order->get_formatted_order_total();
            
            // Estimated delivery (placed + 3 days)
            $est_time = $active_order->get_date_created() ? ( $active_order->get_date_created()->getTimestamp() + ( 3 * 86400 ) ) : ( time() + ( 3 * 86400 ) );
            $est_date = date( 'M d, Y', $est_time );

            // Get first product & all items
            $items = $active_order->get_items();
            $first_item = ! empty( $items ) ? reset( $items ) : null;
            $item_name = $first_item ? $first_item->get_name() : 'Skincare Product';
            $item_qty  = $first_item ? $first_item->get_quantity() : 1;
            $product_obj = $first_item ? $first_item->get_product() : null;
            $thumb_url = $product_obj && $product_obj->get_image_id() ? wp_get_attachment_image_url( $product_obj->get_image_id(), 'thumbnail' ) : $default_img;

            // Status Stepper Calculation
            $step1_class = 'completed';
            $line1_class = 'completed';
            $step2_class = 'completed';
            $line2_class = 'completed';
            $step3_class = 'active';
            $line3_class = 'pending';
            $step4_class = 'pending';
            $pill_label  = 'In Transit';
            $pill_class  = 'in-transit';

            if ( $active_status === 'pending' || $active_status === 'on-hold' ) {
                $step2_class = 'pending';
                $line1_class = '';
                $step3_class = 'pending';
                $line2_class = '';
                $pill_label  = 'Processing';
                $pill_class  = 'status-processing';
            } elseif ( $active_status === 'processing' ) {
                $step2_class = 'active';
                $line1_class = 'completed';
                $step3_class = 'pending';
                $line2_class = '';
                $pill_label  = 'Processing';
                $pill_class  = 'status-processing';
            } elseif ( $active_status === 'completed' ) {
                $step1_class = 'completed';
                $line1_class = 'completed';
                $step2_class = 'completed';
                $line2_class = 'completed';
                $step3_class = 'completed';
                $line3_class = 'completed';
                $step4_class = 'completed';
                $pill_label  = 'Delivered';
                $pill_class  = 'status-completed';
            } elseif ( $active_status === 'cancelled' || $active_status === 'failed' ) {
                $step1_class = 'pending';
                $step2_class = 'pending';
                $step3_class = 'pending';
                $step4_class = 'pending';
                $pill_label  = ucfirst( $active_status );
                $pill_class  = 'status-cancelled';
            }
            ?>

            <!-- Active Tracking Order Card -->
            <div class="rs-active-order-card" id="activeOrderCard">
                <div class="active-order-header">
                    <div>
                        <div class="active-order-title" id="activeOrderTitle">Order #RJ-<?php echo esc_html( $active_num ); ?></div>
                        <p class="active-order-meta" id="activeOrderMeta">Placed on <?php echo esc_html( $active_date ); ?> • <?php echo ( $active_status === 'completed' ) ? 'Delivered' : ( 'Estimated Delivery: ' . esc_html( $est_date ) ); ?></p>
                    </div>
                    <span class="rs-status-pill <?php echo esc_attr( $pill_class ); ?>" id="activeOrderPill"><?php echo esc_html( $pill_label ); ?></span>
                </div>

                <!-- 4-Step Tracking Stepper -->
                <div class="rs-tracking-stepper">
                    <!-- Step 1: Processing -->
                    <div class="stepper-step <?php echo esc_attr( $step1_class ); ?>" id="step1">
                        <span class="step-icon-wrap">✓</span>
                        <span class="step-label-text">Processing</span>
                    </div>
                    <div class="stepper-line <?php echo esc_attr( $line1_class ); ?>" id="line1"></div>

                    <!-- Step 2: Shipped -->
                    <div class="stepper-step <?php echo esc_attr( $step2_class ); ?>" id="step2">
                        <span class="step-icon-wrap">✓</span>
                        <span class="step-label-text">Shipped</span>
                    </div>
                    <div class="stepper-line <?php echo esc_attr( $line2_class ); ?>" id="line2"></div>

                    <!-- Step 3: Out for Delivery -->
                    <div class="stepper-step <?php echo esc_attr( $step3_class ); ?>" id="step3">
                        <span class="step-icon-wrap">●</span>
                        <span class="step-label-text">Out for Delivery</span>
                    </div>
                    <div class="stepper-line <?php echo esc_attr( $line3_class ); ?>" id="line3"></div>

                    <!-- Step 4: Delivered -->
                    <div class="stepper-step <?php echo esc_attr( $step4_class ); ?>" id="step4">
                        <span class="step-icon-wrap">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        </span>
                        <span class="step-label-text">Delivered</span>
                    </div>
                </div>

                <!-- Product Item Details -->
                <div class="rs-order-item-row" id="activeOrderItemRow">
                    <div class="order-item-left">
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $item_name ); ?>" class="order-item-thumb" id="activeItemThumb">
                        <div>
                            <div class="order-item-name" id="activeItemName"><?php echo esc_html( $item_name ); ?></div>
                            <p class="order-item-qty" id="activeItemQty">Quantity: <?php echo esc_html( $item_qty ); ?></p>
                        </div>
                    </div>
                    <div class="order-item-right">
                        <div class="order-item-price" id="activeItemPrice"><?php echo $active_total; ?></div>
                        <?php if ( $active_order && $active_order->needs_payment() ) : ?>
                            <a href="<?php echo esc_url( $active_order->get_checkout_payment_url() ); ?>" class="btn-step-next" style="display:inline-block; padding:6px 14px; font-size:0.75rem; margin-top:6px; text-decoration:none; color:#ffffff !important;">Pay Now &rarr;</a>
                        <?php else : ?>
                            <p class="order-item-shipping">Free Shipping</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Previous Orders Section -->
            <?php if ( ! empty( $previous_orders ) ) : ?>
                <div class="rs-previous-orders-section">
                    <h3 class="rs-prev-orders-title">Previous Orders</h3>
                    <p class="rs-prev-orders-instruction" style="font-size: 0.82rem; color: #888; margin-top: -8px; margin-bottom: 14px;">Click any previous order to view its live tracking status.</p>
                    <div class="rs-prev-orders-list" id="previousOrdersList">
                        <?php foreach ( $previous_orders as $p_order ) :
                            $p_id    = $p_order->get_id();
                            $p_num   = $p_order->get_order_number();
                            $p_date  = $p_order->get_date_created() ? wc_format_datetime( $p_order->get_date_created(), 'M d, Y' ) : '';
                            $p_items = $p_order->get_items();
                            $p_count = count( $p_items );
                            $p_first = ! empty( $p_items ) ? reset( $p_items ) : null;
                            $p_prod  = $p_first ? $p_first->get_product() : null;
                            $p_thumb = $p_prod && $p_prod->get_image_id() ? wp_get_attachment_image_url( $p_prod->get_image_id(), 'thumbnail' ) : $default_img;
                            $p_total = $p_order->get_formatted_order_total();
                            $p_link  = esc_url( add_query_arg( 'order_id', $p_id, $page_url ) );
                            ?>
                            <div class="rs-prev-order-card js-prev-order-item" data-order-id="<?php echo esc_attr( $p_id ); ?>" data-order-url="<?php echo esc_url( $p_link ); ?>" role="button" tabindex="0" title="Click to view tracking for Order #RJ-<?php echo esc_attr( $p_num ); ?>">
                                <div class="prev-order-left">
                                    <img src="<?php echo esc_url( $p_thumb ); ?>" alt="Order #<?php echo esc_attr( $p_num ); ?>" class="prev-order-thumb">
                                    <div>
                                        <div class="prev-order-title">Order #RJ-<?php echo esc_html( $p_num ); ?></div>
                                        <p class="prev-order-meta"><?php echo ( $p_order->get_status() === 'completed' ? 'Delivered on ' : 'Placed on ' ) . esc_html( $p_date ); ?> • <?php echo esc_html( $p_count ); ?> <?php echo $p_count > 1 ? 'items' : 'item'; ?></p>
                                    </div>
                                </div>
                                <div class="prev-order-right">
                                    <div class="prev-order-price"><?php echo $p_total; ?></div>
                                    <button type="button" class="btn-reorder" data-order-id="<?php echo esc_attr( $p_id ); ?>" onclick="event.stopPropagation();">Reorder</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else : ?>

            <!-- Luxury Empty Order History State -->
            <div class="rs-empty-oh-card">
                <div class="rs-empty-oh-icon-wrap">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>

                <h2 class="rs-empty-oh-title"><?php esc_html_e( 'No Order History Found', 'rajaskin-wp-theme' ); ?></h2>
                <p class="rs-empty-oh-desc"><?php esc_html_e( 'You haven\'t placed any skincare orders yet. When you make a purchase, you can monitor real-time shipping updates, view package status, and quickly reorder your routine essentials here.', 'rajaskin-wp-theme' ); ?></p>

                <div class="rs-empty-oh-actions">
                    <a href="<?php echo esc_url( function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/') ); ?>" class="rs-btn-explore-catalogue">
                        <span><?php esc_html_e( 'Discover Our Collection', 'rajaskin-wp-theme' ); ?></span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>

                <!-- Guest Quick Order Lookup Box -->
                <div class="rs-guest-lookup-box">
                    <div class="rs-guest-lookup-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span><?php esc_html_e( 'Already placed an order as a guest?', 'rajaskin-wp-theme' ); ?></span>
                    </div>
                    <form method="get" action="<?php echo esc_url( $page_url ); ?>" class="rs-guest-lookup-form">
                        <div class="rs-lookup-inputs">
                            <input type="text" name="order_id" placeholder="<?php esc_attr_e( 'Enter Order ID (e.g. 1234 or RJ-1234)', 'rajaskin-wp-theme' ); ?>" required class="rs-lookup-input">
                            <button type="submit" class="rs-lookup-btn"><?php esc_html_e( 'Track Order', 'rajaskin-wp-theme' ); ?></button>
                        </div>
                    </form>
                </div>

                <!-- Trust Badges -->
                <div class="rs-empty-oh-badges">
                    <div class="rs-oh-badge-item">
                        <div class="rs-oh-badge-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="rs-oh-badge-text">
                            <strong><?php esc_html_e( 'Live Order Tracking', 'rajaskin-wp-theme' ); ?></strong>
                            <span><?php esc_html_e( 'Real-time courier updates', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                    </div>
                    <div class="rs-oh-badge-item">
                        <div class="rs-oh-badge-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <div class="rs-oh-badge-text">
                            <strong><?php esc_html_e( 'Order Invoicing', 'rajaskin-wp-theme' ); ?></strong>
                            <span><?php esc_html_e( 'Official digital receipts', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                    </div>
                    <div class="rs-oh-badge-item">
                        <div class="rs-oh-badge-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>
                        </div>
                        <div class="rs-oh-badge-text">
                            <strong><?php esc_html_e( '1-Click Reorder', 'rajaskin-wp-theme' ); ?></strong>
                            <span><?php esc_html_e( 'Restock routine effortlessly', 'rajaskin-wp-theme' ); ?></span>
                        </div>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

<!-- Interactive Order Tracking Click & Sync Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Sync guest orders from LocalStorage if available
    try {
        var localOrders = JSON.parse(localStorage.getItem('rajaskin_orders') || '[]');
        if (localOrders.length > 0) {
            var cookieData = localOrders.map(function(o) { return { id: o.id, key: o.order_key }; });
            document.cookie = "rajaskin_guest_orders=" + encodeURIComponent(JSON.stringify(cookieData)) + "; path=/; max-age=" + (365*24*60*60);
        }
    } catch (e) {
        console.warn('Order sync error:', e);
    }

    // 2. Click on Previous Order Item to view tracking details
    document.querySelectorAll('.js-prev-order-item, .rs-prev-order-card').forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.btn-reorder')) {
                return; // Let reorder button handle its own event
            }

            var orderUrl = this.getAttribute('data-order-url');
            if (orderUrl) {
                window.location.href = orderUrl;
                return;
            }

            var orderId = this.getAttribute('data-order-id');
            if (orderId && !orderId.toString().startsWith('demo-')) {
                window.location.href = '<?php echo esc_url( $page_url ); ?>?order_id=' + orderId;
                return;
            }

            // Client-side switcher for demo items
            if (this.hasAttribute('data-demo-title')) {
                var title = this.getAttribute('data-demo-title');
                var meta  = this.getAttribute('data-demo-meta');
                var pill  = this.getAttribute('data-demo-pill');
                var item  = this.getAttribute('data-demo-item');
                var qty   = this.getAttribute('data-demo-qty');
                var price = this.getAttribute('data-demo-price');
                var thumb = this.getAttribute('data-demo-thumb');

                var titleEl = document.getElementById('activeOrderTitle');
                var metaEl  = document.getElementById('activeOrderMeta');
                var pillEl  = document.getElementById('activeOrderPill');
                var nameEl  = document.getElementById('activeItemName');
                var qtyEl   = document.getElementById('activeItemQty');
                var priceEl = document.getElementById('activeItemPrice');
                var thumbEl = document.getElementById('activeItemThumb');

                if (titleEl) titleEl.textContent = title;
                if (metaEl)  metaEl.textContent  = meta;
                if (nameEl)  nameEl.textContent  = item;
                if (qtyEl)   qtyEl.textContent   = qty;
                if (priceEl) priceEl.textContent = price;
                if (thumbEl) thumbEl.src         = thumb;

                if (pillEl) {
                    pillEl.textContent = pill;
                    pillEl.className = 'rs-status-pill status-completed';
                }

                // Update Stepper to Completed (Delivered)
                ['step1', 'step2', 'step3', 'step4'].forEach(function(sId) {
                    var el = document.getElementById(sId);
                    if (el) el.className = 'stepper-step completed';
                });
                ['line1', 'line2', 'line3'].forEach(function(lId) {
                    var el = document.getElementById(lId);
                    if (el) el.className = 'stepper-line completed';
                });

                // Scroll smoothly to active order card with highlight flash
                var activeCard = document.getElementById('activeOrderCard');
                if (activeCard) {
                    activeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    activeCard.classList.remove('card-highlight-flash');
                    void activeCard.offsetWidth;
                    activeCard.classList.add('card-highlight-flash');
                }
            }
        });

        // Accessible Keyboard Enter / Space support
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });

    // 3. Reorder Button AJAX Handler
    document.querySelectorAll('.btn-reorder').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $b = this;
            var orderId = $b.getAttribute('data-order-id');
            $b.classList.add('loading');
            $b.textContent = 'Adding...';

            if (typeof jQuery !== 'undefined' && typeof komerce_ajax !== 'undefined') {
                jQuery.ajax({
                    type: 'POST',
                    url: komerce_ajax.ajax_url,
                    data: {
                        action: 'rajaskin_reorder',
                        order_id: orderId
                    },
                    success: function(res) {
                        window.location.href = (res && res.data && res.data.cart_url) ? res.data.cart_url : '<?php echo esc_url( wc_get_cart_url() ); ?>';
                    },
                    error: function() {
                        window.location.href = '<?php echo esc_url( wc_get_cart_url() ); ?>';
                    }
                });
            } else {
                setTimeout(function() {
                    window.location.href = '<?php echo esc_url( wc_get_cart_url() ); ?>';
                }, 500);
            }
        });
    });
});
</script>

<?php
get_footer();
