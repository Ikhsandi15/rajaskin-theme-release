<?php
/**
 * RajaSkin Theme Customizer Settings
 * Adds full customization for all static images, texts, copywriting, and sections
 * via Appearance > Customize > RajaSkin Theme Settings with instant live preview.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helper function to retrieve customizable image URL with automatic fallback to theme assets
 *
 * @param string $mod_key The theme_mod setting key
 * @param string $default_rel_path Relative path from theme root (e.g. 'assets/images/hero_glow_banner.png')
 * @return string Image URL
 */
function rajaskin_get_image_url( $mod_key, $default_rel_path = '' ) {
    $custom_img = get_theme_mod( $mod_key );
    if ( ! empty( $custom_img ) ) {
        return esc_url( $custom_img );
    }
    if ( ! empty( $default_rel_path ) ) {
        return esc_url( get_template_directory_uri() . '/' . ltrim( $default_rel_path, '/' ) );
    }
    return '';
}

/**
 * Helper function to render feature icons (supports custom image/SVG upload, raw SVG, or default SVGs)
 *
 * @param int $index Feature index (1, 2, 3)
 */
function rajaskin_render_feature_icon( $index ) {
    $img_url = get_theme_mod( "rajaskin_feat{$index}_icon_img" );
    $raw_svg = get_theme_mod( "rajaskin_feat{$index}_icon_svg" );

    if ( ! empty( $img_url ) ) {
        echo '<img src="' . esc_url( $img_url ) . '" alt="' . esc_attr( get_theme_mod( "rajaskin_feat{$index}_title", "Feature {$index}" ) ) . '" class="rs-feature-icon-img">';
        return;
    }

    if ( ! empty( $raw_svg ) ) {
        echo wp_kses( $raw_svg, array(
            'svg'   => array( 'class' => true, 'id' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'xmlns' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ),
            'g'     => array( 'fill' => true, 'stroke' => true ),
            'path'  => array( 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ),
            'circle'=> array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true ),
            'line'  => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true ),
            'rect'  => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true ),
            'polygon'=> array( 'points' => true, 'fill' => true, 'stroke' => true ),
            'polyline'=> array( 'points' => true, 'fill' => true, 'stroke' => true ),
        ) );
        return;
    }

    // Default SVGs matching design
    if ( $index === 1 ) {
        echo '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/></svg>';
    } elseif ( $index === 2 ) {
        echo '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>';
    } elseif ( $index === 3 ) {
        echo '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.5-2.8L17.5 11"/><path d="m16 16 3 3-3 3"/><path d="M16.5 6.5 12 2 7.5 6.5"/><path d="M12 2v10"/></svg>';
    }
}

/**
 * Register RajaSkin Customizer Panels, Sections, Settings & Controls
 */
