<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5works_vc_map_add_narrow_category($args = array())
{
    $category = array();
    $categories = get_categories(array('hide_empty' => '1','taxonomy' => 'works_category'));
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            $category[$cat->name] = $cat->term_id;
        }
    }
    $default = array(
        'type' => 'g5element_selectize',
        'heading' => esc_html__('Narrow Category', 'g5-works'),
        'param_name' => 'cat',
        'value' => $category,
        'multiple' => true,
        'description' => esc_html__('Enter categories by names to narrow output (Note: only listed categories will be displayed, divide categories with linebreak (Enter)).', 'g5-works'),
        'std' => ''
    );
    $default = array_merge($default, $args);
    return $default;
}

function g5works_vc_map_add_filter() {
    return array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show', 'g5-works'),
            'param_name' => 'show',
            'value' => array(
                esc_html__('All', 'g5-works') => '',
                esc_html__('New In', 'g5-works') => 'new-in',
                esc_html__('Featured', 'g5-works') => 'featured',
                esc_html__('Narrow Works', 'g5-works') => 'works'
            ),
            'std' => '',
            'group' => esc_html__('Works Filter', 'g5-works'),
        ),
        g5works_vc_map_add_narrow_category(array(
            'dependency' => array('element' => 'show','value_not_equal_to' => array('works')),
            'group' => esc_html__('Works Filter', 'g5-works')
        )),
        array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Narrow Works', 'g5-works' ),
            'param_name' => 'ids',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'unique_values' => true,
            ),
            'save_always' => true,
            'description' => esc_html__( 'Enter List of Works', 'g5-works' ),
            'dependency' => array('element' => 'show','value' => 'works'),
            'group' => esc_html__('Works Filter', 'g5-works'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order by', 'g5-works'),
            'param_name' => 'orderby',
            'value' => array_flip(G5WORKS()->settings()->get_works_orderby()),
            'std' => 'date',
            'description' => esc_html__('Select how to sort retrieved works.', 'g5-works'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'group' => esc_html__('Works Filter', 'g5-works'),
        ),
        array(
            'type' => 'g5element_button_set',
            'heading' => esc_html__('Sorting', 'g5-works'),
            'param_name' => 'order',
            'value' => array_flip(G5WORKS()->settings()->get_works_order()),
            'std' => 'desc',
            'group' => esc_html__('Works Filter', 'g5-works'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'description' => esc_html__('Select sorting order.', 'g5-works'),
        ),
    );
}

function g5works_dropdown_categories( $args = array() ) {
    global $wp_query;

    $args = wp_parse_args(
        $args,
        array(
            'pad_counts'         => 1,
            'show_count'         => 1,
            'hierarchical'       => 1,
            'hide_empty'         => 1,
            'show_uncategorized' => 1,
            'orderby'            => 'name',
            'selected'           => isset( $wp_query->query_vars['works_category'] ) ? $wp_query->query_vars['works_category'] : '',
            'show_option_none'   => __( 'Select a category', 'g5-works' ),
            'option_none_value'  => '',
            'value_field'        => 'slug',
            'taxonomy'           => 'works_category',
            'name'               => 'works_category',
            'class'              => 'g5works__dropdown_categories',
        )
    );

    if ( 'order' === $args['orderby'] ) {
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = 'order'; // phpcs:ignore
    }

    wp_dropdown_categories( $args );
}

function g5works_single_share_enable() {
	$single_share_enable =  G5WORKS()->options()->get_option('single_share_enable');
	if ($single_share_enable !== 'on') return false;
	return g5core_get_social_share();
}

function g5works_single_navigation_enable() {
	$single_navigation = G5WORKS()->options()->get_option( 'single_navigation_enable' );
	return $single_navigation === 'on';
}