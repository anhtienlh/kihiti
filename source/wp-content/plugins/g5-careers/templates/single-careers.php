<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
get_header();
while (have_posts()) : the_post();
	G5CAREERS()->get_template('content-single-careers.php');
endwhile;
get_footer();