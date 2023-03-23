<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 12/12/17
 * Time: 10:50
 */
namespace JNews\Module\Element;

use JNews\Module\ModuleOptionAbstract;

Class Element_Olive_Option extends ModuleOptionAbstract
{
    public function compatible_column()
    {
        return array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
    }

    public function get_module_name()
    {
        return esc_html__('Makor - Olive', 'jnews');
    }

    public function get_category()
    {
        return esc_html__('JNews - Element', 'jnews');
    }

    public function set_options()
    {
        $this->set_header_option();
        $this->set_style_option();
    }

    public function set_header_option()
    {
//        $this->options[] = array(
//            'type'          => 'textfield',
//            'param_name'    => 'width',
//            'heading'       => esc_html__('Width', 'jnews'),
//            'description'   => esc_html__('Width on pixel / px, leave it empty to use the default number.', 'jnews'),
//            'group'         => esc_html__('Olive Design', 'jnews'),
//        );
    }
}
