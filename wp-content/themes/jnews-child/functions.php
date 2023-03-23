<?php
/**
 * @author : Jegtheme
 */


/**
 * Load parent theme style
 */
add_action( 'wp_enqueue_scripts', 'jnews_child_enqueue_parent_style' );

function jnews_child_enqueue_parent_style()
{
    wp_enqueue_style( 'jnews-parent-style', get_parent_theme_file_uri('/style.css'));
}

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
    if (current_user_can('administrator') && is_admin()) {
        show_admin_bar(true);
    }
}

/*********************************************
 * Makor - added functions by Nadav & Oshri  *
 ********************************************/



/**
 * Switch the title with title for front page for front page articles
 * @param $title
 * @param null $id
 * @return mixed
 */
function set_title_for_front_page( $title, $id = null ) {

    if(is_page()) {
        $post = get_post($id);
        if($post && (($post->post_type == 'post') || (is_front_page() || is_page ('347131')))) {
        //    if($post && ($post->post_type == 'post')) {
            $headlineMainPage = get_field('headline_main_page', $id);
            $title = $headlineMainPage ? $headlineMainPage : $title;
        }
    }

    return $title;
}
add_filter( 'the_title', 'set_title_for_front_page', 10, 2 );

/**
 * Switch the subtitle with title for front page for front page articles
 * @param $excerpt
 * @param null $id
 * @return bool|mixed
 */
function set_subtitle_for_front_page($excerpt, $id = null) {
    // return sub headline for main page if this is the main page
    if(is_page()) {
        $subheadline = get_field('subheadline_main_page', $id);
        $excerpt = $subheadline ? $subheadline : $excerpt;
    }
    return $excerpt;
}

add_filter( 'jnews_module_excerpt', 'set_subtitle_for_front_page', 10, 2);

/**********************
 * VIDEO TYPE SECTION *
 **********************/

/**
 * Responsible for creating the insert brightcove button into the editor
 */
function add_bright_cove_button() {
    echo '<button type="button" id="insert-brightcove" class="button add_media insert-brightcove" style="margin-left: 7px">'.
        '<span class="dashicons dashicons-playlist-video"></span>Add Ilh video'.
        '</button>';
}

add_action('media_buttons', 'add_bright_cove_button');

/**
 * Include the /js/media_buttons.js file
 */
function include_media_button_js_file() {

    wp_enqueue_script('media_button', get_template_directory_uri().'/assets/js/media-buttons.js', array('jquery'), '1.0', true);
}

/**
 * Translates the short code [ih-video] to the html code
 */
add_action('wp_enqueue_media', 'include_media_button_js_file');
/**
 * injecting the video types meta box and the modal
 */
add_action( 'media_buttons', 'video_type_inner_custom_box' );

function video_type_inner_custom_box(){
    // will add this action to the footer otherwise it will happened twice
    add_action( 'admin_footer', 'output_tinymce_modal');
}

function output_tinymce_modal() {

    $allVideoTypes = get_terms(array(
        'taxonomy' => 'videotype',
        'hide_empty' => false,
    ));

    echo '<style>' .
        '#ih-admin-modal-backdrop {display: none;position: fixed;top: 0;left: 0;right: 0;bottom: 0;min-height: 360px;background: #000;opacity: 0.7;filter: alpha(opacity=70);z-index: 100100;}' .
        '#ih-admin-modal-wrap {display: none;background-color: #fff; -webkit-box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );width: 500px;margin-left: -250px;margin-top: -125px;position: fixed;top: 50%;left: 50%;z-index: 100105; -webkit-transition: height 0.2s, margin-top 0.2s;transition: height 0.2s, margin-top 0.2s;}' .
        '#ih-admin-modal {color: #666;padding: 0;position: absolute;top: 0;right: 0;height: 36px;text-align: center;background: none;border: none;cursor: pointer;}' .
        '#ih-admin-modal-close {color: #666; padding: 0; position: absolute;top: 0;right: 0;width: 36px;height: 36px;text-align: center;background: none;border: none;cursor: pointer;}' .
        '#ih-admin-modal-close:before {font: normal 20px/36px \'dashicons\';vertical-align: top;speak: none; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;width: 36px;height: 36px;content: \'\\f158\';}' .
        '#ih-admin-modal-title {background: #fcfcfc;border-bottom: 1px solid #dfdfdf;height: 36px;font-size: 18px;font-weight: 600;line-height: 36px;padding: 0 36px 0 16px;top: 0;right: 0;left: 0;}' .
        '#ih-admin-modal-cancel {line-height: 25px; float: left}' .
        '#ih-admin-modal .ih-submitbox {padding: 8px 16px;background: #fcfcfc;border-top: 1px solid #dfdfdf;position: absolute;bottom: 0;left: 0;right: 0;}' .
        '#ih-admin-modal-update {line-height: 23px;float: right;}' .
        '.ih-form-row {text-align: left}' .
        '.ih-label {display: inline-block; margin-right: 5px; width: 100px;}' .
        '#ih-admin-modal input.invalid {border: 1px solid red;}' .
        '#ih-admin-modal .submitdelete {color: #a00;text-decoration: none;padding: 1px 2px;}' .
        '#ih-admin-modal-content {padding: 0 16px 50px;}' .
        '#id-modal-contents-wrapper {direction: ltr;}' .
        '</style>' .
        '<div id="ih-admin-modal-backdrop"></div>' .
        '<div id="ih-admin-modal-wrap" class="wp-core-ui">' .
        '<div id="ih-admin-modal" tabindex="-1" style="position: relative;height: 100%">' .
        '<div id="ih-admin-modal-title">' .
        '<span id="ih-modal-title">Add video</span>' .
        '<button type="button" id="ih-admin-modal-close" class="modal-close">' .
        '<span class="screen-reader-text modal-close">Close</span>' .
        '</button>' .
        '</div>' .
        '<div id="id-modal-contents-wrapper" style="padding:20px;">' .
        '<div id="ih-admin-modal-content" class="admin-modal-inside">' .
        '<div class="ih-form-row">' .
        '<p class="ih-label">Video id</p>' .
        '<input type="text" id="ih-admin-modal-video-id">' .
        '</div>' .
        '<div class="ih-form-row">' .
        '<p class="ih-label">Video type</p>' .
        '<select id="ih-admin-modal-video-type">';

    forEach($allVideoTypes as $videoType) {
        echo '<option value="'.$videoType->term_id.'">'.$videoType->name.'</option>';
    }

    echo    '</select>' .
        '</div>' .
        '<div class="ih-form-row">' .
        '<p class="ih-label">Credit</p>' .
        '<input type="text" id="ih-admin-modal-video-credit">' .
//                        '<span type="text" id="ih-admin-modal-post-id" style="display: none">'.get_the_ID().'</span>' .
        '</div>' .
        '</div>' .
        '<div class="ih-submitbox" style="display:block;">' .
        '<div id="ih-admin-modal-cancel">' .
        '<a class="submitdelete deletion modal-close" href="#">Cancel</a>' .
        '</div>' .
        '<div id="ih-admin-modal-update">' .
        '<a class="button-primary" id="ih-insert-form">Insert</a>' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</div>' ;
}

/**
 * Inject the video type code
 * @param $atts
 * @return mixed|string
 */
function ih_video_settings($atts){
    $videoId = $atts['id'];
    $videoTypeId = $atts['type'];
    $credit = empty($atts['credit']) ? null : $atts['credit'];

    // get video type by it's id
    $video = get_term_by('id', $videoTypeId, 'videotype');
    $code = get_fields($video)['code'];

    // refine special characters to html code
    $htmlCode = htmlspecialchars_decode($code);

    // replace the credit
    if(!empty($credit)) {
        $htmlCode = str_replace('{{credit}}', 'Video: ' . $credit, $htmlCode);
    } else {
        $htmlCode = str_replace('{{credit}}', '' . $credit, $htmlCode);
    }

    // replace the id
    return empty($code) ? '' : (str_replace('{{id}}', $videoId, $htmlCode));
}

add_shortcode( 'ih-video', 'ih_video_settings' );

/**
 * Prevent the user from insert new writers from edit post screen
 * @param $term
 * @param $taxonomy
 * @return WP_Error
 */
function disallow_insert_term($term, $taxonomy) {

    $screen = get_current_screen();
    if ( $screen && $screen->base === 'post' && $taxonomy === 'writer') {

        return new WP_Error(
            'disallow_insert_term',
            __('Your role does not have permission to add terms to this taxonomy')
        );

    }
    return $term;
}
add_action( 'pre_insert_term', 'disallow_insert_term', 10, 2);

