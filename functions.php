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

/** reviews */
function customizer_fitness_reviews_section($wp_customize) {
    // Vytvoření nové sekce
    $wp_customize->add_section('custom_fitness_reviews', array(
        'title' => 'Recenze', // Název sekce
        'description' => 'Přidání recenzí o vašem fitness centru.', // Popis sekce
        'priority' => 150, // Priorita sekce
    ));

    // Přidání možnosti pro nastavení počtu recenzí
    $wp_customize->add_setting('custom_reviews_count', array(
        'default' => 3, // Počet recenzí jako výchozí hodnota
        'sanitize_callback' => 'absint', // Očistí hodnotu a zajistí, že bude celé číslo
    ));

    $wp_customize->add_control('custom_reviews_count', array(
        'label' => 'Počet recenzí k zobrazení', // Název pole
        'section' => 'custom_fitness_reviews', // Přiřazení k nové sekci
        'type' => 'number',
    ));

    // Loop přes počet recenzí podle nastaveného počtu z customizeru
    $count = get_theme_mod('custom_reviews_count', 3);
    for ($i = 1; $i <= $count; $i++) {
        
        $wp_customize->add_setting("custom_review_image_$i", array(
            'sanitize_callback' => 'esc_url_raw', // Očistí URL
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "custom_review_image_$i", array(
            'label' => "Obrázek recenze $i", // Název pole
            'section' => 'custom_fitness_reviews', // Přiřazení k nové sekci
            'settings' => "custom_review_image_$i", // Přiřazení k nastavení
        )));

        $wp_customize->add_setting("custom_review_firstname_$i", array(
            'sanitize_callback' => 'sanitize_text_field', // Očistí textové pole od nežádoucích znaků
        ));

        $wp_customize->add_control("custom_review_firstname_$i", array(
            'label' => "Křestní jméno recenze $i", // Název pole
            'section' => 'custom_fitness_reviews', // Přiřazení k nové sekci
            'type' => 'text',
        ));

        $wp_customize->add_setting("custom_review_lastname_$i", array(
            'sanitize_callback' => 'sanitize_text_field', // Očistí textové pole od nežádoucích znaků
        ));

        $wp_customize->add_control("custom_review_lastname_$i", array(
            'label' => "Příjmení recenze $i", // Název pole
            'section' => 'custom_fitness_reviews', // Přiřazení k nové sekci
            'type' => 'text',
        ));

        $wp_customize->add_setting("custom_review_text_$i", array(
            'sanitize_callback' => 'sanitize_textarea_field', // Očistí textovou oblast od nežádoucích znaků
        ));

        $wp_customize->add_control("custom_review_text_$i", array(
            'label' => "Text recenze $i", // Název pole
            'section' => 'custom_fitness_reviews', // Přiřazení k nové sekci
            'type' => 'textarea',
        ));
    }
}
add_action('customize_register', 'customizer_fitness_reviews_section');


/** gallery */
function fitness_customizer_settings($wp_customize) {
    // Sekce Galerie
    $wp_customize->add_section('fitness_gallery', array(
        'title' => __('Galerie', 'fitness'),
        'priority' => 30,
    ));

    // Počet obrázků v galerii
    $wp_customize->add_setting('fitness_gallery_count', array(
        'default' => 3,
        'sanitize_callback' => 'absint', // Očistí hodnotu a převede ji na celé číslo
    ));

    $wp_customize->add_control('fitness_gallery_count', array(
        'label' => __('Počet obrázků v galerii', 'fitness'),
        'section' => 'fitness_gallery',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 10, // Můžete upravit maximální počet obrázků, pokud chcete
        ),
    ));

    // Pole pro každý obrázek v galerii
    for ($i = 1; $i <= 10; $i++) { // Uvádíme maximální počet obrázků, který může být v galerii (zde 10)
        $wp_customize->add_setting("fitness_gallery_image_$i", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw', // Očistí URL obrázku
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "fitness_gallery_image_$i", array(
            'label' => sprintf(__('Obrázek %s', 'fitness'), $i),
            'section' => 'fitness_gallery',
        )));
    }
}

add_action('customize_register', 'fitness_customizer_settings');


