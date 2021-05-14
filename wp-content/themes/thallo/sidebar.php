<?php
/**
 * The sidebar containing the main widget area
 *
 * @since 1.0
 * @version 1.0
 */
if ( !thallo_has_sidebar()) {
	return;
}

$sidebar_classes = apply_filters('thallo_sidebar_classes', array(
	'primary-sidebar',
	'sidebar',
	'col',
));
?>

<div id="sidebar" class="<?php echo esc_attr(implode(' ', $sidebar_classes)); ?>">
	<div class="primary-sidebar-inner">
		<?php do_action('thallo_before_sidebar_content'); ?>
		<?php dynamic_sidebar( thallo_sidebar_primary() ); ?>
		<?php do_action('thallo_after_sidebar_content'); ?>
	</div>
</div><!-- #sidebar -->