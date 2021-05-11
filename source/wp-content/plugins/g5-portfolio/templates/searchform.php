<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$unique_id = esc_attr(uniqid('g5portfolio__search-form-'));
?>
<form role="search" method="get" class="search-form g5portfolio__search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr($unique_id)?>"><?php esc_html_e( 'Search for:', 'g5-porfolio' ); ?></label>
    <input type="search" id="<?php echo esc_attr($unique_id)?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search portfolio&hellip;', 'g5-portfolio' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'g5-portfolio' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'g5-portfolio' ); ?></button>
    <input type="hidden" name="post_type" value="portfolio" />
</form>

