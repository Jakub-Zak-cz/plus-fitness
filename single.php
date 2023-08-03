<?php get_header(); ?>

<div id="primary" class="content-area">
    <main class="site-main post-main">

        <?php
        // Start the loop.
        while (have_posts()) :
            the_post();

            // Include the article content template.
            get_template_part('template-parts/content', 'article');

        // End the loop.
        endwhile;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
