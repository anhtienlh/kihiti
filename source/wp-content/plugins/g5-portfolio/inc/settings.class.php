<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Portfolio_Settings' ) ) {
	class G5Portfolio_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function get_portfolio_layout($inherit = false)
        {
            $config = apply_filters('g5portfolio_options_portfolio_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
                'masonry' => array(
                    'label' => esc_html__('Masonry', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-masonry.png'),
                ),
                'masonry-2' => array(
                    'label' => esc_html__('Masonry 02', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-masonry-02.png')
                ),
                'justified' => array(
                    'label' => esc_html__('Justified', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-justified.jpg'),
                ),
                'metro-1' => array(
                    'label' => esc_html__('Metro 01', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-metro-01.png')
                ),
                'metro-2' => array(
                    'label' => esc_html__('Metro 02', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-metro-02.png')
                ),
                'metro-3' => array(
                    'label' => esc_html__('Metro 03', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-metro-03.png')
                ),
                'metro-4' => array(
	                'label' => esc_html__('Metro 04', 'g5-portfolio'),
	                'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-metro-04.png')
                ),


            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-portfolio'),
                            'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }


        public function get_portfolio_skins($inherit = false)
        {
            $config = apply_filters('g5portfolio_options_portfolio_skins', array(
                'skin-01' => array(
                    'label' => esc_html__('Skin 01', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-01.png'),
                ),
                'skin-02' => array(
                    'label' => esc_html__('Skin 02', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-02.png'),
                ),
                'skin-03' => array(
                    'label' => esc_html__('Skin 03', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-03.png'),
                ),
                'skin-04' => array(
                    'label' => esc_html__('Skin 04', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-04.png'),
                ),
                'skin-05' => array(
                    'label' => esc_html__('Skin 05', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-05.png'),
                ),
                'skin-06' => array(
                    'label' => esc_html__('Skin 06', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/skin-06.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-portfolio'),
                            'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_single_layout($inherit = false)
        {
            $config = apply_filters('g5portfolio_options_single_layout', array(
                'layout-1' => array(
                    'label' => esc_html__('Layout 01', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/single-portfolio-1.png')
                ),
                'layout-2' => array(
                    'label' => esc_html__('Layout 02', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/single-portfolio-2.png')
                ),
                'layout-3' => array(
                    'label' => esc_html__('Layout 03', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/single-portfolio-3.png')
                ),
                'layout-4' => array(
                    'label' => esc_html__('Layout 04', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/single-portfolio-4.png')
                ),
                'layout-5' => array(
                    'label' => esc_html__('Layout 05', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/single-portfolio-5.png')
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-portfolio'),
                            'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_single_gallery_layout($inherit = false) {
            $config = apply_filters('g5portfolio_options_single_gallery_layout', array(
                'slider' => array(
                    'label' => esc_html__('Slider', 'g5-portfolio'),
                    'img'   => G5PORTFOLIO()->plugin_url('assets/images/theme-options/gallery-slider.png'),
                ),
                'thumbnail' => array(
                    'label' => esc_html__('Gallery', 'g5-portfolio'),
                    'img'   => G5PORTFOLIO()->plugin_url('assets/images/theme-options/gallery-thumbnail.png'),
                ),
                'carousel' => array(
                    'label' => esc_html__('Carousel', 'g5-portfolio'),
                    'img'   => G5PORTFOLIO()->plugin_url('assets/images/theme-options/gallery-carousel.png'),
                ),
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-portfolio'),
                    'img'   => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
                'masonry' => array(
                    'label' => esc_html__('Masonry', 'g5-portfolio'),
                    'img'   => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-masonry.png'),
                ),
                'justified' => array(
                    'label' => esc_html__('Justified', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/layout-justified.jpg'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-portfolio'),
                            'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_single_related_algorithm($inherit = false)
        {
            $config = apply_filters('g5portfolio_options_single_related_algorithm', array(
                'cat' => esc_html__('by Category', 'g5-portfolio'),
                'tag' => esc_html__('by Tag', 'g5-portfolio'),
                'author' => esc_html__('by Author', 'g5-portfolio'),
                'cat-tag' => esc_html__('by Category & Tag', 'g5-portfolio'),
                'cat-tag-author' => esc_html__('by Category & Tag & Author', 'g5-portfolio'),
                'random' => esc_html__('Randomly', 'g5-portfolio')
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-portfolio')
                    ) + $config;
            }

            return $config;

        }

        public function get_light_box_mode($inherit = false) {
            $config = apply_filters('g5portfolio_options_light_box_mode',array(
                '' => esc_html__('Hide','g5-portfolio'),
                'feature' => esc_html__('Feature Image', 'g5-portfolio'),
                'gallery'   => esc_html__('Media Gallery', 'g5-portfolio')
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-portfolio')
                    ) + $config;
            }

            return $config;
        }


        public function get_widget_portfolio_layout($inherit = false)
        {
            $config = apply_filters('g5portfolio_widget_portfolio_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/widgets/layout-grid.png'),
                ),
                'list' => array(
                    'label' => esc_html__('List', 'g5-portfolio'),
                    'img' => G5PORTFOLIO()->plugin_url('assets/images/widgets/layout-list.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-portfolio'),
                            'img' => G5PORTFOLIO()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

		public function get_portfolio_orderby() {
			return apply_filters('g5portfolio_options_portfolio_orderby',array(
				'date' => esc_html__( 'Date', 'g5-portfolio' ),
				'title' => esc_html__( 'Title', 'g5-portfolio' ),
				'rand' => esc_html__( 'Random', 'g5-portfolio' ),
				'menu_order' => esc_html__( 'Menu Order', 'g5-portfolio' )
			));
		}

		public function get_portfolio_order() {
			return apply_filters('g5portfolio_options_portfolio_order',array(
				'asc' => esc_html__( 'ASC', 'g5-portfolio' ),
				'desc' => esc_html__( 'DESC', 'g5-portfolio' )
			));
		}
	}
}