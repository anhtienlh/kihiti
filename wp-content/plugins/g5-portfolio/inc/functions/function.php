<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
G5PORTFOLIO()->load_file(G5PORTFOLIO()->plugin_dir('inc/functions/conditional.php'));
G5PORTFOLIO()->load_file(G5PORTFOLIO()->plugin_dir('inc/functions/helper.php'));
G5PORTFOLIO()->load_file(G5PORTFOLIO()->plugin_dir('inc/functions/template.php'));

/*add_filter('vc_load_default_templates','your_custom_function');
function your_custom_function() {
    return array(
      array(
          'name' => esc_html__('My custom template','my_plugin'),
          'image_path' => '',
          'custom_class' => 'my_custom_class',
          'content' => '[vc_row][vc_column][vc_column_text css_animation="bounceInRight"]I am text block.[/vc_column_text][/vc_column][/vc_row]'
      )
    );
}*/

