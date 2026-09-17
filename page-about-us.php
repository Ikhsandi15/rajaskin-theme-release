<?php
/**
 * Template Name: About Us
 * Description: Custom About Us page template for RajaSkin
 * Fully customizable via Appearance > Customize > RajaSkin Theme Settings
 */

get_header();

$theme_uri = get_template_directory_uri();
$catalogue_url = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );

// Manifesto & Stats
$manifesto_text = get_theme_mod( 'rajaskin_about_manifesto', 'Rajaskin is a personal care brand committed to build a better future for the earth and humanity. We celebrate self-care with a fresh approach where sustainability, effectiveness, and style meet.' );
$stat1_num      = get_theme_mod( 'rajaskin_about_stat1_num', '100%' );
$stat1_label    = get_theme_mod( 'rajaskin_about_stat1_label', 'Active Formulation' );
$stat2_num      = get_theme_mod( 'rajaskin_about_stat2_num', '0%' );
$stat2_label    = get_theme_mod( 'rajaskin_about_stat2_label', 'Empty Fillers' );
$stat3_num      = get_theme_mod( 'rajaskin_about_stat3_num', '35+' );
$stat3_label    = get_theme_mod( 'rajaskin_about_stat3_label', 'Country Reached' );

// Story 1 (uses banner1 customizer settings or dedicated fallback)
$story1_img   = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( 'rajaskin_banner1_img', 'assets/images/banner_hijab_beauty.jpg' ) : $theme_uri . '/assets/images/banner_hijab_beauty.jpg';
$story1_title = get_theme_mod( 'rajaskin_banner1_title', 'Kecantikan Yang Terukur' );
$story1_desc  = get_theme_mod( 'rajaskin_banner1_desc', 'Kami memadukan bahan organik murni dengan sains klinis, menguji setiap formula untuk efektivitas maksimal dan kesehatan kulit jangka panjang.' );
$story1_btn   = get_theme_mod( 'rajaskin_banner1_btn_text', 'Baca Selengkapnya' );
$story1_url   = get_theme_mod( 'rajaskin_banner1_btn_url' );
if ( empty( $story1_url ) || $story1_url === '#products' ) {
    $story1_url = $catalogue_url;
}

// Story 2 (uses banner2 customizer settings or dedicated fallback)
$story2_img   = function_exists( 'rajaskin_get_image_url' ) ? rajaskin_get_image_url( 'rajaskin_banner2_img', 'assets/images/banner_hands_skin.jpg' ) : $theme_uri . '/assets/images/banner_hands_skin.jpg';
$story2_title = get_theme_mod( 'rajaskin_banner2_title', 'Inovasi Perawatan Kulit' );
$story2_desc  = get_theme_mod( 'rajaskin_banner2_desc', 'Menggunakan teknologi terkini, kami menciptakan produk yang tidak hanya efektif tetapi juga aman untuk semua jenis kulit.' );
$story2_btn   = get_theme_mod( 'rajaskin_banner2_btn_text', 'Baca Selengkapnya' );
$story2_url   = get_theme_mod( 'rajaskin_banner2_btn_url' );
if ( empty( $story2_url ) || $story2_url === '#products' ) {
    $story2_url = $catalogue_url;
}
?>

<div class="rs-about-page">

    <!-- ============================================
         1. BREADCRUMB & PAGE HEADER
    ============================================= -->
    <div class="container">
        <div class="rs-about-header">
            <nav class="rs-about-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                <span class="sep">/</span>
                <span class="current">About Us</span>
            </nav>
            <h1 class="rs-about-title"><?php echo esc_html( get_theme_mod( 'rajaskin_about_title', 'About Us' ) ); ?></h1>
            <p class="rs-about-subtitle"><?php echo esc_html( get_theme_mod( 'rajaskin_about_subtitle', 'Learn more about our story and core values.' ) ); ?></p>
        </div>
    </div>

    <!-- ============================================
         2. MANIFESTO & KEY STATS
    ============================================= -->
    <section class="rs-about-manifesto-section">
        <div class="container">
            <div class="rs-about-manifesto-wrap">
                <p class="rs-about-manifesto-text">
                    <?php echo esc_html( $manifesto_text ); ?>
                </p>
            </div>

            <div class="rs-about-stats-grid">
                <div class="rs-about-stat-item">
                    <span class="rs-stat-number"><?php echo esc_html( $stat1_num ); ?></span>
                    <span class="rs-stat-label"><?php echo esc_html( $stat1_label ); ?></span>
                </div>
                <div class="rs-about-stat-item">
                    <span class="rs-stat-number"><?php echo esc_html( $stat2_num ); ?></span>
                    <span class="rs-stat-label"><?php echo esc_html( $stat2_label ); ?></span>
                </div>
                <div class="rs-about-stat-item">
                    <span class="rs-stat-number"><?php echo esc_html( $stat3_num ); ?></span>
                    <span class="rs-stat-label"><?php echo esc_html( $stat3_label ); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         3. EDITORIAL STORIES (WARM TONE BACKGROUND)
    ============================================= -->
    <section class="rs-about-stories-section">
        <div class="container">
            <!-- Row 1: Image Left, Content Right -->
            <div class="rs-about-story-row rs-story-normal">
                <div class="rs-story-media">
                    <img src="<?php echo esc_url( $story1_img ); ?>" alt="<?php echo esc_attr( $story1_title ); ?>" class="rs-story-img">
                </div>
                <div class="rs-story-content">
                    <h2 class="rs-story-title"><?php echo esc_html( $story1_title ); ?></h2>
                    <p class="rs-story-desc"><?php echo esc_html( $story1_desc ); ?></p>
                    <a href="<?php echo esc_url( $story1_url ); ?>" class="rs-about-pill-btn">
                        <?php echo esc_html( $story1_btn ); ?>
                    </a>
                </div>
            </div>

            <!-- Row 2: Content Left, Image Right -->
            <div class="rs-about-story-row rs-story-reversed">
                <div class="rs-story-content">
                    <h2 class="rs-story-title"><?php echo esc_html( $story2_title ); ?></h2>
                    <p class="rs-story-desc"><?php echo esc_html( $story2_desc ); ?></p>
                    <a href="<?php echo esc_url( $story2_url ); ?>" class="rs-about-pill-btn">
                        <?php echo esc_html( $story2_btn ); ?>
                    </a>
                </div>
                <div class="rs-story-media">
                    <img src="<?php echo esc_url( $story2_img ); ?>" alt="<?php echo esc_attr( $story2_title ); ?>" class="rs-story-img">
                </div>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
