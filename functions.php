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
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0' );

    
    // Scripts
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );

}

add_action( 'wp_enqueue_scripts', 'fitness_assets' );

/** Customizer */
/** Trainers Customizer */
function custom_theme_register_customizer_sections($wp_customize) {
    // Vytvoříme novou sekci pro trenéry
    $wp_customize->add_section('custom_trainer', array(
        'title' => 'Trenéři',
        'priority' => 30,
    ));

    // Přidáme pole pro zadání počtu trenérů
    $wp_customize->add_setting('custom_trainer_count', array(
        'default' => 1,
        'sanitize_callback' => 'absint', // Ujistí se, že hodnota je celé číslo
    ));

    $wp_customize->add_control('custom_trainer_count', array(
        'label' => 'Počet trenérů',
        'section' => 'custom_trainer',
        'type' => 'number',
    ));

    // Nyní přidáme pole pro každého trenéra na základě počtu trenérů
    $count = get_theme_mod('custom_trainer_count', 1);
    for ($i = 1; $i <= $count; $i++) {
        // Obrázek (Trainer Image)
        $wp_customize->add_setting("custom_trainer_image_$i", array(
            'sanitize_callback' => 'esc_url_raw', // Ujistí se, že hodnota je URL obrázku
        ));
    
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "custom_trainer_image_$i", array(
            'label' => "Obrázek trenéra $i",
            'section' => 'custom_trainer',
        )));

        // Jméno (Trainer Name)
        $wp_customize->add_setting("custom_trainer_name_$i", array(
            'sanitize_callback' => 'sanitize_text_field', // Očistí text od nežádoucích znaků
        ));
    
        $wp_customize->add_control("custom_trainer_name_$i", array(
            'label' => "Jméno trenéra $i",
            'section' => 'custom_trainer',
            'type' => 'text',
        ));

        // Počet dovedností pro každého trenéra
        $wp_customize->add_setting("custom_trainer_skills_count_$i", array(
            'default' => 1,
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control("custom_trainer_skills_count_$i", array(
            'label' => "Počet dovedností trenéra $i",
            'section' => 'custom_trainer',
            'type' => 'number',
        ));

        $trainer_skills_count = get_theme_mod("custom_trainer_skills_count_$i", 1);

        // Pro každou dovednost vytvoříme samostatné pole
        for ($j = 1; $j <= $trainer_skills_count; $j++) {
            $wp_customize->add_setting("custom_trainer_skills_${i}_${j}", array(
                'sanitize_callback' => 'sanitize_textarea_field',
            ));

            $wp_customize->add_control("custom_trainer_skills_${i}_${j}", array(
                'label' => "Dovednost $j trenéra $i",
                'section' => 'custom_trainer',
                'type' => 'textarea',
            ));
        }

        // Text pro odkaz (Trainer Link)
        $wp_customize->add_setting("custom_trainer_link_$i", array(
            'sanitize_callback' => 'esc_url_raw', // Ujistí se, že hodnota je URL odkazu
        ));
    
        $wp_customize->add_control("custom_trainer_link_$i", array(
            'label' => "Odkaz trenéra $i",
            'section' => 'custom_trainer',
            'type' => 'url',
        ));
    }
}
add_action('customize_register', 'custom_theme_register_customizer_sections');

/** shortcode */
// Funkce pro vytvoření shortcode
function fitness_trainers_slider_shortcode() {
    ob_start(); // Zahájení vyrovnávací paměti
    ?>

    <section aria-label="Trenéři +fitness" class="custom-trainers">
        <header class="container">
            
            <h2 class="custom-trainers_title">Naši <b>Trenéři</b></h2>

            <span class="custom-trainers_undertitle">S našimi trenéry dosáhnete svých cílů s jistotou a profesionálním vedením.</span>

        </header>

        <div class="container trainers mySwiper swiper">

            <div class="swiper-wrapper">



            <?php
            // Loop přes počet trenérů
            $count = get_theme_mod('custom_trainer_count', 1);
            for ($i = 1; $i <= $count; $i++) {
                // Načtěte hodnoty z customizeru pro aktuálního trenéra
                $image_url = get_theme_mod("custom_trainer_image_$i");
                $trainer_name = get_theme_mod("custom_trainer_name_$i");
                $trainer_skills_count = get_theme_mod("custom_trainer_skills_count_$i", 1);
                $trainer_link = get_theme_mod("custom_trainer_link_$i");
            ?>

                <div class="trainer swiper-slide">
                    <div class="trainers-image-wrapper">
                    
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($trainer_name); ?>">
                    <?php endif; ?>
                        <div class="trainers-image-showup">
                            
                            <ul class="trainers-list" >
                            <?php
                            // Loop pro vytvoření <li> elementu pro každou dovednost trenéra
                            for ($j = 1; $j <= $trainer_skills_count; $j++) {
                                $trainer_skill = get_theme_mod("custom_trainer_skills_${i}_${j}");

                                // Zkontrolujeme, zda byla dovednost zadána
                                if ($trainer_skill) {
                            ?>
                                <li class="trainers-list_item" ><?php echo esc_html($trainer_skill); ?></li>
                            <?php
                                }
                            }
                            ?>
                            </ul>

                        <?php if ($trainer_link) : ?>
                            <a target="_blank" class="trainers-link btn white-btn" href="<?php echo esc_url($trainer_link); ?>">Více informací -></a>
                        <?php endif; ?> 
                        
                        </div>
                
                    </div>

                    
                    <h3 class="trainer-headline shadow" ><?php echo esc_html($trainer_name); ?></h3>
                
                </div>
                

            <?php
            }
            ?>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>

    <?php
    return ob_get_clean(); // Vrácení obsahu vyrovnávací paměti jako výstup z shortcode
}
add_shortcode('fitness_trainers_slider', 'fitness_trainers_slider_shortcode');
