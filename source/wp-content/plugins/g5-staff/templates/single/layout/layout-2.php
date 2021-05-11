<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="g5staff__single-wrap">
	<article id="post-<?php the_ID() ?>" <?php post_class('g5staff__single'); ?>>
		<div class="entry-content clearfix">
			<?php the_content();?>
		</div>
	</article>
	<?php
	/**
	 * Hook: g5staff_after_single.
	 */
	do_action( 'g5staff_after_single' );
	?>
</div>
