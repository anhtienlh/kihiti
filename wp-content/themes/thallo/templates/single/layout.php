<?php
/**
 * The template for displaying single content layout
 *
 * @since 1.0
 * @version 1.0
 */

while ( have_posts() ) : the_post();

	thallo_get_template( 'post/content', array('post_format' => get_post_format()));

	if (get_post_type() !== 'attachment') {
		the_post_navigation( array(
			'prev_text' => '<span aria-hidden="true" class="nav-subtitle"><i class="fas fa-angle-left"></i> ' . esc_html__( 'Previous', 'thallo' ) . '</span><span class="nav-title">%title</span>',
			'next_text' => '<span aria-hidden="true" class="nav-subtitle">' . esc_html__( 'Next', 'thallo' ) . ' <i class="fas fa-angle-right"></i></span><span class="nav-title">%title</span>',
		) );
	}

    thallo_get_template('post/entry-author');

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endwhile; // End of the loop.