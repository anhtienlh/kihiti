<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Works_ShortCodes')) {
    class G5Works_ShortCodes
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
            add_filter('g5element_shortcodes_list',array($this,'add_shortcodes_list'));
            add_filter('g5element_vc_lean_map_config',array($this,'vc_lean_map_config'),10,2);
            add_filter('g5element_autoload_class_path',array($this,'change_autoload_class_path'),10,3);
            add_action( 'vc_after_mapping', array($this,'auto_complete') );
            add_filter('g5element_shortcode_template',array($this,'change_shortcode_template'),10,2);

            add_filter('g5element_shortcode_listing_query_args',array($this,'set_query_args'),10,2);
        }

        public function get_shortcodes() {
            return apply_filters('g5works_shortcodes',array(
                'works',
                'works_slider'
            ));
        }

        public function add_shortcodes_list($shortcodes) {
            return wp_parse_args($this->get_shortcodes(),$shortcodes);
        }

        public function vc_lean_map_config($vc_map_config,$key) {
            if (in_array($key,$this->get_shortcodes())) {
                $file_name = str_replace('_', '-', $key);
                $vc_map_config = G5WORKS()->locate_template("shortcodes/{$file_name}/config.php");
            }
            return $vc_map_config;
        }
        public function change_autoload_class_path($path,$shortcode,$file_name) {
            if (in_array($shortcode,$this->get_shortcodes())) {
                $path = G5WORKS()->locate_template("shortcodes/{$file_name}/{$file_name}.php");
            }
            return $path;
        }

        public function change_shortcode_template($template, $template_name) {
            if (in_array($template_name,$this->get_shortcodes())) {
                $template_name = str_replace('_', '-', $template_name);
                $template = G5WORKS()->locate_template("shortcodes/{$template_name}/template.php");
            }
            return $template;
        }

        public function get_auto_complete_fields() {
            return apply_filters('g5works_auto_complete_fields',array(
                'g5element_works_ids',
	            'g5element_works_slider_ids',

            ));
        }

        public function auto_complete() {
            $auto_complete_fields = $this->get_auto_complete_fields();
            foreach ($auto_complete_fields as $auto_complete_field) {
                //Filters For autocomplete param:
                add_filter( "vc_autocomplete_{$auto_complete_field}_callback", array(&$this,'post_search',), 10, 1 ); // Get suggestion(find). Must return an array
                add_filter( "vc_autocomplete_{$auto_complete_field}_render", array(&$this,'post_render',), 10, 1 ); // Render exact product. Must return an array (label,value)
            }
        }

        public function post_search( $search_string ) {
            $query = $search_string;
            $data = array();
            $args = array(
                's' => $query,
                'post_type' => 'works',
            );
            $args['vc_search_by_title_only'] = true;
            $args['numberposts'] = - 1;
            if ( 0 === strlen( $args['s'] ) ) {
                unset( $args['s'] );
            }
            add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
            $posts = get_posts( $args );
            if ( is_array( $posts ) && ! empty( $posts ) ) {
                foreach ( $posts as $post ) {
                    $data[] = array(
                        'value' => $post->ID,
                        'label' => $post->post_title,
                        'group' => $post->post_type,
                    );
                }
            }

            return $data;
        }

        public function post_render( $value ) {
            $post = get_post( $value['value'] );
            return is_null( $post ) ? false : array(
                'label' => $post->post_title,
                'value' => $post->ID
            );
        }

        public function set_query_args($query_args,$atts) {
            if ($query_args['post_type'] === 'works') {
                $query_args['meta_query'] = array();
                $query_args['tax_query'] = array(
                    'relation' => 'AND',
                );

                if (!isset($atts['show'])) {
                    $atts['show'] = '';
                }

                switch ( $atts['show'] ) {
                    case 'featured':
                        $query_args['tax_query'][] = array(
                            'taxonomy' => 'works_visibility',
                            'field'    => 'slug',
                            'terms'    => 'featured',
                        );
                        break;
                    case 'new-in':
                        $query_args['orderby'] = 'date';
                        $query_args['order'] = 'DESC';
                        break;
                    case 'works':
                        $query_args['post__in'] = array_filter(explode(',',$atts['ids']),'absint');
                        $query_args['posts_per_page'] = -1;
                        $query_args['orderby'] = 'post__in';
                        break;
                }

                if ($atts['show'] !== 'works' && !empty($atts['cat'])) {
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'works_category',
                        'terms' => array_filter(explode(',',$atts['cat']),'absint'),
                        'field' => 'id',
                        'operator' => 'IN'
                    );
                }
            }

            return $query_args;
        }

    }
}