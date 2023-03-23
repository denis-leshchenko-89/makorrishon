<?php

/**
 * @param $post
 * @return mixed|void
 */
function makorPostGallery($post) {

    $size =  'jnews-1140x570';
    $dimension = jnews_get_image_dimension_by_name($size);

    $output = '';
    $images = get_post_meta($post->ID, '_format_gallery_images', true);

    if($images)
    {
        $output = "<div class=\"jeg_featured thumbnail-container size-{$dimension}\">";
        $output .= "<div class=\"featured_gallery jeg_owlslider owl-carousel\">";

        $popup = get_theme_mod('jnews_single_popup_script', 'magnific');

        foreach ( $images as $image_id )
        {
            $image = wp_get_attachment_image_src($image_id, 'full');

            $output .= ( $popup !== 'disable' ) ? "<a href=\"{$image[0]}\">" : "";
            $output .= apply_filters('jnews_single_image_lazy_owl', $image_id, $size);
            $output .= ( $popup !== 'disable' ) ? "</a>" : "";
        }

        $output .= "</div>";
        $output .= "</div>";

    }

    return apply_filters('jnews_featured_gallery', $output, $post->ID);
}

/**
 * Takes an array of author object (aka taxonomy writer) and return their names
 * @param $authors - should be array
 * @return string
 */
function getAuthorName($authors) {
    if(empty($authors)) {
        return '';
    }

    // for multiple names
    if(count($authors) > 1) {
        $str = '';
        foreach ($authors as $index => $author) {
            $str .= $author->name;
            if($index != (count($authors)-1)) {
                $str .= ', ';
            }
        }
        return $str;
    }

    return $authors[0]->name;
}

function get_post_image_metadata( $post = null ) {
    $post = get_post( $post );
    if ( ! $post ) {
        return false;
    }

    $post_image_meta = null;
    $post_image_id   = false;

    if ( has_post_thumbnail( $post->ID ) ) {
        $post_image_id = get_post_thumbnail_id( $post->ID );
    } elseif ( ( 'attachment' === $post->post_type ) && wp_attachment_is( 'image', $post ) ) {
        $post_image_id = $post->ID;
    } else {
        $attached_image_ids = get_posts(
            [
                'post_parent'      => $post->ID,
                'post_type'        => 'attachment',
                'post_mime_type'   => 'image',
                'posts_per_page'   => 1,
                'orderby'          => 'menu_order',
                'order'            => 'ASC',
                'fields'           => 'ids',
                'suppress_filters' => false,
            ]
        );

        if ( ! empty( $attached_image_ids ) ) {
            $post_image_id = array_shift( $attached_image_ids );
        }
    }

    if ( ! $post_image_id ) {
        return false;
    }

    $post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

    if ( is_array( $post_image_src ) ) {
        $post_image_meta = [
            '@type'  => 'ImageObject',
            'url'    => $post_image_src[0],
            'width'  => $post_image_src[1],
            'height' => $post_image_src[2],
        ];
    }

    return $post_image_meta;
}