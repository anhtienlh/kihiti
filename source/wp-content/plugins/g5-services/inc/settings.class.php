<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Services_Settings' ) ) {
	class G5Services_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function get_services_layout($inherit = false)
        {
            $config = apply_filters('g5services_options_services_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-services'),
                    'img' => G5SERVICES()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-services'),
                            'img' => G5SERVICES()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_services_skins($inherit = false)
        {
            $config = apply_filters('g5services_options_services_skins', array(
                'skin-01' => array(
                    'label' => esc_html__('Skin 01', 'g5-services'),
                    'img' => G5SERVICES()->plugin_url('assets/images/theme-options/skin-01.png'),
                ),
                'skin-02' => array(
                    'label' => esc_html__('Skin 02', 'g5-services'),
                    'img' => G5SERVICES()->plugin_url('assets/images/theme-options/skin-02.png'),
                ),
                'skin-03' => array(
                    'label' => esc_html__('Skin 03', 'g5-services'),
                    'img' => G5SERVICES()->plugin_url('assets/images/theme-options/skin-03.png'),
                ),
                'skin-04' => array(
                    'label' => esc_html__('Skin 04', 'g5-services'),
                    'img' => G5SERVICES()->plugin_url('assets/images/theme-options/skin-04.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-services'),
                            'img' => G5SERVICES()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_single_related_algorithm($inherit = false)
        {
            $config = apply_filters('g5services_options_single_related_algorithm', array(
                'cat' => esc_html__('by Category', 'g5-services'),
                'author' => esc_html__('by Author', 'g5-services'),
                'cat-author' => esc_html__('by Category & Author', 'g5-services'),
                'random' => esc_html__('Randomly', 'g5-services')
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-services')
                    ) + $config;
            }

            return $config;

        }

		public function get_services_orderby() {
			return apply_filters('g5services_options_service_orderby',array(
				'date' => esc_html__( 'Date', 'g5-services' ),
				'title' => esc_html__( 'Title', 'g5-services' ),
				'rand' => esc_html__( 'Random', 'g5-services' ),
				'menu_order' => esc_html__( 'Menu Order', 'g5-services' )
			));
		}

		public function get_services_order() {
			return apply_filters('g5services_options_service_order',array(
				'asc' => esc_html__( 'ASC', 'g5-services' ),
				'desc' => esc_html__( 'DESC', 'g5-services' )
			));
		}
	}
}