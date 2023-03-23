<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 12/12/17
 * Time: 10:49
 */
namespace JNews\Module\Element;

use JNews\Module\ModuleViewAbstract;

Class Element_Block13_View extends ModuleViewAbstract
{

    protected $margin;

    public function render_module($attr, $column_class)
    {
        // Heading
        $subtitle       = ! empty($attr['second_title']) ? "<strong>{$attr['second_title']}</strong>"  : "";
        $header_class   = "jeg_block_{$attr['header_type']}";
        $heading_title  = $attr['first_title'] . $subtitle;

        if(!empty($heading_title))
        {
            $heading_icon   = empty($attr['header_icon']) ? "" : "<i class='{$attr['header_icon']}'></i>";
            $heading_title  = "<span>{$heading_icon}{$subtitle} {$attr['first_title']}</span>"; // Nadav edition
            $heading_title  = ! empty($attr['url']) ? "<a href='{$attr['url']}'>{$heading_title}</a>" : $heading_title;
            $heading_title  = "<h3 class=\"jeg_block_title\">{$heading_title}</h3>";
        }

        $style_output   = jnews_header_styling($attr, $this->unique_id);
//        $style          = !empty($style_output) ? "<style>{$style_output}</style>" : "";

        $style = $this->generate_style($attr);
        $content = $this->render_content($attr);

        // Now Render Output
        $name = 'stam name';

        $output =
            "<div class=\"element_block_13 jeg_heroblock jeg_heroblock_{$name} {$attr['hero_style']} {$this->unique_id} {$this->get_vc_class_name()}\" data-margin=\"{$attr['hero_margin']}\">
                <div class=\"jeg_heroblock_wrapper\" style='margin: 0px 0px -{$attr['hero_margin']}px -{$attr['hero_margin']}px;'>
                {$content}
                </div>
            </div>
            {$style}";

        return $output;
    }

    public function generate_style($attr)
    {
        $style = '';

        if(!empty($attr['hero_height_desktop']))
        {
            $height = $this->remove_px($attr['hero_height_desktop']);
            $style .= "@media only screen and (min-width: 1025px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }

        if(!empty($attr['hero_height_1024']))
        {
            $height = $this->remove_px($attr['hero_height_1024']);
            $style .= "@media only screen and (max-width: 1024px) and (min-width: 769px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }

        if(!empty($attr['hero_height_768']))
        {
            $height = $this->remove_px($attr['hero_height_768']);
            $style .= "@media only screen and (max-width: 768px) and (min-width: 668px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }

        if(!empty($attr['hero_height_667']))
        {
            $height = $this->remove_px($attr['hero_height_667']);
            $style .= "@media only screen and (max-width: 667px) and (min-width: 569px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }

        if(!empty($attr['hero_height_568']))
        {
            $height = $this->remove_px($attr['hero_height_568']);
            $style .= "@media only screen and (max-width: 568px) and (min-width: 481px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }

        if(!empty($attr['hero_height_480']))
        {
            $height = $this->remove_px($attr['hero_height_480']);
            $style .= "@media only screen and (max-width: 480px) { .jeg_heroblock.{$this->unique_id} .jeg_heroblock_wrapper{ height: {$height}px; } }";
        }


        if(!empty($style))
        {
            $style = "<style scoped>{$style}</style>";
        }

        return $style;
    }

    public function render_content($attr) {

        $imageId = $attr['image'];
        $image = empty($imageId) ? null : wp_get_attachment_image_src($imageId)[0];
        $title = $attr['title'];
        $link = isset($attr['link']) ? $attr['link'] : '';

        $content =
            "<article " . jnews_post_class("jeg_post jeg_hero_item_1") . ">" .
                    "<div class=\"jeg_block_container\">" .
                        ($image ? "<img src=\"{$image}\" >" : '' ) .
                        "<div class=\"jeg_postblock_content\">" .
                            "<div class=\"jeg_post_category\"></div>" .
                            "<div class=\"jeg_post_info\">" .
                                "<h2 class=\"jeg_post_title\">" .
                                    "<a href=\"" . $link . "\" >" . $title . "</a>" .
                                "</h2>
                            </div>
                        </div>
                    </div>
                </article>";

        return $content;
    }

}
