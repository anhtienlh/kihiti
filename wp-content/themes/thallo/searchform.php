<?php
/**
 * Template for displaying search forms
 *
 * @version 1.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr($unique_id); ?>">
		<span><?php echo esc_html_x( 'Search for:', 'label', 'thallo' ); ?></span>
	</label>
	<input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'thallo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit"><span><?php echo esc_html_x( 'Search', 'submit button', 'thallo' ); ?></span></button>
</form>
