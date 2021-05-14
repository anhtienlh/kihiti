<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Careers_Assets')) {
    class G5Careers_Assets
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



        }

        public function register_assets(){
            wp_register_style(G5CAREERS()->assets_handle('frontend'),G5CAREERS()->asset_url('assets/scss/frontend.min.css'),array(),G5CAREERS()->plugin_ver());
        }

        public function enqueue_assets() {
            wp_enqueue_style(G5CAREERS()->assets_handle('frontend'));
        }
    }
}