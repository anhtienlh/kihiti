<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5ThemeAddons_Element' ) ) {
	class G5ThemeAddons_Element {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter( 'g5element_settings_image_box_layout', array( $this, 'add_layout_image_box' ) );

			add_filter( 'g5element_locate_template', array( $this, 'add_image_box_template' ), 10, 4 );
		}

		public function add_layout_image_box( $layout ) {
			return wp_parse_args( array(
				'style-08' => array(
					'label' => esc_html__( 'Style 08', 'thallo-addons' ),
					'img'   => GTA()->plugin_url( 'assets/images/image-box-style-08.jpg' ),
				),
			), $layout );
		}

		public function add_image_box_template( $located, $template_name, $args ) {
			if ( $template_name === 'image-box/style-08.php' ) {
				return GTA()->locate_template( 'element/image-box/style-08.php' );
			}

			return $located;
		}
	}
}