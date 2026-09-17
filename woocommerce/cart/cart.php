<?php
/**
 * Cart Page Template - RajaSkin Modern Editorial
 * Overrides woocommerce/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

// Ensure WooCommerce cart session is active for guests
if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
    WC()->session->set_customer_session_cookie( true );
}

do_action( 'woocommerce_before_cart' );
?>


<div class="container rs-cart-page-wrap">

    <!-- Breadcrumb -->
    <nav class="cart-breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
        <span class="sep">/</span>
        <span class="current">Shopping Cart</span>
    </nav>

    <!-- Page Title & Subtitle -->
    <div class="cart-header-intro">
        <h1 class="cart-page-title"><?php echo esc_html( get_theme_mod( 'rajaskin_cart_title', 'Shopping Cart' ) ); ?></h1>
        <p class="cart-page-subtitle"><?php echo esc_html( get_theme_mod( 'rajaskin_cart_subtitle', 'Review your selected items and proceed to secure checkout.' ) ); ?></p>
    </div>

    <?php if ( WC()->cart->is_empty() ) : ?>
        <div class="cart-empty-state">
            <div class="empty-icon-wrap">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <h2 class="empty-cart-title"><?php esc_html_e( 'Your shopping cart is empty', 'rajaskin-wp-theme' ); ?></h2>
            <p class="empty-cart-desc"><?php esc_html_e( 'Looks like you haven\'t added any products to your cart yet. Explore our curated clinical skincare collection and begin your glowing journey.', 'rajaskin-wp-theme' ); ?></p>
            
            <div class="empty-cart-action">
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-return-shop">
                    <span><?php esc_html_e( 'Discover Our Products', 'rajaskin-wp-theme' ); ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <!-- Trust & Benefit Perks -->
            <div class="cart-empty-perks-grid">
                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    <div class="perk-text">
                        <strong><?php esc_html_e( 'Free Delivery', 'rajaskin-wp-theme' ); ?></strong>
                        <span><?php esc_html_e( 'On orders over Rp500.000', 'rajaskin-wp-theme' ); ?></span>
                    </div>
                </div>

                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                    </div>
                    <div class="perk-text">
                        <strong><?php esc_html_e( '100% Authentic', 'rajaskin-wp-theme' ); ?></strong>
                        <span><?php esc_html_e( 'BPOM Certified & Tested', 'rajaskin-wp-theme' ); ?></span>
                    </div>
                </div>

                <div class="perk-item">
                    <div class="perk-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <div class="perk-text">
                        <strong><?php esc_html_e( 'Secure Payment', 'rajaskin-wp-theme' ); ?></strong>
                        <span><?php esc_html_e( 'Multi-channel protected', 'rajaskin-wp-theme' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php else : 
        $cart_items = WC()->cart->get_cart();
        $item_count = count( $cart_items );
    ?>
        <form class="woocommerce-cart-form komerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <div class="cart-layout">

                <!-- LEFT COLUMN: Cart Items -->
                <div class="cart-items-col">
                    <h2 class="cart-col-title">Your Cart</h2>

                    <!-- Selection & Clear Bar -->
                    <div class="cart-select-bar">
                        <label class="cart-custom-checkbox select-all-wrap">
                            <input type="checkbox" id="cartSelectAll" checked>
                            <span class="cart-checkmark"></span>
                            <span class="select-count-text"><span class="selected-qty-num"><?php echo esc_html( $item_count ); ?></span> Product Selected</span>
                        </label>
                        <button type="button" class="cart-clear-all" id="cartClearAll">Clear All</button>
                    </div>

                    <!-- Cart Items List -->
                    <div class="cart-items-list">
                        <?php
                        foreach ( $cart_items as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                
                                // Fetch Category Name
                                $terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'names' ) );
                                $cat_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? strtoupper( $terms[0] ) : 'SKIN CARE';
                                ?>
                                <div class="cart-item-row" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                    
                                    <!-- Left Checkbox -->
                                    <label class="cart-custom-checkbox item-checkbox">
                                        <input type="checkbox" class="cart-item-check" checked data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                        <span class="cart-checkmark"></span>
                                    </label>

                                    <!-- Product Thumbnail -->
                                    <div class="cart-item-thumb">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 90, 90 ) ), $cart_item, $cart_item_key );
                                        if ( $product_permalink ) {
                                            echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>';
                                        } else {
                                            echo $thumbnail;
                                        }
                                        ?>
                                    </div>

                                    <!-- Product Info (Middle) -->
                                    <div class="cart-item-info">
                                        <span class="cart-item-category"><?php echo esc_html( $cat_name ); ?></span>
                                        <h3 class="cart-item-name">
                                            <?php
                                            if ( $product_permalink ) {
                                                echo '<a href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( $_product->get_name() ) . '</a>';
                                            } else {
                                                echo wp_kses_post( $_product->get_name() );
                                            }
                                            ?>
                                        </h3>
                                        <?php
                                        $variation = wc_get_formatted_cart_item_data( $cart_item );
                                        if ( $variation ) {
                                            echo '<div class="cart-item-variant">' . $variation . '</div>';
                                        }
                                        ?>
                                        
                                        <!-- Quantity Selector -->
                                        <div class="cart-item-qty-wrap">
                                            <div class="qty-control">
                                                <button type="button" class="qty-minus" aria-label="Decrease">−</button>
                                                <input type="number" class="qty" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1" max="<?php echo esc_attr( $_product->get_max_purchase_quantity() > 0 ? $_product->get_max_purchase_quantity() : 9999 ); ?>" step="1">
                                                <button type="button" class="qty-plus" aria-label="Increase">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side: Delete Top, Subtotal Bottom -->
                                    <div class="cart-item-right">
                                        <button type="button" class="cart-item-remove-btn" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="Remove item">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                        
                                        <div class="cart-item-subtotal">
                                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <div class="cart-hidden-actions" style="display:none;">
                        <button type="submit" class="button" name="update_cart" value="Update cart">Update cart</button>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Order Summary -->
                <div class="cart-summary-col">
                    <div class="cart-summary-card">
                        <h3 class="summary-title">Order Summary</h3>

                        <div class="summary-row subtotal-row">
                            <span class="summary-label">Subtotal (<span class="summary-count"><?php echo esc_html( $item_count ); ?> Products</span>)</span>
                            <span class="summary-value subtotal-value"><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row total-row">
                            <span class="total-label">TOTAL</span>
                            <span class="total-value cart-total-value"><?php echo WC()->cart->get_total(); ?></span>
                        </div>

                        <div class="summary-actions">
                            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-cart-checkout">Checkout</a>
                            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-continue-shopping">Continue Shopping</a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    <?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<!-- Dynamic Cart Interactions -->
<script>
jQuery(function($) {
    var ajaxTimer;
    var ajaxUrl = (typeof komerce_ajax !== 'undefined' && komerce_ajax.ajax_url)
        ? komerce_ajax.ajax_url
        : '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

    // Helper: update selected count text
    function updateSelectedCount() {
        var totalChecked = $('.cart-item-check:checked').length;
        var totalItems = $('.cart-item-check').length;

        $('.selected-qty-num').text(totalChecked);
        $('.summary-count').text(totalChecked + ' Products');

        $('#cartSelectAll').prop('checked', totalChecked === totalItems && totalItems > 0);
    }

    // Select All Checkbox Toggle
    $(document).on('change', '#cartSelectAll', function() {
        var isChecked = $(this).is(':checked');
        $('.cart-item-check').prop('checked', isChecked);
        updateSelectedCount();
    });

    // Individual Item Checkbox Toggle
    $(document).on('change', '.cart-item-check', function() {
        updateSelectedCount();
    });

    // Quantity Minus
    $(document).on('click', '.qty-minus', function() {
        var $row = $(this).closest('.cart-item-row');
        var $input = $(this).closest('.qty-control').find('.qty');
        var val = parseInt($input.val()) || 1;
        if (val > 1) {
            $input.val(val - 1);
            ajaxUpdateQty($row, $input);
        }
    });

    // Quantity Plus
    $(document).on('click', '.qty-plus', function() {
        var $row = $(this).closest('.cart-item-row');
        var $input = $(this).closest('.qty-control').find('.qty');
        var max = parseInt($input.attr('max')) || 9999;
        var val = parseInt($input.val()) || 0;
        if (val < max) {
            $input.val(val + 1);
            ajaxUpdateQty($row, $input);
        }
    });

    // Manual input change
    $(document).on('change', '.qty-control .qty', function() {
        var $row = $(this).closest('.cart-item-row');
        var val = parseInt($(this).val()) || 1;
        if (val < 1) $(this).val(1);
        ajaxUpdateQty($row, $(this));
    });

    // AJAX: Update Quantity
    function ajaxUpdateQty($row, $input) {
        clearTimeout(ajaxTimer);
        var cartItemKey = $row.data('cart-item-key');
        var newQty = parseInt($input.val()) || 1;

        $row.addClass('updating-item');

        ajaxTimer = setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'komerce_update_cart_qty',
                    cart_item_key: cartItemKey,
                    quantity: newQty
                },
                success: function(res) {
                    $row.removeClass('updating-item');
                    if (res.success && res.data) {
                        var data = res.data;
                        $row.find('.cart-item-subtotal').html(data.item_subtotal);
                        $('.subtotal-value').html(data.cart_subtotal);
                        $('.cart-total-value').html(data.cart_total);
                        $('.cart-count-badge').text(data.cart_count);
                        $(document.body).trigger('wc_fragment_refresh');
                    }
                },
                error: function() {
                    $row.removeClass('updating-item');
                }
            });
        }, 300);
    }

    // AJAX: Remove Single Cart Item
    $(document).on('click', '.cart-item-remove-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $row = $btn.closest('.cart-item-row');
        var cartItemKey = $btn.data('cart-item-key');

        $row.addClass('updating-item');

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'komerce_remove_cart_item',
                cart_item_key: cartItemKey
            },
            success: function(res) {
                if (res.success && res.data) {
                    var data = res.data;
                    $row.fadeOut(250, function() {
                        $(this).remove();
                        updateSelectedCount();

                        if (data.is_empty || $('.cart-item-row').length === 0) {
                            location.reload();
                        } else {
                            $('.subtotal-value').html(data.cart_subtotal);
                            $('.cart-total-value').html(data.cart_total);
                            $('.cart-count-badge').text(data.cart_count);
                        }
                    });
                    $(document.body).trigger('wc_fragment_refresh');
                } else {
                    $row.removeClass('updating-item');
                }
            },
            error: function() {
                $row.removeClass('updating-item');
            }
        });
    });

    // AJAX: Clear All Cart Items
    $(document).on('click', '#cartClearAll', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to clear your cart?')) return;

        $('.cart-items-list').css('opacity', '0.4');

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'komerce_clear_cart'
            },
            success: function(res) {
                if (res.success) {
                    location.reload();
                }
            }
        });
    });
});
</script>
