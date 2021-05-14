<?php
/**
 * The template for displaying widget-area-box.php
 *
 * @package WordPress
 * @subpackage april
 * @since april 1.0
 */
$nonce =  wp_create_nonce('g5core-delete-widget-area-nonce');
?>
<script type="text/html" id="g5core-add-widget-template">
	<div id="g5core-add-widget" class="widgets-holder-wrap">
		<input type="hidden" name="g5core-widget-areas-nonce" value="<?php echo esc_attr($nonce) ?>" />
		<div class="sidebar-name">
			<h3><?php esc_html_e('Create Widget Area', 'g5-core'); ?></h3>
		</div>
		<div class="sidebar-description">
			<form id="addWidgetAreaForm" action="" method="post">
				<div class="widget-content">
					<input id="g5core-add-widget-input" name="g5core-add-widget-input" type="text" class="regular-text" title="<?php echo esc_attr(esc_html__('Name','g5-core')); ?>" placeholder="<?php echo esc_attr(esc_html__('Name','g5-core')); ?>" />
				</div>
				<div class="widget-control-actions">
					<input class="addWidgetArea-button button-primary" type="submit" value="<?php echo esc_attr(esc_html__('Create Widget Area', 'g5-core')); ?>" />
				</div>
			</form>
		</div>
	</div>
</script>
