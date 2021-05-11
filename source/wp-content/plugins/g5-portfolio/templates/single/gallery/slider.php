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
 * @var $custom_class
 */
$slick_options = array(
    'slidesToScroll' => 1,
    'slidesToShow' => 1,
    'arrows' => true,
    'dots' => true
);

if ($media_type === 'video') {
    $slick_options['arrows'] = false;
}


$wrapper_classes = array(
    'g5portfolio__single-gallery',
    'g5portfolio__single-gallery-slider',
    "g5portfolio__single-gallery-type-{$media_type}",
    'slick-slider',
    'g5core__post-featured',
    $custom_class
);

$gallery_id = uniqid();
$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class) ?>"
     data-slick-options="<?php echo esc_attr(json_encode($slick_options)) ?>">
    <?php
    if ($media_type === 'image') {
        if ($gallery !== '') {
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
        $video = array_filter($video, 'strlen');
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
