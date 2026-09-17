<?php
/**
 * Front Page Template - RajaSkin
 * Fully customizable via Appearance > Customize > RajaSkin Theme Settings
 */

get_header();

$theme_uri = get_template_directory_uri();
$shop_url  = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );

// Hero Settings
$hero_img      = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( 'rajaskin_hero_image', 'assets/images/hero_glow_banner.png' ) : $theme_uri . '/assets/images/hero_glow_banner.png';
$hero_title    = get_theme_mod( 'rajaskin_hero_title', 'Your Daily Glow Partner' );
$hero_subtitle = get_theme_mod( 'rajaskin_hero_subtitle', 'Clinical results meet organic purity.' );
$hero_btn_text = get_theme_mod( 'rajaskin_hero_btn_text', 'Discover Our Products' );
$hero_btn_url  = get_theme_mod( 'rajaskin_hero_btn_url' ) ?: $shop_url;

// Philosophy & Features
$philo_tag   = get_theme_mod( 'rajaskin_philo_tag', 'Our Mission' );
$philo_text  = get_theme_mod( 'rajaskin_philo_text', 'RajaSkin is a personal care brand committed to build a better future for the earth and humanity. We celebrate self-care with a fresh approach where sustainability, effectiveness, and style meet.' );
$feat1_title = get_theme_mod( 'rajaskin_feat1_title', 'Cruelty-Free' );
$feat1_desc  = get_theme_mod( 'rajaskin_feat1_desc', 'Ethically sourced and cruelty-free for guilt-free beauty.' );
$feat2_title = get_theme_mod( 'rajaskin_feat2_title', 'Clinical Strength' );
$feat2_desc  = get_theme_mod( 'rajaskin_feat2_desc', 'High-potency actives for clear, dermatologist-backed results.' );
$feat3_title = get_theme_mod( 'rajaskin_feat3_title', 'Sustainability' );
$feat3_desc  = get_theme_mod( 'rajaskin_feat3_desc', 'Sustainable packaging and sourcing for a greener planet.' );
?>

