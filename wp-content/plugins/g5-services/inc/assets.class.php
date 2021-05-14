<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Services_Assets')) {
    class G5Services_Assets
    {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            add_action('init',array($this,'register_assets'));
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_assets'));

            add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );


        }

        public function register_assets(){
            wp_register_script(G5SERVICES()->assets_handle('admin'),G5SERVICES()->asset_url('assets/admin/js/admin.min.js'),array('jquery'),G5SERVICES()->plugin_ver(),true);
            wp_register_style(G5SERVICES()->assets_handle('admin'),G5SERVICES()->asset_url('assets/admin/scss/admin.min.css'),array(),G5SERVICES()->plugin_ver());


            wp_register_script(G5SERVICES()->assets_handle('frontend'),G5SERVICES()->asset_url('assets/js/frontend.min.js'),array('jquery'),G5SERVICES()->plugin_ver(),true);
            wp_register_style(G5SERVICES()->assets_handle('frontend'),G5SERVICES()->asset_url('assets/scss/frontend.min.css'),array(),G5SERVICES()->plugin_ver());
        }

        public function enqueue_assets() {
            wp_enqueue_style(G5SERVICES()->assets_handle('frontend'));
            wp_enqueue_script(G5SERVICES()->assets_handle('frontend'));
            wp_localize_script(G5SERVICES()->assets_handle('frontend'), 'g5services_variable', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'home_url' => home_url( '/' ),
                'archive_url' => get_post_type_archive_link('services'),
                'localization'     => array(
                    'dropdown_categories_placeholder' => esc_html__('Select a category','g5-services'),
                    'dropdown_categories_noResults' => esc_html__('No matches found','g5-services'),
                ),
            ));
        }

        public function admin_enqueue_assets($hook) {

            if ( (($hook === 'post-new.php') || ($hook === 'post.php') || ($hook === 'edit.php'))
                && isset($_GET['post_type'])
                && ($_GET['post_type'] === 'services')
            ) {

                wp_enqueue_script(G5SERVICES()->assets_handle('admin'));
                wp_enqueue_style(G5SERVICES()->assets_handle('admin'));

                wp_localize_script(
                    G5SERVICES()->assets_handle('admin'),
                    'g5services_var',
                    array(
                        'ajax_url' => admin_url('admin-ajax.php')
                    )
                );
            }
        }
    }
}