<?php
/**
 * The template for displaying wrapper-start
 *
 * @since 1.0
 * @version 1.0
 */

$wrapper_class = apply_filters('thallo_content_wrapper_classes', array());

$inner_class = apply_filters('thallo_content_inner_classes', array('col'));

/**
 * @hooked thallo_template_page_title - 10
 */
do_action('thallo_before_main_content');
?>
<!-- Primary Content Wrapper -->
<div id="primary-content" class="<?php echo esc_attr(implode(' ', $wrapper_class))?>">
	<!-- Primary Content Container -->
	<div class="container">
		<!-- Primary Content Row -->
		<div class="row">
			<!-- Primary Content Inner -->
			<div id="main-content" class="<?php echo esc_attr(implode(' ', $inner_class)); ?>">


