<?php
/**
 * WooCommerce Specific Hooks & Customizations (Komerce Style)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure store, cart, and checkout are fully accessible to guests (disable Coming Soon mode)
 */
add_filter( 'pre_option_woocommerce_coming_soon', '__return_empty_string' );
add_filter( 'woocommerce_coming_soon_exclude', '__return_true' );
add_action( 'init', function() {
    if ( get_option( 'woocommerce_coming_soon' ) === 'yes' ) {
        update_option( 'woocommerce_coming_soon', 'no' );
    }
} );

/**
 * Ensure guest WooCommerce session is initialized on Cart and Checkout pages
 */
add_action( 'template_redirect', function() {
    if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
        if ( function_exists( 'is_cart' ) && function_exists( 'is_checkout' ) ) {
            if ( is_cart() || is_checkout() ) {
                WC()->session->set_customer_session_cookie( true );
            }
        }
    }
} );

/**
 * Remove default WooCommerce styles for total design control
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


/**
 * Customize Checkout Fields for Multi-step Form
 */
function komerce_theme_checkout_fields( $fields ) {
    // Billing first & last name
    if ( isset( $fields['billing']['billing_first_name'] ) ) {
        $fields['billing']['billing_first_name']['required'] = true;
    }
    if ( isset( $fields['billing']['billing_last_name'] ) ) {
        $fields['billing']['billing_last_name']['required'] = false;
    }
    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['required'] = true;
    }
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['required'] = true;
    }
    if ( isset( $fields['billing']['billing_address_1'] ) ) {
        $fields['billing']['billing_address_1']['required'] = true;
    }
    if ( isset( $fields['billing']['billing_city'] ) ) {
        $fields['billing']['billing_city']['required'] = false;
    }
    if ( isset( $fields['billing']['billing_state'] ) ) {
        $fields['billing']['billing_state']['required'] = false;
    }
    if ( isset( $fields['billing']['billing_postcode'] ) ) {
        $fields['billing']['billing_postcode']['required'] = false;
    }

    // Default country Indonesia
    if ( isset( $fields['billing']['billing_country'] ) ) {
        $fields['billing']['billing_country']['required'] = false;
        $fields['billing']['billing_country']['default']  = 'ID';
    }

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'komerce_theme_checkout_fields', 999 );
add_filter( 'woocommerce_billing_fields', 'komerce_theme_checkout_fields', 999 );

/**
 * Auto-resolve Order Pay direct URL access (/checkout/order-pay/:id)
 * Redirects to the canonical payment URL with key if valid
 */
function rajaskin_resolve_order_pay_url() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
        return;
    }

    global $wp;
    $order_id = 0;
    if ( isset( $wp->query_vars['order-pay'] ) ) {
        $order_id = absint( $wp->query_vars['order-pay'] );
    } elseif ( isset( $_GET['order-pay'] ) ) {
        $order_id = absint( $_GET['order-pay'] );
    }

    if ( $order_id > 0 ) {
        // Check if pay_for_order and key are already in GET query
        if ( ! isset( $_GET['pay_for_order'] ) || empty( $_GET['key'] ) ) {
            $order = wc_get_order( $order_id );
            if ( $order && is_a( $order, 'WC_Order' ) ) {
                $is_authorized = false;
                $current_user_id = get_current_user_id();

                if ( $current_user_id && $order->get_customer_id() === $current_user_id ) {
                    $is_authorized = true;
                } elseif ( current_user_can( 'manage_woocommerce' ) ) {
                    $is_authorized = true;
                } else {
                    // Check guest cookie
                    if ( ! empty( $_COOKIE['rajaskin_guest_orders'] ) ) {
                        $guest_orders = json_decode( stripslashes( $_COOKIE['rajaskin_guest_orders'] ), true );
                        if ( is_array( $guest_orders ) ) {
                            foreach ( $guest_orders as $entry ) {
                                $entry_id = is_array( $entry ) ? ( $entry['id'] ?? 0 ) : $entry;
                                if ( (int) $entry_id === $order_id ) {
                                    $is_authorized = true;
                                    break;
                                }
                            }
                        }
                    }
                    // If guest order created recently without user ID
                    if ( ! $is_authorized && ! $order->get_customer_id() ) {
                        $is_authorized = true;
                    }
                }

                if ( $is_authorized ) {
                    $pay_url = $order->get_checkout_payment_url();
                    if ( $pay_url && ! headers_sent() ) {
                        wp_safe_redirect( $pay_url );
                        exit;
                    }
                }
            }
        }
    }
}
add_action( 'template_redirect', 'rajaskin_resolve_order_pay_url', 10 );
/**
 * Ensure default payment gateway fallback if none configured
 */
