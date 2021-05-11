<?php
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Element_Customize')) {
    class G5Element_Customize
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init()
        {
            add_action('vc_before_init', array($this, 'set_shortcodes_templates_dir'));
            add_action('init',array($this,'remove_filter_excerpt'));
	        add_action( 'body_class', array( $this, 'body_class' ) );
        }

        public function set_shortcodes_templates_dir()
        {
            // Link your VC elements's folder
            if (function_exists('vc_set_shortcodes_templates_dir')) {
                vc_set_shortcodes_templates_dir(G5ELEMENT()->plugin_dir('vc-templates'));
            }
        }

        public function remove_filter_excerpt() {
	        global $vc_manager;
	        if (is_a($vc_manager,'VC_Manager')) {
		        remove_filter( 'the_excerpt', array(
			        $vc_manager->vc(),
			        'excerptFilter',
		        ) );
	        }
        }

        public function body_class($classes) {
	        if (is_singular()) {
		        $used_vc = g5core_page_used_vc();
		        if ($used_vc) {
			        $classes[] = 'used-vc';
		        }
	        }
	        return $classes;
        }
    }
}