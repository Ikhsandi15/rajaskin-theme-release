<?php
/**
 * My Account page wrapper
 *
 * Overrides WooCommerce default my-account.php template
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="rs-myaccount-page-wrap">
    <div class="container rs-myaccount-container">

        <!-- Breadcrumbs -->
        <nav class="rs-myaccount-breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span class="sep">/</span>
            <span class="current">My Account</span>
        </nav>

        <!-- Page Header Banner -->
        <div class="rs-myaccount-header">
            <h1 class="rs-myaccount-title">My Account</h1>
            <p class="rs-myaccount-subtitle">Manage your personal details, saved addresses, orders, and skincare preferences.</p>
        </div>

        <!-- 2-Column Account Grid -->
        <div class="rs-myaccount-grid">
            <?php
            /**
             * My Account navigation.
             *
             * @since 2.6.0
             */
            do_action( 'woocommerce_account_navigation' );
            ?>

            <div class="woocommerce-MyAccount-content rs-myaccount-main-content">
                <?php
                /**
                 * My Account content.
                 *
                 * @since 2.6.0
                 */
                do_action( 'woocommerce_account_content' );
                ?>
            </div>
        </div>

    </div>
</div>