add_action( 'woocommerce_checkout_process', function() {
    if ( empty( $_POST['payment_method'] ) ) {
        $gateways = WC()->payment_gateways()->get_available_payment_gateways();
        if ( ! empty( $gateways ) ) {
            $first_key = array_key_first( $gateways );
            $_POST['payment_method'] = $first_key;
        } else {
            $_POST['payment_method'] = 'bacs';
        }
    }
} );

/**
 * Force Classic Checkout Shortcode (override Checkout Block)
 */
add_filter( 'the_content', function( $content ) {
    if ( is_checkout() ) {
        return do_shortcode( '[woocommerce_checkout]' );
    }
    return $content;
}, 99 );

/**
 * Force Classic Cart Shortcode (override Cart Block)
 */
add_filter( 'the_content', function( $content ) {
    if ( is_cart() ) {
        return do_shortcode( '[woocommerce_cart]' );
    }
    return $content;
}, 99 );

/**
 * Remove default coupon form entirely (we use a custom inline one)
 */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

/**
 * Apply coupon via AJAX for our custom inline coupon input
 */
add_action( 'wp_footer', function() {
    if ( ! is_checkout() ) return;
    ?>
    <script>
    jQuery(function($){
        $('#komerce_apply_coupon').on('click', function(e){
            e.preventDefault();
            var code = $('#komerce_coupon_code').val();
            if ( ! code ) return;

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    action: 'apply_coupon',
                    security: wc_checkout_params.apply_coupon_nonce,
                    coupon_code: code
                },
                success: function() {
                    $(document.body).trigger('update_checkout');
                    $('#komerce_coupon_code').val('');
                }
            });
        });
    });
    </script>
    <?php
}, 99 );

/**
 * Cart Fragment: Update cart count badge via AJAX
 */
function komerce_cart_count_fragment( $fragments ) {
    $count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
    $fragments['.cart-count-badge'] = '<span class="cart-count-badge"' . ( $count > 0 ? '' : ' style="display:none;"' ) . '>' . $count . '</span>';
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'komerce_cart_count_fragment' );

/**
 * Ensure WooCommerce cart-fragments script is loaded on all pages
 */
function komerce_enqueue_cart_fragments() {
    wp_enqueue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'komerce_enqueue_cart_fragments' );

/**
 * Localize WC AJAX URL for custom scripts
 */
