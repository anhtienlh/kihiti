<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5careers_is_single() {
    return is_singular( 'careers' );
}

function g5careers_is_archive() {
    return is_post_type_archive( 'careers' );
}