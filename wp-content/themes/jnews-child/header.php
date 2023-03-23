<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=yes' />
    <meta property="fb:pages" content="204780362875106" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--    <link rel="icon" type="image/png" href="--><?php //echo get_template_directory_uri();?><!--/assets/img/makor1-icon-32x32.png"/>-->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri();?>/assets/img/favicon.ico"/>
    <script src="/c3650cdf-216a-4ba2-80b0-9d6c540b105e58d2670b-ea0f-484e-b88c-0e2c1499ec9bd71e4b42-8570-44e3-89b6-845326fa43b6" type="{text/javascript}"></script>
<!--    <script defer src="https://users.api.jeeng.com/users/domains/VAMRWlJnjL/sdk/configs"> </script>-->
<!--    <script defer src="https://sdk.jeeng.com/v3.js"> </script>-->
    
    <style>
        .magnifier-mobile {
            content:url('https://prodpsus1.blob.core.windows.net/content/makorrishon/Images/search_icon-black_resized.png');
            cursor: pointer;
        }
    </style>
    <script async="async" src="//zdwidget3-bs.sphereup.com/zoomd/SearchUi/Script?clientId=96071093">
    </script>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6TR93C"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'GTM-T6TR93C');
</script>
    <?php do_action('jnews_after_body'); ?>
    <!-- Google Tag Manager (noscript) -->


    <!-- End Google Tag Manager (noscript) -->
<!-- Google Tag Manager (noscript) -->
<?php     if (is_single ('260247') || is_single ('282703') || is_single ('286315') || is_single ('293795'))  { ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRHSHLV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php } ?>
<!-- End Google Tag Manager (noscript) -->

