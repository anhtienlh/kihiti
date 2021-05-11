<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
if (!class_exists('G5ThemeAddons_Portfolio')) {
	class G5ThemeAddons_Portfolio {
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init()
		{
			add_action('init',array($this,'change_template_loop_cate'));
			add_filter('g5core_default_options_g5portfolio_options', array($this, 'change_default_options'));

			add_filter('g5portfolio_options_portfolio_layout',array($this,'change_options_portfolio_layout'));

			add_filter('g5portfolio_config_layout_matrix',array($this,'change_config_layout_matrix'),10,2);

			add_action('init',array($this,'change_template_single_share'));
			add_filter('g5portfolio_single_navigation_args',array($this,'change_single_navigation_args'));
			add_filter('g5portfolio_single_related_layout_setting',array($this,'change_single_related_layout_setting'));



		}

		public function change_template_loop_cate() {
			remove_action('g5portfolio_loop_post_content','g5portfolio_template_loop_cat',5);
			add_action('g5portfolio_loop_post_content','g5portfolio_template_loop_cat',11);
		}

		public function change_default_options($defaults) {
			return wp_parse_args(array(
				'category_filter_enable' => 'on',
				'item_skin' => 'skin-02',
				'post_image_size' => '370x470'
			),$defaults);
		}

		public function change_options_portfolio_layout($config) {
			return wp_parse_args(array(
				'thallo-01' => array(
					'label' => esc_html__('Thallo 01', 'thallo-addons'),
					'img' => GTA()->plugin_url('assets/images/portfolio-layout-metro-01.png')
				)
			),$config);
		}

		public function change_config_layout_matrix($configs,$item_skin) {
			return wp_parse_args(array(
				'thallo-01' => array(
					'isotope' => array(
						'itemSelector' => 'article',
						'layoutMode' => 'masonry',
						'percentPosition' => true,
						'masonry' => array(
							'columnWidth' => '.g5core__col-base',
						),
						'metro' => true
					),
					'layout' => array(
						array('columns' => 'col-lg-7', 'template' => $item_skin, 'layout_ratio' => '1.4x2'),
						array('columns' => 'col-lg-5', 'template' => $item_skin, 'layout_ratio' => '1x1'),
						array('columns' => 'col-lg-5', 'template' => $item_skin, 'layout_ratio' => '1x1'),

						array('columns' => 'col-lg-5', 'template' => $item_skin, 'layout_ratio' => '1x1'),
						array('columns' => 'col-lg-7', 'template' => $item_skin, 'layout_ratio' => '1.4x2'),
						array('columns' => 'col-lg-5', 'template' => $item_skin, 'layout_ratio' => '1x1'),
					)
				),
			),$configs);
		}

		public function change_template_single_share() {
			remove_action( 'g5portfolio_before_single_meta', 'g5portfolio_template_single_share', 30 );
			add_action( 'g5portfolio_after_single', 'g5portfolio_template_single_share', 5 );


			$single_share = g5portfolio_single_share_enable();
			$single_navigation =  g5portfolio_single_navigation_enable();
			if ($single_share || $single_navigation) {
				add_action('g5portfolio_after_single',array($this,'template_sìngle_share_wrap_start'),1);
				add_action('g5portfolio_after_single',array($this,'template_sìngle_share_wrap_end'),11);
			}

		}

		public function template_sìngle_share_wrap_start() {
			echo '<div class="g5portfolio__single-share-wrap">';
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
				'item_skin' => 'skin-02',
			),$setting);
		}


	}
}