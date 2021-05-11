<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!has_post_thumbnail()) return;
$image_id = get_post_thumbnail_id();
$post_settings = G5PORTFOLIO()->listing()->get_layout_settings();
$settingId = isset($post_settings['settingId']) ? $post_settings['settingId'] : uniqid();
$zoom_attributes = array();
$image_full = wp_get_attachment_image_src($image_id,'full');
$image_full_url = '';
if (is_array($image_full) && isset($image_full[0])) {
    $image_full_url = $image_full[0];
}
if ($image_full_url === '') return;
$zoom_attributes[] = sprintf('data-gallery-id="%s"',esc_attr($settingId));
$zoom_attributes[] = sprintf('href="%s"', esc_url($image_full_url));

?>
<a data-g5core-mfp <?php echo join(' ', $zoom_attributes)?>  class="g5core__zoom-image"><i class="fas fa-expand"></i></a>
