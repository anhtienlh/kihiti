<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $items array
 */
?>
<ul class="g5careers__single-meta">
	<?php foreach ($items as $item): ?>
		<?php
			$id = isset($item['id']) ?  $item['id'] : '';
			$title = isset($item['title']) ? $item['title'] : '';
			$content = isset($item['content']) ? $item['content'] : '';
		?>
		<?php if (!empty($content)): ?>
			<li id="g5careers_loop_meta_<?php echo esc_attr($id)?>" title="<?php echo esc_attr($title)?>">
				<label><?php echo esc_html($title)?>:</label>
				<span><?php echo esc_html($content)?></span>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
