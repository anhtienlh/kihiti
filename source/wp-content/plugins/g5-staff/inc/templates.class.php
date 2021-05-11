<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ( ! class_exists( 'G5Staff_Templates' ) ) {
    class G5Staff_Templates
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

            add_filter('g5core_breadcrumb_staff_cat',array($this,'set_breadcrumb_cate'));
        }

        public function template_loader($template) {
            if ( is_embed() ) {
                return $template;
            }


            if ( $default_file = $this->get_template_loader_default_file() ) {
                $search_files = $this->get_template_loader_files( $default_file );
                $template     = locate_template( $search_files );
                if ( ! $template) {
                    $template = G5STAFF()->plugin_dir('templates/' . $default_file);
                }
            }
            return $template;
        }

	    private function get_template_loader_default_file() {
		    if ( g5staff_is_single()) {
			    $default_file = 'single-staff.php';
		    } elseif ( g5staff_is_taxonomy()) {
			    $term = get_queried_object();

			    if ( g5staff_is_group()) {
				    $default_file = 'taxonomy-' . $term->taxonomy . '.php';
			    } else {
				    $default_file = 'archive-staff.php';
			    }
		    } elseif ( g5staff_is_archive() ) {
			    $default_file = 'archive-staff.php';
		    } else {
			    $default_file = '';
		    }
		    return $default_file;
	    }

        private function get_template_loader_files( $default_file ) {
            $search_files   = apply_filters( 'g5staff_template_loader_files', array(), $default_file );

            if ( g5staff_is_taxonomy() ) {
                $term   = get_queried_object();
                $search_files[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $search_files[] = G5STAFF()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $search_files[] = 'taxonomy-' . $term->taxonomy . '.php';
                $search_files[] = G5STAFF()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
            }

            $search_files[] = $default_file;
            $search_files[] = G5STAFF()->template_path() . $default_file;
            return array_unique( $search_files );
        }



        public function page_title($page_title) {
            if (g5staff_is_archive()) {
                $post_type_object = get_post_type_object('staff');
                if (is_a($post_type_object,'WP_Post_Type')) {
                    $page_title = $post_type_object->labels->name;
                }
            }
            return $page_title;
        }

        public function set_breadcrumb_cate() {
            return 'staff_group';
        }

    }
}