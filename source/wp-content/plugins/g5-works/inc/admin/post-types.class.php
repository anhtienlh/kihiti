<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Works_Admin_Post_Types')) {
    class G5Works_Admin_Post_Types {

        private $permalink = array();
        public $post_type = 'works';
        public $taxonomy_cat = 'works_category';
        public $taxonomy_visibility = 'works_visibility';
        private $ajax_nonce = 'g5works_featured_nonce';

        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            $this->permalink = G5WORKS()->admin()->permalink()->get_settings();

            // register post-type
            add_filter('gsf_register_post_type', array($this,'register_post_types'));


            add_filter('gsf_register_taxonomy',array($this,'register_taxonomy'));

            add_filter("manage_{$this->post_type}_posts_columns",array($this,'custom_columns_heading'));
            add_filter("manage_{$this->post_type}_posts_custom_column",array($this,'custom_columns_content'),10,2);

            add_action('wp_ajax_g5works_featured', array($this, 'update_works_featured'));

            // add filter category
            add_action('restrict_manage_posts', array($this,'add_category_filter'));
            add_filter('parse_query', array($this,'add_category_filter_query'));

            add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 32 );


        }

        public function register_post_types($post_types) {
            $post_types [$this->post_type] = array(
                'label'         => esc_html__('Works', 'g5-works'),
                'menu_icon'     => 'dashicons-portfolio',
                'menu_position' => 25,
                'supports'           => array('title', 'editor',  'thumbnail', 'excerpt','page-attributes','comments'),
                'rewrite'       => array('slug' => $this->permalink['post_type_rewrite_slug'],'with_front' => false),
            );
            return $post_types;
        }

        public function register_taxonomy($taxonomies) {
            $taxonomies[$this->taxonomy_cat] = array(
                'post_type'     => $this->post_type,
                'label'         => esc_html__('Categories', 'g5-works'),
                'name'          => esc_html__('Works Categories', 'g5-works'),
                'singular_name' => esc_html__('Category', 'g5-works'),
                'rewrite'       => array('slug' => $this->permalink['cat_rewrite_slug'] , 'with_front' =>  true),
                'show_admin_column' => true,
            );


            $taxonomies[$this->taxonomy_visibility] = array(
                'post_type'     => $this->post_type,
                'hierarchical'      => false,
                'show_ui'           => false,
                'show_in_nav_menus' => false,
                'query_var'         => is_admin(),
                'rewrite'           => false,
                'public'            => false,
            );

            return $taxonomies;
        }

        public function custom_columns_heading($columns) {
            $my_columns['cb'] = $columns['cb'];
            $my_columns['thumbnail'] = "<span class='dashicons dashicons-format-image'></span>"; esc_html__('Thumbnail','g5-works');
            $my_columns['title'] = $columns['title'];
            $my_columns['taxonomy-'. $this->taxonomy_cat] = esc_html__('Categories','g5-works');
            $my_columns['g5works-featured'] = "<span class='dashicons dashicons-star-filled parent-tips' title='" . esc_html__('Featured','g5-works') . "' style='cursor: help'></span>";
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

            if($columns === 'g5works-featured') {
                $nonce = wp_create_nonce($this->ajax_nonce);
                $terms = wp_get_object_terms($post_id, $this->taxonomy_visibility, array( 'fields' => 'slugs' ));
                $options = array(
                    'action' => 'g5works_featured',
                    'id' => $post_id,
                    'nonce' => $nonce,
                    'status' => 0
                );
                $icon = 'dashicons dashicons-star-empty';
                if(is_array($terms) && in_array('featured', $terms) ) {
                    $options['status'] = 1;
                    $icon = 'dashicons dashicons-star-filled';
                }
                ?>
                <a href="javascript:;" class="g5works__featured" data-options="<?php echo esc_attr(json_encode($options))?>">
                    <span class="<?php echo esc_attr($icon)?>"></span>
                </a>
                <?php
            }
        }

        public function update_works_featured() {
            if (!wp_verify_nonce($_REQUEST['nonce'], $this->ajax_nonce)) {
                wp_send_json_error();
            }
            $id = intval($_REQUEST['id']);
            $status = intval($_REQUEST['status']);
            if(!$status) {
                if(is_array(wp_set_post_terms($id,'featured',$this->taxonomy_visibility)) ){
                    wp_send_json_success();
                } else {
                    wp_send_json_error();
                }
            } else {
                $result = wp_remove_object_terms($id,'featured',$this->taxonomy_visibility);
                if($result === true){
                    wp_send_json_success();
                } else {
                    wp_send_json_error();
                }
            }
        }

        public function add_category_filter() {
            global $typenow;
            if ($typenow === $this->post_type) {
                $taxonomy = $this->taxonomy_cat;
                $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                $info_taxonomy = get_taxonomy($taxonomy);
                wp_dropdown_categories(array(
                    'show_option_all' => sprintf(esc_html__('Show All %s', 'g5-works'), $info_taxonomy->label),
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

        public function add_category_filter_query($query) {
            global $pagenow;
            $q_vars    = &$query->query_vars;
            $taxonomy = $this->taxonomy_cat;
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
                'id'     => 'g5works',
                'title'  => esc_html__('Visit Works','g5-works'),
                'href'   => get_post_type_archive_link($this->post_type)
            ) );
        }
    }
}