function add_widget_area() {
    global $post;
    if(is_single()):
    echo('<div id="dfp_article_strip2" class="makor_desktop_ad makor_ad">
<script type="text/javascript">
    googletag.cmd.push(function() { googletag.display("dfp_article_strip2"); });
</script>
</div>
<div id="dfp_mobile_article_strip_2" class="makor_mobile_ad makor_ad">
<script type="text/javascript">
    googletag.cmd.push(function() { googletag.display("dfp_mobile_article_strip_2"); });
</script>
</div>');

    echo('<div class="OUTBRAIN" id="outbrainContainerAR4" data-widget-id="AR_4"></div>
   <script>
                 (function(){
                                 var outbrainContainerAR4 = document.getElementById("outbrainContainerAR4");
                                 outbrainContainerAR4.setAttribute("data-src", location.href );

                                 var launcherScript = document.createElement("script");
                                 launcherScript.setAttribute("async", "async");
                                 launcherScript.setAttribute("src", "https://widgets.outbrain.com/outbrain.js");
                                 outbrainContainerAR4.parentNode.insertBefore(launcherScript, outbrainContainerAR4.nextSibling);
                 })();
   </script>');
    // contact form - free month
    printf('<div class="vc_wp_text wpb_content_element horizantalFreeMonth"><div class="widget widget_text"><div class="textwidget jeg_footer_heading"><h3><span>​עדיין לא מנויים על מקור ראשון? הצטרפו וקבלו חודש חינם במתנה</span></h3><p> *המבצע למצטרפים חדשים <a href="https://www.makorrishon.co.il/תקנון-הצטרפות-לקבלת-מנוי-לחודש/" target="_blank" rel="noopener"><u>בהתאם לתקנון המבצע</u></a></p><div class="formWrapper">%s</div></div></div></div>', do_shortcode('[contact-form-7 id="7767" title="קבל מנוי חינם לחודש" html_class="horizantal" html_id="isrh_form_makor1_subscription"]'));
    // Allow to disable per category POST IM

    $my_post_cat = wp_get_post_categories($post->ID);
    $expertCat = get_category_by_slug('expert');
    $disabled_cat = array( $expertCat->term_id); // this is he array of disabled categories. Feel free to edit this line as per your needs.
    $toDisabled = array_intersect($my_post_cat,$disabled_cat);

    $no_comments_field = get_field('no_comments');
    $toDisabled = $no_comments_field ? $no_comments_field : $toDisabled;

    /* post type expert add image on sidebar */
    if(in_array($expertCat->term_id, $my_post_cat)){
        add_filter('jnews_single_post_sidebar', 'expertPostSidebar', 2, 1);
    }

    /* do not show post IM on expert type of post */
    if(empty($toDisabled)) printf('<div class="spot-appearence"><div data-spotim-module="recirculation" data-spot-id="%s"></div><script async src="https://recirculation.spot.im/spot/%s"></script><script async src="https://launcher.spot.im/spot/%s" data-messages-count="2" data-spotim-module="spotim-launcher" data-post-url="%s" data-post-id="%s"></script></div>', SPOT_IM_ID, SPOT_IM_ID, SPOT_IM_ID, get_permalink(), get_the_ID());



    echo('<div class="OUTBRAIN" id="outbrainContainer" data-widget-id="AR_1"></div>
   <script>
                 (function(){
                                 var outbrainContainer = document.getElementById("outbrainContainer");
                                 outbrainContainer.setAttribute("data-src", location.href );

                                 var launcherScript = document.createElement("script");
                                 launcherScript.setAttribute("async", "async");
                                 launcherScript.setAttribute("src", "https://widgets.outbrain.com/outbrain.js");
                                 outbrainContainer.parentNode.insertBefore(launcherScript, outbrainContainer.nextSibling);
                 })();
   </script>');
endif;
}

/* post type expert add image on sidebar */
function expertPostSidebar($string){
printf('<img src="%s">', get_template_directory_uri().'/assets/img/expertsWBR.png');
return $string;
}
add_action( 'jnews_single_post_after_content', 'add_widget_area');

/**
* This function is responsible for setting terms for any new post that is created.
* Right now the settings are to set opinion category for every new opinion
* @param $post_id
* @param $post
*/
function set_default_object_terms( $post_id, $post ) {
if ($post->post_status === 'publish' && $post->post_type === 'opinion') {
    $defaults = array(
        'category' => array( 'opinion' )
    );
    $taxonomies = get_object_taxonomies( $post->post_type );
    foreach ( (array) $taxonomies as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy ); //wp_get_post_terms
        if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
            wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
        }
    }
}
}
add_action( 'save_post', 'set_default_object_terms', 0, 2 );

add_filter('widget_tag_cloud_args', 'tag_widget_limit');
//Limit number of tags inside widget
function tag_widget_limit($args){

//Check if taxonomy option inside widget is set to tags
if(isset($args['taxonomy']) && $args['taxonomy'] == 'post_tag'){
    $args['number'] = 15;
    $args['order_by'] = 'count';
    //Limit number of tags
}

return $args;
}
//hook dfp script
function hook_custom_makor_head() {

global $post;
if(is_single() && in_category(270, $post->ID)){
    $index_post = get_post_meta($post->ID, 'search_engines', true);
    if($index_post != 'NRG' && ('0' != get_option('blog_public'))){
        wp_no_robots();
    }
}

$slots = array(
    'dfp_premium' => array(
        'type'          => 'global',
        'name'          => 'Mkr_Desktop_{{channel}}_Premium',
        'sizes'         => '[[1, 1], [970, 90], [970, 250], [970, 600]]',
        'sizeMapping'   => array(
            '[1024,0]'     => '[[1, 1], [970, 90], [970, 250], [970, 600]]',
            '[0, 0]'        => '[]'
        )

    ),
    'dfp_mobile_premium' => array(
        'type'          => 'global',
        'name'          => 'Mkr_MobileWeb_{{channel}}_Premium',
        'sizes'         => '[[1, 1], [300, 200], [320, 100], [300, 250], [360, 360]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[1, 1], [300, 200], [320, 100], [300, 250], [360, 360]]',
            '[768, 0]'        => '[]'
        )
    ),

//        'dfp_ozen' => array(
//            'type'          => 'global',
//            'name'          => 'Mkr_Desktop_{{channel}}_Skyscraper_Right',
//            'sizes'         => '[[160, 600], [120, 600]]',
//            'sizeMapping'   => array(
//                '[1460,0]'     => '[[160, 600], [120, 600]]',
//                '[0, 0]'        => '[]'
//            )
//        ),
    'dfp_ozen' => array(
        'type'          => 'global',
        'name'          => 'Mkr_Desktop_{{channel}}_Skyscraper_Right',
        'sizes'         => '[[300, 600],[160, 600], [120, 600]]',
        'sizeMapping'   => array(
            '[1600,0]'     => '[[300, 600],[160, 600], [120, 600]]',
            '[0, 0]'        => '[]'
        )
    ),
//        'dfp_left_big' => array(
//            'type'          => 'global',
//            'name'          => 'Mkr_Desktop_{{channel}}_Big',
//            'sizes'         => '[[300, 600]]',
//            'sizeMapping'   => array(
//                '[768,0]'   => '[[300,600]]',
//                '[0, 0]'    => '[]'
//            )
//        ),
    'dfp_left_big' => array(
        'type'          => 'global',
        'name'          => 'Mkr_Desktop_{{channel}}_Big',
        'sizes'         => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
        'sizeMapping'   => array(
            '[768,0]'   => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
            '[0, 0]'    => '[]'
        )
    ),
//    'dfp_left_big_2' => array(
//        'type'          => 'global',
//        'name'          => 'Mkr_Desktop_{{channel}}_Big_2',
//        'sizes'         => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
//        'sizeMapping'   => array(
//            '[768,0]'   => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
//            '[0, 0]'    => '[]'
//        )
//    ),
//        'dfp_bottom' => array(
//            'type'          => 'global',
//            'name'          => 'Mkr_Desktop_{{channel}}_Bottom',
//            'sizes'         => '[[300, 600]]',
//            'sizeMapping'   => array(
//                '[1024,0]'  => '[[970, 90], [970, 250]]',
//                '[0, 0]'    => '[]'
//            )
//        )
//        'dfp_bottom' => array( - moved to home page
//        'type'          => 'global',
//        'name'          => 'Mkr_Desktop_{{channel}}_Bottom',
//        'sizes'         => '[[970, 250], [970, 350], [728, 180], [728, 90], [728, 250], [970, 180], [970, 90]]',
//        'sizeMapping'   => array(
//            '[1024,0]'  => '[[970, 250], [970, 350], [728, 180], [728, 90], [728, 250], [970, 180], [970, 90]]',
//            '[0, 0]'    => '[]'
//        )
//    )
);

if(is_front_page()){
    $channel = 'HP';
//    $slots['dfp_mobile_premium'] = array(
//        'type'          => 'global',
//        'name'          => 'Mkr_MobileWeb_HP_Premium',
//        'sizes'         => '[[1, 1], [300, 200], [320, 100], [300, 250], [360, 360]]',
//        'sizeMapping'   => array(
//            '[0,0]'     => '[[1, 1], [300, 200], [320, 100], [300, 250], [360, 360]]',
//            '[768, 0]'        => '[]'
//        )
//    );
//        $slots['dfp_liner'] = array(
//            'name'          => 'Mkr_Desktop_HP_Liner',
//            'sizes'         => '[[728, 90]]',
//            'sizeMapping'   => array(
//                '[768,0]'   => '[[728,90]]',
//                '[0, 0]'    => '[]'
//            )
//        );
    $slots['dfp_liner'] = array(
        'name'          => 'Mkr_Desktop_HP_Liner',
        'sizes'         => '[[728, 250], [728, 180], [728, 90]]',
        'sizeMapping'   => array(
            '[728,0]'   => '[[728, 180], [728, 90], [728, 250]]',
            '[0, 0]'    => '[]'
        )
    );
//        $slots['dfp_liner2'] = array(
//            'name'          => 'Mkr_Desktop_HP_Liner2',
//            'sizes'         => '[[728, 90]]',
//            'sizeMapping'   => array(
//                '[768,0]'   => '[[728,90]]',
//                '[0, 0]'    => '[]'
//            )
//        );
    $slots['dfp_liner2'] = array(
        'name'          => 'Mkr_Desktop_HP_Liner2',
        'sizes'         => '[[728, 180], [728, 90], [728, 250]]',
        'sizeMapping'   => array(
            '[728,0]'   => '[[728, 180], [728, 90], [728, 250]]',
            '[0, 0]'    => '[]'
        )
    );
//        $slots['dfp_linerTop'] = array(
//            'name'          => 'Mkr_Desktop_HP_Liner_Top',
//            'sizes'         => '[[728, 90]]',
//            'sizeMapping'   => array(
//                '[768,0]'   => '[[728,90]]',
//                '[0, 0]'    => '[]'
//            )
//        );
    $slots['dfp_linerTop'] = array(
        'name'          => 'Mkr_Desktop_HP_Liner_Top',
        'sizes'         => '[[728, 180], [728, 250], [728, 90]]',
        'sizeMapping'   => array(
            '[728,0]'   => '[[728, 180], [728, 250], [728, 90]]',
            '[0, 0]'    => '[]'
        )
    );
//   $slots['dfp_mobile_innerbox'] = array(
//        'name'          => 'Mkr_MobileWeb_HP_InnerBox',
//        'sizes'         => '[[1, 1],[300,250]]',
//        'sizeMapping'   => array(
//            '[0,0]'     => '[[1, 1],[300,250]]',
//            '[768, 0]'  => '[]'
//        )
//    );
    $slots['dfp_mobile_innerbox'] = array(
        'name'          => 'Mkr_MobileWeb_HP_InnerBox',
        'sizes'         => '[[320, 50], [300, 250], [320, 100]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[320, 50], [300, 250], [320, 100]]',
            '[768, 0]'  => '[]'
        )
    );
//    $slots['dfp_mobile_innerbox2'] = array(
//        'name'          => 'Mkr_MobileWeb_HP_InnerBox2',
//        'sizes'         => '[[1, 1],[300,250]]',
//        'sizeMapping'   => array(
//            '[0,0]'     => '[[1, 1],[300,250]]',
//            '[768, 0]'  => '[]'
//        )
//    );
    $slots['dfp_mobile_innerbox2'] = array(
        'name'          => 'Mkr_MobileWeb_HP_InnerBox2',
        'sizes'         => '[[320, 100], [300, 250], [320, 50], [1, 1]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[320, 100], [300, 250], [320, 50], [1, 1]]',
            '[768, 0]'  => '[]'
        )
    );
