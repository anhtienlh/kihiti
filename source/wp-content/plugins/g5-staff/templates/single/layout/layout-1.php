<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$sticky_options = array(
	'stickTo' => '.g5staff__single-info-wrap'
);
?>
<div class="g5staff__single-wrap">
	<article id="post-<?php the_ID() ?>" <?php post_class( 'g5staff__single row' ); ?>>
		<div class="col-lg-4 g5staff__single-info-wrap">
			<div class="g5staff__single-info g5core-sticky" data-sticky-options="<?php echo esc_attr(json_encode($sticky_options))?>">
				<?php g5staff_template_single_image(); ?>
				<div class="g5staff__single-info-content">
					<?php
					/**
					 * Hook: g5staff_single_info.
					 *
					 * @hooked g5staff_template_single_title - 5
					 * @hooked g5staff_template_loop_job - 10
					 * @hooked g5staff_template_single_meta - 15
					 * @hooked g5staff_template_loop_social_profile - 20
					 */
					do_action( 'g5staff_single_info', 'layout-1' );
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="entry-content clearfix">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
	/**
	 * Hook: g5staff_after_single.
	 */
	do_action( 'g5staff_after_single' );
	?>
</div>
