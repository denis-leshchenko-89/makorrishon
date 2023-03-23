<?php
$single = JNews\Single\SinglePost::getInstance();
if(have_posts()) : the_post();
?>


<div class="jeg_featured_big">
    <div class="jeg_featured_bg">
        <?php the_post_thumbnail('full'); ?>
    </div>

    <div class="jeg_scroll_flag"></div>
</div>

<div class="container">
    <div class="jeg_ad jeg_article_top jnews_article_top_ads">
        <?php do_action('jnews_article_top_ads'); ?>
    </div>
</div>

<div class="jeg_content jeg_singlepage">
    <div class="container">
        <div class="row">
            <div class="jeg_main_content col-md-<?php echo esc_attr($single->main_content_width()); ?>">
                <div class="jeg_inner_content">
                    <div class="entry-header">
                        <?php do_action('jnews_single_post_before_title', get_the_ID());  ?>

                        <h1 class="jeg_post_title"><?php the_title(); ?></h1>

                        <?php if( ! $single->is_subtitle_empty() ) : ?>
                            <h2 class="jeg_post_subtitle"><?php echo esc_html($single->render_subtitle()); ?></h2>
                        <?php endif; ?>

                        <div class="jeg_meta_container"><?php $single->render_post_meta(); ?></div>
                    </div>

                    <?php do_action('jnews_share_top_bar', get_the_ID()); ?>

                    <?php do_action('jnews_single_post_before_content'); ?>

                    <div class="entry-content <?php echo esc_attr($single->share_float_additional_class()); ?>">
                        <div class="jeg_share_button share-float jeg_sticky_share clearfix <?php $single->share_float_style_class(); ?>">
                            <?php do_action('jnews_share_float_bar', get_the_ID()); ?>
                        </div>

                        <div class="content-inner <?php echo apply_filters('jnews_content_class', '', get_the_ID()) ?>">
                            <?php $categories = wp_get_post_categories($post->ID,'fields=ids');?>
                            <?php if(in_array(1, $categories) || in_array(6105, $categories) || in_array(6107, $categories) || in_array(879, $categories) || in_array(211, $categories) || in_array(199, $categories)){
                                echo ('<!-- ZEPHR_FEATURE article- -->');
                            } ?>
                            <?php the_content(); ?>
                            <?php wp_link_pages(); ?>

	                        <?php do_action('jnews_source_via_single_post'); ?>
                            <a class="oops_link" href="mailto:oops@makorrishon.co.il?subject=<?php echo get_permalink( $post->ID ); ?> כותרת הכתבה: <?php echo the_title(); ?>">טעינו? נתקן! אם מצאתם טעות בכתבה, נשמח שתשתפו אותנו</a>
                            <?php if( has_tag() ) { ?>
                                <div class="jeg_post_tags"><?php $single->post_tag_render(); ?></div>
                            <?php } ?>
                            <?php if(in_array(1, $categories) || in_array(6105, $categories) || in_array(6107, $categories) || in_array(879, $categories) || in_array(211, $categories) || in_array(199, $categories)){
                                echo ('<!-- ZEPHR_FEATURE_END article- -->');
                            } ?>
                        </div>
                        <?php do_action('jnews_share_bottom_bar', get_the_ID()); ?>

                        <?php do_action('jnews_push_notification_single_post'); ?>
                    </div>
	                <?php do_action('jnews_share_bottom_bar', get_the_ID()); ?>

	                <?php do_action('jnews_push_notification_single_post'); ?>

                    <?php do_action('jnews_single_post_after_content'); ?>
                </div>

            </div>
            <?php $single->render_sidebar(); ?>
        </div>

        <div class="jeg_ad jeg_article jnews_article_bottom_ads">
            <?php do_action('jnews_article_bottom_ads'); ?>
        </div>

    </div>
</div>

<?php endif; ?>
