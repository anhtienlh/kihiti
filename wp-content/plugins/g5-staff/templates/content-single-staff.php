<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="g5staff__single-wrap">
	<article id="post-<?php the_ID() ?>" <?php post_class('g5staff__single'); ?>>
		<?php

		/**
		 * Hook: g5staff_before_single_content.
		 *
		 * @hooked g5staff_template_single_title - 5
		 * @hooked g5staff_template_loop_job - 10
		 * @hooked g5staff_template_loop_social_profile - 15
		 * @hooked g5staff_template_loop_excerpt - 20
		 */
		do_action('g5staff_before_single_content')

		?>
		<div class="entry-content clearfix">
			<?php the_content();?>
		</div>
		<?php
		/**
		 * * @hooked - g5staff_template_single_share - 10
		 */
		do_action('g5staff_after_single_content');
		?>
	</article>
</div>
