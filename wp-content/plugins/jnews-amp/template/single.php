<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <?php do_action( 'amp_post_template_head', $this ); ?>
    <style amp-custom>
        <?php $this->load_parts( array( 'style' ) ); ?>
        <?php do_action( 'amp_post_template_css', $this ); ?>
    </style>
    <script async custom-element="amp-access" src="https://cdn.ampproject.org/v0/amp-access-0.1.js"></script>
    <!--    israelhayom-makor-rishon.cdn.zephr.com sdk-->
    <!--    israelhayom-staging-stage.cdn.zephr.com sdk-test-->
    <script id="amp-access" type="application/json">
        {
            "authorization" : "https://israelhayom-makor-rishon.cdn.zephr.com/zephr/decision-engine?sdkFeatureSlug=sdk&foreign_id.AMP_ID=READER_ID&path=SOURCE_PATH",
            "noPingback": true,

            "login": {
                "signin": "/login?rid=READER_ID&return=RETURN_URL"
            },
            "authorizationFallbackResponse": {"error": true}


        }
    </script>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php do_action( 'jnews_amp_before_header' ); ?>

<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php $this->load_parts( array( 'sidebar-menu' ) ); ?>

<?php do_action( 'jnews_amp_before_article' ); ?>

<article class="amp-wp-article">

    <div class="amp-wp-breadcrumb">
        <?php echo jnews_native_breadcrumb(); ?>
    </div>

    <header class="amp-wp-article-header">
        <!--Nina - from old jnews -->
        <!--		<h1 class="amp-wp-title">--><?php //echo wp_kses_data( $this->get( 'post_title' ) ); ?><!--</h1>-->
        <!--		--><?php //if ( ! empty( $subtitle = wp_kses( get_post_meta( get_the_ID(), 'post_subtitle', true ), wp_kses_allowed_html() ) ) ) : ?>
        <!--			<h2 class="amp-wp-subtitle">--><?php //echo esc_html( $subtitle ); ?><!--</h2>-->
        <!--		--><?php //endif; ?>
        <!--		<ul class="amp-wp-meta">-->
        <!--			--><?php //$this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author', 'meta-time' ) ) ); ?>
        <!--		</ul>-->
        <?php
        $writer = $this->get( 'writerdata' );
        $post_type = $this->get( 'post_type' );
        if($writer->image && $post_type == 'opinion') { ?>
            <div class="jeg_author_wrap vcard">
                <div class="jeg_author_image">
                    <img src="<?=$writer->image['sizes']['jnews-avatar-90'] ?> " width='90' height='90'
                         class="attachment-jnews-90x90 size-jnews-90x90 wp-post-image lazyautosizes lazyloaded">
                </div>
                <div class="jeg_author_content">
                    <h3 class="jeg_author_name fn">
                        <a href="<?=get_term_link($this->get( 'writerId' ))?>">
                            <?php echo $this->get( 'writer' ); ?> </a>
                    </h3>
                    <p class="jeg_author_desc">
                        <?= $this->get( 'writerdescription' ) ?>
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
        <?php }
        ?>
        <h1 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>
        <!--Nina - add subtitle
        <h3 class="jeg_post_subtitle" style="font-weight:normal"><//?php echo $this->get( 'post_sub_title' ); ?></h3>-->
        <?php if ( ! empty( $subtitle = wp_kses( get_post_meta( get_the_ID(), 'post_subtitle', true ), wp_kses_allowed_html() ) ) ) : ?>
            <h2 class="jeg_post_subtitle amp-wp-subtitle" style="color:#212121;"><?php echo esc_html( $subtitle ); ?></h2>
        <?php endif; ?>
        <ul class="amp-wp-meta">
            <?//php //Nina - add check if there is a writer ?>
            <?//php  //if($this->get( 'writerId' )){ ?>
<!--            <span class="meta_text"> ?php jnews_print_translation('by', 'jnews', 'by'); ?><&nbsp;</span>-->
            <?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author' ) ) ); ?>
                <?//php  } ?>
                <div class="amp_meta_date" style="display:inline;">
                    <a href="<?php the_permalink(); ?>"><?php echo esc_html(substr(get_the_date(null, $post), 0, -1). " ".get_the_date(get_option('time_format'), $post).")"); ?></a>
                </div>

        </ul>



    </header>

    <?php $this->load_parts( array( 'featured-image' ) ); ?>

    <div class="amp-wp-share">
        <?php do_action('jnews_share_amp_bar', get_the_ID()); ?>
    </div>
    <?php
    $categories = wp_get_post_categories(get_the_ID(), 'fields=ids');
    if(in_array(1, $categories) || in_array(43, $categories) || in_array(6105, $categories) || in_array(6107, $categories) || in_array(45, $categories) || in_array(879, $categories) || in_array(211, $categories) || in_array(199, $categories)){
        $site_url = get_site_url(null,'wp-content/uploads/2022/02/makorrishon-rishum1.png');
        $banner = get_site_url(null,'wp-content/uploads/2022/02/כותרתמקורראשון1.png');?>
        <section amp-access="outputValue = 'deny'">
            <h3  style="color: #212638; padding-right:3px; text-align: center;">לצפיה או התחברות לכתבות מקור ראשון</h3>
            <div style="text-align: center;">
                <img style="text-align: center;" src="<?= $banner; ?>" />
                <img style="text-align: center;" src="<?= $site_url; ?>" />
            </div>
            <?php $link = get_permalink() ?>
            <a href="<?= $link; ?>" class="button">המשך</a>

        </section>
        <?php
        do_action( 'jnews_amp_after_content2' );
    } else { ?>
    <section amp-access="outputValue = 'allow'">
        You  have access. </section>
    <?php do_action( 'jnews_amp_before_content' ); ?>
    <div class="amp-wp-article-content">
        <?php echo jnews_sanitize_output( $this->get( 'post_amp_content' ) ); ?>
    </div>

    <ul class="amp-wp-meta-taxonomy"><?php $this->load_parts( array( 'meta-taxonomy') ); ?></ul>

    <?php do_action( 'jnews_amp_after_content' ); ?>
    <?php do_action( 'jnews_amp_after_content2' ); ?>

</article>
<?php }  ?>



<?php do_action( 'jnews_amp_after_article' ); ?>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

</body>
</html>