<?php
if ( ! class_exists( 'G5Core_Options_Abstract', false ) ) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/options.class.php'));
}
if (!class_exists('G5Careers_Options')) {
	class G5Careers_Options extends G5Core_Options_Abstract {
		protected $option_name = 'g5careers_options';

		private static $_instance;
		public static function getInstance() {
			if (self::$_instance == NULL) { self::$_instance = new self(); }
			return self::$_instance;
		}

		public function init_default() {
			return array (
				'archive_table_columns' => 	array(
					'title',
					'department',
					'expired_date'
				),
				'archive_table_responsive' => 'on',
				'archive_orderby' => 'date',
				'archive_order' => 'desc',
				'posts_per_page' => '',
				'post_paging' => 'pagination',
				'single_title_enable' => 'on',
				'single_meta_enable' => 'on',
				'single_share_enable' => 'on',
            );
		}
	}
}