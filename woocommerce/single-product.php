<?php
/**
 * RajaSkin Single Product Template
 * Fully dynamic WooCommerce Product Detail / View Page
 *
 * @package RajaSkin
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$theme_uri = get_template_directory_uri();
?>

<div class="rs-single-product-page">

    <?php while ( have_posts() ) : the_post(); ?>
        <?php
        global $product;
        if ( ! is_a( $product, 'WC_Product' ) ) {
            $product = wc_get_product( get_the_ID() );
        }

        $product_id       = $product->get_id();
        $terms            = get_the_terms( $product_id, 'product_cat' );
        $cat_item         = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0] : null;
        $cat_name         = $cat_item ? $cat_item->name : __( 'Skincare', 'rajaskin-wp-theme' );
        $cat_link         = $cat_item ? get_term_link( $cat_item ) : get_permalink( wc_get_page_id( 'shop' ) );
        $is_in_stock      = $product->is_in_stock();
        $is_on_sale       = $product->is_on_sale();
        $is_variable      = $product->is_type( 'variable' );
        $average_rating   = $product->get_average_rating();
        $review_count     = $product->get_review_count();
        $short_desc       = $product->get_short_description();
        $sku              = $product->get_sku();

        // 1. Prepare Gallery Images
        $gallery_images = array();
        if ( has_post_thumbnail() ) {
            $main_img_url = get_the_post_thumbnail_url( $product_id, 'full' );
            $gallery_images[] = $main_img_url;
        } else {
            $main_img_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'full' ) : $theme_uri . '/assets/images/cat_soft_care.jpg';
            $gallery_images[] = $main_img_url;
        }

        $attachment_ids = $product->get_gallery_image_ids();
        if ( ! empty( $attachment_ids ) ) {
            foreach ( $attachment_ids as $att_id ) {
                $att_url = wp_get_attachment_image_url( $att_id, 'full' );
                if ( $att_url && ! in_array( $att_url, $gallery_images, true ) ) {
                    $gallery_images[] = $att_url;
                }
            }
        }
        ?>

        <div class="container">
            <!-- ============================================
                 1. BREADCRUMB
            ============================================= -->
            <nav class="rs-pdp-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'rajaskin-wp-theme' ); ?></a>
                <span class="sep">/</span>
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Catalogue', 'rajaskin-wp-theme' ); ?></a>
                <?php if ( $cat_item ) : ?>
                    <span class="sep">/</span>
                    <a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a>
                <?php endif; ?>
                <span class="sep">/</span>
                <span class="current"><?php the_title(); ?></span>
            </nav>

            <!-- ============================================
                 2. MAIN PRODUCT SECTION (Gallery + Info)
            ============================================= -->
            <section class="rs-pdp-main">
                <div class="rs-pdp-grid">

                    <!-- LEFT: Product Gallery -->
                    <div class="rs-pdp-gallery">
                        <div class="rs-pdp-main-image-wrap">
                            <?php if ( $is_on_sale ) : ?>
                                <span class="rs-pdp-badge-sale"><?php esc_html_e( 'Sale', 'rajaskin-wp-theme' ); ?></span>
                            <?php endif; ?>
                            <img id="rsPdpMainImg" src="<?php echo esc_url( $gallery_images[0] ); ?>" alt="<?php the_title_attribute(); ?>">
                        </div>

                        <!-- Thumbnails Row (Only shown if more than 1 image) -->
                        <?php if ( count( $gallery_images ) > 1 ) : ?>
                            <div class="rs-pdp-thumbnails">
                                <?php foreach ( $gallery_images as $idx => $thumb_url ) : ?>
                                    <button type="button" class="rs-pdp-thumb <?php echo $idx === 0 ? 'active' : ''; ?>" data-img="<?php echo esc_url( $thumb_url ); ?>" aria-label="<?php printf( esc_attr__( 'View Image %d', 'rajaskin-wp-theme' ), $idx + 1 ); ?>">
                                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() . ' - Image ' . ( $idx + 1 ) ); ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- RIGHT: Product Info & Buy Box -->
                    <div class="rs-pdp-info">
                        <?php if ( $cat_item ) : ?>
                            <a href="<?php echo esc_url( $cat_link ); ?>" class="rs-pdp-eyebrow"><?php echo esc_html( $cat_name ); ?></a>
                        <?php endif; ?>

                        <h1 class="rs-pdp-title"><?php the_title(); ?></h1>

                        <!-- Ratings & Stock Bar -->
                        <div class="rs-pdp-meta-bar">
                            <?php if ( wc_review_ratings_enabled() ) : ?>
                                <div class="rs-pdp-rating-summary">
                                    <div class="rs-stars-gold">
                                        <?php
                                        $rating_val = floatval( $average_rating ) ?: 5.0;
                                        for ( $s = 1; $s <= 5; $s++ ) {
                                            echo $s <= round( $rating_val ) ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="rs-rating-score"><?php echo number_format( $rating_val, 1 ); ?></span>
                                    <span class="rs-rating-count">(<?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'rajaskin-wp-theme' ); ?>)</span>
                                </div>
                            <?php endif; ?>

                            <div class="rs-pdp-stock-status <?php echo $is_in_stock ? 'in-stock' : 'out-of-stock'; ?>">
                                <span class="rs-stock-dot"></span>
                                <?php echo $is_in_stock ? esc_html__( 'In Stock', 'rajaskin-wp-theme' ) : esc_html__( 'Out of Stock', 'rajaskin-wp-theme' ); ?>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="rs-pdp-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <!-- Short Description / Excerpt -->
                        <?php if ( ! empty( $short_desc ) ) : ?>
                            <div class="rs-pdp-short-desc">
                                <?php echo wp_kses_post( $short_desc ); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Add to Cart / Form Area -->
                        <?php if ( $is_variable ) : ?>
                            <!-- Variable Product Form (Native WooCommerce Variations) -->
                            <div class="rs-pdp-variable-form-wrap">
                                <?php woocommerce_variable_add_to_cart(); ?>
                            </div>
                        <?php elseif ( $is_in_stock ) : ?>
                            <!-- Simple Product Custom Buy Form -->
                            <form class="rs-pdp-cart-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
                                <div class="rs-pdp-actions-row">
                                    <div class="rs-pdp-qty-wrap">
                                        <button type="button" class="rs-qty-btn minus" id="rsQtyMinus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'rajaskin-wp-theme' ); ?>">−</button>
                                        <input type="number" id="rsQtyInput" name="quantity" class="rs-qty-input" value="1" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : 99 ); ?>">
                                        <button type="button" class="rs-qty-btn plus" id="rsQtyPlus" aria-label="<?php esc_attr_e( 'Increase quantity', 'rajaskin-wp-theme' ); ?>">+</button>
                                    </div>

                                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" class="rs-pdp-add-btn product-quick-add" id="rsAddToCartBtn" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-product_type="<?php echo esc_attr( $product->get_type() ); ?>">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        <span><?php esc_html_e( 'Add to Cart', 'rajaskin-wp-theme' ); ?></span>
                                    </button>
                                </div>
                            </form>
                        <?php else : ?>
                            <div class="rs-pdp-out-of-stock-notice">
                                <button type="button" class="rs-pdp-add-btn disabled" disabled>
                                    <?php esc_html_e( 'Currently Out of Stock', 'rajaskin-wp-theme' ); ?>
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- SKU & Categories Info -->
                        <div class="rs-pdp-meta-footer">
                            <?php if ( $sku ) : ?>
                                <div class="rs-meta-row">
                                    <span class="rs-meta-label"><?php esc_html_e( 'SKU:', 'rajaskin-wp-theme' ); ?></span>
                                    <span class="rs-meta-val"><?php echo esc_html( $sku ); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ( $cat_item ) : ?>
                                <div class="rs-meta-row">
                                    <span class="rs-meta-label"><?php esc_html_e( 'Category:', 'rajaskin-wp-theme' ); ?></span>
                                    <a href="<?php echo esc_url( $cat_link ); ?>" class="rs-meta-link"><?php echo esc_html( $cat_name ); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ============================================
                             Accordion Product Details
                        ============================================= -->
                        <div class="rs-pdp-accordion">
                            <!-- 1. Description -->
                            <div class="rs-accordion-item open">
                                <button type="button" class="rs-accordion-header" aria-expanded="true">
                                    <span><?php esc_html_e( 'Description & Formulation', 'rajaskin-wp-theme' ); ?></span>
                                    <span class="rs-accordion-icon">−</span>
                                </button>
                                <div class="rs-accordion-content" style="display: block;">
                                    <?php
                                    $main_content = get_the_content();
                                    if ( ! empty( $main_content ) ) :
                                        the_content();
                                    else :
                                        ?>
                                        <p><?php esc_html_e( 'Specially formulated with clinical-grade active ingredients and organic botanical extracts to nourish, balance, and strengthen your skin barrier against daily environmental stressors.', 'rajaskin-wp-theme' ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- 2. Additional Info / Attributes (If defined in WooCommerce) -->
                            <?php
                            $attributes = $product->get_attributes();
                            if ( ! empty( $attributes ) || $product->has_weight() || $product->has_dimensions() ) :
                            ?>
                            <div class="rs-accordion-item">
                                <button type="button" class="rs-accordion-header" aria-expanded="false">
                                    <span><?php esc_html_e( 'Product Specifications', 'rajaskin-wp-theme' ); ?></span>
                                    <span class="rs-accordion-icon">+</span>
                                </button>
                                <div class="rs-accordion-content" style="display: none;">
                                    <?php do_action( 'woocommerce_product_additional_information', $product ); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- 3. How to Use & Benefits -->
                            <div class="rs-accordion-item">
                                <button type="button" class="rs-accordion-header" aria-expanded="false">
                                    <span><?php esc_html_e( 'How to Use & Routine', 'rajaskin-wp-theme' ); ?></span>
                                    <span class="rs-accordion-icon">+</span>
                                </button>
                                <div class="rs-accordion-content" style="display: none;">
                                    <ul class="rs-usage-list">
                                        <li><strong>Step 1:</strong> Cleanse skin thoroughly with warm water.</li>
                                        <li><strong>Step 2:</strong> Dispense 2-3 drops or a pea-sized amount onto clean fingertips.</li>
                                        <li><strong>Step 3:</strong> Gently massage into face and neck using upward circular motions.</li>
                                        <li><strong>Step 4:</strong> Apply daily every morning and evening for optimal radiant results.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 4. Guarantee & Ethics -->
                            <div class="rs-accordion-item">
                                <button type="button" class="rs-accordion-header" aria-expanded="false">
                                    <span><?php esc_html_e( 'Cruelty-Free & Sustainability', 'rajaskin-wp-theme' ); ?></span>
                                    <span class="rs-accordion-icon">+</span>
                                </button>
                                <div class="rs-accordion-content" style="display: none;">
                                    <p><?php esc_html_e( 'All RajaSkin products are 100% cruelty-free, vegan-friendly, dermatologist-tested, and housed in eco-conscious recyclable packaging.', 'rajaskin-wp-theme' ); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ============================================
                 3. CUSTOMER REVIEWS SECTION
            ============================================= -->
            <?php
            $product_comments = get_comments( array(
                'post_id' => $product_id,
                'status'  => 'approve',
                'type'    => 'review',
            ) );
            ?>
            <section class="rs-pdp-reviews-section">
                <div class="rs-section-title-wrap">
                    <div>
                        <h2 class="rs-section-heading"><?php esc_html_e( 'Customer Reviews', 'rajaskin-wp-theme' ); ?></h2>
                        <p class="rs-section-subheading">
                            <?php if ( ! empty( $product_comments ) ) : ?>
                                <?php printf( esc_html__( 'Rated %s/5 based on %d real customer reviews', 'rajaskin-wp-theme' ), number_format( $rating_val, 1 ), count( $product_comments ) ); ?>
                            <?php else : ?>
                                <?php esc_html_e( 'See what radiant skincare enthusiasts have to say.', 'rajaskin-wp-theme' ); ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ( ! empty( $product_comments ) && count( $product_comments ) > 2 ) : ?>
                        <div class="rs-slider-nav">
                            <button type="button" class="rs-nav-btn rs-nav-prev" onclick="scrollSlider('reviewSlider', -1)" aria-label="<?php esc_attr_e( 'Previous Reviews', 'rajaskin-wp-theme' ); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                            <button type="button" class="rs-nav-btn rs-nav-next" onclick="scrollSlider('reviewSlider', 1)" aria-label="<?php esc_attr_e( 'Next Reviews', 'rajaskin-wp-theme' ); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="rs-slider-container">
                    <div class="rs-reviews-grid" id="reviewSlider">
                        <?php if ( ! empty( $product_comments ) ) : ?>
                            <?php foreach ( $product_comments as $rev ) :
                                $rev_rating = intval( get_comment_meta( $rev->comment_ID, 'rating', true ) ) ?: 5;
                                ?>
                                <div class="rs-review-card">
                                    <div class="rs-review-stars">
                                        <?php for ( $r = 1; $r <= 5; $r++ ) { echo $r <= $rev_rating ? '★' : '☆'; } ?>
                                    </div>
                                    <p class="rs-review-text">
                                        <?php echo esc_html( $rev->comment_content ); ?>
                                    </p>
                                    <div class="rs-review-meta">
                                        <div class="rs-review-author"><?php echo esc_html( $rev->comment_author ); ?></div>
                                        <div class="rs-review-badge-date">
                                            <span class="rs-review-badge"><?php esc_html_e( 'Verified Buyer', 'rajaskin-wp-theme' ); ?></span>
                                            <span class="rs-review-date"><?php echo esc_html( get_comment_date( 'M d, Y', $rev ) ); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <!-- Clean Demo Reviews when no database reviews yet -->
                            <div class="rs-review-card">
                                <div class="rs-review-stars">★★★★★</div>
                                <p class="rs-review-text">
                                    <?php esc_html_e( 'This formula is lightweight, absorbs seamlessly, and keeps my skin hydrated and visibly balanced all day.', 'rajaskin-wp-theme' ); ?>
                                </p>
                                <div class="rs-review-meta">
                                    <div class="rs-review-author">Sarah Jenkins</div>
                                    <div class="rs-review-badge-date">
                                        <span class="rs-review-badge"><?php esc_html_e( 'Verified Buyer', 'rajaskin-wp-theme' ); ?></span>
                                        <span class="rs-review-date">Oct 12, 2023</span>
                                    </div>
                                </div>
                            </div>

                            <div class="rs-review-card">
                                <div class="rs-review-stars">★★★★★</div>
                                <p class="rs-review-text">
                                    <?php esc_html_e( 'After a week of use, redness reduced noticeably. The clinical actives really make a visible difference.', 'rajaskin-wp-theme' ); ?>
                                </p>
                                <div class="rs-review-meta">
                                    <div class="rs-review-author">Marcus Thorne</div>
                                    <div class="rs-review-badge-date">
                                        <span class="rs-review-badge"><?php esc_html_e( 'Verified Buyer', 'rajaskin-wp-theme' ); ?></span>
                                        <span class="rs-review-date">Oct 08, 2023</span>
                                    </div>
                                </div>
                            </div>

                            <div class="rs-review-card">
                                <div class="rs-review-stars">★★★★★</div>
                                <p class="rs-review-text">
                                    <?php esc_html_e( 'The texture is silky smooth and layers beautifully under sunscreen and daily makeup.', 'rajaskin-wp-theme' ); ?>
                                </p>
                                <div class="rs-review-meta">
                                    <div class="rs-review-author">Elena Rodriguez</div>
                                    <div class="rs-review-badge-date">
                                        <span class="rs-review-badge"><?php esc_html_e( 'Verified Buyer', 'rajaskin-wp-theme' ); ?></span>
                                        <span class="rs-review-date">Sep 28, 2023</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- ============================================
                 4. YOU MAY ALSO LIKE (Dynamic Related Products)
            ============================================= -->
            <?php
            $related_ids = function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $product_id, 4 ) : array();
            
            // If no WooCommerce related IDs, fetch products from same category or newest
            if ( empty( $related_ids ) ) {
                $related_query = new WP_Query( array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => 4,
                    'post__not_in'   => array( $product_id ),
                    'orderby'        => 'rand',
                ) );
            } else {
                $related_query = new WP_Query( array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'post__in'       => $related_ids,
                    'posts_per_page' => 4,
                ) );
            }

            if ( $related_query->have_posts() ) :
            ?>
            <section class="rs-pdp-related-section">
                <div class="rs-section-title-wrap">
                    <h2 class="rs-section-heading"><?php esc_html_e( 'You May Also Like', 'rajaskin-wp-theme' ); ?></h2>
                    <div class="rs-slider-nav">
                        <button type="button" class="rs-nav-btn rs-nav-prev" onclick="scrollSlider('relatedSlider', -1)" aria-label="<?php esc_attr_e( 'Previous Products', 'rajaskin-wp-theme' ); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button type="button" class="rs-nav-btn rs-nav-next" onclick="scrollSlider('relatedSlider', 1)" aria-label="<?php esc_attr_e( 'Next Products', 'rajaskin-wp-theme' ); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>

                <div class="rs-slider-container">
                    <div class="rs-products-grid" id="relatedSlider">
                        <?php
                        while ( $related_query->have_posts() ) : $related_query->the_post();
                            global $product;
                            if ( ! $product ) continue;
                            ?>
                            <div class="rs-cat-prod-card">
                                <div class="rs-cat-prod-img-box">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( $theme_uri . '/assets/images/cat_soft_care.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                    </a>
                                    <button type="button" class="rs-cat-wishlist-btn" aria-label="<?php esc_attr_e( 'Add to Wishlist', 'rajaskin-wp-theme' ); ?>">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    </button>
                                </div>
                                <div class="rs-cat-prod-bottom">
                                    <div class="rs-cat-prod-meta">
                                        <a href="<?php the_permalink(); ?>" class="rs-cat-prod-name"><?php the_title(); ?></a>
                                        <div class="rs-cat-prod-price"><?php echo $product->get_price_html(); ?></div>
                                    </div>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="rs-cat-bag-btn product-quick-add" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_type="<?php echo esc_attr( $product->get_type() ); ?>" aria-label="<?php esc_attr_e( 'Add to Cart', 'rajaskin-wp-theme' ); ?>">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </a>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

        </div>

    <?php endwhile; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Gallery Thumbnails Switcher
    var thumbs = document.querySelectorAll('.rs-pdp-thumb');
    var mainImg = document.getElementById('rsPdpMainImg');
    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            thumbs.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            if (mainImg) {
                mainImg.src = this.getAttribute('data-img');
            }
        });
    });

    // 2. Quantity Plus / Minus
    var minusBtn = document.getElementById('rsQtyMinus');
    var plusBtn  = document.getElementById('rsQtyPlus');
    var qtyInput = document.getElementById('rsQtyInput');

    if (minusBtn && qtyInput) {
        minusBtn.addEventListener('click', function() {
            var val = parseInt(qtyInput.value, 10) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });
    }

    if (plusBtn && qtyInput) {
        plusBtn.addEventListener('click', function() {
            var max = parseInt(qtyInput.getAttribute('max'), 10) || 99;
            var val = parseInt(qtyInput.value, 10) || 1;
            if (val < max) qtyInput.value = val + 1;
        });
    }

    // 3. Accordion Toggle
    var headers = document.querySelectorAll('.rs-accordion-header');
    headers.forEach(function(header) {
        header.addEventListener('click', function() {
            var item = this.closest('.rs-accordion-item');
            var content = item.querySelector('.rs-accordion-content');
            var icon = this.querySelector('.rs-accordion-icon');

            if (item.classList.contains('open')) {
                content.style.display = 'none';
                item.classList.remove('open');
                this.setAttribute('aria-expanded', 'false');
                icon.textContent = '+';
            } else {
                content.style.display = 'block';
                item.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
                icon.textContent = '−';
            }
        });
    });

    // Helper: Update Navbar Cart Badge Counter Instantly
    function updateCartCounterBadge(newCount) {
        var cartLinks = document.querySelectorAll('.cart-link, .header-icon-link.cart-link');
        cartLinks.forEach(function(cartLink) {
            var badge = cartLink.querySelector('.cart-count-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'cart-count-badge';
                cartLink.appendChild(badge);
            }
            badge.textContent = newCount;
            badge.style.display = newCount > 0 ? 'flex' : 'none';
            badge.classList.remove('pulse-badge');
            void badge.offsetWidth; // Trigger reflow for re-animation
            badge.classList.add('pulse-badge');
        });
    }

    // 4. AJAX Add to Cart for Single Product
    var addBtn = document.getElementById('rsAddToCartBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var btn = this;
            var productId = btn.getAttribute('data-product-id') || btn.getAttribute('data-product_id');
            var qtyInput = document.getElementById('rsQtyInput');
            var qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;

            if (!productId || btn.classList.contains('loading')) return;
            btn.classList.add('loading');
            var originalHtml = btn.innerHTML;
            btn.innerHTML = '<span>Adding...</span>';

            // Optimistic update: instantly increment badge
            var currentBadge = document.querySelector('.cart-count-badge');
            var currentCount = currentBadge ? (parseInt(currentBadge.textContent, 10) || 0) : 0;
            updateCartCounterBadge(currentCount + qty);

            if (typeof jQuery !== 'undefined') {
                var ajaxUrl = (typeof komerce_ajax !== 'undefined') ? komerce_ajax.ajax_url : '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    data: {
                        action: 'woocommerce_add_to_cart',
                        product_id: productId,
                        quantity: qty
                    },
                    success: function(res) {
                        btn.classList.remove('loading');
                        btn.classList.add('added');
                        btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Added to Cart!</span>';
                        
                        if (res && res.cart_count !== undefined) {
                            updateCartCounterBadge(res.cart_count);
                        }

                        // Trigger WooCommerce fragment refresh
                        jQuery(document.body).trigger('wc_fragment_refresh');
                        jQuery(document.body).trigger('added_to_cart', [res && res.fragments ? res.fragments : {}, res && res.cart_hash ? res.cart_hash : '', jQuery(btn)]);

                        setTimeout(function() {
                            btn.classList.remove('added');
                            btn.innerHTML = originalHtml;
                        }, 2200);
                    },
                    error: function() {
                        btn.closest('form').submit();
                    }
                });
            } else {
                btn.closest('form').submit();
            }
        });
    }

    // 5. Related Products Quick Add
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('click', '.rs-cat-bag-btn.product-quick-add', function(e) {
            e.preventDefault();
            var $b = jQuery(this);
            var pId = $b.data('product_id');
            if (!pId) return;

            if ($b.hasClass('loading')) return;
            $b.addClass('loading');

            var currentBadge = document.querySelector('.cart-count-badge');
            var currentCount = currentBadge ? (parseInt(currentBadge.textContent, 10) || 0) : 0;
            updateCartCounterBadge(currentCount + 1);

            var ajaxUrl = (typeof komerce_ajax !== 'undefined') ? komerce_ajax.ajax_url : '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
            jQuery.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: pId,
                    quantity: 1
                },
                success: function(res) {
                    $b.removeClass('loading').addClass('added');
                    if (res && res.cart_count !== undefined) {
                        updateCartCounterBadge(res.cart_count);
                    }
                    jQuery(document.body).trigger('wc_fragment_refresh');
                    jQuery(document.body).trigger('added_to_cart', [res && res.fragments ? res.fragments : {}, res && res.cart_hash ? res.cart_hash : '', $b]);
                    setTimeout(function() {
                        $b.removeClass('added');
                    }, 1800);
                },
                error: function() {
                    $b.removeClass('loading');
                }
            });
        });
    }
});

function scrollSlider(id, direction) {
    var slider = document.getElementById(id);
    if (!slider) return;
    var scrollAmount = 320;
    slider.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}
</script>

<?php get_footer(); ?>
