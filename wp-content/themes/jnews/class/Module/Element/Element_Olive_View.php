<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 12/12/17
 * Time: 10:49
 */
namespace JNews\Module\Element;

use JNews\Module\ModuleViewAbstract;

Class Element_Olive_View extends ModuleViewAbstract
{

    protected $margin;

    public function render_module($attr, $column_class)
    {

        $link = MAKOR_OLIVE_URL;
        $imageUrl = $this->getImageLink();
        $style = $this->generate_style($attr);
        // Now Render Output
        $output =
            "<div class=\"gdlr-image-frame-item gdlr-item\" id=\"mk_epaper\" style=\"margin-bottom: 30px;\">
                <div class=\"gdlr-frame frame-type-solid\" style=\"background-color: #1e73be;\">
                    <div class=\"gdlr-image-link-shortcode\">
                        <img src=\"{$imageUrl}\" alt=\"\" width=\"200\" height=\"62\" style=\"opacity: 1; width: 200px;\">
                        <a href=\"{$link}\" target='_blank'>
                            <span class=\"gdlr-image-link-overlay\" style=\"opacity: 0;\">&nbsp;</span>
                            <span class=\"gdlr-image-link-icon\" style=\"opacity: 0;\">
                                <i class=\"fa icon-link\"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <script>
                $('.gdlr-image-link-shortcode').hover(function(){
                    $(this).find('.gdlr-image-link-overlay').animate({opacity: 0.8}, 150);
                    $(this).find('.gdlr-image-link-icon').animate({opacity: 1}, 150);
                }, function(){
                    $(this).find('.gdlr-image-link-overlay').animate({opacity: 0}, 150);
                    $(this).find('.gdlr-image-link-icon').animate({opacity: 0}, 150);
                });	
            </script>
            {$style}"
;

        return $output;
    }

    public function getImageLink() {
        $lastFriday = date('Y-m-d', strtotime('last Friday'));
        $oneBeforeTheLastFriday = new \DateTime($lastFriday);
        $oneBeforeTheLastFriday->modify('-1 week');
        $imageUrl = MAKOR_OLIVE_PREFIX . $lastFriday . MAKOR_OLIVE_SUFFIX;
        $response = wp_remote_get(esc_url_raw($imageUrl));
        // try with last friday
        if(isset($response['response']['code']) && $response['response']['code'] == 200) {
            return $imageUrl;
        }
        // try with one before the last friday
        $imageUrl = MAKOR_OLIVE_PREFIX . $oneBeforeTheLastFriday->format('Y-m-d') . MAKOR_OLIVE_SUFFIX;
        $response = wp_remote_get(esc_url_raw($imageUrl));
        if($response['response']['code'] == 200) {
            return $imageUrl;
        }
        return '';
    }

    public function generate_style($attr)
    {
        $style = '
        .gdlr-image-frame-item {
            margin: 0px 15px 0;
            text-align: center;
            line-height: 0;
        }
        .gdlr-frame.frame-type-solid {
            display: inline-block;
            max-width: 100%;
            position: relative;
            padding: 5px;
        }
        .gdlr-image-link-shortcode {
            position: relative;
        }
        .gdlr-image-link-overlay {
            background-color: #0a0101;
            position: absolute;
            top: 0px;
            right: 0px;
            bottom: 0px;
            left: 0px;
            opacity: 0;
            filter: alpha(opacity=0);
            cursor: pointer;
        }
        .gdlr-image-link-icon {
            color: #ffffff; 
            padding: 25px;
            line-height: 1;
            display: inline-block;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -37px;
            margin-left: -37px;
            background: url(wp-content/themes/jnews/assets/img/portfolio-icon-overlay.png);
            opacity: 0;
            filter: alpha(opacity=0);
            -moz-border-radius: 37px;
            -webkit-border-radius: 37px;
            border-radius: 37px;
            filter: inherit;
        }
        '

        ;

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

}
