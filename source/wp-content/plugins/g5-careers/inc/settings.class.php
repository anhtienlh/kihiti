<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Careers_Settings' ) ) {
	class G5Careers_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		public function get_careers_orderby() {
			return apply_filters('g5careers_options_career_orderby',array(
				'date' => esc_html__( 'Date', 'g5-careers' ),
				'title' => esc_html__( 'Title', 'g5-careers' ),
				'rand' => esc_html__( 'Random', 'g5-careers' ),
				'menu_order' => esc_html__( 'Menu Order', 'g5-careers' )
			));
		}

		public function get_careers_order() {
			return apply_filters('g5careers_options_careers_order',array(
				'asc' => esc_html__( 'ASC', 'g5-careers' ),
				'desc' => esc_html__( 'DESC', 'g5-careers' )
			));
		}

		public function get_careers_columns_table() {
			return apply_filters( 'g5core_options_careers_columns_table', array(
				'title' => esc_html__('Job Title','g5-careers'),
				'location' => esc_html__('Location','g5-careers'),
				'department' => esc_html__('Department','g5-careers'),
				'salary' => esc_html__('Salary','g5-careers'),
				'expired_date' => esc_html__('Expired Date','g5-careers')
			) );
		}
	}
}