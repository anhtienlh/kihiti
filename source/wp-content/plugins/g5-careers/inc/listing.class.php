<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Core_Listing_Abstract', false)) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/listing.class.php'));
}
if (!class_exists('G5Careers_Listing')) {
    class G5Careers_Listing extends G5Core_Listing_Abstract
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        protected $key_layout_settings = 'g5careers_layout_settings';

        public function init()
        {
            add_action('g5core_careers_pagination_ajax_response', array($this, 'pagination_ajax_response'), 10, 2);
        }

        public function pagination_ajax_response($settings, $query_args)
        {
            $this->render_content($query_args, $settings);
        }

        public function get_layout_settings_default()
        {
            return array(
                'post_animation' => 'none',
                'post_paging' => G5CAREERS()->options()->get_option('post_paging'),
                'itemSelector' => '.g5careers__table tbody > tr',
                'post_type' => 'careers'
            );
        }

        public function render_listing()
        {
            G5CAREERS()->get_template('listing.php');
        }
    }
}