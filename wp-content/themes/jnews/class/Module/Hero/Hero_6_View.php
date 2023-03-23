<?php
/**
 * @author : Jegtheme
 */
namespace JNews\Module\Hero;

Class Hero_6_View extends HeroViewAbstract
{
    public function render_block_type_1($post)
    {
        if($post) {
            $primary_category = $this->get_primary_category($post->ID);
            $image = $this->get_thumbnail($post->ID, 'full');
            $value = get_field( "has_video", $post->ID );
            $stampa = get_field( "stampa", $post->ID );
            if ($value==1)
                $icon="<img class=\"movie_icon\" src=\"".get_parent_theme_file_uri('/assets/img/movie_icon.png'). "\"/>";
            else
                $icon="";
            if ($stampa==1)
                $stampaicon="<img class=\"stampa_icon\" src=\"".get_parent_theme_file_uri('/assets/img/stampa.png'). "\"/>";
            else
                $stampaicon="";
            $output =
                "<article " . jnews_post_class("jeg_post jeg_hero_item_1", $post->ID) . " style=\"padding: 0 0 {$this->margin}px {$this->margin}px;\">
                    <div class=\"jeg_block_container\">
                     <span> {$stampaicon}</span>
                        " . jnews_edit_post( $post->ID ) . "
                        <span class=\"jeg_postformat_icon\"></span>
                        <div class=\"jeg_thumb\">
                            <a href=\"" . get_the_permalink($post) . "\" >{$image}</a>
                        </div>
                        <div class=\"jeg_postblock_content\">
                            <div class=\"jeg_post_category\">{$icon}{$primary_category}</div>
                            <div class=\"jeg_post_info\">
                                <h2 class=\"jeg_post_title\">
                                    <a href=\"" . get_the_permalink($post) . "\" >" . get_the_title($post) . "</a>
                                </h2>
                                {$this->post_meta_3($post)}
                            </div>
                        </div>
                    </div>
                </article>";

            return $output;
        } else {
            $output = "
                <article class=\"jeg_post jeg_hero_item_1 jeg_hero_empty\" style=\"padding: 0 0 {$this->margin}px {$this->margin}px;\">
                    <div class=\"jeg_block_container\"></div>
                </article>";
            return $output;
        }
    }

    public function render_block_type_2($post, $index)
    {
        $index = $index + 1;
        $value = get_field( "has_video", $post->ID );
        $audio = get_field( "has_audio", $post->ID );
        $stampa = get_field( "stampa", $post->ID );
        if ($value==1)
            $icon="<img class=\"movie_icon_hero6\"   src=\"".get_parent_theme_file_uri('/assets/img/movie_icon.png'). "\"/>";
        else if ($audio==1)
            $icon="<img class=\"movie_icon_hero6\" src=\"".get_parent_theme_file_uri('/assets/img/audio_icon.png'). "\"/>";
        else
            $icon="";
        if ($stampa==1)
            $stampaicon="<img class=\"stampa_icon\" src=\"".get_parent_theme_file_uri('/assets/img/stampa.png'). "\"/>";
        else
            $stampaicon="";
        if($post) {
            $primary_category = $this->get_primary_category($post->ID);
            $image = $this->get_thumbnail($post->ID, 'jnews-featured-750');

            $output =
                "<article " . jnews_post_class("jeg_post jeg_hero_item_{$index}", $post->ID) . " style=\"padding: 0 0 {$this->margin}px {$this->margin}px;\">
                    <div class=\"jeg_block_container\">
                    <span> {$stampaicon}</span>
                        " . jnews_edit_post( $post->ID ) . "
                        <span class=\"jeg_postformat_icon\"></span>
                        <div class=\"jeg_thumb\">
                            <a href=\"" . get_the_permalink($post) . "\" >{$image}</a>
                        </div>
                        <div class=\"jeg_postblock_content\">
                            <div class=\"jeg_post_category\">
                                {$icon}{$primary_category}
                            </div>
                            <div class=\"jeg_post_info\">
                                <h2 class=\"jeg_post_title\">
                                    <a href=\"" . get_the_permalink($post) . "\">" . get_the_title($post) . "</a>
                                </h2>
                                {$this->post_meta_2($post)}
                            </div>
                        </div>
                    </div>
                </article>";

            return $output;
        } else {
            $output =
                "<article class=\"jeg_post jeg_hero_item_{$index} jeg_hero_empty\" style=\"padding: 0 0 {$this->margin}px {$this->margin}px;\">
                    <div class=\"jeg_block_container\"></div>
                </article>";

            return $output;
        }
    }

    public function render_element($result)
    {
        $first_block = $this->render_block_type_1($result[0]);

        $second_block = '';
        for($i = 1; $i <= ( $this->get_number_post() - 1 ); $i++){
            $item = isset($result[$i]) ? $result[$i] : '';
            $second_block .=$this->render_block_type_2($item, $i);
        }

        $output =
            "{$first_block}
            <div class=\"jeg_heroblock_scroller\">
                {$second_block}
            </div>";

        return $output;
    }
}