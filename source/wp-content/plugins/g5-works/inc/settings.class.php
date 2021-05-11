<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Works_Settings' ) ) {
	class G5Works_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function get_works_layout($inherit = false)
        {
            $config = apply_filters('g5works_options_works_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-works'),
                    'img' => G5WORKS()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-works'),
                            'img' => G5WORKS()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_works_skins($inherit = false)
        {
            $config = apply_filters('g5works_options_works_skins', array(
	            'skin-01' => array(
		            'label' => esc_html__('Skin 01', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-01.png'),
	            ),
	            'skin-02' => array(
		            'label' => esc_html__('Skin 02', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-02.png'),
	            ),
	            'skin-03' => array(
		            'label' => esc_html__('Skin 03', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-03.png'),
	            ),
	            'skin-04' => array(
		            'label' => esc_html__('Skin 04', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-04.png'),
	            ),
	            'skin-05' => array(
		            'label' => esc_html__('Skin 05', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-05.png'),
	            ),
	            'skin-06' => array(
		            'label' => esc_html__('Skin 06', 'g5-works'),
		            'img' => G5WORKS()->plugin_url('assets/images/theme-options/skin-06.png'),
	            ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-works'),
                            'img' => G5WORKS()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_single_related_algorithm($inherit = false)
        {
            $config = apply_filters('g5works_options_single_related_algorithm', array(
                'cat' => esc_html__('by Category', 'g5-works'),
                'author' => esc_html__('by Author', 'g5-works'),
                'cat-author' => esc_html__('by Category & Author', 'g5-works'),
                'random' => esc_html__('Randomly', 'g5-works')
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-works')
                    ) + $config;
            }

            return $config;

        }

		public function get_works_orderby() {
			return apply_filters('g5works_options_works_orderby',array(
				'date' => esc_html__( 'Date', 'g5-works' ),
				'title' => esc_html__( 'Title', 'g5-works' ),
				'rand' => esc_html__( 'Random', 'g5-works' ),
				'menu_order' => esc_html__( 'Menu Order', 'g5-works' )
			));
		}

		public function get_works_order() {
			return apply_filters('g5works_options_works_order',array(
				'asc' => esc_html__( 'ASC', 'g5-works' ),
				'desc' => esc_html__( 'DESC', 'g5-works' )
			));
		}
	}
}