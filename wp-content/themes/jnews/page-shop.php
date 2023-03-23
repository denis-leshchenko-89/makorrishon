<?php
get_header();
?>
    <div class="jeg_main">
        <div class="jeg_container">
            <?php do_action('jnews_before_main'); ?>
            <div class="jeg_content jeg_singlepage">
                <div class="container">
                    <?php
                    the_post();
                    the_content();
                    ?>
                </div>
            </div>
            <?php do_action('jnews_after_main'); ?>
        </div>
    </div>
<?php get_footer();