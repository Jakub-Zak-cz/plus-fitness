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

/** price */
function fitness_price_list($wp_customize) {
    // Vytvoříme novou sekci pro ceník
    $wp_customize->add_section('cenik', array(
        'title' => 'Ceník', // Název sekce
        'priority' => 30, // Pořadí sekce
    ));

    // Pole s hodnotami pro ceník pro dospělé
    $adult_options = array(
        'once' => 'Jednorazovy',
        'one-month' => '1 měsíc',
        'two-months' => '2 měsíce',
        'three-months' => '3 měsíce',
        'half-year' => 'Půl roku',
        'one-year' => 'Jeden Rok',
        '10-entries' => '10 vstupů',
        '20-entries' => '20 vstupů',
        '30-entries' => '30 vstupů',
        'group' => 'Skupina',
        'personal' => 'Osobní Trenér',
    );

    // Pole s hodnotami pro ceník pro studenty
    $student_options = array(
        'once-student' => 'Jednorazovy Student',
        'one-month-student' => '1 měsíc Student',
        'two-months-student' => '2 měsíce Student',
        'three-months-student' => '3 měsíce Student',
        'half-year-student' => 'Půl roku Student',
        'one-year-student' => 'Jeden Rok Student',
        '10-entries-student' => '10 vstupů Student',
        '20-entries-student' => '20 vstupů Student',
        '30-entries-student' => '30 vstupů Student',
        'group-student' => 'Skupina Student',
        'personal-student' => 'Osobní Trenér Student',
    );

    // Přidáme textová pole pro jednotlivé hodnoty pro ceník pro dospělé
    foreach ($adult_options as $option_key => $option_label) {
        $wp_customize->add_setting($option_key, array(
            'default' => '',
            'type' => 'option', // Uložení hodnoty jako volbu v databázi
            'sanitize_callback' => 'wp_kses_post', // Očištění hodnoty
        ));

        $wp_customize->add_control($option_key, array(
            'label' => $option_label, // Popisek pole
            'section' => 'cenik', // Sekce, do které pole patří
            'type' => 'text', // Typ pole
        ));
    }

    // Přidáme textová pole pro jednotlivé hodnoty pro ceník pro studenty
    foreach ($student_options as $option_key => $option_label) {
        $wp_customize->add_setting($option_key, array(
            'default' => '',
            'type' => 'option', // Uložení hodnoty jako volbu v databázi
            'sanitize_callback' => 'sanitize_text_field', // Očištění hodnoty
        ));

        $wp_customize->add_control($option_key, array(
            'label' => $option_label, // Popisek pole
            'section' => 'cenik', // Sekce, do které pole patří
            'type' => 'text', // Typ pole
        ));
    }
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
        'spalovacka' => 'Spalovačka'
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



/** shortcode */
// Funkce pro vytvoření shortcode
function fitness_trainers_slider_shortcode() {
    ob_start(); // Zahájení vyrovnávací paměti
    ?>

    <section aria-label="Trenéři +fitness" class="custom-trainers">
        <header class="container">
            
            <h2 class="custom-trainers_title">Naši <b class="accent-text">trenéři</b></h2>

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

/** price list shortcode */
function display_price_list(){

    ob_start();
    ?>

    <section class="price-list" aria-labelledby="#price_title" >
        
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
                    $one_entry = get_option('once');
                    $one_month = get_option('one-month');
                    $two_months = get_option('two-months');
                    $three_months = get_option('three-months');
                    $half_year = get_option('half-year');
                    $one_year = get_option('one-year');
                    $entries_10 = get_option('10-entries');
                    $entries_20 = get_option('20-entries');
                    $entries_30 = get_option('30-entries');
                    $group = get_option('group');
                    $personal = get_option('personal');
                ?>

                    <div class="price-block">

                        <span class="price-span">Jednorázový vstup</span>

                        <span class="price"><?php echo esc_html($one_entry); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">1 měsíc</span>

                        <span class="price"><?php echo esc_html($one_month); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">2 měsíce</span>

                        <span class="price"><?php echo esc_html($two_months); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">3 měsíce</span>

                        <span class="price"><?php echo esc_html($three_months); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">6 měsíců</span>

                        <span class="price"><?php echo esc_html($half_year); ?></span>

                    </div> 

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">1 Rok</span>

                        <span class="price"><?php echo esc_html($one_year); ?></span>

                    </div>
                    
                    
                    <div class="price-block">

                        <span class="price-span">10 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_10); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">20 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_20); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">30 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_30); ?></span>

                    </div>

                    <div class="price-block accent-price-block shadow" id="group-training" >

                        <span class="price-span">
                            Skupinová cvičení
                            s Instruktorem
                        </span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo esc_html($group); ?></span>

                    </div>

                    <div class="price-block accent-price-block shadow">

                        <span class="price-span">Cvičení s osobním trenérem</span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo esc_html($personal); ?></span>

                    </div>

                </div>    
                
                <div id="student-price-list" class="price-list__content">
                    <!-- studentske ceny -->

                    <?php
                    // students
                    $one_entry_student = get_option('once-student');
                    $one_month_student = get_option('one-month-student');
                    $two_months_student = get_option('two-months-student');
                    $three_months_student = get_option('three-months-student');
                    $half_year_student = get_option('half-year-student');
                    $one_year_student = get_option('one-year-student');
                    $entries_10_student = get_option('10-entries-student');
                    $entries_20_student = get_option('20-entries-student');
                    $entries_30_student = get_option('30-entries-student');
                    $group_student = get_option('group-student');
                    $personal_student = get_option('personal-student');

                    ?>

                    <div class="price-block">

                        <span class="price-span">Jednorázový vstup</span>

                        <span class="price"><?php echo esc_html($one_entry_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">1 měsíc</span>

                        <span class="price"><?php echo esc_html($one_month_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">2 měsíce</span>

                        <span class="price"><?php echo esc_html($two_months_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">3 měsíce</span>

                        <span class="price"><?php echo esc_html($three_months_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">6 měsíců</span>

                        <span class="price"><?php echo esc_html($half_year_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">Permanentka</span>

                        <span class="price-span">1 Rok</span>

                        <span class="price"><?php echo esc_html($one_year_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">10 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_10_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">20 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_20_student); ?></span>

                    </div>

                    <div class="price-block">

                        <span class="price-span">30 vstupů</span>

                        <span class="price"><?php echo esc_html($entries_30_student); ?></span>

                    </div>

                    <div class="price-block accent-price-block">

                        <span class="price-span">
                            Skupinová cvičení
                            s Instruktorem
                        </span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo esc_html($group_student); ?></span>

                    </div>

                    <div class="price-block accent-price-block">

                        <span class="price-span">Cvičení s osobním trenérem</span>

                        <span class="price-span">1 hodina</span>

                        <span class="price"><?php echo esc_html($personal_student); ?></span>

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

                    </ul>
                
                </div>

                
                
                <div class="credit-wrapper">
                    
                    <h3 class="credit-pay_title">Kredity je možné uhradit:</h3>
        
                    <ul class="credit-pay_list">

                        <li>a ) V hotovosti nebo platební kartou na recepci fitness.</li>

                        <li>b ) Převodním příkazem z účtu klienta na účet: 11111 (do poznámky je třeba uvést email klienta nebo celé jméno z rezervačního systému, aby bylo možné platbu přiřadit ke konkrétnímu uživatelskému účtu).</li>

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
        'lekce' => 'spinning,kruhovy_trenink,trampoliny',
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
function recent_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 4, // Počet zobrazených příspěvků na stránce
    ), $atts);

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['count'],
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
    );

    $query = new WP_Query($args);

    $output = '<div class="recent-posts container">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="post-preview">';
            $output .= '<a target="_blank" href="' . esc_url(get_permalink()) . '">';
            $output .= '<div class="thumbnail">' . get_the_post_thumbnail() . '</div>';
            $output .= '<h3 class="post-title">' . get_the_title() . '</h3>';
            $output .= '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
            $output .= '</a>';
            $output .= '</div>';
        }

        // Paginace - pokud je více příspěvků než počet zobrazených na stránce, zobrazíme paginaci
        if ($query->max_num_pages > 1) {
            $output .= '<div class="pagination">';
            $output .= paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => max(1, $paged),
                'total' => $query->max_num_pages,
                'prev_text' => __('« Předchozí'),
                'next_text' => __('Další »'),
            ));
            $output .= '</div>';
        }
    }

    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('recent_posts', 'recent_posts_shortcode');

