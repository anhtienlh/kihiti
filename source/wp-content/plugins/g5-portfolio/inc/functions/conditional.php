<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5portfolio_is_single() {
    return is_singular( 'portfolio' );
}

function g5portfolio_is_taxonomy() {
    return is_tax( get_object_taxonomies( 'portfolio' ) );
}

function g5portfolio_is_cat($term = '') {
    return is_tax( 'portfolio_category', $term );
}

function g5portfolio_is_tag($term = '') {
    return is_tax( 'portfolio_tag', $term );
}

function g5portfolio_is_archive() {
    return is_post_type_archive( 'portfolio' );
}