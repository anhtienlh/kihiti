<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5portfolio_single_layout_class($classes) {
    if (is_singular('portfolio')) {
        $single_post_layout = G5PORTFOLIO()->options()->get_option('single_layout');
        $classes[] = 'g5portfolio__single-' . $single_post_layout;
    }
    return $classes;
}
add_filter('body_class', 'g5portfolio_single_layout_class');

function g5portfolio_vc_map_add_narrow_category($args = array())
{
    $category = array();
    $categories = get_categories(array('hide_empty' => '1','taxonomy' => 'portfolio_category'));
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            $category[$cat->name] = $cat->term_id;
        }
    }
    $default = array(
        'type' => 'g5element_selectize',
        'heading' => esc_html__('Narrow Category', 'g5-portfolio'),
        'param_name' => 'cat',
        'value' => $category,
        'multiple' => true,
        'description' => esc_html__('Enter categories by names to narrow output (Note: only listed categories will be displayed, divide categories with linebreak (Enter)).', 'g5-portfolio'),
        'std' => ''
    );
    $default = array_merge($default, $args);
    return $default;
}

function g5portfolio_vc_map_add_filter() {
    return array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show', 'g5-portfolio'),
            'param_name' => 'show',
            'value' => array(
                esc_html__('All', 'g5-portfolio') => '',
                esc_html__('New In', 'g5-portfolio') => 'new-in',
                esc_html__('Featured', 'g5-portfolio') => 'featured',
                esc_html__('Narrow Portfolio', 'g5-portfolio') => 'portfolio'
            ),
            'std' => '',
            'group' => esc_html__('Portfolio Filter', 'g5-portfolio'),
        ),
        g5portfolio_vc_map_add_narrow_category(array(
            'dependency' => array('element' => 'show','value_not_equal_to' => array('portfolio')),
            'group' => esc_html__('Portfolio Filter', 'g5-portfolio')
        )),
        array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Narrow Portfolio', 'g5-portfolio' ),
            'param_name' => 'ids',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'unique_values' => true,
            ),
            'save_always' => true,
            'description' => esc_html__( 'Enter List of Portfolio', 'g5-portfolio' ),
            'dependency' => array('element' => 'show','value' => 'portfolio'),
            'group' => esc_html__('Portfolio Filter', 'g5-portfolio'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order by', 'g5-portfolio'),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__('Date', 'g5-portfolio') => 'date',
                esc_html__('Random', 'g5-portfolio') => 'rand',
            ),
            'std' => 'date',
            'description' => esc_html__('Select how to sort retrieved portfolio.', 'g5-portfolio'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'group' => esc_html__('Portfolio Filter', 'g5-portfolio'),
        ),
        array(
            'type' => 'g5element_button_set',
            'heading' => esc_html__('Sorting', 'g5-portfolio'),
            'param_name' => 'order',
            'value' => array(
                esc_html__('Descending', 'g5-portfolio') => 'DESC',
                esc_html__('Ascending', 'g5-portfolio') => 'ASC',
            ),
            'std' => 'DESC',
            'group' => esc_html__('Portfolio Filter', 'g5-portfolio'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'description' => esc_html__('Select sorting order.', 'g5-portfolio'),
        ),
    );
}

function g5portfolio_dropdown_categories( $args = array() ) {
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
            'selected'           => isset( $wp_query->query_vars['portfolio_category'] ) ? $wp_query->query_vars['portfolio_category'] : '',
            'show_option_none'   => __( 'Select a category', 'g5-portfolio' ),
            'option_none_value'  => '',
            'value_field'        => 'slug',
            'taxonomy'           => 'portfolio_category',
            'name'               => 'portfolio_category',
            'class'              => 'g5portfolio__dropdown_categories',
        )
    );

    if ( 'order' === $args['orderby'] ) {
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = 'order'; // phpcs:ignore
    }

    wp_dropdown_categories( $args );
}

function g5portfolio_single_share_enable() {
	$single_share_enable =  G5PORTFOLIO()->options()->get_option('single_share_enable');
	if ($single_share_enable !== 'on') return false;
	return g5core_get_social_share();
}

function g5portfolio_single_navigation_enable() {
	$single_navigation = G5PORTFOLIO()->options()->get_option( 'single_navigation_enable' );
	return $single_navigation === 'on';
}