//    $slots['dfp_mobile_innerbox3'] = array(
//        'name'          => 'Mkr_MobileWeb_HP_InnerBox3',
//        'sizes'         => '[[1, 1],[300,250]]',
//        'sizeMapping'   => array(
//            '[0,0]'     => '[[1, 1],[300,250]]',
//            '[768, 0]'  => '[]'
//        )
//    );
    $slots['dfp_mobile_innerbox3'] = array(
        'name'          => 'Mkr_MobileWeb_HP_InnerBox3',
        'sizes'         => '[[320, 50], [300, 250], [1, 1], [320, 100]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[320, 50], [300, 250], [1, 1], [320, 100]]',
            '[768, 0]'  => '[]'
        )
    );
    $slots['dfp_mobile_innerbox4'] = array(
        'name'          => 'Mkr_MobileWeb_HP_InnerBox4',
        'sizes'         => '[[320, 50], [320, 100], [300, 250]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[320, 50], [320, 100], [300, 250]]',
            '[768, 0]'  => '[]'
        )
    );
    $slots['dfp_mobile_innerbox5'] = array(
        'name'          => 'Mkr_MobileWeb_HP_InnerBox5',
        'sizes'         => '[[320, 50], [320, 100], [300, 250]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[320, 50], [320, 100], [300, 250]]',
            '[768, 0]'  => '[]'
        )
    );
   $slots['dfp_mobile_HP_Strip'] = array(
       'name'          => 'Mkr_MobileWeb_HP_Strip',
       'sizes'         => '[[1, 1],[320,100],[320,50]]',
       'sizeMapping'   => array(
           '[0,0]'     => '[[1, 1],[320,100],[320,50]]',
           '[768, 0]'  => '[]'
       )
   );
//        $slots['dfp_desktop_Big_Bottom'] = array(
//            'name'          => 'Mkr_Desktop_HP_Big_Bottom',
//            'sizes'         => '[[1, 1],[300,600]]',
//            'sizeMapping'   => array(
//                '[0,0]'     => '[[1, 1],[300,600]]',
//                '[0, 0]'  => '[]'
//            )
//        );

    $slots['dfp_desktop_Big_Bottom'] = array(
        'name'          => 'Mkr_Desktop_HP_Big_Bottom',
        'sizes'         => '[[1, 1],[120, 600], [160, 600], [300, 250],[300,600]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[1, 1],[120, 600], [160, 600], [300, 250],[300,600]]',
            '[0, 0]'  => '[]'
        )
    );
    $slots ['dfp_bottom'] = array(
        'type'          => 'global',
        'name'          => 'Mkr_Desktop_{{channel}}_Bottom',
        'sizes'         => '[[970, 250], [970, 350], [728, 180], [728, 90], [728, 250], [970, 180], [970, 90]]',
        'sizeMapping'   => array(
            '[1024,0]'  => '[[970, 250], [970, 350], [728, 180], [728, 90], [728, 250], [970, 180], [970, 90]]',
            '[0, 0]'    => '[]'
        )
    );
//        $slots['dfp_desktp_hp_top'] = array(
//            'name'          => 'Mkr_Desktop_HP_300x250_TOP',
//            'sizes'         => '[[1, 1],[300,250]]',
//            'sizeMapping'   => array(
//                '[0,0]'     => '[[1, 1],[300,250]]',
//                '[0, 0]'  => '[]'
//            )
//        );
    $slots['dfp_desktp_hp_top'] = array(
        'name'          => 'Mkr_Desktop_HP_300x250_TOP',
        'sizes'         => '[[1, 1],[300, 600], [120, 600],[300,250],[160, 600]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[1, 1],[300, 600], [120, 600],[300,250],[160, 600]]',
            '[0, 0]'  => '[]'
        )
    );
    $slots['dfp_mobile_hp_sticky'] = array(
        'name'          => 'Mkr_MobileWeb_HP_StickyBottom',
        'sizes'         => '[[1,1],[320,50]]',
        'sizeMapping'   => array(
            '[0,0]'     => '[[1,1],[320,50]]',
            '[768, 0]'        => '[[1,1],[320,50]]'
        )
    );
} else {
    $channel = 'IP';

    if(is_singular( array('post','opinion'))){
//        $slots['dfp_mobile_premium'] = array(
//            'type'          => 'global',
//            'name'          => 'Mkr_MobileWeb_IP_Premium',
//            'sizes'         => '[[336, 280], [300, 250], [320, 100], [360, 360], [1, 1]]',
//            'sizeMapping'   => array(
//                '[0,0]'     => '[[336, 280], [300, 250], [320, 100], [360, 360], [1, 1]]',
//                '[768, 0]'        => '[]'
//            )
//        );

        $slots['dfp_article_innerbox'] = array(
            'name'          => 'Mkr_Desktop_IP_InnerBox',
            'sizes'         => '[[1, 1],[300,250]]',
            'sizeMapping'   => array(
                '[768,0]'   => '[[1, 1],[300,250]]',
                '[0, 0]'    => '[]'
            )
        );

        $slots['dfp_article_innerbox2'] = array(
            'name'          => 'Mkr_Desktop_IP_InnerBox2',
            'sizes'         => '[[1, 1],[300,250]]',
            'sizeMapping'   => array(
                '[0,0]'   => '[[1, 1],[300,250]]',
                '[768, 0]'    => '[]'
            )
        );

        $slots['dfp_article_innerbox3'] = array(
            'name'          => 'Mkr_Desktop_IP_InnerBox3',
            'sizes'         => '[[1, 1],[300,250]]',
            'sizeMapping'   => array(
                '[0,0]'   => '[[1, 1],[300,250]]',
                '[768, 0]'    => '[]'
            )
        );

        $slots['dfp_mobile_article_innerbox'] = array(
            'name'          => 'Mkr_MobileWeb_IP_InnerBox',
            'sizes'         => '[[1,1],[300,250]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[300,250]]',
                '[768, 0]'        => '[]'
            )
        );
        $slots['dfp_mobile_article_innerbox2'] = array(
            'name'          => 'Mkr_MobileWeb_IP_InnerBox2',
            'sizes'         => '[[1,1],[300,250]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[300,250]]',
                '[768, 0]'        => '[]'
            )
        );
        $slots['dfp_mobile_article_innerbox3'] = array(
            'name'          => 'Mkr_MobileWeb_IP_InnerBox3',
            'sizes'         => '[[1,1],[300,250]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[300,250]]',
                '[768, 0]'        => '[]'
            )
        );
//New
        $slots['dfp_mobile_article_strip_2'] = array(
            'name'          => 'Mkr_MobileWeb_IP_article_strip_2',
            'sizes'         => '[[320, 100], [320, 50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[320, 100], [320, 50]]',
                '[768, 0]'        => '[]'
            )
        );
        $slots['dfp_mobile_article_strip_1'] = array(
            'name'          => 'Mkr_MobileWeb_IP_article_strip_1',
            'sizes'         => '[[320, 100], [320, 50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[320, 100], [320, 50]]',
                '[768, 0]'        => '[]'
            )
        );
        $slots['dfp_mobile_ip_sticky'] = array(
            'name'          => 'Mkr_MobileWeb_IP_StickyBottom',
            'sizes'         => '[[1,1],[320,50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[320,50]]',
                '[768, 0]'        => '[[1,1],[320,50]]'
            )
        );
        $slots['dfp_article_strip1'] = array(
            'name'          => 'Mkr_Desktop_IP_article_strip_1',
            'sizes'         => '[[1,1],[728,90]]',
            'sizeMapping'   => array(
                '[768,0]'     => '[[1,1],[728,90]]',
                '[0, 0]'        => '[]'
            )
        );
        $slots['dfp_article_strip2'] = array(
            'name'          => 'Mkr_Desktop_IP_article_strip_2',
            'sizes'         => '[[1,1],[728,90]]',
            'sizeMapping'   => array(
                '[768,0]'     => '[[1,1],[728,90]]',
                '[0, 0]'        => '[]'
            )
        );

        $slots['dfp_bottom'] = array(
            'name'          => 'Mkr_Desktop_IP_Bottom',
            'sizes'         => '[[1, 1],[970,350], [970, 90], [970, 250]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1, 1],[970,350], [970, 90], [970, 250]]',
                '[0, 0]'  => '[]'
            )
        );

        $slots['dfp_left_big_2'] = array(
        'type'          => 'global',
        'name'          => 'Mkr_Desktop_IP_Big_2',
        'sizes'         => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
        'sizeMapping'   => array(
            '[768,0]'   => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
            '[0, 0]'    => '[]'
        )
    );
    }
    else if ( is_category() ) {

        $channel = 'IP';
//        $slots['dfp_mobile_premium'] = array(
//            'type'          => 'global',
//            'name'          => 'Mkr_MobileWeb_IP_Premium',
//            'sizes'         => '[[336, 280], [300, 250], [320, 100], [360, 360], [1, 1]]',
//            'sizeMapping'   => array(
//                '[0,0]'     => '[[336, 280], [300, 250], [320, 100], [360, 360], [1, 1]]',
//                '[768, 0]'        => '[]'
//            )
//        );
        $slots['dfp_category_strip_1'] = array(
            'name'          => 'Mkr_Desktop_IP_strip_1',
            'sizes'         => '[[728, 180], [728, 90], [728, 250]]',
            'sizeMapping'   => array(
                '[768,0]'   => '[[728, 180], [728, 90], [728, 250]]',
                '[0, 0]'    => '[]'
            )
        );
        $slots['dfp_category_strip_2'] = array(
            'name'          => 'Mkr_Desktop_IP_strip_2',
            'sizes'         => '[[728, 180], [728, 90], [728, 250]]',
            'sizeMapping'   => array(
                '[768,0]'   => '[[728, 180], [728, 90], [728, 250]]',
                '[0, 0]'    => '[]'
            )
        );
        $slots['dfp_category_strip_3'] = array(
            'name'          => 'Mkr_Desktop_IP_strip_3',
            'sizes'         => '[[728, 180], [728, 90], [728, 250]]',
            'sizeMapping'   => array(
                '[768,0]'   => '[[728, 180], [728, 90], [728, 250]]',
                '[0, 0]'    => '[]'
            )
        );
        $slots['dfp_mobile_ip_sticky'] = array(
            'name'          => 'Mkr_MobileWeb_IP_StickyBottom',
            'sizes'         => '[[1,1],[320,50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[320,50]]',
                '[768, 0]'        => '[[1,1],[320,50]]'
            )
        );
        $slots['dfp_mobile_mobile_inline_1'] = array(
            'name'          => 'Mkr_MobileWeb_IP_inline_1',
            'sizes'         => '[[1,1],[320, 100], [300, 250], [320, 50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[320, 100], [300, 250], [320, 50]]',
                '[768, 0]'        => '[]'
            )
        );
        $slots['dfp_mobile_mobile_inline_2'] = array(
            'name'          => 'Mkr_MobileWeb_IP_inline_2',
            'sizes'         => '[[1,1],[320, 100], [300, 250], [320, 50]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1,1],[320, 100], [300, 250], [320, 50]]',
                '[768, 0]'        => '[]'
            )
        );

        $slots['dfp_bottom'] = array(
            'name'          => 'Mkr_Desktop_IP_Bottom',
            'sizes'         => '[[1, 1],[970,350], [970, 90], [970, 250]]',
            'sizeMapping'   => array(
                '[0,0]'     => '[[1, 1],[970,350], [970, 90], [970, 250]]',
                '[0, 0]'  => '[]'
            )
        );
        $slots['dfp_left_big_2'] = array(
            'type'          => 'global',
            'name'          => 'Mkr_Desktop_IP_Big_2',
            'sizes'         => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
            'sizeMapping'   => array(
                '[768,0]'   => '[[120, 600], [300, 600], [160, 600], [300, 250]]',
                '[0, 0]'    => '[]'
            )
        );
    }

    //remove old slot dont relevant
