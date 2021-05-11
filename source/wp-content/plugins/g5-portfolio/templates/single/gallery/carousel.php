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
 * @var $columns_gutter
 * @var $columns_xl
 * @var $columns_lg
 * @var $columns_md
 * @var $columns_sm
 * @var $columns
 * @var $custom_class
 */

$wrapper_classes = array(
    'g5portfolio__single-gallery',
    "g5portfolio__single-gallery-type-{$media_type}",
    "g5core__gutter-{$columns_gutter}",
    'slick-slider',
    $custom_class
);

$slick_options = array(
    'arrows' => false,
    'dots' => true,
    'slidesToShow' => $columns_xl,
    'slidesToScroll' => $columns_xl,
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
);
$centerMode = G5PORTFOLIO()->options()->get_option('single_gallery_carousel_center_enable');
if ($centerMode === 'on') {
    $slick_options['centerMode'] = true;
    $slick_options['infinite'] = true;
    $centerPadding = G5PORTFOLIO()->options()->get_option('single_gallery_carousel_center_padding');
    if ($centerPadding !== '') {
        $slick_options['centerPadding'] = $centerPadding;
    }
    $wrapper_classes[] = 'slick-slide-center';
}
$slick_options = apply_filters('g5portfolio_single_gallery_carousel_options',$slick_options);


$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>" data-slick-options="<?php echo esc_attr(json_encode($slick_options))?>">
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
