<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
return array(
    'base' => 'g5element_single_careers_meta',
    'name' => esc_html__('Careers Single Meta', 'g5-careers'),
    'description' => esc_html__( 'Display meta of single careers', 'g5-careers' ),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-single-careers-meta',
    'params' => array_merge(
        array(
            g5element_vc_map_add_css_animation(),
            g5element_vc_map_add_animation_duration(),
            g5element_vc_map_add_animation_delay(),
            g5element_vc_map_add_css_editor(),
            g5element_vc_map_add_responsive(),
        )
    )
);