<!-- Google Tag Manager (noscript) -->
<?php if (is_single ('278809') || is_single ('278835')) { ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRS2L74"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php } ?>
<!-- End Google Tag Manager (noscript) -->

<?php
/*if ( is_single() ) {
global $post;

$categories = wp_get_post_categories($post->ID,'fields=ids');
if (in_array(879, $categories)) {
    $recipes = get_field('recipe');
    if (recipes) {
        $writer = str_replace('"', '\"', wp_get_object_terms($post->ID, 'writer'));
        $writerfield = $writer[0];
        $writerName1 = str_replace('"', '\"',$writerfield->name);
        $writerName = str_replace(' ', '-', $writerName1);
        $writerTid = $writerfield->term_id;
        $recipe_name =  str_replace('"', '\"',$recipes["recipe_name"]);
        $recipe_description = str_replace('"', '\"',$recipes["recipe_description"]);
        $recipe_keywords = str_replace('"', '\"',$recipes["recipe_keywords"]);
        $recipe_image = $recipes["recipe_image"]["url"];
        $prepTime = $recipes["preptime"];
        $cookTime = $recipes["cooktime"];
        $totalTime = $recipes["totaltime"];
        $recipeCategory = $recipes["recipecategory"];
        $recipeCuisine = $recipes["recipecuisine"];
        $recipeYield = $recipes["recipeyield"];
        $calories = $recipes["calories"];
       $recipeIngredient[] = str_replace('"', '\"',$recipes["recipeingredient"]);

      $recipeIngredientStr = '["';
      $recipeIngredientStr .= implode('", "', $recipeIngredient);
      $recipeIngredientStr .= '"]';

        $created = get_the_date(DATE_W3C);
        $str1 = strtotime($created);
        $gmtDiff = (date("Z")) / 60 / 60;
        $created_format  = (date('Y-m-d\TH:i:s', $str1)) . '+0'.$gmtDiff.':00';
        //file_put_contents('./log_recipe1_.log', 'recipe image'.print_r($recipe_image,true).PHP_EOL, FILE_APPEND);
        $google_schema_recipe = '<script type="application/ld+json">
   {
  "@context": "https://schema.org/",
  "@type": "Recipe",
  "name": "'.$recipe_name.'",
  "description": "'.$recipe_description.'",
   "image": "'.$recipe_image.'",
   "keywords": "'.$recipe_keywords.'",
  "author": {
    "@type": "Person",
    "name": "'.$writerName1.'",
  },
 "prepTime": "'.$prepTime.'",
 "cookTime": "'.$cookTime.'",
 "totalTime": "'.$totalTime.'",
 "recipeCategory": "'.$recipeCategory.'",
 "recipeCuisine": "'.$recipeCuisine.'",
  "recipeYield": "'.$recipeYield.'",
  "nutrition": {
    "@type": "NutritionInformation",
 "calories": "'.$calories.'"
 },
  "datePublished": "'.$created_format.'",
  "recipeIngredient": '.$recipeIngredientStr.'
  
  }
</script>
';
        echo $google_schema_recipe;
    }
}
 }*/
if ( is_single() ) {
    global $post;
    $title = str_replace('"', '\"', get_the_title($post));
    $post_sub = get_post_meta( $post->ID, 'post_subtitle', true );
    //before change
   // $post_subtitle = str_replace('"', '\"', $post_sub);
    //before change
   // $post_subtitle1 = str_replace('\\', '/\\', $post_subtitle);
   // $subtitle = !empty( $post_subtitle ) ? esc_html( get_post_meta( $post->ID, 'post_subtitle', true ) ) : '';
//    $subtitle = !empty( $post_subtitle ) ? esc_html( $post_subtitle ) : '';
    //new changes
    $post_subtitle = str_replace('\\', '\\/', $post_sub);
    $post_subtitle1 =str_replace('"', '\\/', $post_subtitle);
    $subtitle = $post_subtitle1;
//writer
    $writer = str_replace('"', '\"', wp_get_object_terms($post->ID, 'writer'));
    $writerfield = $writer[0];
    $writerName1 = str_replace('"', '\"',$writerfield->name);
    $writerName = str_replace(' ', '-', $writerName1);
    $writerTid = $writerfield->term_id;
 //categories   
    $categories = wp_get_post_categories($post->ID,'fields=all');
    foreach ($categories as $categorie){
        $categoryElem = json_decode(json_encode($categorie), true);

        $sections[] = str_replace('"', '\"',$categoryElem['name']);
      }
      $sectionsStr = '["';
      $sectionsStr .= implode('", "', $sections);
      $sectionsStr .= '"]';
    
   
 //Dates
   $modified = get_the_modified_date(DATE_W3C);
   $str = strtotime(str_replace("/", "-", $modified));
   $gmtDiff = (date("Z")) / 60 / 60;
   $modified_format = (date('Y-m-d\TH:i:s', $str)) . '+0'.$gmtDiff.':00';
   
   //$created = get_the_date('Y-m-d h:i A'); 
   $created = get_the_date(DATE_W3C); 
   $str1 = strtotime($created);
   $created_format  = (date('Y-m-d\TH:i:s', $str1)) . '+0'.$gmtDiff.':00';
//Images
   $attimages = get_attached_media('image', $post->ID);
   $imagesArr = array();
   foreach ($attimages as $image) {
       $imagesArr[] =  wp_get_attachment_url($image->ID);
   }
    require_once (get_parent_theme_file_path('MakorFunctionsHelper.php'));
    $image_metadata = get_post_image_metadata( $post );
    if ( $image_metadata ) {
             array_push($imagesArr,$image_metadata['url']);
      }
   $imagesStr = '["';
    $imagesStr .= implode('","', $imagesArr);
   $imagesStr .= '"]';
   $base_url = get_site_url();
   $post_url = get_permalink();
   $articleBody1 = strip_shortcodes($post->post_content);
    $articleBody = strip_tags($articleBody1);
    $articleBody = preg_replace('/\s+/', ' ', trim($articleBody));
    //before change
   // $articleBody2 = str_replace('"', '\"', $articleBody);
    //new change
    $articleBody2 = str_replace('\\', '\\/', $articleBody);
    $articleBody3 = str_replace('"', '\\/', $articleBody2);
    $words = explode(" ", $articleBody);
    $wordCount = count($words);
    $response = wp_remote_get('https://open-api.spot.im/v1/messages-count?spot_id=' . SPOT_IM_ID . '&posts_ids=' . $post->ID, array());
    $response = json_decode(wp_remote_retrieve_body($response));
    
    if ( is_wp_error( $response ) ) {
        $response_count = 0;
    }
    else {
        $response_count = reset($response->messages_count);
        if ($response_count == "")
            $response_count = 0;
    }



    $google_schema = '<script type="application/ld+json">
   {
  "@context": "https://schema.org/",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "'. $post_url.'"
  },
  "headline": "'.$title.'",
  "description": "'.$subtitle.'",
  "articleBody": "'.$articleBody3.'",
  "image": '.$imagesStr.',
  "author": {
    "@type": "Person",
    "name": "'.$writerName1.'",
    "sameAs": "https://www.makorrishon.co.il/writer/'.$writerName.'"
  },
  "publisher": {
    "@type": "NewsMediaOrganization",
    "name": ["Makor Rishon", "מקור ראשון"],
    "sameAs": [
    "https://he.wikipedia.org/wiki/%D7%9E%D7%A7%D7%95%D7%A8_%D7%A8%D7%90%D7%A9%D7%95%D7%9F",
    "https://www.facebook.com/makorrishon",
    "https://twitter.com/MakorRishon",
    "https://www.youtube.com/channel/UCRStSS7Dl9-Xu7HDrn6fMnA/videos",
    "https://www.makorrishon.co.il/"
    ],
    "logo": {
      "@type": "ImageObject",
      "url": "https://makorrishon.co.il/wp-content/uploads/2020/06/makor1-logo-1-e1540370188149ccc.png"
    }
  },
  "datePublished": "'.$created_format.'",
  "dateModified": "'.$modified_format.'",
  "articleSection": '.$sectionsStr.',
  "wordCount": "'.$wordCount.'",
  "commentCount": "'.$response_count.'",
  "inLanguage": "he",
  "isAccessibleForFree":true,"hasPart":{"@type":"WebPageElement","isAccessibleForFree":true,"cssSelector":".content-inner"}
  }
</script>
';


echo $google_schema;
  } ?>

<!-- Google Schema News Article End-->
    <?php get_template_part('fragment/side-feed'); ?>

    <div class="jeg_ad jeg_ad_top jnews_header_top_ads">
        <?php do_action('jnews_header_top_ads'); ?>
    </div>

    <!-- The Main Wrapper
    ============================================= -->
    <div class="jeg_viewport">

        <?php jnews_background_ads(); ?>

        <div class="jeg_header_wrapper">
            <?php get_template_part('fragment/header/desktop-builder'); ?>
        </div>

        <div class="jeg_header_sticky">
            <?php get_template_part('fragment/header/desktop-sticky-wrapper'); ?>
        </div>

        <div class="jeg_navbar_mobile_wrapper">
            <?php get_template_part('fragment/header/mobile-builder'); ?>
        </div>