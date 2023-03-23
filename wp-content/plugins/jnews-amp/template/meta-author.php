<?php //$post_author = $this->get( 'post_author' ); ?>
<?php $writer = wp_get_post_terms(get_the_ID(), 'writer')[0];
$path_writer="writer/".$writer->slug;
$writer->image = get_field('image', 'writer_' . $writer->term_id);
?>

<li class="amp-wp-byline">
	<?php if ( function_exists( 'get_avatar_url' ) ) : ?>
    <span class="amp-wp-author"><?php echo jnews_return_translation( 'By', 'jnews-amp', 'by' );   ?> <amp-img src="<?php echo $writer->image['sizes']['jnews-avatar-90']?>" width="35" height="35" layout="fixed"></amp-img>
	<?php endif; ?>
	<a href="<?php echo get_site_url( "",$path_writer ); ?>"><?php echo esc_html( $writer->name ); ?></a></span>
</li>
