<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $image
 * @var $image_size
 * @var $image_ratio
 * @var $image_mode
 * @var $hover_effect
 * @var $gallery_id
 * @var $item_class
 * @var $item_inner_class
 * @var $post_inner_attributes
 *
 */

if (!isset($post_inner_attributes)) {
    $post_inner_attributes = array();
}


?>
<div class="<?php echo esc_attr($item_class) ?>">
    <div class="<?php echo esc_attr($item_inner_class) ?>" <?php echo implode(' ', $post_inner_attributes)?>>
        <?php
        g5element_render_image_markup(array(
            'image_size' => $image_size,
            'image_ratio' => $image_ratio,
            'image_mode' => $image_mode,
            'image_id' => $image,
            'gallery_id' => $gallery_id,
            'hover_effect' => $hover_effect
        ));
        ?>
    </div>
</div>
