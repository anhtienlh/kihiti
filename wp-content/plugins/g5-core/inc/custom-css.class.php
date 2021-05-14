<?php
/**
 * Custom Css In Page
 *
 * Add custom css any where and render it on footer (wp-footer)
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( ! class_exists( 'G5Core_Custom_Css' ) ) {
	class G5Core_Custom_Css {
		private $_custom_css = array();
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Plugin construct
		 */
		public function init() {
			if ( ! is_admin() ) {
				add_action( 'template_redirect', array( $this, 'global_custom_css' ), 100 );
			}

			add_action( 'wp_head', array( $this, 'init_custom_css' ), 10 );
			add_action( 'wp_footer', array( $this, 'render_custom_css' ), 10 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_editor_enqueue' ), 100 );
		}

		/**
		 * Check css key exists
		 *
		 * @param $key
		 *
		 * @return bool
		 */
		public function existsCssKey($key) {
			return isset($this->_custom_css[ $key ]);
		}

		/**
		 * Add custom css
		 *
		 * @param $css
		 * @param string $key (default: '')
		 */
		public function addCss( $css, $key = '' ) {
			if ( $key === '' ) {
				$this->_custom_css[] = $css;
			} else {
				$this->_custom_css[ $key ] = $css;
			}

		}

		/**
		 * @param bool $allow_flush
		 *
		 * @return null|string|string[]
		 */
		public function getCss( $allow_flush = false ) {
			$css = ' ' . implode( '', $this->_custom_css );
			if ( $allow_flush ) {
				$this->_custom_css = [];
			}

			return preg_replace( '/\r\n|\n|\t/', '', $css );
		}

		/**
		 * Render custom css in footer
		 */
		public function init_custom_css() {
			echo '<style type="text/css" id="g5core-custom-css">' . $this->getCss( true ) . '</style>';
		}

		public function render_custom_css() {
			echo sprintf( '<script>jQuery("style#g5core-custom-css").append("%s");</script>', esc_js($this->getCss( true )) );
		}

		public function global_custom_css() {
			$this->addCss( $this->site_typography(), 'site_typography' );
			$this->addCss( $this->site_color(), 'site_color' );

			$this->addCss( $this->header_responsive_css(), 'header_responsive' );
			$this->addCss( $this->header_color(), 'header_color' );
			$this->addCss( $this->header_font(), 'header_font' );
			$this->addCss( $this->navigation_size(), 'navigation_size' );

			$this->addCss( $this->layout_css(), 'layout_css' );
			$this->addCss( $this->page_loading_color(), 'page_loading_color' );

		}

		private function site_typography() {
			$fonts = array(
				'body_font' => array(
					'tag'       => 'body',
					'important' => ''
				),
				'h1_font'   => array(
					'tag'       => 'h1',
					'important' => ''
				),
				'h2_font'   => array(
					'tag'       => 'h2',
					'important' => ''
				),
				'h3_font'   => array(
					'tag'       => 'h3',
					'important' => ''
				),
				'h4_font'   => array(
					'tag'       => 'h4',
					'important' => ''
				),
				'h5_font'   => array(
					'tag'       => 'h5',
					'important' => ''
				),
				'h6_font'   => array(
					'tag'       => 'h6',
					'important' => ''
				),
				'display_1' => array(
					'tag'       => '.display-1',
					'important' => '!important'
				),
				'display_2' => array(
					'tag'       => '.display-2',
					'important' => '!important'
				),
				'display_3' => array(
					'tag'       => '.display-3',
					'important' => '!important'
				),
				'display_4' => array(
					'tag'       => '.display-4',
					'important' => '!important'
				),
			);

			$css = '';
			foreach ( $fonts as $font_key => $font_tag ) {
				$font = g5core_process_font( G5CORE()->options()->typography()->get_option( $font_key ) );

				$css .= <<<CUSTOM_CSS
{$font_tag['tag']} {
	font-family: {$font['font_family']}{$font_tag['important']};
	font-size: {$font['font_size']};
	font-weight: {$font['font_weight']}{$font_tag['important']};
	font-style: {$font['font_style']}{$font_tag['important']};
	text-transform: {$font['transform']}{$font_tag['important']};
	letter-spacing: {$font['letter_spacing']}em{$font_tag['important']};
}
CUSTOM_CSS;
			}

			return $css;
		}

		private function site_color() {
			$site_text_color = G5CORE()->options()->color()->get_option( 'site_text_color' );
			$css             = "body {color:{$site_text_color}}";
			$css             .= ".text-color{color:{$site_text_color}!important}";

			$color_keys = array(
				'accent',
				'border',
				'heading',
				'caption',
				'placeholder',
				'primary',
				'secondary',
				'dark',
				'light',
				'gray',
			);

			foreach ( $color_keys as $key ) {
				$color = G5CORE()->options()->color()->get_option( "{$key}_color" );
				$css   .= ".{$key}-text-color{color:{$color}!important}";
				$css   .= ".{$key}-text-hover-color:hover{color:{$color}!important}";
				$css   .= ".{$key}-bg-color{background-color:{$color}!important}";
				$css   .= ".{$key}-bg-hover-color:hover{background-color:{$color}!important}";
				$css   .= ".{$key}-border-color{border-color:{$color}!important}";
				$css   .= ".{$key}-border-hover-color:hover{border-color:{$color}!important}";
			}

			$link_color = G5CORE()->options()->color()->get_option( "link_color" );
			$link_hover_color = g5core_color_adjust_brightness($link_color);
			$css .= "a {color:{$link_color}}";
			$css .= "a:hover {color:{$link_hover_color}}";


			$heading_color = G5CORE()->options()->color()->get_option( "heading_color" );
			$css           .= "h1, h2, h3, h4, h5, h6 {color:{$heading_color}}";

			$caption_color = G5CORE()->options()->color()->get_option( "caption_color" );
			$css           .= ".wp-block-image figcaption {color:{$caption_color}}";

			$placeholder_color = G5CORE()->options()->color()->get_option( "placeholder_color" );
			$css            .= "textarea:-moz-placeholder,
					select:-moz-placeholder,
					input[type]:-moz-placeholder {
						color: {$placeholder_color};
					}
					textarea::-moz-placeholder,
					select::-moz-placeholder,
					input[type]::-moz-placeholder {
						color: {$placeholder_color};
					}
					textarea:-ms-input-placeholder,
					select:-ms-input-placeholder,
					input[type]:-ms-input-placeholder {
						color: {$placeholder_color};
					}
					textarea::-webkit-input-placeholder,
					select::-webkit-input-placeholder,
					input[type]::-webkit-input-placeholder {
						color: {$placeholder_color};
					}";
			$css .= g5core_get_background_css( G5CORE()->options()->color()->get_option( 'site_background_color' ), '#site-wrapper,#wrapper_content' );
			$css .= g5core_get_background_css( G5CORE()->options()->layout()->get_option( 'boxed_background_color' ), 'body.site-style-boxed' );

			$css = apply_filters( 'g5core_site_color_css', $css );

			return $css;
		}

		private function header_responsive_css() {
			$responsive_break_point = G5CORE()->options()->header()->get_option( 'header_responsive_breakpoint', '991' );
			$responsive_break_point_2 = $responsive_break_point + 1;

			return <<<CUSTOM_CSS
@media screen and (max-width: {$responsive_break_point}px) {
	#site-header {
		display: none;
	}
	#site-mobile-header {
		display: block;
	}
	body.g5core-is-header-vertical.g5core-is-header-vertical-left {
		padding-left: 0;
	}
	body.g5core-is-header-vertical.g5core-is-header-vertical-right {
		padding-right: 0;
	}
	
	.x-mega-sub-menu .vc_column_container {
		width: 100%;
    }
	
	
}