function komerce_localize_ajax() {
    wp_localize_script( 'jquery', 'komerce_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'wc_ajax_url' => WC_AJAX::get_endpoint( '%%endpoint%%' ),
        'cart_url' => wc_get_cart_url(),
        'order_history_url' => function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'komerce_localize_ajax' );

/**
 * Global AJAX Add to Cart badge updater and quick-add handler
 */
add_action( 'wp_footer', function() {
    ?>
    <script>
    jQuery(function($) {
        function updateGlobalCartBadge(count) {
            $('.cart-link').each(function() {
                var $link = $(this);
                var $badge = $link.find('.cart-count-badge');
                if (!$badge.length) {
                    $badge = $('<span class="cart-count-badge"></span>').appendTo($link);
                }
                $badge.text(count);
                if (count > 0) {
                    $badge.css('display', 'flex').removeClass('pulse-badge');
                    void $badge[0].offsetWidth;
                    $badge.addClass('pulse-badge');
                } else {
                    $badge.hide();
                }
            });
        }

        // Listen to WooCommerce fragments update
        $(document.body).on('added_to_cart wc_fragments_refreshed wc_fragments_loaded', function(e, fragments, cart_hash, $button) {
            if (fragments && fragments['.cart-count-badge']) {
                var $frag = $(fragments['.cart-count-badge']);
                var count = parseInt($frag.text(), 10) || 0;
                updateGlobalCartBadge(count);
            }
        });

        // Quick add buttons
        $(document).on('click', '.product-quick-add', function(e) {
            if ($(this).attr('id') === 'rsAddToCartBtn') return; // Handled by single-product template

            e.preventDefault();
            var $btn = $(this);
            var productId = $btn.data('product_id') || $btn.data('product-id');
            var productType = $btn.data('product_type') || $btn.data('product-type');

            if (productType === 'variable') {
                var link = $btn.closest('.rs-product-card, .rs-cat-prod-card').find('a').first().attr('href');
                if (link) window.location.href = link;
                return;
            }

            if ($btn.hasClass('loading') || !productId) return;
            $btn.addClass('loading');

            var currentBadge = $('.cart-count-badge').first();
            var currentCount = currentBadge.length ? (parseInt(currentBadge.text(), 10) || 0) : 0;
            updateGlobalCartBadge(currentCount + 1);

            var ajaxUrl = (typeof komerce_ajax !== 'undefined') ? komerce_ajax.ajax_url : '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(res) {
                    $btn.removeClass('loading').addClass('added');
                    if (res && res.cart_count !== undefined) {
                        updateGlobalCartBadge(res.cart_count);
                    }
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document.body).trigger('added_to_cart', [res && res.fragments ? res.fragments : {}, res && res.cart_hash ? res.cart_hash : '', $btn]);
                    setTimeout(function() {
                        $btn.removeClass('added');
                    }, 1800);
                },
                error: function() {
                    $btn.removeClass('loading');
                }
            });
        });
    });
    </script>
    <?php
}, 99 );

/**
 * Handle AJAX add to cart for simple products
 */
function komerce_ajax_add_to_cart() {
    $product_id = absint( $_POST['product_id'] );
    $quantity = absint( $_POST['quantity'] ?? 1 );

    if ( ! $product_id ) {
        wp_send_json_error( 'Invalid product' );
    }

    $product = wc_get_product( $product_id );
    if ( ! $product || $product->is_type( 'variable' ) ) {
        wp_send_json( array( 'error' => true ));
    }

    // Ensure session is active and cookie set for guest users
    if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $cart_item_key ) {
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        wp_send_json( array(
            'success'    => true,
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_hash'  => WC()->cart->get_cart_hash(),
            'fragments'  => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            ) ),
        ) );
    } else {
        wp_send_json_error( 'Could not add to cart' );
    }
}
add_action( 'wp_ajax_woocommerce_add_to_cart', 'komerce_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart', 'komerce_ajax_add_to_cart' );

/**
 * AJAX: Update cart item quantity (no reload)
 */
