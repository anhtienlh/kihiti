<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
/**
 * @var $image_size
 * @var $image_ratio
 * @var $post_class
 * @var $post_inner_class
 * @var $post_inner_attributes
 * @var $image_mode
 * @var $category_enable
 * @var $excerpt_enable
 *
 */
$post_class .= ' g5services__post-skin-classic';

?>
<article <?php post_class($post_class) ?>>
	<div <?php echo implode(' ', $post_inner_attributes) ?> class="<?php echo esc_attr($post_inner_class); ?>">
		<div class="g5core__post-featured g5services__post-featured">
			<?php g5services_render_thumbnail_markup(array(
				'image_size' => $image_size,
				'image_ratio' => $image_ratio,
				'image_mode' => $image_mode,
			));?>
		</div>
		<div class="g5services__post-content">
			<?php
			/**
			 * Hook: g5services_loop_post_content.
			 *
			 * @hooked g5services_template_loop_cat - 5
			 * @hooked g5services_template_loop_title - 10
			 * @hooked g5services_template_loop_excerpt - 15
			 */
			do_action('g5services_loop_post_content');
			?>
		</div>
	</div>
</article>