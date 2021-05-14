<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $post
 * @var $single_link_disable
 */
?>
<h3 class="g5staff__post-title">
	<?php if ($single_link_disable): ?>
		<?php g5core_the_title($post) ?>
	<?php else: ?>
		<a title="<?php g5core_the_title_attribute($post)?>" href="<?php g5core_the_permalink($post)?>"><?php g5core_the_title($post) ?></a>
	<?php endif; ?>
</h3>
