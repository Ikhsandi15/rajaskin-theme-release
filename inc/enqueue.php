<?php
/**
 * Enqueue scripts and styles
 */

function komerce_theme_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'komerce-theme-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );

    // Custom checkout styles (only on checkout pages)
    if ( is_checkout() ) {
        wp_enqueue_style( 'komerce-checkout-style', get_template_directory_uri() . '/assets/css/checkout.css', array(), filemtime( get_template_directory() . '/assets/css/checkout.css' ) );
    }

    // Custom cart styles (only on cart page)
    if ( is_cart() ) {
        wp_enqueue_style( 'komerce-cart-style', get_template_directory_uri() . '/assets/css/cart.css', array(), '1.0.0' );
    }

    // Homepage styles (only on front page or home)
    if ( is_front_page() || is_home() ) {
        wp_enqueue_style( 'komerce-home-style', get_template_directory_uri() . '/assets/css/home.css', array(), '1.1.0' );
    }

    // Shop page styles
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_style( 'komerce-shop-style', get_template_directory_uri() . '/assets/css/shop.css', array(), '1.0.0' );
    }

    // Single product page styles
    if ( is_product() ) {
        wp_enqueue_style( 'komerce-product-style', get_template_directory_uri() . '/assets/css/product.css', array(), '1.0.0' );
    }

    // My Account styles
    if ( is_account_page() ) {
        wp_enqueue_style( 'komerce-my-account-style', get_template_directory_uri() . '/assets/css/my-account.css', array(), '1.0.0' );
    }

    // About Us page styles
    $is_about = is_page( 'about-us' ) || is_page( 'about' ) || is_page_template( 'page-about-us.php' ) || is_page_template( 'template-about.php' ) || is_page_template( 'page-about.php' );
    if ( ! $is_about ) {
        $req_path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
        if ( $req_path === 'about-us' || $req_path === 'about' || preg_match( '#(^|/)about(-us)?$#i', $req_path ) ) {
            $is_about = true;
        }
    }
    if ( $is_about ) {
        wp_enqueue_style( 'komerce-about-style', get_template_directory_uri() . '/assets/css/about.css', array(), '1.0.0' );
    }

    // Order History styles
    $is_oh = is_page( 'order-history' ) || is_page( 'orders' ) || is_page_template( 'page-order-history.php' );
    if ( ! $is_oh ) {
        $req_path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
        if ( $req_path === 'order-history' || $req_path === 'orders' || preg_match( '#(^|/)order-history/?$#i', $req_path ) ) {
            $is_oh = true;
        }
    }
    if ( $is_oh ) {
        wp_enqueue_style( 'rajaskin-order-history-style', get_template_directory_uri() . '/assets/css/order-history.css', array(), '1.0.0' );
    }



    // Google Fonts (Inter + Playfair Display)
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap', array(), null );

    // jQuery (provided by WordPress)
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'komerce_theme_scripts' );