@media (min-width: {$responsive_break_point_2}px) {
	body.g5core__stretched_content .g5core__single-breadcrumbs > .container,
	body.g5core__stretched_content #primary-content > .container,
	.g5core-site-header.header-layout-stretched .g5core-header-bellow > .container,
	 .g5core-site-header.header-layout-stretched .g5core-header-above > .container,
	  .g5core-site-header.header-layout-stretched .g5core-header-navigation > .container,
	   .g5core-site-header.header-layout-stretched .g5core-top-bar-desktop > .container,
	    .g5core-site-header.header-layout-stretched .g5core-header-desktop-wrapper > .container {
    max-width: 95%;
	}

}

CUSTOM_CSS;
		}

		private function header_color() {
			$header_mobile_background_color = G5CORE()->options()->header()->get_option( 'header_mobile_background_color' );
			$header_mobile_text_color       = G5CORE()->options()->header()->get_option( 'header_mobile_text_color' );
			$header_mobile_border_color     = G5CORE()->options()->header()->get_option( 'header_mobile_border_color' );
			$header_mobile_text_hover_color = G5CORE()->options()->header()->get_option( 'header_mobile_text_hover_color' );

			$header_mobile_sticky_background_color = G5CORE()->options()->header()->get_option( 'header_mobile_sticky_background_color' );
			$header_mobile_sticky_text_color       = G5CORE()->options()->header()->get_option( 'header_mobile_sticky_text_color' );
			$header_mobile_sticky_text_hover_color = G5CORE()->options()->header()->get_option( 'header_mobile_sticky_text_hover_color' );
			$header_mobile_sticky_border_color     = G5CORE()->options()->header()->get_option( 'header_mobile_sticky_border_color' );

			$top_bar_background_color = G5CORE()->options()->header()->get_option( 'top_bar_background_color' );
			$top_bar_text_color       = G5CORE()->options()->header()->get_option( 'top_bar_text_color' );
			$top_bar_text_hover_color = G5CORE()->options()->header()->get_option( 'top_bar_text_hover_color' );
			$top_bar_border_color     = G5CORE()->options()->header()->get_option( 'top_bar_border_color' );

			$header_mobile_top_bar_background_color = G5CORE()->options()->header()->get_option( 'header_mobile_top_bar_background_color' );
			$header_mobile_top_bar_text_color       = G5CORE()->options()->header()->get_option( 'header_mobile_top_bar_text_color' );
			$header_mobile_top_bar_text_hover_color = G5CORE()->options()->header()->get_option( 'header_mobile_top_bar_text_hover_color' );
			$header_mobile_top_bar_border_color     = G5CORE()->options()->header()->get_option( 'header_mobile_top_bar_border_color' );

			$header_background_color = G5CORE()->options()->header()->get_option( 'header_background_color' );
			$header_text_color       = G5CORE()->options()->header()->get_option( 'header_text_color' );
			$header_text_hover_color = G5CORE()->options()->header()->get_option( 'header_text_hover_color' );
			$header_border_color     = G5CORE()->options()->header()->get_option( 'header_border_color' );
			$header_disable_color    = G5CORE()->options()->header()->get_option( 'header_disable_color' );

			$navigation_background_color = G5CORE()->options()->header()->get_option( 'navigation_background_color' );
			$navigation_border_color     = G5CORE()->options()->header()->get_option( 'navigation_border_color' );
			$navigation_text_color       = G5CORE()->options()->header()->get_option( 'navigation_text_color' );
			$navigation_text_hover_color = G5CORE()->options()->header()->get_option( 'navigation_text_hover_color' );
			$navigation_disable_color    = G5CORE()->options()->header()->get_option( 'navigation_disable_color' );

			$submenu_background_color = G5CORE()->options()->header()->get_option( 'submenu_background_color' );
			$submenu_heading_color = G5CORE()->options()->header()->get_option('submenu_heading_color');
			$submenu_text_color       = G5CORE()->options()->header()->get_option( 'submenu_text_color' );
			$submenu_text_hover_color = G5CORE()->options()->header()->get_option( 'submenu_text_hover_color' );
			$submenu_item_bg_hover_color = G5CORE()->options()->header()->get_option('submenu_item_bg_hover_color');
			$submenu_border_color     = G5CORE()->options()->header()->get_option( 'submenu_border_color' );

			$header_sticky_background_color = G5CORE()->options()->header()->get_option( 'header_sticky_background_color' );
			$header_sticky_text_color       = G5CORE()->options()->header()->get_option( 'header_sticky_text_color' );
			$header_sticky_text_hover_color = G5CORE()->options()->header()->get_option( 'header_sticky_text_hover_color' );
			$header_sticky_border_color     = G5CORE()->options()->header()->get_option( 'header_sticky_border_color');
			if ($header_sticky_border_color == '') {
				$header_sticky_border_color = 'transparent';
			}
			$header_sticky_disable_color    = G5CORE()->options()->header()->get_option( 'header_sticky_disable_color' );

			return <<<CUSTOM_CSS
.g5core-site-header,
.g5core-header-vertical {
	background-color: $header_background_color;
	color: $header_text_color;
}
.g5core-site-header.header-border-bottom {
	border-bottom: solid 1px $header_border_color;
}


.g5core-header-customize ul.g5core-social-networks.g5core-social-networks a:hover,
.g5core-header-desktop-wrapper .site-branding-text .site-title a:hover,
.g5core-header-desktop-wrapper .menu-horizontal .menu-current > a,
.g5core-header-desktop-wrapper .menu-horizontal .current-menu-parent > a,
.g5core-header-desktop-wrapper .menu-horizontal .current-menu-ancestor > a,
.g5core-header-desktop-wrapper .menu-horizontal .current-menu-item > a,
.g5core-header-desktop-wrapper .menu-horizontal .menu-item > a:hover,
.g5core-header-desktop-wrapper .menu-horizontal .menu-item > a:focus,
.g5core-header-desktop-wrapper .site-branding-text .site-title a:hover,
.g5core-header-desktop-wrapper .g5core-search-button a:hover,
.g5core-header-desktop-wrapper .g5core-login-button a:hover,
.g5core-header-desktop-wrapper .toggle-icon:hover,
 .g5core-header-desktop-wrapper .g5shop_header-action-icon:hover {
	color: $header_text_hover_color;
}

.g5core-header-desktop-wrapper .select2-container--default.select2-container--default .select2-selection--single,
.g5core-header-desktop-wrapper .g5core-search-form select,
.g5core-header-desktop-wrapper .g5core-search-form input[type=search] {
	border-color: $header_border_color;
}
 

.g5core-header-desktop-wrapper .g5core-search-form .remove,
.g5core-header-desktop-wrapper .g5core-search-form input[type=search]::placeholder,
.g5core-header-desktop-wrapper .g5core-search-form button {
	color: $header_disable_color;
}

.g5core-header-navigation {
	background-color: $navigation_background_color;
	color: $navigation_text_color;
}
.g5core-header-navigation.navigation-bordered-top {
	border-top-color: $navigation_border_color;
}
.g5core-header-navigation.navigation-bordered-bottom {
	border-bottom-color: $navigation_border_color;
}

.g5core-header-navigation ul.g5core-social-networks.g5core-social-networks a:hover,
.g5core-header-navigation .g5shop_header-action-icon:hover,
.g5core-header-navigation .menu-horizontal .menu-current > a,
.g5core-header-navigation .menu-horizontal .current-menu-parent > a,
.g5core-header-navigation .menu-horizontal .current-menu-ancestor > a,
.g5core-header-navigation .menu-horizontal .current-menu-item > a,
.g5core-header-navigation .menu-horizontal .menu-item > a:hover,
.g5core-header-navigation .site-branding-text .site-title a:hover,
.g5core-header-navigation .g5core-search-button a:hover,
.g5core-header-navigation .g5core-login-button a:hover,
.g5core-header-navigation .toggle-icon:hover {
	color: $navigation_text_hover_color;
}
.g5core-header-navigation .select2-container--default.select2-container--default .select2-selection--single,
.g5core-header-navigation .g5core-search-form select,
.g5core-header-navigation .g5core-search-form input[type=search] {
	border-color: $navigation_border_color;
}
.g5core-header-navigation .g5core-search-form input[type=search]::placeholder,
.g5core-header-navigation .g5core-search-form button {
	color: $navigation_disable_color;
}

.sticky-area-wrap.sticky .sticky-area {
	background-color: $header_sticky_background_color;
	color: $header_sticky_text_color;
}
.sticky-area-wrap.sticky .menu-horizontal > .menu-item > a,
.sticky-area-wrap.sticky .site-branding-text .site-title a,
.sticky-area-wrap.sticky .g5core-search-button a,
.sticky-area-wrap.sticky .g5core-login-button a,
.sticky-area-wrap.sticky .toggle-icon {
	color: $header_sticky_text_color;
}
.sticky-area-wrap.sticky .menu-horizontal > .menu-current > a,
.sticky-area-wrap.sticky .menu-horizontal > .current-menu-parent > a,
.sticky-area-wrap.sticky .menu-horizontal > .current-menu-ancestor > a,
.sticky-area-wrap.sticky .menu-horizontal > .current-menu-item > a,
.sticky-area-wrap.sticky .menu-horizontal > .menu-item > a:hover,
.sticky-area-wrap.sticky .site-branding-text .site-title a:hover,
.sticky-area-wrap.sticky .g5core-search-button a:hover,
.sticky-area-wrap.sticky .g5core-login-button a:hover,
.sticky-area-wrap.sticky .toggle-icon:hover {
	color: $header_sticky_text_hover_color;
}

.sticky-area-wrap.sticky .select2-container--default.select2-container--default .select2-selection--single,
.sticky-area-wrap.sticky .g5core-search-form select,
.sticky-area-wrap.sticky .g5core-search-form input[type=search] {
	border-color: $header_sticky_border_color;
}
.sticky-area-wrap.sticky .g5core-search-form input[type=search]::placeholder,
.sticky-area-wrap.sticky .g5core-search-form button {
	color: $header_sticky_disable_color;
}

.g5core-mobile-header-wrapper {
	background-color: $header_mobile_background_color;
	color: $header_mobile_text_color;
}
.g5core-mobile-header-wrapper.border-bottom {
	border-bottom: solid 1px $header_mobile_border_color;
}
.g5core-mobile-header-wrapper .g5core-search-button a:hover,
.g5core-mobile-header-wrapper .g5core-login-button a:hover,
.g5core-mobile-header-wrapper .toggle-icon:hover,
.g5core-mobile-header-wrapper .g5shop_header-action-icon:hover{
	color: $header_mobile_text_hover_color;
}

.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area {
	background-color: $header_mobile_sticky_background_color;
	color: $header_mobile_sticky_text_color;
}


.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .g5core-search-button a,
.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .g5core-login-button a,
.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .toggle-icon {
	color: $header_mobile_sticky_text_color;
}


.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area.border-bottom {
	border-bottom: solid 1px $header_mobile_sticky_border_color;
}
.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .g5core-search-button a:hover,
.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .g5core-login-button a:hover,
.sticky-area-wrap.sticky .g5core-mobile-header-wrapper.sticky-area .toggle-icon:hover {
	color: $header_mobile_sticky_text_hover_color;
}

.g5core-top-bar-desktop {
	background-color: $top_bar_background_color;
	color: $top_bar_text_color;
}
.g5core-top-bar-desktop .g5core-login-button a:hover,
.g5core-top-bar-desktop .g5core-top-bar-item a:hover {
	color: $top_bar_text_hover_color;
}
.top-bar-desktop-border-bottom {
	border-bottom: solid 1px $top_bar_border_color;
}

.g5core-top-bar-mobile {
	background-color: $header_mobile_top_bar_background_color;
	color: $header_mobile_top_bar_text_color;
}
.g5core-top-bar-mobile .g5core-login-button a:hover,
.g5core-top-bar-mobile .g5core-top-bar-item a:hover {
	color: $header_mobile_top_bar_text_hover_color;
}
.top-bar-mobile-border-bottom {
	border-bottom: solid 1px $header_mobile_top_bar_border_color;
}

.g5core-header-desktop-wrapper .menu-horizontal .sub-menu {
	background-color: $submenu_background_color;
	color: $submenu_text_color;
}
.g5core-header-desktop-wrapper .menu-horizontal .sub-menu .menu-item > a {
	color: inherit;
}

.g5core-header-desktop-wrapper .menu-horizontal .sub-menu .menu-item .x-mega-sub-menu .gel-heading-title {
	color: {$submenu_heading_color};
}


.menu-horizontal .sub-menu .menu-item.menu-current > a,
.menu-horizontal .sub-menu .menu-item.current-menu-parent > a,
.menu-horizontal .sub-menu .menu-item.current-menu-ancestor > a,
.menu-horizontal .sub-menu .menu-item.current-menu-item > a,
.g5core-header-desktop-wrapper .menu-horizontal .sub-menu .menu-item > a:hover,
 .g5core-header-desktop-wrapper .menu-horizontal .sub-menu .menu-item .x-mega-sub-menu div.gel-list .item-list:hover{
	color: $submenu_text_hover_color;
	background-color: $submenu_item_bg_hover_color;
}
.g5core-header-desktop-wrapper .menu-horizontal .sub-menu .menu-item {
	border-bottom-color: $submenu_border_color;
}

.menu-vertical.navigation-bordered {
	border-top-color: $header_border_color;
}
.menu-vertical.navigation-bordered > .menu-item {
	border-bottom-color: $header_border_color;
}

.menu-vertical .menu-item:hover > a {
	color: $header_text_hover_color;
}
.menu-vertical > .menu-item > a {
	color: $header_text_color;
}

.menu-vertical .sub-menu {
	background-color: $submenu_background_color;
	border-color: $submenu_border_color;
}
.menu-vertical .sub-menu .menu-item {
	border-bottom-color: $submenu_border_color;
}
.menu-vertical .sub-menu .menu-item > a {
	color: $submenu_text_color;
}
.menu-vertical .sub-menu .menu-item > a:hover {
	color: $submenu_text_hover_color;
}
CUSTOM_CSS;
		}

		private function header_font() {
			$logo_font     = g5core_process_font( G5CORE()->options()->header()->get_option( 'logo_font' ) );
			$top_bar_font  = g5core_process_font( G5CORE()->options()->header()->get_option( 'top_bar_font' ) );
			$menu_font     = g5core_process_font( G5CORE()->options()->header()->get_option( 'menu_font' ) );
			$sub_menu_font = g5core_process_font( G5CORE()->options()->header()->get_option( 'sub_menu_font' ) );

			return <<<CUSTOM_CSS
.g5core-top-bar,
.g5core-top-bar .menu-horizontal > .menu-item > a,
.g5core-top-bar .g5core-login-button a {
	font-family: {$top_bar_font['font_family']};
	font-size: {$top_bar_font['font_size']};
	font-weight: {$top_bar_font['font_weight']};
	font-style: {$top_bar_font['font_style']};
	text-transform: {$top_bar_font['transform']};
	letter-spacing: {$top_bar_font['letter_spacing']}em;
}

.menu-popup > .menu-item > a,
.menu-horizontal > .menu-item > a,
.menu-vertical > .menu-item > a {
	font-family: {$menu_font['font_family']};
	font-size: {$menu_font['font_size']};
	font-weight: {$menu_font['font_weight']};
	font-style: {$menu_font['font_style']};
	text-transform: {$menu_font['transform']};
	letter-spacing: {$menu_font['letter_spacing']}em;
}

.menu-popup .sub-menu .menu-item > a,
.menu-horizontal .sub-menu .menu-item > a,
.menu-vertical .sub-menu .menu-item > a,
 .x-mega-sub-menu div.gel-list .item-list{
	font-family: {$sub_menu_font['font_family']};
	font-size: {$sub_menu_font['font_size']};
	font-weight: {$sub_menu_font['font_weight']};
	font-style: {$sub_menu_font['font_style']};
	text-transform: {$sub_menu_font['transform']};
	letter-spacing: {$sub_menu_font['letter_spacing']}em;
}

.site-branding-text .site-title {
	font-family: {$logo_font['font_family']};
	font-size: {$logo_font['font_size']};
	font-weight: {$logo_font['font_weight']};
	font-style: {$logo_font['font_style']};
	text-transform: {$logo_font['transform']};
	letter-spacing: {$logo_font['letter_spacing']}em;
}
CUSTOM_CSS;
		}

		private function navigation_size() {
			$menu_spacing           = G5CORE()->options()->header()->get_option( 'menu_spacing' );
			$logo_max_height        = G5CORE()->options()->header()->get_option( 'logo_max_height' );
            $logo_sticky_max_height        = G5CORE()->options()->header()->get_option( 'logo_sticky_max_height' );
			$mobile_logo_max_height = G5CORE()->options()->header()->get_option( 'mobile_logo_max_height' );
            $mobile_logo_sticky_max_height = G5CORE()->options()->header()->get_option( 'mobile_logo_sticky_max_height' );

			$css = '';
			if ( isset($logo_max_height['height']) && $logo_max_height['height'] !== '' ) {
				$css .= <<<CUSTOM_CSS
.g5core-site-branding .site-logo {
	max-height: {$logo_max_height['height']}px;
}
.g5core-header-above .g5core-site-branding .site-logo,
.g5core-header-bellow .g5core-site-branding .site-logo {
	max-height: {$logo_max_height['height']}px;
}
CUSTOM_CSS;

			}


            if ( isset($logo_sticky_max_height['height']) && $logo_sticky_max_height['height'] !== '' ) {
                $css .= <<<CUSTOM_CSS
                
.g5core-site-header .sticky-area-wrap.sticky .sticky-area .g5core-site-branding .site-logo {
	max-height: {$logo_sticky_max_height['height']}px;
}

CUSTOM_CSS;

            }

			if ( isset($mobile_logo_max_height['height']) && $mobile_logo_max_height['height'] !== '' ) {
				$css .= <<<CUSTOM_CSS
.g5core-mobile-header-inner .site-logo {
	max-height: {$mobile_logo_max_height['height']}px;
}
CUSTOM_CSS;

			}

            if ( isset($mobile_logo_sticky_max_height['height']) && $mobile_logo_sticky_max_height['height'] !== '' ) {
                $css .= <<<CUSTOM_CSS
.sticky-area-wrap.sticky .g5core-mobile-header-inner .site-logo {
	max-height: {$mobile_logo_sticky_max_height['height']}px;
}
CUSTOM_CSS;

            }

			if ( isset($menu_spacing['width']) && $menu_spacing['width'] !== '' ) {
				$css .= <<<CUSTOM_CSS
				.g5core-primary-menu .menu-horizontal > li + li {
					margin-left: {$menu_spacing['width']}px;
				}
CUSTOM_CSS;
			}

			return $css;
		}

		private function layout_css() {
			$content_padding = wp_parse_args( G5CORE()->options()->layout()->get_option( 'content_padding' ), array(
				'top'    => '0',
				'bottom' => '0',
			) );

			$bordered_width = G5CORE()->options()->layout()->get_option( 'bordered_width' );
			$bordered_color = G5CORE()->options()->layout()->get_option( 'bordered_color' );
			$border_width_admin_bar = $bordered_width['width'] + 32;
			$back_to_top_position   = $bordered_width['width'] + 10;



				$custom_css =  <<<CUSTOM_CSS
@media (min-width: 992px) {
	body.site-style-bordered {
		border: solid {$bordered_width['width']}px $bordered_color;
	}
	.g5core-site-bordered-top,
	.g5core-site-bordered-bottom {
		border-top: solid {$bordered_width['width']}px $bordered_color;
	}
	body.site-style-bordered .sticky-area-wrap.sticky > .sticky-area  {
		margin-left: {$bordered_width['width']}px;
		margin-right: {$bordered_width['width']}px;
	}
	body.site-style-bordered .g5core-header-vertical {
		top: {$bordered_width['width']}px;
		bottom: {$bordered_width['width']}px;
	}
	body.site-style-bordered.admin-bar .g5core-header-vertical {
		top: {$border_width_admin_bar}px;
	}
	
	body.site-style-bordered .g5core-header-vertical-left {
		margin-left: {$bordered_width['width']}px;
	}
	body.site-style-bordered .g5core-header-vertical-right {
		margin-right: {$bordered_width['width']}px;
	}
	body.site-style-bordered .g5core-site-footer-fixed {
	    bottom: {$bordered_width['width']}px;
	    left: {$bordered_width['width']}px;
	    right: {$bordered_width['width']}px;
	}
	
	body.site-style-bordered .g5core-back-to-top {
	    bottom: {$back_to_top_position}px;
        right: {$back_to_top_position}px;
	}
	body.site-style-bordered.g5core-is-header-vertical-right.g5core-is-header-vertical-large .g5core-back-to-top,
	body.site-style-bordered.g5core-is-header-vertical-right.g5core-is-header-vertical-mini .g5core-back-to-top {
		left: {$back_to_top_position}px;
	}
}
CUSTOM_CSS;

				if (isset($content_padding['top']) && ($content_padding['top'] !== '')) {
					$custom_css .= <<<CUSTOM_CSS
				#primary-content {
					padding-top: {$content_padding['top']}px;
				}
CUSTOM_CSS;
				}

			if (isset($content_padding['bottom']) && ($content_padding['bottom'] !== '')) {
				$custom_css .= <<<CUSTOM_CSS
				#primary-content {
					padding-bottom: {$content_padding['bottom']}px;
				}