function rajaskin_customize_register( $wp_customize ) {

    // ===================================================
    // MAIN PANEL: RajaSkin Theme Settings
    // ===================================================
    $wp_customize->add_panel( 'rajaskin_theme_panel', array(
        'title'       => __( 'RajaSkin Theme Settings', 'rajaskin-wp-theme' ),
        'description' => __( 'Customize all copywriting, banners, images, categories, and typography across RajaSkin.', 'rajaskin-wp-theme' ),
        'priority'    => 25,
    ) );

    // ---------------------------------------------------
    // 1. SECTION: Header & Branding
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_header_section', array(
        'title'       => __( 'Header & Logo', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 10,
        'description' => __( 'Upload your brand logo image or configure brand text.', 'rajaskin-wp-theme' ),
    ) );

    // Custom Logo Image
    $wp_customize->add_setting( 'rajaskin_logo_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_logo_image', array(
        'label'       => __( 'Custom Header Logo Image', 'rajaskin-wp-theme' ),
        'description' => __( 'Upload your logo image (PNG/SVG recommended, max height ~40px). If empty, text logo is used.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_header_section',
    ) ) );

    // Logo Text Fallback
    $wp_customize->add_setting( 'rajaskin_logo_text', array(
        'default'           => 'RajaSkin',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_logo_text', array(
        'label'       => __( 'Brand Name / Text Logo', 'rajaskin-wp-theme' ),
        'description' => __( 'Used when no logo image is uploaded.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_header_section',
        'type'        => 'text',
    ) );

    // ---------------------------------------------------
    // 2. SECTION: Homepage Hero Section
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_hero_section', array(
        'title'       => __( 'Homepage - Hero Section', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 20,
        'description' => __( 'Customize the top hero banner copywriting and background image.', 'rajaskin-wp-theme' ),
    ) );

    // Hero Image
    $wp_customize->add_setting( 'rajaskin_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_hero_image', array(
        'label'       => __( 'Hero Banner Image', 'rajaskin-wp-theme' ),
        'description' => __( 'Recommended: 1400x800px or larger. Scaled automatically with object-fit: cover.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_hero_section',
    ) ) );

    // Hero Title
    $wp_customize->add_setting( 'rajaskin_hero_title', array(
        'default'           => 'Your Daily Glow Partner',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_hero_title', array(
        'label'   => __( 'Hero Title', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_hero_section',
        'type'    => 'text',
    ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'rajaskin_hero_subtitle', array(
        'default'           => 'Clinical results meet organic purity.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_hero_subtitle', array(
        'label'   => __( 'Hero Subtitle', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_hero_section',
        'type'    => 'text',
    ) );

    // Hero Button Text
    $wp_customize->add_setting( 'rajaskin_hero_btn_text', array(
        'default'           => 'Discover Our Products',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_hero_btn_text', array(
        'label'   => __( 'Hero Button Text', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_hero_section',
        'type'    => 'text',
    ) );

    // Hero Button URL
    $wp_customize->add_setting( 'rajaskin_hero_btn_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_hero_btn_url', array(
        'label'       => __( 'Hero Button URL (Leave empty for Shop page)', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_hero_section',
        'type'        => 'url',
    ) );

    // ---------------------------------------------------
    // 3. SECTION: Homepage Brand Philosophy & Features
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_philosophy_section', array(
        'title'       => __( 'Homepage - Philosophy & Features', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 25,
        'description' => __( 'Customize the mission statement and the 3 brand feature cards.', 'rajaskin-wp-theme' ),
    ) );

    // Philosophy Tag
    $wp_customize->add_setting( 'rajaskin_philo_tag', array(
        'default'           => 'Our Mission',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_philo_tag', array(
        'label'   => __( 'Mission Eyebrow Tag', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_philosophy_section',
        'type'    => 'text',
    ) );

    // Philosophy Text
    $wp_customize->add_setting( 'rajaskin_philo_text', array(
        'default'           => 'RajaSkin is a personal care brand committed to build a better future for the earth and humanity. We celebrate self-care with a fresh approach where sustainability, effectiveness, and style meet.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_philo_text', array(
        'label'   => __( 'Mission / Manifesto Copywriting', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_philosophy_section',
        'type'    => 'textarea',
    ) );

    // Feature 1
    $wp_customize->add_setting( 'rajaskin_feat1_icon_img', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_feat1_icon_img', array(
        'label'       => __( 'Feature 1 Icon Image / SVG', 'rajaskin-wp-theme' ),
        'description' => __( 'Upload custom SVG or PNG icon (e.g. 48x48px). If empty, default SVG icon is used.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
    ) ) );
    $wp_customize->add_setting( 'rajaskin_feat1_icon_svg', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat1_icon_svg', array(
        'label'       => __( 'Feature 1 Raw SVG Code (Optional)', 'rajaskin-wp-theme' ),
        'description' => __( 'Paste custom <svg>...</svg> code if you do not want to upload a file.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
        'type'        => 'textarea',
    ) );
    $wp_customize->add_setting( 'rajaskin_feat1_title', array( 'default' => 'Cruelty-Free', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat1_title', array( 'label' => __( 'Feature 1 Title', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_feat1_desc', array( 'default' => 'Ethically sourced and cruelty-free for guilt-free beauty.', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat1_desc', array( 'label' => __( 'Feature 1 Description', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );

    // Feature 2
    $wp_customize->add_setting( 'rajaskin_feat2_icon_img', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_feat2_icon_img', array(
        'label'       => __( 'Feature 2 Icon Image / SVG', 'rajaskin-wp-theme' ),
        'description' => __( 'Upload custom SVG or PNG icon (e.g. 48x48px). If empty, default SVG icon is used.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
    ) ) );
    $wp_customize->add_setting( 'rajaskin_feat2_icon_svg', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat2_icon_svg', array(
        'label'       => __( 'Feature 2 Raw SVG Code (Optional)', 'rajaskin-wp-theme' ),
        'description' => __( 'Paste custom <svg>...</svg> code if you do not want to upload a file.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
        'type'        => 'textarea',
    ) );
    $wp_customize->add_setting( 'rajaskin_feat2_title', array( 'default' => 'Clinical Strength', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat2_title', array( 'label' => __( 'Feature 2 Title', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_feat2_desc', array( 'default' => 'High-potency actives for clear, dermatologist-backed results.', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat2_desc', array( 'label' => __( 'Feature 2 Description', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );

    // Feature 3
    $wp_customize->add_setting( 'rajaskin_feat3_icon_img', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_feat3_icon_img', array(
        'label'       => __( 'Feature 3 Icon Image / SVG', 'rajaskin-wp-theme' ),
        'description' => __( 'Upload custom SVG or PNG icon (e.g. 48x48px). If empty, default SVG icon is used.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
    ) ) );
    $wp_customize->add_setting( 'rajaskin_feat3_icon_svg', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat3_icon_svg', array(
        'label'       => __( 'Feature 3 Raw SVG Code (Optional)', 'rajaskin-wp-theme' ),
        'description' => __( 'Paste custom <svg>...</svg> code if you do not want to upload a file.', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_philosophy_section',
        'type'        => 'textarea',
    ) );
    $wp_customize->add_setting( 'rajaskin_feat3_title', array( 'default' => 'Sustainability', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat3_title', array( 'label' => __( 'Feature 3 Title', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_feat3_desc', array( 'default' => 'Sustainable packaging and sourcing for a greener planet.', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_feat3_desc', array( 'label' => __( 'Feature 3 Description', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_philosophy_section', 'type' => 'text' ) );

    // ---------------------------------------------------
    // 4. SECTION: Shop by Category
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_categories_section', array(
        'title'       => __( 'Homepage - Shop by Category', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 30,
        'description' => __( 'Customize images, titles, and links for the 4 featured categories.', 'rajaskin-wp-theme' ),
    ) );

    $wp_customize->add_setting( 'rajaskin_cat_heading', array(
        'default'           => 'Shop by Category',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_cat_heading', array(
        'label'   => __( 'Category Section Heading', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_categories_section',
        'type'    => 'text',
    ) );

    $default_cats = array(
        1 => array( 'name' => 'Soft Care',       'img' => 'assets/images/cat_soft_care.jpg' ),
        2 => array( 'name' => 'Sun Protection',  'img' => 'assets/images/cat_sun_protect.jpg' ),
        3 => array( 'name' => 'Body Care',       'img' => 'assets/images/cat_body_care.jpg' ),
        4 => array( 'name' => 'Hair Care',       'img' => 'assets/images/cat_hair_care.jpg' ),
    );

    foreach ( $default_cats as $idx => $cat_data ) {
        // Category Image
        $wp_customize->add_setting( "rajaskin_cat_img_{$idx}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "rajaskin_cat_img_{$idx}", array(
            'label'       => sprintf( __( 'Category %d Image (%s)', 'rajaskin-wp-theme' ), $idx, $cat_data['name'] ),
            'description' => __( 'Recommended: Square 1:1 ratio image (e.g. 600x600px).', 'rajaskin-wp-theme' ),
            'section'     => 'rajaskin_categories_section',
        ) ) );

        // Category Title
        $wp_customize->add_setting( "rajaskin_cat_title_{$idx}", array(
            'default'           => $cat_data['name'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( "rajaskin_cat_title_{$idx}", array(
            'label'   => sprintf( __( 'Category %d Title', 'rajaskin-wp-theme' ), $idx ),
            'section' => 'rajaskin_categories_section',
            'type'    => 'text',
        ) );

        // Category Link
        $wp_customize->add_setting( "rajaskin_cat_link_{$idx}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( "rajaskin_cat_link_{$idx}", array(
            'label'       => sprintf( __( 'Category %d Link (Optional)', 'rajaskin-wp-theme' ), $idx ),
            'section'     => 'rajaskin_categories_section',
            'type'        => 'url',
        ) );
    }

    // ---------------------------------------------------
    // 5. SECTION: Homepage Best Seller Products
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_bestseller_section', array(
        'title'       => __( 'Homepage - Best Sellers', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 35,
        'description' => __( 'Customize heading for the best seller products slider.', 'rajaskin-wp-theme' ),
    ) );

    $wp_customize->add_setting( 'rajaskin_bestseller_heading', array(
        'default'           => 'Our Best Seller Products',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_bestseller_heading', array(
        'label'   => __( 'Best Sellers Heading', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_bestseller_section',
        'type'    => 'text',
    ) );

    // ---------------------------------------------------
    // 6. SECTION: Editorial Feature Banners
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_editorial_section', array(
        'title'       => __( 'Editorial Feature Banners', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 40,
        'description' => __( 'Customize the 2 alternating editorial story banners and copywriting.', 'rajaskin-wp-theme' ),
    ) );

    // Banner 1: Image
    $wp_customize->add_setting( 'rajaskin_banner1_img', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_banner1_img', array(
        'label'       => __( 'Banner 1 Image (Kecantikan Yang Terukur)', 'rajaskin-wp-theme' ),
        'description' => __( 'Recommended: Square 1:1 image (e.g. 800x800px).', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_editorial_section',
    ) ) );

    // Banner 1: Title
    $wp_customize->add_setting( 'rajaskin_banner1_title', array(
        'default'           => 'Kecantikan Yang Terukur',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner1_title', array(
        'label'   => __( 'Banner 1 Title', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // Banner 1: Description
    $wp_customize->add_setting( 'rajaskin_banner1_desc', array(
        'default'           => 'Kami memadukan bahan organik murni dengan sains klinis, menguji setiap formula untuk efektivitas maksimal dan kesehatan kulit jangka panjang.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner1_desc', array(
        'label'   => __( 'Banner 1 Description', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'textarea',
    ) );

    // Banner 1: Button Text
    $wp_customize->add_setting( 'rajaskin_banner1_btn_text', array(
        'default'           => 'Pelajari Lebih Lanjut',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner1_btn_text', array(
        'label'   => __( 'Banner 1 Button Text', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // Banner 1: Button Link
    $wp_customize->add_setting( 'rajaskin_banner1_btn_url', array(
        'default'           => '#products',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner1_btn_url', array(
        'label'   => __( 'Banner 1 Button Link URL', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // Banner 2: Image
    $wp_customize->add_setting( 'rajaskin_banner2_img', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rajaskin_banner2_img', array(
        'label'       => __( 'Banner 2 Image (Inovasi Perawatan Kulit)', 'rajaskin-wp-theme' ),
        'description' => __( 'Recommended: Square 1:1 image (e.g. 800x800px).', 'rajaskin-wp-theme' ),
        'section'     => 'rajaskin_editorial_section',
    ) ) );

    // Banner 2: Title
    $wp_customize->add_setting( 'rajaskin_banner2_title', array(
        'default'           => 'Inovasi Perawatan Kulit',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner2_title', array(
        'label'   => __( 'Banner 2 Title', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // Banner 2: Description
    $wp_customize->add_setting( 'rajaskin_banner2_desc', array(
        'default'           => 'Menggunakan teknologi terkini, kami menciptakan produk yang tidak hanya efektif tetapi juga aman untuk semua jenis kulit.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner2_desc', array(
        'label'   => __( 'Banner 2 Description', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'textarea',
    ) );

    // Banner 2: Button Text
    $wp_customize->add_setting( 'rajaskin_banner2_btn_text', array(
        'default'           => 'Temukan Produk Kami',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner2_btn_text', array(
        'label'   => __( 'Banner 2 Button Text', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // Banner 2: Button Link
    $wp_customize->add_setting( 'rajaskin_banner2_btn_url', array(
        'default'           => '#products',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_banner2_btn_url', array(
        'label'   => __( 'Banner 2 Button Link URL', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_editorial_section',
        'type'    => 'text',
    ) );

    // ---------------------------------------------------
    // 7. SECTION: Community Gallery (7 Images & Copywriting)
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_community_section', array(
        'title'       => __( 'Homepage - Community Gallery', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 50,
        'description' => __( 'Customize the copywriting and 7 curved arch images displayed in the community gallery.', 'rajaskin-wp-theme' ),
    ) );

    // Community Heading
    $wp_customize->add_setting( 'rajaskin_community_heading', array(
        'default'           => 'Join to Our Community',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_community_heading', array(
        'label'   => __( 'Community Section Heading', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_community_section',
        'type'    => 'text',
    ) );

    // Community Subtitle
    $wp_customize->add_setting( 'rajaskin_community_subtitle', array(
        'default'           => 'Become a part of RajaSkin community and share your radiant journey.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_community_subtitle', array(
        'label'   => __( 'Community Section Subtitle', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_community_section',
        'type'    => 'text',
    ) );

    $default_comm_images = array(
        1 => array( 'label' => 'Gallery Image 1 (Outer Left)',  'img' => 'assets/images/comm_bathroom_routine.jpg' ),
        2 => array( 'label' => 'Gallery Image 2 (Mid Left)',    'img' => 'assets/images/comm_towel.jpg' ),
        3 => array( 'label' => 'Gallery Image 3 (Inner Left)',  'img' => 'assets/images/comm_black_hijab.jpg' ),
        4 => array( 'label' => 'Gallery Image 4 (Center Peak)', 'img' => 'assets/images/comm_white_hijab.jpg' ),
        5 => array( 'label' => 'Gallery Image 5 (Inner Right)', 'img' => 'assets/images/comm_applying_cream.jpg' ),
        6 => array( 'label' => 'Gallery Image 6 (Mid Right)',   'img' => 'assets/images/banner_hijab_beauty.jpg' ),
        7 => array( 'label' => 'Gallery Image 7 (Outer Right)', 'img' => 'assets/images/comm_turban_hijab.jpg' ),
    );

    foreach ( $default_comm_images as $idx => $comm_data ) {
        $wp_customize->add_setting( "rajaskin_comm_img_{$idx}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "rajaskin_comm_img_{$idx}", array(
            'label'       => sprintf( __( '%s', 'rajaskin-wp-theme' ), $comm_data['label'] ),
            'description' => __( 'Will be automatically scaled & cropped to fit the symmetrical bell curve arch.', 'rajaskin-wp-theme' ),
            'section'     => 'rajaskin_community_section',
        ) ) );
    }

    // ---------------------------------------------------
    // 8. SECTION: Catalogue / Shop Page Copywriting
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_catalogue_section', array(
        'title'       => __( 'Catalogue / Shop Page', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 55,
        'description' => __( 'Customize the headline and introduction for the product catalogue / shop page.', 'rajaskin-wp-theme' ),
    ) );

    $wp_customize->add_setting( 'rajaskin_catalogue_title', array(
        'default'           => 'Discover Our Collection',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_catalogue_title', array(
        'label'   => __( 'Catalogue Title', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_catalogue_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'rajaskin_catalogue_subtitle', array(
        'default'           => 'Try clinical formulas that naturally restore and balance your skin.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_catalogue_subtitle', array(
        'label'   => __( 'Catalogue Subtitle', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_catalogue_section',
        'type'    => 'text',
    ) );

    // ---------------------------------------------------
    // 9. SECTION: About Us Page Copywriting
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_about_section', array(
        'title'       => __( 'About Us Page', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 60,
        'description' => __( 'Customize the title, subtitle, manifesto, and statistics on the About Us page.', 'rajaskin-wp-theme' ),
    ) );

    // About Title
    $wp_customize->add_setting( 'rajaskin_about_title', array(
        'default'           => 'About Us',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_about_title', array(
        'label'   => __( 'Page Title', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_about_section',
        'type'    => 'text',
    ) );

    // About Subtitle
    $wp_customize->add_setting( 'rajaskin_about_subtitle', array(
        'default'           => 'Learn more about our story and core values.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_about_subtitle', array(
        'label'   => __( 'Page Subtitle', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_about_section',
        'type'    => 'text',
    ) );

    // Manifesto Text
    $wp_customize->add_setting( 'rajaskin_about_manifesto', array(
        'default'           => 'Rajaskin is a personal care brand committed to build a better future for the earth and humanity. We celebrate self-care with a fresh approach where sustainability, effectiveness, and style meet.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_about_manifesto', array(
        'label'   => __( 'About Manifesto Text', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_about_section',
        'type'    => 'textarea',
    ) );

    // Stats 1
    $wp_customize->add_setting( 'rajaskin_about_stat1_num', array( 'default' => '100%', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat1_num', array( 'label' => __( 'Stat 1 Number', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_about_stat1_label', array( 'default' => 'Active Formulation', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat1_label', array( 'label' => __( 'Stat 1 Label', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );

    // Stats 2
    $wp_customize->add_setting( 'rajaskin_about_stat2_num', array( 'default' => '0%', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat2_num', array( 'label' => __( 'Stat 2 Number', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_about_stat2_label', array( 'default' => 'Empty Fillers', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat2_label', array( 'label' => __( 'Stat 2 Label', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );

    // Stats 3
    $wp_customize->add_setting( 'rajaskin_about_stat3_num', array( 'default' => '35+', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat3_num', array( 'label' => __( 'Stat 3 Number', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_about_stat3_label', array( 'default' => 'Country Reached', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_about_stat3_label', array( 'label' => __( 'Stat 3 Label', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_about_section', 'type' => 'text' ) );

    // ---------------------------------------------------
    // 10. SECTION: Cart & Order History Copywriting
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_pages_copy_section', array(
        'title'       => __( 'Cart & Order History Copywriting', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 65,
        'description' => __( 'Customize headings and descriptions on the Cart and Order History pages.', 'rajaskin-wp-theme' ),
    ) );

    // Cart Title & Subtitle
    $wp_customize->add_setting( 'rajaskin_cart_title', array( 'default' => 'Shopping Cart', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_cart_title', array( 'label' => __( 'Cart Page Title', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_pages_copy_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_cart_subtitle', array( 'default' => 'Review your selected items and proceed to secure checkout.', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_cart_subtitle', array( 'label' => __( 'Cart Page Subtitle', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_pages_copy_section', 'type' => 'text' ) );

    // Order History Title & Subtitle
    $wp_customize->add_setting( 'rajaskin_oh_title', array( 'default' => 'Order History', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_oh_title', array( 'label' => __( 'Order History Title', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_pages_copy_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'rajaskin_oh_subtitle', array( 'default' => 'Manage, track, and review your skincare orders in one place.', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( 'rajaskin_oh_subtitle', array( 'label' => __( 'Order History Subtitle', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_pages_copy_section', 'type' => 'text' ) );

    // ---------------------------------------------------
    // 11. SECTION: Footer & Socials
    // ---------------------------------------------------
    $wp_customize->add_section( 'rajaskin_footer_section', array(
        'title'       => __( 'Footer & Social Media', 'rajaskin-wp-theme' ),
        'panel'       => 'rajaskin_theme_panel',
        'priority'    => 70,
        'description' => __( 'Customize footer watermark text and social media profile links.', 'rajaskin-wp-theme' ),
    ) );

    // Footer Watermark Text
    $wp_customize->add_setting( 'rajaskin_footer_watermark', array(
        'default'           => 'RajaSkin',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'rajaskin_footer_watermark', array(
        'label'   => __( 'Footer Giant Watermark Text', 'rajaskin-wp-theme' ),
        'section' => 'rajaskin_footer_section',
        'type'    => 'text',
    ) );

    // Social Links
    $wp_customize->add_setting( 'rajaskin_social_facebook', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'rajaskin_social_facebook', array( 'label' => __( 'Facebook URL', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_footer_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'rajaskin_social_instagram', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'rajaskin_social_instagram', array( 'label' => __( 'Instagram URL', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_footer_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'rajaskin_social_tiktok', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'rajaskin_social_tiktok', array( 'label' => __( 'TikTok URL', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_footer_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'rajaskin_social_x', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'rajaskin_social_x', array( 'label' => __( 'X (Twitter) URL', 'rajaskin-wp-theme' ), 'section' => 'rajaskin_footer_section', 'type' => 'url' ) );
}
add_action( 'customize_register', 'rajaskin_customize_register' );

/**
 * Enqueue JavaScript handler for instant live preview updates in Customizer iframe
 */
function rajaskin_customize_preview_js() {
    wp_enqueue_script(
        'rajaskin-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array( 'customize-preview', 'jquery' ),
        '1.0.0',
        true
    );
}
add_action( 'customize_preview_init', 'rajaskin_customize_preview_js' );
