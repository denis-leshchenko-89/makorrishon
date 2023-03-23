<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 04/02/19
 * Time: 12:24
 */

namespace JNews\Module\Element;

use JNews\Module\ModuleViewAbstract;

Class Element_FacebookVideo_View extends ModuleViewAbstract
{

    protected $images;

    function __construct()
    {
        parent::__construct();
    }

    public function render_module($attr, $column_class)
    {
        $mute = '&mute=1';
       // $autoplay='&autoplay=1';
        $videoid = $attr['videoid'];
        $src = 'https://www.facebook.com/plugins/video.php?href='.$videoid.$mute;
        $output = '<div id="player" style="position: relative; padding-bottom: 56.25%; padding-top: 0px; height: 0; overflow: hidden;"><iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" src= '.$src.' scrolling="no" allowfullscreen="allowfullscreen"><span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_start">﻿</span>
</iframe></div><div id="space" style="margin-bottom: 10px"></div>';
        return $output;
    }


}

