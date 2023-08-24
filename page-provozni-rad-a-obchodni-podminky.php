<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <main>
    <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    the_content();
                endwhile;
            endif;
    ?>
    </main>

    <footer class="order-footer" >

        <div class="container">
            
            <a href="<?php echo do_shortcode('[home_url]');  ?>" class="get-back accent-text">Vrátit se zpět -></a>
        
        </div>
        
        <div class="copyright container">
    
            <span>Copyright &copy; <?php echo date_i18n('Y'); ?> Plus Fitness Rokycany</span>

            <span>Made By <b>Jakub Žák.</b></span>

        </div>  
    
    </footer>

<?php wp_footer(); ?>
</body>
</html>