//    $slots['dfp_mobile_bottom'] = array(
//        'name'          => 'Mkr_MobileWeb_IP_Bottom',
//        'sizes'         => '[[1,1],[300, 250],[300,600]]',
//        'sizeMapping'   => array(
//            '[0,0]'     => '[[1,1] ,[300, 250],[300,600]]',
//            '[768, 0]'  => '[]'
//        )
//    );
}
$makorTS = filemtime(get_template_directory() . '/assets/css/makor.css');
?>
<link rel='stylesheet' id='makor-custom-css'  href='<?php echo  get_template_directory_uri(); ?>/assets/css/makor.css?ver=<?php echo $makorTS; ?>' type='text/css' media='all' />
<script async src="//www.googletagservices.com/tag/js/gpt.js"></script>
<script>googletag = window.googletag || {cmd: []}; googletag.cmd = googletag.cmd || []</script>
<?php if (!is_single ('312313') && !is_page ('401597') && !is_page ('416647')) { ?>
<script type="text/javascript">

    (function(){

        dfp = window.dfp || { adslot : {} };
        googletag.cmd.push(function() {

            //for resize event
            var rtime, timeout = false, delta = 300, currentWindowSize = [0,0];

            function waitjQuery(callback, interval , max){
                if(typeof window.jQuery == "undefined" && max > 0){
                    setTimeout(function(){
                        waitjQuery(callback,interval, max--);
                    }, interval );
                } else {
                    callback();
                }
            }

            //list of all adslots
            dfp.adslot = {
                <?php
                $slot_output = '';
                foreach($slots as $name => $slot):
                    $slot_name = isset($slot['type']) && $slot['type'] == 'global' ? str_replace('{{channel}}', $channel , $slot['name']) : $slot['name'];
                    $slot_output .=  "'{$name}' : googletag.defineSlot('/135823970/{$slot_name}', {$slot['sizes']},'{$name}')";
                    if(isset($slot['sizeMapping'])){
                        $slot_output .= ".defineSizeMapping(googletag.sizeMapping()";
                        foreach ($slot['sizeMapping'] as $slotsize => $slotsizes){
                            $slot_output .= ".addSize({$slotsize},{$slotsizes})";
                        }
                        $slot_output .= ".build()).addService(googletag.pubads()),".PHP_EOL;
                    }
                endforeach;
                $slot_output = rtrim($slot_output,",");


                echo $slot_output;

                ?>


            };


            //ad slots callback object
            dfp.callbacks = {
                //load ozen if premium is in size range or is Empty
                "premium" : function(event) {
                    // dfp.handlers.loadOzen();

                }
            };

            //global close premium functions
            window.closeAdUnitPlazma = function(){

            };

            window.closePremiumTapet = function(){

            };


            dfp.handlers = {
                'loadOzen' : function(){


                },
                'close' : function(id){

                },
                'repositionOzen': function(){
                    var refElement = jQuery('#dfp_ozen');
                    var isPost = jQuery('.single-post, .single-opinion').length > 0 ? 1 : 0;

                    var boundsElementTop =   isPost ? jQuery('.jnews_article_top_ads') : jQuery('.jeg_main');
                    var boundsElementBottom = jQuery('.jeg_footer');
                    var windowScrollTop = jQuery(window).scrollTop();
                    var fixedHeight = 600;
                 //  var offsetTop = $('.jeg_stickybar').innerHeight(); Nina change $ -> JQuery
                    var offsetTop = jQuery('.jeg_stickybar').innerHeight();

                    var bounds = {
                        top      : boundsElementTop.offset().top ,
                        bottom   : boundsElementBottom.offset().top
                    };

                    var currentPos = bounds.top - windowScrollTop;
                    currentPos = currentPos < offsetTop ? offsetTop  : currentPos;

                    if(windowScrollTop + fixedHeight  > bounds.bottom){

                        currentPos = currentPos - ((windowScrollTop + fixedHeight + offsetTop) - bounds.bottom);
                        //currentPos(currentPos);
                    }

                     refElement.css("top", currentPos );
                },

                'resize' : function(event){}
            };

            //init ozen on start
            waitjQuery(function(){

                //if
                jQuery(document).ready(function(){

                    dfp.handlers.repositionOzen();

                });

                jQuery(window).resize(function(){

                });

                jQuery(window).scroll(function(){
                    dfp.handlers.repositionOzen();

                });

                jQuery(document).on('jnews-autoload-change-id', function(e, id){
                    var slots = ['dfp_mobile_article_innerbox','dfp_mobile_article_innerbox2','dfp_mobile_article_innerbox3','dfp_article_innerbox','dfp_article_innerbox2','dfp_article_innerbox3'];

                    if(dfp.adslot){
                        jQuery.each(slots, function(i,item){
                            if(dfp.adslot[item]){
                                console.log('slot is => ' + item);
                                var wrapper = jQuery('.post-autoload[data-id="'+ id +'"]');
                                console.log(wrapper.length);
                                var slotDiv = jQuery('#'+ item , wrapper);
                                console.log(slotDiv.length);
                                if(dfp.adslot[item].active) {
                                    console.log('slot is  active => ' + item);
                                    googletag.pubads().refresh(slotDiv[0]);
                                    console.log(slotDiv[0]);
                                } else {
                                    console.log('slot is not active => ' + item);
                                    googletag.display(slotDiv[0]);

                                }
                            }
                        });
                    }

                });


            },200,10);



            //on slot render Event
            //check whether a slot callback exists and execute him
            googletag.pubads().addEventListener('slotRenderEnded', function (event) {
                var id = event.slot.getSlotElementId();
                var size = event.size;


                if(!event.isEmpty && dfp.adslot[id]){
                    dfp.adslot[id]['active'] = true;
                }

                if(dfp.adslot[id] && !dfp.adslot[id].rendered){
                    dfp.adslot[id]['rendered'] = true;
                }

                dfp.adslot[id]['size'] = size;

                if(typeof dfp.callbacks[id] === "function"){
                    try{
                        dfp.callbacks[id](event);
                    }catch(e){}
                }
            });


            googletag.pubads().collapseEmptyDivs();
            googletag.enableServices();
        });
    })();
</script>
<?php
}}
add_action('wp_head', 'hook_custom_makor_head' );


/**
 * Fix the media link to file issue
 */


function _my_reset_image_insert_settings() {
    ?>
    <script>
        if ( typeof setUserSetting !== 'undefined' ) {
            setUserSetting( 'urlbutton', 'file' ); // none || file || post
        }
    </script>
    <?php
}

add_action( 'admin_head-post.php', '_my_reset_image_insert_settings' );
add_action( 'admin_head-post-new.php', '_my_reset_image_insert_settings' );
//Remove analytics code - moved to tag GTM-T6TR93C
/*if ( ! function_exists('jnews_add_google_analytics') )
{
    add_action( 'wp_footer', 'jnews_add_google_analytics' );

    function jnews_add_google_analytics()
    {
        $analytics_script = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-21986641-1', 'auto');
  ga('send', 'pageview');

</script>";

        echo $analytics_script;
    }
}*/

if ( ! function_exists('jnews_add_accessibility') )
{
    add_action( 'wp_head', 'jnews_add_accessibility' );

    function jnews_add_accessibility()
    {
        $accessibility_script = "<script data-cfasync='false'>
        window.interdeal = {
        sitekey   : 'c9e02f0a4765525fe7e0d86b9f07216d',
        Position  : 'Right',
        Menulang  : 'HE',
        btnStyle  : {
        scale	  : ['undefined','0.6'],
        }
        }
        </script>
        <script data-cfasync='false' src='https://js.nagich.co.il/accessibility.js'></script>";

        echo $accessibility_script;
    }
}

