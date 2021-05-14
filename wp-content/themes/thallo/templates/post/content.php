<?php
$post_class = is_singular() ? 'article-post article-single-post' : 'article-post article-archive-post';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
	<?php if (!is_singular()): ?>
		<?php thallo_get_template('post/entry-thumbnail') ?>
	<?php endif; ?>
	<header class="entry-header">
		<?php thallo_get_template('post/entry-meta') ?>
		<?php thallo_get_template('post/entry-title') ?>
	</header><!-- .entry-header -->
	<?php if (is_singular()): ?>
		<?php thallo_get_template('post/entry-thumbnail') ?>
	<?php endif; ?>
	<?php thallo_get_template('post/entry-content') ?>
	<?php if (is_singular()): ?>
		<?php thallo_get_template('post/entry-tags') ?>
	<?php else: ?>
		<?php thallo_get_template('post/read-more') ?>
	<?php endif; ?>
</article><!-- #post-## -->