<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('G5Services_Config_Options')) {
    class G5Services_Config_Options
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
	        add_filter('g5core_admin_bar_theme_options', array($this, 'admin_bar_theme_options'), 300);

            add_filter('g5core_taxonomy_for_term_meta',array($this,'term_meta_page_title'));

            add_filter( 'g5core_default_options_g5core_options', array($this,'change_default_options') );

	        add_filter('gsf_meta_box_config', array($this, 'define_meta_box'));

	        add_action('template_redirect', array($this, 'change_single_setting'));
        }

	    public function admin_bar_theme_options($admin_bar_theme_options) {
		    $admin_bar_theme_options['g5services_options'] = array(
			    'title' => esc_html__('Services','g5-services'),
			    'permission' => 'manage_options',
		    );
		    return $admin_bar_theme_options;
	    }

        public function define_options($configs)
        {
            $configs['g5services_options'] = array(
                'layout' => 'inline',
                'page_title' => esc_html__('Services Options', 'g5-services'),
                'menu_title' => esc_html__('Services', 'g5-services'),
                'option_name' => 'g5services_options',
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
                'title' => esc_html__('Archive Listing', 'g5-services'),
                'icon' => 'dashicons dashicons-category',
                'fields' => array(
                    'category_filter_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'category_filter_enable',
                        'title' => esc_html__('Category Filter Enable', 'g5-services'),
                        'subtitle' => esc_html__('Turn On this option if you want to enable category filter', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default('category_filter_enable', ''),
                    )),
                    'category_filter_align' => array(
	                    'id' => 'category_filter_align',
	                    'title' => esc_html__('Category Filter Align','g5-services'),
	                    'subtitle' => esc_html__('Specify your category filter align','g5-services'),
	                    'type' => 'button_set',
	                    'options' => G5CORE()->settings()->get_category_filter_align(),
	                    'default' => G5SERVICES()->options()->get_default('category_filter_align', ''),
	                    'required' => array('category_filter_enable','=','on')
                    ),
                    'append_tabs' =>  array(
                        'id' => 'append_tabs',
                        'title' => esc_html__('Append Categories','g5-services'),
                        'subtitle' => esc_html__('Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)','g5-services'),
                        'type' => 'text',
                        'default' => G5SERVICES()->options()->get_default( 'append_tabs','' ),
                        'required' => array('category_filter_enable','=','on')
                    ),

                    'post_layout' => array(
                        'id' => 'post_layout',
                        'title' => esc_html__('Layout', 'g5-services'),
                        'subtitle' => esc_html__('Specify your services layout', 'g5-services'),
                        'type' => 'image_set',
                        'options' => G5SERVICES()->settings()->get_services_layout(),
                        'default' => G5SERVICES()->options()->get_default('post_layout', 'grid'),
                    ),
                    'item_skin' => array(
                        'id' => 'item_skin',
                        'title' => esc_html__('Item Skin', 'g5-services'),
                        'subtitle' => esc_html__('Specify your services item skin', 'g5-services'),
                        'type' => 'image_set',
                        'options' => G5SERVICES()->settings()->get_services_skins(),
                        'default' => G5SERVICES()->options()->get_default('item_skin', 'skin-01'),
                    ),
                    'item_custom_class' => array(
                        'id' => 'item_custom_class',
                        'title' => esc_html__('Item Css Classes', 'g5-services'),
                        'subtitle' => esc_html__('Add custom css classes to item', 'g5-services'),
                        'type' => 'text'
                    ),

                    'post_columns_gutter' => array(
                        'id' => 'post_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-services'),
                        'subtitle' => esc_html__('Specify your horizontal space between item.', 'g5-services'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SERVICES()->options()->get_default('post_columns_gutter', '30'),
                    ),
                    'post_columns_group' => array(
                        'id' => 'post_columns_group',
                        'title' => esc_html__('Columns', 'g5-services'),
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
                                        'title' => esc_html__('Extra Large Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your columns on extra large devices (>= 1200px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('post_columns_xl', '3'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_lg' => array(
                                        'id' => 'post_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your columns on large devices (>= 992px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('post_columns_lg', '3'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_md' => array(
                                        'id' => 'post_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your columns on medium devices (>= 768px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('post_columns_md', '2'),
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
                                        'title' => esc_html__('Small Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your columns on small devices (< 768px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('post_columns_sm', '2'),
                                        'layout' => 'full',
                                    ),
                                    'post_columns' => array(
                                        'id' => 'post_columns',
                                        'title' => esc_html__('Extra Small Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your columns on extra small devices (< 576px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('post_columns', '1'),
                                        'layout' => 'full',
                                    )
                                )
                            )
                        )
                    ),
                    'post_image_size' => array(
                        'id' => 'post_image_size',
                        'title' => esc_html__('Image size', 'g5-services'),
                        'subtitle' => esc_html__('Enter your image size', 'g5-services'),
                        'desc' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-services'),
                        'type' => 'text',
                        'default' => G5SERVICES()->options()->get_default('post_image_size', '300x355'),
                        'required' => array('post_layout', 'not in', array('masonry','justified')),
                    ),
                    'post_image_ratio' => array(
                        'id'       => 'post_image_ratio',
                        'title'    => esc_html__('Image Ratio', 'g5-services'),
                        'subtitle' => esc_html__('Enter image ratio', 'g5-services'),
                        'type'     => 'dimension',
                        'required' => array(
                            array('post_image_size', '=', 'full'),
                            array('post_layout', 'not in', array('masonry','justified')),
                        )
                    ),
                    'sorting_group' => array(
	                    'id'       => 'sorting_group',
	                    'title'    => esc_html__( 'Sorting', 'g5-services' ),
	                    'type'     => 'group',
	                    'fields' => array(
		                    'archive_orderby' => array(
			                    'id' => 'archive_orderby',
			                    'title' => esc_html__('Order By', 'g5-services'),
			                    'subtitle' =>  esc_html__('Select how to sort retrieved service.', 'g5-services'),
			                    'type' => 'select',
			                    'options' => G5SERVICES()->settings()->get_services_orderby(),
			                    'default' => G5SERVICES()->options()->get_default( 'archive_orderby', 'date' ),
		                    ),
		                    'archive_order' => array(
			                    'id' => 'archive_order',
			                    'title' => esc_html__('Order', 'g5-services'),
			                    'subtitle' => esc_html__('Select sorting order.', 'g5-services'),
			                    'type' => 'select',
			                    'options' => G5SERVICES()->settings()->get_services_order(),
			                    'default' => G5SERVICES()->options()->get_default( 'archive_order', 'desc' ),
		                    ),
	                    )
                    ),


                    'posts_per_page' => array(
                        'id' => 'posts_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-services'),
                        'subtitle' => esc_html__('Enter number of posts per page you want to display.', 'g5-services'),
                        'type' => 'text',
                        'default' => G5SERVICES()->options()->get_default('posts_per_page', ''),
                        'input_type' => 'number',
                    ),
                    'post_paging' => array(
                        'id' => 'post_paging',
                        'title' => esc_html__('Post Paging', 'g5-services'),
                        'subtitle' => esc_html__('Specify your post paging mode', 'g5-services'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_paging_mode(),
                        'default' => G5SERVICES()->options()->get_default('post_paging', 'pagination'),
                    ),
                    'post_animation' => array(
                        'id'       => 'post_animation',
                        'title'    => esc_html__('Animation', 'g5-services'),
                        'subtitle' => esc_html__('Specify your post animation', 'g5-services'),
                        'type'     => 'select',
                        'options'  => G5CORE()->settings()->get_animation(),
                        'default'  => G5SERVICES()->options()->get_default('post_animation','none'),
                    ),
                    'category_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'category_enable',
                        'title' => esc_html__('Show Category', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default( 'category_enable','' ),
                    )),
                    'excerpt_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'excerpt_enable',
                        'title' => esc_html__('Show Excerpt', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default( 'excerpt_enable','on' ),
                    )),
                )
            );
        }

        public function config_section_single()
        {
            return array(
                'id' => 'section_single',
                'title' => esc_html__('Single Services', 'g5-services'),
                'icon' => 'dashicons dashicons-screenoptions',
                'fields' => array(
	                'single_title_enable' => G5CORE()->fields()->get_config_toggle(array(
		                'id' => 'single_title_enable',
		                'title' => esc_html__('Show Title', 'g5-services'),
		                'subtitle' => esc_html__('Turn Off this option if you want to hide title on single', 'g5-services'),
		                'default' => G5SERVICES()->options()->get_default( 'single_title_enable','on' )
	                )),
                    'single_navigation_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_navigation_enable',
                        'title' => esc_html__('Navigation', 'g5-services'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide navigation on single', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default( 'single_navigation_enable','on' )
                    )),
                    'comment_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'comment_enable',
                        'title' => esc_html__('Comment', 'g5-services'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide comment on single', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default( 'comment_enable','' )
                    )),
                    'single_share_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_share_enable',
                        'title' => esc_html__('Share', 'g5-services'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide share on single services', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default( 'single_share_enable','on' ),
                    )),
                    $this->config_group_single_related()
                )
            );
        }


        public function config_group_single_related()
        {
            return array(
                'id' => 'group_single_related',
                'title' => esc_html__('Related Services', 'g5-services'),
                'type' => 'group',
                'toggle_default' => false,
                'fields' => array(
                    'single_related_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_related_enable',
                        'title' => esc_html__('Related Services', 'g5-services'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide related services', 'g5-services'),
                        'default' => G5SERVICES()->options()->get_default('single_related_enable', 'on')
                    )),
                    'single_related_algorithm' => array(
                        'id' => 'single_related_algorithm',
                        'title' => esc_html__('Related Algorithm', 'g5-services'),
                        'subtitle' => esc_html__('Specify the algorithm of related services', 'g5-services'),
                        'type' => 'select',
                        'options' => G5SERVICES()->settings()->get_single_related_algorithm(),
                        'default' => G5SERVICES()->options()->get_default('single_related_algorithm', 'cat'),
                        'required' => array('single_related_enable', '=', 'on')
                    ),
                    'single_related_per_page' => array(
                        'id' => 'single_related_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-services'),
                        'subtitle' => esc_html__('Enter number of services per page you want to display', 'g5-services'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5SERVICES()->options()->get_default('single_related_per_page', '6'),
                        'required' => array('single_related_enable', '=', 'on')
                    ),
                    'single_related_columns_gutter' => array(
                        'id' => 'single_related_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-services'),
                        'subtitle' => esc_html__('Specify your horizontal space between services.', 'g5-services'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SERVICES()->options()->get_default('single_related_columns_gutter', '30'),
                        'required' => array('single_related_enable', '=', 'on'),
                    ),
                    'single_related_columns_group' => array(
                        'id' => 'single_related_columns_group',
                        'title' => esc_html__('Columns', 'g5-services'),
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
                                        'title' => esc_html__('Extra Large Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your post columns on extra large devices (>= 1200px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('single_related_columns_xl', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns_lg' => array(
                                        'id' => 'single_related_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your post columns on large devices (>= 992px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('single_related_columns_lg', '3'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns_md' => array(
                                        'id' => 'single_related_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-services'),
                                        'desc' => esc_html__('Specify your post columns on medium devices (>= 768px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('single_related_columns_md', '2'),
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
                                        'title' => esc_html__('Small Devices ', 'g5-services'),
                                        'desc' => esc_html__('Specify your post columns on small devices (< 768px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('single_related_columns_sm', '2'),
                                        'layout' => 'full',
                                    ),
                                    'single_related_columns' => array(
                                        'id' => 'single_related_columns',
                                        'title' => esc_html__('Extra Small Devices ', 'g5-services'),
                                        'desc' => esc_html__('Specify your post columns on extra small devices (< 576px)', 'g5-services'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SERVICES()->options()->get_default('single_related_columns', '1'),
                                        'layout' => 'full',
                                    )
                                )
                            ),
                        )
                    ),
                    'single_related_paging' => array(
                        'id' => 'single_related_paging',
                        'title' => esc_html__('Post Paging', 'g5-services'),
                        'subtitle' => esc_html__('Specify your post paging mode', 'g5-services'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_paging_small_mode(),
                        'default' => G5SERVICES()->options()->get_default('single_related_paging', 'slider'),
                        'required' => array('single_related_enable', '=', 'on'),
                    ),
                )
            );
        }

        public function define_meta_box($configs) {
	        $prefix = G5SERVICES()->meta_prefix;
	        $configs['g5services_meta'] = array(
		        'name' => esc_html__('Services Settings', 'g5-services'),
		        'post_type' => array('services'),
		        'layout' => 'inline',
		        'fields' => array(
			        "{$prefix}single_title_enable" => G5CORE()->fields()->get_config_toggle(array(
				        'id' => "{$prefix}single_title_enable",
				        'title' => esc_html__('Show Title', 'g5-services'),
				        'subtitle' => esc_html__('Turn Off this option if you want to hide title on single', 'g5-services'),
				        'default' => ''
			        ),true),
		        )
	        );
	        return $configs;

        }

        public function term_meta_page_title($terms) {
            $terms[] = 'services_category';
            return $terms;
        }

        public function change_default_options($defaults) {
            return wp_parse_args(array(
                'services_archive__site_layout' => 'none',
                'services_single__site_layout' => 'none'
            ),$defaults) ;
        }

        public function change_single_setting() {
	        if (g5services_is_single()) {
		        $prefix = G5SERVICES()->meta_prefix;
		        $single_title_enable = get_post_meta(get_the_ID(),"{$prefix}single_title_enable",true);
		        if (!empty($single_title_enable)) {
			        G5SERVICES()->options()->set_option('single_title_enable',$single_title_enable);
		        }
	        }
        }
    }
}