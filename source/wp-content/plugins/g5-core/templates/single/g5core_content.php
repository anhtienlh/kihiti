<?php
get_header();
while (have_posts()) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
		<div class="g5core-vc-template-entry-content clearfix">
			<?php
			$content_type = get_post_meta(get_the_ID(),'g5core_content_block_type',true);
			if ($content_type === 'other') {
				the_content();
			} ?>
		</div>
	</article>
<?php
endwhile;
get_footer();