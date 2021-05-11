<?php if (has_tag()): ?>
	<div class="tagcloud">
		<label><?php esc_html_e('Tags:','thallo') ?></label>
		<?php the_tags('','',''); ?>
	</div>
<?php endif; ?>