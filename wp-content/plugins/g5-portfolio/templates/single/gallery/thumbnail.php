<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $media_type
 * @var $gallery
 * @var $video
 * @var $image_size
 * @var $image_ratio
 * @var $columns_xl
 * @var $columns_lg
 * @var $columns_md
 * @var $columns_sm
 * @var $columns
 * @var $custom_class
 */
$wrapper_classes = array(
    'g5portfolio__single-gallery',
    'g5portfolio__single-gallery-thumbnail',
    $custom_class
);
$wrapper_class = implode(' ', $wrapper_classes);

$slick_options = array(
    'slidesToScroll' =>  1,
    'slidesToShow' => 1,
    'arrows' => false,
    'dots' => true,
);

if ($media_type === 'image') {
    $slick_options['asNavFor'] = '.g5portfolio__slider-thumb';
    $slick_options['fade'] =  true;
    $slick_options['arrows'] =  true;
}


$slick_thumb_options = array(
    'slidesToShow' => $columns_xl,
    'slidesToScroll' => $columns_xl,
    'arrows' => false,
    'dots' => false,
    'focusOnSelect' => true,
    'asNavFor' => '.g5portfolio__slider-main',
    'responsive' => array(
        array(
            'breakpoint' => 1200,
            'settings' => array(
                'slidesToShow' => $columns_lg,
                'slidesToScroll' => $columns_lg,
            )
        ),
        array(
            'breakpoint' => 992,
            'settings' => array(
                'slidesToShow' => $columns_md,
                'slidesToScroll' => $columns_md,
            )
        ),
        array(
            'breakpoint' => 768,
            'settings' => array(
                'slidesToShow' => $columns_sm,
                'slidesToScroll' => $columns_sm,
            )
        ),
        array(
            'breakpoint' => 576,
            'settings' => array(
                'slidesToShow' => $columns,
                'slidesToScroll' => $columns,
            )
        )
    ),
)

?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="g5core__post-featured g5portfolio__slider-main slick-slider g5portfolio__single-gallery-slider g5portfolio__single-gallery-type-<?php echo esc_attr($media_type)?>" data-slick-options="<?php echo esc_attr(json_encode($slick_options))?>">
        <?php
        if ($media_type === 'image') {
            if ($gallery !== '') {
                $gallery_id = uniqid();
                $gallery = explode('|', $gallery);
                foreach ($gallery as $image) {
                    g5portfolio_render_single_thumbnail_markup(array(
                        'image_size' => $image_size,
                        'image_ratio' => $image_ratio,
                        'image_id' => $image,
                        'gallery_id' => $gallery_id
                    ));
                }
            } else if (has_post_thumbnail()) {
                g5portfolio_render_single_thumbnail_markup(array(
                    'image_size' => $image_size,
                    'image_ratio' => $image_ratio,
                ));
            }
        } else {
            $video = !is_array($video) ? array($video) : $video;
            $video =  array_filter($video,'strlen');
            if (count($video) > 0) {
                foreach ($video as $url) {
                    if (wp_oembed_get($url) !== false) {
                        echo '<div class="g5core__embed-responsive g5core__image-size-16x9">';
                        echo wp_oembed_get($url, array('wmode' => 'transparent'));
                        echo '</div>';
                    }
                }
            } else if (has_post_thumbnail()) {
                g5portfolio_render_single_thumbnail_markup(array(
                    'image_size' => $image_size,
                    'image_ratio' => $image_ratio,
                ));
            }
        }
        ?>
    </div>
    <?php if ($media_type === 'image'): ?>
    <div class="g5portfolio__slider-thumb slick-slider g5core__gutter-20 g5portfolio__single-gallery-type-<?php echo esc_attr($media_type)?>" data-slick-options="<?php echo esc_attr(json_encode($slick_thumb_options))?>">
        <?php
        $image_size = apply_filters('g5portfolio_single_gallery_thumbnail_image_size','thumbnail') ;
        $image_ratio = '';
        if ($gallery !== '') {
            foreach ($gallery as $image) {
                g5portfolio_render_thumbnail_markup(array(
                    'image_size' => $image_size,
                    'image_ratio' => $image_ratio,
                    'image_id' => $image,
                    'image_mode' => 'image',
                    'display_permalink' => false
                ));
            }
        } else if (has_post_thumbnail()) {
            g5portfolio_render_thumbnail_markup(array(
                'image_size' => $image_size,
                'image_ratio' => $image_ratio,
                'display_permalink' => false
            ));
        }
        ?>
    </div>
    <?php endif; ?>

</div>
