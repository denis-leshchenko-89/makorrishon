<?php
get_header();
echo('taxonomy-writer');

$writer = get_queried_object();

$writer->image = get_field('image', 'writer_' . $writer->term_id);
$writer->facebook = get_field('facebook', 'writer_' . $writer->term_id);
$writer->twitter = get_field('twitter', 'writer_' . $writer->term_id);

$writerArchive = new \JNews\Makor\WriterArchive($writer);

//$author = new \JNews\Archive\AuthorArchive();
//$url = get_author_posts_url(get_the_author_meta('ID'));
$section = isset($_REQUEST['section']) ? esc_html($_REQUEST['section']) : '';
?>

    <div class="jeg_main">
        <div class="jeg_container">
            <?php do_action('jnews_before_main'); ?>
            <div class="jeg_content">

                <div class="jeg_section">
                    <div class="container">

                        <div class="jeg_archive_header jeg_authorpage clearfix">

<!--                            --><?php //if(jnews_can_render_breadcrumb()) : ?>
<!--                                <div class="jeg_breadcrumbs jeg_breadcrumb_container">-->
<!--                                    --><?php //echo jnews_sanitize_output( $author->render_breadcrumb() ); ?>
<!--                                </div>-->
<!--                            --><?php //endif; ?>

                            <div class="jeg_author_wrap vcard">
                                <div class="jeg_author_image">
<!--                                    --><?php //echo get_avatar( get_the_author_meta( 'ID' ), 110, null, get_the_author_meta('display_name') ) ?>
                                    <?php
                                        if($writer->image) {
                                            echo '<img src="' . $writer->image['sizes']['jnews-avatar-90'] . '" class="avatar avatar-90 photo"></a>';
                                        }
                                    ?>
                                </div>
                                <div class="jeg_author_content">
                                    <h3 class="jeg_author_name fn">
<!--                                        --><?php //echo esc_html(get_the_author_meta('display_name')); ?>
                                        <?= $writer->name ?>
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


                        <div class="jeg_cat_content row">

<!--                            <div class="jeg_main_content jeg_column col-sm---><?php //echo esc_attr($author->get_content_width()); ?><!--">-->
<!--                            <div class="jeg_main_content jeg_column col-sm---><?php //echo esc_attr($author->get_content_width()); ?><!--">-->
                            <div class="jeg_main_content jeg_column col-sm-12">

                                <?php if ( defined( 'JNEWS_REVIEW' ) ): ?>
                                    <ul class="authorlink">
                                        <li class="<?php echo esc_attr($section === '' ? 'active' : '');  ?>">
<!--                                            <a href="--><?php //echo esc_url($url); ?><!--">--><?php //jnews_print_translation('All', 'jnews', 'all'); ?><!--</a>-->
                                        </li>
                                        <li class="<?php echo esc_attr($section === 'review' ? 'active' : '');  ?>">
<!--                                            <a href="--><?php //echo esc_url(add_query_arg(array('section' => 'review'), $url)); ?><!--">--><?php //jnews_print_translation('Reviews', 'jnews', 'reviews'); ?><!--</a>-->
                                        </li>
                                    </ul>
                                <?php endif ?>

                                <div class="jnews_author_content_wrapper">
                                    <?php echo jnews_sanitize_output( $writerArchive->render_content() ); ?>
                                </div>
                            </div>

<!--                            --><?php //if($author->get_content_show_sidebar()) : ?>
<!--                                <div class="jeg_sidebar --><?php //echo esc_attr( $author->get_sticky_sidebar() ); ?><!-- jeg_column col-sm-4">-->
<!--                                    --><?php //jnews_widget_area( $author->get_content_sidebar() ); ?>
<!--                                </div>-->
<!--                            --><?php //endif; ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php do_action('jnews_after_main'); ?>
        </div>
    </div>

<?php get_footer(); ?>