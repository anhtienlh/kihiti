<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
return array(
    'base' => 'g5element_careers',
    'name' => esc_html__('Careers', 'g5-careers'),
    'description' => esc_html__( 'Display list of careers', 'g5-careers' ),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-careers',
    'params' => array_merge(
        array(
	        array(
		        'type' => 'param_group',
		        'heading' => esc_html__('Table Columns', 'g5-careers'),
		        'param_name' => 'table_columns',
		        'value' => rawurlencode( wp_json_encode( array(
			        array(
				        'column' => 'title',
			        ),
			        array(
				        'column' => 'department',
			        ),
			        array(
				        'column' => 'expired_date',
			        ),
		        ) ) ),
		        'params' => array(
			        array(
				        'param_name' => 'column',
				        'heading' => esc_html__('Column', 'g5-careers'),
				        'description' => esc_html__('Specify your column', 'g5-careers'),
				        'type' => 'dropdown',
				        'value' => array_flip(G5CAREERS()->settings()->get_careers_columns_table()),
				        'admin_label' => true,
				        'std' => 'title'
			        ),
		        )
	        ),
	        array(
		        'type' => 'g5element_switch',
		        'heading' => esc_html__('Table Responsive', 'g5-careers'),
		        'description' => esc_html__('Turn Off this option if you want to disable responsive table on mobile', 'g5-careers'),
		        'param_name' => 'table_responsive',
		        'std' => 'on'
	        ),
            array(
                'param_name' => 'posts_per_page',
                'heading' => esc_html__('Careers Per Page', 'g5-careers'),
                'description' => esc_html__('Enter number of careers per page you want to display. Default 10', 'g5-careers'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'offset',
                'heading' => esc_html__('Offset posts', 'g5-careers'),
                'description' => esc_html__('Start the count with an offset. If you have a block that shows 4 posts before this one, you can make this one start from the 5\'th post (by using offset 4)', 'g5-careers'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'post_paging',
                'heading' => esc_html__('Paging', 'g5-careers'),
                'description' => esc_html__('Specify your post paging mode', 'g5-careers'),
                'type' => 'dropdown',
                'value' => array_flip(G5ELEMENT()->settings()->get_post_paging()),
                'std' => 'none'
            ),
            g5element_vc_map_add_element_id(),
            g5element_vc_map_add_extra_class(),
        ),
        g5careers_vc_map_add_filter(),
        array(
            g5element_vc_map_add_css_animation(),
            g5element_vc_map_add_animation_duration(),
            g5element_vc_map_add_animation_delay(),
            g5element_vc_map_add_css_editor(),
            g5element_vc_map_add_responsive(),
        )
    )
);