/** shortcode */
// Funkce pro vytvoření shortcode
function fitness_trainers_slider_shortcode() {
    ob_start(); // Zahájení vyrovnávací paměti
    ?>

    <section aria-label="Trenéři +fitness" class="custom-trainers">
        <header class="container">
            
            <h2 class="custom-trainers_title">Naši <b class="accent-text">Trenéři</b></h2>

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


/** reviews shortcode */
function fitness_reviews_shortcode() {
    ob_start(); 
    ?>

    <aside class="reviews" aria-label="recenze" title="reviews">
        <div class="container">
            <header class="reviews-introduction" >
                
                <h2 class="reviews_title">Co o nás říkají <b class="accent-text">naši zákazníci</b></h2>
            
                <p class="reviews_paragraph">Níže najdete několik referencí od našich klientů, kteří nám důvěřovali a dosáhli skvělých výsledků. Jejich příběhy jsou důkazem naší oddanosti a schopnosti pomoci lidem dosáhnout jejich fitness cílů. Přečtěte si jejich zkušenosti a motivujte se k vlastnímu úspěchu. Recenze jsou přímo z našeho <a href="https://www.facebook.com/plusfitko/reviews" target="_blank" aria-label="Odkaz na sekci s recenzemi z naší Facebook stránky."> Facebooku</a></p>
            
            </header>
            <div class="reviews_slider swiper">

                <div class="swiper-wrapper reviews-wrapper">

        <?php 

            $count = get_theme_mod('custom_reviews_count', 3); // Získáme nastavený počet recenzí z customizeru

            for ($i = 1; $i <= $count; $i++) {
                $image_url = get_theme_mod("custom_review_image_$i");
                $review_firstname = get_theme_mod("custom_review_firstname_$i");
                $review_lastname = get_theme_mod("custom_review_lastname_$i");
                $review_text = get_theme_mod("custom_review_text_$i");

                // Zkontrolujeme, zda máme dostatečné informace o recenzi
                if ($image_url && $review_firstname && $review_lastname && $review_text) {
                ?>    
                    <div class="review swiper-slide">
                        
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($review_firstname . ' ' . $review_lastname); ?>">
                        <div class="reviews_text">

                            <header class="reviews-headline">
                                
                                <h3><?php echo wp_kses_post($review_firstname . ' <b class="accent-text">' . $review_lastname . '</b>'); ?></h3>
                                
                                <figure class="review-figure">
                                    
                                    <img class="reviews-approval" src="<?php echo get_template_directory_uri(); ?>/assets/img/recommend.png" alt="<?php echo esc_attr($review_firstname . ' ' . $review_lastname); ?> doporučuje Plus Fitness">
                                    
                                    <figcaption>Doporučuje Plus Fitness</figcaption>
                                
                                </figure>
                            
                            </header>
                            
                            <p><?php echo esc_html($review_text); ?></p>
                        
                        </div>
                        
                    </div>
                
                <?php
                    }
                }

            ?>      
                </div>
                <div class="swiper-pagination reviews-pagination"></div>
            </div>
        </div>
    </aside>
    
    
    
    <?php
    return ob_get_clean();
};

add_shortcode('fitness_reviews', 'fitness_reviews_shortcode');

/** gallery shortcode */
function display_gallery() {
    ob_start();
    ?>
    <section class="fitness-gallery" aria-label="Galerie obrázků prostorů našeho fitness">
        
        <h2 class="gallery-title">Galerie</h2>
        
        <div class="container">
            
            <div class="gallery-slider swiper">
                
                <div class="swiper-wrapper">
                    <?php
                    $gallery_count = get_theme_mod('fitness_gallery_count', 3);
                    for ($i = 1; $i <= $gallery_count; $i++) {
                        $image_url = get_theme_mod("fitness_gallery_image_$i");
                        if ($image_url) {
                            ?>
                            <div class="gallery-slide swiper-slide">

                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr__('Obrázek galerie', 'fitness') . ' ' . $i; ?>">
                            
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            
            </div>
            
            <div class="swiper-button-next gallery-button-next"></div>
            
            <div class="swiper-button-prev gallery-button-prev"></div>
        
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('fitness_gallery', 'display_gallery');
