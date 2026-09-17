<?php
/**
 * My Addresses template
 *
 * Overrides WooCommerce default my-address.php template
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing'  => __( 'Billing address', 'rajaskin-wp-theme' ),
            'shipping' => __( 'Shipping address', 'rajaskin-wp-theme' ),
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __( 'Billing address', 'rajaskin-wp-theme' ),
        ),
        $customer_id
    );
}

$oldcol = 1;
$col    = 1;
?>

<div class="rs-my-addresses-wrap">
    <div class="rs-addresses-header">
        <h2 class="rs-addresses-heading"><?php esc_html_e( 'Saved Addresses', 'rajaskin-wp-theme' ); ?></h2>
        <p class="rs-addresses-subtext">
            <?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'rajaskin-wp-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </p>
    </div>

    <?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
        <div class="u-columns woocommerce-Addresses col2-set addresses rs-addresses-grid">
    <?php endif; ?>

    <?php foreach ( $get_addresses as $name => $address_title ) : ?>
        <?php
            $address = wc_get_account_formatted_address( $name );
            $col     = $col * -1;
            $oldcol  = $oldcol * -1;
        ?>

        <div class="u-column<?php echo $col < 0 ? 1 : 2; ?> col-<?php echo $oldcol < 0 ? 1 : 2; ?> woocommerce-Address rs-address-card">
            <header class="woocommerce-Address-title title rs-address-card-header">
                <h3 class="rs-address-type"><?php echo esc_html( $address_title ); ?></h3>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit rs-btn-edit-addr">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span><?php echo $address ? esc_html__( 'Edit', 'rajaskin-wp-theme' ) : esc_html__( 'Add', 'rajaskin-wp-theme' ); ?></span>
                </a>
            </header>
            <address class="rs-formatted-address">
                <?php
                    echo $address ? wp_kses_post( $address ) : esc_html__( 'You have not set up this type of address yet.', 'rajaskin-wp-theme' );
                ?>
            </address>
        </div>

    <?php endforeach; ?>

    <?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
        </div>
    <?php endif; ?>
</div>