/** shortcode for showing schedule of trainings */

function display_schedule_shortcode() {

    ob_start(); ?>

    <section class="schedule" aria-labelledby="#schedule-title">

        <div class="container">

            <h2 id="schedule-title">Skupinová cvičení <b class="accent-text">Srpen</b></h2>

            <div class="schedule-wrapper">
                
                <div class="schedule_controls">
                    
                    <button class="schedule-control_toggle active" onclick="toggleSchedule(event, 'schedule-monday')">Pondělí</button>
                    
                    <!-- <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-tuesday')">Úterý</button> -->
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-wednesday')">Středa</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-thursday')">Čtvrtek</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-friday')">Pátek</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-saturday')">Sobota</button>
                    
                    <button class="schedule-control_toggle"  onclick="toggleSchedule(event, 'schedule-sunday')">Neděle</button>

                    <!-- responsive version -->

                    <button class="schedule-control_toggle schedule-control_responsive active" onclick="toggleSchedule(event, 'schedule-monday')">Po</button>
                    
                    <!-- <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-tuesday')">Út</button> -->
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-wednesday')">St</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-thursday')">Čt</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-friday')">Pá</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-saturday')">So</button>
                    
                    <button class="schedule-control_toggle schedule-control_responsive"  onclick="toggleSchedule(event, 'schedule-sunday')">Ne</button>
                
                </div>

                <div id="schedule-monday" class="schedule-content active">

                    <div class="schedule-cart">

                        <span class="schedule-name">Michal</span>

                        <span class="schedule-event">KRUHÁČ-štíhlá linie</span>

                        <span class="schedule-time">18:00 - 19:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div>
                
                <!-- <div id="schedule-tuesday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">PEVNÉ TĚLO</span>

                        <span class="schedule-time">18:00 - 19:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div> -->
                
                <div id="schedule-wednesday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">PEVNÉ TĚLO</span>

                        <span class="schedule-time">18:00 - 19:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">PEVNÉ TĚLO</span>

                        <span class="schedule-time">18:00 - 19:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div>
                
                <div id="schedule-thursday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Michal</span>

                        <span class="schedule-event">KRUHÁČ - štíhlá linie</span>

                        <span class="schedule-time">19:00 - 20:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div>
                
                <div id="schedule-friday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Michal</span>

                        <span class="schedule-event">KRUHÁČ - štíhlá linie</span>

                        <span class="schedule-time">19:00 - 20:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div> 
                    
                    <div class="schedule-cart">

                        <span class="schedule-name">Michal</span>

                        <span class="schedule-event">KRUHÁČ - štíhlá linie</span>

                        <span class="schedule-time">19:00 - 20:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div> 

                    <div class="schedule-cart">

                        <span class="schedule-name">Michal</span>

                        <span class="schedule-event">KRUHÁČ - štíhlá linie</span>

                        <span class="schedule-time">19:00 - 20:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div> 

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">BODYFORMING</span>

                        <span class="schedule-time">10:00 – 11:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">BODYFORMING</span>

                        <span class="schedule-time">10:00 – 11:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div>
                
                <div id="schedule-saturday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">BODYFORMING</span>

                        <span class="schedule-time">10:00 – 11:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>

                </div>
                
                <div id="schedule-sunday" class="schedule-content">

                    <div class="schedule-cart">

                        <span class="schedule-name">Péťa</span>

                        <span class="schedule-event">PEVNÉ TĚLO</span>

                        <span class="schedule-time">19:00 – 20:00</span>

                        <a href="#" class="btn accent-btn shadow schedule-btn">Zjistit více</a>

                    </div>                

                </div>
            
            </div>

        </div>

    </section>
    
    <?php
    return ob_get_clean();
} ;
add_shortcode('show_schedule', 'display_schedule_shortcode');