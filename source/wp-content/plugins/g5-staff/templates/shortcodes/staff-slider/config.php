<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
return array(
    'base' => 'g5element_staff_slider',
    'name' => esc_html__('Staff Slider', 'g5-staff'),
    'description' => esc_html__( 'Display slider of staff', 'g5-staff' ),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-staff-slider',
    'params' => array_merge(
        array(
            array(
                'param_name' => 'cate_filter_enable',
                'heading' => esc_html__('Group Filter', 'g5-staff'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
	        array(
		        'param_name' => 'cate_filter_align',
		        'heading' => esc_html__('Group Filter Align', 'g5-services'),
		        'type' => 'g5element_button_set',
		        'value' => array_flip(G5CORE()->settings()->get_category_filter_align()),
		        'std' => '',
		        'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
	        ),
            array(
                'param_name' => 'item_skin',
                'heading' => esc_html__('Item Skin', 'g5-staff'),
                'description' => esc_html__('Specify your staff item skin.', 'g5-staff'),
                'type' => 'g5element_image_set',
                'value' => G5STAFF()->settings()->get_staff_skins(),
                'std' => 'skin-01',
                'admin_label' => true
            ),
            array(
                'param_name' => 'item_custom_class',
                'heading' => esc_html__( 'Item Css Classes', 'g5-staff' ),
                'description' => esc_html__( 'Add custom css classes to item', 'g5-staff' ),
                'type' => 'textfield'
            ),
	        array(
		        'param_name' => 'single_link_disable',
		        'heading' => esc_html__('Hide Single Link','g5-staff'),
		        'type' => 'g5element_switch',
		        'std' => '',
	        ),
	        array(
		        'param_name' => 'excerpt_enable',
		        'heading' => esc_html__('Show Excerpt','g5-staff'),
		        'type' => 'g5element_switch',
		        'std' => '',
	        ),
	        array(
		        'param_name' => 'social_profiles_enable',
		        'heading' => esc_html__('Show Social Profiles','g5-staff'),
		        'type' => 'g5element_switch',
		        'std' => 'on',
	        ),
            array(
                'param_name' => 'columns_gutter',
                'heading' => esc_html__('Columns Gutter', 'g5-staff'),
                'description' => esc_html__('Specify your horizontal space between staff.', 'g5-staff'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_post_columns_gutter()),
                'std' => '30',
            ),
            array(
                'param_name' => 'posts_per_page',
                'heading' => esc_html__('Staff Per Page', 'g5-staff'),
                'description' => esc_html__('Enter number of staff per page you want to display. Default 10', 'g5-staff'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'offset',
                'heading' => esc_html__('Offset posts', 'g5-staff'),
                'description' => esc_html__('Start the count with an offset. If you have a block that shows 4 posts before this one, you can make this one start from the 5\'th post (by using offset 4)', 'g5-staff'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'post_animation',
                'heading' => esc_html__('Animation', 'g5-staff'),
                'description' => esc_html__('Specify your portfolio animation', 'g5-staff'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_animation()),
                'std' => 'none'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __( 'Append Group', 'g5-staff' ),
                'param_name'  => 'append_tabs',
                'std'         => '',
                'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
                'description' => esc_html__( 'Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)', 'g5-staff' ),
            ),
            g5element_vc_map_add_element_id(),
            g5element_vc_map_add_extra_class(),
        ),
        g5staff_vc_map_add_filter(),
        g5element_vc_map_add_columns(array(), esc_html__('Columns', 'g5-staff')),
        g5element_vc_map_add_slider(array(), esc_html__('Slider Options', 'g5-staff')),
        array(
            array(
                'param_name' => 'post_image_size',
                'heading' => esc_html__('Image size', 'g5-staff'),
                'description' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 300x400).', 'g5-staff'),
                'type' => 'textfield',
                'std' => 'medium',
                'group' => esc_html__('Image Size', 'g5-staff'),
            ),
            array(
                'param_name' => 'post_image_ratio_width',
                'heading' => esc_html__('Image ratio width', 'g5-staff'),
                'description' => esc_html__('Enter width for image ratio', 'g5-staff'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-staff'),
            ),
            array(
                'param_name' => 'post_image_ratio_height',
                'heading' => esc_html__('Image ratio height', 'g5-staff'),
                'description' => esc_html__('Enter height for image ratio', 'g5-staff'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-staff'),
            ),
        ),
        array(
            g5element_vc_map_add_css_animation(),
            g5element_vc_map_add_animation_duration(),
            g5element_vc_map_add_animation_delay(),
            g5element_vc_map_add_css_editor(),
            g5element_vc_map_add_responsive(),
        )
    )
);