/**
 * Add the title attribute for all images, that is because they are missing for some reason
 * @param $html
 * @param $postId
 * @param $post_thumbnail_id
 * @return mixed
 */
function add_title_to_images($html, $postId, $post_thumbnail_id) {
    $caption = get_the_post_thumbnail_caption($postId);
    $newHtml = str_replace('/>', 'title="'. $caption .'" />', $html);
    return $newHtml;

}

add_filter('post_thumbnail_html', 'add_title_to_images', 10, 3);

function create_preloading_cache_file($url, $lang = '' ){

    $url = explode('*', $url);
   // error_log('url: ' .print_r($url,true));
    if(is_array($url)){
        $url = $url[0] == "" ? "/" : $url[0];
        $url=str_replace("https://www.makorrishon.co.il","http://127.0.0.1",$url);
        $headers = array(
            'Host: www.makorrishon.co.il',
            'X-Forwarded-Proto: https'
        );
        if(!empty($url)){

                $curl = curl_init();
                if ($curl) {
                    //error_log("url: -".$url."-\n");
                    curl_setopt($curl, CURLOPT_URL,  $url);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, 'mkr_preloader');
                    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 20000);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

                    $o = @curl_exec($curl);
                    $response = curl_getinfo($curl);
                   // error_log(' returnd value from exec value of o: ' . $o);

                    curl_close($curl);
                }

        }
    }
}
add_action('after_rocket_clean_home', 'create_preloading_cache_file' );
add_action('after_rocket_clean_file', 'create_preloading_cache_file' );

add_action( 'wp_enqueue_scripts', 'makor_custom_fp_js' );
function makor_custom_fp_js(){
    global $post;
    $frontPage = get_theme_mod('refresh_homepage');
    $articles = get_theme_mod('refresh_articals');

    wp_enqueue_script( 'makor-refresh-front-page', get_template_directory_uri().'/assets/js/makorHPR.js', 'jQuery', filemtime( get_template_directory() .'/assets/js/makorHPR.js' ), false );
    wp_localize_script( 'makor-refresh-front-page', 'makorAutoReload', Array('front-page'=>$frontPage, 'articles' => $articles, 'type'=>get_post_type(), 'isFront'=> is_front_page(), 'postID' => $post->ID) );
   // wp_enqueue_script(  'recaptchV3' , "https://www.google.com/recaptcha/api.js?render=6LdmnRcaAAAAAGGp1iA5fh-VrYIGDGigCkzfsaNt",false );
    if ( is_search() && $_REQUEST['type'] === 'google') {
        wp_enqueue_script( 'makor-gce', get_template_directory_uri().'/assets/js/gce.js', 'jQuery', filemtime( get_template_directory() .'/assets/js/gce.js' ), true );
    }
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function makor_customizer_refresh( $wp_customize ) {
    $wp_customize->add_section(
        'refresh_settings',
        array(
            'title' => 'Refresh pages',
            'description' => 'This is a settings section.',
            'priority' => 35,
        )
    );

    $wp_customize->add_setting('refresh_homepage', array('default' => 10,));

    $wp_customize->add_control('refresh_homepage', array(
        'label' => 'Home Page',
        'section' => 'refresh_settings',
        'type' => 'number',
    ));

    $wp_customize->add_setting('refresh_articals', array('default' => 5));
    $wp_customize->add_control('refresh_articals', array(
        'label' => 'Articals',
        'section' => 'refresh_settings',
        'type' => 'number',
    ));
}
add_action( 'customize_register', 'makor_customizer_refresh' );

add_action('wp_footer', 'makor_footer_scripts_libs');
function makor_footer_scripts_libs(){
    if(is_admin()) return;

    echo <<<FB
<!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1442297055899848');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1442297055899848&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code -->
FB;
}
add_action( 'vc_before_init', 'makor_horizantal_free_month_register_vc_init' );

function makor_horizantal_free_month_register_vc_init(){
    /*
    Element Description: VC Info Box
    */

    // Element Class
    class makor_horizantal_free_month_register_vc extends WPBakeryShortCode {

        // Element Init
        function __construct() {
            add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
            add_shortcode( 'makor_horizantal_free_month_register', array( $this, 'vc_infobox_html' ) );
        }

        // Element Mapping
        public function vc_infobox_mapping() {

            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }

            // Map the block with vc_map()
            vc_map(

                array(
                    'name' => __('Free newspaper contact horizantal', 'text-domain'),
                    'base' => 'makor_horizantal_free_month_register',
                    'description' => __('Free newspaper contact horizantal', 'text-domain'),
                    'category' => __('Contact', 'text-domain'),
                    // 'icon' => get_template_directory_uri().'/assets/img/vc-icon.png',
                    'params' => array(

                        array(
                            'type' => 'textfield',
                            'holder' => 'h3',
                            'class' => 'title-class',
                            'heading' => __( 'Title', 'text-domain' ),
                            'param_name' => 'title',
                            'value' => __( 'Default value', 'text-domain' ),
                            'description' => __( 'Box Title', 'text-domain' ),
                            'admin_label' => false,
                            'weight' => 0,
                            'group' => 'Custom Group',
                        ),

                    )
                )
            );


        }


        // Element HTML
        public function vc_infobox_html( $atts ) {

            //.. the Code is in the next steps ..//
            extract(
                shortcode_atts(
                    array(
                        'title'   => '',
                        'text' => '',
                    ),
                    $atts
                )
            );

            // Fill $html var with data
            $html = sprintf('<div class="vc_wp_text wpb_content_element"><div class="widget widget_text"><div class="textwidget">%s</div></div></div>', do_shortcode('[contact-form-7 id="7767" title="'.$title.'" html_id="isrh_form_makor1_subscription" html_class="horizantal"]'));

            return $html;
        }

    } // End Element Class

    // Element Class Init
    new makor_horizantal_free_month_register_vc();
}
function makor_horizantal_free_month_register_widget() {
    register_widget( 'makor_horizantal_free_month' );
}
add_action( 'widgets_init', 'makor_horizantal_free_month_register_widget', 0 );
add_filter( 'jnews_load_post_subtitle', '__return_true');


class makor_horizantal_free_month extends WP_Widget {
    /**
     * To create the example widget all four methods will be
     * nested inside this single instance of the WP_Widget class.
     **/
    public function __construct() {
        $widget_options = array(
            'classname' => 'example_widget',
            'description' => 'This is an Example Widget',
        );
        parent::__construct( 'makor_horizantal_free_month', 'Horizantal free month contact form', $widget_options );
    }


    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        printf("%s%s%s", $args['before_widget'], do_shortcode('[contact-form-7 id="7767" title="'.$title.'" html_id="isrh_form_makor1_subscription" html_class="horizantal"]'),$args['after_title']);
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }
}

add_action( 'wp_head', 'jnews_add_google_tag_for_article',2 );

function jnews_add_google_tag_for_article()
{

    if (is_single ('249745')) {
        $google_tag_script = "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KM5SBGK');</script>";

        echo $google_tag_script;
    }
}



/* ADMIN דטמב נואאםמ || MORAN REQUEST */

function makor_expert_sync_admin($wp_admin_bar){
    $args = array(
        'id' => 'makor_expert_sync_admin',
        'title' => 'סינכרון מומחים',
        'href' => '#',
        'meta' => array(
            'class' => 'custom-button-class'
        )
    );
    $wp_admin_bar->add_node($args);
}
/*function makor_expert_sync_admin_js(){
    echo <<<BOF
	<script>
		(function($){
			var run = false;
			$('#wp-admin-bar-makor_expert_sync_admin').on('click', function(e){
				if(run) return alert('המתן לסיום הסנכרון הקודם, תודה!');
				run = true;
				var deffer = $.ajax({
					method: 'post',
					url: ajaxurl,
					data: {
						action: 'sync_experts'
					}
				});
				deffer.done(function(data, textStatus, jqXHR ){
					alert(data.message);
				});
				deffer.fail(function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseJSON.message);
				});
				deffer.always(function(){
					run = false;
				})
			})
		})(jQuery)
	</script>
BOF;
}

function makor_expert_sync_admin_xhr(){
    $file = ABSPATH.'wp-admin/expert-cron.php';

    if(!file_exists($file)){
        status_header(500);
        wp_send_json(array('err'=>true, 'message'=> $file.' dosn\'t exits!'));
        return wp_die();
    }
    $out = shell_exec('php '.$file);

    wp_send_json(array('err'=>false, 'message'=> 'בקשת סנכרון מומחים בוצעה', 'log'=>$out));
    wp_die();
}

add_action('admin_bar_menu', 'makor_expert_sync_admin', 50);
add_action('admin_footer', 'makor_expert_sync_admin_js');
add_action( 'wp_ajax_sync_experts', 'makor_expert_sync_admin_xhr' );*/

/* google search and site wide search */
function swap_search_parameter($query_string) {
    // pretty url destroy native search need to parse it ourselve
    $e = explode("?",  $_SERVER['REQUEST_URI']);
    if(!isset($e[1])) return $query_string;
    parse_str($e[1], $args);
    if(!isset($args['type']) || $args['type'] != 'site' && $args['type'] != 'google') return $query_string;

    $query_string_array = array();
    parse_str($e[1], $query_string_array);
    // $_REQUEST boolean controller in search.php
    $_REQUEST['type'] = $query_string_array['type'] = $query_string_array['type'];
    if(!empty($query_string_array['q'])) $query_string_array['s'] = $query_string_array['q'];
    if(!empty($_GET['q'])) $query_string_array['s'] = sanitize_text_field($_GET['q']);
    unset($query_string_array['q']);
    return http_build_query($query_string_array, '', '&');
}

// autoload articles don't like search hack
if(  empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) ||
    strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) != 'xmlhttprequest' ) {
    //This is an ajax request.
    add_filter('query_string', 'swap_search_parameter');
}

function new_search_parameter( $allowed_query_vars ) {
    if(is_search()) $allowed_query_vars[] = 'q';
    return $allowed_query_vars;
}
add_filter('query_vars', 'new_search_parameter' );

add_action( 'wp_head', 'addgooglmanager' );

