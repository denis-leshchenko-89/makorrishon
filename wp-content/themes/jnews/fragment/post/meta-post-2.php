<?php
$single = JNews\Single\SinglePost::getInstance();
$orderWriter = get_post_meta(get_the_ID(), 'orderWriter', true);

//$writerarr = wp_get_object_terms(get_the_ID(), 'writer', array('orderby'=>'term_order'));

//$writer = wp_get_post_terms(get_the_ID(), 'writer')[0];
//$writer->image = get_field('image', 'writer_' . $writer->term_id);
//$writer->twitter = get_field('twitter', 'writer_' . $writer->term_id);
//$writerarr = wp_get_post_terms(get_the_ID(), 'writer');

if($orderWriter !== NULL){
    $writerarr = wp_get_object_terms(get_the_ID(), 'writer', array('orderby'=>'term_order'));
}else{
    $writerarr = wp_get_object_terms(get_the_ID(), 'writer');
}

?>
<div class="jeg_post_meta jeg_post_meta_2">

    <!-- Nadav Edition -->
    <?php if($single->show_author_meta()) : ?>
        <?php if(!empty($writerarr)): ?>
            <div class="jeg_meta_author">
                <!-- Nadav editing -->

                <span class="meta_text"><?php jnews_print_translation('by', 'jnews', 'by'); ?>&nbsp</span>
                <?php foreach ( $writerarr as $writer): ?>
                    <?php    $writer->image = get_field('image', 'writer_' . $writer->term_id); ?>
                    <?php if(!empty($writer->image) && $writer): ?>
                        <img src="<?=$writer->image['sizes']['jnews-avatar-90']?>"
                             class="avatar avatar-90 photo"
                    <?php endif; ?>
                    <span class="jeg_meta_author a">
                    <a href="<?=get_term_link($writer->term_id)?>" >
                        <?php echo $writer->name; ?>
                    </a></span>
                    <?php if($writer !== end($writerarr)) echo ", ";?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
	<?php if ( $single->show_date_meta() ) : ?>
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

	<div class="meta_right">
		<?php do_action( 'jnews_render_before_meta_right', get_the_ID() ); ?>
		<?php if ( $single->show_comment_meta() ) : ?>
			<div class="jeg_meta_comment"><a href="<?php echo jnews_get_respond_link(); ?>"><i
						class="fa fa-comment-o"></i> <?php echo esc_html( jnews_get_comments_number() ); ?></a></div>
		<?php endif; ?>
	</div>
</div>
