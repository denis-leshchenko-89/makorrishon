<?php
/**
 * @author : Jegtheme
 */
namespace JNews\Module\Widget;

Class Widget_CategoryRecentNews_Option extends WidgetOptionAbstract
{
    public function get_module_name()
    {
        return esc_html__('JNews - Category Recent News Widget', 'jnews');
    }
}