function addgooglmanager()
{
    $google_tag_script = '';

    if (is_single ('260247') || is_single ('282703') || is_single ('286315') || is_single ('293795')) {
        $google_tag_script = "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-MRHSHLV');</script>";

        echo $google_tag_script;
    }

    if (is_page ('292221') || is_single ('293979') || is_single ('294681')) {
        $google_tag_script = "<!-- Global site tag (gtag.js) - Google Ads: 797275563 -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-797275563\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-797275563');
</script>
";
        echo $google_tag_script;
    }

}
add_action( 'wp_head', 'addgooglmanager_for_post' );

function addgooglmanager_for_post()
{
    if (is_single ('278809') || is_single ('278835')) {
        $google_tag = "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MRS2L74');</script>";

        echo $google_tag;
    }

}
add_action('add_meta_boxes', 'change_meta_box_titles', 999);
add_action( 'save_post', 'makor_writer_order_save', 10, 2 );

function makor_writer_order_save($postId, $post){
    // check privligies
    $post_type = get_post_type_object( $post->post_type );
    if ( !current_user_can( $post_type->cap->edit_post, $postId ) ) return $post_id;


    $meta_key = 'orderWriter';
    $meta_value = get_post_meta( $postId, $meta_key, true );

    // delete meta key or ignore
    if(empty($_POST['orderWriter'])){
        if(!$meta_value) return $postId;
        delete_post_meta( $postId, $meta_key, $meta_value );
        return $postId;
    }

    if(!$meta_value){
        add_post_meta( $postId, $meta_key, true, true );
    }else{
        update_post_meta( $postId, $meta_key, true );
    }

    return $postId;

}

function change_meta_box_titles() {
    global $wp_meta_boxes;
    if(isset($wp_meta_boxes['post']['side']['core']['tagsdiv-writer']['callback'])) $wp_meta_boxes['post']['side']['core']['tagsdiv-writer']['callback'] = 'adminWriterMakor';
}

function adminWriterMakor($post, $box){
    // use wordpress core taxonomy tag form
    // save it to a buffer so textarea could be replace to show proper order
    ob_start();
    post_tags_meta_box($post, $box);
    $form = ob_get_contents();
    ob_end_clean();

    $orderWriter = get_post_meta($post->ID, 'orderWriter', true);

    if(!empty($orderWriter)){
        $terms = wp_get_object_terms($post->ID, 'writer', array('orderby'=>'term_order'));

        if($terms !== NULL){
            foreach ($terms as &$term) {
                $term = $term->name;
                var_dump($term);
            }
        }else{
            $terms = array();
        }

        echo preg_replace("/<textarea(.*)>.*<\/textarea>/", "<textarea$1>". implode(",", $terms) ."</textarea>", $form);
    }else{
        echo $form;
    }


    // $xpath = new DOMXPath($DOM);



    echo  '<div class="misc-pub-section misc-pub-section-last">'
        .'<label><input type="checkbox"' . (!empty($orderWriter) ? ' checked="checked" ' : null) . ' value="1" name="orderWriter" /> הצג כתבים לפי סדר הזנה</label>'
        .'</div>';
}

add_action('wp_head', 'makor_firstimpression');
function makor_firstimpression()
{
    if (!is_single('312313')) {
        if (is_admin()) return;
        echo <<<EOL
<!--BEGIN FIRSTIMPRESSION TAG - makorrishon.co.il -->
<script data-cfasync='false' type='text/javascript'>
    ;(function(o) {
        var w=window.top,a='apdAdmin',ft=w.document.getElementsByTagName('head')[0],
        l=w.location.href,d=w.document;w.apd_options=o;
        if(l.indexOf('disable_fi')!=-1) { console.error("disable_fi has been detected in URL. FI functionality is disabled for this page view."); return; }
        var fiab=d.createElement('script'); fiab.type = 'text/javascript';
        fiab.src=o.scheme+'ecdn.analysis.fi/static/js/fab.js';fiab.id='fi-'+o.websiteId;
        ft.appendChild(fiab, ft); if(l.indexOf(a)!=-1) w.localStorage[a]=1; 
        var aM = w.localStorage[a]==1, fi=d.createElement('script'); 
        fi.type='text/javascript'; fi.async=true; if(aM) fi['data-cfasync']='false';
        fi.src=o.scheme+(aM?'cdn':'ecdn') + '.firstimpression.io/' + (aM ? 'fi.js?id='+o.websiteId : 'fi_client.js');
        ft.appendChild(fi);
    })({ 
        'websiteId': 7121, 
        'scheme':    '//'
    });
</script>
<!-- END FIRSTIMPRESSION TAG -->
EOL;
    }
}
add_action('wp_head', 'makor_tagManger', 1);

function makor_tagManger(){
//    echo('<div class="INFINITE" id="infiniteContainer"></div>
//    <script>
//        (function () {
//	    var node = document.getElementsByTagName("script")[0],
//		rvbP = document.createElement("script");
//	    window.CMT = { appId: "38aece9c-6178-4442-b681-efd8797f87d9" };
//	    rvbP.defer = true;
//	    rvbP.type = "text/javascript";
//	    rvbP.src = (document.location.protocol == "https:" ? "https:" : "http:") +
//			"//revboostprocdnadsprod.azureedge.net/scripts/latest/min.js";
//	    node.parentNode.insertBefore(rvbP, node);
//        })();
//    </script>');
    if (is_single ()) {
        global $post;
        $writerName1 = "";
        $writer = str_replace('"', '\"', wp_get_object_terms($post->ID, 'writer'));
        $writerfield = $writer[0];
        if ($writerfield)
            $writerName1 = str_replace('"', '\"', $writerfield->name);
        $writerfield2 = $writer[1];
        $writerName2 = str_replace('"', '\"', $writerfield2->name);
        $time = get_the_date(get_option('time_format'), $post);
        $date = substr($post->post_date,0,10);
        if (isset($writerfield2->name)) {
            echo <<<EOL2
        <!-- Google Tag Manager -->
<script>
window.dataLayer = window.dataLayer || [];
dataLayer.push({
'event': 'page attributes',
'writer1': "$writerName1",
'writer2': "$writerName2",
'publish date': "$date",
'publish time': "$time"
});
</script>
EOL2;
        }
        else {
            if (isset($writerfield->name)) {
                echo <<<EOL
        <!-- Google Tag Manager -->
<script>
window.dataLayer = window.dataLayer || [];
 dataLayer.push({
'event': 'page attributes',
'writer1': "$writerName1",
'publish date': "$date",
'publish time': "$time"
});
</script>
EOL;
            }}
    }
    echo <<<EOL1
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T6TR93C');</script>
            
        <!-- End Google Tag Manager -->
EOL1;
}


include(get_parent_theme_file_path( 'class/telegram.php' ));

add_action( 'save_post', 'makor_telegram_push' );

function makor_telegram_push($postId){
    if(get_post_status($postId) != 'publish') return false;

    $custom_fields = get_post_custom($postId);
    if(isset($custom_fields['telegramPost']) && isset($custom_fields['telegramPost'][0]) && $custom_fields['telegramPost'][0] === "1" || !has_tag('טלגרם', $postId) &&  get_post_type($postId) !== 'opinion') return false;

    $t = new \makor\telegram();
    $url = get_permalink($postId);
    // dev testing
    // $url = str_replace('http://dev.', 'https://www.', $url);
    $t->setToken()->message($url);
    $tags = wp_get_post_tags( $postId );
    add_post_meta($postId, 'telegramPost', true, true);
    return true;
}

add_filter( 'get_the_terms', 'makor_exclude_telegram_tag', 10, 1 );

// Make sure to disexclude telegram tag from frontend
function makor_exclude_telegram_tag($terms){
    $blacklist_terms = array('טלגרם');
    foreach( $terms as $k => $term ) {
        if(in_array( $term->name, $blacklist_terms, true ))
            unset( $terms[$k] );
    }
    return $terms;
}

if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

