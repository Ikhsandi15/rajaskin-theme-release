<?php
/**
 * Edit address form
 *
 * Overrides WooCommerce default form-edit-address.php template
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'rajaskin-wp-theme' ) : esc_html__( 'Shipping address', 'rajaskin-wp-theme' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
    <?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

    <div class="rs-edit-address-wrap">
        <div class="rs-address-form-header">
            <h2 class="rs-address-form-title"><?php echo esc_html( $page_title ); ?></h2>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="rs-btn-back-addresses">&larr; <?php esc_html_e( 'Back to Addresses', 'rajaskin-wp-theme' ); ?></a>
        </div>

        <form method="post" class="rs-luxury-address-form">
            <div class="woocommerce-address-fields">
                <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

                <div class="woocommerce-address-fields__field-wrapper">
                    <?php
                    foreach ( $address as $key => $field ) {
                        woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
                    }
                    ?>
                </div>

                <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

                <div class="rs-form-submit-row">
                    <button type="submit" class="button rs-btn-primary-save" name="save_address" value="<?php esc_attr_e( 'Save address', 'rajaskin-wp-theme' ); ?>"><?php esc_html_e( 'Save address', 'rajaskin-wp-theme' ); ?></button>
                    <?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
                    <input type="hidden" name="action" value="edit_address" />
                </div>
            </div>
        </form>
    </div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
