<?php
// Get Categories for posts.
$categories_list = get_the_category_list( ', ');

if (empty($categories_list)) {
	return;
}
?>
<span class="cat-tags-links">
	<span class="cat-links"><?php echo wp_kses_post($categories_list) ?></span>
</span>