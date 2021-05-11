<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('G5Portfolio_Config_Options')) {
    class G5Portfolio_Config_Options
    {
        /*
         * loader instances
         */
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == null) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init()
        {
            add_filter('gsf_option_config', array($this, 'define_options'), 300);
            add_filter('gsf_meta_box_config', array($this, 'define_meta_box'));
	        add_filter('g5core_admin_bar_theme_options', array($this, 'admin_bar_theme_options'), 300);

            add_action('pre_get_posts', array($this, 'pre_get_posts'));
            add_filter('g5core_taxonomy_for_term_meta',array($this,'term_meta_page_title'));
            add_action('template_redirect', array($this, 'change_single_setting'));

            add_filter( 'g5core_default_options_g5core_options', array($this,'change_default_options') );
        }

	    public function admin_bar_theme_options($admin_bar_theme_options) {
		    $admin_bar_theme_options['g5portfolio_options'] = array(
			    'title' => esc_html__('Portfolio','g5-portfolio'),
			    'permission' => 'manage_options',
		    );
		    return $admin_bar_theme_options;
	    }

        public function define_options($configs)
        {
            $configs['g5portfolio_options'] = array(
                'layout' => 'inline',
                'page_title' => esc_html__('Portfolio Options', 'g5-portfolio'),
                'menu_title' => esc_html__('Portfolio', 'g5-portfolio'),
                'option_name' => 'g5portfolio_options',
                'parent_slug' => 'g5core_options',
                'permission' => 'manage_options',
                'section' => array(
                    $this->config_section_archive(),
                    $this->config_section_single()
                )
            );
            return $configs;
        }

        public function config_section_archive()
        {
            return array(
                'id' => 'section_archive',
                'title' => esc_html__('Archive Listing', 'g5-portfolio'),
                'icon' => 'dashicons dashicons-category',
                'fields' => array(
                    'category_filter_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'category_filter_enable',
                        'title' => esc_html__('Category Filter Enable', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn On this option if you want to enable category filter', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default('category_filter_enable', ''),
                    )),
                    'category_filter_align' => array(
	                    'id' => 'category_filter_align',
	                    'title' => esc_html__('Category Filter Align','g5-portfolio'),
	                    'subtitle' => esc_html__('Specify your category filter align','g5-portfolio'),
	                    'type' => 'button_set',
	                    'options' => G5CORE()->settings()->get_category_filter_align(),
	                    'default' => G5PORTFOLIO()->options()->get_default('category_filter_align', ''),
	                    'required' => array('category_filter_enable','=','on')
                    ),
                    'append_tabs' =>  array(
                        'id' => 'append_tabs',
                        'title' => esc_html__('Append Categories','g5-portfolio'),
                        'subtitle' => esc_html__('Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)','g5-portfolio'),
                        'type' => 'text',
                        'default' => G5PORTFOLIO()->options()->get_default( 'append_tabs','' ),
                        'required' => array('category_filter_enable','=','on')
                    ),

                    'post_layout' => array(
                        'id' => 'post_layout',
                        'title' => esc_html__('Layout', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your portfolio layout', 'g5-portfolio'),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_portfolio_layout(),
                        'default' => G5PORTFOLIO()->options()->get_default('post_layout', 'grid'),
                    ),
                    'item_skin' => array(
                        'id' => 'item_skin',
                        'title' => esc_html__('Item Skin', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your portfolio item skin', 'g5-portfolio'),
                        'desc' => esc_html__( 'Note: Skin 01, Skin 02 only apply for layout Grid and Masonry', 'g5-portfolio' ),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_portfolio_skins(),
                        'default' => G5PORTFOLIO()->options()->get_default('item_skin', 'skin-01'),
                    ),
                    'item_custom_class' => array(
                        'id' => 'item_custom_class',
                        'title' => esc_html__('Item Css Classes', 'g5-portfolio'),
                        'subtitle' => esc_html__('Add custom css classes to item', 'g5-portfolio'),
                        'type' => 'text'
                    ),

                    'post_columns_gutter' => array(
                        'id' => 'post_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your horizontal space between item.', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5PORTFOLIO()->options()->get_default('post_columns_gutter', '30'),
                    ),
                    'post_columns_group' => array(
                        'id' => 'post_columns_group',
                        'title' => esc_html__('Columns', 'g5-portfolio'),
                        'type' => 'group',
                        'required' => array('post_layout', 'in', array('grid', 'masonry', 'carousel')),
                        'fields' => array(
                            'post_columns_row_1' => array(
                                'id' => 'post_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'post_columns_xl' => array(
                                        'id' => 'post_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your columns on extra large devices (>= 1200px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('post_columns_xl', '3'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_lg' => array(
                                        'id' => 'post_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your columns on large devices (>= 992px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('post_columns_lg', '3'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_md' => array(
                                        'id' => 'post_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your columns on medium devices (>= 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('post_columns_md', '2'),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'post_columns_row_2' => array(
                                'id' => 'post_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'post_columns_sm' => array(
                                        'id' => 'post_columns_sm',
                                        'title' => esc_html__('Small Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your columns on small devices (< 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('post_columns_sm', '2'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns' => array(
                                        'id' => 'post_columns',
                                        'title' => esc_html__('Extra Small Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your columns on extra small devices (< 576px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('post_columns', '1'),
                                        'layout' => 'full',
                                    )
                                )
                            )
                        )
                    ),
                    'post_image_size' => array(
                        'id' => 'post_image_size',
                        'title' => esc_html__('Image size', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your image size', 'g5-portfolio'),
                        'desc' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-portfolio'),
                        'type' => 'text',
                        'default' => G5PORTFOLIO()->options()->get_default('post_image_size', '300x355'),
                        'required' => array('post_layout', 'not in', array('masonry','justified')),
                    ),
                    'post_image_width' => array(
                        'id' => 'post_image_width',
                        'title' => esc_html__('Image width', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your image width', 'g5-portfolio'),
                        'type' => 'dimension',
                        'height' => false,
                        'default' => G5PORTFOLIO()->options()->get_default( 'post_image_width', array(
                            'width' => '400'
                        )),
                        'required' => array('post_layout', 'in', array('masonry')),
                    ),

                    'post_image_ratio' => array(
                        'id'       => 'post_image_ratio',
                        'title'    => esc_html__('Image Ratio', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter image ratio', 'g5-portfolio'),
                        'type'     => 'dimension',
                        'required' => array(
                            array('post_image_size', '=', 'full'),
                            array('post_layout', 'not in', array('masonry','justified')),
                        )
                    ),

                    'justified_option_group' => array(
                        'id' => 'justified_option_group',
                        'title' => esc_html__( 'Justified Options', 'g5-portfolio' ),
                        'type' => 'group',
                        'required' => array('post_layout','=','justified'),
                        'fields' => array(
                            'justified_row_height' =>  array(
                                'id'       => 'justified_row_height',
                                'title'    => esc_html__('Row Height', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your portfolio row height', 'g5-portfolio'),
                                'type'     => 'dimension',
                                'width'   => false,
                                'default'  => G5PORTFOLIO()->options()->get_default('justified_row_height',array(
                                    'height' => '200'
                                )),
                            ),
                            'justified_row_max_height' => array(
                                'id'       => 'justified_row_max_height',
                                'title'    => esc_html__('Row Max Height', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your portfolio row max height', 'g5-portfolio'),
                                'type'     => 'dimension',
                                'width'   => false,
                            ),
                        )
                    ),

                    'sorting_group' => array(
	                    'id'       => 'sorting_group',
	                    'title'    => esc_html__( 'Sorting', 'g5-portfolio' ),
	                    'type'     => 'group',
	                    'fields' => array(
		                    'archive_orderby' => array(
			                    'id' => 'archive_orderby',
			                    'title' => esc_html__('Order By', 'g5-portfolio'),
			                    'subtitle' =>  esc_html__('Select how to sort retrieved service.', 'g5-portfolio'),
			                    'type' => 'select',
			                    'options' => G5PORTFOLIO()->settings()->get_portfolio_orderby(),
			                    'default' => G5PORTFOLIO()->options()->get_default( 'archive_orderby', 'date' ),
		                    ),
		                    'archive_order' => array(
			                    'id' => 'archive_order',
			                    'title' => esc_html__('Order', 'g5-portfolio'),
			                    'subtitle' => esc_html__('Select sorting order.', 'g5-portfolio'),
			                    'type' => 'select',
			                    'options' => G5PORTFOLIO()->settings()->get_portfolio_order(),
			                    'default' => G5PORTFOLIO()->options()->get_default( 'archive_order', 'desc' ),
		                    ),
	                    )
                    ),

                    'posts_per_page' => array(
                        'id' => 'posts_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter number of posts per page you want to display.', 'g5-portfolio'),
                        'type' => 'text',
                        'default' => G5PORTFOLIO()->options()->get_default('posts_per_page', ''),
                        'input_type' => 'number',
                    ),
                    'post_paging' => array(
                        'id' => 'post_paging',
                        'title' => esc_html__('Post Paging', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your post paging mode', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_paging_mode(),
                        'default' => G5PORTFOLIO()->options()->get_default('post_paging', 'pagination'),
                    ),
                    'post_animation' => array(
                        'id'       => 'post_animation',
                        'title'    => esc_html__('Animation', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your post animation', 'g5-portfolio'),
                        'type'     => 'select',
                        'options'  => G5CORE()->settings()->get_animation(),
                        'default'  => G5PORTFOLIO()->options()->get_default('post_animation','none'),
                    ),
                    'light_box_mode' => array(
                        'id'       => 'light_box_mode',
                        'type'     => 'select',
                        'title'    => esc_html__('Light Box', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your portfolio light box', 'g5-portfolio'),
                        'options'  => G5PORTFOLIO()->settings()->get_light_box_mode(),
                        'default'  => G5PORTFOLIO()->options()->get_default( 'light_box_mode','feature' ),
                    ),
                    'category_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'category_enable',
                        'title' => esc_html__('Show Category', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default( 'category_enable','on' ),
                    )),
                    'excerpt_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'excerpt_enable',
                        'title' => esc_html__('Show Excerpt', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default( 'excerpt_enable','' ),
                    )),
                )
            );
        }

        public function config_section_single()
        {
            return array(
                'id' => 'section_single',
                'title' => esc_html__('Single Portfolio', 'g5-portfolio'),
                'icon' => 'dashicons dashicons-screenoptions',
                'fields' => array(
                    'single_layout' => array(
                        'id' => 'single_layout',
                        'title' => esc_html__('Layout', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your single portfolio layout', 'g5-portfolio'),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_single_layout(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_layout', 'layout-1'),
                    ),
                    $this->config_group_single_gallery(),
                    'single_navigation_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_navigation_enable',
                        'title' => esc_html__('Navigation', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide navigation on single', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default( 'single_navigation_enable','on' )
                    )),
                    'comment_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'comment_enable',
                        'title' => esc_html__('Comment', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide comment on single', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default( 'comment_enable','' )
                    )),
                    'single_share_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_share_enable',
                        'title' => esc_html__('Share', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide share on single blog', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default( 'single_share_enable','on' ),
                    )),
                    $this->config_group_single_related()
                )
            );
        }

        public function config_group_single_gallery()
        {
            return array(
                'id' => 'single_gallery_group',
                'title' => esc_html__('Gallery', 'g5-portfolio'),
                'type' => 'group',
                'required' => array('single_layout', '!=', 'layout-5'),
                'fields' => array(
                    'single_gallery_layout' => array(
                        'id' => 'single_gallery_layout',
                        'title' => esc_html__('Layout', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your single portfolio gallery layout', 'g5-portfolio'),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_single_gallery_layout(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_layout','slider'),
                    ),
                    'single_gallery_justified_option_group' => array(
                        'id' => 'single_gallery_justified_option_group',
                        'title' => esc_html__( 'Justified Options', 'g5-portfolio' ),
                        'type' => 'group',
                        'required' => array('single_gallery_layout','=','justified'),
                        'fields' => array(
                            'single_gallery_justified_row_height' =>  array(
                                'id'       => 'single_gallery_justified_row_height',
                                'title'    => esc_html__('Row Height', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your portfolio row height', 'g5-portfolio'),
                                'type'     => 'dimension',
                                'width'   => false,
                                'default'  => G5PORTFOLIO()->options()->get_default('single_gallery_justified_row_height',array(
                                    'height' => '200'
                                )),
                            ),
                            'single_gallery_justified_row_max_height' => array(
                                'id'       => 'single_gallery_justified_row_max_height',
                                'title'    => esc_html__('Row Max Height', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your gallery row max height', 'g5-portfolio'),
                                'type'     => 'dimension',
                                'width'   => false,
                            ),
                        )
                    ),
                    'single_gallery_carousel_group' => array(
                        'id' => 'single_gallery_carousel_group',
                        'title' => esc_html__( 'Carousel Options', 'g5-portfolio' ),
                        'type' => 'group',
                        'required' => array('single_gallery_layout','=','carousel'),
                        'fields' => array(
                            'single_gallery_carousel_center_enable' => G5CORE()->fields()->get_config_toggle(array(
                                'id' => 'single_gallery_carousel_center_enable',
                                'title' => esc_html__('Center Mode', 'g5-portfolio'),
                                'subtitle' => esc_html__('Turn On this option if you want to enable center mode', 'g5-portfolio'),
                                'default' => G5PORTFOLIO()->options()->get_default( 'single_gallery_carousel_center_enable','' )
                            )),
                            'single_gallery_carousel_center_padding' => array(
                                'id' => 'single_gallery_carousel_center_padding',
                                'title' => esc_html__( 'Center Padding', 'g5-portfolio' ),
                                'subtitle' => esc_html__( 'Side padding when in center mode (px or %). Default 50px', 'g5-portfolio' ),
                                'type' => 'text',
                                'default' => G5PORTFOLIO()->options()->get_default( 'single_gallery_carousel_center_padding','' ),
                                'required' => array('single_gallery_carousel_center_enable','=','on')

                            )
                        )
                    ),
                    'single_gallery_image_size' => array(
                        'id' => 'single_gallery_image_size',
                        'title' => esc_html__('Image size', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your image size', 'g5-portfolio'),
                        'desc' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-portfolio'),
                        'type' => 'text',
                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_image_size', 'large'),
                        'required' => array('single_gallery_layout', 'not in', array('masonry','justified')),
                    ),
                    'single_gallery_image_ratio' => array(
                        'id'       => 'single_gallery_image_ratio',
                        'title'    => esc_html__('Image Ratio', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter image ratio', 'g5-portfolio'),
                        'type'     => 'dimension',
                        'required' => array(
                            array('single_gallery_image_size', '=', 'full'),
                            array('single_gallery_layout', 'not in', array('masonry','justified')),
                        )
                    ),
                    'single_gallery_image_width' => array(
                        'id' => 'single_gallery_image_width',
                        'title' => esc_html__('Image width', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your image width', 'g5-portfolio'),
                        'type' => 'dimension',
                        'height' => false,
                        'default' => array(
                            'width' => '400'
                        ),
                        'required' => array('single_gallery_layout', 'in', array('masonry')),
                    ),
                    'single_gallery_columns_gutter' => array(
                        'id' => 'single_gallery_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your horizontal space between gallery.', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns_gutter', '30'),
                        'required' => array("single_gallery_layout", 'not in', array('carousel-3d', 'thumbnail', 'slider','justified'))
                    ),
                    'single_gallery_columns_group' => array(
                        'id' => 'single_gallery_columns_group',
                        'title' => esc_html__('Columns', 'g5-portfolio'),
                        'type' => 'group',
                        'required' => array('single_gallery_layout', 'in', array('grid', 'masonry', 'carousel','thumbnail')),
                        'fields' => array(
                            'single_gallery_columns_row_1' => array(
                                'id' => 'single_gallery_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'single_gallery_columns_xl' => array(
                                        'id' => 'single_gallery_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns_xl', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_gallery_columns_lg' => array(
                                        'id' => 'single_gallery_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns_lg', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_gallery_columns_md' => array(
                                        'id' => 'single_gallery_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns_md', '2'),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'single_gallery_columns_row_2' => array(
                                'id' => 'single_gallery_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'single_gallery_columns_sm' => array(
                                        'id' => 'single_gallery_columns_sm',
                                        'title' => esc_html__('Small Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns_sm', '2'),
                                        'layout' => 'full',
                                    ),
                                    'single_gallery_columns' => array(
                                        'id' => 'single_gallery_columns',
                                        'title' => esc_html__('Extra Small Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_gallery_columns', '1'),
                                        'layout' => 'full',
                                    )
                                )
                            )
                        )
                    ),
                    'single_gallery_custom_class' => array(
                        'id' => 'single_gallery_custom_class',
                        'title' => esc_html__('Css Classes', 'g5-portfolio'),
                        'subtitle' => esc_html__('Add custom css classes to gallery', 'g5-portfolio'),
                        'type' => 'text'
                    ),

                )
            );
        }

        public function config_group_single_related()
        {
            return array(
                'id' => 'group_single_related',
                'title' => esc_html__('Related Portfolio', 'g5-portfolio'),
                'type' => 'group',
                'toggle_default' => false,
                'fields' => array(
                    'single_related_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_related_enable',
                        'title' => esc_html__('Related Portfolio', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide related portfolio', 'g5-portfolio'),
                        'default' => G5PORTFOLIO()->options()->get_default('single_related_enable', 'on')
                    )),
                    'single_related_algorithm' => array(
                        'id' => 'single_related_algorithm',
                        'title' => esc_html__('Related Algorithm', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify the algorithm of related portfolio', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5PORTFOLIO()->settings()->get_single_related_algorithm(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_related_algorithm', 'cat'),
                        'required' => array('single_related_enable', '=', 'on')
                    ),
                    'single_related_per_page' => array(
                        'id' => 'single_related_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter number of portfolio per page you want to display', 'g5-portfolio'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5PORTFOLIO()->options()->get_default('single_related_per_page', '6'),
                        'required' => array('single_related_enable', '=', 'on')
                    ),
                    'single_related_columns_gutter' => array(
                        'id' => 'single_related_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your horizontal space between portfolio.', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns_gutter', '30'),
                        'required' => array('single_related_enable', '=', 'on'),
                    ),
                    'single_related_columns_group' => array(
                        'id' => 'single_related_columns_group',
                        'title' => esc_html__('Columns', 'g5-portfolio'),
                        'type' => 'group',
                        'required' => array('single_related_enable', '=', 'on'),
                        'fields' => array(
                            'single_related_columns_row_1' => array(
                                'id' => 'single_related_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'single_related_columns_xl' => array(
                                        'id' => 'single_related_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your post columns on extra large devices (>= 1200px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns_xl', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns_lg' => array(
                                        'id' => 'single_related_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your post columns on large devices (>= 992px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns_lg', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns_md' => array(
                                        'id' => 'single_related_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your post columns on medium devices (>= 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns_md', '2'),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'post_columns_row_2' => array(
                                'id' => 'post_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'single_related_columns_sm' => array(
                                        'id' => 'single_related_columns_sm',
                                        'title' => esc_html__('Small Devices ', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your post columns on small devices (< 768px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns_sm', '2'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns' => array(
                                        'id' => 'single_related_columns',
                                        'title' => esc_html__('Extra Small Devices ', 'g5-portfolio'),
                                        'desc' => esc_html__('Specify your post columns on extra small devices (< 576px)', 'g5-portfolio'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5PORTFOLIO()->options()->get_default('single_related_columns', '1'),
                                        'layout' => 'full',
                                    )
                                )
                            ),
                        )
                    ),
                    'single_related_paging' => array(
                        'id' => 'single_related_paging',
                        'title' => esc_html__('Post Paging', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your post paging mode', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_paging_small_mode(),
                        'default' => G5PORTFOLIO()->options()->get_default('single_related_paging', 'slider'),
                        'required' => array('single_related_enable', '=', 'on'),
                    ),
                )
            );
        }

        public function define_meta_box($configs) {
            $prefix = G5PORTFOLIO()->meta_prefix;
            $configs['g5portfolio_meta'] = array(
                'name' => esc_html__('Portfolio Settings', 'g5-portfolio'),
                'post_type' => array('portfolio'),
                'layout' => 'inline',
                'fields' => array(

                    "{$prefix}single_media_type" => array(
                        'id' => "{$prefix}single_media_type",
                        'title' => esc_html__('Media Type','g5-portfolio'),
                        'subtitle' => esc_html__('Specify your portfolio media type','g5-portfolio'),
                        'type' => 'button_set',
                        'options' => array(
                            'image' => esc_html__('Image','g5-portfolio'),
                            'video' => esc_html__('Video','g5-portfolio')
                        ),
                        'default' => 'image'
                    ),
                    "{$prefix}single_gallery" => array(
                        'id'       => "{$prefix}single_gallery",
                        'title'    => esc_html__('Gallery', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your portfolio gallery', 'g5-portfolio'),
                        'type'     => 'gallery',
                        'required' => array("{$prefix}single_media_type",'=','image')
                    ),
                    "{$prefix}single_video" => array(
                        'id'       => "{$prefix}single_video",
                        'title'    => esc_html__('Video Url', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your portfolio Video Url', 'g5-portfolio'),
                        'type'     => 'text',
                        'width' => '100%',
                        'clone'    => true,
                        'sort'     => true,
                        'required' => array("{$prefix}single_media_type",'=','video')
                    ),
                    "{$prefix}single_additional_details" => array(
                        'id' => "{$prefix}single_additional_details",
                        'title' => esc_html__( 'Additional details', 'g5-portfolio' ),
                        'type'    => 'repeater',
                        'sort'    => true,
                        'default' => array(),
                        'fields' => array(
                            array(
                                'title'   => esc_html__( 'Title', 'g5-portfolio' ),
                                'id'      => "title",
                                'type'    => 'text',
                                'col'     => '4',
                                'default' => '',
                                'desc'    => esc_html__( 'Enter additional title', 'g5-portfolio' ),
                            ),
                            array(
                                'title'   => esc_html__( 'Value', 'g5-portfolio' ),
                                'id'      => "value",
                                'type'    => 'text',
                                'col'     => '8',
                                'width'   => '100%',
                                'default' => '',
                                'desc'    => esc_html__( 'Enter additional value', 'g5-portfolio' ),
                            ),
                        )
                    ),
                    "{$prefix}single_external_link" => array(
                        'id'       => "{$prefix}single_external_link",
                        'title'    => esc_html__('External Link', 'g5-portfolio'),
                        'desc' => esc_html__('Enter URL to link instead of link to single portfolio page from Portfolio List page.', 'g5-portfolio'),
                        'type'     => 'text',
                        'width' => '100%',
                    ),
                    "{$prefix}single_layout" => array(
                        'id' => "{$prefix}single_layout",
                        'title' => esc_html__('Layout', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your single portfolio layout', 'g5-portfolio'),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_single_layout(true),
                        'default' => '',
                    ),
                    "{$prefix}custom_single_gallery" => G5CORE()->fields()->get_config_toggle(array(
                        'id' => "{$prefix}custom_single_gallery",
                        'title' => esc_html__('Custom Gallery Settings', 'g5-portfolio'),
                        'subtitle' => esc_html__('Turn On this option if you want to custom gallery settings', 'g5-portfolio'),
                        'default' => '',
                    )),
                    "{$prefix}single_gallery_group" => array(
                        'id' => "{$prefix}single_gallery_group",
                        'title' => esc_html__( 'Gallery Settings', 'g5-portfolio' ),
                        'type' => 'group',
                        'required' =>  array("{$prefix}custom_single_gallery",'=','on'),
                        'fields' => array(
                            "{$prefix}single_gallery_layout" => array(
                                'id' => "{$prefix}single_gallery_layout",
                                'title' => esc_html__('Layout', 'g5-portfolio'),
                                'subtitle' => esc_html__('Specify your single portfolio gallery layout', 'g5-portfolio'),
                                'type' => 'image_set',
                                'options' => G5PORTFOLIO()->settings()->get_single_gallery_layout(),
                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_layout','slider'),
                            ),
                            "{$prefix}single_gallery_justified_option_group" => array(
                                'id' => "{$prefix}single_gallery_justified_option_group",
                                'title' => esc_html__( 'Justified Options', 'g5-portfolio' ),
                                'type' => 'group',
                                'required' => array("{$prefix}single_gallery_layout",'=','justified'),
                                'fields' => array(
                                    "{$prefix}single_gallery_justified_row_height" =>  array(
                                        'id'       => "{$prefix}single_gallery_justified_row_height",
                                        'title'    => esc_html__('Row Height', 'g5-portfolio'),
                                        'subtitle' => esc_html__('Enter your portfolio row height', 'g5-portfolio'),
                                        'type'     => 'dimension',
                                        'width'   => false,
                                        'default'  => G5PORTFOLIO()->options()->get_option('single_gallery_justified_row_height',array(
                                            'height' => '200'
                                        )),
                                    ),
                                    'single_gallery_justified_row_max_height' => array(
                                        'id'       => 'single_gallery_justified_row_max_height',
                                        'title'    => esc_html__('Row Max Height', 'g5-portfolio'),
                                        'subtitle' => esc_html__('Enter your gallery row max height', 'g5-portfolio'),
                                        'type'     => 'dimension',
                                        'width'   => false,
                                    ),
                                )
                            ),
                            "{$prefix}single_gallery_carousel_group" => array(
                                'id' => "{$prefix}single_gallery_carousel_group",
                                'title' => esc_html__( 'Carousel Options', 'g5-portfolio' ),
                                'type' => 'group',
                                'required' => array("{$prefix}single_gallery_layout",'=','carousel'),
                                'fields' => array(
                                    "{$prefix}single_gallery_carousel_center_enable" => G5CORE()->fields()->get_config_toggle(array(
                                        'id' => "{$prefix}single_gallery_carousel_center_enable",
                                        'title' => esc_html__('Center Mode', 'g5-portfolio'),
                                        'subtitle' => esc_html__('Turn On this option if you want to enable center mode', 'g5-portfolio'),
                                        'default' => G5PORTFOLIO()->options()->get_option( 'single_gallery_carousel_center_enable')
                                    )),
                                    "{$prefix}single_gallery_carousel_center_padding" => array(
                                        'id' => "{$prefix}single_gallery_carousel_center_padding",
                                        'title' => esc_html__( 'Center Padding', 'g5-portfolio' ),
                                        'subtitle' => esc_html__( 'Side padding when in center mode (px or %). Default 50px', 'g5-portfolio' ),
                                        'type' => 'text',
                                        'default' => G5PORTFOLIO()->options()->get_option( 'single_gallery_carousel_center_padding'),
                                        'required' => array("{$prefix}single_gallery_carousel_center_enable" ,'=','on')

                                    )
                                )
                            ),
                            "{$prefix}single_gallery_image_size" => array(
                                'id' => "{$prefix}single_gallery_image_size",
                                'title' => esc_html__('Image size', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your image size', 'g5-portfolio'),
                                'desc' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-portfolio'),
                                'type' => 'text',
                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_image_size', 'medium'),
                                'required' => array("{$prefix}single_gallery_layout", 'not in', array('masonry','justified')),
                            ),
                            "{$prefix}single_gallery_image_ratio" => array(
                                'id'       => "{$prefix}single_gallery_image_ratio",
                                'title'    => esc_html__('Image Ratio', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter image ratio', 'g5-portfolio'),
                                'type'     => 'dimension',
                                'required' => array(
                                    array("{$prefix}single_gallery_image_size", '=', 'full'),
                                    array("{$prefix}single_gallery_layout", 'not in', array('masonry','justified')),
                                )
                            ),
                            "{$prefix}single_gallery_image_width" => array(
                                'id' => "{$prefix}single_gallery_image_width",
                                'title' => esc_html__('Image width', 'g5-portfolio'),
                                'subtitle' => esc_html__('Enter your image width', 'g5-portfolio'),
                                'type' => 'dimension',
                                'height' => false,
                                'default' => array(
                                    'width' => '400'
                                ),
                                'required' => array("{$prefix}single_gallery_layout", 'in', array('masonry')),
                            ),
                            "{$prefix}single_gallery_columns_gutter" => array(
                                'id' => "{$prefix}single_gallery_columns_gutter",
                                'title' => esc_html__('Columns Gutter', 'g5-portfolio'),
                                'subtitle' => esc_html__('Specify your horizontal space between gallery.', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns_gutter(),
                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns_gutter', '30'),
                                'required' => array( "{$prefix}single_gallery_layout", 'not in', array('carousel-3d', 'thumbnail', 'slider','justified'))
                            ),
                            "{$prefix}single_gallery_columns_group" => array(
                                'id' => "{$prefix}single_gallery_columns_group",
                                'title' => esc_html__('Columns', 'g5-portfolio'),
                                'type' => 'group',
                                'required' => array("{$prefix}single_gallery_layout", 'in', array('grid', 'masonry', 'carousel','thumbnail')),
                                'fields' => array(
                                    "{$prefix}single_gallery_columns_row_1" => array(
                                        'id' => "{$prefix}single_gallery_columns_row_1",
                                        'type' => 'row',
                                        'col' => 3,
                                        'fields' => array(
                                            "{$prefix}single_gallery_columns_xl" => array(
                                                'id' => "{$prefix}single_gallery_columns_xl",
                                                'title' => esc_html__('Extra Large Devices', 'g5-portfolio'),
                                                'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-portfolio'),
                                                'type' => 'select',
                                                'options' => G5CORE()->settings()->get_post_columns(),
                                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns_xl', '3'),
                                                'layout' => 'full',
                                            ),
                                            "{$prefix}single_gallery_columns_lg" => array(
                                                'id' => "{$prefix}single_gallery_columns_lg",
                                                'title' => esc_html__('Large Devices', 'g5-portfolio'),
                                                'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-portfolio'),
                                                'type' => 'select',
                                                'options' => G5CORE()->settings()->get_post_columns(),
                                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns_lg', '3'),
                                                'layout' => 'full',
                                            ),
                                            "{$prefix}single_gallery_columns_md" => array(
                                                'id' => "{$prefix}single_gallery_columns_md",
                                                'title' => esc_html__('Medium Devices', 'g5-portfolio'),
                                                'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-portfolio'),
                                                'type' => 'select',
                                                'options' => G5CORE()->settings()->get_post_columns(),
                                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns_md', '2'),
                                                'layout' => 'full',
                                            ),
                                        )
                                    ),
                                    "{$prefix}single_gallery_columns_row_2" => array(
                                        'id' => "{$prefix}single_gallery_columns_row_2",
                                        'type' => 'row',
                                        'col' => 3,
                                        'fields' => array(
                                            "{$prefix}single_gallery_columns_sm" => array(
                                                'id' => "{$prefix}single_gallery_columns_sm",
                                                'title' => esc_html__('Small Devices', 'g5-portfolio'),
                                                'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-portfolio'),
                                                'type' => 'select',
                                                'options' => G5CORE()->settings()->get_post_columns(),
                                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns_sm', '2'),
                                                'layout' => 'full',
                                            ),
                                            "{$prefix}single_gallery_columns" => array(
                                                'id' => "{$prefix}single_gallery_columns",
                                                'title' => esc_html__('Extra Small Devices', 'g5-portfolio'),
                                                'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-portfolio'),
                                                'type' => 'select',
                                                'options' => G5CORE()->settings()->get_post_columns(),
                                                'default' => G5PORTFOLIO()->options()->get_option('single_gallery_columns', '1'),
                                                'layout' => 'full',
                                            )
                                        )
                                    )
                                )
                            ),
                            "{$prefix}single_gallery_custom_class" => array(
                                'id' => "{$prefix}single_gallery_custom_class",
                                'title' => esc_html__('Css Classes', 'g5-portfolio'),
                                'subtitle' => esc_html__('Add custom css classes to gallery', 'g5-portfolio'),
                                'type' => 'text'
                            ),
                        )
                    )

                )
            );
            return $configs;
        }

        public function pre_get_posts($query) {
            if (!is_admin() && $query->is_main_query() && ($query->is_post_type_archive( 'portfolio' ) || $query->is_tax( get_object_taxonomies( 'portfolio' )))) {
                $posts_per_page = absint(G5PORTFOLIO()->options()->get_option('posts_per_page'));
                if (!empty($posts_per_page)) {
                    $query->set('posts_per_page', $posts_per_page);
                }
            }
        }

        public function term_meta_page_title($terms) {
            $terms[] = 'portfolio_category';
            return $terms;
        }

        public function change_single_setting() {
            if (g5portfolio_is_single()) {
                $prefix = G5PORTFOLIO()->meta_prefix;
                $single_layout = get_post_meta(get_the_ID(),"{$prefix}single_layout",true);
                if (!empty($single_layout)) {
                    G5PORTFOLIO()->options()->set_option('single_layout',$single_layout);
                }

                $custom_single_gallery = get_post_meta(get_the_ID(),"{$prefix}custom_single_gallery",true);
                if ($custom_single_gallery === 'on') {
                    $settings = array(
                        'single_gallery_layout',
                        'single_gallery_justified_row_height',
                        'single_gallery_justified_row_max_height',
                        'single_gallery_carousel_center_enable',
                        'single_gallery_carousel_center_padding',
                        'single_gallery_image_size',
                        'single_gallery_image_ratio',
                        'single_gallery_image_width',
                        'single_gallery_columns_gutter',
                        'single_gallery_columns_xl',
                        'single_gallery_columns_lg',
                        'single_gallery_columns_md',
                        'single_gallery_columns_sm',
                        'single_gallery_columns',
                        'single_gallery_custom_class'
                    );

                    foreach ($settings as $setting) {
                        $v = get_post_meta(get_the_ID(),"{$prefix}{$setting}",true);
                        G5PORTFOLIO()->options()->set_option($setting, $v);
                    }
                }

            }

        }

        public function change_default_options($defaults) {
            return wp_parse_args(array(
                'portfolio_archive__site_layout' => 'none',
                'portfolio_single__site_layout' => 'none'
            ),$defaults) ;
        }
    }
}