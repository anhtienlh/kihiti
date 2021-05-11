<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="g5careers__single-wrap">
	<article id="post-<?php the_ID() ?>" <?php post_class('g5careers__single'); ?>>
		<?php
			/**
			 * Hook: g5careers_before_single_content.
			 *
			 * @hooked - g5careers_template_single_title - 5
			 * @hooked - g5careers_template_single_meta - 10
			 *
			 */
			do_action('g5careers_before_single_content');
		?>
		<div class="entry-content clearfix">
			<?php the_content();?>
		</div>
		<?php
		/**
		 * * Hook: g5careers_after_single_content.
		 *
		 * * hooked - g5careers_template_single_button_contact_us - 5
		 * * @hooked - g5careers_template_single_share - 10
		 */
		do_action('g5careers_after_single_content');
		?>
	</article>
	<?php
	/**
	 * Hook: g5careers_after_single.
	 *
	 */
	do_action('g5careers_after_single');
	?>
</div>
