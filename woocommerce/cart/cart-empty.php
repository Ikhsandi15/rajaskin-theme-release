<?php
/**
 * Empty Cart Page Template - RajaSkin Luxury Editorial
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container rs-cart-page-wrap rs-cart-empty-wrap">

    <!-- Breadcrumb -->
    <nav class="cart-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'rajaskin-wp-theme' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'rajaskin-wp-theme' ); ?></a>
        <span class="sep">/</span>
        <span class="current"><?php esc_html_e( 'Shopping Cart', 'rajaskin-wp-theme' ); ?></span>
    </nav>

    <!-- Page Title & Subtitle -->
    <div class="cart-header-intro">
        <h1 class="cart-page-title"><?php echo esc_html( get_theme_mod( 'rajaskin_cart_title', 'Shopping Cart' ) ); ?></h1>
        <p class="cart-page-subtitle"><?php echo esc_html( get_theme_mod( 'rajaskin_cart_subtitle', 'Review your selected items and proceed to secure checkout.' ) ); ?></p>
    </div>

    <!-- Empty State Content Card -->
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

        <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
            <div class="empty-cart-action">
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-return-shop">
                    <span><?php esc_html_e( 'Discover Our Products', 'rajaskin-wp-theme' ); ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        <?php endif; ?>

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

    <?php
    /**
     * Trigger action when cart is empty
     */
    do_action( 'woocommerce_cart_is_empty' );
    ?>

</div>
