<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ( ! class_exists( 'G5Careers_Templates' ) ) {
    class G5Careers_Templates
    {
        private static $_instance;

        public static function getInstance() {
            if ( self::$_instance == null ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init() {
            add_filter( 'template_include', array( $this, 'template_loader' ) );

            add_filter('g5core_page_title',array($this,'page_title'));


            add_action('template_redirect',array($this,'template_redirect'));
        }

        public function template_loader($template) {
            if ( is_embed() ) {
                return $template;
            }

            if ( $default_file = $this->get_template_loader_default_file() ) {
                $search_files = $this->get_template_loader_files( $default_file );
                $template     = locate_template( $search_files );
                if ( ! $template) {
                    $template = G5CAREERS()->plugin_dir('templates/' . $default_file);
                }
            }
            return $template;
        }

        private function get_template_loader_default_file() {
            if ( g5careers_is_single()) {
                $default_file = 'single-careers.php';
            }  elseif ( g5careers_is_archive() ) {
                $default_file = 'archive-careers.php';
            } else {
                $default_file = '';
            }
            return $default_file;
        }

        private function get_template_loader_files( $default_file ) {
            $search_files   = apply_filters( 'g5careers_template_loader_files', array(), $default_file );

            $search_files[] = $default_file;
            $search_files[] = G5CAREERS()->template_path() . $default_file;
            return array_unique( $search_files );
        }



        public function page_title($page_title) {
            if (g5careers_is_archive()) {
                $post_type_object = get_post_type_object('careers');
                if (is_a($post_type_object,'WP_Post_Type')) {
                    $page_title = $post_type_object->labels->name;
                }
            }
            return $page_title;
        }


        public function template_redirect() {
	        global $wp_query;
	        // Redirect to the product page if we have a single product.
	        if ( is_search() && g5careers_is_archive() && apply_filters( 'g5careers_redirect_single_search_result', true ) && 1 === absint( $wp_query->found_posts ) ) {
		        $post = get_post( $wp_query->post );

		        if ( is_a($post,'WP_Post') ) {
			        wp_safe_redirect( get_permalink( $post->ID ), 302 );
			        exit;
		        }
	        }
        }
    }
}