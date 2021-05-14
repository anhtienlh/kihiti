<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5staff_is_single() {
    return is_singular( 'staff' );
}

function g5staff_is_taxonomy() {
    return is_tax( get_object_taxonomies( 'staff' ) );
}

function g5staff_is_group($term = '') {
	return is_tax( 'staff_group', $term );
}

function g5staff_is_archive() {
    return is_post_type_archive( 'staff' );
}