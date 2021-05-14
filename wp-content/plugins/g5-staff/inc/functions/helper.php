<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5staff_single_layout_class($classes) {
	if (g5staff_is_single()) {
		$single_post_layout = G5STAFF()->options()->get_option('single_layout');
		$classes[] = 'g5staff__single-' . $single_post_layout;
	}
	return $classes;
}
add_filter('body_class', 'g5staff_single_layout_class');

function g5staff_vc_map_add_narrow_group($args = array())
{
	$category = array();
	$categories = get_categories(array('hide_empty' => '1','taxonomy' => 'staff_group'));
	if (is_array($categories)) {
		foreach ($categories as $cat) {
			$category[$cat->name] = $cat->term_id;
		}
	}
	$default = array(
		'type' => 'g5element_selectize',
		'heading' => esc_html__('Narrow Groups', 'g5-staff'),
		'param_name' => 'cat',
		'value' => $category,
		'multiple' => true,
		'description' => esc_html__('Enter groups by names to narrow output (Note: only listed categories will be displayed, divide categories with linebreak (Enter)).', 'g5-staff'),
		'std' => ''
	);
	$default = array_merge($default, $args);
	return $default;
}

function g5staff_vc_map_add_filter() {
    return array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show', 'g5-staff'),
            'param_name' => 'show',
            'value' => array(
                esc_html__('All', 'g5-staff') => '',
                esc_html__('New In', 'g5-staff') => 'new-in',
                esc_html__('Narrow Staff', 'g5-staff') => 'staff'
            ),
            'std' => '',
            'group' => esc_html__('Staff Filter', 'g5-staff'),
        ),
	    g5staff_vc_map_add_narrow_group(array(
		    'dependency' => array('element' => 'show','value_not_equal_to' => array('staff')),
		    'group' => esc_html__('Staff Filter', 'g5-staff')
	    )),
        array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Narrow Staff', 'g5-staff' ),
            'param_name' => 'ids',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'unique_values' => true,
            ),
            'save_always' => true,
            'description' => esc_html__( 'Enter List of Staff', 'g5-staff' ),
            'dependency' => array('element' => 'show','value' => 'staff'),
            'group' => esc_html__('Staff Filter', 'g5-staff'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order by', 'g5-staff'),
            'param_name' => 'orderby',
            'value' => array_flip(G5STAFF()->settings()->get_staff_orderby()),
            'std' => 'date',
            'description' => esc_html__('Select how to sort retrieved staff.', 'g5-staff'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'group' => esc_html__('Staff Filter', 'g5-staff'),
        ),
        array(
            'type' => 'g5element_button_set',
            'heading' => esc_html__('Sorting', 'g5-staff'),
            'param_name' => 'order',
            'value' => array_flip(G5STAFF()->settings()->get_staff_order()),
            'std' => 'desc',
            'group' => esc_html__('Staff Filter', 'g5-staff'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'description' => esc_html__('Select sorting order.', 'g5-staff'),
        ),
    );
}

function g5staff_dropdown_groups( $args = array() ) {
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
			'selected'           => isset( $wp_query->query_vars['staff_group'] ) ? $wp_query->query_vars['staff_group'] : '',
			'show_option_none'   => __( 'Select a group', 'g5-staff' ),
			'option_none_value'  => '',
			'value_field'        => 'slug',
			'taxonomy'           => 'staff_group',
			'name'               => 'staff_group',
			'class'              => 'g5staff__dropdown_groups',
		)
	);

	if ( 'order' === $args['orderby'] ) {
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = 'order'; // phpcs:ignore
	}

	wp_dropdown_categories( $args );
}




function g5staff_single_share_enable() {
	$single_share_enable =  G5STAFF()->options()->get_option('single_share_enable');
	if ($single_share_enable !== 'on') return false;

	$social_share = G5STAFF()->options()->get_option('single_social_share');
	if (!is_array($social_share)) return false;
	unset($social_share['sort_order']);
	if (count($social_share) === 0) return false;
	return $social_share;
}

