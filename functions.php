<?php 
// functions template 
// theme +fitness
?>
<?php
/**
 * Adding support
 */
function fitness_theme_setup() {
    add_theme_support( 'menus' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'customizer' );
}
add_action( 'after_setup_theme', 'fitness_theme_setup' );


/**
 * enqueue SCRIPTS 
 */
function fitness_assets() {
    // Styles
    wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );
    
    // Scripts
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'fitness_assets' );