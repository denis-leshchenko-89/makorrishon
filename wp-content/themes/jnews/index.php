<?php
$index = new \JNews\Archive\IndexArchive();
get_header();
?>

    <div class="jeg_main <?php $index->main_class(); ?>">
		<div class="jeg_container">
			<div class="jeg_content">
				<div class="jeg_vc_content">
					<?php
						$index->render_top_content();
					?>
				</div>
				<div class="jeg_section">
					<div class="container">
						<div class="jeg_cat_content row">
							<div class="jeg_main_content col-sm-<?php echo esc_attr($index->get_content_width()); ?>">
                                <div class="jeg_archive_header">
                                    <?php
                                    if(!is_home())
                                    {
                                        the_archive_title( '<h1 class="jeg_archive_title">', '</h1>' );
                                    }
                                    ?>
                                </div>
                                <div class="jeg_postblock_3 jeg_postblock">
                                    <div class="jeg_posts jeg_block_container">
                                        <div class="jeg_posts">

                                            <?php
                                            if ( have_posts() ) :

                                                add_filter('excerpt_more', 'jnews_excerpt_more');
                                                add_filter('excerpt_length', 'jnews_excerpt_length');

                                                $archive = array();

                                                while ( have_posts() ) :
                                                    the_post();
                                                    do_action('jnews_json_archive_push', get_the_ID());
                                                    ?>

                                                    <div id="post-<?php the_ID(); ?>" <?php post_class('jeg_post jeg_pl_md_2'); ?>>
                                                        <?php if(has_post_thumbnail()) : ?>
                                                            <div class="jeg_thumb">
                                                                <?php echo jnews_edit_post( get_the_ID() ); ?>
                                                                <a href="<?php the_permalink(); ?>"><?php echo apply_filters('jnews_image_thumbnail', get_the_ID(), "jnews-350x250");?></a>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="jeg_postblock_content">
                                                            <h1 class="jeg_post_title">
                                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </h1>
                                                            <div class="jeg_post_meta">
                                                                <div class="jeg_meta_author"><?php jnews_print_translation('by', 'jnews', 'by'); ?> <?php jnews_the_author_link(); ?></div>
                                                                <div class="jeg_meta_date"><a href="<?php the_permalink(); ?>"><i class="fa fa-clock-o"></i> <?php echo esc_html( get_the_date() ); ?></a></div>
                                                                <div class="jeg_meta_comment"><a href="<?php echo jnews_get_respond_link() ?>"><i class="fa fa-comment-o"></i> <?php echo esc_html(jnews_get_comments_number()); ?></a></div>
                                                            </div>
                                                            <div class="jeg_post_excerpt">
                                                                <?php the_excerpt(); ?>
                                                                <a href="<?php the_permalink(); ?>" class="jeg_readmore"><?php jnews_print_translation('Read more','jnews', 'read_more'); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php
                                                endwhile;

                                                // pagination
                                                echo jnews_paging_navigation(array(
                                                    'pagination_mode' => 'nav_1',
                                                    'pagination_align' => 'center',
                                                    'pagination_navtext' => false,
                                                    'pagination_pageinfo' => false,
                                                ));

                                                remove_filter('excerpt_more', 'jnews_excerpt_more');
                                                remove_filter('excerpt_length', 'jnews_excerpt_length');

                                            endif;
                                            ?>

                                        </div>
                                    </div>
                                </div>
								<div class="jnews_index_content_wrapper">
									<?php echo jnews_sanitize_output( $index->render_content() ); ?>
								</div>

								<?php echo jnews_sanitize_output( $index->render_navigation() ); ?>
							</div>
							<?php $index->render_sidebar(); ?>
						</div>
					</div>
				</div>

			</div>
			<?php do_action('jnews_after_main'); ?>
		</div>
	</div>

<?php get_footer(); ?>