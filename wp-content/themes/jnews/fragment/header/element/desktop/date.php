<div class="jeg_nav_item jeg_top_date">
    <?php echo date_i18n( get_theme_mod('jnews_header_date_format', 'l, F j, Y')  );  
     /*Nina - add hebrew date */
     if ( shortcode_exists( 'today_hebdate' ) ) {
       echo (' | '); 
       echo do_shortcode('[today_hebdate]');} ?>
</div>