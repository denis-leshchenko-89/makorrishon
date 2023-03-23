<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 04/02/19
 * Time: 12:24
 */

namespace JNews\Module\Element;

use JNews\Module\ModuleViewAbstract;

Class Element_ElectionsStrip_View extends ModuleViewAbstract
{

    protected $images;

    function __construct()
    {
        parent::__construct();
        $this->images = array (
            array('name'=>'ליברמן','width' => 68, 'height' => 76, 'link' => 'https://www.makorrishon.co.il/elections19/liberman', 'slug' => 'liberman'),
            array('name'=>'בנט ושקד','width' => 117, 'height' => 82, 'link' => 'https://www.makorrishon.co.il/elections19/shaked-bennet', 'slug' => 'bennet'),
            array('name'=>'כחלון','width' => 71, 'height' => 88, 'link' => 'https://www.makorrishon.co.il/elections19/kahlon', 'slug' => 'kahlon'),
            array('name'=>'פרץ','width' => 59, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/peretz', 'slug' => 'peretz'),
//            array('name'=>'סמוטריץ','width' => 66, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/smotrich', 'slug' => 'smotrich'),
            array('name'=>'נתניהו','width' => 75, 'height' => 90, 'link' => 'https://www.makorrishon.co.il/elections19/netanyahu', 'slug' => 'netanyahu'),
            array('name'=>'גנץ ולפיד','width' => 110, 'height' => 87, 'link' => 'https://www.makorrishon.co.il/elections19/gantz-lapid', 'slug' => 'gantz'),
//            array('name'=>'לבני','width' => 85, 'height' => 84, 'link' => 'https://www.makorrishon.co.il/elections19/livni', 'slug' => 'livni'),
            array('name'=>'גבאי','width' => 71, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/gabay', 'slug' => 'gabay'),
            array('name'=>'ליצמן','width' => 74, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/litzman', 'slug' => 'litzman'),
            array('name'=>'דרעי','width' => 88, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/deri', 'slug' => 'deri'),
//            array('name'=>'לוי','width' => 74, 'height' => 96, 'link' => 'https://www.makorrishon.co.il/elections19/leviabeksis', 'slug' => 'levi'),
            array('name'=>'פיגלין','width' => 68, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/feiglin', 'slug' => 'feiglin'),
            array('name'=>'תמר','width' => 75, 'height' => 99, 'link' => 'https://www.makorrishon.co.il/elections19/zandberg', 'slug' => 'zandberg'),
//            array('name'=>'עודה', 'width' => 82, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/odeh', 'slug' => 'odeh'),
//            array('name'=>'טיבי', 'width' => 65, 'height' => 88, 'link' => 'https://www.makorrishon.co.il/elections19/tibi', 'slug' => 'tibi')
            array('name'=>'טיבי-עודה', 'width' => 125, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/odeh-tibi', 'slug' => 'odeh-tibi'),
            array('name'=>'מנצור','width' => 72, 'height' => 100, 'link' => 'https://www.makorrishon.co.il/elections19/abas', 'slug' => 'abas')
        );
    }

    public function render_module($attr, $column_class)
    {
        $imagesHtml = '';
        $script = $this->getScript();
        foreach ($this->images as $image) {
            $imagesHtml .=
                '<a class="makor-elections-portrait-container '. $image['slug']. '" style="width:'. $image['width'] .'px; height:'. $image['height'] .'px"
                    href="'. $image['link'] .'">
                    <img class="makor-elections-portrait" alt="'. $image['name'] .
                    '" src="/wp-content/themes/jnews/assets/img/elections2019/blue/'. $image['name'] .'.png">
                    <img class="makor-elections-portrait-color" alt="'. $image['name'] .
                    '" src="/wp-content/themes/jnews/assets/img/elections2019/colors/'. $image['name'] .'.png">
                </a>';
        }

        $output =   '<div class="makor-elections-container" id="makor-elections-container">
                        <div class="makor-elections-strip" id="makor-elections-strip">' .
                            $imagesHtml .
                        '<a href="#" class="flex-prev hidden"><img src="/wp-content/themes/jnews/assets/img/elections2019/arrow_right.png"></a>
                         <a href="#" class="flex-next"><img src="/wp-content/themes/jnews/assets/img/elections2019/arrow_left.png"></a>
                        </div>
                    </div>' .
                    $script;
        return $output;
    }

    private function getScript() {

        $script = '<script>
                    
                    (function($){
                      
                        window.addEventListener("load", function(e){
                            
                            var body = $("body");
                            var strip = document.querySelector("#makor-elections-strip");
                            var container = document.querySelector("#makor-elections-container");
                            var prev = $(".makor-elections-container .flex-prev");
                            var next = $(".makor-elections-container .flex-next");
                            
                            var touchCheck = function(e) {
                              if(!body.hasClass("INDMobile")) {
                                return;
                              }
                                
                              var scrollLeft = e.currentTarget.children[0].scrollLeft;
                              if(scrollLeft === 0) {
                                next.addClass("hidden");  
                              } else {
                                next.removeClass("hidden");
                              }
                              
                              if(e.currentTarget.children[0].scrollLeft === e.currentTarget.children[0].scrollWidth - e.currentTarget.children[0].offsetWidth) {
                                prev.addClass("hidden");
                              } else {
                                prev.removeClass("hidden");
                              }
                            };
    
                            container.addEventListener("touchend", touchCheck, false);     
                            container.addEventListener("touchstart", touchCheck, false);     
                        });
                    })(jQuery);
                    
                  </script>';

        return $script;
    }

}