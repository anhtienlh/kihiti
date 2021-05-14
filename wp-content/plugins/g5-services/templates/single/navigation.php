<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$args = apply_filters('g5services_single_navigation_args',array(
    'prev_text' => '<span aria-hidden="true" class="nav-subtitle"><i class="fas fa-angle-left"></i> ' . __( 'Previous', 'g5-services' ) . '</span><span class="nav-title">%title</span>',
    'next_text' => '<span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'g5-services' ) . ' <i class="fas fa-angle-right"></i></span><span class="nav-title">%title</span>',
    'in_same_term'       => false,
    'excluded_terms'     => '',
    'taxonomy'           => 'services_category',
    'post_type' => 'services',
    'archive_link_enable' => true
));


$post_type_object = get_post_type_object($args['post_type']);
if (is_a($post_type_object,'WP_Post_Type')) {
    $post_type_archive_label = $post_type_object->labels->name;
    $screen_reader_text =  sprintf( esc_html__( '%s navigation', 'g5-services' ), $post_type_archive_label);
}

$previous = get_previous_post_link(
    '<div class="nav-previous">%link</div>',
    $args['prev_text'],
    $args['in_same_term'],
    $args['excluded_terms'],
    $args['taxonomy']
);

$next = get_next_post_link(
    '<div class="nav-next">%link</div>',
    $args['next_text'],
    $args['in_same_term'],
    $args['excluded_terms'],
    $args['taxonomy']
);



// Only add markup if there's somewhere to navigate to.
if ( $previous || $next ) {
    $archive_link_html = '';
    if ($args['archive_link_enable']) {
        $archive_link = get_post_type_archive_link($args['post_type']);
        $archive_link_html = sprintf('<a class="nav-back" href="%s"><i class="fa fa-th"></i></a>', esc_url($archive_link));
    }

    if ($previous === '') {
        $previous = '<div class="nav-previous disabled"></div>';
    }

    if ($next === '') {
        $next = '<div class="nav-next disabled"></div>';
    }
    echo _navigation_markup( $previous .$archive_link_html . $next, 'g5services__single-navigation', $screen_reader_text );
}
