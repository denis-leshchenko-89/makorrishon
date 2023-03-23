<?php
$single = JNews\Single\SinglePost::getInstance();
$writer = wp_get_post_terms(get_the_ID(), 'writer')[0];
$writer->image = get_field('image', 'writer_' . $writer->term_id);
$writer->facebook = get_field('facebook', 'writer_' . $writer->term_id);
$writer->twitter = get_field('twitter', 'writer_' . $writer->term_id);

?>
<div class="jeg_content jeg_singlepage">

    <div class="container">

        <div class="jeg_ad jeg_article jnews_article_top_ads">
            <?php do_action('jnews_article_top_ads'); ?>
        </div>
        <?php $categories = wp_get_post_categories($post->ID,'fields=ids');?>
        <?php if(in_array(25203, $categories)){
            do_action('jnews_rishonot_banner');
        } ?>
        <div class="jeg_archive_header jeg_authorpage clearfix">

            <div class="jeg_author_wrap vcard">
                <div class="jeg_author_image">
                    <!--                                    --><?php //echo get_avatar( get_the_author_meta( 'ID' ), 110, null, get_the_author_meta('display_name') ) ?>
                    <?php
                    if($writer->image) {
                        echo '<img src="' . $writer->image['sizes']['jnews-avatar-90'] . '" class="avatar avatar-90 photo"  title="'.$writer->image['alt'].'" alt="'.$writer->image['alt'].'"></a>';
                    }
                    ?>
                </div>
                <div class="jeg_author_content">
                    <h3 class="jeg_author_name fn">
                        <a href="">
                            <!--                                        --><?php //echo esc_html(get_the_author_meta('display_name')); ?>
                            <?= $writer->name ?>
                        </a>
                    </h3>
                    <p class="jeg_author_desc">
                        <!--                                        --><?php //if ( get_the_author_meta( 'description') ) :
                        //                                            the_author_meta( 'description');
                        //                                        endif; ?>
                        <?= $writer->description ?>
                    </p>
                    <div class="jeg_author_socials">
                        <?php if(!empty($writer->facebook)):?>
                            <a href="<?= $writer->facebook ?>" rel="nofollow" class="facebook">
                                <i class="fa fa-facebook-official"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($writer->twitter)):?>
                            <a href="<?= $writer->twitter?>" rel="nofollow" class="twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="jeg_main_content col-md-<?php echo esc_attr($single->main_content_width()); ?>">
                <div class="jeg_inner_content">
                    <?php if(have_posts()) : the_post(); ?>

                        <?php if(jnews_can_render_breadcrumb()) : ?>
                            <div class="jeg_breadcrumbs jeg_breadcrumb_container">
                                <?php $single->render_breadcrumb(); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-header">
                            <?php do_action('jnews_single_post_before_title', get_the_ID());  ?>

                            <h1 class="jeg_post_title"><?php the_title(); ?></h1>

                            <?php if( ! $single->is_subtitle_empty() ) : ?>
                                <h2 class="jeg_post_subtitle"><?php echo esc_html($single->render_subtitle()); ?></h2>
                            <?php endif; ?>

                            <div class="jeg_meta_container"><?php $single->render_post_meta(); ?></div>
                        </div>
                        <?php do_action('jnews_single_post_before_content'); ?>
                        <?php $single->render_featured_post(); ?>

                        <?php do_action('jnews_share_top_bar', get_the_ID()); ?>



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


                        </div>
                        <?php do_action('jnews_share_bottom_bar', get_the_ID()); ?>

                        <?php do_action('jnews_push_notification_single_post'); ?>

                        <?php do_action('jnews_single_post_after_content'); ?>

                    <?php endif; ?>
                </div>
            </div>
            <?php $single->render_sidebar(); ?>
        </div>

        <div class="jeg_ad jeg_article jnews_article_bottom_ads">
            <?php do_action('jnews_article_bottom_ads'); ?>
        </div>

    </div>
</div>