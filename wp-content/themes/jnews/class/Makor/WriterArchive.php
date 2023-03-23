<?php
/**
 * @author : Nadav
 */
namespace JNews\Makor;

use JNews\Archive\ArchiveAbstract;
use JNews\Module\ModuleManager;

/**
 * Class Theme Archive Author
 */
Class WriterArchive extends ArchiveAbstract
{
    /**
     * @var \WP_Term
     */
    protected $writer;

    /**
     * @var String
     */
    protected $section;

    public function __construct($writer)
    {
        $this->writer = $writer;
        $this->section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
    }

    public function render_content()
    {
        $content_width = array($this->get_content_width());
        ModuleManager::getInstance()->set_width($content_width);

        $post_per_page = get_option( 'posts_per_page' );

        $attr = array(
            'content_type' => $this->section,
            'date_format' => $this->get_content_date(),
            'date_format_custom' => $this->get_content_date_custom(),
            'excerpt_length' => $this->get_content_excerpt(),
            'pagination_number_post' => $post_per_page,
            'number_post' => $post_per_page,
            'post_offset' => $this->offset,
//            'include_author' => $this->writer->term_id,
            'sort_by' => 'latest',
            'pagination_mode' => $this->get_content_pagination(),
            'pagination_scroll_limit' => $this->get_content_pagination_limit(),
            'paged' => jnews_get_post_current_page(),
            'pagination_align' => $this->get_content_pagination_align(),
            'pagination_navtext' => $this->get_content_pagination_navtext(),
            'pagination_pageinfo' => $this->get_content_pagination_pageinfo(),
            'push_archive' => true,
            'tax_query' => array(
                array(
                    'taxonomy' => 'writer',
                    'terms' => array($this->writer->term_id) // Where term_id of Term 1 is "1".
                )
            )
        );

        $name = jnews_get_view_class_from_shortcode ( 'JNews_Block_' . $this->get_content_type() );
        $this->content_instance = jnews_get_module_instance($name);
        return $this->content_instance->build_module($attr);
    }

    // content
    public function get_content_type()
    {
        return apply_filters('jnews_author_content', get_theme_mod('jnews_author_content', '3'), $this->writer);
    }

    public function get_content_excerpt()
    {
        return apply_filters('jnews_author_content_excerpt', get_theme_mod('jnews_author_content_excerpt', 20), $this->writer);
    }

    public function get_content_date()
    {
        return apply_filters('jnews_author_content_date', get_theme_mod('jnews_author_content_date', 'default'), $this->writer);
    }

    public function get_content_date_custom()
    {
        return apply_filters('jnews_author_content_date_custom', get_theme_mod('jnews_author_content_date_custom', 'Y/m/d'), $this->writer);
    }

    public function get_content_pagination()
    {
        return apply_filters('jnews_author_content_pagination', get_theme_mod('jnews_author_content_pagination', 'nav_1'), $this->writer);
    }

    public function get_content_pagination_limit()
    {
        return apply_filters('jnews_author_content_pagination_limit', get_theme_mod('jnews_author_content_pagination_limit'), $this->writer);
    }

    public function get_content_pagination_align()
    {
        return apply_filters('jnews_author_content_pagination_align', get_theme_mod('jnews_author_content_pagination_align', 'center'), $this->writer);
    }

    public function get_content_pagination_navtext()
    {
        return apply_filters('jnews_author_content_pagination_show_navtext', get_theme_mod('jnews_author_content_pagination_show_navtext', false), $this->writer);
    }

    public function get_content_pagination_pageinfo()
    {
        return apply_filters('jnews_author_content_pagination_show_pageinfo', get_theme_mod('jnews_author_content_pagination_show_pageinfo', false), $this->writer);
    }

    public function get_content_show_sidebar()
    {
        return apply_filters('jnews_author_show_sidebar',  get_theme_mod('jnews_author_show_sidebar', true), $this->writer);
    }

    public function get_content_sidebar()
    {
        return apply_filters('jnews_author_sidebar',  get_theme_mod('jnews_author_sidebar', 'default-sidebar'), $this->writer);
    }

    public function sticky_sidebar()
    {
        return apply_filters('jnews_author_sticky_sidebar', get_theme_mod('jnews_author_sticky_sidebar', true), $this->archive_id);
    }

    public function get_sticky_sidebar()
    {
        if ( $this->sticky_sidebar() )
        {
            return 'jeg_sticky_sidebar';
        }

        return false;
    }

    public function get_header_title(){}
    public function get_header_description(){}
}