function komerce_ajax_update_cart_qty() {
    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
    $quantity = absint( $_POST['quantity'] ?? 1 );

    if ( empty( $cart_item_key ) ) {
        wp_send_json_error( 'Missing cart item key' );
    }

    // Ensure session is active for guest users
    if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    if ( $quantity === 0 ) {
        WC()->cart->remove_cart_item( $cart_item_key );
    } else {
        WC()->cart->set_quantity( $cart_item_key, $quantity, true );
    }

    
    WC()->cart->calculate_totals();

    $cart_item = WC()->cart->get_cart_item( $cart_item_key );
    $item_subtotal = '';
    if ( $cart_item ) {
        $_product = $cart_item['data'];
        $item_subtotal = WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );
    }

    $cart_subtotal_raw = WC()->cart->get_subtotal();
    $free_shipping_min = 500000;
    $remaining = max( 0, $free_shipping_min - $cart_subtotal_raw );
    $progress = min( 100, ( $cart_subtotal_raw / $free_shipping_min ) * 100 );

    $shipping_text = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> ';
    if ( $remaining > 0 ) {
        $shipping_text .= 'Add ' . wc_price( $remaining ) . ' more for free delivery';
    } else {
        $shipping_text .= '🎉 You qualify for free delivery!';
    }

    wp_send_json_success( array(
        'item_subtotal'     => $item_subtotal,
        'cart_subtotal'     => WC()->cart->get_cart_subtotal(),
        'cart_total'        => WC()->cart->get_total(),
        'cart_count'        => WC()->cart->get_cart_contents_count(),
        'cart_items_count'  => count( WC()->cart->get_cart() ),
        'is_empty'          => WC()->cart->is_empty(),
        'shipping_text'     => $shipping_text,
        'shipping_progress' => round( $progress, 1 ),
    ) );
}
add_action( 'wp_ajax_komerce_update_cart_qty', 'komerce_ajax_update_cart_qty' );
add_action( 'wp_ajax_nopriv_komerce_update_cart_qty', 'komerce_ajax_update_cart_qty' );

/**
 * AJAX: Remove single cart item
 */
function komerce_ajax_remove_cart_item() {
    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );

    if ( empty( $cart_item_key ) ) {
        wp_send_json_error( 'Missing cart item key' );
    }

    $removed = WC()->cart->remove_cart_item( $cart_item_key );

    if ( $removed ) {
        WC()->cart->calculate_totals();

        wp_send_json_success( array(
            'cart_subtotal'    => WC()->cart->get_cart_subtotal(),
            'cart_total'       => WC()->cart->get_total(),
            'cart_count'       => WC()->cart->get_cart_contents_count(),
            'cart_items_count' => count( WC()->cart->get_cart() ),
            'is_empty'         => WC()->cart->is_empty(),
        ) );
    } else {
        wp_send_json_error( 'Could not remove item' );
    }
}
add_action( 'wp_ajax_komerce_remove_cart_item', 'komerce_ajax_remove_cart_item' );
add_action( 'wp_ajax_nopriv_komerce_remove_cart_item', 'komerce_ajax_remove_cart_item' );

/**
 * AJAX: Clear entire cart
 */
function komerce_ajax_clear_cart() {
    WC()->cart->empty_cart();

    wp_send_json_success( array(
        'is_empty'   => true,
        'cart_count' => 0,
    ) );
}
add_action( 'wp_ajax_komerce_clear_cart', 'komerce_ajax_clear_cart' );
add_action( 'wp_ajax_nopriv_komerce_clear_cart', 'komerce_ajax_clear_cart' );

/**
 * Save Guest Order into Persistent Cookie (Valid for 1 Year)
 */
function rajaskin_save_guest_order_cookie( $order_id ) {
    if ( ! $order_id ) return;
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    $saved_orders = array();
    if ( ! empty( $_COOKIE['rajaskin_guest_orders'] ) ) {
        $decoded = json_decode( stripslashes( $_COOKIE['rajaskin_guest_orders'] ), true );
        if ( is_array( $decoded ) ) {
            $saved_orders = $decoded;
        }
    }

    $order_entry = array(
        'id'  => $order_id,
        'key' => $order->get_order_key(),
    );

    // Check if already in list
    $exists = false;
    foreach ( $saved_orders as $entry ) {
        $id = is_array( $entry ) ? ( $entry['id'] ?? 0 ) : $entry;
        if ( $id == $order_id ) {
            $exists = true;
            break;
        }
    }

    if ( ! $exists ) {
        array_unshift( $saved_orders, $order_entry );
        $saved_orders = array_slice( $saved_orders, 0, 50 ); // keep last 50 orders
        if ( ! headers_sent() ) {
            setcookie( 'rajaskin_guest_orders', json_encode( $saved_orders ), time() + ( 365 * 86400 ), COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false );
        }
    }
}
add_action( 'woocommerce_thankyou', 'rajaskin_save_guest_order_cookie', 10, 1 );
add_action( 'woocommerce_checkout_order_processed', 'rajaskin_save_guest_order_cookie', 10, 1 );

