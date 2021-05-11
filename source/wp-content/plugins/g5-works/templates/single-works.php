<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
get_header();
while (have_posts()) : the_post();
    G5WORKS()->get_template('content-single-works.php');
endwhile;
get_footer();