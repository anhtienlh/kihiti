<?php
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists(' G5Element_Settings')) {
    class  G5Element_Settings
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function get_gallery_layout()
        {
            $config = apply_filters('g5element_settings_gallery_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-grid.png'),
                ),
                'masonry' => array(
                    'label' => esc_html__('Masonry', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-masonry.png'),
                ),
                'masonry-2' => array(
                    'label' => esc_html__('Masonry 2', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-masonry-02.png'),
                ),
                'justified' => array(
                    'label' => esc_html__('Justified', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-justified.jpg'),
                ),
                'metro-1' => array(
                    'label' => esc_html__('Metro 01', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-metro-01.png')
                ),
                'metro-2' => array(
                    'label' => esc_html__('Metro 02', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-metro-02.png')
                ),
                'metro-3' => array(
                    'label' => esc_html__('Metro 03', 'g5-element'),
                    'img' => G5ELEMENT()->plugin_url('assets/images/gallery-layout-metro-03.png')
                ),

            ));
            return $config;
        }

        public function get_post_paging($inherit = false)
        {
            $config = apply_filters('g5element_settings_post_paging', array(
                'none' => esc_html__('None', 'g5-element'),
                'pagination-ajax' => esc_html__('Ajax - Pagination', 'g5-element'),
                'next-prev' => esc_html__('Ajax - Next Prev', 'g5-element'),
                'load-more' => esc_html__('Ajax - Load More', 'g5-element'),
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-element')
                    ) + $config;
            }

            return $config;
        }

    }
}