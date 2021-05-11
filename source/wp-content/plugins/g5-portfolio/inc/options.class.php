<?php
if ( ! class_exists( 'G5Core_Options_Abstract', false ) ) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/options.class.php'));
}
if (!class_exists('G5Portfolio_Options')) {
	class G5Portfolio_Options extends G5Core_Options_Abstract {
		protected $option_name = 'g5portfolio_options';

		private static $_instance;
		public static function getInstance() {
			if (self::$_instance == NULL) { self::$_instance = new self(); }
			return self::$_instance;
		}

		public function init_default() {
			return array (
                'category_filter_enable' => '',
                'category_filter_align' => '',
                'append_tabs' => '',
                'post_layout' => 'grid',
                'item_skin' => 'skin-01',
                'item_custom_class' => '',
                'post_columns_gutter' => '30',
                'post_columns_xl' => '3',
                'post_columns_lg' => '3',
                'post_columns_md' => '2',
                'post_columns_sm' => '2',
                'post_columns' => '1',
                'post_image_size' => '300x355',
                'post_image_width' =>
                    array (
                        'width' => '400',
                        'height' => '',
                    ),
                'post_image_ratio' =>
                    array (
                        'width' => '',
                        'height' => '',
                    ),
                'justified_row_height' =>
                    array (
                        'width' => '',
                        'height' => '200',
                    ),
                'justified_row_max_height' =>
                    array (
                        'width' => '',
                        'height' => '',
                    ),
                'archive_orderby' => 'date',
                'archive_order' => 'desc',
                'posts_per_page' => '',
                'post_paging' => 'pagination',
                'post_animation' => 'none',
                'light_box_mode' => 'feature',
                'category_enable' => 'on',
                'excerpt_enable' => '',
                'single_layout' => 'layout-1',
                'single_gallery_layout' => 'slider',
                'single_gallery_justified_row_height' =>
                    array (
                        'width' => '',
                        'height' => '200',
                    ),
                'single_gallery_justified_row_max_height' =>
                    array (
                        'width' => '',
                        'height' => '',
                    ),
                'single_gallery_carousel_center_enable' => '',
                'single_gallery_carousel_center_padding' => '',
                'single_gallery_image_size' => 'large',
                'single_gallery_image_ratio' =>
                    array (
                        'width' => '',
                        'height' => '',
                    ),
                'single_gallery_image_width' =>
                    array (
                        'width' => '400',
                        'height' => '',
                    ),
                'single_gallery_columns_gutter' => '30',
                'single_gallery_columns_xl' => '3',
                'single_gallery_columns_lg' => '3',
                'single_gallery_columns_md' => '2',
                'single_gallery_columns_sm' => '2',
                'single_gallery_columns' => '1',
                'single_gallery_custom_class' => '',
                'single_navigation_enable' => 'on',
                'comment_enable' => '',
                'single_share_enable' => 'on',
                'single_related_enable' => 'on',
                'single_related_algorithm' => 'cat',
                'single_related_per_page' => '6',
                'single_related_columns_gutter' => '30',
                'single_related_columns_xl' => '3',
                'single_related_columns_lg' => '3',
                'single_related_columns_md' => '2',
                'single_related_columns_sm' => '2',
                'single_related_columns' => '1',
                'single_related_paging' => 'slider',
            );
		}
	}
}