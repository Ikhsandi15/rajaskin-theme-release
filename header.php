<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header rajaskin-header sticky-header">
    <div class="container header-grid">
        <div class="header-left">
            <nav id="site-navigation" class="main-navigation">
                <?php
                if ( has_nav_menu( 'menu-1' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s header-menu">%3$s</ul>',
                    ) );
                } else {
                    ?>
                    <ul class="header-menu">
                        <li class="<?php echo ( is_front_page() || is_home() ) ? 'current-menu-item' : ''; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                        <li class="<?php echo ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) ? 'current-menu-item' : ''; ?>"><a href="<?php echo esc_url( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>">Catalogue</a></li>
                        <?php
                        $is_about_nav = ( is_page( 'about-us' ) || is_page( 'about' ) || is_page_template( 'page-about-us.php' ) || is_page_template( 'template-about.php' ) || is_page_template( 'page-about.php' ) );
                        if ( ! $is_about_nav ) {
                            $nav_path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
                            if ( $nav_path === 'about-us' || $nav_path === 'about' || preg_match( '#(^|/)about(-us)?$#i', $nav_path ) ) {
                                $is_about_nav = true;
                            }
                        }
                        ?>
                        <li class="<?php echo $is_about_nav ? 'current-menu-item' : ''; ?>"><a href="<?php echo esc_url( function_exists( 'rajaskin_get_about_url' ) ? rajaskin_get_about_url() : home_url( '/about-us/' ) ); ?>">About Us</a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>
            <button class="mobile-menu-toggle" aria-label="Toggle Menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>

        <div class="header-center">
            <?php
            $custom_logo_img = get_theme_mod( 'rajaskin_logo_image' );
            $logo_text       = get_theme_mod( 'rajaskin_logo_text', get_bloginfo( 'name' ) ?: 'RajaSkin' );

            if ( has_custom_logo() ) {
                the_custom_logo();
            } elseif ( ! empty( $custom_logo_img ) ) {
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link" rel="home">
                    <img src="<?php echo esc_url( $custom_logo_img ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>" class="custom-logo site-logo-img">
                </a>
                <?php
            } else {
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-text"><?php echo esc_html( $logo_text ); ?></a>
                <?php
            }
            ?>
        </div>

        <div class="header-right header-icons">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="header-icon-link" aria-label="Search">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </a>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="header-icon-link wishlist-link" aria-label="Wishlist">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </a>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-icon-link cart-link" aria-label="Cart">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <?php
                $cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
                ?>
                <span class="cart-count-badge" style="<?php echo $cart_count > 0 ? '' : 'display:none;'; ?>"><?php echo esc_html( $cart_count ); ?></span>
            </a>
            <!-- Profile / Account Dropdown -->
            <div class="rs-account-dropdown-wrap" id="rsAccountDropdownWrap">
                <button type="button" class="header-icon-link account-link rs-account-trigger" id="rsAccountTrigger" aria-label="Profile Menu" aria-expanded="false" aria-haspopup="true">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </button>

                <div class="rs-account-dropdown" id="rsAccountDropdown">
                    <?php if ( is_user_logged_in() ) : 
                        $current_user = wp_get_current_user();
                        $user_name = $current_user->display_name ?: $current_user->user_login;
                    ?>
                        <div class="rs-dropdown-user-header">
                            <span class="rs-dropdown-greeting">Signed in as</span>
                            <span class="rs-dropdown-username"><?php echo esc_html( $user_name ); ?></span>
                        </div>
                        <div class="rs-dropdown-divider"></div>

                        <ul class="rs-dropdown-menu">
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Profile & Settings</span>
                                </a>
                            </li>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                    <span>Account Details</span>
                                </a>
                            </li>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View Order</span>
                                </a>
                            </li>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>History Order</span>
                                </a>
                            </li>
                            <div class="rs-dropdown-divider"></div>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="rs-dropdown-link logout-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    <span>Log Out</span>
                                </a>
                            </li>
                        </ul>

                    <?php else : ?>

                        <div class="rs-dropdown-user-header">
                            <span class="rs-dropdown-greeting">Welcome to RajaSkin</span>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="rs-dropdown-login-btn">Sign In / Register</a>
                        </div>
                        <div class="rs-dropdown-divider"></div>

                        <ul class="rs-dropdown-menu">
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Profile Settings</span>
                                </a>
                            </li>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View Order</span>
                                </a>
                            </li>
                            <li class="rs-dropdown-item">
                                <a href="<?php echo esc_url( function_exists( 'rajaskin_get_order_history_url' ) ? rajaskin_get_order_history_url() : home_url( '/order-history/' ) ); ?>" class="rs-dropdown-link">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>History Order</span>
                                </a>
                            </li>
                        </ul>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>
</header>

<main id="primary" class="site-main">
