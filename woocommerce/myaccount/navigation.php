<?php
/**
 * My Account navigation sidebar
 *
 * Overrides WooCommerce default navigation.php template
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$display_name = $current_user->display_name ?: $current_user->user_login;
$user_email   = $current_user->user_email;
$initials     = strtoupper( substr( $display_name, 0, 1 ) );

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation rs-myaccount-sidebar" aria-label="<?php esc_html_e( 'Account pages', 'rajaskin-wp-theme' ); ?>">
    
    <!-- User Quick Profile Card in Sidebar -->
    <div class="rs-account-user-card">
        <div class="rs-user-avatar">
            <?php echo esc_html( $initials ); ?>
        </div>
        <div class="rs-user-info">
            <h4 class="rs-user-name"><?php echo esc_html( $display_name ); ?></h4>
            <p class="rs-user-email"><?php echo esc_html( $user_email ); ?></p>
        </div>
    </div>

    <!-- Navigation Menu Items with Icons -->
    <ul class="rs-myaccount-nav-list">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
            $item_classes = wc_get_account_menu_item_classes( $endpoint );
            $is_active    = ( strpos( $item_classes, 'is-active' ) !== false );
            $icon_svg     = '';

            switch ( $endpoint ) {
                case 'dashboard':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
                    break;
                case 'orders':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
                    break;
                case 'order-history':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
                    break;
                case 'edit-address':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                    break;
                case 'edit-account':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                    break;
                case 'customer-logout':
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>';
                    break;
                default:
                    $icon_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                    break;
            }
        ?>
            <li class="<?php echo esc_attr( $item_classes ); ?>">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="rs-nav-link <?php echo $is_active ? 'active' : ''; ?>">
                    <span class="rs-nav-icon"><?php echo $icon_svg; ?></span>
                    <span class="rs-nav-text"><?php echo esc_html( $label ); ?></span>
                    <svg class="rs-nav-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
