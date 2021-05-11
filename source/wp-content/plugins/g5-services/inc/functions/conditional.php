<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5services_is_single() {
    return is_singular( 'services' );
}

function g5services_is_taxonomy() {
    return is_tax( get_object_taxonomies( 'services' ) );
}

function g5services_is_cat($term = '') {
    return is_tax( 'services_category', $term );
}

function g5services_is_archive() {
    return is_post_type_archive( 'services' );
}