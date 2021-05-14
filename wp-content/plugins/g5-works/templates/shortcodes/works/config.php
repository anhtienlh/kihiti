<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
return array(
    'base' => 'g5element_works',
    'name' => esc_html__('Works', 'g5-works'),
    'description' => esc_html__( 'Display list of works', 'g5-works' ),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-works',
    'params' => array_merge(
        array(
            array(
                'param_name' => 'cate_filter_enable',
                'heading' => esc_html__('Category Filter', 'g5-works'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
	        array(
		        'param_name' => 'cate_filter_align',
		        'heading' => esc_html__('Category Filter Align', 'g5-works'),
		        'type' => 'g5element_button_set',
		        'value' => array_flip(G5CORE()->settings()->get_category_filter_align()),
		        'std' => '',
		        'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
	        ),
            array(
                'param_name' => 'post_layout',
                'heading' => esc_html__('Layout', 'g5-works'),
                'description' => esc_html__('Specify your works layout', 'g5-works'),
                'type' => 'g5element_image_set',
                'value' => G5WORKS()->settings()->get_works_layout(),
                'std' => 'grid',
                'admin_label' => true
            ),
            array(
                'param_name' => 'item_skin',
                'heading' => esc_html__('Item Skin', 'g5-works'),
                'type' => 'g5element_image_set',
                'value' => G5WORKS()->settings()->get_works_skins(),
                'std' => 'skin-01',
                'admin_label' => true
            ),
            array(
                'param_name' => 'item_custom_class',
                'heading' => esc_html__( 'Item Css Classes', 'g5-works' ),
                'description' => esc_html__( 'Add custom css classes to item', 'g5-works' ),
                'type' => 'textfield'
            ),
            array(
                'param_name' => 'category_enable',
                'heading' => esc_html__('Show Category','g5-works'),
                'type' => 'g5element_switch',
                'std' => 'on',
            ),
            array(
                'param_name' => 'excerpt_enable',
                'heading' => esc_html__('Show Excerpt','g5-works'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
            array(
                'param_name' => 'columns_gutter',
                'heading' => esc_html__('Columns Gutter', 'g5-works'),
                'description' => esc_html__('Specify your horizontal space between works.', 'g5-works'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_post_columns_gutter()),
                'std' => '30',
            ),
            array(
                'param_name' => 'posts_per_page',
                'heading' => esc_html__('Works Per Page', 'g5-works'),
                'description' => esc_html__('Enter number of works per page you want to display. Default 10', 'g5-works'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'offset',
                'heading' => esc_html__('Offset posts', 'g5-works'),
                'description' => esc_html__('Start the count with an offset. If you have a block that shows 4 posts before this one, you can make this one start from the 5\'th post (by using offset 4)', 'g5-works'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'post_paging',
                'heading' => esc_html__('Paging', 'g5-works'),
                'description' => esc_html__('Specify your post paging mode', 'g5-works'),
                'type' => 'dropdown',
                'value' => array_flip(G5ELEMENT()->settings()->get_post_paging()),
                'std' => 'none'
            ),
            array(
                'param_name' => 'post_animation',
                'heading' => esc_html__('Animation', 'g5-works'),
                'description' => esc_html__('Specify your works animation', 'g5-works'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_animation()),
                'std' => 'none'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __( 'Append Categories', 'g5-works' ),
                'param_name'  => 'append_tabs',
                'std'         => '',
                'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
                'description' => esc_html__( 'Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)', 'g5-works' ),
            ),
            g5element_vc_map_add_element_id(),
            g5element_vc_map_add_extra_class(),
        ),
        g5works_vc_map_add_filter(),
        g5element_vc_map_add_columns(array(), esc_html__('Columns', 'g5-works')),
        array(
            array(
                'param_name' => 'post_image_size',
                'heading' => esc_html__('Image size', 'g5-works'),
                'description' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 300x400).', 'g5-works'),
                'type' => 'textfield',
                'std' => 'medium',
                'dependency' => array('element' => 'post_layout', 'value_not_equal_to' => array('masonry','justified')),
                'group' => esc_html__('Image Size', 'g5-works'),
            ),
            array(
                'param_name' => 'post_image_ratio_width',
                'heading' => esc_html__('Image ratio width', 'g5-works'),
                'description' => esc_html__('Enter width for image ratio', 'g5-works'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-works'),
            ),
            array(
                'param_name' => 'post_image_ratio_height',
                'heading' => esc_html__('Image ratio height', 'g5-works'),
                'description' => esc_html__('Enter height for image ratio', 'g5-works'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-works'),
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