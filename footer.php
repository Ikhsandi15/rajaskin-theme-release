</main><!-- #primary -->

<footer id="colophon" class="site-footer rs-site-footer">
    <div class="container rs-footer-container">
        <!-- Top Navigation Row -->
        <div class="rs-footer-top">
            <nav class="rs-footer-nav" aria-label="Footer Navigation">
                <ul class="rs-footer-menu">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>">Catalogue</a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'rajaskin_get_about_url' ) ? rajaskin_get_about_url() : home_url( '/about-us/' ) ); ?>">About Us</a></li>
                </ul>
            </nav>

            <?php
            $fb_url   = get_theme_mod( 'rajaskin_social_facebook', '#' );
            $ig_url   = get_theme_mod( 'rajaskin_social_instagram', '#' );
            $tt_url   = get_theme_mod( 'rajaskin_social_tiktok', '#' );
            $x_url    = get_theme_mod( 'rajaskin_social_x', '#' );
            $watermark = get_theme_mod( 'rajaskin_footer_watermark', get_bloginfo( 'name' ) ?: 'RajaSkin' );
            ?>
            <div class="rs-footer-icons">
                <?php if ( ! empty( $fb_url ) ) : ?>
                    <a href="<?php echo esc_url( $fb_url ); ?>" class="rs-footer-icon-link" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( $ig_url ) ) : ?>
                    <a href="<?php echo esc_url( $ig_url ); ?>" class="rs-footer-icon-link" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( $tt_url ) ) : ?>
                    <a href="<?php echo esc_url( $tt_url ); ?>" class="rs-footer-icon-link" aria-label="TikTok" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( $x_url ) ) : ?>
                    <a href="<?php echo esc_url( $x_url ); ?>" class="rs-footer-icon-link" aria-label="X" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l16 16M4 20L20 4"/></svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Giant Watermark Typography -->
        <div class="rs-footer-watermark-wrap">
            <h1 class="rs-footer-watermark"><?php echo esc_html( $watermark ); ?></h1>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sticky Header Scroll Effect
    var stickyHeader = document.getElementById('masthead');
    if (stickyHeader) {
        var handleHeaderScroll = function() {
            if (window.pageYOffset > 10) {
                stickyHeader.classList.add('is-scrolled');
            } else {
                stickyHeader.classList.remove('is-scrolled');
            }
        };
        window.addEventListener('scroll', handleHeaderScroll, { passive: true });
        handleHeaderScroll();
    }

    // Mobile Drawer Menu
    const toggleBtn = document.querySelector('.mobile-menu-toggle');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const body = document.body;

    function toggleMenu() {
        body.classList.toggle('mobile-menu-active');
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleMenu);
    if (overlay) overlay.addEventListener('click', toggleMenu);

    // Profile / Account Dropdown Toggle
    const accountWrap = document.getElementById('rsAccountDropdownWrap');
    const accountTrigger = document.getElementById('rsAccountTrigger');

    if (accountTrigger && accountWrap) {
        accountTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = accountWrap.classList.toggle('active');
            accountTrigger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!accountWrap.contains(e.target)) {
                accountWrap.classList.remove('active');
                accountTrigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && accountWrap.classList.contains('active')) {
                accountWrap.classList.remove('active');
                accountTrigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>

<script>
// Fix Select2 inline width injection causing horizontal overflow on mobile
(function() {
    function fixSelect2Widths() {
        var containers = document.querySelectorAll('.woocommerce-checkout .select2-container, #cart-destination-field .select2-container');
        containers.forEach(function(el) {
            el.style.removeProperty('width');
            el.style.setProperty('width', '100%', 'important');
            el.style.setProperty('max-width', '100%', 'important');
        });
    }

    // Fix on page load
    document.addEventListener('DOMContentLoaded', fixSelect2Widths);

    // Fix after Select2 value changes
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('select2:select select2:open select2:close', function() {
                setTimeout(fixSelect2Widths, 50);
            });

            // Also hook into WooCommerce AJAX checkout refresh
            jQuery(document.body).on('updated_checkout', function() {
                setTimeout(fixSelect2Widths, 100);
            });
        }

        // Fallback: MutationObserver to catch any style="" injection
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    var el = mutation.target;
                    if (el.classList.contains('select2-container')) {
                        el.style.setProperty('width', '100%', 'important');
                        el.style.setProperty('max-width', '100%', 'important');
                    }
                }
            });
        });

        var select2Containers = document.querySelectorAll('.woocommerce-checkout .select2-container, #cart-destination-field .select2-container');
        select2Containers.forEach(function(el) {
            observer.observe(el, { attributes: true, attributeFilter: ['style'] });
        });
    });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
