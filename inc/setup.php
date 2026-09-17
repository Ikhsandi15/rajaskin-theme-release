<?php
/**
 * General Theme Setup
 */

if ( ! function_exists( 'komerce_theme_setup' ) ) :
    function komerce_theme_setup() {
        // Add support for custom logo
        add_theme_support( 'custom-logo' );

        // Add support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        // Register navigation menus
        register_nav_menus( array(
            'menu-1' => esc_html__( 'Primary', 'komerce-theme' ),
        ) );

        // Switch default core markup for search form, comment form, etc. to output valid HTML5
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );
    }
endif;
add_action( 'after_setup_theme', 'komerce_theme_setup' );

/**
 * Helper to get About Us page URL
 */
function rajaskin_get_about_url() {
    $about_page = get_page_by_path( 'about-us' );
    if ( ! $about_page ) {
        $about_page = get_page_by_path( 'about' );
    }
    if ( $about_page ) {
        return get_permalink( $about_page->ID );
    }
    return home_url( '/about-us/' );
}

/**
 * Auto-create About Us page in WordPress database if not exists
 */
function rajaskin_auto_create_about_page() {
    if ( get_option( 'rajaskin_about_page_created' ) ) {
        return;
    }

    $about_page = get_page_by_path( 'about-us' );
    if ( ! $about_page ) {
        $about_page = get_page_by_path( 'about' );
    }

    if ( ! $about_page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => 'About Us',
            'post_name'      => 'about-us',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '',
            'page_template'  => 'page-about-us.php',
        ) );
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_option( 'rajaskin_about_page_created', 1 );
            flush_rewrite_rules( false );
        }
    } else {
        update_option( 'rajaskin_about_page_created', 1 );
    }
}
add_action( 'init', 'rajaskin_auto_create_about_page' );

/**
 * Ensure About Us template loads for /about-us or /about URLs
 */
function rajaskin_about_template_routing( $template ) {
    if ( is_page( 'about-us' ) || is_page( 'about' ) || is_page_template( 'page-about-us.php' ) || is_page_template( 'template-about.php' ) || is_page_template( 'page-about.php' ) ) {
        $located = locate_template( array( 'page-about-us.php', 'template-about.php', 'page-about.php' ) );
        if ( $located ) {
            return $located;
        }
    }

    $req_path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    if ( $req_path === 'about-us' || $req_path === 'about' || preg_match( '#(^|/)about(-us)?$#i', $req_path ) ) {
        global $wp_query;
        if ( isset( $wp_query ) ) {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
        }
        status_header( 200 );
        $located = locate_template( array( 'page-about-us.php', 'template-about.php', 'page-about.php' ) );
        if ( $located ) {
            return $located;
        }
    }

    return $template;
}
add_filter( 'template_include', 'rajaskin_about_template_routing', 99 );

/**
 * Helper to get Order History page URL
 */
function rajaskin_get_order_history_url() {
    $oh_page = get_page_by_path( 'order-history' );
    if ( ! $oh_page ) {
        $oh_page = get_page_by_path( 'orders' );
    }
    if ( $oh_page ) {
        return get_permalink( $oh_page->ID );
    }
    return home_url( '/order-history/' );
}

/**
 * Auto-create Order History page in WordPress database if not exists
 */
function rajaskin_auto_create_order_history_page() {
    if ( get_option( 'rajaskin_order_history_page_created' ) ) {
        return;
    }

    $oh_page = get_page_by_path( 'order-history' );
    if ( ! $oh_page ) {
        $oh_page = get_page_by_path( 'orders' );
    }

    if ( ! $oh_page ) {
        $page_id = wp_insert_post( array(
            'post_title'     => 'Order History',
            'post_name'      => 'order-history',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '',
            'page_template'  => 'page-order-history.php',
        ) );
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_option( 'rajaskin_order_history_page_created', 1 );
            flush_rewrite_rules( false );
        }
    } else {
        update_option( 'rajaskin_order_history_page_created', 1 );
    }
}
add_action( 'init', 'rajaskin_auto_create_order_history_page' );

/**
 * Ensure Order History template loads for /order-history URLs
 */
function rajaskin_order_history_template_routing( $template ) {
    if ( is_page( 'order-history' ) || is_page( 'orders' ) || is_page_template( 'page-order-history.php' ) ) {
        $located = locate_template( array( 'page-order-history.php' ) );
        if ( $located ) {
            return $located;
        }
    }

    $req_path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    if ( $req_path === 'order-history' || $req_path === 'orders' || preg_match( '#(^|/)order-history/?$#i', $req_path ) ) {
        global $wp_query;
        if ( isset( $wp_query ) ) {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
        }
        status_header( 200 );
        $located = locate_template( array( 'page-order-history.php' ) );
        if ( $located ) {
            return $located;
        }
    }

    return $template;
}
add_filter( 'template_include', 'rajaskin_order_history_template_routing', 99 );



