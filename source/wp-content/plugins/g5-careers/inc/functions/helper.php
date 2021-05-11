<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}


function g5careers_vc_map_add_filter() {
    return array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show', 'g5-careers'),
            'param_name' => 'show',
            'value' => array(
                esc_html__('All', 'g5-careers') => '',
                esc_html__('New In', 'g5-careers') => 'new-in',
                esc_html__('Narrow Careers', 'g5-careers') => 'careers'
            ),
            'std' => '',
            'group' => esc_html__('Careers Filter', 'g5-careers'),
        ),
        array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Narrow Careers', 'g5-careers' ),
            'param_name' => 'ids',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'unique_values' => true,
            ),
            'save_always' => true,
            'description' => esc_html__( 'Enter List of Careers', 'g5-careers' ),
            'dependency' => array('element' => 'show','value' => 'careers'),
            'group' => esc_html__('Careers Filter', 'g5-careers'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order by', 'g5-careers'),
            'param_name' => 'orderby',
            'value' => array_flip(G5CAREERS()->settings()->get_careers_orderby()),
            'std' => 'date',
            'description' => esc_html__('Select how to sort retrieved careers.', 'g5-careers'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'group' => esc_html__('Careers Filter', 'g5-careers'),
        ),
        array(
            'type' => 'g5element_button_set',
            'heading' => esc_html__('Sorting', 'g5-careers'),
            'param_name' => 'order',
            'value' => array_flip(G5CAREERS()->settings()->get_careers_order()),
            'std' => 'desc',
            'group' => esc_html__('Careers Filter', 'g5-careers'),
            'dependency' => array('element' => 'show','value' => array('', 'featured')),
            'description' => esc_html__('Select sorting order.', 'g5-careers'),
        ),
    );
}

function g5careers_single_share_enable() {
	$single_share_enable =  G5CAREERS()->options()->get_option('single_share_enable');
	if ($single_share_enable !== 'on') return false;
	return g5core_get_social_share();
}



function g5careers_get_careers_info($is_single = false) {
	$prefix = G5CAREERS()->meta_prefix;
	$department = get_post_meta(get_the_ID(),"{$prefix}department",true);
	$location = get_post_meta(get_the_ID(),"{$prefix}location",true);
	$salary = get_post_meta(get_the_ID(),"{$prefix}salary",true);
	$expired_date = get_post_meta(get_the_ID(),"{$prefix}expired_date",true);
	$items = apply_filters('g5careers_loop_meta_items',array(
		array(
			'id' => 'department',
			'title' => esc_html__('Department','g5-careers'),
			'content' => $department,
			'priority' => 0,
		),
		array(
			'id' => 'location',
			'title' => esc_html__('Location','g5-careers'),
			'content' => $location,
			'priority' => 10,
		),
		array(
			'id' => 'salary',
			'title' => esc_html__('Salary','g5-careers'),
			'content' => $salary,
			'priority' => 20,
		),
		array(
			'id' => 'expired_date',
			'title' => esc_html__('Expired Date','g5-careers'),
			'content' => $expired_date,
			'priority' => 30000,
		),
	));

	if ($is_single) {
		$additional_details = get_post_meta(get_the_ID(),"{$prefix}additional_details",true);
		$additional_details = !is_array($additional_details) ? array($additional_details) : $additional_details;
		$priority = 30;
		foreach ($additional_details as $additional_detail) {
			if (isset($additional_detail['title']) && $additional_detail['title'] !== '' && isset($additional_detail['value']) && $additional_detail['value'] !== '') {
				$items[] = array(
					'id' =>  sanitize_title($additional_detail['title']),
					'title' => $additional_detail['title'],
					'content' => $additional_detail['value'],
					'priority' => $priority
				);
				$priority += 5;
			}
		}
	}
	$items = apply_filters('g5careers_loop_meta_items',$items,$is_single);
	uasort($items, 'g5core_sort_by_order_callback');
	return $items;
}