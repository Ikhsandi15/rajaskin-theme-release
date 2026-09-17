<?php
/**
 * RajaSkin Catalogue / Product Archive Template
 * Overrides WooCommerce default product archive
 */

get_header();

$theme_uri = get_template_directory_uri();
?>

<div class="rs-catalogue-page">
    <div class="container">

        <!-- ============================================
             1. BREADCRUMB & HEADER
        ============================================= -->
        <div class="rs-catalogue-header">
            <nav class="rs-catalogue-breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                <span class="sep">/</span>
                <span class="current">Catalogue</span>
            </nav>
            <h1 class="rs-catalogue-title"><?php echo esc_html( get_theme_mod( 'rajaskin_catalogue_title', 'Discover Our Collection' ) ); ?></h1>
            <p class="rs-catalogue-subtitle"><?php echo esc_html( get_theme_mod( 'rajaskin_catalogue_subtitle', 'Try clinical formulas that naturally restore and balance your skin.' ) ); ?></p>
        </div>

        <!-- ============================================
             2. MAIN CONTENT (Sidebar + Products)
        ============================================= -->
        <div class="rs-catalogue-layout">

            <!-- SIDEBAR FILTERS -->
            <aside class="rs-catalogue-sidebar" id="catalogueSidebar">

                <!-- 1. Category -->
                <div class="rs-filter-block">
                    <h3 class="rs-filter-heading">Category</h3>
                    <div class="rs-filter-options">
                        <label class="rs-checkbox-label">
                            <input type="checkbox" name="cat_filter" value="all" checked class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">All Products</span>
                        </label>
                        <?php
                        $product_categories = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'exclude'    => get_option( 'default_product_cat' ),
                        ) );

                        if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
                            foreach ( $product_categories as $cat ) :
                                ?>
                                <label class="rs-checkbox-label">
                                    <input type="checkbox" name="cat_filter" value="<?php echo esc_attr( $cat->slug ); ?>" class="rs-custom-checkbox">
                                    <span class="rs-checkbox-custom"></span>
                                    <span class="rs-checkbox-text"><?php echo esc_html( $cat->name ); ?></span>
                                </label>
                                <?php
                            endforeach;
                        else :
                            ?>
                            <label class="rs-checkbox-label">
                                <input type="checkbox" name="cat_filter" value="skincare" class="rs-custom-checkbox">
                                <span class="rs-checkbox-custom"></span>
                                <span class="rs-checkbox-text">Skin Care</span>
                            </label>
                            <label class="rs-checkbox-label">
                                <input type="checkbox" name="cat_filter" value="haircare" class="rs-custom-checkbox">
                                <span class="rs-checkbox-custom"></span>
                                <span class="rs-checkbox-text">Hair Care</span>
                            </label>
                            <label class="rs-checkbox-label">
                                <input type="checkbox" name="cat_filter" value="makeup" class="rs-custom-checkbox">
                                <span class="rs-checkbox-custom"></span>
                                <span class="rs-checkbox-text">Makeup</span>
                            </label>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. Skin Type -->
                <div class="rs-filter-block">
                    <h3 class="rs-filter-heading">Skin Type</h3>
                    <div class="rs-filter-options">
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Combination</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Dry / Dehydrated</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Normal</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Oily / Acne Prone</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Sensitive</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Benefits -->
                <div class="rs-filter-block">
                    <h3 class="rs-filter-heading">Benefits</h3>
                    <div class="rs-filter-options">
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Anti-Aging</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Brightening</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Cleansing</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Dermatologically Tested</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Cooling</span>
                        </label>
                        <label class="rs-checkbox-label">
                            <input type="checkbox" class="rs-custom-checkbox">
                            <span class="rs-checkbox-custom"></span>
                            <span class="rs-checkbox-text">Firming</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Price Slider -->
                <div class="rs-filter-block">
                    <h3 class="rs-filter-heading">Price</h3>
                    <p class="rs-filter-sublabel">Select a price range</p>
                    <div class="rs-price-slider-wrap">
                        <input type="range" min="0" max="250000" step="5000" value="250000" class="rs-price-range" id="priceRangeInput">
                        <div class="rs-price-range-labels">
                            <span>Rp 0</span>
                            <span id="priceRangeVal">Rp 250.000</span>
                        </div>
                    </div>
                </div>

            </aside>

            <!-- RIGHT PRODUCT GRID -->
            <main class="rs-catalogue-main">

                <div class="rs-catalogue-grid">
                    <?php
                    // Dynamic WooCommerce Product Query (fetches all inputted products)
                    $catalogue_query = new WP_Query( array(
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'posts_per_page' => 24,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ) );

                    if ( $catalogue_query->have_posts() ) :
                        while ( $catalogue_query->have_posts() ) : $catalogue_query->the_post();
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
                                    <button type="button" class="rs-cat-wishlist-btn" aria-label="Add to Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    </button>
                                </div>
                                <div class="rs-cat-prod-bottom">
                                    <div class="rs-cat-prod-meta">
                                        <a href="<?php the_permalink(); ?>" class="rs-cat-prod-name"><?php the_title(); ?></a>
                                        <div class="rs-cat-prod-price"><?php echo $product->get_price_html(); ?></div>
                                    </div>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="rs-cat-bag-btn product-quick-add" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_type="<?php echo esc_attr( $product->get_type() ); ?>" aria-label="Add to Cart">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </a>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Graceful Fallback matching screenshot exactly
                        $demo_products = array(
                            array( 'title' => 'Skin Protection', 'price' => 'Rp 170.000', 'image' => $theme_uri . '/assets/images/prod_pink_jar.jpg' ),
                            array( 'title' => 'Skin Protection', 'price' => 'Rp 170.000', 'image' => $theme_uri . '/assets/images/prod_pink_foam.jpg' ),
                            array( 'title' => 'Skin Protection', 'price' => 'Rp 170.000', 'image' => $theme_uri . '/assets/images/prod_cleansing_oil.jpg' ),
                            array( 'title' => 'Hair Nourishment', 'price' => 'Rp 150.000', 'image' => $theme_uri . '/assets/images/cat_soft_care.jpg' ),
                            array( 'title' => 'Moisturizing Cream', 'price' => 'Rp 90.000', 'image' => $theme_uri . '/assets/images/cat_sun_protect.jpg' ),
                            array( 'title' => 'Sunscreen Lotion', 'price' => 'Rp 110.000', 'image' => $theme_uri . '/assets/images/cat_body_care.jpg' ),
                            array( 'title' => 'Moisturizing Shampoo', 'price' => 'Rp 140.000', 'image' => $theme_uri . '/assets/images/cat_hair_care.jpg' ),
                            array( 'title' => 'Anti-Aging Serum', 'price' => 'Rp 200.000', 'image' => $theme_uri . '/assets/images/prod_cleansing_oil.jpg' ),
                            array( 'title' => 'Cleansing Oil', 'price' => 'Rp 80.000', 'image' => $theme_uri . '/assets/images/prod_cleansing_oil.jpg' ),
                        );

                        foreach ( $demo_products as $item ) :
                            ?>
                            <div class="rs-cat-prod-card">
                                <div class="rs-cat-prod-img-box">
                                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">
                                        <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
                                    </a>
                                    <button type="button" class="rs-cat-wishlist-btn" aria-label="Add to Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    </button>
                                </div>
                                <div class="rs-cat-prod-bottom">
                                    <div class="rs-cat-prod-meta">
                                        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="rs-cat-prod-name"><?php echo esc_html( $item['title'] ); ?></a>
                                        <div class="rs-cat-prod-price"><?php echo esc_html( $item['price'] ); ?></div>
                                    </div>
                                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="rs-cat-bag-btn" aria-label="Add to Cart">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </a>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <!-- Pagination if available -->
                <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                <div class="rs-shop-pagination">
                    <?php
                    echo paginate_links( array(
                        'total'   => $wp_query->max_num_pages,
                        'current' => max( 1, get_query_var( 'paged' ) ),
                        'prev_text' => '←',
                        'next_text' => '→',
                    ) );
                    ?>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var rangeInput = document.getElementById('priceRangeInput');
    var rangeVal = document.getElementById('priceRangeVal');
    if (rangeInput && rangeVal) {
        rangeInput.addEventListener('input', function() {
            var val = parseInt(this.value, 10);
            rangeVal.textContent = 'Rp ' + val.toLocaleString('id-ID');
        });
    }
});
</script>

<?php get_footer(); ?>
