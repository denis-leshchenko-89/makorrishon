<?php
/**
 * Created by PhpStorm.
 * User: nadavt
 * Date: 12/12/17
 * Time: 10:50
 */
namespace JNews\Module\Element;

use JNews\Module\ModuleOptionAbstract;

Class Element_Block13_Option extends ModuleOptionAbstract
{
    public function compatible_column()
    {
        return array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
    }

    public function get_module_name()
    {
        return esc_html__('JNews - Cover', 'jnews');
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
        // new by Nadav
        $this->options[] = array(
            'type'          => 'attach_image',
            'param_name'    => 'image',
            'heading'       => esc_html__('Image', 'jnews'),
            'description'   => esc_html__('Upload your ads image.', 'jnews'),
            'dependency'    => Array('element' => "ads_type", 'value' => array('image')),
            'group'         => esc_html__('Details', 'jnews')
        );

        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'title',
            'holder'        => 'span',
            'heading'       => esc_html__('Title', 'jnews'),
            'description'   => esc_html__('Main title of Module Block.', 'jnews'),
            'group'         => esc_html__('Details', 'jnews'),
            'default'       => ''
        );

        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'link',
            'holder'        => 'span',
            'heading'       => esc_html__('Link', 'jnews'),
            'description'   => esc_html__('Main title of Module Block.', 'jnews'),
            'group'         => esc_html__('Details', 'jnews'),
            'default'       => ''
        );

        // -----------------------

        $this->options[] = array(
            'type'          => 'slider',
            'param_name'    => 'hero_margin',
            'heading'       => esc_html__('Hero Margin', 'jnews'),
            'description'   => esc_html__('Margin of each hero element.', 'jnews'),
            'group'         => esc_html__('Hero Setting', 'jnews'),
            'min'           => 0,
            'max'           => 30,
            'step'          => 1,
            'std'           => 0,
        );
        $this->options[] = array(
            'type'          => 'radioimage',
            'param_name'    => 'hero_style',
            'std'           => 'jeg_hero_style_1',
            'value'         => array(
                JNEWS_THEME_URL . '/assets/img/admin/hero-1.png'  => 'jeg_hero_style_1',
                JNEWS_THEME_URL . '/assets/img/admin/hero-2.png'  => 'jeg_hero_style_2',
                JNEWS_THEME_URL . '/assets/img/admin/hero-3.png'  => 'jeg_hero_style_3',
                JNEWS_THEME_URL . '/assets/img/admin/hero-4.png'  => 'jeg_hero_style_4',
                JNEWS_THEME_URL . '/assets/img/admin/hero-5.png'  => 'jeg_hero_style_5',
                JNEWS_THEME_URL . '/assets/img/admin/hero-6.png'  => 'jeg_hero_style_6',
                JNEWS_THEME_URL . '/assets/img/admin/hero-7.png'  => 'jeg_hero_style_7',
            ),
            'heading'       => esc_html__('Hero Style', 'jnews'),
            'description'   => esc_html__('Choose which hero style that fit your content design.', 'jnews'),
            'group'         => esc_html__('Hero Setting', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'dropdown',
            'param_name'    => 'date_format',
            'heading'       => esc_html__('Choose Date Format', 'jnews'),
            'description'   => esc_html__('Choose which date format you want to use.', 'jnews'),
            'std'           => 'default',
            'group'         => esc_html__('Hero Setting', 'jnews'),
            'value'         => array(
                esc_html__('Relative Date/Time Format (ago)', 'jnews')               => 'ago',
                esc_html__('WordPress Default Format', 'jnews')      => 'default',
                esc_html__('Custom Format', 'jnews')                 => 'custom',
            )
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'date_format_custom',
            'heading'       => esc_html__('Custom Date Format', 'jnews'),
            'description'   => wp_kses(sprintf(__('Please write custom date format for your module, for more detail about how to write date format, you can refer to this <a href="%s" target="_blank">link</a>.', 'jnews'), 'https://codex.wordpress.org/Formatting_Date_and_Time'), wp_kses_allowed_html()),
            'std'           => 'Y/m/d',
            'group'         => esc_html__('Hero Setting', 'jnews'),
            'dependency'    => array('element' => 'date_format', 'value' => array('custom'))
        );

        // Design
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_desktop',
            'heading'       => esc_html__('Hero Height on Dekstop', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_1024',
            'heading'       => esc_html__('Hero Height on 1024px Width Screen', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_768',
            'heading'       => esc_html__('Hero Height on 768px Width Screen', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_667',
            'heading'       => esc_html__('Hero Height on 667px Width Screen', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_568',
            'heading'       => esc_html__('Hero Height on 568px Width Screen', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
        $this->options[] = array(
            'type'          => 'textfield',
            'param_name'    => 'hero_height_480',
            'heading'       => esc_html__('Hero Height on 480px Width Screen', 'jnews'),
            'description'   => esc_html__('Height on pixel / px, leave it empty to use the default number.', 'jnews'),
            'group'         => esc_html__('Hero Design', 'jnews'),
        );
    }
}