<div class="rajaskin-home">

    <!-- ============================================
         1. HERO SECTION
    ============================================= -->
    <section class="rs-hero-section">
        <div class="container">
            <div class="rs-hero-card">
                <div class="rs-hero-bg">
                    <img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" class="rs-hero-img">
                </div>
                <div class="rs-hero-content">
                    <h1 class="rs-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
                    <p class="rs-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
                    <a href="<?php echo esc_url( $hero_btn_url ); ?>" class="rs-hero-btn">
                        <?php echo esc_html( $hero_btn_text ); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         2. BRAND PHILOSOPHY & FEATURES (Customizable Copywriting)
    ============================================= -->
    <section id="philosophy" class="rs-philosophy-section">
        <div class="container">
            <div class="rs-philosophy-header">
                <span class="rs-section-tag"><?php echo esc_html( $philo_tag ); ?></span>
                <p class="rs-philosophy-text">
                    <?php echo esc_html( $philo_text ); ?>
                </p>
            </div>

            <div class="rs-features-grid">
                <div class="rs-feature-card">
                    <div class="rs-feature-icon-box">
                        <?php rajaskin_render_feature_icon( 1 ); ?>
                    </div>
                    <div class="rs-feature-body">
                        <h3 class="rs-feature-title"><?php echo esc_html( $feat1_title ); ?></h3>
                        <p class="rs-feature-desc"><?php echo esc_html( $feat1_desc ); ?></p>
                    </div>
                </div>

                <div class="rs-feature-card">
                    <div class="rs-feature-icon-box">
                        <?php rajaskin_render_feature_icon( 2 ); ?>
                    </div>
                    <div class="rs-feature-body">
                        <h3 class="rs-feature-title"><?php echo esc_html( $feat2_title ); ?></h3>
                        <p class="rs-feature-desc"><?php echo esc_html( $feat2_desc ); ?></p>
                    </div>
                </div>

                <div class="rs-feature-card">
                    <div class="rs-feature-icon-box">
                        <?php rajaskin_render_feature_icon( 3 ); ?>
                    </div>
                    <div class="rs-feature-body">
                        <h3 class="rs-feature-title"><?php echo esc_html( $feat3_title ); ?></h3>
                        <p class="rs-feature-desc"><?php echo esc_html( $feat3_desc ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         3. SHOP BY CATEGORY (Customizable Images & Titles)
    ============================================= -->
    <section class="rs-category-section">
        <div class="container">
            <div class="rs-section-title-wrap">
                <h2 class="rs-section-heading"><?php echo esc_html( get_theme_mod( 'rajaskin_cat_heading', 'Shop by Category' ) ); ?></h2>
            </div>

            <div class="rs-slider-container">
                <div class="rs-category-grid" id="categorySlider">
                    <?php
                    $categories_config = array(
                        1 => array( 'name' => 'Soft Care',      'default_img' => 'assets/images/cat_soft_care.jpg' ),
                        2 => array( 'name' => 'Sun Protection', 'default_img' => 'assets/images/cat_sun_protect.jpg' ),
                        3 => array( 'name' => 'Body Care',      'default_img' => 'assets/images/cat_body_care.jpg' ),
                        4 => array( 'name' => 'Hair Care',      'default_img' => 'assets/images/cat_hair_care.jpg' ),
                    );

                    foreach ( $categories_config as $idx => $cat_item ) :
                        $cat_name = get_theme_mod( "rajaskin_cat_title_{$idx}", $cat_item['name'] );
                        $cat_img  = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( "rajaskin_cat_img_{$idx}", $cat_item['default_img'] ) : $theme_uri . '/' . $cat_item['default_img'];
                        $cat_link = get_theme_mod( "rajaskin_cat_link_{$idx}" ) ?: $shop_url;
                        ?>
                        <a href="<?php echo esc_url( $cat_link ); ?>" class="rs-category-card">
                            <div class="rs-cat-img-box">
                                <img src="<?php echo esc_url( $cat_img ); ?>" alt="<?php echo esc_attr( $cat_name ); ?>">
                            </div>
                            <div class="rs-cat-info">
                                <span class="rs-cat-name"><?php echo esc_html( $cat_name ); ?></span>
                                <span class="rs-cat-arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="rs-slider-nav">
                    <button type="button" class="rs-nav-btn rs-nav-prev" onclick="scrollSlider('categorySlider', -1)" aria-label="Previous Categories">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button type="button" class="rs-nav-btn rs-nav-next" onclick="scrollSlider('categorySlider', 1)" aria-label="Next Categories">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         4. OUR BEST SELLER PRODUCTS (Customizable Heading)
    ============================================= -->
    <?php
    $wc_products_query = new WP_Query( array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( $wc_products_query->have_posts() ) :
    ?>
    <section id="products" class="rs-products-section">
        <div class="container">
            <div class="rs-section-title-wrap">
                <h2 class="rs-section-heading"><?php echo esc_html( get_theme_mod( 'rajaskin_bestseller_heading', 'Our Best Seller Products' ) ); ?></h2>
            </div>

            <div class="rs-slider-container">
                <div class="rs-products-grid" id="productSlider">
                    <?php
                    while ( $wc_products_query->have_posts() ) : $wc_products_query->the_post();
                        global $product;
                        if ( ! $product ) continue;
                        ?>
                        <div class="rs-product-card">
                            <div class="rs-prod-img-box">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( $theme_uri . '/assets/images/cat_soft_care.jpg' ); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </a>
                                <button type="button" class="rs-prod-wishlist" aria-label="Add to wishlist">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                </button>
                            </div>
                            <div class="rs-prod-info">
                                <div class="rs-prod-meta">
                                    <a href="<?php the_permalink(); ?>" class="rs-prod-title"><?php the_title(); ?></a>
                                    <div class="rs-prod-price"><?php echo $product->get_price_html(); ?></div>
                                </div>
                                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="rs-prod-action-btn product-quick-add" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_type="<?php echo esc_attr( $product->get_type() ); ?>" aria-label="Add to Cart">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div class="rs-slider-nav">
                    <button type="button" class="rs-nav-btn rs-nav-prev" onclick="scrollSlider('productSlider', -1)" aria-label="Previous Products">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button type="button" class="rs-nav-btn rs-nav-next" onclick="scrollSlider('productSlider', 1)" aria-label="Next Products">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================
         5. ALTERNATING EDITORIAL BANNERS (Customizable)
    ============================================= -->
    <?php
    // Banner 1 Values
    $b1_img      = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( 'rajaskin_banner1_img', 'assets/images/banner_hijab_beauty.jpg' ) : $theme_uri . '/assets/images/banner_hijab_beauty.jpg';
    $b1_title    = get_theme_mod( 'rajaskin_banner1_title', 'Kecantikan Yang Terukur' );
    $b1_desc     = get_theme_mod( 'rajaskin_banner1_desc', 'Kami memadukan bahan organik murni dengan sains klinis, menguji setiap formula untuk efektivitas maksimal dan kesehatan kulit jangka panjang.' );
    $b1_btn_text = get_theme_mod( 'rajaskin_banner1_btn_text', 'Pelajari Lebih Lanjut' );
    $b1_btn_url  = get_theme_mod( 'rajaskin_banner1_btn_url', '#products' );

    // Banner 2 Values
    $b2_img      = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( 'rajaskin_banner2_img', 'assets/images/banner_hands_skin.jpg' ) : $theme_uri . '/assets/images/banner_hands_skin.jpg';
    $b2_title    = get_theme_mod( 'rajaskin_banner2_title', 'Inovasi Perawatan Kulit' );
    $b2_desc     = get_theme_mod( 'rajaskin_banner2_desc', 'Menggunakan teknologi terkini, kami menciptakan produk yang tidak hanya efektif tetapi juga aman untuk semua jenis kulit.' );
    $b2_btn_text = get_theme_mod( 'rajaskin_banner2_btn_text', 'Temukan Produk Kami' );
    $b2_btn_url  = get_theme_mod( 'rajaskin_banner2_btn_url', '#products' );
    ?>
    <section class="rs-editorial-section">
        <div class="container">
            <!-- Row 1: Image Left, Text Right -->
            <div class="rs-editorial-row rs-row-normal">
                <div class="rs-editorial-media">
                    <img src="<?php echo esc_url( $b1_img ); ?>" alt="<?php echo esc_attr( $b1_title ); ?>" class="rs-editorial-img">
                </div>
                <div class="rs-editorial-content">
                    <h2 class="rs-editorial-title"><?php echo esc_html( $b1_title ); ?></h2>
                    <p class="rs-editorial-desc"><?php echo esc_html( $b1_desc ); ?></p>
                    <a href="<?php echo esc_url( $b1_btn_url ); ?>" class="rs-pill-btn">
                        <?php echo esc_html( $b1_btn_text ); ?>
                    </a>
                </div>
            </div>

            <!-- Row 2: Text Left, Image Right -->
            <div class="rs-editorial-row rs-row-reversed">
                <div class="rs-editorial-content">
                    <h2 class="rs-editorial-title"><?php echo esc_html( $b2_title ); ?></h2>
                    <p class="rs-editorial-desc"><?php echo esc_html( $b2_desc ); ?></p>
                    <a href="<?php echo esc_url( $b2_btn_url ); ?>" class="rs-pill-btn">
                        <?php echo esc_html( $b2_btn_text ); ?>
                    </a>
                </div>
                <div class="rs-editorial-media">
                    <img src="<?php echo esc_url( $b2_img ); ?>" alt="<?php echo esc_attr( $b2_title ); ?>" class="rs-editorial-img">
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         6. JOIN TO OUR COMMUNITY (7 Customizable Arch Images)
    ============================================= -->
    <?php
    $comm_heading  = get_theme_mod( 'rajaskin_community_heading', 'Join to Our Community' );
    $comm_subtitle = get_theme_mod( 'rajaskin_community_subtitle', 'Become a part of RajaSkin community and share your radiant journey.' );

    $comm_fallbacks = array(
        1 => 'assets/images/comm_bathroom_routine.jpg',
        2 => 'assets/images/comm_towel.jpg',
        3 => 'assets/images/comm_black_hijab.jpg',
        4 => 'assets/images/comm_white_hijab.jpg',
        5 => 'assets/images/comm_applying_cream.jpg',
        6 => 'assets/images/banner_hijab_beauty.jpg',
        7 => 'assets/images/comm_turban_hijab.jpg',
    );
    ?>
    <section id="community" class="rs-community-section">
        <div class="container">
            <div class="rs-community-header">
                <h2 class="rs-section-heading"><?php echo esc_html( $comm_heading ); ?></h2>
                <p class="rs-community-subtitle"><?php echo esc_html( $comm_subtitle ); ?></p>
            </div>
        </div>

        <div class="rs-community-gallery">
            <?php for ( $i = 1; $i <= 7; $i++ ) : 
                $img_url = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( "rajaskin_comm_img_{$i}", $comm_fallbacks[$i] ) : $theme_uri . '/' . $comm_fallbacks[$i];
            ?>
                <div class="rs-community-item">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $comm_heading . ' ' . $i ); ?>">
                </div>
            <?php endfor; ?>
        </div>
    </section>

</div>

<script>
function scrollSlider(id, direction) {
    var el = document.getElementById(id);
    if (!el) return;
    var card = el.querySelector('.rs-product-card, .rs-category-card, :first-child');
    var gap = 24;
    var cardWidth = card ? (card.offsetWidth + gap) : 300;
    
    var maxScroll = el.scrollWidth - el.clientWidth;
    if (maxScroll <= 0) return;

    if (direction > 0 && el.scrollLeft >= maxScroll - 10) {
        el.scrollTo({ left: 0, behavior: 'smooth' });
    } else if (direction < 0 && el.scrollLeft <= 10) {
        el.scrollTo({ left: maxScroll, behavior: 'smooth' });
    } else {
        el.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }
}
</script>

<?php get_footer(); ?>
