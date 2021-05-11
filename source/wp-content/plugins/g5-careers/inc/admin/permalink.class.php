<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Careers_Admin_Permalink')) {
    class G5Careers_Admin_Permalink {
        private $option_key = 'g5careers_permalink';

        private $post_type_base = 'g5careers_base';
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
                'g5careers_base',
                esc_html__('Works base','g5-careers'),
                array( $this, 'post_type_input_callback' ),
                'permalink',
                'optional'
            );

            $this->permalink = $this->get_settings();
        }

        public function post_type_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->post_type_base) ?>" placeholder="careers" class="regular-text code" value="<?php echo esc_attr($this->permalink['post_type_base']) ?>">
            <?php
        }

        public function get_settings() {
            $permalink = wp_parse_args((array)get_option($this->option_key, array()), array(
                'post_type_base' => '',
            ));

            // Ensure rewrite slugs are set.
            $permalink['post_type_rewrite_slug'] = untrailingslashit(empty($permalink['post_type_base']) ? _x('careers', 'slug', 'g5-careers') : $permalink['post_type_base']);
            return $permalink;
        }

        public function save_settings() {
            if (!is_admin()) {
                return;
            }

            if (isset($_POST['permalink_structure'])) {
                $permalink = (array)get_option($this->option_key, array());
                $permalink['post_type_base'] = sanitize_title_with_dashes(trim($_POST[$this->post_type_base]));
                update_option($this->option_key, $permalink);
            }
        }


    }
}