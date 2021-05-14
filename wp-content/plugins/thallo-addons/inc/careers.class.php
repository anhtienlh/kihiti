<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!class_exists('G5ThemeAddons_Careers')) {
	class G5ThemeAddons_Careers {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter('g5core_default_options_g5careers_options', array($this,'change_default_options'), 11 );
		}

		public function change_default_options($defaults) {
			return wp_parse_args(array(
				'single_title_enable' => '',
				'single_meta_enable' => '',
			),$defaults) ;
		}
	}
}