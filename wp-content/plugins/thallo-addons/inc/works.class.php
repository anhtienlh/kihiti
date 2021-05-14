<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!class_exists('G5ThemeAddons_Works')) {
	class G5ThemeAddons_Works {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_filter('g5core_default_options_g5works_options', array($this, 'change_default_options'));
			add_filter('g5works_options_works_skins',array($this,'change_options_works_skins'));
			add_filter('g5works_options_works_skins',array($this,'options_works_skins_08'));

			add_filter('g5core_page_title',array($this,'page_title'));

			add_action('init',array($this,'change_template_single_share'));

			add_filter('g5works_single_navigation_args',array($this,'change_single_navigation_args'));

			add_filter('g5works_single_related_layout_setting',array($this,'change_single_related_layout_setting'));
		}

		function change_default_options($defaults) {
			return wp_parse_args(array(
				'item_skin' => 'skin-03',
				'post_image_size' => '570x320',
				'post_columns_xl' => 2,
				'post_columns_lg' => 2
			),$defaults);
		}


		public function change_options_works_skins($skins) {
			return wp_parse_args(array(
				'skin-07' => array(
					'label' => esc_html__('Skin 07', 'thallo-addons'),
					'img' => GTA()->plugin_url('assets/images/works-skin-01.png'),
				)
			),$skins);
		}
		public function options_works_skins_08($skins) {
			return wp_parse_args(array(
				'skin-08' => array(
					'label' => esc_html__('Skin 08', 'thallo-addons'),
					'img' => GTA()->plugin_url('assets/images/works-skin-02.png'),
				)
			),$skins);
		}

		public function page_title($page_title) {
			if (g5works_is_single()) {
				$page_title = get_the_title();
			}
			return $page_title;
		}

		public function change_template_single_share() {
			remove_action( 'g5works_after_single_content', 'g5works_template_single_share', 10 );
			add_action( 'g5works_after_single', 'g5works_template_single_share', 5 );

			$single_share = g5works_single_share_enable();
			$single_navigation =  g5works_single_navigation_enable();
			if ($single_share || $single_navigation) {
				add_action('g5works_after_single',array($this,'template_sìngle_share_wrap_start'),1);
				add_action('g5works_after_single',array($this,'template_sìngle_share_wrap_end'),11);
			}
		}

		public function template_sìngle_share_wrap_start() {
			echo '<div class="g5works__single-share-wrap">';
		}

		public function template_sìngle_share_wrap_end() {
			echo '</div>';
		}

		public function change_single_navigation_args($args) {
			return wp_parse_args(array(
				'prev_text' => '<i class="fas fa-long-arrow-left"></i>',
				'next_text' => '<i class="fas fa-long-arrow-right"></i>',
				'archive_link_enable' => false
			),$args);
		}

		public function change_single_related_layout_setting($setting) {
			return wp_parse_args(array(
				'item_skin' => 'skin-03',
				'image_size' => '570x320',
				'single_related_columns_xl' => 2,
				'single_related_columns_lg' =>  2
			),$setting);
		}
	}
}