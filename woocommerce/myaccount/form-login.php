<?php
/**
 * Login / Registration Form
 *
 * Overrides WooCommerce default form-login.php template
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="rs-myaccount-page-wrap rs-login-page-wrap">
    <div class="container rs-login-container">

        <!-- Breadcrumbs -->
        <nav class="rs-myaccount-breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span class="sep">/</span>
            <span class="current">Sign In</span>
        </nav>

        <!-- Header -->
        <div class="rs-myaccount-header rs-login-header">
            <h1 class="rs-myaccount-title"><?php esc_html_e( 'Welcome to RajaSkin', 'rajaskin-wp-theme' ); ?></h1>
            <p class="rs-myaccount-subtitle"><?php esc_html_e( 'Sign in to access your order history, live tracking, and saved skincare preferences.', 'rajaskin-wp-theme' ); ?></p>
        </div>

        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
            <div class="u-columns col2-set rs-login-grid" id="customer_login">
                <div class="u-column1 col-1 rs-login-col">
        <?php else : ?>
            <div class="rs-login-single-wrap" id="customer_login">
                <div class="rs-login-card">
        <?php endif; ?>

            <div class="rs-auth-card">
                <div class="rs-auth-card-header">
                    <h2 class="rs-auth-title"><?php esc_html_e( 'Sign In', 'rajaskin-wp-theme' ); ?></h2>
                    <p class="rs-auth-desc"><?php esc_html_e( 'Enter your email and password to log into your account.', 'rajaskin-wp-theme' ); ?></p>
                </div>

                <form class="woocommerce-form woocommerce-form-login login rs-auth-form" method="post">
                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                        <label for="username"><?php esc_html_e( 'Username or email address', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="name@example.com" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                        <label for="password"><?php esc_html_e( 'Password', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                        <input class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" />
                    </p>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <div class="rs-login-options-row">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme rs-checkbox-label">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                            <span><?php esc_html_e( 'Remember me', 'rajaskin-wp-theme' ); ?></span>
                        </label>
                        <p class="woocommerce-LostPassword lost_password rs-lost-pw">
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'rajaskin-wp-theme' ); ?></a>
                        </p>
                    </div>

                    <div class="rs-auth-submit-wrap">
                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit rs-btn-auth-primary" name="login" value="<?php esc_attr_e( 'Sign In', 'rajaskin-wp-theme' ); ?>"><?php esc_html_e( 'Sign In', 'rajaskin-wp-theme' ); ?></button>
                    </div>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>
                </form>
            </div>

        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
            </div>

            <div class="u-column2 col-2 rs-login-col">
                <div class="rs-auth-card">
                    <div class="rs-auth-card-header">
                        <h2 class="rs-auth-title"><?php esc_html_e( 'Register', 'rajaskin-wp-theme' ); ?></h2>
                        <p class="rs-auth-desc"><?php esc_html_e( 'Create a new account for faster checkouts and exclusive rewards.', 'rajaskin-wp-theme' ); ?></p>
                    </div>

                    <form method="post" class="woocommerce-form woocommerce-form-register register rs-auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                                <label for="reg_username"><?php esc_html_e( 'Username', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
                            </p>
                        <?php endif; ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                            <label for="reg_email"><?php esc_html_e( 'Email address', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="name@example.com" />
                        </p>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide rs-input-group">
                                <label for="reg_password"><?php esc_html_e( 'Password', 'rajaskin-wp-theme' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text rs-text-input" name="password" id="reg_password" autocomplete="new-password" placeholder="••••••••" />
                            </p>
                        <?php else : ?>
                            <p class="rs-reg-privacy-note"><?php esc_html_e( 'A temporary password will be sent to your email address.', 'rajaskin-wp-theme' ); ?></p>
                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <div class="rs-auth-submit-wrap">
                            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit rs-btn-auth-primary" name="register" value="<?php esc_attr_e( 'Create Account', 'rajaskin-wp-theme' ); ?>"><?php esc_html_e( 'Create Account', 'rajaskin-wp-theme' ); ?></button>
                        </div>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>
                    </form>
                </div>
            </div>
        </div>
        <?php else : ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
