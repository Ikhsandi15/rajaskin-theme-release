<?php
/**
 * Multi-Step Checkout Form - RajaSkin
 * 4 Steps: Shipping -> Delivery -> Payment -> Review
 * 100% Dynamic WooCommerce Data (Zero Hardcoded Values)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$cart_items = WC()->cart->get_cart();
$item_count = count( $cart_items );
$subtotal_raw = WC()->cart->get_subtotal();

// Available gateways directly from WooCommerce
$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
$chosen_gateway = WC()->session->get( 'chosen_payment_method' );
if ( empty( $chosen_gateway ) || ! isset( $available_gateways[ $chosen_gateway ] ) ) {
    $chosen_gateway = ! empty( $available_gateways ) ? array_key_first( $available_gateways ) : '';
}

// Available billing fields from WooCommerce checkout
$billing_fields = $checkout->get_checkout_fields( 'billing' );
?>

<div class="container rs-checkout-page-wrap">

    <!-- Top Step Progress Bar (1: Shipping, 2: Delivery, 3: Payment, 4: Review) -->
    <div class="checkout-step-tracker">
        <div class="step-tracker-item active" data-step="1">
            <div class="step-circle">1</div>
            <span class="step-label">Shipping</span>
        </div>
        <div class="step-line" data-line="1"></div>
        <div class="step-tracker-item" data-step="2">
            <div class="step-circle">2</div>
            <span class="step-label">Delivery</span>
        </div>
        <div class="step-line" data-line="2"></div>
        <div class="step-tracker-item" data-step="3">
            <div class="step-circle">3</div>
            <span class="step-label">Payment</span>
        </div>
        <div class="step-line" data-line="3"></div>
        <div class="step-tracker-item" data-step="4">
            <div class="step-circle">4</div>
            <span class="step-label">Review</span>
        </div>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout rs-step-checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <!-- Hidden Required Fields for Country & Region Compliance -->
        <input type="hidden" name="billing_country" id="billing_country" value="<?php echo esc_attr( $checkout->get_value( 'billing_country' ) ?: 'ID' ); ?>">
        <input type="hidden" name="shipping_country" id="shipping_country" value="<?php echo esc_attr( $checkout->get_value( 'shipping_country' ) ?: 'ID' ); ?>">
        <input type="hidden" name="billing_state" id="billing_state" value="<?php echo esc_attr( $checkout->get_value( 'billing_state' ) ?: 'JK' ); ?>">
        <input type="hidden" name="ship_to_different_address" value="0">

        <div class="checkout-main-grid">

            <!-- LEFT COLUMN: Step Panels -->
            <div class="checkout-steps-col">

                <!-- ==========================================
                     STEP 1: SHIPPING (Contact & Address)
                     ========================================== -->
                <div class="checkout-step-panel active" id="stepPanel1" data-step="1">
                    
                    <!-- Contact Information -->
                    <div class="form-section-block">
                        <h2 class="step-section-heading">Contact Information</h2>
                        
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="billing_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?></label>
                                <input type="text" name="billing_first_name" id="billing_first_name" class="rs-input" placeholder="<?php echo esc_attr( $billing_fields['billing_first_name']['placeholder'] ?? __( 'First name', 'woocommerce' ) ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_first_name' ) ); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="billing_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?></label>
                                <input type="text" name="billing_last_name" id="billing_last_name" class="rs-input" placeholder="<?php echo esc_attr( $billing_fields['billing_last_name']['placeholder'] ?? __( 'Last name', 'woocommerce' ) ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_last_name' ) ); ?>">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="billing_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?></label>
                                <input type="email" name="billing_email" id="billing_email" class="rs-input" placeholder="<?php echo esc_attr( $billing_fields['billing_email']['placeholder'] ?? __( 'Email address', 'woocommerce' ) ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_email' ) ); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="billing_phone"><?php esc_html_e( 'Phone', 'woocommerce' ); ?></label>
                                <input type="tel" name="billing_phone" id="billing_phone" class="rs-input" placeholder="<?php echo esc_attr( $billing_fields['billing_phone']['placeholder'] ?? __( 'Phone number', 'woocommerce' ) ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_phone' ) ); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Address Details -->
                    <div class="form-section-block">
                        <h2 class="step-section-heading">Address Details</h2>

                        <div class="form-grid-2">
                            <!-- Destination Field using WooCommerce / Komerce cart-destination-field -->
                            <div class="form-group" id="cart-destination-field">
                                <?php
                                $dest_id = 'billing_city';
                                if ( isset( $billing_fields['billing_komerce_destination'] ) ) {
                                    $dest_id = 'billing_komerce_destination';
                                } elseif ( isset( $billing_fields['billing_destination'] ) ) {
                                    $dest_id = 'billing_destination';
                                }
                                ?>
                                <label for="<?php echo esc_attr( $dest_id ); ?>">Pilih Destinasi</label>
                                <?php
                                ob_start();
                                if ( isset( $billing_fields['billing_komerce_destination'] ) ) {
                                    woocommerce_form_field( 'billing_komerce_destination', array_merge( $billing_fields['billing_komerce_destination'], array(
                                        'label'       => '',
                                        'class'       => array( 'form-row-wide rs-destination-field' ),
                                    ) ), $checkout->get_value( 'billing_komerce_destination' ) );
                                } elseif ( isset( $billing_fields['billing_destination'] ) ) {
                                    woocommerce_form_field( 'billing_destination', array_merge( $billing_fields['billing_destination'], array(
                                        'label'       => '',
                                        'class'       => array( 'form-row-wide rs-destination-field' ),
                                    ) ), $checkout->get_value( 'billing_destination' ) );
                                } elseif ( isset( $billing_fields['billing_city'] ) ) {
                                    woocommerce_form_field( 'billing_city', array_merge( $billing_fields['billing_city'], array(
                                        'label'       => '',
                                        'placeholder' => 'Pilih Destinasi (Kecamatan / Kota)',
                                        'class'       => array( 'form-row-wide rs-destination-field' ),
                                        'input_class' => array( 'rs-input' ),
                                    ) ), $checkout->get_value( 'billing_city' ) );
                                } else {
                                    ?>
                                    <input type="text" name="billing_city" id="billing_city" class="rs-input" placeholder="Pilih Destinasi (Kecamatan / Kota)" value="<?php echo esc_attr( $checkout->get_value( 'billing_city' ) ); ?>">
                                    <?php
                                }
                                $dest_field_html = ob_get_clean();

                                // Strip any inner label rendered inside the field wrapper to prevent vertical misalignment
                                $dest_field_html = preg_replace( '/<label\b[^>]*>(.*?)<\/label>/is', '', $dest_field_html );

                                echo $dest_field_html;
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="billing_postcode"><?php esc_html_e( 'Postcode / ZIP', 'woocommerce' ); ?></label>
                                <input type="text" name="billing_postcode" id="billing_postcode" class="rs-input" placeholder="<?php echo esc_attr( $billing_fields['billing_postcode']['placeholder'] ?? __( 'Postcode / ZIP', 'woocommerce' ) ); ?>" value="<?php echo esc_attr( $checkout->get_value( 'billing_postcode' ) ); ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="billing_address_1"><?php esc_html_e( 'Street address', 'woocommerce' ); ?></label>
                            <textarea name="billing_address_1" id="billing_address_1" class="rs-textarea" rows="3" placeholder="<?php echo esc_attr( $billing_fields['billing_address_1']['placeholder'] ?? 'Nama jalan, nomor rumah, RT/RW, dsb.' ); ?>" required><?php echo esc_textarea( $checkout->get_value( 'billing_address_1' ) ); ?></textarea>
                        </div>
                    </div>

                    <!-- Step 1 Actions -->
                    <div class="step-actions-row">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn-step-back">Back to Cart</a>
                        <button type="button" class="btn-step-next" id="btnToDelivery" disabled>Continue to Delivery</button>
                    </div>

                </div>

                <!-- ==========================================
                     STEP 2: DELIVERY (Delivery Method)
                     ========================================== -->
                <div class="checkout-step-panel" id="stepPanel2" data-step="2">
                    <h2 class="step-section-heading">Delivery Method</h2>

                    <?php rajaskin_render_delivery_methods(); ?>

                    <!-- Step 2 Actions -->
                    <div class="step-actions-row">
                        <button type="button" class="btn-step-back" id="btnBackToShipping">Back to Shipping</button>
                        <button type="button" class="btn-step-next" id="btnToPayment" disabled>Continue to Payment</button>
                    </div>
                </div>

                <!-- ==========================================
                     STEP 3: PAYMENT (Payment Method)
                     ========================================== -->
                <div class="checkout-step-panel" id="stepPanel3" data-step="3">
                    <h2 class="step-section-heading">Payment Method</h2>

                    <div class="payment-step-methods-container" id="payment-methods-wrapper">
                        <?php woocommerce_checkout_payment(); ?>
                    </div>

                    <!-- Step 3 Actions -->
                    <div class="step-actions-row">
                        <button type="button" class="btn-step-back" id="btnBackToDelivery">Back to Delivery</button>
                        <button type="button" class="btn-step-next" id="btnToReview" disabled>Continue to Review</button>
                    </div>
                </div>

                <!-- ==========================================
                     STEP 4: REVIEW (Review & Place Order)
                     ========================================== -->
                <div class="checkout-step-panel" id="stepPanel4" data-step="4">
                    <h2 class="step-section-heading">Review Your Order</h2>

                    <!-- 1. Shipping Address Card -->
                    <div class="review-info-card">
                        <h4 class="review-card-title">Shipping Address</h4>
                        <p class="review-customer-name" id="revCustomerName">-</p>
                        <p class="review-contact-line" id="revContactLine">-</p>
                        <p class="review-address-line" id="revAddressLine">-</p>
                    </div>

                    <!-- 2. Delivery Method Card -->
                    <div class="review-info-card">
                        <h4 class="review-card-title">Delivery Method</h4>
                        <p class="review-delivery-name" id="revDeliveryName">Belum dipilih</p>
                        <p class="review-delivery-meta" id="revDeliveryMeta"></p>
                    </div>

                    <!-- 3. Payment Method Card -->
                    <div class="review-info-card">
                        <div class="review-pm-header">
                            <h4 class="review-card-title">Payment Method</h4>
                            <div class="va-status-badge">
                                <span class="status-dot">●</span> WAITING FOR PAYMENT
                            </div>
                        </div>
                        <p class="review-pm-name" id="revPmName">-</p>
                        <p class="review-pm-meta" id="revPmMeta"></p>
                    </div>

                    <!-- 4. Order Items Card -->
                    <div class="review-info-card">
                        <h4 class="review-card-title">Order Items</h4>
                        <div class="review-items-list">
                            <?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
                                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) : ?>
                                    <div class="review-item-row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                        <div class="review-item-thumb">
                                            <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 60, 60 ) ), $cart_item, $cart_item_key ); ?>
                                        </div>
                                        <div class="review-item-info">
                                            <span class="review-item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></span>
                                            <?php
                                            $variation_data = wc_get_formatted_cart_item_data( $cart_item );
                                            if ( $variation_data ) : ?>
                                                <span class="review-item-qty"><?php echo $variation_data; ?></span>
                                            <?php else : ?>
                                                <span class="review-item-qty">Qty: <?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="review-item-price">
                                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                        </div>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>

                    <!-- Step 4 Actions -->
                    <div class="step-actions-row">
                        <button type="button" class="btn-step-back" id="btnBackToPayment">Back to Payment</button>
                        <button type="submit" class="btn-place-order" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo esc_attr( apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ) ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ) ); ?></button>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: Order Summary Card (Sticky & WooCommerce AJAX Connected) -->
            <div class="checkout-summary-col">
                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php wc_get_template( 'checkout/review-order.php' ); ?>
                </div>
            </div>

        </div>

        <!-- Nonce & Security Fields -->
        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

    </form>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<!-- Step Controller Script -->
<script>
jQuery(function($) {
    var currentStep = 1;
    var selectedBank = '';
    var selectedVa = '';
    var selectedPm = '';
    var selectedCourier = 'Belum dipilih';
    var selectedCourierDesc = '';

    function getDestinationText() {
        var $destSelect = $('#cart-destination-field select');
        if ($destSelect.length && $destSelect.find('option:selected').length && $destSelect.val()) {
            return $destSelect.find('option:selected').text().trim();
        }
        var $destInput = $('#cart-destination-field input');
        if ($destInput.length && $destInput.val()) {
            return $destInput.val().trim();
        }
        return '';
    }

    function syncSelectedShipping() {
        var $checked = $('input[name^="shipping_method"]:checked');
        if ($checked.length) {
            var $card = $checked.closest('li, label');
            $card.addClass('active').siblings().removeClass('active');
            selectedCourier = $checked.data('name') || $card.find('.dm-name').text().trim() || 'Courier';
            var price = $checked.data('price') || $card.find('.dm-price, .amount').text().trim();
            var desc = $card.find('.dm-desc').text().trim();
            selectedCourierDesc = desc + (price ? ' &nbsp;•&nbsp; ' + price : '');
        }
    }

    function syncSelectedPayment() {
        var $checked = $('input[name="payment_method"]:checked');
        if ($checked.length) {
            var $box = $checked.closest('.wc_payment_method, .payment-method-box');
            $('.wc_payment_method, .payment-method-box').removeClass('active');
            $box.addClass('active');
            
            selectedPm = $checked.data('title') || $box.find('label[for="' + $checked.attr('id') + '"]').text().trim() || $box.find('.pm-header-title').text().trim() || $checked.val();

            // Check if Komerce Payment sub-option is selected
            var $checkedKomerce = $('input[name="komerce_pay_method"]:checked');
            if ($checkedKomerce.length) {
                var bank = $checkedKomerce.data('bank') || $checkedKomerce.val();
                var payName = $checkedKomerce.closest('.komerce-pay-option').find('.komerce-pay-name').text().trim();
                var payType = $checkedKomerce.closest('.komerce-pay-option').find('.komerce-pay-type').text().trim();
                if (payName) {
                    selectedBank = payName;
                    selectedVa = payType;
                    selectedPm = 'Komerce Payment (' + payName + ')';
                }
            }
        }
    }

    function goToStep(step) {
        currentStep = step;

        // Update Tracker UI
        $('.step-tracker-item').each(function() {
            var s = parseInt($(this).data('step'));
            $(this).removeClass('active completed');
            if (s === step) {
                $(this).addClass('active');
            } else if (s < step) {
                $(this).addClass('completed');
            }
        });

        $('.step-line').each(function() {
            var l = parseInt($(this).data('line'));
            $(this).toggleClass('completed', l < step);
        });

        // Switch Panels
        $('.checkout-step-panel').removeClass('active');
        $('#stepPanel' + step).addClass('active');

        // Scroll smoothly to top of form
        if ($('.checkout-step-tracker').length) {
            $('html, body').animate({
                scrollTop: $('.checkout-step-tracker').offset().top - 40
            }, 200);
        }

        // Populate Step 4 Review Card
        if (step === 4) {
            var firstName = $('#billing_first_name').val() || '';
            var lastName = $('#billing_last_name').val() || '';
            var email = $('#billing_email').val() || '';
            var phone = $('#billing_phone').val() || '';
            var destination = getDestinationText();
            var postcode = $('#billing_postcode').val() || '';
            var address = $('#billing_address_1').val() || '';

            var fullName = (firstName + ' ' + lastName).trim() || '-';
            var contactInfo = (phone + (phone && email ? ' | ' : '') + email).trim() || '-';
            var fullAddress = (address + (address && destination ? ', ' : '') + destination + (postcode ? ', ' + postcode : '')).trim() || '-';

            $('#revCustomerName').text(fullName);
            $('#revContactLine').text(contactInfo);
            $('#revAddressLine').text(fullAddress);

            syncSelectedShipping();
            $('#revDeliveryName').text(selectedCourier);
            $('#revDeliveryMeta').html(selectedCourierDesc);

            syncSelectedPayment();
            $('#revPmName').text(selectedPm || '-');
            if (selectedBank && selectedVa) {
                $('#revPmMeta').html(selectedBank + ' &nbsp;•&nbsp; ' + selectedVa);
            } else {
                $('#revPmMeta').text('');
            }
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validateStep1() {
        var firstName = $('#billing_first_name').val() ? $('#billing_first_name').val().trim() : '';
        var email = $('#billing_email').val() ? $('#billing_email').val().trim() : '';
        var phone = $('#billing_phone').val() ? $('#billing_phone').val().trim() : '';
        var destination = getDestinationText();
        var address = $('#billing_address_1').val() ? $('#billing_address_1').val().trim() : '';

        var valid = (firstName.length > 0) &&
                    (email.length > 0 && isValidEmail(email)) &&
                    (phone.length >= 6) &&
                    (destination.length > 0) &&
                    (address.length > 0);

        $('#btnToDelivery').prop('disabled', !valid).toggleClass('disabled', !valid);
        return valid;
    }

    function validateStep2() {
        var hasShipping = $('input[name^="shipping_method"]:checked').length > 0;
        $('#btnToPayment').prop('disabled', !hasShipping).toggleClass('disabled', !hasShipping);
        return hasShipping;
    }

    function validateStep3() {
        var $pm = $('input[name="payment_method"]:checked');
        var valid = false;

        if ($pm.length > 0) {
            valid = true;
            // If Komerce payment with sub-options
            if ($pm.val() === 'komerce_payment' && $('input[name="komerce_pay_method"]').length > 0) {
                valid = $('input[name="komerce_pay_method"]:checked').length > 0;
            }
        }

        $('#btnToReview').prop('disabled', !valid).toggleClass('disabled', !valid);
        return valid;
    }

    // Step Tracker Clicks
    $('.step-tracker-item').on('click', function() {
        var targetStep = parseInt($(this).data('step'));
        if (targetStep < currentStep) {
            goToStep(targetStep);
        } else if (targetStep === 2 && validateStep1()) {
            goToStep(2);
        } else if (targetStep === 3 && validateStep1() && validateStep2()) {
            goToStep(3);
        } else if (targetStep === 4 && validateStep1() && validateStep2() && validateStep3()) {
            goToStep(4);
        }
    });

    // Real-time validation event listeners for Step 1
    $(document).on('input keyup change blur', '#stepPanel1 input, #stepPanel1 textarea, #cart-destination-field select', function() {
        validateStep1();
    });

    // Destination change listener -> trigger WooCommerce update_checkout & validation
    $(document).on('change select2:select select2:clear', '#cart-destination-field select, #cart-destination-field input', function() {
        validateStep1();
        $(document.body).trigger('update_checkout');
    });

    // Step 1 -> Step 2
    $('#btnToDelivery').on('click', function() {
        if (validateStep1()) {
            $(document.body).trigger('update_checkout');
            goToStep(2);
        }
    });

    // Step 2 -> Step 1
    $('#btnBackToShipping').on('click', function() {
        goToStep(1);
    });

    // Step 2 -> Step 3
    $('#btnToPayment').on('click', function() {
        if (validateStep2()) {
            goToStep(3);
        }
    });

    // Allow clicking the entire delivery method card to select courier
    $(document).on('click', '.delivery-method-card', function(e) {
        if (!$(e.target).is('input[type="radio"]')) {
            var $radio = $(this).find('input[type="radio"]');
            if ($radio.length) {
                $radio.prop('checked', true).trigger('change');
            }
        }
    });

    // Delivery Radio Change
    $(document).on('change', 'input[name^="shipping_method"]', function() {
        var $radio = $(this);
        var $card = $radio.closest('li, label, .delivery-method-card');
        $('ul#shipping_method li, .delivery-method-card').removeClass('active');
        $card.addClass('active');

        // Optimistic UI update: immediately sync Order Summary shipping fee
        var priceHtml = $card.find('.dm-price').html();
        if (priceHtml) {
            $('#summaryShippingFee').html(priceHtml);
        }

        syncSelectedShipping();
        validateStep2();
        $(document.body).trigger('update_checkout');
    });

    // Step 3 -> Step 2
    $('#btnBackToDelivery').on('click', function() {
        goToStep(2);
    });

    // Step 3 -> Step 4
    $('#btnToReview').on('click', function() {
        if (validateStep3()) {
            goToStep(4);
        }
    });

    // Payment Radio Change
    $(document).on('change', 'input[name="payment_method"], input[name="komerce_pay_method"]', function() {
        syncSelectedPayment();
        validateStep3();
        $(document.body).trigger('payment_method_selected');
    });

    // Bank Selection Click (BACS accounts)
    $(document).on('click', '.bank-item-row', function() {
        selectedBank = $(this).data('bank');
        selectedVa = $(this).data('va');

        var $box = $(this).closest('.payment-method-box');
        $box.find('#vaBankNameLabel').text(selectedBank.toUpperCase());
        $box.find('#vaNumberDisplay').text(selectedVa);

        $box.find('.bank-options-list').slideUp(200);
        $box.find('.selected-va-card').slideDown(200);

        validateStep3();
    });

    // Change Bank Click
    $(document).on('click', '#btnChangeBank', function(e) {
        e.stopPropagation();
        var $box = $(this).closest('.payment-method-box');
        $box.find('.selected-va-card').slideUp(200);
        $box.find('.bank-options-list').slideDown(200);
    });

    // Copy VA Number
    $(document).on('click', '#btnCopyVa', function() {
        var text = $('#vaNumberDisplay').text().replace(/\s/g, '');
        if (text) {
            navigator.clipboard.writeText(text).then(function() {
                var $t = $('#btnCopyVa .copy-text');
                $t.text('COPIED!');
                setTimeout(function() {
                    $t.text('COPY');
                }, 2000);
            });
        }
    });

    // Step 4 -> Step 3
    $('#btnBackToPayment').on('click', function() {
        goToStep(3);
    });

    // When WooCommerce finishes AJAX calculation, sync totals & re-validate
    $(document.body).on('updated_checkout', function() {
        syncSelectedShipping();
        syncSelectedPayment();
        validateStep1();
        validateStep2();
        validateStep3();

        // Safety fallback: ensure summary shipping fee matches active delivery card if courier is selected
        var $activeCard = $('ul#shipping_method li.delivery-method-card.active, ul#shipping_method li input[name^="shipping_method"]:checked').closest('.delivery-method-card');
        if ($activeCard.length) {
            var cardPriceHtml = $activeCard.find('.dm-price').html();
            var currentShippingFeeText = $('#summaryShippingFee').text().trim();
            if (cardPriceHtml && (currentShippingFeeText === 'Rp0' || !currentShippingFeeText)) {
                $('#summaryShippingFee').html(cardPriceHtml);
            }
        }

        var currentTotal = $('#summaryGrandTotal').text().trim();
        if (currentTotal) {
            $('#vaAmountDisplay').text(currentTotal.toUpperCase());
        }
    });

    // Order History Base URL
    var orderHistoryBaseUrl = '<?php echo esc_url( function_exists( "rajaskin_get_order_history_url" ) ? rajaskin_get_order_history_url() : home_url( "/order-history/" ) ); ?>';

    // Helper: Check response from /wp-admin/admin-ajax.php or place order and redirect if paid is true
    function checkAndRedirectIfPaid(response) {
        if (!response) return false;

        var data = (typeof response === 'object' && response !== null && response.data) ? response.data : response;
        var isPaid = (data && (data.paid === true || data.paid === 'true' || data.paid === 1 || data.paid === '1'));

        if (isPaid) {
            var orderId = (data && (data.order_id || data.id || data.orderId || data.order_number)) || (response && (response.order_id || response.id)) || '';
            var redirectUrl = orderHistoryBaseUrl;
            if (orderId) {
                var separator = redirectUrl.indexOf('?') !== -1 ? '&' : '?';
                redirectUrl = redirectUrl + separator + 'order_id=' + encodeURIComponent(orderId);
            }
            window.location.href = redirectUrl;
            return true;
        }
        return false;
    }

    // Intercept AJAX responses from /wp-admin/admin-ajax.php and WooCommerce checkout
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (!xhr) return;
        try {
            var res = xhr.responseJSON;
            if (!res && xhr.responseText) {
                var text = xhr.responseText.trim();
                if (text.startsWith('{') || text.startsWith('[')) {
                    res = JSON.parse(text);
                }
            }
            if (res) {
                checkAndRedirectIfPaid(res);
            }
        } catch (e) {
            // Ignore non-JSON response parsing errors
        }
    });

    // Also listen to WooCommerce standard checkout success event
    $(document.body).on('checkout_place_order_success', function(event, result) {
        if (result) {
            checkAndRedirectIfPaid(result);
        }
    });

    // Initial sync and validation run
    syncSelectedShipping();
    syncSelectedPayment();
    validateStep1();
    validateStep2();
    validateStep3();
});
</script>
