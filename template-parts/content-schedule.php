<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header container">

        <?php if (has_post_thumbnail()) : ?>
        <div class="featured-image">
            <?php the_post_thumbnail(); ?>
        </div>
        <?php endif; ?>

        <h1 class="entry-title"><?php the_title(); ?></h1>

        <div class="hr"></div>

    </header><!-- .entry-header -->

    <div class="entry-content container post-container">
        <?php the_content(); ?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