CUSTOM_CSS;
			}
				return $custom_css;

		}

		private function page_loading_color() {
			$loading_animation = G5CORE()->options()->get_option( 'loading_animation' );
			if ( empty( $loading_animation ) ) {
				return '';
			}
			$loading_animation_bg_color = G5CORE()->options()->get_option( 'loading_animation_bg_color' );
			$spinner_color              = G5CORE()->options()->get_option( 'spinner_color' );
			$css = '';
			if ( $loading_animation_bg_color !== '' ) {
				$css = <<<CUSTOM_CSS
.g5core-site-loading {
	background: $loading_animation_bg_color;
}
CUSTOM_CSS;
			}

			if ( $spinner_color !== '' ) {
				$css .= <<<CUSTOM_CSS
.sk-chasing-dots .sk-child,
.sk-circle .sk-child:before,
.sk-rotating-plane,
.sk-double-bounce .sk-child,
.sk-fading-circle .sk-circle:before,
.sk-folding-cube .sk-cube:before,
.sk-spinner-pulse,
.sk-three-bounce .sk-child,
.sk-wave .sk-rect {
	background-color: $spinner_color;
}
CUSTOM_CSS;
			}

			return $css;
		}

		public function gutenberg_editor_enqueue() {
			$id = isset( $_GET['post'] ) ? $_GET['post'] : 0;

			if ( $id !== 0 ) {
				$prefix            = G5CORE()->meta_prefix;
				$typography_preset = get_post_meta( $id, "{$prefix}typography_preset", true );
				if ( ! empty( $typography_preset ) ) {
					G5CORE()->options()->typography()->set_preset( $typography_preset );
				}

				$color_preset = get_post_meta( $id, "{$prefix}color_preset", true );
				if ( ! empty( $color_preset ) ) {
					G5CORE()->options()->color()->set_preset( $color_preset );
				}

				$site_layout = get_post_meta( $id, "{$prefix}site_layout", true );
				$sidebar     = get_post_meta( $id, "{$prefix}sidebar", true );

				if ( ! empty( $site_layout ) ) {
					G5CORE()->options()->layout()->set_option( 'site_layout', $site_layout );
				}

				if ( ! empty( $sidebar ) ) {
					G5CORE()->options()->layout()->set_option( 'sidebar', $sidebar );
				}
			}

			$fonts = array(
				'body_font' => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper',
					'important' => ''
				),
				'h1_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h1, .edit-post-layout__content .editor-post-title__block .editor-post-title__input',
					'important' => ''
				),
				'h2_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h2',
					'important' => ''
				),
				'h3_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h3',
					'important' => ''
				),
				'h4_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h4',
					'important' => ''
				),
				'h5_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h5',
					'important' => ''
				),
				'h6_font'   => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper h6',
					'important' => ''
				),
				'display_1' => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper .display-1',
					'important' => '!important'
				),
				'display_2' => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper .display-2',
					'important' => '!important'
				),
				'display_3' => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper .display-3',
					'important' => '!important'
				),
				'display_4' => array(
					'tag'       => '.edit-post-visual-editor.editor-styles-wrapper .display-4',
					'important' => '!important'
				),
			);

			$css = '';
			foreach ( $fonts as $font_key => $font_tag ) {
				$font = g5core_process_font( G5CORE()->options()->typography()->get_option( $font_key ) );

				$css .= <<<CUSTOM_CSS
{$font_tag['tag']} {
	font-family: {$font['font_family']}{$font_tag['important']};
	font-size: {$font['font_size']};
	font-weight: {$font['font_weight']}{$font_tag['important']};
	font-style: {$font['font_style']}{$font_tag['important']};
	text-transform: {$font['transform']}{$font_tag['important']};
	letter-spacing: {$font['letter_spacing']}em{$font_tag['important']};
}
CUSTOM_CSS;
			}

			// Color css
			$site_text_color = G5CORE()->options()->color()->get_option( 'site_text_color' );
			$css             .= ".edit-post-visual-editor.editor-styles-wrapper {color:{$site_text_color}}";
			$css             .= ".text-color{color:{$site_text_color}!important}";

			$color_keys = array(
				'accent',
				'border',
				'heading',
				'caption',
				'placeholder',
				'primary',
				'secondary',
				'dark',
				'light',
				'gray',
			);

			foreach ( $color_keys as $key ) {
				$color = G5CORE()->options()->color()->get_option( "{$key}_color" );
				$css   .= ".{$key}-text-color{color:{$color}!important}";
				$css   .= ".{$key}-text-hover-color:hover{color:{$color}!important}";
				$css   .= ".{$key}-bg-color{background-color:{$color}!important}";
				$css   .= ".{$key}-bg-hover-color:hover{background-color:{$color}!important}";
				$css   .= ".{$key}-border-color{border-color:{$color}!important}";
				$css   .= ".{$key}-border-hover-color:hover{border-color:{$color}!important}";
			}

			$heading_color = G5CORE()->options()->color()->get_option( "heading_color" );
			$css           .= ".edit-post-visual-editor.editor-styles-wrapper h1, .edit-post-visual-editor.editor-styles-wrapper h2, .edit-post-visual-editor.editor-styles-wrapper h3, .edit-post-visual-editor.editor-styles-wrapper h4, .edit-post-visual-editor.editor-styles-wrapper h5, .edit-post-visual-editor.editor-styles-wrapper h6 {color:{$heading_color}}";

			$caption_color = G5CORE()->options()->color()->get_option( "caption_color" );
			$css           .= ".edit-post-visual-editor.editor-styles-wrapper .wp-block-image figcaption {color:{$caption_color}}";

			$css = apply_filters( 'g5core_gutenberg_editor_css', $css );

			wp_add_inline_style( 'block-editor', $css );
		}

	}
}