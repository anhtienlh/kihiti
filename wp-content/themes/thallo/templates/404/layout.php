<?php
/**
 * The template for displaying 404 content layout
 *
 * @since 1.0
 * @version 1.0
 */
?>
<div class="content-404-wrapper">
	<h2><?php esc_html_e('404','thallo'); ?></h2>
	<h4><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'thallo' ); ?></h4>
	<p><?php esc_html_e('Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.', 'thallo') ?></p>
	<a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-gradient"><?php esc_html_e('Go to home page', 'thallo'); ?></a>
</div><!-- .wrap -->
