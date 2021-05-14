<?php
function thallo_comment_form_args() {
	$commenter = wp_get_current_commenter();
	$req = get_option('require_name_email');
	$html_req = ($req ? " required='required'" : '');

    $fields = array(
        'author'  => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Name', 'thallo' ) . ($req ? '*' : '') . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $html_req . ' /></p>',
        'email'   => '<p class="comment-form-email">' . '<input id="email" name="email" placeholder="' . esc_attr__( 'Email', 'thallo' ) . ($req ? '*' : '') . '" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' /></p>',
        'url'     => '<p class="comment-form-url">' . '<input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'thallo' ) . '" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></p>',
    );

    $defaults = array(
        'format'               => 'html5',
        'fields'             => $fields,
        'comment_field'      => '<p class="comment-form-comment"><textarea placeholder="' . esc_attr__('Comment', 'thallo') . ($req ? '*' : '') . '" id="comment" name="comment" cols="45" rows="3" maxlength="65525" required="required"></textarea></p>',
        'class_submit'       => 'btn btn-gradient'
    );

	return $defaults;
}


