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
if ($media_type === 'video') return;

$image_size = 'full';
$image_mode = 'image';
$justified_row_height_arr =  G5PORTFOLIO()->options()->get_option('single_gallery_justified_row_height');
if (is_array($justified_row_height_arr) && isset($justified_row_height_arr['height'])) {
    $justified_row_height = absint($justified_row_height_arr['height']);
} else {
    $justified_row_height = 200;
}

$justified_row_max_height_arr =  G5PORTFOLIO()->options()->get_option('single_gallery_justified_row_max_height');
if (is_array($justified_row_max_height_arr) && isset($justified_row_max_height_arr['height'])) {
    $justified_row_max_height = absint($justified_row_max_height_arr['height']);
} else {
    $justified_row_max_height = 0;
}

$justified_options = array(
    'rowHeight' => $justified_row_height,
    'maxRowHeight' => $justified_row_max_height > 0 ? $justified_row_max_height : false,
    'margins' => $columns_gutter,
    'selector' => '.g5core__justified-item',
    'imgSelector' => '.g5core__entry-thumbnail > img'
);

$wrapper_classes = array(
    'g5portfolio__single-gallery',
    'g5core__justified-gallery',
    'justified-gallery',
    $custom_class
);
$wrapper_class = implode(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>" data-justified-options="<?php echo esc_attr(json_encode($justified_options))?>">
    <?php
    if ($gallery !== '') {
        $gallery_id = uniqid();
        $gallery = explode('|', $gallery);
        foreach ($gallery as $image) {
            echo '<div class="g5core__justified-item">';
            g5portfolio_render_single_thumbnail_markup(array(
                'image_size' => $image_size,
                'image_mode' => $image_mode,
                'image_id' => $image,
                'gallery_id' => $gallery_id,
            ));
            echo '</div>';
        }
    } else if (has_post_thumbnail()) {
        echo '<div class="g5core__justified-item">';
        g5portfolio_render_single_thumbnail_markup(array(
            'image_size' => $image_size,
            'image_mode' => $image_mode,
        ));
        echo '</div>';
    }
    ?>
</div>
