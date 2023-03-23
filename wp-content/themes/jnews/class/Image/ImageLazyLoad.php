<?php
/**
 * @author : Jegtheme
 */

namespace JNews\Image;

/**
 * Class JNews Image
 */
Class ImageLazyLoad implements ImageInterface {

	/**
	 * @var ImageLazyLoad
	 */
	private static $instance;

	private $expand_range = 700;

	/**
	 * @return ImageLazyLoad
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function single_image_unwrap( $id, $size ) {
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10, 2 );

		$image_size = wp_get_attachment_image_src( $id, $size );
		$image      = get_post( $id );
		$percentage = round( $image_size[2] / $image_size[1] * 100, 3 );

		$thumbnail = "<div class=\"thumbnail-container animate-lazy\" style=\"padding-bottom:" . $percentage . "%\">";
		$thumbnail .= wp_get_attachment_image( $id, $size );
		$thumbnail .= "</div>";

		if ( ! empty( $image->post_excerpt ) ) {
			$thumbnail .= "<p class=\"wp-caption-text\">" . $image->post_excerpt . "</p>";
		}

		jnews_remove_filters( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10 );

		return $thumbnail;
	}

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function image_thumbnail_unwrap( $id, $size ) {
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10, 2 );

		$post_thumbnail_id = get_post_thumbnail_id( $id );
		$image_size        = wp_get_attachment_image_src( $post_thumbnail_id, $size );
		$image             = get_post( $post_thumbnail_id );
		$percentage        = ! empty( $image_size[1] ) ? round( $image_size[2] / $image_size[1] * 100, 3 ) : '';

		$thumbnail = "<div class=\"thumbnail-container animate-lazy\" style=\"padding-bottom:" . $percentage . "%\">";
		$thumbnail .= get_the_post_thumbnail( $id, $size );
		$thumbnail .= "</div>";

		if ( ! empty( $image->post_excerpt ) ) {
			$thumbnail .= "<p class=\"wp-caption-text\">" . $image->post_excerpt . "</p>";
		}

		jnews_remove_filters( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10 );

		return $thumbnail;
	}

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function image_thumbnail( $id, $size ) {
		$image_size = Image::getInstance()->get_image_size( $size );

		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10, 2 );

		$additional_class = '';
		if ( ! has_post_thumbnail( $id ) ) {
			$additional_class = 'no_thumbnail';
		}

		$thumbnail = "<div class=\"thumbnail-container animate-lazy {$additional_class} size-{$image_size['dimension']} \">";
//Nina - add opinion from old jnews - To Do
        $ajaxFlag = $this->checkIfAjaxRequest();
        // this for insert front page image from custom field when only on front page
        if (is_page('307829') || is_page('301025')) {

            $thumbnailObj = get_field('image_beit_midrash', $id);
            $thumbnail .= $thumbnailObj ? wp_get_attachment_image($thumbnailObj['id'], $size) : get_the_post_thumbnail($id, $size);

            $thumbnail .= "</div>";

            remove_filter('wp_get_attachment_image_attributes', array($this, 'lazy_load_image'), 10);

            return $thumbnail;
        }
        else {
            if (is_page() || is_category() || $ajaxFlag) {
                $type = get_post_type($id);

                // for opinions
                if ($type === 'opinion') {
                    $thumbnail = "<div class=\"thumbnail-container animate-lazy makor-opinion-hide-background size-{$image_size['dimension']} \">";
                    $showThumbnail = get_field('show_thumbnail', $id);
                    $categories = wp_get_post_categories($id,'fields=ids');
                    $isRishonot = in_array(25203, $categories);

                    if (!$showThumbnail) {
                        $author = wp_get_post_terms($id, 'writer')[0];
                        $thumbnailObj = get_field('image', 'writer_' . $author->term_id);
                        $thumbnail .= isset($thumbnailObj) ? wp_get_attachment_image($thumbnailObj['id'], $size, false, array('title' => trim(strip_tags(get_post_meta($thumbnailObj['id'], '_wp_attachment_image_alt', true))))) : '';

                    } else if (is_front_page() && $isRishonot) {
                        $author = wp_get_post_terms($id, 'writer')[0];
                        $thumbnailObj = get_field('image', 'writer_' . $author->term_id);
                        $thumbnail .= isset($thumbnailObj) ? wp_get_attachment_image($thumbnailObj['id'], $size, false, array('title' => trim(strip_tags(get_post_meta($thumbnailObj['id'], '_wp_attachment_image_alt', true))))) : '';
                    }
                    else {
                        $thumbnail .= get_the_post_thumbnail($id, $size);

                    }
//                    if ($showThumbnail) {
//                        $thumbnail .= get_the_post_thumbnail($id, $size);
//                        file_put_contents('./log_lazy_.log', 'in opinion'.PHP_EOL, FILE_APPEND);
//                    } else {
//                        $author = wp_get_post_terms($id, 'writer')[0];
//                        $thumbnailObj = get_field('image', 'writer_' . $author->term_id);
//                        $thumbnail .= isset($thumbnailObj) ? wp_get_attachment_image($thumbnailObj['id'], $size, false, array('title' => trim(strip_tags(get_post_meta($thumbnailObj['id'], '_wp_attachment_image_alt', true))))) : '';
//                    }
                    // regular post
                } else {
                    $thumbnailObj = get_field('image_front_page', $id);
                    $thumbnail .= $thumbnailObj ? wp_get_attachment_image($thumbnailObj['id'], $size) : get_the_post_thumbnail($id, $size);
                }
            } else {
                $thumbnail .= get_the_post_thumbnail($id, $size);
            }
            // -------------
            $thumbnail .= "</div>";

            remove_filter('wp_get_attachment_image_attributes', array($this, 'lazy_load_image'), 10);

            return $thumbnail;
        }
//		$thumbnail .= get_the_post_thumbnail( $id, $size );
//		$thumbnail .= "</div>";
//
//		jnews_remove_filters( 'wp_get_attachment_image_attributes', array( $this, 'lazy_load_image' ), 10 );
//
//		return $thumbnail;
	}
    /**
     * Check if the request is of jnews ajax origin, the one which called whenever "load more" is picked
     * @return bool
     */
    public function checkIfAjaxRequest() {
        if($_REQUEST && count($_REQUEST) > 0 && isset($_REQUEST['ajax-request']) && isset($_REQUEST['data']) && isset($_REQUEST['data']['current_page'])) {
            return true;
        }
    }

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function owl_single_image( $id, $size ) {
		$image_size = Image::getInstance()->get_image_size( $size );

		$thumbnail = "<div class=\"thumbnail-container size-{$image_size['dimension']} \">";
		$thumbnail .= wp_get_attachment_image( $id, $size );
		$thumbnail .= "</div>";

		return $thumbnail;
	}

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function owl_lazy_single_image( $id, $size ) {
		$image_size = Image::getInstance()->get_image_size( $size );

		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'owl_lazy_attr' ), 10, 2 );

		$thumbnail = "<div class=\"thumbnail-container size-{$image_size['dimension']} \">";
		$thumbnail .= wp_get_attachment_image( $id, $size );
		$thumbnail .= "</div>";

		jnews_remove_filters( 'wp_get_attachment_image_attributes', array( $this, 'owl_lazy_attr' ), 10 );

		return $thumbnail;
	}

	/**
	 * @param $id
	 * @param $size
	 *
	 * @return string
	 */
	public function owl_lazy_image( $id, $size ) {
		$image_size = Image::getInstance()->get_image_size( $size );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'owl_lazy_attr' ), 10, 2 );

		$thumbnail = "<div class=\"thumbnail-container size-{$image_size['dimension']} \">";
		$thumbnail .= get_the_post_thumbnail( $id, $size );
		$thumbnail .= "</div>";

		jnews_remove_filters( 'wp_get_attachment_image_attributes', array( $this, 'owl_lazy_attr' ), 10 );

		return $thumbnail;
	}

	/**
	 * @param $img_src
	 * @param $img_title
	 * @param $img_size
	 *
	 * @return string
	 */
	public function single_image( $img_src, $img_title, $img_size ) {
		$img_tag = "<img class='lazyload' src='" . apply_filters( 'jnews_empty_image', '' ) . "' data-expand='" . $this->expand_range . "' alt='{$img_title}' data-src='{$img_src}' title='{$img_title}'>";

		if ( $img_size ) {
			return "<div class='thumbnail-container animate-lazy size-{$img_size}'>{$img_tag}</div>";
		} else {
			return $img_tag;
		}
	}

	/**
	 * @param $attr
	 * @param $image
	 *
	 * @return mixed
	 */
	public function lazy_load_image( $attr, $image ) {
		$attr['class']       = $attr['class'] . ' lazyload';
		$attr['data-src']    = $attr['src'];
	    $attr['data-srcset'] = isset( $attr['srcset'] ) ? $attr['srcset'] : '';
		$attr['data-sizes']  = 'auto';
		$attr['data-expand'] = $this->expand_range;
		$attr['src']         = apply_filters( 'jnews_empty_image', '' );

		if ( empty( $attr['alt'] ) && ! empty( $image->post_parent ) ) {
			$attr['alt'] = wp_strip_all_tags( get_the_title( $image->post_parent ) );
		}

		// Need to fix issues on ajax request image not showing
		if ( wp_doing_ajax() ) {
			$attr['data-animate'] = 0;
		}

		if ( get_theme_mod( 'jnews_disable_image_srcset', false ) ) {
			$attr['class'] = 'lazyload';
			unset( $attr['data-srcset'] );
			unset( $attr['sizes'] );
		}

		unset( $attr['srcset'] );

		return $attr;
	}

	/**
	 * @param $attr
	 * @param $image
	 *
	 * @return mixed
	 */
	public function owl_lazy_attr( $attr, $image ) {
		$attr['class']    = $attr['class'] . ' owl-lazy';
		$attr['data-src'] = $attr['src'];
		$attr['src']      = apply_filters( 'jnews_empty_image', '' );

		if ( empty( $attr['alt'] ) && ! empty( $image->post_parent ) ) {
			$attr['alt'] = wp_strip_all_tags( get_the_title( $image->post_parent ) );
		}

		if ( get_theme_mod( 'jnews_disable_image_srcset', false ) ) {
			$attr['class'] = 'owl-lazy';
			unset( $attr['sizes'] );
		}

		unset( $attr['srcset'] );

		return $attr;
	}
}
