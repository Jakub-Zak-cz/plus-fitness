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
    add_theme_support('post-thumbnails');
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

$fitness_trainer_name = "";
$fitness_schedule_time = "";

/** Customizer */
/** Trainers Customizer */
function custom_theme_register_customizer_sections($wp_customize) {
    // Vytvoříme novou sekci pro trenéry
    $wp_customize->add_section('custom_trainer', array(
        'title' => 'Instruktoři slider',
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
        ),
    ));

    $fitness_gallery_count = get_theme_mod('fitness_gallery_count');

    // Pole pro každý obrázek v galerii
    for ($i = 1; $i <= $fitness_gallery_count; $i++) { // Uvádíme maximální počet obrázků, který může být v galerii (zde 10)
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

/** price */
function fitness_price_list($wp_customize) {
    // Vytvoříme novou sekci pro ceník
    $wp_customize->add_section('cenik', array(
        'title' => 'Ceník', // Název sekce
        'priority' => 30, // Pořadí sekce
    ));

    $wp_customize->add_setting('adults_count', array(
        'default' => 1,
        'sanitize_callback' => 'absint', // Očistí hodnotu a převede ji na celé číslo
    ));

    $wp_customize->add_control('adults_count', array(
        'label' => __('Počet cenových možností', 'fitness'),
        'section' => 'cenik',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
        ),
    ));

    // Proměnná by měla být definovaná až po těchto řádcích
    $adults_tags_count = get_theme_mod('adults_count');

    for ($i = 1; $i <= $adults_tags_count; $i++) { 

        $wp_customize->add_setting("adults_count_first_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("adults_count_first_h_$i", array(
            'label' => "Pokud permanentka $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

        $wp_customize->add_setting("adults_count_second_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("adults_count_second_h_$i", array(
            'label' => "Čas $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

        $wp_customize->add_setting("adults_count_third_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("adults_count_third_h_$i", array(
            'label' => "Cena $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

    };

    $wp_customize->add_setting('students_count', array(
        'default' => 1,
        'sanitize_callback' => 'absint', // Očistí hodnotu a převede ji na celé číslo
    ));

    $wp_customize->add_control('students_count', array(
        'label' => __('Počet cenových možností pro studenty', 'fitness'),
        'section' => 'cenik',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
        ),
    ));

    // Proměnná by měla být definovaná až po těchto řádcích
    $students_tags_count = get_theme_mod('students_count');

    for ($i = 1; $i <= $students_tags_count; $i++) { 

        $wp_customize->add_setting("students_count_first_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("students_count_first_h_$i", array(
            'label' => "Pokud permanentka pro studenty $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

        $wp_customize->add_setting("students_count_second_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("students_count_second_h_$i", array(
            'label' => "Čas pro studenty $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

        $wp_customize->add_setting("students_count_third_h_$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("students_count_third_h_$i", array(
            'label' => "Cena pro studenty $i", 
            'section' => 'cenik', 
            'type' => 'text', 
        ));

    };

    // group training adults

    $wp_customize->add_setting("group_adults", array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_control("group_adults", array(
        'label' => "Skupinová cvičení s instruktorem pro dospělé cena", 
        'section' => 'cenik', 
        'type' => 'text', 
    ));

    // group training students

    $wp_customize->add_setting("group_students", array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_control("group_students", array(
        'label' => "Skupinová cvičení s instruktorem pro studenty cena", 
        'section' => 'cenik', 
        'type' => 'text', 
    ));

    // personal trainer adults

    $wp_customize->add_setting("personal_adult", array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_control("personal_adult", array(
        'label' => "Osobní trenér pro dospělé cena", 
        'section' => 'cenik', 
        'type' => 'text', 
    ));

    // personal trainer student

    $wp_customize->add_setting("personal_student", array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_control("personal_student", array(
        'label' => "Osobní trenér pro studenty cena", 
        'section' => 'cenik', 
        'type' => 'text', 
    ));
    
}

add_action('customize_register', 'fitness_price_list');

/** lekce */
function fitness_trainings($wp_customize) {
    // Vytvoříme novou sekci pro Lekce
    $wp_customize->add_section('fitness_lekce', array(
        'title' => 'Lekce', // Název sekce
        'priority' => 30, // Pořadí sekce
    ));

     // Pole s hodnotami pro Lekce
     $lekce_options = array(
        'spinning' => 'Spinning',
        'kruhovy_trenink' => 'Kruhový Trénink',
        'trampoliny' => 'Trampolíny',
        'fitness_trener' => 'Osobní Fitness Trenér',
        'pevne_telo' => 'Pevné Tělo',
        'bodyforming' => 'BodyForming',
        'spalovacka' => 'Spalovačka',
        'nova_lekce_1' => 'nova_lekce_1',
        'nova_lekce_2' => 'nova_lekce_2',
        'nova_lekce_3' => 'nova_lekce_3'
    );

    // Přidáme textová pole pro jednotlivé hodnoty Lekce
    foreach ($lekce_options as $option_key => $option_label) {
        $wp_customize->add_setting($option_key, array(
            'default' => '',
            'type' => 'option', // Uložení hodnoty jako volbu v databázi
            'sanitize_callback' => 'wp_kses_post', // Očištění hodnoty
        ));

        $wp_customize->add_control($option_key, array(
            'label' => $option_label, // Popisek pole
            'section' => 'fitness_lekce', // Sekce, do které pole patří
            'type' => 'textarea', // Typ pole
        ));
    } 
}

add_action('customize_register', 'fitness_trainings');


function trainers_tags($wp_customize) {
    
    $wp_customize->add_section('trainers_tags', array(
        'title' => __('Instruktoři pro lekce'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('trainers_tags_count', array(
        'default' => 1,
        'sanitize_callback' => 'absint', // Očistí hodnotu a převede ji na celé číslo
    ));

    $wp_customize->add_control('trainers_tags_count', array(
        'label' => __('Počet instruktorů', 'fitness'),
        'section' => 'trainers_tags',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
        ),
    ));

    // Proměnná by měla být definovaná až po těchto řádcích
    $trainers_tags_count = get_theme_mod('trainers_tags_count');

    for ($i = 1; $i <= $trainers_tags_count; $i++) {
        
        $wp_customize->add_setting("trainers_tags_name$i", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ));

        $wp_customize->add_control("trainers_tags_name$i", array(
            'label' => "Jméno trenéra $i", 
            'section' => 'trainers_tags', 
            'type' => 'textarea', 
        ));

        $wp_customize->add_setting("trainers_tags_image$i", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "trainers_tags_image$i", array(
            'label' => "Obrázek pro trenéra $i",
            'section' => 'trainers_tags',
            'settings' => "trainers_tags_image$i",
        )));
    }
};

add_action('customize_register', 'trainers_tags');


/** shortcode */
// Funkce pro vytvoření shortcode
function fitness_trainers_slider_shortcode() {
    ob_start(); // Zahájení vyrovnávací paměti
    ?>

    <section aria-label="Trenéři +fitness" class="custom-trainers" id="trainers" >
        <header class="container">
            
            <h2 class="custom-trainers_title">Naši <b class="accent-text">trenéři</b></h2>

            <span class="custom-trainers_undertitle">S našimi trenéry dosáhnete svých cílů s jistotou pod profesionálním vedením.</span>

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
                            <a target="_blank" class="trainers-link btn white-btn" href="<?php echo home_url() ; ?>/instruktori<?php echo esc_url($trainer_link); ?>">Více informací -></a>
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


/** gallery shortcode */
function display_gallery() {
    ob_start();
    ?>
    <section id="galerie" class="fitness-gallery" aria-label="Galerie obrázků prostorů našeho fitness">
        
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

/** price list shortcode */
function display_price_list(){

    ob_start();
    ?>

    <section class="price-list" aria-labelledby="#price_title" id="cenik" >
        
        <div class="container">

            <header>
                
                <h2 id="price_title">Kolik to u <b class="accent-text" > nás stojí</b></h2>
            
            </header>

            <div class="price-list_wrapper">

                <div class="price-list_controls">

                    <button class="price-list__toggle active" onclick="togglePriceList(event, 'adult-price-list')">Dospělý</button>
                    
                    <button class="price-list__toggle" onclick="togglePriceList(event, 'student-price-list')">Studenti</button>

                </div>

                <div id="adult-price-list" class="price-list__content active">
                <!-- dospele ceny -->

                <?php
                // addults
                $adults_tags_count = get_theme_mod('adults_count', 1);

                for ($i = 1; $i <= $adults_tags_count; $i++) { 
                    $first_headline = get_theme_mod("adults_count_first_h_$i");
                    $second_headline = get_theme_mod("adults_count_second_h_$i");
                    $third_headline = get_theme_mod("adults_count_third_h_$i");

                    if (!empty($third_headline)) {
                        echo '<div class="price-block">';
                        echo '<span class="price-span">' . esc_html($first_headline) . '</span>';
                        echo '<span class="price-span">' . esc_html($second_headline) . '</span>';
                        echo '<span class="price">' . esc_html($third_headline) . '</span>';
                        echo '</div>';
                    }
                }

                ?>
                    <div class="price-block accent-price-block shadow" id="group-training" >

                        <span class="price-span">
                            Skupinová cvičení
                            s Instruktorem
                        </span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo get_theme_mod('group_adults'); ?></span>

                    </div>

                    <div class="price-block accent-price-block shadow">

                        <span class="price-span">Cvičení s osobním trenérem</span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo get_theme_mod('personal_adult'); ?></span>

                    </div>

                </div>    
                
                <div id="student-price-list" class="price-list__content">
                    <!-- studentske ceny -->

                    <?php
                    // students
                    $students_tags_count = get_theme_mod('students_count', 1);

                    for ($i = 1; $i <= $students_tags_count; $i++) { 
                        $first_headline = get_theme_mod("students_count_first_h_$i");
                        $second_headline = get_theme_mod("students_count_second_h_$i");
                        $third_headline = get_theme_mod("students_count_third_h_$i");

                        if ( !empty($third_headline)) {
                            echo '<div class="price-block">';
                            echo '<span class="price-span">' . esc_html($first_headline) . '</span>';
                            echo '<span class="price-span">' . esc_html($second_headline) . '</span>';
                            echo '<span class="price">' . esc_html($third_headline) . '</span>';
                            echo '</div>';
                        }
                    }
                    ?>

                    <div class="price-block accent-price-block shadow" id="group-training-student" >

                        <span class="price-span">
                            Skupinová cvičení
                            s Instruktorem
                        </span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo get_theme_mod('group_students'); ?></span>

                    </div>

                    <div class="price-block accent-price-block shadow">

                        <span class="price-span">Cvičení s osobním trenérem</span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo get_theme_mod('personal_student'); ?></span>

                    </div>

                </div>

            </div>

                
            <span id="credit-btn">Pravidla skupinových cvičení -></span>

            <div class="credit-info" id="credit-text">

                <div class="credit-wrapper">
                    
                    <h3 class="credit-pay_title">Pravidla:</h3>
        
                    <ul class="credit-pay_list">

                        <li>1. Cvičení se uskuteční pokud 2 hod před začátkem lekce je přihlášen  min.počet účastníků (číslo v závorce). Při zrušení se kredity vrací automaticky a zasíláme informační SMS.</li>

                        <li>2. Rezervaci na cvičení lze zrušit nejpozději 6 hod před začátkem lekce s vrácením kreditů. Po té již nelze kredity vracet.</li>

                        <li>3. Rezervaci lze vytvořit přes rezervační systém na našich webových stránkách, telefonicky pokud máte na účtě dostatečný počet kreditů a nebo osobně zaplacením na recepci. Rezervaci nelze vytvořit bez předplacení.</li>

                        <li>4. Na lekci je možno přijít i bez rezervace, ale na vlastní riziko, že může být již plno nebo naopak lekce je pro malý počet rezervací zrušená.</li>

                        <li>5. Na skupinová cvičení se lze přihlásit prostřednictvím <a class="accent-text" href="http://rezervace.plusfitness.cz/" target="_blank" >rezervačního systému.</a></li>

                    </ul>
                
                </div>

                
                
                <div class="credit-wrapper">
                    
                    <h3 class="credit-pay_title">Kredity je možné uhradit:</h3>
        
                    <ul class="credit-pay_list">

                        <li>a ) V hotovosti nebo platební kartou na recepci fitness.</li>

                        <li>b ) Převodním příkazem z účtu klienta na účet: <b class="accent-text">317137611/0300</b>  (do poznámky je třeba uvést email klienta nebo celé jméno z rezervačního systému, aby bylo možné platbu přiřadit ke konkrétnímu uživatelskému účtu).</li>

                    </ul>
                
                </div>
        
            </div>

            

        </div>
    
    </section>

    <?php
    return ob_get_clean();

} ;

add_shortcode('price_list', 'display_price_list');

function fitness_lekce_shortcode($atts) {
    $atts = shortcode_atts(array(
        'lekce' => '',
    ), $atts, 'fitness_lekce');


    $lekce_names = explode(',', $atts['lekce']);

    $output = '';

    foreach ($lekce_names as $name) {

        $words = explode('_', $name);
        $heading_text = implode(' ', $words);

        $input_value = get_option($name, '');

        $output .= '<div class="lesson shadow">'; // instead of faq

        $output .= '<div class="visible">'; // instead of question

        $output .= '<h3 class="visible-headline" >' . esc_html($heading_text) . '</h3>'; // instead of question-headline

        $output .= '<img class="lesson-arrow" alt="černá šipka, při kliknutí se zobrazí popis lekce" src="'. get_template_directory_uri() . ' /assets/img/arrow-fitness.png" >'; // instead of faq arrow

        $output .= '</div>';

        if ($name === 'Fitness_trenér') {
            $output .= '<div class="more fitness-training">'; // instead of answer 
        } else {
            $output .= '<div class="more">'; // instead of answer 
        }

        // Use wpautop to format the input value
        $output .= wpautop(esc_html($input_value));

        $output .= '</div>';
        
        $output .= '</div>';
    }

    return $output;
}
add_shortcode('fitness_lekce', 'fitness_lekce_shortcode');

/** recent posts */
function recent_posts_shortcode() {
    
    $args = array(
        'post_type' => 'post',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    $output = '<div class="recent-posts container">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Získání tagů příspěvku
            $post_tags = get_the_tags();
            $exclude_tags = array('pondeli', 'utery', 'streda', 'ctvrtek', 'patek', 'sobota', 'nedele');
            
            // Kontrola, zda příspěvek má některý z vyloučených tagů
            $skip_post = false;
            if ($post_tags) {
                foreach ($post_tags as $tag) {
                    if (in_array($tag->slug, $exclude_tags)) {
                        $skip_post = true;
                        break;
                    }
                }
            }

            if (!$skip_post) {
                $output .= '<div class="post-preview">';
                $output .= '<a target="_blank" href="' . esc_url(get_permalink()) . '">';
                $output .= '<div class="thumbnail">' . get_the_post_thumbnail() . '</div>';
                $output .= '<h3 class="post-title">' . get_the_title() . '</h3>';
                $output .= '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                $output .= '</a>';
                $output .= '</div>';
            }
        }

    }

    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('recent_posts', 'recent_posts_shortcode');



// První shorcode - rozvrch_lekce_shortcode
function rozvrch_lekce_shortcode($atts) {
   
    $a = shortcode_atts(array(
        'lekce' => '',
        'den' => "",
        'instruktor' => '',
        'cas' => '',
    ), $atts);

    $description = get_option($a['lekce']);

    if ($a['instruktor'] == 'peta') {
        $trainer_name = 'péťa';
    } else {
        $trainer_name = esc_html($a['instruktor']);
    }

    $schedule_time = esc_html($a['cas']);

    $tag_name = esc_html($a['den']);

    // Přiřazení tagů k příspěvku
    $post_id = get_the_ID();
    wp_set_post_tags($post_id, array($a['cas'], $a['instruktor'], $a['den']));


    $trainer_image = '';
    $instruktori_data = array();

    $trainers_tags_count = get_theme_mod('trainers_tags_count');

    for ($i = 1; $i <= $trainers_tags_count; $i++) {
        $instruktor_name = get_theme_mod("trainers_tags_name$i");
        $instruktor_image = get_theme_mod("trainers_tags_image$i");

        if (!empty($instruktor_name) && !empty($instruktor_image)) {
            $instruktori_data[$instruktor_name] = $instruktor_image;
        }
    }  

    if (isset($instruktori_data[$a['instruktor']])) {
        $trainer_image = $instruktori_data[$a['instruktor']];
    }


    $output = '<article class="schedule_article">
                <div class="schedule_article-container">
                    <header class="schedule_header">
                        <h2 class="schedule_title">' . get_the_title() . '</h2>
                        <span>' . esc_html($tag_name) . ' ' . esc_html($schedule_time) . '</span>
                    </header>
                    <div class="hr"></div>
                    <div class="schedule-text_container">
                        <img class="schedule-text_responsive-img" src="' . esc_url($trainer_image) . '" alt="' . esc_attr($trainer_name) . '">
                        <h3 class="schedule-text_responsive-h3">' . esc_html($trainer_name) . '</h3>
                        <p class="schedule-text_about">' . esc_html($description) . '</p>
                        <a href="http://rezervace.plusfitness.cz/" class="btn accent-btn shadow">Chci se rezervovat</a>
                    </div>
                </div>
                <section class="schedule-trainer_section" aria-label="' . esc_html($trainer_name) . '">
                    <img src="' . esc_url($trainer_image) . '" alt="' . esc_attr($trainer_name) . '">
                    <h3>' . esc_html($trainer_name) . '</h3>
                </section>
            </article>';

    return $output;
}
add_shortcode('rozvrch_lekce', 'rozvrch_lekce_shortcode');

// Druhý shorcode - display_schedule_shortcode
function display_schedule_shortcode($atts) {

    ob_start(); 

    $a = shortcode_atts(array(
            'mesic' => ''   
    ), $atts);

    $month = esc_html($a['mesic']);
   
    ?>
    <section class="schedule" aria-labelledby="#schedule-title">
        <div class="container">
            <h2 id="schedule-title">Skupinová cvičení <b class="accent-text"><?php echo esc_html($month)?></b></h2>
            <div class="schedule-wrapper">
                <div class="schedule_controls">
                <button class="schedule-control_toggle active" onclick="toggleSchedule(event, 'schedule-pondeli')">Pondělí</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-utery')">Úterý</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-streda')">Středa</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-ctvrtek')">Čtvrtek</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-patek')">Pátek</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-sobota')">Sobota</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-nedele')">Neděle</button>

                    <!-- responsive version -->

                    <button class="schedule-control_toggle schedule-control_responsive active" onclick="toggleSchedule(event, 'schedule-pondeli')">Po</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-utery')">Út</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-streda')">St</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-ctvrtek')">Čt</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-patek')">Pá</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-sobota')">So</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-nedele')">Ne</button>
                </div>

                <?php               

                $tags = array('pondeli', 'utery', 'streda', 'ctvrtek', 'patek', 'sobota', 'nedele');

                foreach ($tags as $index => $tag) {
                    // Query pro získání příspěvků s daným tagem
                    $args = array(
                        'post_type' => 'post',
                        'tag' => $tag,
                        'posts_per_page' => -1,
                    );
                
                    $query = new WP_Query($args);
                
                    // Přidáme třídu "active" pro tag "pondeli"
                    $class = ($index === 0 && $tag === 'pondeli') ? 'schedule-content active' : 'schedule-content';
                
                    // Vytvoříme div s příslušným id a class
                    echo '<div id="schedule-' . esc_attr($tag) . '" class="' . esc_attr($class) . '">';
                
                    // Pokud jsou příspěvky k dispozici, projdeme je a vypíšeme
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post();

                            $trainers_tags_count = get_theme_mod('trainers_tags_count');
                            $name_tags = array();

                            for ($i = 1; $i <= $trainers_tags_count; $i++) {
                                $setting_name = "trainers_tags_name$i";
                                $name_tags[] = get_theme_mod($setting_name);
                            }
                            
                            $post_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'slugs'));                    
                            // Zde jsou tagy, které jsou přítomné v aktuálním příspěvku
                            $matching_tags = array_intersect($name_tags, $post_tags);

                            $other_tags = array_diff($post_tags, $name_tags, $tags);

                            
                            echo '<div class="schedule-cart">';

                            if (!empty($matching_tags)) {
                                foreach ($matching_tags as $tag) {
                                    echo '<span class="schedule-name">';
                                    if ($tag == 'peta') {
                                        echo 'Péťa';
                                    } else {
                                        echo esc_html($tag);
                                    }
                                    echo '</span>';
                                }
                            }
                           

                            echo '<span class="schedule-event">' . get_the_title() . '</span>';
                            
                            if (!empty($other_tags)) {
                                echo '<span class="schedule-time">'; 
                                foreach ($other_tags as $other_tag) {
                                    echo esc_html($other_tag) . ' ';
                                }
                                echo '</span>';
                            }    
                            echo '<a href="' . esc_url(get_permalink()) . '" class="btn accent-btn shadow schedule-btn">Zjistit více</a>';
                            
                            echo '</div>';
                        }
                    } else {
                        // Pokud žádné příspěvky nemají daný tag, zobrazíme zprávu
                        echo '<p>Dnes nejsou žádné lekce</p>';
                    }
                
                    echo '</div>';
                
                    // Obnovíme data postu
                    wp_reset_postdata();
                
                } ?>
                
                
                            </div>
                
                        </div>
                
                    </section>
                    
                    <?php
                    return ob_get_clean();
                }
                
add_shortcode('show_schedule', 'display_schedule_shortcode');

// home url
 function display_home_url_shortcode() {
    return home_url();
 };

 add_shortcode('home_url', 'display_home_url_shortcode');