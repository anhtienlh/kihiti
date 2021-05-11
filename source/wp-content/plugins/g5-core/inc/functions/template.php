<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5core_get_image_sizes()
{
    $data = G5CORE()->cache()->get('image_sizes');
    if (!is_null($data)) {
        return $data;
    }
    $data =  apply_filters('g5core_image_sizes', array());
    G5CORE()->cache()->set('image_sizes',$data);
    return $data;
}

function g5core_get_image_dimension($image_size = 'thumbnail'){
    $cache_key = "image_dimension_{$image_size}";
    $data = G5CORE()->cache()->get($cache_key);
    if (!is_null($data)) {
        return $data;
    }

    global $_wp_additional_image_sizes;
    $width = '';
    $height = '';

    $image_sizes = g5core_get_image_sizes();
    if (array_key_exists($image_size,$image_sizes)) {
        $image_size = $image_sizes[$image_size];
    }


    if (preg_match('/x/',$image_size)) {
        $image_size = preg_split('/x/', $image_size);
        $width = $image_size[0];
        $height = $image_size[1];
    } elseif (in_array($image_size,array('thumbnail', 'medium','large'))) {
        $width = intval(get_option( $image_size . '_size_w' ));
        $height = intval(get_option( $image_size . '_size_h' ));
    } elseif (isset($_wp_additional_image_sizes[$image_size])) {
        $width = intval($_wp_additional_image_sizes[ $image_size ]['width']);
        $height = intval($_wp_additional_image_sizes[ $image_size ]['height']) ;
    }

    if ($width !== '' && $height !== '') {
        $data = array(
            'width' => $width,
            'height' => $height
        );
    } else {
        $data = false;
    }
    G5CORE()->cache()->set($cache_key,$data);
    return $data;
}

function g5core_get_metro_image_size($image_size_base = '300x300', $layout_ratio = '1x1', $columns_gutter = 0) {
    $image_width = 0;
    $image_height = 0;
    $layout_ratio_width = 1;
    $layout_ratio_height = 1;
    $columns_gutter = intval($columns_gutter);
    $width = 0;
    $height = 0;

    $image_size_base_dimension =  g5core_get_image_dimension($image_size_base);
    if ($image_size_base_dimension) {
        $image_width = $image_size_base_dimension['width'];
        $image_height = $image_size_base_dimension['height'];
    }

    if (preg_match('/x/',$layout_ratio)) {
        $layout_ratio = preg_split('/x/', $layout_ratio);
        $layout_ratio_width = isset($layout_ratio[0]) ? floatval($layout_ratio[0]) : 0;
        $layout_ratio_height = isset($layout_ratio[1]) ? floatval($layout_ratio[1]) : 0;
    }

    if (($image_width > 0) && ($image_height > 0)) {
        $width = ($layout_ratio_width - 1) * $columns_gutter + $image_width * $layout_ratio_width;
        $height = ($layout_ratio_height - 1) * $columns_gutter + $image_height * $layout_ratio_height;
    }

    if (($width > 0) && ($height > 0)) {
        return "{$width}x{$height}";
    }

    return $image_size_base;
}

function g5core_get_metro_image_ratio($image_ratio_base = '1x1', $layout_ratio = '1x1') {
    $layout_ratio_width = 1;
    $layout_ratio_height = 1;

    $image_ratio_base_width = 1;
    $image_ratio_base_height = 1;


    if (preg_match('/x/',$layout_ratio)) {
        $layout_ratio = preg_split('/x/', $layout_ratio);
        $layout_ratio_width = isset($layout_ratio[0]) ? floatval($layout_ratio[0]) : 0;
        $layout_ratio_height = isset($layout_ratio[1]) ? floatval($layout_ratio[1]) : 0;
    }

    if (preg_match('/x/',$image_ratio_base)) {
        $image_ratio_base = preg_split('/x/', $image_ratio_base);
        $image_ratio_base_width = isset($image_ratio_base[0]) ? floatval($image_ratio_base[0]) : 0;
        $image_ratio_base_height = isset($image_ratio_base[1]) ? floatval($image_ratio_base[1]) : 0;
    }

    if (($layout_ratio_width > 0)
        && ($layout_ratio_height > 0)
        && ($image_ratio_base_width > 0)
        && ($image_ratio_base_height > 0)) {
        $image_ratio_width = $image_ratio_base_width * $layout_ratio_width;
        $image_ratio_height = $image_ratio_base_height * $layout_ratio_height;
        return "{$image_ratio_width}x{$image_ratio_height}";
    }
    return $image_ratio_base;
}