// MAK-168 - add search element to mobile nav
function add_last_nav_item($items, $args) {
    if ($args->menu->term_id == '31') {
        return $items .= '<li><span zoomdSearch=\'{"trigger":"OnClick"}\' class="magnifier-mobile"></span></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items','add_last_nav_item', 10, 2);



add_action("wpcf7_before_send_mail", "wpcf7_do_something_else");
function wpcf7_do_something_else( $cf7 ) {
    $submission = WPCF7_Submission::get_instance();

    if ( $submission ) {
        $posted_data = $submission->get_posted_data();
        //$recaptchaVerify = verifyRecaptcha($action = 'contact_form');

       // if ($recaptchaVerify)  {
        //Nina
        if ($cf7->id == 338883) {
            require __DIR__ . "/FormAPIHandler.php";
            $send_data['last_name'] = $posted_data['field_2fa_last_name'];
            $send_data['first_name'] = $posted_data['field_2fa_first_name'];
            $send_data['email'] = $posted_data['field_2fa_email'];
            $send_data['phone'] = $posted_data['field_2fa_phone'];
            $send_data['organization'] = $posted_data['field_2fa_organization'];


            $api_form_name = 'isrh_form_amolam';
            $forms_api = new Forms_API_handler('web');

            $response = $forms_api->add_form_data($api_form_name, array(
                0 => array(
                    'field_2fa_first_name' => $send_data['first_name'],
                    'field_2fa_last_name' => $send_data['last_name'],
                    'field_2fa_email' => $send_data['email'],
                    'field_2fa_phone' => $send_data['phone'],
                    'field_2fa_organization' => $send_data['organization'],
                    'field_2fa_status' => 1,
                    'field_2fa_email_tpl' => 1,
                    'field_2fa_send_auth_email' => 0,
                )
            ));
        }
        ///Nina
            if ($cf7->id == 219609) {
                require __DIR__ . "/FormAPIHandler.php";
                $send_data['last_name'] = $posted_data['field_4af_e_last_name'];
                $send_data['first_name'] = $posted_data['field_4af_e_first_name'];
                $send_data['mobile'] = $posted_data['field_4af_e_mobile'];
                $send_data['address'] = $posted_data['field_4af_e_address'];
                $send_data['apartment'] = $posted_data['field_4af_e_apartment'];
                $send_data['locality'] = $posted_data['field_4af_e_locality'];
                $send_data['country'] = $posted_data['field_4af_e_country'];
                $send_data['email'] = $posted_data['field_4af_e_email'];
                $send_data['house_num'] = $posted_data['field_4af_house_number'];
                $send_data['zip'] = $posted_data['field_4af_zip'];
                $send_data['text'] = $posted_data['field_4af_text'];



                $api_form_name = 'isrh_form_mkr_chamets_passover';
                $forms_api = new Forms_API_handler('web');

                $response = $forms_api->add_form_data($api_form_name, array(
                    0 => array(
                        'field_d3f_first_name' => $send_data['first_name'],
                        'field_d3f_last_name' => $send_data['last_name'],
                        'field_d3f_email' => $send_data['email'],
                        'field_d3f_phone' => $send_data['mobile'],
                        'field_d3f_address' => $send_data['address'],
                        'field_d3f_apartment' => $send_data['apartment'],
                        'field_d3f_locality' => $send_data['locality'],
                        'field_d3f_country' => $send_data['country'],
                        'field_d3f_house_number' => $send_data['house_num'],
                        'field_d3f_zip' => $send_data['zip'],
                        'field_d3f_text' => $send_data['text'],
                        'field_d3f_status' => 1,
                        'field_d3f_email_tpl' => 1,
                        'field_d3f_send_auth_email' => 0,
                    )
                ));
            }

            if ($cf7->id == 215513) {
                require __DIR__ . "/FormAPIHandler.php";

                $send_data['last_name'] = $posted_data['field_c1b_last_name'];
                $send_data['first_name'] = $posted_data['field_c1b_first_name'];
                $send_data['email'] = $posted_data['field_c1b_email'];
                $send_data['fullname'] = $posted_data['field_c1b_last_name'];
                $send_data['website'] = 'סליחות';


                $api_form_name = 'isrh_form_makorpopup';
                $forms_api = new Forms_API_handler('web');

                $response = $forms_api->add_form_data($api_form_name, array(
                    0 => array(
                        'field_c1b_first_name' => $send_data['first_name'],
                        'field_c1b_last_name' => $send_data['last_name'],
                        'field_c1b_email' => $send_data['email'],
                        'field_c1b_website' => $send_data['website'],
                        'field_c1b_fullname' => $send_data['fullname'],
                        'field_c1b_status' => 1,
                        'field_c1b_email_tpl' => 1,
                        'field_c1b_send_auth_email' => 0,
                    )
                ));
            }

            if ($cf7->id == 263007) {
                require __DIR__ . "/FormAPIHandler.php";
                $send_data['last_name'] = $posted_data['field_c1b_last_name'];
                $send_data['first_name'] = $posted_data['field_c1b_first_name'];
                $send_data['email'] = $posted_data['field_c1b_email'];
                $send_data['fullname'] = $posted_data['field_c1b_last_name'];
                $send_data['website'] = 'סליחות';


                $api_form_name = 'isrh_form_makorpopup';
                $forms_api = new Forms_API_handler('web');

                $response = $forms_api->add_form_data($api_form_name, array(
                    0 => array(
                        'field_c1b_first_name' => $send_data['first_name'],
                        'field_c1b_last_name' => $send_data['last_name'],
                        'field_c1b_email' => $send_data['email'],
                        'field_c1b_website' => $send_data['website'],
                        'field_c1b_fullname' => $send_data['fullname'],
                        'field_c1b_status' => 1,
                        'field_c1b_email_tpl' => 1,
                        'field_c1b_send_auth_email' => 0,
                    )
                ));
            }
            if ($cf7->id == 292689) {
                require __DIR__ . "/FormAPIHandler.php";

                $send_data['last_name'] = $posted_data['field_c0b_last_name'];
                $send_data['first_name'] = $posted_data['field_c0b_first_name'];
                $send_data['email'] = $posted_data['field_c0b_email'];


                $api_form_name = 'isrh_form_makor1_bneiakiva1';
                $forms_api = new Forms_API_handler('web');

                $response = $forms_api->add_form_data($api_form_name, array(
                    0 => array(
                        'field_c0b_first_name' => $send_data['first_name'],
                        'field_c0b_last_name' => $send_data['last_name'],
                        'field_c0b_email' => $send_data['email'],
                        'field_c0b_status' => 1,
                        'field_c0b_email_tpl' => 1,
                        'field_c0b_send_auth_email' => 0,
                    )
                ));
            }

        }
  //  }

}

//Add video icon to posts that have a video attachement MAK-181
add_action( 'save_post', 'add_video_icon', 10, 2 );

function add_video_icon($postId, $post){
    $content = get_post_field('post_content', $postId);
   // file_put_contents('./log_video_.log', $content.PHP_EOL, FILE_APPEND);
    preg_match  ('/(?:(?:www\.youtube))|(?:(?:video))|(?:(?:webplayer))|(?:(?:cdnwiz))/', $content, $matches);
    if ($matches) {
        update_field('has_video', 1);
        preg_match  ('/type="24981"/', $content, $matches1);

        if ($matches1){
            update_field('has_video', 0);
            update_field('has_audio', 1);
        }
    }
    else {
        update_field('has_video', 0);
        preg_match  ('/omny.fm/', $content, $matches2);
          if ($matches2) {
              update_field('has_audio', 1);
          }
          else {
              update_field('has_audio', 0);
          }
    }


}

add_action( 'save_post', 'zephr_cache' , 100, 2 );

function zephr_cache($postId, $post ){
    $post1 = get_post( $postId );
    file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'post_status'.$post1->post_status.PHP_EOL, FILE_APPEND);
    if (  $post1->post_status != 'publish' ) {
        return true;
    }
    $url_full = get_permalink($postId);
    $url = wp_make_link_relative($url_full);
    file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'urllll'.$url.PHP_EOL, FILE_APPEND);
    file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'url_full'.$url_full.PHP_EOL, FILE_APPEND);
    require_once __DIR__ . "/ZephrClass.php";
    $zephr_api = new ZephrClass($url);

    $response = $zephr_api->clear_cache($url);
  //  file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'update'.$update.PHP_EOL, FILE_APPEND);
}
//Add Tags to opinions
function reg_tag() {
    register_taxonomy_for_object_type('post_tag', 'opinion');
}
add_action('init', 'reg_tag');
add_filter( 'pre_get_posts', 'wpshout_add_custom_post_types_to_query' );
function wpshout_add_custom_post_types_to_query( $query ) {
    if(
        is_archive() &&
        $query->is_main_query() &&
        empty( $query->query_vars['suppress_filters'] )
    ) {
        $query->set( 'post_type', array(
            'post',
            'opinion'
        ) );
    }
}
add_action('amp_post_template_css','ampforwp_add_custom_css_example', 11);
function ampforwp_add_custom_css_example() {
    $mobile_logo 						=  get_template_directory_uri() . '/assets/img/logo_mobile.png';
    $mobile_logo_retina 				=  get_template_directory_uri() . '/assets/img/logo_mobile@2x.png';
    ?>
    /** Author Page **/
    .jeg_authorpage .jeg_author_wrap { position: relative; padding: 30px 20px; background: #f5f5f5; border: 1px solid #eaeaea; border-radius: 3px; }
    .jeg_authorpage .jeg_author_image { width: auto; }
    .jeg_authorpage .jeg_author_image img { width: 110px; height: 110px; }
    .jeg_authorpage .jeg_author_content { padding-left: 20px; margin-left: 30px; }
    .jeg_authorpage .jeg_author_name { font-weight: bold; font-size: 24px; margin: 0 0 5px; }
    .jeg_authorpage .jeg_author_content p { width: 75%; }
    .authorlink { position: relative; right: 0; bottom: 0; display: block; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    .authorlink li { display: inline-block; }
    .authorlink li a { display: block; color: #212121; text-align: center; line-height: 38px; padding: 0 12px; position: relative; }
    .authorlink li.active a:before { content: ''; display: block; width: 100%; height: 3px; background: #F70D28; position: absolute; left: 0; bottom: -1px; }
    .jeg_authorpage .jeg_author_wrap::after { content: ""; display: block; clear: both; }

    /** Author Box **/
    .jeg_authorbox { border: 1px solid #eee; padding: 30px 0; margin-bottom: 30px; }

    .jeg_author_image img { border-radius: 100%; width: 80px; height: 80px; }
    .jeg_author_content { padding-right: 20px; margin-left: 20px; color: #a0a0a0; }
    .jeg_author_content p { margin-bottom: 1em; }
    h3.jeg_author_name { margin: 0 0 10px; font-size: 18px; font-weight: bold; }
    .jeg_author_socials a { font-size: 16px; display: inline-block; margin: 10px 5px 20px 10px; color: #999; }
    .jeg_author_wrap.vcard {
    padding: 30px;
    border-bottom: 1px solid slategray;
    }
    .jeg_author_wrap.vcard:after {
    content: "";
    display: table;
    clear: both;
    }

    .spot-im-amp-overflow {
            background: white;
            font-size: 15px;
            padding: 15px 0;
            text-align: center;
            font-family: Helvetica, Arial, sans-serif;
            color: #307fe2;
    }

    .amp-wp-title { padding-top: 30px !important;}
    a.button {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;
    text-decoration: none;
    display: block;
    width: 200px;
    margin: 5px auto 0;
    height: 38px;
    font-weight: bold;
    color: #FFFFFF;
    background-color: #1D72BB;
    border: none;
    font-size: 18px;
    padding-top:5px;
    text-align:center;

    }
    }
    <?php
}
add_filter( 'upload_mimes', 'video_upload_mimes' );
function video_upload_mimes( $mimes ) {

    unset($mimes['mp4|m4v']);

    return $mimes;
}
/*Add Chartbeat to AMP pages*/
add_filter( 'amp_post_template_head', 'chartbeat_amp' );
function chartbeat_amp()
{
    global $post;
    $title = str_replace('"', '\"', get_the_title($post));
    $writer = str_replace('"', '\"', wp_get_object_terms($post->ID, 'writer'));
    $writerfield = $writer[0];
    $writerName1 = str_replace('"', '\"',$writerfield->name);
    $writerName = str_replace(' ', '-', $writerName1);
    $categories = wp_get_post_categories($post->ID,'fields=all');
    foreach ($categories as $categorie){
        $categoryElem = json_decode(json_encode($categorie), true);
        $sections[] = $categoryElem['name'];
    }

    echo('<amp-analytics type="chartbeat">
    <script type="application/json">
        {
            "vars": {
                "uid": "44983",
                "domain": "makorrishon.co.il",
                "authors": "'.$writerName.'",
                "title": "'.$title.'",
                "sections":  "'.$sections[0].'",
                "contentType": "article page"
            }
        }
    </script>
   </amp-analytics>');

}
//Add google analytics to AMP pages
add_action( 'amp_post_template_head', 'add_google_analytics' );
function add_google_analytics()
{
echo('<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-MJDFHFM&gtm.url=www.makorrishon.co.il" data-credentials="include"></amp-analytics>');

   /* echo('<amp-analytics type="googleanalytics">
<script type="application/json">
{
  "vars": {
    "account": "UA-21986641-1"
  },
  "triggers": {
    "trackPageview": {
      "on": "visible",
      "request": "pageview"
    }
  }
}
</script>
</amp-analytics>');*/
}

/**
 * Category ID Column on Category Page
 * https://wordpress.org/support/topic/show-category-id-on-category-page-manager-in-admin/
 */
add_filter( 'manage_edit-category_columns', 'category_column_header' );

function category_column_header($columns) {
    $columns['header_name'] = 'Category Id';
    return $columns;
}

add_filter( 'manage_category_custom_column', 'category_column_content', 10, 3 );

function category_column_content($content, $column_name, $term_id){
    if ($column_name=='header_name'){
       return $term_id;}
    else
       return $content;
}

function remove_item_from_menu() {

        remove_menu_page('edit.php?post_type=footer');
        remove_menu_page('edit.php?post_type=custom-post-template');
        remove_menu_page('edit.php?post_type=archive-template');
        remove_menu_page('edit.php?post_type=custom-mega-menu');

}
add_action( 'admin_menu', 'remove_item_from_menu' , 999);

add_action('wp_footer', 'makor_pixel_libs');
function makor_pixel_libs(){
    if(is_admin()) return;
    if (is_page ('292221') || is_single ('293979') || is_single ('294681')) {
        echo <<<FB
<!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '441005946381144');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=441005946381144&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code -->
FB;
    }
}
/*add_action( 'wp_head', 'addspot_im' );
function addspot_im(){
    echo <<<EOL
    <script
            async="async"
            src="https://launcher.spot.im/spot/sp_KuOZ3qn7"
            data-spotim-module="spotim-launcher">
    </script>
EOL;
}*/
function verifyRecaptcha($action = ''){
    $token = $_POST['_wpcf7_recaptcha_response'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => '6LdmnRcaAAAAAEQU8rAZMiY55dqpsK0K3a3aQY3q', 'response' => $token)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $arrResponse = json_decode($response, true);
    $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
        "arrResponse: ".($arrResponse).PHP_EOL.
        "response: ".$response.PHP_EOL.
        "token: ".$token.PHP_EOL.
        "ch: ".$ch.PHP_EOL.
        "arrResponse-success: ".$arrResponse["success"].PHP_EOL.
        "arrResponse-action: ".$arrResponse["action"].PHP_EOL.
        "arrResponse-score: ".$arrResponse["score"].PHP_EOL.
        "action: ".$action.PHP_EOL.
        "-------------------------".PHP_EOL;
    //-
    file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
    if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
        return true;
    } else {
        return false;
    }
}

add_action( 'wp_footer', 'check_current_url' );
function check_current_url()
{
    $prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    if ($prev_url == 'https://makorrishon.co.il/beit-midrash/')
    {
        ?>
        <script>
            (function ($) {
            $(document).ready(function () {

                var els = document.getElementsByClassName('jeg_logo_img');
                i = els.length;
                while (i--) {
                    els[i].className += ' beit_midrash_logo';
                }
            });
            })(jQuery);
        </script>

  <?php  }

}
add_action( 'wp_head', 'hot_jar' );
function hot_jar()
{
$hotjar = "<!-- Hotjar Tracking Code for https://www.makorrishon.co.il/ -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2271350,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>";
echo $hotjar;
}
add_action( 'cron_for_mumlazim', 'mumlazim' );
//add_action( 'wp_head', 'mumlazim' );
function mumlazim()
{
    require __DIR__ . "/mumlazimCron.php";
    $mumlazimCron = new mumlazimCron();
}

//add_action( 'jnews_before_main_outbrain', 'add_outbrain_area', 99);
//function add_outbrain_area()
//{
//    if(is_single()):
//    echo('<div class="OUTBRAIN" id="outbrainContainerAR3" data-widget-id="AR_3"></div>
//       <script>
//                     (function(){
//                                     var outbrainContainerAR3 = document.getElementById("outbrainContainerAR3");
//                                     outbrainContainerAR3.setAttribute("data-src", location.href );
//
//                                     var launcherScript = document.createElement("script");
//                                     launcherScript.setAttribute("async", "async");
//                                     launcherScript.setAttribute("src", "https://widgets.outbrain.com/outbrain.js");
//                                     outbrainContainerAR3.parentNode.insertBefore(launcherScript, outbrainContainerAR3.nextSibling);
//                     })();
//       </script>');
//    endif;
//}

function prefix_insert_after_paragraph( $insertion, $content ) {
    $closing_p = '</p>';
    $paragraphs = explode( $closing_p, $content );
    $paragraph_id = round(count($paragraphs) / 2);
    foreach ($paragraphs as $index => $paragraph) {
        if ( trim( $paragraph ) ) {
            $paragraphs[$index] .= $closing_p;
        }
        if ( $paragraph_id == $index + 1 ) {
            $paragraphs[$index] .= $insertion;
        }
}
    return implode( '', $paragraphs );
}
add_filter( 'the_content', 'add_outbrain_area_middle', 99);
function add_outbrain_area_middle( $content )
{
    global $post;
    $audio = get_field( "has_audio", $post->ID );
    if ($audio!=1) {
        if (is_single() && !amp_is_request()) {

            $ad_code = '<div class="OUTBRAIN" id="outbrainContainerAR2" data-widget-id="AR_2"></div>
       <script>
                     (function(){
                                     var outbrainContainerAR2 = document.getElementById("outbrainContainerAR2");
                                     outbrainContainerAR2.setAttribute("data-src", location.href );

                                     var launcherScript = document.createElement("script");
                                     launcherScript.setAttribute("async", "async");
                                     launcherScript.setAttribute("src", "https://widgets.outbrain.com/outbrain.js");
                                     outbrainContainerAR2.parentNode.insertBefore(launcherScript, outbrainContainerAR2.nextSibling);
                     })();
       </script>';
            return prefix_insert_after_paragraph($ad_code, $content);
        } else if (is_single() && amp_is_request()) {
            $ad_code = '<div class="OUTBRAIN" id="outbrainContainerAMP2" data-widget-id="AMP_2"></div>
        <amp-embed
            width="100"
            height="100"
            type="outbrain"
            layout="responsive"
            data-widgetIds="AMP_2">
          </amp-embed>';
            return prefix_insert_after_paragraph($ad_code, $content);
        }
    }
    return $content;
}
add_action( 'jnews_rishonot_banner', 'add_rishonot_banner', 99);
function add_rishonot_banner()
{
    $site_url = get_site_url(null,'wp-content/uploads/2021/10/rishonot-logo.jpg');
    if(is_single()):  ?>
        <div class="rishonot-banner">
            <img src="<?php echo esc_url( $site_url ); ?>" alt="" />
        </div>
    <?php
    endif;
}
/**
Add a banner to the begining of the post in category -  מיניות וזוגיות
 */
add_filter( 'the_content', 'add_banner_to_category', 199);
function add_banner_to_category( $content )
{
    global $post;

    $categories = wp_get_post_categories($post->ID, 'fields=ids');
    if (in_array(27219, $categories)) {
        if (is_single()) {
                $ad_code = '<a target = \'_blank\' href="https://kelimshloovim.co.il/%D7%9E%D7%A0%D7%97%D7%99%D7%9D-%D7%9C%D7%9E%D7%99%D7%A0%D7%99%D7%95%D7%AA-%D7%91%D7%A8%D7%99%D7%90%D7%94-%D7%91%D7%96%D7%95%D7%92%D7%99%D7%95%D7%AA/"><img class="size-full wp-image-420419 aligncenter" src="https://www.makorrishon.co.il/wp-content/uploads/2021/11/WhatsApp-Image-2021-11-11-at-14.39.11.jpeg" alt="" width="1024" height="126" /></a>';
                $ad_code .= $content;
                return $ad_code;
            }
        }
        return $content;
}
//add_action( 'save_post', 'makor_save_collaboration', 10, 2 );

function makor_save_collaboration($postId, $post){

    $categories = wp_get_post_categories($post->ID, 'fields=ids');
    if(in_array(815, $categories) || in_array(23261, $categories)) {
        $writer = str_replace('"', '\"', wp_get_object_terms($postId, 'writer'));
        if ($writer) {
            $writerfield = $writer[0];
            $writerName1 = str_replace('"', '', $writerfield->name);
            //$writerName = str_replace(' ', '-', $writerName1);
            $beshituf = 'בשיתוף ';
            $writerName = $beshituf." ".$writerName1;
            if(in_array(23261, $categories)){
                update_field('collaboration', $writerName, $postId);}
        }
    }

    return $postId;

}
//add_filter( 'wp_nav_menu_args', 'rishonot_mobile_menu',1);
//    function rishonot_mobile_menu( $args ){
//     //   if (is_page ('231472') && wp_is_mobile()){
//        if (is_page ('328483') && wp_is_mobile()){
//        $args['theme_location'] = 'mobile_rishonot';
//    }
//
//    return $args;
//}
//14072021-RISHONOT-OPINION

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
//remove post id folder in cache2 affter saving the post
//prevent cache view to user
add_action( 'save_post', 'remove_cache2' , 1, 2 );

function remove_cache2($postId, $post ){
    $category_detail="";
    $cat_name="";
    $category_detail = get_the_category($postId);
    foreach($category_detail as $cd){
        $cat_name = $cd->slug;
    }
    $file_path=WP_CONTENT_DIR. '/cache2/wp-rocket/stage.makorrishon.co.il/'.$cat_name.'/'.$postId;
    deleteDirectory($file_path);
    file_put_contents('./../log_d_cache2_'.date("j.n.Y").'.log', $file_path.PHP_EOL, FILE_APPEND);
}
