<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'G5Staff_Config_Options' ) ) {
	class G5Staff_Config_Options {
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_filter( 'gsf_option_config', array( $this, 'define_options' ), 300 );
			add_filter( 'g5core_admin_bar_theme_options', array( $this, 'admin_bar_theme_options' ), 300 );


			add_filter( 'g5core_default_options_g5core_options', array( $this, 'change_default_options' ) );

			add_filter( 'gsf_meta_box_config', array( $this, 'define_meta_box' ) );

			add_action('template_redirect', array($this, 'change_single_setting'));
		}

		public function admin_bar_theme_options( $admin_bar_theme_options ) {
			$admin_bar_theme_options['g5staff_options'] = array(
				'title'      => esc_html__( 'Staff', 'g5-staff' ),
				'permission' => 'manage_options',
			);

			return $admin_bar_theme_options;
		}

		public function define_options( $configs ) {
			$configs['g5staff_options'] = array(
				'layout'      => 'inline',
				'page_title'  => esc_html__( 'Staff Options', 'g5-staff' ),
				'menu_title'  => esc_html__( 'Staff', 'g5-staff' ),
				'option_name' => 'g5staff_options',
				'parent_slug' => 'g5core_options',
				'permission'  => 'manage_options',
				'section'     => array(
					$this->config_section_archive(),
					$this->config_section_single()
				)
			);

			return $configs;
		}

		public function config_section_archive() {
			return array(
				'id'     => 'section_archive',
				'title'  => esc_html__( 'Archive Listing', 'g5-staff' ),
				'icon'   => 'dashicons dashicons-category',
				'fields' => array(
					'group_filter_enable' => G5CORE()->fields()->get_config_toggle(array(
						'id' => 'group_filter_enable',
						'title' => esc_html__('Group Filter Enable', 'g5-staff'),
						'subtitle' => esc_html__('Turn On this option if you want to enable group filter', 'g5-staff'),
						'default' => G5STAFF()->options()->get_default('group_filter_enable', ''),
					)),
					'group_filter_align' => array(
						'id' => 'group_filter_align',
						'title' => esc_html__('Group Filter Align','g5-staff'),
						'subtitle' => esc_html__('Specify your group filter align','g5-staff'),
						'type' => 'button_set',
						'options' => G5CORE()->settings()->get_category_filter_align(),
						'default' => G5STAFF()->options()->get_default('group_filter_align', ''),
						'required' => array('group_filter_enable','=','on')
					),
					'append_tabs' =>  array(
						'id' => 'append_tabs',
						'title' => esc_html__('Append Group','g5-staff'),
						'subtitle' => esc_html__('Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)','g5-staff'),
						'type' => 'text',
						'default' => G5STAFF()->options()->get_default( 'append_tabs','' ),
						'required' => array('group_filter_enable','=','on')
					),

					'post_layout'       => array(
						'id'       => 'post_layout',
						'title'    => esc_html__( 'Layout', 'g5-staff' ),
						'subtitle' => esc_html__( 'Specify your staff layout', 'g5-staff' ),
						'type'     => 'image_set',
						'options'  => G5STAFF()->settings()->get_staff_layout(),
						'default'  => G5STAFF()->options()->get_default( 'post_layout', 'grid' ),
					),
					'item_skin'         => array(
						'id'       => 'item_skin',
						'title'    => esc_html__( 'Item Skin', 'g5-staff' ),
						'subtitle' => esc_html__( 'Specify your staff item skin', 'g5-staff' ),
						'type'     => 'image_set',
						'options'  => G5STAFF()->settings()->get_staff_skins(),
						'default'  => G5STAFF()->options()->get_default( 'item_skin', 'skin-01' ),
					),
					'item_custom_class' => array(
						'id'       => 'item_custom_class',
						'title'    => esc_html__( 'Item Css Classes', 'g5-staff' ),
						'subtitle' => esc_html__( 'Add custom css classes to item', 'g5-staff' ),
						'type'     => 'text'
					),

					'post_columns_gutter' => array(
						'id'       => 'post_columns_gutter',
						'title'    => esc_html__( 'Columns Gutter', 'g5-staff' ),
						'subtitle' => esc_html__( 'Specify your horizontal space between item.', 'g5-staff' ),
						'type'     => 'select',
						'options'  => G5CORE()->settings()->get_post_columns_gutter(),
						'default'  => G5STAFF()->options()->get_default( 'post_columns_gutter', '30' ),
					),
					'post_columns_group'  => array(
						'id'       => 'post_columns_group',
						'title'    => esc_html__( 'Columns', 'g5-staff' ),
						'type'     => 'group',
						'required' => array( 'post_layout', 'in', array( 'grid', 'masonry', 'carousel' ) ),
						'fields'   => array(
							'post_columns_row_1' => array(
								'id'     => 'post_columns_row_1',
								'type'   => 'row',
								'col'    => 3,
								'fields' => array(
									'post_columns_xl' => array(
										'id'      => 'post_columns_xl',
										'title'   => esc_html__( 'Extra Large Devices', 'g5-staff' ),
										'desc'    => esc_html__( 'Specify your columns on extra large devices (>= 1200px)', 'g5-staff' ),
										'type'    => 'select',
										'options' => G5CORE()->settings()->get_post_columns(),
										'default' => G5STAFF()->options()->get_default( 'post_columns_xl', '4' ),
										'layout'  => 'full',
									),
									'post_columns_lg' => array(
										'id'      => 'post_columns_lg',
										'title'   => esc_html__( 'Large Devices', 'g5-staff' ),
										'desc'    => esc_html__( 'Specify your columns on large devices (>= 992px)', 'g5-staff' ),
										'type'    => 'select',
										'options' => G5CORE()->settings()->get_post_columns(),
										'default' => G5STAFF()->options()->get_default( 'post_columns_lg', '4' ),
										'layout'  => 'full',
									),
									'post_columns_md' => array(
										'id'      => 'post_columns_md',
										'title'   => esc_html__( 'Medium Devices', 'g5-staff' ),
										'desc'    => esc_html__( 'Specify your columns on medium devices (>= 768px)', 'g5-staff' ),
										'type'    => 'select',
										'options' => G5CORE()->settings()->get_post_columns(),
										'default' => G5STAFF()->options()->get_default( 'post_columns_md', '3' ),
										'layout'  => 'full',
									),
								)
							),
							'post_columns_row_2' => array(
								'id'     => 'post_columns_row_2',
								'type'   => 'row',
								'col'    => 3,
								'fields' => array(
									'post_columns_sm' => array(
										'id'      => 'post_columns_sm',
										'title'   => esc_html__( 'Small Devices', 'g5-staff' ),
										'desc'    => esc_html__( 'Specify your columns on small devices (< 768px)', 'g5-staff' ),
										'type'    => 'select',
										'options' => G5CORE()->settings()->get_post_columns(),
										'default' => G5STAFF()->options()->get_default( 'post_columns_sm', '2' ),
										'layout'  => 'full',
									),
									'post_columns'    => array(
										'id'      => 'post_columns',
										'title'   => esc_html__( 'Extra Small Devices', 'g5-staff' ),
										'desc'    => esc_html__( 'Specify your columns on extra small devices (< 576px)', 'g5-staff' ),
										'type'    => 'select',
										'options' => G5CORE()->settings()->get_post_columns(),
										'default' => G5STAFF()->options()->get_default( 'post_columns', '1' ),
										'layout'  => 'full',
									)
								)
							)
						)
					),
					'post_image_size'     => array(
						'id'       => 'post_image_size',
						'title'    => esc_html__( 'Image size', 'g5-staff' ),
						'subtitle' => esc_html__( 'Enter your image size', 'g5-staff' ),
						'desc'     => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-staff' ),
						'type'     => 'text',
						'default'  => G5STAFF()->options()->get_default( 'post_image_size', '300x355' ),
						'required' => array( 'post_layout', 'not in', array( 'masonry', 'justified' ) ),
					),
					'post_image_ratio'    => array(
						'id'       => 'post_image_ratio',
						'title'    => esc_html__( 'Image Ratio', 'g5-staff' ),
						'subtitle' => esc_html__( 'Enter image ratio', 'g5-staff' ),
						'type'     => 'dimension',
						'required' => array(
							array( 'post_image_size', '=', 'full' ),
							array( 'post_layout', 'not in', array( 'masonry', 'justified' ) ),
						)
					),

					'sorting_group' => array(
						'id'       => 'sorting_group',
						'title'    => esc_html__( 'Sorting', 'g5-staff' ),
						'type'     => 'group',
						'fields' => array(
							'archive_orderby' => array(
								'id' => 'archive_orderby',
								'title' => esc_html__('Order By', 'g5-staff'),
								'subtitle' =>  esc_html__('Select how to sort retrieved staff.', 'g5-staff'),
								'type' => 'select',
								'options' =>G5STAFF()->settings()->get_staff_orderby(),
								'default' => G5STAFF()->options()->get_default( 'archive_orderby', 'date' ),
							),
							'archive_order' => array(
								'id' => 'archive_order',
								'title' => esc_html__('Order', 'g5-staff'),
								'subtitle' => esc_html__('Select sorting order.', 'g5-staff'),
								'type' => 'select',
								'options' => G5STAFF()->settings()->get_staff_order(),
								'default' => G5STAFF()->options()->get_default( 'archive_order', 'desc' ),
							),
						)
					),

					'posts_per_page'  => array(
						'id'         => 'posts_per_page',
						'title'      => esc_html__( 'Posts Per Page', 'g5-staff' ),
						'subtitle'   => esc_html__( 'Enter number of posts per page you want to display.', 'g5-staff' ),
						'type'       => 'text',
						'default'    => G5STAFF()->options()->get_default( 'posts_per_page', '' ),
						'input_type' => 'number',
					),
					'post_paging'     => array(
						'id'       => 'post_paging',
						'title'    => esc_html__( 'Post Paging', 'g5-staff' ),
						'subtitle' => esc_html__( 'Specify your post paging mode', 'g5-staff' ),
						'type'     => 'select',
						'options'  => G5CORE()->settings()->get_post_paging_mode(),
						'default'  => G5STAFF()->options()->get_default( 'post_paging', 'pagination' ),
					),
					'post_animation'  => array(
						'id'       => 'post_animation',
						'title'    => esc_html__( 'Animation', 'g5-staff' ),
						'subtitle' => esc_html__( 'Specify your post animation', 'g5-staff' ),
						'type'     => 'select',
						'options'  => G5CORE()->settings()->get_animation(),
						'default'  => G5STAFF()->options()->get_default( 'post_animation', 'none' ),
					),
					'single_link_disable' => G5CORE()->fields()->get_config_toggle( array(
						'id'      => 'single_link_disable',
						'title'   => esc_html__( 'Hide Single Link', 'g5-staff' ),
						'subtitle' => esc_html__( 'Turn On this option if you want to hide link to staff details', 'g5-staff' ),
						'default' => G5STAFF()->options()->get_default( 'single_link_disable', '' ),
					) ),
					'social_profiles_enable'  => G5CORE()->fields()->get_config_toggle( array(
						'id'      => 'social_profiles_enable',
						'title'   => esc_html__( 'Show Social Profiles', 'g5-staff' ),
						'default' => G5STAFF()->options()->get_default( 'social_profiles_enable', 'on' ),
					) ),
					'excerpt_enable'  => G5CORE()->fields()->get_config_toggle( array(
						'id'      => 'excerpt_enable',
						'title'   => esc_html__( 'Show Excerpt', 'g5-staff' ),
						'default' => G5STAFF()->options()->get_default( 'excerpt_enable', '' ),
					) ),
				)
			);
		}

		public function config_section_single() {
			return array(
				'id'     => 'section_single',
				'title'  => esc_html__( 'Single Staff', 'g5-staff' ),
				'icon'   => 'dashicons dashicons-screenoptions',
				'fields' => array(
					'single_layout' => array(
						'id' => 'single_layout',
						'title' => esc_html__('Layout', 'g5-staff'),
						'subtitle' => esc_html__('Specify your single staff layout', 'g5-staff'),
						'type' => 'image_set',
						'options' => G5STAFF()->settings()->get_single_layout(),
						'default' => G5STAFF()->options()->get_default('single_layout', 'layout-1'),
					)
				)
			);
		}

		public function change_default_options( $defaults ) {
			return wp_parse_args( array(
				'staff_archive__site_layout' => 'none'
			), $defaults );
		}

		public function define_meta_box( $configs ) {
			$prefix                  = G5STAFF()->meta_prefix;
			$configs['g5staff_meta'] = array(
				'name'      => esc_html__( 'Staff Settings', 'g5-staff' ),
				'post_type' => array( 'staff' ),
				'layout'    => 'inline',
				'fields'    => array(
					"{$prefix}single_layout" => array(
						'id'    => "{$prefix}single_layout",
						'title' => esc_html__( 'Layout', 'g5-staff' ),
						'subtitle' => esc_html__('Specify your single staff layout', 'g5-staff'),
						'type' => 'image_set',
						'options' => G5STAFF()->settings()->get_single_layout(true),
						'default' => '',
					),
					"{$prefix}job_title" => array(
						'id'    => "{$prefix}job_title",
						'title' => esc_html__( 'Job Title', 'g5-staff' ),
						'desc'  => esc_html__( 'Enter job or role of this staff', 'g5-staff' ),
						'type'  => 'text',
					),
					"{$prefix}staff_info" => array(
						'id' => "{$prefix}staff_info",
						'title' => esc_html__( 'Staff Info', 'g5-staff' ),
						'type'    => 'repeater',
						'sort'    => true,
						'default' =>  G5STAFF()->settings()->get_staff_info_default(),
						'fields' => array(
							array(
								'title'   => esc_html__( 'Title', 'g5-staff' ),
								'id'      => "title",
								'type'    => 'text',
								'col'     => '4',
								'default' => '',
								'desc'    => esc_html__( 'Enter staff info title', 'g5-staff' ),
							),
							array(
								'title'   => esc_html__( 'Value', 'g5-staff' ),
								'id'      => "value",
								'type'    => 'text',
								'col'     => '8',
								'width'   => '100%',
								'default' => '',
								'desc'    => esc_html__( 'Enter staff info value', 'g5-staff' ),
							),
						)
					),
					"{$prefix}social_profiles" => array(
						'id'       => "{$prefix}social_profiles",
						'title'    => esc_html__( 'Social Profile', 'g5-staff' ),
						'type'     => 'panel',
						'clone' => false,
						'fields'   => array(
							"facebook" => array(
								'id'    => "facebook",
								'title' => esc_html__( 'Facebook', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter facebook profile or page link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"twitter" => array(
								'id'    => "twitter",
								'title' => esc_html__( 'Twitter', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter twitter profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"linkedin" => array(
								'id'    => "linkedin",
								'title' => esc_html__( 'LinkedIn', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter LinkedIn profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"instagram" => array(
								'id'    => "instagram",
								'title' => esc_html__( 'Instagram', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter Instagram profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"dribbble" => array(
								'id'    => "dribbble",
								'title' => esc_html__( 'Dribbble', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter Dribbble profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"youtube" => array(
								'id'    => "youtube",
								'title' => esc_html__( 'Youtube', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter Youtube profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
							"vimeo" => array(
								'id'    => "vimeo",
								'title' => esc_html__( 'Vimeo', 'g5-staff' ),
								'desc'  => esc_html__( 'Enter Vimeo profile link.', 'g5-staff' ),
								'type'  => 'text',
							),
						)
					)

				),
			);

			return $configs;
		}

		public function change_single_setting() {
			if (g5staff_is_single()) {
				$prefix = G5STAFF()->meta_prefix;
				$single_layout = get_post_meta(get_the_ID(),"{$prefix}single_layout",true);
				if (!empty($single_layout)) {
					G5STAFF()->options()->set_option('single_layout',$single_layout);
				}
			}
		}
	}
}