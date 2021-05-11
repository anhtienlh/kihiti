<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
get_header();
$single_layout = G5PORTFOLIO()->options()->get_option('single_layout');
while (have_posts()) : the_post();
    G5PORTFOLIO()->get_template("single/layout/{$single_layout}.php");
endwhile;
get_footer();