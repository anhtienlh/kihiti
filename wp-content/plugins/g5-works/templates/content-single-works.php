<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="g5works__single-wrap">
	<article id="post-<?php the_ID() ?>" <?php post_class('g5works__single'); ?>>
		<?php
		/**
		 * Hook: g5works_before_single_content.
		 *
		 * @hooked - g5works_template_single_title - 5
		 *
		 */
		do_action('g5works_before_single_content')
		?>
		<div class="entry-content clearfix">
			<?php the_content();?>
		</div>
		<?php
		/**
		 * * @hooked - g5works_template_single_share - 10
		 */
		do_action('g5works_after_single_content');
		?>
	</article>
	<?php
	/**
	 * @hooked - g5works_template_single_navigation - 10
	 * @hooked - g5works_template_single_related - 20
	 * @hooked - g5works_template_single_comment - 30
	 */
	do_action('g5works_after_single');
	?>
</div>
