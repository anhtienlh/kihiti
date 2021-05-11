<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5ThemeAddons_Blog')) {
    class G5ThemeAddons_Blog {
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
            add_filter('g5blog_widget_posts_post_meta_args', array($this,'change_widget_posts_post_meta_args'));
            add_filter('g5core_breadcrumbs_args', array($this,'change_breadcrumbs_args'));

            add_filter('g5blog_single_related_settings', array($this,'change_single_blog_related_settings') );

            add_action('template_redirect', array($this,'demo_layout') ,15);
            add_action( 'pre_get_posts', array( $this, 'demo_post_per_pages' ), 15 );

            add_action('init',array($this,'change_loop_post_meta_position'));
	        add_action('init',array($this,'change_widget_post_meta_position'));

            add_action('g5blog_loop_post_content',array($this,'template_loop_read_more'),25);

	        add_filter( 'g5core_default_options_g5blog_options', array($this,'change_default_options'), 11 );

	        add_filter('g5blog_image_sizes',array($this,'change_image_sizes'));


        }

        public function change_widget_posts_post_meta_args() {
            return array(
                'author' => true,
	            'date' => true
            );
        }

        public function change_breadcrumbs_args($args) {
            return wp_parse_args(array(
                'separator' => '.'
            ),$args);
        }


        public function change_single_blog_related_settings($settings) {
            return wp_parse_args(array(
                'excerpt_enable' => false,
	            'image_size' => '370x230',
	            'placeholder' => 'on'
            ),$settings);
        }


        public function demo_layout() {
            if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5BLOG' ) ) {
                return;
            }
            $post_layout = isset( $_REQUEST['post_layout'] ) ? $_REQUEST['post_layout'] : '';

            if ( ! empty( $post_layout ) ) {
                $ajax_query                = G5CORE()->cache()->get( 'g5core_ajax_query', array() );
                $ajax_query['post_layout'] = $post_layout;
                G5CORE()->cache()->set( 'g5core_ajax_query', $ajax_query );
            }


            $has_sidebar = g5core_has_sidebar();

            if ( ! empty( $post_layout ) ) {
                switch ( $post_layout ) {
                    case 'large-image':
                        G5BLOG()->options()->set_option('post_layout','large-image');
	                    G5BLOG()->options()->set_option('post_image_size','full');
	                    G5BLOG()->options()->set_option('category_filter_enable','on');
	                    if ($has_sidebar) {
		                    G5BLOG()->options()->set_option('category_filter_enable','');
	                    }
                        break;
                    case 'grid':
                        G5BLOG()->options()->set_option('post_layout','grid');
                        G5BLOG()->options()->set_option('post_columns_gutter','30');
                        G5BLOG()->options()->set_option('post_columns_xl','3');
                        G5BLOG()->options()->set_option('post_columns_lg','3');
                        G5BLOG()->options()->set_option('post_columns_md','3');
                        G5BLOG()->options()->set_option('post_columns_sm','2');
                        G5BLOG()->options()->set_option('post_columns','1');
                        G5BLOG()->options()->set_option('post_image_size','370x230');
	                    G5BLOG()->options()->set_option('category_filter_enable','on');
	                    G5BLOG()->options()->set_option('excerpt_enable','');
                        if ($has_sidebar) {
                            G5BLOG()->options()->set_option('post_columns_xl','2');
                            G5BLOG()->options()->set_option('post_columns_lg','2');
                            G5BLOG()->options()->set_option('post_columns_md','2');
	                        G5BLOG()->options()->set_option('category_filter_enable','');
                        }

                        break;
                    case 'medium-image':
                    	G5BLOG()->options()->set_option('post_layout','medium-image');
                        G5BLOG()->options()->set_option('post_image_size','700x480');
	                    G5BLOG()->options()->set_option('category_filter_enable','on');
	                    //G5BLOG()->options()->set_option('excerpt_enable','');
	                    if ($has_sidebar) {
		                    G5BLOG()->options()->set_option('category_filter_enable','');
		                    G5BLOG()->options()->set_option('excerpt_enable','');
	                    }
                        break;
                }


            }
        }

        public function demo_post_per_pages( $query ) {
            if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5BLOG' ) ) {
                return;
            }
            if ( ! is_admin() && $query->is_main_query() ) {
                $post_layout = isset( $_REQUEST['post_layout'] ) ? $_REQUEST['post_layout'] : '';
                if ( empty( $post_layout ) ) {
                    return;
                }
                $site_layout = isset( $_REQUEST['site_layout'] ) ? $_REQUEST['site_layout'] : '';
                if ( ! empty( $site_layout ) ) {
                    G5CORE()->options()->layout()->set_option( 'site_layout', $site_layout );
                }
                $has_sidebar = g5core_has_sidebar();
                switch ( $post_layout ) {
                    case 'grid':
                        $query->set( 'posts_per_page', 9 );
                        if ( $has_sidebar ) {
                            $query->set( 'posts_per_page', 8 );
                        }
                        break;
                    case 'medium-image':
                        $query->set( 'posts_per_page', 6 );
                        break;
                    case 'large-image':
                        $query->set( 'posts_per_page', 6 );
                        break;
                }
            }

        }

        public function change_loop_post_meta_position() {
        	remove_action('g5blog_loop_post_content','g5blog_template_loop_post_meta',10);
	        add_action('g5blog_loop_post_content','g5blog_template_loop_post_meta',4);
        }

        public function change_widget_post_meta_position() {
	        remove_action('g5blog_widget_post_content','g5blog_template_widget_post_meta',10);
	        add_action('g5blog_widget_post_content','g5blog_template_widget_post_meta',4);
        }

        public function template_loop_read_more() {
        	GTA()->get_template('blog/loop/read-more.php');
        }

	    public function change_default_options($defaults) {
		    return wp_parse_args(array(
			    'single_post_related_columns_xl' => 2,
			    'single_post_related_columns_lg' => 2,
			    'single_post_related_columns_md' => 2,
			    'single_post_related_columns_sm' => 2,
			    'single_post_related_columns' => 1,
			    'single_post_navigation_enable' => '',
			    'single_post_author_info_enable' => '',
			    'single_post_related_enable' => 'on',
		    ),$defaults) ;
	    }

	    public function change_image_sizes($image_sizes) {
        	return wp_parse_args(array(
		        'blog-widget' => '330x220',
	        ),$image_sizes);
	    }



    }
}