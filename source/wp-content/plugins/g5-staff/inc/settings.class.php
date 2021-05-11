<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Staff_Settings' ) ) {
	class G5Staff_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function get_staff_layout($inherit = false)
        {
            $config = apply_filters('g5staff_options_staff_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-staff'),
                    'img' => G5STAFF()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-staff'),
                            'img' => G5STAFF()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_staff_skins($inherit = false)
        {
            $config = apply_filters('g5staff_options_staff_skins', array(
                'skin-01' => array(
                    'label' => esc_html__('Skin 01', 'g5-staff'),
                    'img' => G5STAFF()->plugin_url('assets/images/theme-options/skin-01.png'),
                ),
                'skin-02' => array(
                    'label' => esc_html__('Skin 02', 'g5-staff'),
                    'img' => G5STAFF()->plugin_url('assets/images/theme-options/skin-02.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-staff'),
                            'img' => G5STAFF()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


		public function get_staff_orderby() {
			return apply_filters('g5staff_options_staff_orderby',array(
				'date' => esc_html__( 'Date', 'g5-staff' ),
				'title' => esc_html__( 'Title', 'g5-staff' ),
				'rand' => esc_html__( 'Random', 'g5-staff' ),
				'menu_order' => esc_html__( 'Menu Order', 'g5-staff' )
			));
		}

		public function get_staff_order() {
			return apply_filters('g5staff_options_staff_order',array(
				'asc' => esc_html__( 'ASC', 'g5-staff' ),
				'desc' => esc_html__( 'DESC', 'g5-staff' )
			));
		}

		public function get_single_layout($inherit = false)
		{
			$config = apply_filters('g5staff_options_single_layout', array(
				'layout-1' => array(
					'label' => esc_html__('Layout 01', 'g5-staff'),
					'img' => G5STAFF()->plugin_url('assets/images/theme-options/single-layout-01.png')
				),
				'layout-2' => array(
					'label' => esc_html__('Layout 02', 'g5-staff'),
					'img' => G5STAFF()->plugin_url('assets/images/theme-options/single-layout-02.png')
				),
			));
			if ($inherit) {
				$config = array(
					          '' => array(
						          'label' => esc_html__('Inherit', 'g5-staff'),
						          'img' => G5STAFF()->plugin_url('assets/images/theme-options/default.png'),
					          ),
				          ) + $config;
			}
			return $config;
		}

		public function get_staff_info_default() {
			return apply_filters('g5staff_options_staff_info_default', array(
				array(
					'title' => esc_html__('Phone Number','g5-staff'),
					'value' => ''
				),
				array(
					'title' => esc_html__('Email','g5-staff'),
					'value' => ''
				),
				array(
					'title' => esc_html__('Address','g5-staff'),
					'value' => ''
				)
			));
		}
	}
}