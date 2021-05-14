<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $media_type
 * @var $gallery
 * @var $video
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
    'row',
    "g5core__gutter-{$columns_gutter}",
    'isotope',
    $custom_class
);

$item_classes = array(
    'g5core__gutter-item',
);

$item_classes[] = g5core_get_bootstrap_columns(array(
    'xl' => $columns_xl,
    'lg' => $columns_lg,
    'md' => $columns_md,
    'sm' => $columns_sm,
    '' => $columns
));

$image_width = G5PORTFOLIO()->options()->get_option('single_gallery_image_width');
if (is_array($image_width) && isset($image_width['width'])) {
    $image_width = intval($image_width['width']);
} else {
    $image_width = 400;
}

$image_size = "{$image_width}x0";

$isotope_config = array(
    'itemSelector' => '.g5core__gutter-item',
    'layoutMode'   => 'masonry',
);


$item_class = implode(' ', $item_classes);

$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo  esc_attr($wrapper_class)?>" data-isotope-options="<?php echo esc_attr(json_encode($isotope_config))?>">
    <?php
    if ($media_type === 'image') {
        if ($gallery !== '') {
            $gallery_id = uniqid();
            $gallery = explode('|', $gallery);
            foreach ($gallery as $image) {
                echo '<div class="'. $item_class .'">';
                g5portfolio_render_single_thumbnail_markup(array(
                    'image_size' => $image_size,
                    'image_ratio' => $image_ratio,
                    'image_id' => $image,
                    'gallery_id' => $gallery_id
                ));
                echo '</div>';
            }
        } else if (has_post_thumbnail()) {
            echo '<div class="'. $item_class .'">';
            g5portfolio_render_single_thumbnail_markup(array(
                'image_size' => $image_size,
                'image_ratio' => $image_ratio,
            ));
            echo '</div>';
        }
    } else {
        $video = !is_array($video) ? array($video) : $video;
        $video =  array_filter($video,'strlen');
        if (count($video) > 0) {
            foreach ($video as $url) {
                if (wp_oembed_get($url) !== false) {
                    echo '<div class="'. $item_class .'">';
                    echo '<div class="g5core__embed-responsive g5core__image-size-16x9">';
                    echo wp_oembed_get($url, array('wmode' => 'transparent'));
                    echo '</div>';
                    echo '</div>';
                }
            }
        } else if (has_post_thumbnail()) {
            echo '<div class="'. $item_class .'">';
            g5portfolio_render_single_thumbnail_markup(array(
                'image_size' => $image_size,
                'image_ratio' => $image_ratio,
            ));
            echo '</div>';
        }
    }
    ?>
</div>