function g5core_get_the_title( $post = 0 ) {
    $data = G5CORE()->cache()->get_cache_listing( 'title' );
    if ( ! is_null( $data ) ) {
        return $data;
    }
    $data = get_the_title( $post );
    G5CORE()->cache()->set_cache_listing( 'title', $data );
    return $data;
}

function g5core_the_title_attribute( $args = '' ) {
    $args = wp_parse_args( $args, array(
        'before' => '',
        'after' => '',
        'echo' => true
    ) );

    $title = g5core_get_the_title();

    if ( strlen( $title ) == 0 ) {
        return;
    }

    $title = $args['before'] . $title . $args['after'];

    if ( $args['echo'] ) {
        echo esc_attr( strip_tags( $title ) );
    } else {
        return esc_attr( strip_tags( $title ) );
    }
}

function g5core_get_image_data( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'image_id'           => '',
        'image_size'         => 'thumbnail',
        'animated_thumbnail' => true
    ) );
    if ( empty( $args['image_id'] ) ) {
        return false;
    }
    $cache_key = "image_{$args['image_id']}_{$args['image_size']}";
    $data      = G5CORE()->cache()->get( $cache_key );
    if ( ! is_null( $data ) ) {
        return $data;
    }

    $output      = array(
        'id'              => $args['image_id'],
        'url'             => '',
        'width'           => '',
        'height'          => '',
        'alt'             => get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true ),
        'title'           => g5core_the_title_attribute( array( 'echo' => false ) ),
        'caption'         => '',
        'description'     => '',
        'skip_smart_lazy' => false
    );


    if (empty($output['alt'])) {
        $output['alt'] = g5core_the_title_attribute( array( 'echo' => false ) );
    }

    $image_sizes = g5core_get_image_sizes();
    if ( array_key_exists( $args['image_size'], $image_sizes ) ) {
        $size             = preg_split( '/x/', $image_sizes[ $args['image_size'] ] );
        $image_width      = isset( $size[0] ) ? intval( $size[0] ) : 0;
        $image_height     = isset( $size[1] ) ? intval( $size[1] ) : 0;
        $img              = G5CORE()->image_resize()->resize( array(
            'image_id' => $args['image_id'],
            'width'    => $image_width,
            'height'   => $image_height
        ) );
        $output['url']    = $img['url'];
        $output['width']  = $img['width'];
        $output['height'] = $img['height'];


    } elseif ( preg_match( '/x/', $args['image_size'] ) ) {
        $size         = preg_split( '/x/', $args['image_size'] );
        $image_width  = isset( $size[0] ) ? intval( $size[0] ) : 0;
        $image_height = isset( $size[1] ) ? intval( $size[1] ) : 0;

        $img = G5CORE()->image_resize()->resize( array(
            'image_id' => $args['image_id'],
            'width'    => $image_width,
            'height'   => $image_height
        ) );

        $output['url']    = $img['url'];
        $output['width']  = $img['width'];
        $output['height'] = $img['height'];
    } else {
        $img = wp_get_attachment_image_src( $args['image_id'], $args['image_size'] );
        if ( $img ) {
            $output['url']    = $img[0];
            $output['width']  = $img[1];
            $output['height'] = $img[2];
        }
    }

    if ( ! empty( $output['url'] ) && $args['animated_thumbnail'] ) {
        $file_type = wp_check_filetype( $output['url'] );
        if ( $file_type['ext'] === 'gif' ) {
            $img = wp_get_attachment_image_src( $args['image_id'], 'full' );
            if ( $img ) {
                $output['url']             = $img[0];
                $output['width']           = $img[1];
                $output['height']          = $img[2];
                $output['skip_smart_lazy'] = true;
            }
        }
    }

    $img_post = get_post( $args['image_id'] );

    if ( ! is_null( $img_post ) ) {
        $thumbnail['alt']         = get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true );
        $thumbnail['caption']     = $img_post->post_excerpt;
        $thumbnail['description'] = $img_post->post_content;
    }

    G5CORE()->cache()->set( $cache_key, $output );

    return $output;

}

