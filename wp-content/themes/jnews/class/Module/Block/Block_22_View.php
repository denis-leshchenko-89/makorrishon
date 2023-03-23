<?php
/**
 * @author : Jegtheme
 */
namespace JNews\Module\Block;

Class Block_22_View extends BlockViewAbstract
{
    public function render_block_type_1($post, $image_size)
    {
        $thumbnail          = $this->get_thumbnail($post->ID, $image_size);
        $primary_category   = $this->get_primary_category($post->ID);
       $value = get_field( "has_video", $post->ID ); //has_video ACF field
        $audio = get_field( "has_audio", $post->ID );
        $stampa = get_field( "stampa", $post->ID );
         $categories = wp_get_post_categories($post->ID, 'fields=ids');

        if ($value==1)
           $icon="<img class=\"movie_icon\" src=\"".get_parent_theme_file_uri('/assets/img/movie_icon_block_22.png'). "\"/>";
        else if ($audio==1)
            $icon="<img class=\"movie_icon\" src=\"".get_parent_theme_file_uri('/assets/img/radio_icon_s.png'). "\"/>";
        else
           $icon="";
        if ($stampa==1)
            $stampaicon="<img class=\"stampa_icon_block_22\" src=\"".get_parent_theme_file_uri('/assets/img/stampa-small.png'). "\"/>";
        else
            $stampaicon="";
        if(in_array(815, $categories) || in_array(23261, $categories)) {
            $authors = wp_get_post_terms($post->ID, 'writer');

            require_once(get_parent_theme_file_path('MakorFunctionsHelper.php'));
            $authorNames = getAuthorName($authors);
            $authorNamesnoGeresh = str_replace('"', '', $authorNames);
            $collab = get_field("collaboration", $post->ID);
            $collabNoGeresh = str_replace('"', '', $collab);
            $nameofShituf = $collab ? $collabNoGeresh : $authorNamesnoGeresh;
            $created = get_the_date(DATE_W3C, $post);
            $str = strtotime($created);
            $reg_date = (date('d-m-Y', $str));

                $output =
                    " <article " . jnews_post_class(" jeg_post jeg_pl_md_5", $post->ID) . ">
                 <div class=\"marketing_flex\">
                <div class=\"jeg_thumb\">
                {$stampaicon}
                    " . jnews_edit_post($post->ID) . "
                    <a href=\"" . get_the_permalink($post) . "\">" . $thumbnail . "</a>
                    <div class=\"jeg_post_category\">
                        <span>{$primary_category}</span>
                    </div>
                </div>
                
                <div class=\" marketing-background jeg_postblock_content\">
                    <h3 style='position: relative' class=\"jeg_post_title\">
                    {$icon}
                    " . $this->post_meta_4($post) . "
                   
                        <a  href=\"" . get_the_permalink($post) . "\">" . get_the_title($post) . "</a>
                         <span data-tooltip=\" תוכן בשיתוף עם " . $nameofShituf . " הכתבה הופקה בידי המחלקה המסחרית של מקור ראשון בהשתתפות מימונית של גורם חיצוני ופורסמה לאחר עריכה עיתונאית \">                           <span class =\"marketing_circle\"></span></span> 
                    </h3>
                    <div class=\"marketing_date jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\"><i class=\"fa fa-clock-o\"></i> " . $reg_date . "</a></div>
                 </div>
              </div>
            </article> ";

        }
        else {

            $output =
                "<article " . jnews_post_class("jeg_post jeg_pl_md_5", $post->ID) . ">
                <div class=\"jeg_thumb\">
                {$stampaicon}
                    " . jnews_edit_post($post->ID) . "
                    <a href=\"" . get_the_permalink($post) . "\">" . $thumbnail . "</a>
                    <div class=\"jeg_post_category\">
                        <span>{$primary_category}</span>
                    </div>
                </div>
                <div class=\"jeg_postblock_content\">
                    <h3 class=\"jeg_post_title\">
                    {$icon}
                        <a href=\"" . get_the_permalink($post) . "\">" . get_the_title($post) . "</a>
                    </h3>
                    " . $this->post_meta_2($post) . "
                </div>
            </article>";




        }
        return $output;
    }

    public function build_column_1($results)
    {
        $first_block = '';
        for($i = 0; $i < sizeof($results); $i++) {
            $first_block .= $this->render_block_type_1($results[$i], 'jnews-120x86');
        }
        $output =
            "<div class=\"jeg_posts_wrap\">
                <div class=\"marketing-align jeg_posts jeg_load_more_flag\"> 
                    {$first_block}
                </div>
            </div>";

        return $output;
    }

    public function build_column_2($results)
    {
        $first_block = '';
        for($i = 0; $i < sizeof($results); $i++) {
            $first_block .= $this->render_block_type_1($results[$i], 'jnews-350x250');
        }

        $output =
            "<div class=\"jeg_posts_wrap\">
                <div class=\"marketing-align jeg_posts jeg_load_more_flag\"> 
                    {$first_block}
                </div>
            </div>";

        return $output;
    }

    public function build_column_1_alt($results)
    {
        $first_block = '';
        for($i = 0; $i < sizeof($results); $i++) {
            $first_block .= $this->render_block_type_1($results[$i], 'jnews-120x86');
        }

        $output = $first_block;

        return $output;
    }

    public function build_column_2_alt($results)
    {
        $first_block = '';
        for($i = 0; $i < sizeof($results); $i++) {
            $first_block .= $this->render_block_type_1($results[$i], 'jnews-350x250');
        }

        $output = $first_block;

        return $output;
    }

    public function render_output($attr, $column_class)
    {
	    if ( isset( $attr['results'] ) ) {
		    $results = $attr['results'];
	    } else {
		    $results = $this->build_query($attr);
	    }

	    $navigation = $this->render_navigation($attr, $results['next'], $results['prev'], $results['total_page']);

        if(!empty($results['result'])) {
            $content = $this->render_column($results['result'], $column_class);
        } else {
            $content = $this->empty_content();
        }

        return
            "<div class=\"jeg_block_container\">
                {$this->get_content_before($attr)}
                {$content}
                {$this->get_content_after($attr)}
            </div>
            <div class=\"jeg_block_navigation\">
                {$this->get_navigation_before($attr)}
                {$navigation}
                {$this->get_navigation_after($attr)}
            </div>";
    }

    public function render_column($result, $column_class)
    {
        switch($column_class)
        {
            case "jeg_col_1o3" :
                $content = $this->build_column_1($result);
                break;
            case "jeg_col_3o3" :
            case "jeg_col_2o3" :
            default :
                $content = $this->build_column_2($result);
                break;
        }

        return $content;
    }

    public function render_column_alt($result, $column_class)
    {
        switch($column_class)
        {
            case "jeg_col_1o3" :
                $content = $this->build_column_1_alt($result);
                break;
            case "jeg_col_3o3" :
            case "jeg_col_2o3" :
            default :
                $content = $this->build_column_2_alt($result);
                break;
        }

        return $content;
    }
}
