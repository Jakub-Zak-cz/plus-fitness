<footer>
  <div class="container">

    <div class="footer-item">

      <h3 class="footer-headline">Kdy Otvíráme</h3>

      <div class="opening-hours">

        <span>Po-Pa : 8:00 - 12:00, 15:00 - 20:30</span>

        <span>So : 8:00 - 12:00</span>

        <span>
          Ne : Dle inviduálních tréninků <br>
          a rozpisu skupinových cvičení
        </span>

      </div>

    </div>

    <div class="footer-item">

      <h3 class="footer-headline">Menu</h3>

      <div class="footer-menu">
      <?php

      $menu_items = wp_get_nav_menu_items('primary-navigation');

      if ($menu_items) {
        foreach ($menu_items as $menu_item) {
        
          echo '<a href="' . $menu_item->url . '" aria-label="' . $menu_item->title . '">' . $menu_item->title . '</a>';

        }
      }  
      ?>
      </div>

    </div>

    <div class="footer-item">

      <h3 class="footer-headline">Kontakt</h3>

      <div class="footer-contact">
        
        <a href="tel:+420777006392">+420 777 006 392</a>

        <a href="mailto:info@plusfitness.cz">info@plusfitness.cz</a>

        <a target="_blank" id="need-trainers" href="#">Sháníme nové trenéry</a>

        <a target="_blank" id="contract" href="#">Provozní řád a obchodní podmínky</a>
      
      </div>
    
    </div>

    <div class="footer-item">
      
      <a target="_blank" href="https://www.facebook.com/plusfitko" class="facebook-link">
        
        <span>Sledujte nás na Facebooku</span>
        
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/facebook-thumbnail.png" alt="náhledovka naší facebook stránky">
      
      </a>
    
    </div>

  </div>

  <h2 id="location" class="footer-location shadow">Kde nás najdete - Masarykovo náměstí 93, Rokycany</h2>

  <iframe style="border:none" src="https://frame.mapy.cz/s/humobobenu" width="100%" height="750px" frameborder="0"></iframe>

  <div class="copyright container">
    
    <span>Copyright &copy; <?php echo date_i18n('Y'); ?> Plus Fitness Rokycany</span>

    <span>Made By <b>Jakub Žák.</b></span>

  </div>  

</footer>
<?php wp_footer(); ?>
</body>
</html>