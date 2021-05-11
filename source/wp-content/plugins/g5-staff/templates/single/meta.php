<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $staff_info
 */
?>
<div class="g5staff__single-meta">
	<?php foreach ($staff_info as $info): ?>
		<?php if (isset($info['title']) && !empty($info['title']) && isset($info['value']) && !empty($info['value'])): ?>
			<div class="<?php echo esc_attr(sanitize_title($info['title']))?>">
				<label><?php echo wp_kses_post($info['title']) ?>:</label>
				<span><?php echo wp_kses_post($info['value']) ?></span>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
