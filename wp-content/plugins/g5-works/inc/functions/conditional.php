<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5works_is_single() {
    return is_singular( 'works' );
}

function g5works_is_taxonomy() {
    return is_tax( get_object_taxonomies( 'works' ) );
}

function g5works_is_cat($term = '') {
    return is_tax( 'works_category', $term );
}

function g5works_is_archive() {
    return is_post_type_archive( 'works' );
}