<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
get_header();

$settings = array();
$category_filter_enable = G5PORTFOLIO()->options()->get_option('category_filter_enable');
$category_filter_align = G5PORTFOLIO()->options()->get_option('category_filter_align');
$settings['cate_filter_enable'] = $category_filter_enable === 'on';
$settings['cate_filter_align'] = $category_filter_align;
G5PORTFOLIO()->listing()->render_content(null,$settings);
get_footer();