<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!class_exists('G5ThemeAddons_Staff')) {
	class G5ThemeAddons_Staff {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter('g5core_default_options_g5staff_options', array($this, 'change_default_options'));
			add_filter('g5staff_options_staff_skins',array($this,'change_options_staff_skins'));
			add_filter('g5staff_options_staff_info_default',array($this,'change_options_staff_info_default'));
		}

		function change_default_options($defaults) {
			return wp_parse_args(array(
				'item_skin' => 'skin-02',
				'post_image_size' => '220x220'
			),$defaults);
		}

		public function change_options_staff_skins($skins) {
			return wp_parse_args(array(
				'skin-01' => array(
					'label' => esc_html__('Skin 01', 'thallo-addons'),
					'img' => GTA()->plugin_url('assets/images/staff-skin-01.png'),
				),
				'skin-02' => array(
					'label' => esc_html__('Skin 02', 'thallo-addons'),
					'img' => GTA()->plugin_url('assets/images/staff-skin-02.png'),
				),
			),$skins);
		}

		public function change_options_staff_info_default() {
			return array(
				array(
					'title' => esc_html__('Project Completed','thallo-addons'),
					'value' => ''
				),
				array(
					'title' => esc_html__('Experience','thallo-addons'),
					'value' => ''
				),
				array(
					'title' => esc_html__('Phone','thallo-addons'),
					'value' => ''
				),
				array(
					'title' => esc_html__('Mail','thallo-addons'),
					'value' => ''
				)
			);
		}

	}
}