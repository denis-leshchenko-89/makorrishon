<?php
/**
 * @author : Jegtheme
 */

namespace JNews\Module;

use JNews\Image\ImageNormalLoad;

abstract Class ModuleViewAbstract {
	/**
	 * @var array
	 */
	protected static $instance;

	/**
	 * Option Field
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * @var string
	 */
	protected $unique_id;

	/**
	 * Array of attribute
	 *
	 * @var array
	 */
	protected $attribute;

	/**
	 * @var ModuleManager
	 */
	protected $manager;

	/**
	 * @var string
	 */
	protected $class_name;

	/**
	 * @var ModuleOptionAbstract
	 */
	protected $option_class;

	/**
	 * @var String
	 */
	protected $content;

	/**
	 * @return ModuleViewAbstract
	 * @var $manager
	 */
	public static function getInstance() {
		$class = get_called_class();

		if ( ! isset( self::$instance[ $class ] ) ) {
			self::$instance[ $class ] = new $class();
		}

		return self::$instance[ $class ];
	}

	/**
	 * ModuleViewAbstract constructor.
	 */
	protected function __construct() {
		$this->class_name = jnews_get_shortcode_name_from_view( get_class( $this ) );
		$this->manager    = ModuleManager::getInstance();

		// set option class
		$class_option       = str_replace( '_View', '_Option', get_class( $this ) );
		$this->option_class = call_user_func( array( $class_option, 'getInstance' ) );

		$this->set_options();
	}

	private function set_options() {
		$options = $this->option_class->get_options();

		foreach ( $options as $option ) {
			$this->options[ $option['param_name'] ] = isset( $option['std'] ) ? $option['std'] : '';
		}
	}

	private function compatible_column() {
		return $this->option_class->compatible_column();
	}

	public function color_scheme() {
		return $this->attribute['scheme'];
	}

	public function get_vc_class_name() {
		$class_name = null;

		if ( isset( $this->attribute['css'] ) ) {
			$css_exploded = explode( '{', $this->attribute['css'] );
			$class        = $css_exploded[0];
			$class_name   = substr( $class, 1 );
		}

		if ( isset( $this->attribute['boxed'] ) && $this->attribute['boxed'] ) {
			$class_name .= ' jeg_pb_boxed';
		}
		if ( isset( $this->attribute['boxed_shadow'] ) && $this->attribute['boxed_shadow'] ) {
			$class_name .= ' jeg_pb_boxed_shadow';
		}

		return $class_name;
	}

	public function is_compatible_widget() {
		$column = $this->compatible_column();

		if ( in_array( 4, $column ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $attr
	 *
	 * @return string
	 */
	public function get_module_column_class( $attr ) {
		if ( isset( $attr['column_width'] ) && $attr['column_width'] !== 'auto' ) {
			switch ( $attr['column_width'] ) {
				case 4 :
					$class_name = 'jeg_col_1o3';
					break;
				case 8 :
					$class_name = 'jeg_col_2o3';
					break;
				case 12 :
					$class_name = 'jeg_col_3o3';
					break;
				default :
					$class_name = 'jeg_col_3o3';
			}

			return $class_name;
		} else {
			return $this->manager->get_column_class();
		}
	}

	/**
	 * Call from VC to build Module
	 *
	 * @param $attr
	 * @param $content
	 *
	 * @return string
	 */
	public function build_module( $attr, $content = null ) {
		$this->content = $content;
		$this->generate_unique_id();
		$attr = $this->get_attribute( $attr );
		$this->load_vc_icon_elements( $attr );

		$column_class = $this->get_module_column_class( $attr );
		$output       = $this->render_module( $attr, $column_class );

		if ( ! $this->is_column_compatible() && ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) ) {
			$output = $output . $this->render_uncompatible();
		}

		do_action( $this->class_name );

		return $output;
	}

	/**
	 * @param $attr
	 */
	public function load_vc_icon_elements( $attr ) {
		if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
			$flag        = false;
			$params_icon = [
				'header_icon'          => isset( $attr['header_icon'] ) ? $attr['header_icon'] : '',
				'button_download_icon' => isset( $attr['button_download_icon'] ) ? $attr['button_download_icon'] : '',
				'button_icon'          => isset( $attr['button_icon'] ) ? $attr['button_icon'] : '',
				'icon'                 => isset( $attr['icon'] ) ? $attr['icon'] : '',
				'newsticker_icon'      => isset( $attr['newsticker_icon'] ) ? $attr['newsticker_icon'] : '',
			];

			foreach ( $params_icon as $key => $value ) {
				if ( ! $flag ) {
					if ( ! empty( $value ) && is_string( $value ) ) {
						$class = explode( ' ', $value );
						if ( 'fa' !== $class[0] ) {
							$flag = true;
						}
					}
				} else {
					break;
				}
			}
			if ( $flag ) {
				vc_icon_element_fonts_enqueue( 'fontawesome' );
			}
		}
	}

	/**
	 * Render if module is not compatible
	 *
	 * @return string
	 */
	public function render_uncompatible() {
		$compatible = $this->compatible_column();
		$column     = $this->manager->get_current_width();
		$text       = wp_kses( sprintf( __( 'This module works best for column <strong>%s</strong> ( current column width <strong>%s</strong> ). This warning will only show if you login as Admin.', 'jnews' ), implode( ', ', $compatible ), $column ), wp_kses_allowed_html() );
		$element    =
			"<div class=\"alert alert-error alert-compatibility\">
                <strong>" . jnews_return_translation( 'Optimal Column', 'jnews', 'optimal_column' ) . "</strong> {$text}
            </div>";

		return $element;
	}

	/**
	 * Check if column is not compatible
	 *
	 * @return bool
	 */
	public function is_column_compatible() {
		$compatible = $this->compatible_column();
		$column     = $this->manager->get_current_width();

		if ( $column === null ) {
			return true;
		}

		return in_array( $column, $compatible );
	}

	/**
	 * @return int
	 */
	public function get_post_id() {
		global $wp_query;
		if ( isset( $wp_query->post ) ) {
			return $wp_query->post->ID;
		}

		return null;
	}

	/**
	 * Generate Unique ID For Module
	 */
	public function generate_unique_id() {
		$this->unique_id = 'jnews_module_' . $this->get_post_id() . '_' . $this->manager->get_module_count() . '_' . uniqid();
		// need to increase module count
		$this->manager->increase_module_count();
	}

	/**
	 * Render Widget
	 *
	 * @param $instance
	 */
	public function render_widget( $instance ) {
		if ( $this->is_compatible_widget() ) {
			echo jnews_sanitize_output( $this->build_module( $instance ) );
		}
	}

	/**
	 * Render VC shortcode
	 *
	 * @param $attr
	 * @param $content
	 *
	 * @return mixed
	 */
	public function render_shortcode( $attr, $content ) {
		return $this->build_module( $attr, $content );
	}

	/**
	 * get thumbnail
	 *
	 * @param $post_id
	 * @param $size
	 *
	 * @return mixed|string
	 */
	public function get_thumbnail( $post_id, $size ) {
		if ( isset( $this->attribute['force_normal_image_load'] ) && 'true' === $this->attribute['force_normal_image_load'] ) {
			return ImageNormalLoad::getInstance()->image_thumbnail( $post_id, $size );
		} else {
			return apply_filters( 'jnews_image_thumbnail', $post_id, $size );
		}
	}

	/**
	 * Render primary category
	 *
	 * @param $post_id
	 *
	 * @return mixed|string
	 */
	public function get_primary_category( $post_id ) {
		$cat_id   = jnews_get_primary_category( $post_id );
		$category = '';

		if ( $cat_id ) {
			$category = get_category( $cat_id );
			if ( $category && ( isset( $category->slug ) && isset( $category->name ) ) ) {
				$class    = 'class="category-' . $category->slug . '"';
				$category = "<a href=\"" . get_category_link( $cat_id ) . "\" {$class}>" . $category->name . "</a>";
			}
		}

		return $category;
	}

	public function except_more() {
		return isset( $this->attribute['excerpt_ellipsis'] ) ? $this->attribute['excerpt_ellipsis'] : ' ...';
	}

	public function excerpt_length() {
		if ( isset( $this->attribute['excerpt_length'] ) ) {
			if ( isset( $this->attribute['excerpt_length']['size'] ) && ! empty( $this->attribute['excerpt_length']['size'] ) ) {
				return $this->attribute['excerpt_length']['size'];
			}

			return $this->attribute['excerpt_length'];
		} else {
			return 20;
		}
	}

	public function format_date( $post ) {
		if ( isset( $this->attribute['date_format'] ) ) {
			$date_format = $this->attribute['date_format'];
			if ( $date_format === 'ago' ) {
				return jnews_ago_time( human_time_diff( get_the_time( 'U', $post ), current_time( 'timestamp' ) ) );
			} else if ( $date_format === 'custom' ) {
				return jeg_get_post_date( $this->attribute['date_format_custom'], $post );
			} else if ( $date_format ) {
			    //Nina - start add hebrew date
				//return jeg_get_post_date( '', $post );
				return jeg_get_post_date('', $post) . " " . hebDate(jeg_get_post_date('d-m-Y', $post));
				//Nina - End

}
		}

		return jeg_get_post_date( '', $post );
	}
	//Nina - from old Jnews
    protected function get_excerpt_subtitle($post)
    {
        $meta = get_post_meta((int)$post->ID);
        $subtitle = (isset($meta['post_subtitle']) && count((array)$meta['post_subtitle'])> 0) ? $meta['post_subtitle'][0] : '';
        $excerpt = $subtitle;

        if(empty($excerpt))
        {
            $excerpt = $post->post_content;
        }

        $excerpt = wp_trim_words($excerpt, $this->excerpt_length(), $this->except_more());
        $excerpt = preg_replace( '/\[[^\]]+\]/', '', $excerpt );

        return apply_filters('jnews_module_excerpt', $excerpt, $post->ID, $this->excerpt_length(), $this->except_more());
    }

	protected function get_excerpt( $post ) {
		$excerpt = $post->post_excerpt;

		if ( empty( $excerpt ) ) {
			$excerpt = $post->post_content;
		}

		$excerpt = wp_trim_words( $excerpt, $this->excerpt_length(), $this->except_more() );
		$excerpt = preg_replace( '/\[[^\]]+\]/', '', $excerpt );

		return apply_filters( 'jnews_module_excerpt', $excerpt, $post->ID, $this->excerpt_length(), $this->except_more() );
	}

	protected function collect_post_id( $content ) {
		$post_ids = array();
		foreach ( $content['result'] as $result ) {
			$post_ids[] = $result->ID;
		}

		return $post_ids;
	}

	/**
	 * build query
	 *
	 * @param $attr
	 *
	 * @return array
	 */
	protected function build_query( $attr ) {
		if ( isset( $attr['unique_content'] ) && $attr['unique_content'] !== 'disable' ) {
			if ( ! empty( $attr['exclude_post'] ) ) {
				$exclude_post = explode( ',', $attr['exclude_post'] );
			} else {
				$exclude_post = array();
			}

			$exclude_post         = array_merge( $this->manager->get_unique_article( $attr['unique_content'] ), $exclude_post );
			$attr['exclude_post'] = implode( ',', $exclude_post );

			// we need to alter attribute here...
			$this->set_attribute( $attr );
		}

		$result = ModuleQuery::do_query( $attr );

		if ( isset( $attr['unique_content'] ) && $attr['unique_content'] !== 'disable' ) {
			$this->manager->add_unique_article( $attr['unique_content'], $this->collect_post_id( $result ) );
		}

		if ( isset( $result['result'] ) ) {
			foreach ( $result['result'] as $post ) {
				do_action( 'jnews_json_archive_push', $post->ID );
			}
		}

		return $result;
	}

	/**
	 * Post meta 1
	 *
	 * @param  object $post
	 * @param  boolean $avatar
	 *
	 * @return string
	 */
	public function post_meta_1( $post, $avatar = false ) {

//		$output = '';
//
//		if ( get_theme_mod( 'jnews_show_block_meta', true ) ) {
//			$comment    = jnews_get_comments_number( $post->ID );
//			$view_count = jnews_meta_views( $post->ID );
//
//			// author detail
//			$author        = $post->post_author;
//			$author_url    = get_author_posts_url( $author );
//			$author_name   = get_the_author_meta( 'display_name', $author );
//			$author_avatar = $avatar ?
//				'<div class="jeg_author_avatar">
//                    ' . get_avatar( get_the_author_meta( 'ID', $post->post_author ), 80, null, get_the_author_meta( 'display_name', $post->post_author ) ) . '
//                </div>' : '';
//
//
//			$trending = ( vp_metabox( 'jnews_single_post.trending_post', null, $post->ID ) ) ? "<div class=\"jeg_meta_trending\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-bolt\"></i></a></div>" : "";
//
//			if ( jnews_is_review( $post->ID ) ) {
//				$rating = jnews_generate_rating( $post->ID, 'jeg_landing_review' );
//
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_rating', true ) ? $rating : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_author', true ) ? ( jnews_check_coauthor_plus() ? "<div class=\"jeg_meta_author coauthor\">" . jnews_get_author_coauthor( $post->ID, false, 'by', 1 ) . "</div>" : "<div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>" ) : "";
//				$output .= "</div>";
//			} else {
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_author', true ) ? ( jnews_check_coauthor_plus() ? "<div class=\"jeg_meta_author coauthor\">" . jnews_get_author_coauthor( $post->ID, $avatar, 'by', 1 ) . "</div>" : "<div class=\"jeg_meta_author\">" . $author_avatar . "<span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>" ) : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_date', true ) ? "<div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-clock-o\"></i> " . $this->format_date( $post ) . "</a></div>" : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_comment', true ) ? "<div class=\"jeg_meta_comment\"><a href=\"" . jnews_get_respond_link( $post->ID ) . "\" ><i class=\"fa fa-comment-o\"></i> {$comment} </a></div>" : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_views', false ) ? "<div class=\"jeg_meta_views\"><a href=\"" . get_the_permalink( $post->ID ) . "\" ><i class=\"fa fa-eye\"></i> {$view_count} </a></div>" : "";
//				$output .= "</div>";
//			}
//		}
//
//		return apply_filters( 'jnews_module_post_meta_1', $output, $post, self::getInstance() );
        $output = '';
        $comment            = jnews_get_comments_number($post->ID);

        // author detail
        // Nadav edition
        $orderWriter = get_post_meta($post->ID, 'orderWriter', true);
        $authors = (!empty($orderWriter)) ? wp_get_object_terms($post->ID, 'writer', array('orderby'=>'term_order')) : wp_get_post_terms($post->ID, 'writer');
        if($authors && !empty($authors)) {

            //        $author             = $post->post_author;
            //        $author_url         = get_author_posts_url($author);
            $author_url         = get_term_link($authors[0]->term_id);
            //        $author_name        = get_the_author_meta('display_name', $author);
            require_once (get_parent_theme_file_path('MakorFunctionsHelper.php'));
            $author_name = getAuthorName($authors);
            //$hebrew_date1               = jeg_get_post_date( '', $post);
            $created = get_the_date(DATE_W3C,$post);
            $str = strtotime($created);
            $reg_date  = (date('d-m-Y', $str));

           // $output .= sprintf('<div class="jeg_meta_date"><a href="%s"><i class="fa fa-clock-o"></i> %s</a></div>', get_the_permalink($post),  $reg_date);

            if( jnews_is_review($post->ID) )
            {
                $rating = jnews_generate_rating($post->ID, 'jeg_landing_review');
                $output .=
                    "<div class=\"jeg_post_meta\">
                        {$rating}
                        <div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation('by', 'jnews', 'by') . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>
                        <div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\"><i class=\"fa fa-clock-o\"></i> " . $reg_date . "</a></div>                                
                    </div>";
            } else {
               // if(is_front_page()) {

                    $output .=
                        "<div class=\"jeg_post_meta\">
                        <div class=\"jeg_meta_author\"> <a href=\"{$author_url}\">{$author_name}</a></div>  
                        <div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\"><i class=\"fa fa-clock-o\"></i> " . $reg_date . "</a></div>                                
                    </div>";
              //  }
//                else {
//                    $response = wp_remote_get('https://open-api.spot.im/v1/messages-count?spot_id=' . SPOT_IM_ID . '&posts_ids=' . $post->ID, array());
//                    $response = json_decode(wp_remote_retrieve_body($response));
//                    $response_count = reset($response->messages_count);
//                    $output .=
//                        "<div class=\"jeg_post_meta\">
//                        <div class=\"jeg_meta_author\"> <a href=\"{$author_url}\">{$author_name}</a></div>
//                        <div class=\"spot-im-replies-count1\">   {$response_count}  </div>
//                    </div>";
//                }
            }

            return apply_filters('jnews_module_post_meta_1', $output, $post);
        }
	}

	/**
	 * Post Meta Type 2
	 *
	 * @param $post
	 *
	 * @return string
	 */
	public function post_meta_2( $post ) {

//		$output = '';
//
//		if ( get_theme_mod( 'jnews_show_block_meta', true ) ) {
//			$trending   = ( vp_metabox( 'jnews_single_post.trending_post', null, $post->ID ) ) ? "<div class=\"jeg_meta_trending\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-bolt\"></i></a></div>" : "";
//			$view_count = jnews_meta_views( $post->ID );
//
//			if ( jnews_is_review( $post->ID ) ) {
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_rating', true ) ? jnews_generate_rating( $post->ID, 'jeg_landing_review' ) : "";
//				$output .= "</div>";
//			} else {
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_date', true ) ? "<div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink( $post ) . "\" ><i class=\"fa fa-clock-o\"></i> " . $this->format_date( $post ) . "</a></div>" : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_views', false ) ? "<div class=\"jeg_meta_views\"><a href=\"" . get_the_permalink( $post->ID ) . "\" ><i class=\"fa fa-eye\"></i> {$view_count} </a></div>" : "";
//				$output .= "</div>";
//			}
//		}
//
//
//		return apply_filters( 'jnews_module_post_meta_2', $output, $post, self::getInstance() );
        $output = '';
        if( jnews_is_review($post->ID) )
        {
            $output = jnews_generate_rating($post->ID, 'jeg_landing_review');
        } else {

            // Nadav edition
//            $output .=
//                "<div class=\"jeg_post_meta\">
//                    <div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\" ><i class=\"fa fa-clock-o\"></i> " . $this->format_date($post) . "</a></div>
//                </div>";
            require_once (get_parent_theme_file_path('MakorFunctionsHelper.php'));
            $orderWriter = get_post_meta($post->ID, 'orderWriter', true);
            $authors = (!empty($orderWriter)) ? wp_get_object_terms($post->ID, 'writer', array('orderby'=>'term_order')) : wp_get_post_terms($post->ID, 'writer');
            $authorNames = getAuthorName($authors);
            $created = get_the_date(DATE_W3C,$post);
            $str = strtotime($created);
            $reg_date  = (date('d-m-Y', $str));

            $output .=
                "<div class=\"jeg_post_meta\">
                    <div class=\"jeg_meta_date\">
                        <a href=\"" . get_the_permalink($post) . "\" >{$authorNames}</a>
                         <div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\"><i class=\"fa fa-clock-o\"></i> " . $reg_date . "</a></div>
                    </div>                  
                </div>";
        }


        return apply_filters('jnews_module_post_meta_2', $output, $post);
	}

	/**
	 * Post meta type 3
	 *
	 * @param $post
	 *
	 * @return string
	 */
	public function post_meta_3( $post ) {

//		$output = '';
//
//		if ( get_theme_mod( 'jnews_show_block_meta', true ) ) {
//			$trending   = ( vp_metabox( 'jnews_single_post.trending_post', null, $post->ID ) ) ? "<div class=\"jeg_meta_trending\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-bolt\"></i></a></div>" : "";
//			$view_count = jnews_meta_views( $post->ID );
//
//			if ( jnews_is_review( $post->ID ) ) {
//				$rating = jnews_generate_rating( $post->ID, 'jeg_landing_review' );
//
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_rating', true ) ? $rating : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_date', true ) ? "<div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-clock-o\"></i> " . $this->format_date( $post ) . "</a></div>" : "";
//				$output .= "</div>";
//			} else {
//
//				// author detail
//				$author      = $post->post_author;
//				$author_url  = get_author_posts_url( $author );
//				$author_name = get_the_author_meta( 'display_name', $author );
//
//				$output .= "<div class=\"jeg_post_meta\">";
//				$output .= $trending;
//				$output .= get_theme_mod( 'jnews_show_block_meta_author', true ) ? ( jnews_check_coauthor_plus() ? "<div class=\"jeg_meta_author coauthor\">" . jnews_get_author_coauthor( $post->ID, false, 'by', 1 ) . "</div>" : "<div class=\"jeg_meta_author\"><span class=\"by\">" . jnews_return_translation( 'by', 'jnews', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>" ) : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_date', true ) ? "<div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink( $post ) . "\"><i class=\"fa fa-clock-o\"></i> " . $this->format_date( $post ) . "</a></div>" : "";
//				$output .= get_theme_mod( 'jnews_show_block_meta_views', false ) ? "<div class=\"jeg_meta_views\"><a href=\"" . get_the_permalink( $post->ID ) . "\" ><i class=\"fa fa-eye\"></i> {$view_count} </a></div>" : "";
//				$output .= "</div>";
//
//			}
//		}
//
//		return apply_filters( 'jnews_module_post_meta_3', $output, $post, self::getInstance() );
        $output = '';

        if(jnews_is_review($post->ID))
        {
            $rating = jnews_generate_rating($post->ID, 'jeg_landing_review');
           // if(is_front_page()){
               // $output =
                  //  "<div class=\"jeg_post_meta\">
                //        {$rating}
                 //   </div>";
           // }else{
                $output =
                    "<div class=\"jeg_post_meta\">
                        {$rating}
                        <div class=\"jeg_meta_date\"><a href=\"" . get_the_permalink($post) . "\"><i class=\"fa fa-clock-o\"></i> " . $this->format_date($post) . "</a></div>                    
                    </div>";
           // }

        } else {

            // author detail
            // Nadav edition
            $orderWriter = get_post_meta($post->ID, 'orderWriter', true);
            $authors = (!empty($orderWriter)) ? wp_get_object_terms($post->ID, 'writer', array('orderby'=>'term_order')) : wp_get_post_terms($post->ID, 'writer');

            $author_url         = empty($authors) ? '' : get_term_link($authors[0]->term_id);
            //        $author_name        = get_the_author_meta('display_name', $author);
            require_once (get_parent_theme_file_path('MakorFunctionsHelper.php'));
            $author_name = empty($authors) ? '' : getAuthorName($authors);

          //  if(is_front_page()){

//                $output .= sprintf('<div class="jeg_post_meta"><div class="jeg_meta_author">');
//                foreach ( $authors as $writer):
//                    $output .= sprintf('<a href="%s">%s%s</a>', get_term_link($writer->term_id), $writer->name, ($writer !== end($authors))?", ":null);
//                endforeach;
//                $output .= sprintf('</div>');
//                $output .= sprintf('</div>');

         //   }else{

                //$hebrew_date = esc_html(substr(get_the_date(null, $post), 0, -1). " ".get_the_date(get_option('time_format'), $post).")");

            $created = get_the_date(DATE_W3C,$post);
            $str = strtotime($created);
            $reg_date  = (date('d-m-Y', $str));

            $output .= sprintf('<div class="jeg_post_meta"><div class="jeg_meta_author"><span class="by">%s&nbsp;</span>', jnews_return_translation('by', 'jnews', 'by'));
                foreach ( $authors as $writer):
                    $output .= sprintf('<a href="%s">%s%s</a>', get_term_link($writer->term_id), $writer->name, ($writer !== end($authors))?", ":null);
                endforeach;
                $output .= sprintf('</div>');
                //Nina - hebrew date on categories
               // $output .= sprintf('<div class="jeg_meta_date"><a href="%s"><i class="fa fa-clock-o"></i> %s</a></div></div>', get_the_permalink($post),  $this->format_date($post));
                $output .= sprintf('<div class="jeg_meta_date"><a href="%s"><i class="fa fa-clock-o"></i> %s</a></div>', get_the_permalink($post),  $reg_date);
                $output .= sprintf('</div>');

          //  }


        }

        return apply_filters('jnews_module_post_meta_3', $output, $post);
	}
    public function post_meta_4( $post ) {


        $output = '';
        if( jnews_is_review($post->ID) )
        {
            $output = jnews_generate_rating($post->ID, 'jeg_landing_review');
        } else {

            require_once (get_parent_theme_file_path('MakorFunctionsHelper.php'));
            $orderWriter = get_post_meta($post->ID, 'orderWriter', true);
            $authors = (!empty($orderWriter)) ? wp_get_object_terms($post->ID, 'writer', array('orderby'=>'term_order')) : wp_get_post_terms($post->ID, 'writer');
            $authorNames = getAuthorName($authors);
            $collaboration = get_field( "collaboration", $post->ID );
            $beshituf = 'בשיתוף ';
            if ($collaboration) {

                $collaboration_final = $beshituf . " " . $collaboration;
            } else {

                $collaboration_final = $beshituf . " " . $authorNames;
            }
            $created = get_the_date(DATE_W3C,$post);
            $str = strtotime($created);
            $reg_date  = (date('d-m-Y', $str));

            $output .=
                "<div class=\"jeg_post_meta\">
                    <div class=\"jeg_meta_date\">
                        <a href=\"" . get_the_permalink($post) . "\" >{$collaboration_final}</a>
                    </div>                  
                </div>";
        }


        return apply_filters('jnews_module_post_meta_4', $output, $post);
    }

	/**
	 * Get attribute
	 *
	 * @param $attr
	 *
	 * @return array
	 */
	public function get_attribute( $attr ) {
		$this->attribute = wp_parse_args( $attr, $this->options );

		return $this->attribute;
	}

	public function set_attribute( $attr ) {
		$this->attribute = $attr;
	}

	/**
	 * Empty Content
	 *
	 * @return string
	 */
	public function empty_content() {
		$no_content = "<div class='jeg_empty_module'>" . jnews_return_translation( 'No Content Available', 'jnews', 'no_content_available' ) . "</div>";

		return apply_filters( 'jnews_module_no_content', $no_content );
	}

	public function render_module_ads( $ajax_class = '' ) {
		$attr     = $this->attribute;
		$addclass = isset( $attr['ads_class'] ) ? 'jnews_' . $attr['ads_class'] . '_ads' : '';

		return "<div class='jeg_ad jeg_ad_module {$addclass} {$ajax_class}'>" . $this->build_module_ads( $attr ) . "</div>";
	}

	public function build_module_ads( $attr, $echo = false ) {
		$type     = $attr['ads_type'];
		$addclass = isset( $attr['ads_class'] ) ? $attr['ads_class'] : '';
		$ads_html = '';

		if ( $type === 'image' ) {
			$ads_tab  = $attr['ads_image_new_tab'] ? '_blank' : '_self';
			$ads_link = $attr['ads_image_link'];
			$ads_text = $attr['ads_image_alt'];

			$ads_images = array(
				'ads_image'        => $attr['ads_image'],
				'ads_image_tablet' => $attr['ads_image_tablet'],
				'ads_image_phone'  => $attr['ads_image_phone']
			);

			foreach ( $ads_images as $key => $ads_image ) {
				if ( filter_var( $ads_image, FILTER_VALIDATE_URL ) === false ) {
					if ( isset( $ads_image['url'] ) && ! empty( $ads_image['url'] ) ) {
						$ads_images[ $key ] = $ads_image['url'];
					} else {
						$images = wp_get_attachment_image_src( $ads_image, 'full' );
						$ads_images[ $key ] = isset( $images[0] ) ? $images[0] : '';
					}
				}
			}

			foreach ( $ads_images as $key => $ads_image ) {
				if ( ! empty( $ads_image ) ) {
					if ( $attr['ads_image_normal_load'] ) {
						$ads_html .= 
							"<a href='{$ads_link}' target='{$ads_tab}' class='adlink {$key} {$addclass}'>
								<img src='{$ads_image}' alt='{$ads_text}' data-pin-no-hover=\"true\">
							</a>";
					} else {
						$ads_html .= 
							"<a href='{$ads_link}' target='{$ads_tab}' class='adlink {$key} {$addclass}'>
								<img src='" . apply_filters( 'jnews_empty_image', '' ) . "' class='lazyload' data-src='{$ads_image}' alt='{$ads_text}' data-pin-no-hover=\"true\">
							</a>";
					}
				}
			}
		}

		if ( $type === 'shortcode' ) {
			$shortcode = $attr['shortcode'];
			$ads_html  = "<div class='{$addclass}'>" . do_shortcode( $shortcode ) . "</div>";
		}

		if ( $type === 'code' ) {
			$attr['content'] = isset( $attr['code'] ) ? $attr['code'] : $attr['content'];
			$code            = empty( $this->content ) ? $attr['content'] : $this->content;
			$ads_html        = "<div class='{$addclass}'>" . $code . "</div>";
		}

		if ( $type === 'googleads' ) {
			$publisherid = $attr['google_publisher_id'];
			$slotid      = $attr['google_slot_id'];

			if ( ! empty( $publisherid ) && ! empty( $slotid ) ) {
				$column = $this->manager->get_current_width();

				if ( $column >= 8 ) {
					$desktopsize_ad = array( '728', '90' );
					$tabsize_ad     = array( '468', '60' );
					$phonesize_ad   = array( '320', '50' );
				} else {
					$desktopsize_ad = array( '300', '250' );
					$tabsize_ad     = array( '300', '250' );
					$phonesize_ad   = array( '300', '250' );
				}

				$desktopsize = $attr['google_desktop'];
				$tabsize     = $attr['google_tab'];
				$phonesize   = $attr['google_phone'];

				if ( $desktopsize !== 'auto' ) {
					$desktopsize_ad = explode( 'x', $desktopsize );
				}
				if ( $tabsize !== 'auto' ) {
					$tabsize_ad = explode( 'x', $tabsize );
				}
				if ( $phonesize !== 'auto' ) {
					$phonesize_ad = explode( 'x', $phonesize );
				}

				$randomstring = jeg_generate_random_string();
				$ad_style     = '';

				if ( $desktopsize !== 'hide' && is_array( $desktopsize_ad ) && isset( $desktopsize_ad['0'] ) && isset( $desktopsize_ad['1'] ) ) {
					$ad_style .= ".adsslot_{$randomstring}{ width:{$desktopsize_ad[0]}px !important; height:{$desktopsize_ad[1]}px !important; }\n";
				}
				if ( $tabsize !== 'hide' && is_array( $tabsize_ad ) && isset( $tabsize_ad['0'] ) && isset( $tabsize_ad['1'] ) ) {
					$ad_style .= "@media (max-width:1199px) { .adsslot_{$randomstring}{ width:{$tabsize_ad[0]}px !important; height:{$tabsize_ad[1]}px !important; } }\n";
				}
				if ( $phonesize !== 'hide' && is_array( $phonesize_ad ) && isset( $phonesize_ad['0'] ) && isset( $phonesize_ad['1'] ) ) {
					$ad_style .= "@media (max-width:767px) { .adsslot_{$randomstring}{ width:{$phonesize_ad[0]}px !important; height:{$phonesize_ad[1]}px !important; } }\n";
				}

				$ads_html .=
					"<div class=\"{$addclass}\">
                        <style type='text/css' scoped>
                            {$ad_style}
                        </style>
                        <ins class=\"adsbygoogle adsslot_{$randomstring}\" style=\"display:inline-block;\" data-ad-client=\"{$publisherid}\" data-ad-slot=\"{$slotid}\"></ins>
                        <script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
                        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>";
			}
		}

		$bottom_text = $attr['ads_bottom_text'];

		if ( $bottom_text ) {
			$ads_text_html = jnews_return_translation( 'ADVERTISEMENT', 'jnews', 'advertisement' );
			$ads_html      = $ads_html . "<div class='ads-text'>{$ads_text_html}</div>";
		}

		$ads_html = "<div class='ads-wrapper'>{$ads_html}</div>";

		if ( $echo ) {
			echo jnews_sanitize_by_pass( $ads_html );
		} else {
			return $ads_html;
		}
	}

	protected function random_ads_position( $count ) {
		$position = - 1;
		$attr     = $this->attribute;

		if ( isset( $attr['ads_type'] ) && $attr['ads_type'] !== 'disable' ) {
			if ( $attr['ads_random'] ) {
				$position = rand( $attr['ads_position'], ( $count - 2 ) );
			} else {
				$position = $attr['ads_position'];
			}
		}

		return (int) $position;
	}

	public function element_id( $attr ) {
		if ( isset( $attr['el_id'] ) && ! empty( $attr['el_id'] ) ) {
			return "id='{$attr['el_id']}'";
		}

		return null;
	}

	public function _content_template() {
	}

	abstract public function render_module( $attr, $column_class );
}
