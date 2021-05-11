<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Header template
 */
function thallo_template_header() {
	thallo_get_template('header');
}
add_action('thallo_before_page_wrapper_content', 'thallo_template_header', 10);

/**
 * Footer template
 */
function thallo_template_footer() {
	thallo_get_template('footer');
}
add_action('thallo_after_page_wrapper_content', 'thallo_template_footer', 10);

/**
 * Content Wrapper Start
 */
function thallo_template_wrapper_start() {
	thallo_get_template('global/wrapper-start');
}
add_action('thallo_main_wrapper_content_start', 'thallo_template_wrapper_start', 10);

/**
 * Content Wrapper End
 */
function thallo_template_wrapper_end() {
	thallo_get_template('global/wrapper-end');
}
add_action('thallo_main_wrapper_content_end', 'thallo_template_wrapper_end', 10);

/**
 * Archive content layout
 */
function thallo_template_archive_content() {
	thallo_get_template('archive/layout');
}
add_action('thallo_archive_content', 'thallo_template_archive_content', 10);

/**
 * Single content layout
 */
function thallo_template_single_content() {
	thallo_get_template('single/layout');
}
add_action('thallo_single_content', 'thallo_template_single_content', 10);

/**
 * Single content layout
 */
function thallo_template_page_content() {
	thallo_get_template('page/layout');
}
add_action('thallo_page_content', 'thallo_template_page_content', 10);

/**
 * Search content layout
 */
function thallo_template_search_content() {
	thallo_get_template('search/layout');
}
add_action('thallo_search_content', 'thallo_template_search_content', 10);

/**
 * 404 content layout
 */
function thallo_template_404_content() {
	thallo_get_template('404/layout');
}
add_action('thallo_404_content', 'thallo_template_404_content', 10);

function thallo_template_page_title() {
	thallo_get_template( 'page-title' );
}
add_action('thallo_before_main_content', 'thallo_template_page_title', 10);
