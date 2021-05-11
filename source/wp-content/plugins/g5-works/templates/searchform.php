<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$unique_id = esc_attr(uniqid('g5works__search-form-'));
?>
<form role="search" method="get" class="search-form g5works__search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr($unique_id)?>"><?php esc_html_e( 'Search for:', 'g5-works' ); ?></label>
    <input type="search" id="<?php echo esc_attr($unique_id)?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search works&hellip;', 'g5-works' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'g5-works' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'g5-works' ); ?></button>
    <input type="hidden" name="post_type" value="works" />
</form>

