/**
 * RajaSkin Theme Customizer Live Preview
 * Enables instant, real-time live preview updates in Customizer iframe
 */

(function($) {
    'use strict';

    if (typeof wp === 'undefined' || !wp.customize) {
        return;
    }

    // 1. Header & Logo
    wp.customize('rajaskin_logo_text', function(value) {
        value.bind(function(newval) {
            $('.site-logo-text').text(newval);
        });
    });

    wp.customize('rajaskin_logo_image', function(value) {
        value.bind(function(newval) {
            var $logoImg = $('.site-logo-img, .custom-logo');
            if ($logoImg.length && newval) {
                $logoImg.attr('src', newval);
            }
        });
    });

    // 2. Homepage Hero Section
    wp.customize('rajaskin_hero_title', function(value) {
        value.bind(function(newval) {
            $('.rs-hero-title').text(newval);
        });
    });

    wp.customize('rajaskin_hero_subtitle', function(value) {
        value.bind(function(newval) {
            $('.rs-hero-subtitle').text(newval);
        });
    });

    wp.customize('rajaskin_hero_btn_text', function(value) {
        value.bind(function(newval) {
            $('.rs-hero-btn').text(newval);
        });
    });

    wp.customize('rajaskin_hero_btn_url', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-hero-btn').attr('href', newval);
            }
        });
    });

    wp.customize('rajaskin_hero_image', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-hero-img').attr('src', newval);
            }
        });
    });

    // 3. Homepage Philosophy & Features
    wp.customize('rajaskin_philo_tag', function(value) {
        value.bind(function(newval) {
            $('.rs-section-tag').text(newval);
        });
    });

    wp.customize('rajaskin_philo_text', function(value) {
        value.bind(function(newval) {
            $('.rs-philosophy-text').text(newval);
        });
    });

    var defaultFeatureSvgs = {
        1: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/></svg>',
        2: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
        3: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.5-2.8L17.5 11"/><path d="m16 16 3 3-3 3"/><path d="M16.5 6.5 12 2 7.5 6.5"/><path d="M12 2v10"/></svg>'
    };

    [1, 2, 3].forEach(function(i) {
        wp.customize('rajaskin_feat' + i + '_icon_img', function(value) {
            value.bind(function(newval) {
                var $box = $('.rs-features-grid .rs-feature-card:nth-child(' + i + ') .rs-feature-icon-box');
                if (newval) {
                    $box.html('<img src="' + newval + '" class="rs-feature-icon-img" alt="Feature ' + i + '">');
                } else {
                    var svgVal = wp.customize('rajaskin_feat' + i + '_icon_svg') ? wp.customize('rajaskin_feat' + i + '_icon_svg').get() : '';
                    $box.html(svgVal || defaultFeatureSvgs[i]);
                }
            });
        });

        wp.customize('rajaskin_feat' + i + '_icon_svg', function(value) {
            value.bind(function(newval) {
                var imgVal = wp.customize('rajaskin_feat' + i + '_icon_img') ? wp.customize('rajaskin_feat' + i + '_icon_img').get() : '';
                if (!imgVal) {
                    var $box = $('.rs-features-grid .rs-feature-card:nth-child(' + i + ') .rs-feature-icon-box');
                    $box.html(newval || defaultFeatureSvgs[i]);
                }
            });
        });

        wp.customize('rajaskin_feat' + i + '_title', function(value) {
            value.bind(function(newval) {
                $('.rs-features-grid .rs-feature-card:nth-child(' + i + ') .rs-feature-title').text(newval);
            });
        });

        wp.customize('rajaskin_feat' + i + '_desc', function(value) {
            value.bind(function(newval) {
                $('.rs-features-grid .rs-feature-card:nth-child(' + i + ') .rs-feature-desc').text(newval);
            });
        });
    });

    // 4. Shop by Category
    wp.customize('rajaskin_cat_heading', function(value) {
        value.bind(function(newval) {
            $('.rs-category-section .rs-section-heading').text(newval);
        });
    });

    [1, 2, 3, 4].forEach(function(i) {
        wp.customize('rajaskin_cat_title_' + i, function(value) {
            value.bind(function(newval) {
                $('.rs-category-grid .rs-category-card:nth-child(' + i + ') .rs-cat-name').text(newval);
            });
        });

        wp.customize('rajaskin_cat_img_' + i, function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.rs-category-grid .rs-category-card:nth-child(' + i + ') .rs-cat-img-box img').attr('src', newval);
                }
            });
        });

        wp.customize('rajaskin_cat_link_' + i, function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.rs-category-grid .rs-category-card:nth-child(' + i + ')').attr('href', newval);
                }
            });
        });
    });

    // 5. Best Sellers Heading
    wp.customize('rajaskin_bestseller_heading', function(value) {
        value.bind(function(newval) {
            $('.rs-products-section .rs-section-heading').text(newval);
        });
    });

    // 6. Editorial Feature Banners
    // Banner 1
    wp.customize('rajaskin_banner1_title', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-normal .rs-editorial-title, .rs-about-story-row.rs-story-normal .rs-story-title').text(newval);
        });
    });

    wp.customize('rajaskin_banner1_desc', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-normal .rs-editorial-desc, .rs-about-story-row.rs-story-normal .rs-story-desc').text(newval);
        });
    });

    wp.customize('rajaskin_banner1_btn_text', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-normal .rs-pill-btn, .rs-about-story-row.rs-story-normal .rs-about-pill-btn').text(newval);
        });
    });

    wp.customize('rajaskin_banner1_btn_url', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-editorial-row.rs-row-normal .rs-pill-btn, .rs-about-story-row.rs-story-normal .rs-about-pill-btn').attr('href', newval);
            }
        });
    });

    wp.customize('rajaskin_banner1_img', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-editorial-row.rs-row-normal .rs-editorial-img, .rs-about-story-row.rs-story-normal .rs-story-img').attr('src', newval);
            }
        });
    });

    // Banner 2
    wp.customize('rajaskin_banner2_title', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-reversed .rs-editorial-title, .rs-about-story-row.rs-story-reversed .rs-story-title').text(newval);
        });
    });

    wp.customize('rajaskin_banner2_desc', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-reversed .rs-editorial-desc, .rs-about-story-row.rs-story-reversed .rs-story-desc').text(newval);
        });
    });

    wp.customize('rajaskin_banner2_btn_text', function(value) {
        value.bind(function(newval) {
            $('.rs-editorial-row.rs-row-reversed .rs-pill-btn, .rs-about-story-row.rs-story-reversed .rs-about-pill-btn').text(newval);
        });
    });

    wp.customize('rajaskin_banner2_btn_url', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-editorial-row.rs-row-reversed .rs-pill-btn, .rs-about-story-row.rs-story-reversed .rs-about-pill-btn').attr('href', newval);
            }
        });
    });

    wp.customize('rajaskin_banner2_img', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.rs-editorial-row.rs-row-reversed .rs-editorial-img, .rs-about-story-row.rs-story-reversed .rs-story-img').attr('src', newval);
            }
        });
    });

    // 7. Community Gallery
    wp.customize('rajaskin_community_heading', function(value) {
        value.bind(function(newval) {
            $('.rs-community-section .rs-section-heading').text(newval);
        });
    });

    wp.customize('rajaskin_community_subtitle', function(value) {
        value.bind(function(newval) {
            $('.rs-community-subtitle').text(newval);
        });
    });

    [1, 2, 3, 4, 5, 6, 7].forEach(function(i) {
        wp.customize('rajaskin_comm_img_' + i, function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.rs-community-gallery .rs-community-item:nth-child(' + i + ') img').attr('src', newval);
                }
            });
        });
    });

    // 8. Catalogue Page
    wp.customize('rajaskin_catalogue_title', function(value) {
        value.bind(function(newval) {
            $('.rs-catalogue-title').text(newval);
        });
    });

    wp.customize('rajaskin_catalogue_subtitle', function(value) {
        value.bind(function(newval) {
            $('.rs-catalogue-subtitle').text(newval);
        });
    });

    // 9. About Us Page
    wp.customize('rajaskin_about_title', function(value) {
        value.bind(function(newval) {
            $('.rs-about-title').text(newval);
        });
    });

    wp.customize('rajaskin_about_subtitle', function(value) {
        value.bind(function(newval) {
            $('.rs-about-subtitle').text(newval);
        });
    });

    wp.customize('rajaskin_about_manifesto', function(value) {
        value.bind(function(newval) {
            $('.rs-about-manifesto-text').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat1_num', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(1) .rs-stat-number').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat1_label', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(1) .rs-stat-label').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat2_num', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(2) .rs-stat-number').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat2_label', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(2) .rs-stat-label').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat3_num', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(3) .rs-stat-number').text(newval);
        });
    });

    wp.customize('rajaskin_about_stat3_label', function(value) {
        value.bind(function(newval) {
            $('.rs-about-stats-grid .rs-about-stat-item:nth-child(3) .rs-stat-label').text(newval);
        });
    });

    // 10. Cart & Order History
    wp.customize('rajaskin_cart_title', function(value) {
        value.bind(function(newval) {
            $('.cart-page-title').text(newval);
        });
    });

    wp.customize('rajaskin_cart_subtitle', function(value) {
        value.bind(function(newval) {
            $('.cart-page-subtitle').text(newval);
        });
    });

    wp.customize('rajaskin_oh_title', function(value) {
        value.bind(function(newval) {
            $('.rs-oh-title').text(newval);
        });
    });

    wp.customize('rajaskin_oh_subtitle', function(value) {
        value.bind(function(newval) {
            $('.rs-oh-subtitle').text(newval);
        });
    });

    // 11. Footer
    wp.customize('rajaskin_footer_watermark', function(value) {
        value.bind(function(newval) {
            $('.rs-footer-watermark').text(newval);
        });
    });

})(jQuery);
