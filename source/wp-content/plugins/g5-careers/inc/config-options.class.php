<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!class_exists('G5Careers_Config_Options')) {
	class G5Careers_Config_Options {
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

		public function init() {
			add_filter('gsf_option_config', array($this, 'define_options'), 300);
			add_filter('gsf_meta_box_config', array($this, 'define_meta_box'));
			add_filter('g5core_admin_bar_theme_options', array($this, 'admin_bar_theme_options'), 300);
			add_action('template_redirect', array($this, 'change_single_setting'));
		}

		public function admin_bar_theme_options($admin_bar_theme_options) {
			$admin_bar_theme_options['g5careers_options'] = array(
				'title' => esc_html__('Careers','g5-careers'),
				'permission' => 'manage_options',
			);
			return $admin_bar_theme_options;
		}

		public function define_meta_box($configs) {

			$prefix = G5CAREERS()->meta_prefix;
			$configs['g5careers_meta'] = array(
				'name' => esc_html__('Careers Settings', 'g5-careers'),
				'post_type' => array('careers'),
				'layout' => 'inline',
				'fields' => array(
					"{$prefix}single_title_enable" => G5CORE()->fields()->get_config_toggle(array(
						'id' => "{$prefix}single_title_enable",
						'title' => esc_html__('Show Title', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to hide title on single', 'g5-careers'),
						'default' => ''
					),true),

					"{$prefix}single_meta_enable" => G5CORE()->fields()->get_config_toggle(array(
						'id' => "{$prefix}single_meta_enable",
						'title' => esc_html__('Show Meta', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to hide meta on single', 'g5-careers'),
						'default' => ''
					),true),


					"{$prefix}department" => array(
						'id' => "{$prefix}department",
						'title' => esc_html__('Department','g5-careers'),
						'type' => 'text',
						'default' => ''
					),

					"{$prefix}location" => array(
						'id' => "{$prefix}location",
						'title' => esc_html__('Location','g5-careers'),
						'type' => 'text',
						'default' => ''
					),
					"{$prefix}salary" => array(
						'id' => "{$prefix}salary",
						'title' => esc_html__('Salary','g5-careers'),
						'type' => 'text',
						'default' => ''
					),

					"{$prefix}expired_date" => array(
						'id' => "{$prefix}expired_date",
						'title' => esc_html__('Expired Date','g5-careers'),
						'type' => 'datetimepicker',
						'js_options' => array(
							'format' => 'd/m/Y',
							'timepicker' => false
						)
					),
					"{$prefix}additional_details" => array(
						'id' => "{$prefix}additional_details",
						'title' => esc_html__( 'Additional Details', 'g5-careers' ),
						'type'    => 'repeater',
						'sort'    => true,
						'fields' => array(
							array(
								'title'   => esc_html__( 'Title', 'g5-careers' ),
								'id'      => "title",
								'type'    => 'text',
								'col'     => '4',
								'default' => '',
								'desc'    => esc_html__( 'Enter additional title', 'g5-careers' ),
							),
							array(
								'title'   => esc_html__( 'Value', 'g5-careers' ),
								'id'      => "value",
								'type'    => 'text',
								'col'     => '8',
								'width'   => '100%',
								'default' => '',
								'desc'    => esc_html__( 'Enter additional value', 'g5-careers' ),
							),
						)
					),
					"{$prefix}contact_us_type" => array(
						'id' => "{$prefix}contact_us_type",
						'title' => esc_html__('Contact Type','g5-careers'),
						'subtitle' => esc_html__('Specify your contact type','g5-careers'),
						'type' => 'button_set',
						'options' => array(
							'' => esc_html__('Hide','g5-careers'),
							'form' => esc_html__('Form','g5-careers'),
							'link' => esc_html__('Link','g5-careers'),
						),
						'default' => 'form'
					),
					"{$prefix}contact_us_form" => array(
						'id' => "{$prefix}contact_us_form",
						'title' => esc_html__('Contact Us Form','g5-careers'),
						'type' => 'selectize',
						'allow_clear' => true,
						'data' => 'wpcf7_contact_form',
						'placeholder' => esc_html__( 'Select Form', 'g5-careers' ),
						'multiple'    => false,
						'desc'        => esc_html__( 'Select form to contact us', 'g5-careers' ),
						'create_link' => admin_url( 'admin.php?page=wpcf7-new' ),
						'edit_link'   => admin_url( 'admin.php?page=wpcf7&action=edit' ),
						'default' => '',
						'required' => array("{$prefix}contact_us_type",'=','form')
					),
					"{$prefix}contact_us_link" => array(
						'id' => "{$prefix}contact_us_link",
						'title' => esc_html__('Contact Us Link','g5-careers'),
						'type' => 'text',
						'default' => '',
						'required' => array("{$prefix}contact_us_type",'=','link')
					)
				)
			);

			return $configs;
		}

		public function define_options($configs) {
			$configs['g5careers_options'] = array(
				'layout' => 'inline',
				'page_title' => esc_html__('Careers Options', 'g5-careers'),
				'menu_title' => esc_html__('Careers', 'g5-careers'),
				'option_name' => 'g5careers_options',
				'parent_slug' => 'g5core_options',
				'permission' => 'manage_options',
				'section' => array(
					$this->config_section_archive(),
					$this->config_section_single()
				)
			);
			return $configs;
		}

		public function config_section_archive() {
			return array(
				'id' => 'section_archive',
				'title' => esc_html__('Archive Listing', 'g5-careers'),
				'icon' => 'dashicons dashicons-category',
				'fields' => array(
					'archive_table_columns' => array(
						'id'      => 'archive_table_columns',
						'title'   => esc_html__( 'Table Columns', 'g5-careers' ),
						'type'    => 'sortable',
						'options' => G5CAREERS()->settings()->get_careers_columns_table(),
						'default' => G5CAREERS()->options()->get_default( 'archive_table_columns'),
					),
					'archive_table_responsive' => G5CORE()->fields()->get_config_toggle(array(
						'id' => 'archive_table_responsive',
						'title' => esc_html__('Table Responsive', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to disable responsive table on mobile', 'g5-careers'),
						'default' => G5CAREERS()->options()->get_default( 'archive_table_responsive','on' )
					)),
					'sorting_group' => array(
						'id'       => 'sorting_group',
						'title'    => esc_html__( 'Sorting', 'g5-careers' ),
						'type'     => 'group',
						'fields' => array(
							'archive_orderby' => array(
								'id' => 'archive_orderby',
								'title' => esc_html__('Order By', 'g5-careers'),
								'subtitle' =>  esc_html__('Select how to sort retrieved service.', 'g5-careers'),
								'type' => 'select',
								'options' => G5CAREERS()->settings()->get_careers_orderby(),
								'default' => G5CAREERS()->options()->get_default( 'archive_orderby', 'date' ),
							),
							'archive_order' => array(
								'id' => 'archive_order',
								'title' => esc_html__('Order', 'g5-careers'),
								'subtitle' => esc_html__('Select sorting order.', 'g5-careers'),
								'type' => 'select',
								'options' => G5CAREERS()->settings()->get_careers_order(),
								'default' => G5CAREERS()->options()->get_default( 'archive_order', 'desc' ),
							),
						)
					),
					'posts_per_page' => array(
						'id' => 'posts_per_page',
						'title' => esc_html__('Posts Per Page', 'g5-careers'),
						'subtitle' => esc_html__('Enter number of posts per page you want to display.', 'g5-careers'),
						'type' => 'text',
						'default' => G5CAREERS()->options()->get_default('posts_per_page', ''),
						'input_type' => 'number',
					),
					'post_paging' => array(
						'id' => 'post_paging',
						'title' => esc_html__('Post Paging', 'g5-careers'),
						'subtitle' => esc_html__('Specify your post paging mode', 'g5-careers'),
						'type' => 'select',
						'options' => G5CORE()->settings()->get_post_paging_mode(),
						'default' => G5CAREERS()->options()->get_default('post_paging', 'pagination'),
					),
				)
			);
		}

		public function config_section_single() {
			return array(
				'id' => 'section_single',
				'title' => esc_html__('Single Careers', 'g5-careers'),
				'icon' => 'dashicons dashicons-screenoptions',
				'fields' => array(
					'single_title_enable' => G5CORE()->fields()->get_config_toggle(array(
						'id' => 'single_title_enable',
						'title' => esc_html__('Show Title', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to hide title on single', 'g5-careers'),
						'default' => G5CAREERS()->options()->get_default( 'single_title_enable','on' )
					)),
					'single_meta_enable' => G5CORE()->fields()->get_config_toggle(array(
						'id' => 'single_meta_enable',
						'title' => esc_html__('Show Meta', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to hide meta on single', 'g5-careers'),
						'default' => G5CAREERS()->options()->get_default( 'single_meta_enable','on' )
					)),
					'single_share_enable' => G5CORE()->fields()->get_config_toggle(array(
						'id' => 'single_share_enable',
						'title' => esc_html__('Share', 'g5-careers'),
						'subtitle' => esc_html__('Turn Off this option if you want to hide share on single careers', 'g5-careers'),
						'default' => G5CAREERS()->options()->get_default( 'single_share_enable','on' ),
					)),
				)
			);
		}

		public function change_single_setting() {
			if (g5careers_is_single()) {
				$prefix = G5CAREERS()->meta_prefix;
				$settings = array(
					'single_title_enable',
					'single_meta_enable',
				);

				foreach ($settings as $setting) {
					$v = get_post_meta(get_the_ID(),"{$prefix}{$setting}",true);
					if ($v !== '') {
						G5CAREERS()->options()->set_option($setting, $v);
					}
				}

			}
		}
	}
}