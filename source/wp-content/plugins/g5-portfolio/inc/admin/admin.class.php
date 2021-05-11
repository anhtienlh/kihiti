<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Portfolio_Admin')) {
    class G5Portfolio_Admin {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function init() {
            $this->permalink()->init();
            $this->post_types()->init();
        }

        /**
         * @return G5Portfolio_Admin_Post_Types
         */
        public function post_types() {
            return G5Portfolio_Admin_Post_Types::getInstance();
        }

        /**
         * @return G5Portfolio_Admin_Permalink
         */
        public function permalink() {
            return G5Portfolio_Admin_Permalink::getInstance();
        }

    }
}