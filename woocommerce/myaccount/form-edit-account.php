<?php
/**
 * Edit account form
 *
 * Overrides WooCommerce default form-edit-account.php template
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="rs-edit-account-wrap">
    <div class="rs-account-form-header">
        <h2 class="rs-account-form-title"><?php esc_html_e( 'Account Details', 'rajaskin-wp-theme' ); ?></h2>
        <p class="rs-account-form-subtext"><?php esc_html_e( 'Keep your personal information and login security updated.', 'rajaskin-wp-theme' ); ?></p>
    </div>

    <form class="woocommerce-EditAccountForm edit-account rs-luxury-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

        <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

        <!-- Basic Information -->
        <div class="rs-form-section">
            <h3 class="rs-form-section-title"><?php esc_html_e( 'Personal Information', 'rajaskin-wp-theme' ); ?></h3>

            <div class="rs-form-row-group">
                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first rs-input-group">
                    <label for="account_first_name"><?php esc_html_e( 'First name', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last rs-input-group">
                    <label for="account_last_name"><?php esc_html_e( 'Last name', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
                </p>
            </div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                <label for="account_display_name"><?php esc_html_e( 'Display name', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
                <span class="rs-input-hint"><?php esc_html_e( 'This will be how your name is displayed in your account and in product reviews.', 'rajaskin-wp-theme' ); ?></span>
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                <label for="account_email"><?php esc_html_e( 'Email address', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--email input-text rs-text-input" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
            </p>
        </div>

        <!-- Password Change Section -->
        <fieldset class="rs-form-section rs-password-section">
            <legend class="rs-form-section-title"><?php esc_html_e( 'Password Change', 'rajaskin-wp-theme' ); ?></legend>
            <p class="rs-section-hint"><?php esc_html_e( 'Leave blank to keep your current password unchanged.', 'rajaskin-wp-theme' ); ?></p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                <label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'rajaskin-wp-theme' ); ?></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text rs-text-input" name="password_current" id="password_current" autocomplete="current-password" />
            </p>

            <div class="rs-form-row-group">
                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first rs-input-group">
                    <label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'rajaskin-wp-theme' ); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text rs-text-input" name="password_1" id="password_1" autocomplete="new-password" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last rs-input-group">
                    <label for="password_2"><?php esc_html_e( 'Confirm new password', 'rajaskin-wp-theme' ); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text rs-text-input" name="password_2" id="password_2" autocomplete="new-password" />
                </p>
            </div>
        </fieldset>

        <?php do_action( 'woocommerce_edit_account_form' ); ?>

        <div class="rs-form-submit-row">
            <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
            <button type="submit" class="woocommerce-Button button rs-btn-primary-save" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'rajaskin-wp-theme' ); ?>"><?php esc_html_e( 'Save changes', 'rajaskin-wp-theme' ); ?></button>
            <input type="hidden" name="action" value="save_account_details" />
        </div>

        <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
    </form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
