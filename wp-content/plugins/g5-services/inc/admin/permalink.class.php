<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Services_Admin_Permalink')) {
    class G5Services_Admin_Permalink {
        private $option_key = 'g5services_permalink';

        private $post_type_base = 'g5services_base';
        private $cat_base = 'g5services_cat_base';
        private $permalink = array();


        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            add_action('admin_init',array($this,'register_settings'));
            add_action( 'load-options-permalink.php', array( $this,'save_settings') );
        }

        public function register_settings() {
            add_settings_field(
                'g5services_base',
                esc_html__('Services base','g5-services'),
                array( $this, 'post_type_input_callback' ),
                'permalink',
                'optional'
            );

            add_settings_field(
                'g5services_cat_base',
                esc_html__('Services category base','g5-services'),
                array( $this, 'cat_input_callback' ),
                'permalink',
                'optional'
            );



            $this->permalink = $this->get_settings();
        }

        public function post_type_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->post_type_base) ?>" placeholder="services" class="regular-text code" value="<?php echo esc_attr($this->permalink['post_type_base']) ?>">
            <?php
        }

        public function cat_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->cat_base) ?>" placeholder="services-cat" class="regular-text code" value="<?php echo esc_attr($this->permalink['cat_base']) ?>">
            <?php
        }

        public function get_settings() {
            $permalink = wp_parse_args((array)get_option($this->option_key, array()), array(
                'post_type_base' => '',
                'cat_base'  => '',
            ));

            // Ensure rewrite slugs are set.
            $permalink['post_type_rewrite_slug'] = untrailingslashit(empty($permalink['post_type_base']) ? _x('services', 'slug', 'g5-services') : $permalink['post_type_base']);
            $permalink['cat_rewrite_slug'] = untrailingslashit(empty($permalink['cat_base']) ? _x('services-cat', 'slug', 'g5-services') : $permalink['cat_base']);
            return $permalink;
        }

        public function save_settings() {
            if (!is_admin()) {
                return;
            }

            if (isset($_POST['permalink_structure'])) {
                $permalink = (array)get_option($this->option_key, array());
                $permalink['post_type_base'] = sanitize_title_with_dashes(trim($_POST[$this->post_type_base]));
                $permalink['cat_base'] = sanitize_title_with_dashes(trim($_POST[$this->cat_base]));
                update_option($this->option_key, $permalink);
            }
        }


    }
}