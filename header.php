<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="primary-header" class="shadow" >
    <div class="nav-logo">
            <a aria-label="odkaz na domácí stránku" href="<?php echo home_url(); ?>">
                <?php if (has_custom_logo()) {
                    the_custom_logo();
                } ?>
            </a>
    </div>

    <nav aria-label="primary-navigation" class="primary-navigation">
        <ul>
            <?php
            // We get navigation menu items named 'primary-navigation'
            $menu_items = wp_get_nav_menu_items('primary-navigation');

            // List a link for each menu item
            if ($menu_items) {
                foreach ($menu_items as $menu_item) {
                    $is_current = false;
                    if (is_page($menu_item->object_id)) {
                        $is_current = true;
                    }

                    echo '<li' . ($is_current ? ' class="current_page_item"' : '') . '>';
                    echo '<a href="' . $menu_item->url . '" ' . ($is_current ? 'aria-current="page"' : '') . '>' . $menu_item->title . '</a>';
                    echo '</li>';
                }
            }

            ?>


        </ul>
    </nav>

    <!-- responsive hamburger menu -->
    <div class="responsive-menu">
        <div class="menu-btn">
            <div class="menu-btn__burger"></div>
        </div>
        
        <aside class="sidebar">
        
            <nav>
            
            <?php    
    
    if ($menu_items) {
        foreach ($menu_items as $menu_item) {
            $is_current = false;
            if (is_page($menu_item->object_id)) {
                $is_current = true;
            }

            echo '<div' . ($is_current ? ' class="current_page_item"' : '') . '>';
            echo '<a href="' . $menu_item->url . '" ' . ($is_current ? 'aria-current="page"' : '') . '>' . $menu_item->title . '</a>';
            echo '</div>';
        }
    }

            ?>

                <div><a href="#footer-nav">Kontakt</a></div>
            
            </nav>
        
        </aside>
    </div>

</header>


