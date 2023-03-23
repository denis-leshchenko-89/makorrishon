<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 04/02/19
 * Time: 12:27
 */

namespace JNews\Module\Element;

use JNews\Module\ModuleOptionAbstract;

class Element_FacebookVideo_Option extends ModuleOptionAbstract {

    public function compatible_column()
    {
        return array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
    }

    public function get_module_name()
    {
        return esc_html__('Facebook Video', 'jnews');
    }

    public function get_category()
    {
        return esc_html__('Makor', 'jnews');
    }

    public function set_options()
    {

        $this->set_header_option();
        $this->set_style_option();

    }

    public function set_header_option()
    {
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'videoid',
            'heading'       => esc_html__('Facebook video id', 'jnews'),
            'description'   => esc_html__('Insert a facebook video id.', 'jnews'),
        );
    }
}