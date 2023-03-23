        <div class="footer-holder" id="footer" data-id="footer">
            <?php
                $footer_style = get_theme_mod('jnews_footer_style', '1');
                if($footer_style === 'custom') {
                    get_template_part('fragment/footer/footer-custom');
                } else {
                    get_template_part('fragment/footer/footer-' . $footer_style);
                }
            ?>
        </div>

        <div class="jscroll-to-top">
        	<a href="#back-to-top" class="jscroll-to-top_link"><i class="fa fa-angle-up"></i></a>
        </div>
    </div>

    <?php
        get_template_part('fragment/header/mobile-menu');

        if(apply_filters('jnews_can_render_account_popup', false))
        {
            get_template_part('fragment/account/account-popup');
        }

        wp_footer();
    ?>
        <!-- Makor Form Ajax Requests -->
        <script>
            document.addEventListener( 'wpcf7mailsent', function( event ) {
                if(location) {
                    var form = jQuery(event.srcElement).find('form')[0];
                    if(form) {
                        var id = form.id;   // therefor the id of the form should be the form name in the forms server !!!
                        var data = event.detail ? event.detail.inputs : [];

                        function ajaxMakorForm(action) {
                            jQuery.ajax({
                                url: jnews_ajax_url,
                                type: "post",
                                dataType: "json",
                                data: {
                                    action : action,
                                    data: data
                                }
                            });
                        }

                        if(id && id.length) {
                            ajaxMakorForm(id);
                        }
                    }
                }
            }, false );
        </script>
<!--?php wp_footer(); ?-->
</body>
</html>
