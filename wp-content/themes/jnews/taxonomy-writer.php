<?php
$index = new \JNews\Archive\IndexArchive();
//$writerArchive = new \JNews\Makor\WriterArchive($writer);
$writer = get_queried_object();
$writer->image = get_field('image', 'writer_' . $writer->term_id);
$writer->facebook = get_field('facebook', 'writer_' . $writer->term_id);
$writer->twitter = get_field('twitter', 'writer_' . $writer->term_id);

get_header();
?>

    <div class="jeg_main <?php $index->main_class(); ?>">
        <div class="jeg_container">
            <?php do_action('jnews_before_main'); ?>
            <div class="jeg_content">
                <div class="jeg_vc_content">
                    <?php
                    $index->render_top_content();
                    ?>
                </div>
                <div class="jeg_section">
                    <div class="container">

                                <div class="jeg_archive_header jeg_authorpage clearfix">

                                    <div class="jeg_author_wrap vcard">
                                        <div class="jeg_author_image">
                                            <?php
                                            if($writer->image) {
                                                echo '<img src="' . $writer->image['sizes']['jnews-avatar-90'] . '" class="avatar avatar-90 photo"></a>';
                                            }
                                            ?>
                                        </div>
                                        <div class="jeg_author_content">
                                            <h3 class="jeg_author_name fn">
                                                <?= $writer->name ?>
                                            </h3>
                                            <p class="jeg_author_desc">
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
                                    <div class="jeg_main_content jeg_column col-sm-12">
                                        <div class="jeg_posts">

                                            <?php
                                            if ( have_posts() ) :

                                                add_filter('excerpt_more', 'jnews_excerpt_more');
                                                add_filter('excerpt_length', 'jnews_excerpt_length');

                                                $archive = array();



                                                // pagination
//                                                echo jnews_paging_navigation(array(
//                                                    'pagination_mode' => 'nav_1',
//                                                    'pagination_align' => 'center',
//                                                    'pagination_navtext' => false,
//                                                    'pagination_pageinfo' => false,
//                                                ));

                                                remove_filter('excerpt_more', 'jnews_excerpt_more');
                                                remove_filter('excerpt_length', 'jnews_excerpt_length');

                                            endif;
                                            ?>
                                            <div class="jnews_index_content_wrapper">
                                                <?php echo jnews_sanitize_output( $index->render_content() ); ?>
                                            </div>
                                        </div>
                                    </div>



                                <?php echo jnews_sanitize_output( $index->render_navigation() ); ?>
                            </div>


                    </div>
                </div>

            </div>
            <?php do_action('jnews_after_main'); ?>
        </div>
    </div>

<?php get_footer(); ?>