function g5core_get_thumbnail_data( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'image_size'         => 'thumbnail',
        'animated_thumbnail' => true,
        'placeholder'        => '',
        'post' => null
    ) );


    $thumbnail_id = get_post_thumbnail_id($args['post']);
    $cache_id = "thumbnail_{$thumbnail_id}_{$args['image_size']}";
    if (!empty($thumbnail_id)) {
        $data     = G5CORE()->cache()->get_cache_listing( $cache_id );
        if ( ! is_null( $data ) ) {
            return $data;
        }
    }


    $thumbnail = array(
        'id'              => '',
        'url'             => '',
        'width'           => '',
        'height'          => '',
        'alt'             => '',
        'caption'         => '',
        'description'     => '',
        'title'           => g5core_the_title_attribute( array( 'echo' => false ) ),
        'skip_smart_lazy' => false
    );



    if ( ! empty( $thumbnail_id ) ) {
        $thumbnail = g5core_get_image_data( array(
            'image_id'           => $thumbnail_id,
            'image_size'         => $args['image_size'],
            'animated_thumbnail' => $args['animated_thumbnail']
        ) );

        if (empty($thumbnail['alt'])) {
            $thumbnail['alt'] = g5core_the_title_attribute( array( 'echo' => false ) );
        }

        G5CORE()->cache()->set_cache_listing( $cache_id, $thumbnail );
        return $thumbnail;
    }
    $first_image_as_post_thumbnail = G5CORE()->options()->get_option('first_image_as_post_thumbnail');
    if ( $first_image_as_post_thumbnail === 'on' ) {
        global $post;
        if ( isset( $post->post_content ) ) {
            if ( preg_match( "'<\s*img\s.*?src\s*=\s*
						([\"\'])?
						(?(1) (.*?)\\1 | ([^\s\>]+))'isx", $post->post_content, $matched ) ) {

                $thumbnail['url'] = esc_url( $matched[2] );
                G5CORE()->cache()->set_cache_listing( $cache_id, $thumbnail );
                return $thumbnail;
            }
        }

    }
    $placeholder = $args['placeholder'] !== '' ? $args['placeholder'] : G5CORE()->options()->get_option('default_thumbnail_placeholder_enable');
    if ( $placeholder === 'on' ) {
        $placeholder_img    = G5CORE()->options()->get_option('default_thumbnail_image');
        $placeholder_img_id = isset( $placeholder_img['id'] ) ? $placeholder_img['id'] : '';
        if ( ! empty( $placeholder_img_id ) ) {
            $thumbnail = g5core_get_image_data( array(
                'image_id'           => $placeholder_img_id,
                'image_size'         => $args['image_size'],
                'animated_thumbnail' => $args['animated_thumbnail']
            ) );
            G5CORE()->cache()->set_cache_listing( $cache_id, $thumbnail );

            return $thumbnail;
        }
        $thumbnail['url'] = G5CORE()->plugin_url( 'assets/images/placeholder.png' );
        if (preg_match('/x/',$args['image_size'])) {
            $image_size = preg_split('/x/', $args['image_size']);
            $image_width = $image_size[0];
            $image_height = $image_size[1];
            $thumbnail['width'] = $image_width;
            $thumbnail['height'] = $image_height;
        }

    }


    if (!empty($thumbnail_id)) {
        G5CORE()->cache()->set_cache_listing( $cache_id, $thumbnail );
    }
    return $thumbnail;
}

function g5core_render_thumbnail_markup( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'image_size'         => 'thumbnail',
        'image_ratio' => '',
        'image_id'           => '',
        'animated_thumbnail' => true,
        'display_permalink'  => true,
        'image_mode'         => '',
        'post' => null,
        'placeholder' => '',
        'gallery_id' => ''
    ) );

    if ( ! empty( $args['image_id'] ) ) {
        $image_data = g5core_get_image_data( array(
            'image_id'           => $args['image_id'],
            'image_size'         => $args['image_size'],
            'animated_thumbnail' => $args['animated_thumbnail']
        ) );
    } else {
        $image_data = g5core_get_thumbnail_data( array(
            'image_size'         => $args['image_size'],
            'animated_thumbnail' => $args['animated_thumbnail'],
            'post' => $args['post'],
            'placeholder' => $args['placeholder']
        ) );


    }




    if ( ! $image_data || empty( $image_data['url'] ) ) {
        return '';
    }

    ob_start();
    if ( $args['image_mode'] !== 'image' ) {
        $attributes = array();


        if ( ! empty( $image_data['title'] ) && $args['display_permalink'] ) {
            $attributes[] = sprintf( 'title="%s"', esc_attr( $image_data['title'] ) );
        }

        $classes = array(
            'g5core__entry-thumbnail',
            'g5core__embed-responsive',
        );
        if (empty($args['image_ratio'])) {
            if (preg_match('/x/',$args['image_size'])) {
                if (($image_data['width']  > 0) && ($image_data['height']  > 0)) {
                    $ratio = ($image_data['height'] /$image_data['width'] ) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$image_data['width']}x{$image_data['height']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css,"g5core__image-size-{$image_data['width']}x{$image_data['height']}");
                }

                $classes[] = "g5core__image-size-{$image_data['width']}x{$image_data['height']}";
            } else {
                $classes[] = "g5core__image-size-{$args['image_size']}";
            }

        } else {
            $classes[] = "g5core__image-size-{$args['image_ratio']}";

            if (!in_array($args['image_ratio'],array('1x1','3x4','4x3','16x9','9x16'))) {

                $image_ratio_sizes = preg_split('/x/', $args['image_ratio']);
                $image_ratio_width = isset($image_ratio_sizes[0]) ? intval($image_ratio_sizes[0]) : 0;
                $image_ratio_height = isset($image_ratio_sizes[1]) ? intval($image_ratio_sizes[1]) : 0;

                if (($image_ratio_width > 0) && ($image_ratio_height > 0)) {
                    $ratio = ($image_ratio_height/$image_ratio_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$args['image_ratio']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css,"g5core__image-size-{$args['image_ratio']}");
                }
            }
        }



        $attributes[] = sprintf( 'style="background-image: url(%s);"', esc_url( $image_data['url'] ) );

        $attributes[] = sprintf( 'class="%s"', join( ' ', $classes ) );

        if ( $args['display_permalink'] ) {
            ?>
            <a <?php echo join( ' ', $attributes ) ?> href="<?php g5core_the_permalink() ?>">
            </a>
            <?php
        } else {
            ?>
            <div <?php echo join( ' ', $attributes ) ?>></div>
            <?php

        }
    } else {
        $attributes = array();

        if ( ! empty( $image_data['alt'] ) ) {
            $attributes[] = sprintf( 'alt="%s"', esc_attr( $image_data['alt'] ) );
        }

        if ( ! empty( $image_data['width'] ) ) {
            $attributes[] = sprintf( 'width="%s"', esc_attr( $image_data['width'] ) );
        }

        if ( ! empty( $image_data['height'] ) ) {
            $attributes[] = sprintf( 'height="%s"', esc_attr( $image_data['height'] ) );
        }

        $attributes[] = sprintf( 'src="%s"', esc_url( $image_data['url'] ) );
        if ( $args['display_permalink'] ) {
            ?>
            <a class="g5core__entry-thumbnail g5core__entry-thumbnail-image" href="<?php g5core_the_permalink() ?>">
                <img <?php echo join( ' ', $attributes ); ?>>
            </a>
            <?php
        } else {
            ?>
            <div class="g5core__entry-thumbnail g5core__entry-thumbnail-image">
                <img <?php echo join( ' ', $attributes ); ?>>
                <?php
                    $image_full_url = '';
                    if (!empty($image_data['id'])) {
                        $image_full = wp_get_attachment_image_src($image_data['id'],'full');
                        if (is_array($image_full) && isset($image_full[0])) {
                            $image_full_url = $image_full[0];

                        }
                    }
                    $zoom_attributes = array();
                    if (!empty($args['gallery_id'])) {
                        $zoom_attributes[] = sprintf('data-gallery-id="%s"',esc_attr($args['gallery_id']));
                    }

                    $zoom_attributes[] = sprintf('href="%s"', esc_url($image_full_url));

                ?>

                <?php if (!empty($image_full_url)): ?>
                    <a data-g5core-mfp <?php echo join(' ', $zoom_attributes)?>  class="g5core__zoom-image"><i class="fas fa-expand"></i></a>
                <?php endif; ?>
            </div>
            <?php

        }
    }
    echo ob_get_clean();
}

