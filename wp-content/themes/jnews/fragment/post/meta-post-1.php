<?php
$single = JNews\Single\SinglePost::getInstance();
$writer = wp_get_post_terms(get_the_ID(), 'writer')[0];
$writers = wp_get_post_terms(get_the_ID(), 'writer');
//$writer->image = get_field('image', 'writer_' . $writer->term_id);
//$writer->twitter = get_field('twitter', 'writer_' . $writer->term_id);
?>
<div class="jeg_post_meta jeg_post_meta_1">

	<div class="meta_left">
        <!-- Nadav Edition -->
        <?php if($single->show_author_meta()) : ?>


        <div class="jeg_meta_author">
                <?php
                $first = true;
                foreach ( $writers as $writr): ?>
            <?php if(!empty($writr->term_id)): ?>
                <?php    $writr->image = get_field('image', 'writer_' . $writr->term_id); ?>
                        <?php        if ($first){ ?>
                    <span class="meta_text"><?php jnews_print_translation('by', 'jnews', 'by'); ?>&nbsp;</span>
            <?php } ?>
                        <?php if(!empty($writr->image) && $writr): ?>
                    <img src="<?=$writr->image['sizes']['jnews-avatar-90']?>"
                             class="avatar avatar-90 photo"
                    <?php endif; ?>
                    <?php endif; ?>

            <?php


            if(!empty($writr->term_id)): ?>

            <span class="meta_text"><a href="<?=get_term_link($writr->term_id)?>">
                        <?php echo $writr->name; ?>
                    </a></span>
                <?php if($writr !== end($writers)) echo ", ";?>
                <?php
                $first = false;
            endif;
            endforeach;  ?>

        </div>

        <?php endif; ?>
		<?php if ( $single->show_date_meta() ) : ?>
        <!--Ninaa display date from old jnews -->
<!--			<div class="jeg_meta_date">-->
<!--				<a href="--><?php //the_permalink(); ?><!--">--><?php //echo esc_html( $single->post_date_format( $post ) ); ?><!--</a>-->
<!--			</div>-->
            <div class="jeg_meta_date">
                <a href="<?php the_permalink(); ?>"><?php echo esc_html(substr(get_the_date(null, $post), 0, -1). " ".get_the_date(get_option('time_format'), $post).")"); ?></a>
            </div>
		<?php endif; ?>
		<?php if ( $single->show_category_meta() ) : ?>
			<div class="jeg_meta_category">
				<span><span class="meta_text"><?php jnews_print_translation( 'in', 'jnews', 'in' ); ?></span>
					<?php the_category( ', ' ); ?>
				</span>
			</div>
		<?php endif; ?>

		<?php do_action( 'jnews_render_after_meta_left' ); ?>
	</div>

	<div class="meta_right">
		<?php do_action( 'jnews_render_before_meta_right', get_the_ID() ); ?>
		<?php if ( $single->show_comment_meta() ) : ?>
			<div class="jeg_meta_comment"><a href="<?php echo jnews_get_respond_link(); ?>"><i
						class="fa fa-comment-o"></i> <?php echo esc_html( jnews_get_comments_number() ); ?></a></div>
		<?php endif; ?>
	</div>
</div>
