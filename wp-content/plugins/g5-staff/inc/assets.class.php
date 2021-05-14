<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Staff_Assets')) {
    class G5Staff_Assets
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
            wp_register_style(G5STAFF()->assets_handle('admin'),G5STAFF()->asset_url('assets/admin/scss/admin.min.css'),array(),G5STAFF()->plugin_ver());

            wp_register_style(G5STAFF()->assets_handle('frontend'),G5STAFF()->asset_url('assets/scss/frontend.min.css'),array(),G5STAFF()->plugin_ver());
        }

        public function enqueue_assets() {
            wp_enqueue_style(G5STAFF()->assets_handle('frontend'));
        }

        public function admin_enqueue_assets($hook) {

            if ( (($hook === 'post-new.php') || ($hook === 'post.php') || ($hook === 'edit.php'))
                && isset($_GET['post_type'])
                && ($_GET['post_type'] === 'staff')
            ) {

                wp_enqueue_style(G5STAFF()->assets_handle('admin'));
            }
        }
    }
}