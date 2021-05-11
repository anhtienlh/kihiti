<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$post_id = get_the_ID();
$thumbnail_data = g5core_get_thumbnail_data(array(
	'image_size' => 'full',
	'image_id' => get_post_thumbnail_id($post_id),
));
?>

<?php if ($thumbnail_data['url'] !== ''): ?>
	<div class="g5core__post-featured g5staff__single-featured">
		<?php g5core_render_thumbnail_markup(array(
			'post_id' => $post_id,
			'image_id' => get_post_thumbnail_id($post_id),
			'image_size' => 'full',
			'display_permalink' => false,
			'image_mode' => 'image'
		)) ?>
	</div>
<?php endif; ?>
