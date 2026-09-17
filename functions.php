<?php
/**
 * Komerce Custom Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme Setup & WooCommerce Support
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Enqueue scripts and styles
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * WooCommerce Specific Hooks & Customizations
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Theme Customizer Settings
 */
require get_template_directory() . '/inc/customizer.php';

// Add WooCommerce support to theme
function komerce_theme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'komerce_theme_add_woocommerce_support' );

/**
 * Automatic Theme Updates via GitHub Release
 */
if ( file_exists( get_template_directory() . '/inc/plugin-update-checker/plugin-update-checker.php' ) ) {
    require_once get_template_directory() . '/inc/plugin-update-checker/plugin-update-checker.php';
    $rajaskin_theme_update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/Ikhsandi15/rajaskin-theme-release/',
        get_template_directory() . '/style.css',
        'rajaskin-theme'
    );
    $rajaskin_theme_update_checker->setBranch( 'main' );
    if ( is_object( $rajaskin_theme_update_checker->getVcsApi() ) && method_exists( $rajaskin_theme_update_checker->getVcsApi(), 'enableReleaseAssets' ) ) {
        $rajaskin_theme_update_checker->getVcsApi()->enableReleaseAssets( '/\.zip($|[?&#])/i' );
    }
}


