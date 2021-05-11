<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!class_exists('G5ThemeAddons_Services')) {
	class G5ThemeAddons_Services {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function init() {
			add_action( 'g5services_loop_post_content', array( $this, 'template_loop_read_more' ), 20 );

			add_action('init',array($this,'change_template_single_share'));

			add_filter('g5services_single_navigation_args',array($this,'change_single_navigation_args'));

			add_filter('g5core_page_title',array($this,'page_title'),11);

			add_filter('g5core_default_options_g5services_options', array($this, 'change_default_options'));

			add_filter('g5services_single_related_layout_setting',array($this,'change_single_related_layout_setting'));

			add_filter('g5services_options_services_skins',array($this,'thallo_options_services_skins'));

			add_action('template_redirect', array($this,'demo_layout') ,15);
			add_action( 'pre_get_posts', array( $this, 'demo_post_per_pages' ), 15 );

		}

		public function template_loop_read_more() {
			GTA()->get_template( 'services/loop/read-more.php' );
		}

		public function change_template_single_share() {
			remove_action( 'g5services_after_single_content', 'g5services_template_single_share', 10 );
			add_action( 'g5services_after_single', 'g5services_template_single_share', 5 );

			$single_share = g5services_single_share_enable();
			$single_navigation =  g5services_single_navigation_enable();
			if ($single_share || $single_navigation) {
				add_action('g5services_after_single',array($this,'template_sìngle_share_wrap_start'),1);
				add_action('g5services_after_single',array($this,'template_sìngle_share_wrap_end'),11);
			}
		}

		public function template_sìngle_share_wrap_start() {
			echo '<div class="g5services__single-share-wrap">';
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

		public function page_title($page_title) {

			if (g5services_is_single()) {
				return get_the_title();
			}

			return $page_title;
		}

		public function change_default_options($defaults) {
			return wp_parse_args(array(
				'item_skin' => 'skin-03',
				'post_image_size' => '370x240'
			),$defaults);
		}

		public function change_single_related_layout_setting($setting) {
			return wp_parse_args(array('item_skin' => 'skin-03','image_size' => '370x240'),$setting);
		}

		public function thallo_options_services_skins($config)
		{
			$config['skin-05'] = array(
				'label' => esc_html__('Skin 05', 'thallo-addons'),
				'img'   => GTA()->plugin_url( 'assets/images/services-skin-05.png' )
			);
			return $config;
		}

		public function demo_layout() {
			if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5SERVICES' ) ) {
				return;
			}

			$services_layout = isset( $_REQUEST['services_layout'] ) ? $_REQUEST['services_layout'] : '';

			if ( ! empty( $services_layout ) ) {
				$ajax_query                = G5CORE()->cache()->get( 'g5core_ajax_query', array() );
				$ajax_query['services_layout'] = $services_layout;
				G5CORE()->cache()->set( 'g5core_ajax_query', $ajax_query );
			}

			switch ( $services_layout ) {
				case 'list':
					G5SERVICES()->options()->set_option('item_skin','skin-05');
					G5SERVICES()->options()->set_option('post_columns_xl','1');
					G5SERVICES()->options()->set_option('post_columns_lg','1');
					G5SERVICES()->options()->set_option('post_columns_md','1');
					G5SERVICES()->options()->set_option('post_columns_sm','1');
					G5SERVICES()->options()->set_option('post_columns','1');
					G5SERVICES()->options()->set_option('post_image_size','300x230');
					G5CORE()->options()->layout()->set_option('site_layout','right');
					break;
			}
		}

		public function demo_post_per_pages($query) {
			if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5SERVICES' ) ) {
				return;
			}
			if ( ! is_admin() && $query->is_main_query() ) {
				$services_layout = isset( $_REQUEST['services_layout'] ) ? $_REQUEST['services_layout'] : '';
				if ( empty( $services_layout ) ) {
					return;
				}

				switch ( $services_layout ) {
					case 'list':
						$query->set( 'posts_per_page', 6 );
						break;
				}
			}
		}
	}
}