<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5element_render_image_markup($args = array()) {
    $args = wp_parse_args($args, array(
        'image_size' => 'thumbnail',
        'image_ratio' => '',
        'image_id' => '',
        'image_mode' => '',
        'gallery_id' => '',
        'hover_effect' => ''
    ));

    $image_data = g5core_get_image_data(array(
        'image_id' => $args['image_id'],
        'image_size' => $args['image_size']
    ));

    if (!$image_data) {
        $args['image_mode'] = '';
    }

    ob_start();

    if ($args['image_mode'] !== 'image') {
        $attributes = array();

        $classes = array(
            'g5core__entry-thumbnail',
            'g5core__embed-responsive',
        );

        if (empty($args['image_ratio'])) {
            if (preg_match('/x/', $args['image_size'])) {
                if (!$image_data) {
                    $image_sizes = preg_split('/x/', $args['image_size']);
                    $image_width = isset($image_sizes[0]) ? intval($image_sizes[0]) : 0;
                    $image_height = isset($image_sizes[1]) ? intval($image_sizes[1]) : 0;
                } else {
                    $image_width = $image_data['width'];
                    $image_height = $image_data['height'];
                }


                if (($image_width > 0) && ($image_height > 0)) {
                    $ratio = ($image_height / $image_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$image_width}x{$image_height}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$image_width}x{$image_height}");
                }

                $classes[] = "g5core__image-size-{$image_width}x{$image_height}";
            } else {
                $classes[] = "g5core__image-size-{$args['image_size']}";
            }

        } else {
            $classes[] = "g5core__image-size-{$args['image_ratio']}";

            if (!in_array($args['image_ratio'], array('1x1', '3x4', '4x3', '16x9', '9x16'))) {

                $image_ratio_sizes = preg_split('/x/', $args['image_ratio']);
                $image_ratio_width = isset($image_ratio_sizes[0]) ? intval($image_ratio_sizes[0]) : 0;
                $image_ratio_height = isset($image_ratio_sizes[1]) ? intval($image_ratio_sizes[1]) : 0;

                if (($image_ratio_width > 0) && ($image_ratio_height > 0)) {
                    $ratio = ($image_ratio_height / $image_ratio_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$args['image_ratio']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$args['image_ratio']}");
                }
            }
        }

        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('style="background-image: url(%s);"', esc_url($image_data['url']));
        }

        $attributes[] = sprintf('class="%s"', join(' ', $classes));
        ?>
        <div <?php echo join(' ', $attributes) ?>></div>
        <?php
    } else {

        $attributes = array();

        if (!empty($image_data['alt'])) {
            $attributes[] = sprintf('alt="%s"', esc_attr($image_data['alt']));
        }

        if (!empty($image_data['width'])) {
            $attributes[] = sprintf('width="%s"', esc_attr($image_data['width']));
        }

        if (!empty($image_data['height'])) {
            $attributes[] = sprintf('height="%s"', esc_attr($image_data['height']));
        }

        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('src="%s"', esc_url($image_data['url']));
        }

        ?>
        <div class="g5core__entry-thumbnail g5core__entry-thumbnail-image">
            <img <?php echo join(' ', $attributes); ?>>
        </div>
        <?php

    }

    $image_html =  ob_get_clean();
    $image_full_url = '';
    if (!empty($args['image_id'])) {
        $image_full = wp_get_attachment_image_src($args['image_id'],'full');
        if (is_array($image_full) && isset($image_full[0])) {
            $image_full_url = $image_full[0];

        }
    }

    $zoom_attributes = array();
    if (!empty($args['gallery_id'])) {
        $zoom_attributes[] = sprintf('data-gallery-id="%s"',esc_attr($args['gallery_id']));
    }
    $zoom_attributes[] = sprintf('href="%s"', esc_url($image_full_url));

    $wrapper_classes = array(
        'g5core__post-featured'
    );

    if (!empty($args['hover_effect'])) {
        $wrapper_classes[] =  'g5core__post-featured-effect';
        $wrapper_classes[] =  "effect-{$args['hover_effect']}";
    }
    $wrapper_class = implode(' ', $wrapper_classes);

    ?>
    <div class="<?php echo esc_attr($wrapper_class)?>">
        <?php echo $image_html;?>
        <?php if ($image_full_url !== ''): ?>
            <a data-g5core-mfp <?php echo join(' ', $zoom_attributes)?>  class="g5core__zoom-image"><i class="fas fa-expand"></i></a>
        <?php endif; ?>
    </div>
    <?php
}