<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Careers_Admin_Post_Types')) {
    class G5Careers_Admin_Post_Types {

        private $permalink = array();
        public $post_type = 'careers';

        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            $this->permalink = G5CAREERS()->admin()->permalink()->get_settings();

            // register post-type
            add_filter('gsf_register_post_type', array($this,'register_post_types'));

            add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 32 );


        }

        public function register_post_types($post_types) {
            $post_types [$this->post_type] = array(
                'label'         => esc_html__('Careers', 'g5-careers'),
                'menu_icon'     => 'dashicons-portfolio',
                'menu_position' => 25,
                'supports'           => array('title', 'editor', 'page-attributes'),
                'rewrite'       => array('slug' => $this->permalink['post_type_rewrite_slug'],'with_front' => false),
            );
            return $post_types;
        }


        public function admin_bar_menus($wp_admin_bar) {
            if ( ! is_admin() || ! is_user_logged_in() ) {
                return;
            }

            if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
                return;
            }

            $wp_admin_bar->add_node( array(
                'parent' => 'site-name',
                'id'     => 'g5careers',
                'title'  => esc_html__('Visit Careers','g5-careers'),
                'href'   => get_post_type_archive_link($this->post_type)
            ) );
        }
    }
}