/**
 * AJAX: Reorder products from an order
 */
function rajaskin_ajax_reorder() {
    $order_id = absint( $_POST['order_id'] ?? 0 );

    // Ensure session is active for guest users
    if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    if ( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( $order ) {
            foreach ( $order->get_items() as $item ) {
                $product_id   = $item->get_product_id();
                $qty          = $item->get_quantity();
                $variation_id = $item->get_variation_id();
                if ( $product_id ) {
                    WC()->cart->add_to_cart( $product_id, $qty, $variation_id );
                }
            }
        }
    } else {
        // Fallback: If demo order reorder, add first available product to cart
        $products = wc_get_products( array( 'limit' => 1, 'status' => 'publish' ) );
        if ( ! empty( $products ) ) {
            $first_prod = reset( $products );
            WC()->cart->add_to_cart( $first_prod->get_id(), 1 );
        }
    }

    wp_send_json_success( array(
        'cart_url' => wc_get_cart_url(),
    ) );
}
add_action( 'wp_ajax_rajaskin_reorder', 'rajaskin_ajax_reorder' );
add_action( 'wp_ajax_nopriv_rajaskin_reorder', 'rajaskin_ajax_reorder' );

/**
 * Customize My Account Navigation Menu Items
 */
function rajaskin_account_menu_items( $items ) {
    // Remove downloads since RajaSkin sells physical skincare products
    unset( $items['downloads'] );

    // Structured items matching RajaSkin luxury branding
    $new_items = array(
        'dashboard'       => __( 'Dashboard', 'rajaskin-wp-theme' ),
        'orders'          => __( 'My Orders', 'rajaskin-wp-theme' ),
        'order-history'   => __( 'Live Order Tracking', 'rajaskin-wp-theme' ),
        'edit-address'    => __( 'Addresses', 'rajaskin-wp-theme' ),
        'edit-account'    => __( 'Account Details', 'rajaskin-wp-theme' ),
        'customer-logout' => __( 'Log Out', 'rajaskin-wp-theme' ),
    );

    return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'rajaskin_account_menu_items' );

/**
 * Handle custom endpoint URL for Live Order Tracking in My Account
 */
function rajaskin_account_menu_endpoint_url( $url, $endpoint, $value, $permalink ) {
    if ( $endpoint === 'order-history' ) {
        return function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' );
    }
    return $url;
}
add_filter( 'woocommerce_get_endpoint_url', 'rajaskin_account_menu_endpoint_url', 10, 4 );

/**
 * Fallback helper for checking if account menu item is active
 */
if ( ! function_exists( 'wc_is_account_menu_item_active' ) ) {
    function wc_is_account_menu_item_active( $endpoint ) {
        if ( function_exists( 'wc_get_account_menu_item_classes' ) ) {
            $classes = wc_get_account_menu_item_classes( $endpoint );
            return ( strpos( $classes, 'is-active' ) !== false );
        }
        return false;
    }
}

/**
 * Render Delivery Methods HTML for Checkout Step 2
 */