function g5core_the_title( $post = 0, $before = '', $after = '', $echo = true, $length = -1 ) {
    $title = g5core_get_the_title( $post );

    if ( strlen( $title ) == 0 ) {
        return;
    }

    if ($length > 0) {
        $title = g5core_truncate_text($title,$length);
    }

    $title = $before . $title . $after;

    if ( $echo ) {
        echo esc_html( $title );
    } else {
        return $title;
    }
}

function g5core_the_permalink( $post = 0 ) {
    echo g5core_get_permalink( $post );
}

function g5core_get_permalink( $post = 0 ) {

    $cache_key = 'permalink';
    $data      = G5CORE()->cache()->get_cache_listing( $cache_key );

    if ( ! is_null( $data ) ) {
        return $data;
    }
    $data = get_permalink( $post );
    G5CORE()->cache()->set_cache_listing( $cache_key, $data );
    return $data;
}

function g5core_template_breadcrumbs($wrapper_class = '') {
    if (G5CORE()->cache()->get('g5core_breadcrumb_enable')) {
        return;
    }
    G5CORE()->get_template('breadcrumb.php', array('wrapper_class' => $wrapper_class));
}

function g5core_template_social_share() {
	$social_share = g5core_get_social_share();
	if (!$social_share) return;
	G5CORE()->get_template('share.php',array('social_share' => $social_share));
}
