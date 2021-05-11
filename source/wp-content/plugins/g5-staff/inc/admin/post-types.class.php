<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Staff_Admin_Post_Types')) {
    class G5Staff_Admin_Post_Types {

        private $permalink = array();
        public $post_type = 'staff';
        public $taxonomy_group = 'staff_group';

        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            $this->permalink = G5STAFF()->admin()->permalink()->get_settings();

            // register post-type
            add_filter('gsf_register_post_type', array($this,'register_post_types'));


            add_filter('gsf_register_taxonomy',array($this,'register_taxonomy'));

            add_filter("manage_{$this->post_type}_posts_columns",array($this,'custom_columns_heading'));
            add_filter("manage_{$this->post_type}_posts_custom_column",array($this,'custom_columns_content'),10,2);


            // add filter category
            add_action('restrict_manage_posts', array($this,'add_group_filter'));
            add_filter('parse_query', array($this,'add_group_filter_query'));

            add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 32 );


        }

        public function register_post_types($post_types) {
            $post_types [$this->post_type] = array(
                'label'         => esc_html__('Staff', 'g5-staff'),
                'menu_icon'     => 'dashicons-businessperson',
                'menu_position' => 25,
                'supports'           => array('title', 'editor',  'thumbnail', 'excerpt','page-attributes'),
                'rewrite'       => array('slug' => $this->permalink['post_type_rewrite_slug'],'with_front' => false),
            );
            return $post_types;
        }

        public function register_taxonomy($taxonomies) {
            $taxonomies[$this->taxonomy_group] = array(
                'post_type'     => $this->post_type,
                'label'         => esc_html__('Groups', 'g5-staff'),
                'name'          => esc_html__('Staff Groups', 'g5-staff'),
                'singular_name' => esc_html__('Group', 'g5-staff'),
                'rewrite'       => array('slug' => $this->permalink['group_rewrite_slug'] , 'with_front' =>  true),
                'show_admin_column' => true,
            );


            return $taxonomies;
        }

        public function custom_columns_heading($columns) {
            $my_columns['cb'] = $columns['cb'];
            $my_columns['thumbnail'] = "<span class='dashicons dashicons-format-image'></span>"; esc_html__('Thumbnail','g5-staff');
            $my_columns['title'] = $columns['title'];
            $my_columns['taxonomy-'. $this->taxonomy_group] = esc_html__('Groups','g5-staff');
            $my_columns['date'] = $columns['date'];
            return $my_columns;
        }

        public function custom_columns_content($columns,$post_id) {
            if ($columns === 'thumbnail') {
                if (has_post_thumbnail($post_id)) {
                    echo '<a href="'. get_edit_post_link($post_id) .'">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                }
            }
        }


        public function add_group_filter() {
            global $typenow;
            if ($typenow === $this->post_type) {
                $taxonomy = $this->taxonomy_group;
                $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                $info_taxonomy = get_taxonomy($taxonomy);
                wp_dropdown_categories(array(
                    'show_option_all' => sprintf(esc_html__('Show All %s', 'g5-staff'), $info_taxonomy->label),
                    'taxonomy'        => $taxonomy,
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => true,
                    'hide_if_empty' => true
                ));
            }
        }

        public function add_group_filter_query($query) {
            global $pagenow;
            $q_vars    = &$query->query_vars;
            $taxonomy = $this->taxonomy_group;
            if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $this->post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
                $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
                $q_vars[$taxonomy] = $term->slug;
            }
        }

        public function admin_bar_menus($wp_admin_bar) {
            if ( ! is_admin() || ! is_user_logged_in() ) {
                return;
            }

            if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
                return;
            }

            $wp_admin_bar->add_node( array(
                'parent' => 'site-name',
                'id'     => 'g5staff',
                'title'  => esc_html__('Visit Staff','g5-staff'),
                'href'   => get_post_type_archive_link($this->post_type)
            ) );
        }
    }
}