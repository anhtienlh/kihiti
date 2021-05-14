<?php
if ( ! class_exists( 'G5Core_Options_Abstract', false ) ) {
	G5CORE()->load_file( G5CORE()->plugin_dir( 'inc/abstract/options.class.php' ) );
}
if ( ! class_exists( 'G5Staff_Options' ) ) {
	class G5Staff_Options extends G5Core_Options_Abstract {
		protected $option_name = 'g5staff_options';

		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init_default() {
			return array(
				'group_filter_enable' => '',
				'group_filter_align' => '',
				'append_tabs'         => '',
				'post_layout'         => 'grid',
				'item_skin'           => 'skin-01',
				'item_custom_class'   => '',
				'post_columns_gutter' => '30',
				'post_columns_xl'     => '4',
				'post_columns_lg'     => '4',
				'post_columns_md'     => '3',
				'post_columns_sm'     => '2',
				'post_columns'        => '1',
				'post_image_size'     => '300x355',
				'post_image_ratio'    =>
					array(
						'width'  => '',
						'height' => '',
					),
				'archive_orderby' => 'date',
				'archive_order' => 'desc',
				'posts_per_page'      => '',
				'post_paging'         => 'pagination',
				'post_animation'      => 'none',
				'single_link_disable' => '',
				'excerpt_enable'      => '',
				'single_layout' => 'layout-1',
			);
		}
	}
}