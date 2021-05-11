<?php
add_filter( 'g5core_theme_font_default', 'thallo_font_default' );
function thallo_font_default() {
	return array(
		array(
			'family'   => 'Open Sans',
			'kind'     => 'webfonts#webfont',
			'variants' => array(
				"300italic",
				"300",
				"400italic",
				"400",
				"600italic",
				"600",
				"700italic",
				"700",
				"800italic",
				"800",
			),
		),
		array(
			'family'   => 'Work Sans',
			'kind'     => 'webfonts#webfont',
			'variants' => array(
				"100",
				"200",
				"300",
				"400",
				"500",
				"600",
				"700",
				"800",
				"900",
			),
		),
	);
}

if (!class_exists('THALLO_SETUP_DATA')) {
	class THALLO_SETUP_DATA {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_filter('g5core_header_options', array($this, 'change_g5core_header_options_config'), 20);

			add_filter('g5core_default_options_g5core_header_options', array($this, 'change_default_options_g5core_header_options'));

			add_filter('g5core_default_options_g5core_color_options', array($this, 'change_default_options_g5core_color_options'));

			add_filter('g5core_default_options_g5core_typography_options', array($this, 'change_default_options_g5core_typography_options'));

			add_filter( 'g5core_default_options_g5core_layout_options', array($this, 'change_default_options_g5core_layout_options') );

			add_filter('g5core_paging_load_more_css_class',array($this,'change_paging_load_more_css_class'));

			add_filter( 'g5core_default_options_g5core_options', array($this,'change_default_options_g5core_options'), 11 );
		}

		public function change_g5core_header_options_config($options_config)
		{
			$options_config['section_color']['fields']['menu_color_group']['fields']['submenu_scheme']['preset'] = array(
				array(
					'op'     => '=',
					'value'  => 'light',
					'fields' => array(
						array( 'submenu_background_color', '#fff' ),
						array( 'submenu_heading_color', '#000' ),
						array( 'submenu_item_bg_hover_color', '#fff' ),
						array( 'submenu_text_color', '#7d7d7d' ),
						array( 'submenu_text_hover_color', '#4b2bb0' ),
						array( 'submenu_border_color', '#ebebeb' ),
					)
				),
				array(
					'op'     => '=',
					'value'  => 'dark',
					'fields' => array(
						array( 'submenu_background_color', '#171717' ),
						array( 'submenu_heading_color', '#fff' ),
						array( 'submenu_item_bg_hover_color', '#171717' ),
						array( 'submenu_text_color', '#171717' ),
						array( 'submenu_text_hover_color', '#fff' ),
						array( 'submenu_border_color', '#272727' ),
					)
				),


			);

			$options_config['section_color']['fields']['navigation_color_group']['fields']['navigation_scheme']['preset'] = array(
				array(
					'op' => '=',
					'value' => 'light',
					'fields' => array(
						array('navigation_background_color', '#fff'),
						array('navigation_text_color', '#000000'),
						array('navigation_text_hover_color', '#4b2bb0'),
						array('navigation_border_color', '#ebebeb'),
						array('navigation_disable_color', '#9fa4af'),
					)
				),
				array(
					'op' => '=',
					'value' => 'dark',
					'fields' => array(
						array('navigation_background_color', '#171717'),
						array('navigation_text_color', '#fff'),
						array('navigation_text_hover_color', '#999'),
						array('navigation_border_color', '#ebebeb'),
						array('navigation_disable_color', '#aaa'),
					)
				),
			);

			return $options_config;
		}

		public function change_default_options_g5core_header_options($defaults)
		{

			$defaults = wp_parse_args(array(
				'logo_font'                              =>
					array(
						'font_family'    => 'Work Sans',
						'font_size'      => '2rem',
						'font_weight'    => '700',
						'font_style'     => '',
						'align'          => '',
						'transform'      => 'none',
						'line_height'    => '',
						'letter_spacing' => '0',
					),

				'top_bar_font'                           =>
					array(
						'font_family'    => 'Open Sans',
						'font_size'      => '12px',
						'font_weight'    => '400',
						'font_style'     => '',
						'transform'      => 'uppercase',
						'line_height'    => '',
						'letter_spacing' => '-0.04',
					),

				'menu_font'                              =>
					array(
						'font_family'    => 'Open Sans',
						'font_size'      => '16px',
						'font_weight'    => '700',
						'font_style'     => '',
						'transform'      => 'none',
						'line_height'    => '',
						'letter_spacing' => '-0.01',
					),

				'sub_menu_font'                          =>
					array(
						'font_family'    => 'Open Sans',
						'font_size'      => '16px',
						'font_weight'    => '400',
						'font_style'     => '',
						'transform'      => 'none',
						'line_height'    => '',
						'letter_spacing' => '-0.025',
					),

				'header_background_color' => 'rgba(255,255,255,0)',
				'header_text_color' => '#000000',
				'header_text_hover_color' => '#4b2bb0',
				'header_border_color' => '#ebebeb',
				'header_disable_color' => '#9fa4af',

				'header_sticky_background_color' => '#fff',
				'header_sticky_text_color' => '#000000',
				'header_sticky_text_hover_color' => '#4b2bb0',
				'header_sticky_border_color' => '#ebebeb',
				'header_sticky_disable_color' => '#9fa4af',


				'navigation_background_color' => '#fff',
				'navigation_text_color' => '#000000',
				'navigation_text_hover_color' => '#4b2bb0',
				'navigation_border_color' => '#ebebeb',
				'navigation_disable_color' => '#9fa4af',

				'submenu_background_color' => '#fff',
				'submenu_heading_color' => '#000',
				'submenu_text_color' => '#7d7d7d',
				'submenu_item_bg_hover_color' => '#fff',
				'submenu_text_hover_color' => '#4b2bb0',
				'submenu_border_color' => '#ebebeb',


				'header_mobile_background_color' => '#fff',
				'header_mobile_text_color' => '#000',
				'header_mobile_text_hover_color' => '#4b2bb0',
				'header_mobile_border_color' => '#ebebeb',

				'header_mobile_sticky_background_color' => '#fff',
				'header_mobile_sticky_text_color' => '#000',
				'header_mobile_sticky_text_hover_color' => '#4b2bb0',
				'header_mobile_sticky_border_color' => '#ebebeb',

				'header_float' => 'on'

			), $defaults);


			return $defaults;
		}

		public function change_default_options_g5core_color_options($defaults)
		{
			return wp_parse_args(array(
				'site_text_color' => '#7d7d7d',
				'accent_color' => '#4b2bb0',
				'link_color' => '#4b2bb0',
				'border_color' => '#ebebeb',
				'heading_color' => '#000',
				'caption_color' => '#9fa4af',
				'placeholder_color' => '#7d7d7d',
				'primary_color' => '#4f4f6f',
				'secondary_color' => '#6428cb',
				'dark_color' => '#222',
				'light_color' => '#fafafa',
				'gray_color' => '#898989',
			), $defaults);
		}

		public function change_default_options_g5core_typography_options($defaults)
		{

			return wp_parse_args(array(

				'body_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '16px',
						'font_weight' => '400',
					),
				'primary_font' => array(
					'font_family' => 'Work Sans'
				),

				'h1_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '48px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),
				'h2_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '36px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),
				'h3_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '30px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),
				'h4_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '24px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),
				'h5_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '20px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),
				'h6_font' =>
					array (
						'font_family' => 'Open Sans',
						'font_size' => '16px',
						'font_weight' => '700',
						'letter_spacing' => '-0.025',
					),

				'display_1' => array(
					'font_family' => 'Open Sans',
					'font_size' => '16px',
				),
				'display_2' => array(
					'font_family' => 'Open Sans',
					'font_size' => '16px',
				),
				'display_3' => array(
					'font_family' => 'Open Sans',
					'font_size' => '16px',
				),
				'display_4' => array(
					'font_family' => 'Open Sans',
					'font_size' => '16px',
				),
			), $defaults);

		}

		public function change_default_options_g5core_layout_options($defaults) {
			return wp_parse_args(array(
				'content_padding' =>
					array (
						'left' => '',
						'right' => '',
						'top' => 80,
						'bottom' => 130,
					),
			),$defaults);
		}

		public function change_default_options_g5core_options($defaults) {
			return wp_parse_args(array(
				'post_single__page_title_enable' => 'on',
			),$defaults);
		}

		public function change_paging_load_more_css_class($css_classes) {
			$css_classes[] = 'btn-outline';
			return $css_classes;
		}
	}

	function THALLO_SETUP_DATA() {
		return THALLO_SETUP_DATA::getInstance();
	}

	THALLO_SETUP_DATA()->init();
}

