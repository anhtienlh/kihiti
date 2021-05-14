<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ( ! class_exists( 'G5Portfolio_Templates' ) ) {
    class G5Portfolio_Templates
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

            add_action( G5CORE_CURRENT_THEME . '_before_main_content', array( $this, 'single_featured_template' ), 12 );

            add_filter('g5core_breadcrumb_portfolio_cat',array($this,'set_breadcrumb_cate'));

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
                    $template = G5PORTFOLIO()->plugin_dir('templates/' . $default_file);
                }
            }
            return $template;
        }

        private function get_template_loader_default_file() {
            if ( g5portfolio_is_single()) {
                $default_file = 'single-portfolio.php';
            } elseif ( g5portfolio_is_taxonomy()) {
                $term = get_queried_object();

                if ( g5portfolio_is_cat() || g5portfolio_is_tag()) {
                    $default_file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $default_file = 'archive-portfolio.php';
                }
            } elseif ( g5portfolio_is_archive() ) {
                $default_file = 'archive-portfolio.php';
            } else {
                $default_file = '';
            }
            return $default_file;
        }

        private function get_template_loader_files( $default_file ) {
            $search_files   = apply_filters( 'g5portfolio_template_loader_files', array(), $default_file );

            if ( g5portfolio_is_taxonomy() ) {
                $term   = get_queried_object();
                $search_files[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $search_files[] = G5PORTFOLIO()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $search_files[] = 'taxonomy-' . $term->taxonomy . '.php';
                $search_files[] = G5PORTFOLIO()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
            }

            $search_files[] = $default_file;
            $search_files[] = G5PORTFOLIO()->template_path() . $default_file;
            return array_unique( $search_files );
        }

        public function single_featured_template() {
            if (!g5portfolio_is_single()) return;
            $single_layout = G5PORTFOLIO()->options()->get_option('single_layout');
            if (in_array($single_layout,array('layout-4'))) {
                g5portfolio_template_single_gallery();
            }
        }

        public function page_title($page_title) {
            if (g5portfolio_is_single() || g5portfolio_is_archive()) {
                $post_type_object = get_post_type_object('portfolio');
                if (is_a($post_type_object,'WP_Post_Type')) {
                    $page_title = $post_type_object->labels->name;
                }
            }
            return $page_title;
        }

        public function set_breadcrumb_cate() {
            return 'portfolio_category';
        }

	    public function template_redirect() {
		    global $wp_query;
		    // Redirect to the product page if we have a single product.
		    if ( is_search() && g5portfolio_is_archive() && apply_filters( 'g5portfolio_redirect_single_search_result', true ) && 1 === absint( $wp_query->found_posts ) ) {
			    $post = get_post( $wp_query->post );

			    if ( is_a($post,'WP_Post') ) {
				    wp_safe_redirect( get_permalink( $post->ID ), 302 );
				    exit;
			    }
		    }
	    }
    }
}