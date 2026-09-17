<?php
/**
 * The template for displaying all pages
 */

get_header(); ?>

<div class="container site-content-wrapper">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="page-header">
                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
            </header>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'komerce-theme' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>
    <?php
    endwhile;
    ?>
</div>

<?php get_footer(); ?>
