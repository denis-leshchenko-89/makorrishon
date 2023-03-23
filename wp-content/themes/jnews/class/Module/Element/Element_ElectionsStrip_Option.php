<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 04/02/19
 * Time: 12:27
 */

namespace JNews\Module\Element;

use JNews\Module\ModuleOptionAbstract;

class Element_ElectionsStrip_Option extends ModuleOptionAbstract {

    public function compatible_column()
    {
        return array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
    }

    public function get_module_name()
    {
        return esc_html__('Elections 2019 strip', 'jnews');
    }

    public function get_category()
    {
        return esc_html__('Makor', 'jnews');
    }

    public function set_options()
    {
        $this->set_style_option();
    }
}