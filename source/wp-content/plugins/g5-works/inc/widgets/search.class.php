<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Works_Widget_Search')) {
    class G5Works_Widget_Search extends GSF_Widget {
        public function __construct()
        {
            $this->widget_cssclass = 'g5works__widget-search widget_search';
            $this->widget_id = 'g5works__widget_search';
            $this->widget_name = esc_html__('G5Plus: Works Search', 'g5-works');
            $this->widget_description = esc_html__( 'A search form for your works.', 'g5-works' );
            $this->settings = array(
                'fields' => array(
                    array(
                        'id'      => 'title',
                        'title'   => esc_html__('Title', 'g5-works'),
                        'type'    => 'text',
                        'default' => '',
                    )
                )
            );
            parent::__construct();
        }

        function widget($args, $instance)
        {
            if ($this->get_cached_widget($instance)) {
                return;
            }
            extract($args, EXTR_SKIP);

            ob_start();
            $this->widget_start($args,$instance);
            g5works_template_search_form();
            $this->widget_end($args);
            echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
        }
    }
}