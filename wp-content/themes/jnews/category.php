<?php
    get_header();
    $term = get_queried_object();
    $category = new \JNews\Category\Category($term);
?>

<div class="jeg_main <?php $category->main_class(); ?>">
    <div class="jeg_container">
        <?php do_action('jnews_before_main'); ?>
        <div class="jeg_content">
            <div class="jnews_category_header_top">
                <?php echo jnews_sanitize_output( $category->render_header('top') ); ?>
            </div>

            <div class="jeg_section">
                <div class="container">

                    <?php do_action('jnews_archive_above_hero'); ?>

                    <div class="jnews_category_hero_container">
                        <?php echo jnews_sanitize_output( $category->render_hero() ); ?>
                    </div>

                    <?php do_action('jnews_archive_below_hero'); ?>
                    
                    <div class="jeg_cat_content row">
                        <div class="jeg_main_content jeg_column col-sm-<?php echo esc_attr($category->get_content_width()); ?>">
                            <div class="jeg_inner_content">
                                <div class="jnews_category_header_bottom">
                                    <?php echo jnews_sanitize_output( $category->render_header('bottom') ); ?>
                                </div>
                                <div class="jnews_category_content_wrapper">
                                    <?php echo jnews_sanitize_output( $category->render_content() ); ?>
                                </div>
                            </div>
                        </div>
	                    <?php $category->render_sidebar(); ?>
                    </div>

                    <div class="jeg_cat_content row">

                        <div class="jeg_column col-sm-8">
                            <?php
                            echo('<div class="OUTBRAIN" id="outbrainContainerSF1" data-widget-id="SF_1"></div>
       <script>
                     (function(){
                                     var outbrainContainerSF1 = document.getElementById("outbrainContainerSF1");
                                     outbrainContainerSF1.setAttribute("data-src", location.href );

                                     var launcherScript = document.createElement("script");
                                     launcherScript.setAttribute("async", "async");
                                     launcherScript.setAttribute("src", "https://widgets.outbrain.com/outbrain.js");
                                     outbrainContainerSF1.parentNode.insertBefore(launcherScript, outbrainContainerSF1.nextSibling);
                     })();
       </script>');
                            ?>
                        </div>
                        <div class="jeg_column col-sm-4">
                            <?php
                            echo '<div id="dfp_left_big_2" class="makor_desktop_ad makor_ad"><script type="text/javascript">' .
                                'googletag.cmd.push(function() { googletag.display("dfp_left_big_2"); });' .
                                '</script></div>';
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php do_action('jnews_after_main'); ?>
    </div>
</div>


<?php get_footer(); ?>