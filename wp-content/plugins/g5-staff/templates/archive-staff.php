<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
get_header();
$settings = array();
$group_filter_enable = G5STAFF()->options()->get_option('group_filter_enable');
$group_filter_align = G5STAFF()->options()->get_option('group_filter_align');
$settings['cate_filter_enable'] = $group_filter_enable === 'on';
$settings['cate_filter_align'] = $group_filter_align;
G5STAFF()->listing()->render_content(null,$settings);
get_footer();