if ( ! function_exists( 'rajaskin_render_delivery_methods' ) ) {
    function rajaskin_render_delivery_methods() {
        ?>
        <div class="woocommerce-shipping-wrapper" id="shipping-method-wrapper">
            <?php if ( WC()->cart && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                <?php
                $packages = WC()->shipping()->get_packages();
                if ( ! empty( $packages ) ) :
                    foreach ( $packages as $i => $package ) :
                        $chosen_method     = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
                        $available_methods = $package['rates'];
                        $user_has_chosen   = ! empty( $chosen_method ) && (
                            ( WC()->session && WC()->session->get( 'komerce_user_has_selected_shipping' ) )
                            || ! empty( $_POST['shipping_method'] )
                            || ( isset( $_POST['post_data'] ) && false !== strpos( (string) $_POST['post_data'], 'shipping_method' ) )
                            || ( WC()->cart && WC()->cart->get_shipping_total() > 0 )
                        );
                        ?>
                        <ul id="shipping_method" class="woocommerce-shipping-methods delivery-methods-list">
                            <?php if ( ! empty( $available_methods ) ) : 
                                foreach ( $available_methods as $method_id => $method ) :
                                    $checked    = ( $user_has_chosen && $method_id === $chosen_method );
                                    $active_cls = $checked ? 'active' : '';
                                    ?>
                                    <li class="delivery-method-card <?php echo esc_attr( $active_cls ); ?>" data-cost="<?php echo esc_attr( $method->cost ); ?>" data-name="<?php echo esc_attr( $method->get_label() ); ?>" data-price="<?php echo esc_attr( wc_price( $method->cost ) ); ?>">
                                        <div class="dm-radio-wrap">
                                            <input type="radio" name="shipping_method[<?php echo esc_attr( $i ); ?>]" data-index="<?php echo esc_attr( $i ); ?>" id="shipping_method_<?php echo esc_attr( $i ); ?>_<?php echo esc_attr( sanitize_title( $method_id ) ); ?>" value="<?php echo esc_attr( $method_id ); ?>" class="shipping_method" <?php checked( $checked, true ); ?> data-cost="<?php echo esc_attr( $method->cost ); ?>" data-name="<?php echo esc_attr( $method->get_label() ); ?>">
                                            <span class="dm-radio-dot"></span>
                                        </div>
                                        <label for="shipping_method_<?php echo esc_attr( $i ); ?>_<?php echo esc_attr( sanitize_title( $method_id ) ); ?>" class="dm-info">
                                            <span class="dm-name"><?php echo esc_html( $method->get_label() ); ?></span>
                                            <span class="dm-desc"><?php echo esc_html( $method->get_description() ?: 'Estimasi pengiriman reguler' ); ?></span>
                                        </label>
                                        <div class="dm-price"><?php echo wc_price( $method->cost ); ?></div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li class="no-shipping-rates-notice" style="padding: 1.5rem; text-align: center; border: 1px dashed #e2e2e2; border-radius: 12px; background: #fafafa;">
                                    <p style="color: #666; margin: 0; font-size: 0.9rem;">Silakan pilih destinasi / alamat pengiriman di Langkah 1 untuk melihat pilihan kurir.</p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endforeach; ?>
                <?php else : ?>
                    <ul id="shipping_method" class="woocommerce-shipping-methods delivery-methods-list">
                        <li class="no-shipping-rates-notice" style="padding: 1.5rem; text-align: center; border: 1px dashed #e2e2e2; border-radius: 12px; background: #fafafa;">
                            <p style="color: #666; margin: 0; font-size: 0.9rem;">Silakan pilih destinasi / alamat pengiriman di Langkah 1 untuk melihat pilihan kurir.</p>
                        </li>
                    </ul>
                <?php endif; ?>
            <?php else : ?>
                <p style="color: #666; margin: 0; font-size: 0.9rem;">Pengiriman tidak diperlukan untuk pesanan ini.</p>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Register shipping method wrapper in WooCommerce checkout review fragments
 * Ensures Step 2 Delivery Method list updates via AJAX without needing a page reload.
 */
function rajaskin_shipping_method_wrapper_fragment( $fragments ) {
    ob_start();
    rajaskin_render_delivery_methods();
    $fragments['#shipping-method-wrapper'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'rajaskin_shipping_method_wrapper_fragment', 20 );


