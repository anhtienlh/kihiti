<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Portfolio_Admin_Permalink')) {
    class G5Portfolio_Admin_Permalink {
        private $option_key = 'g5portfolio_permalink';

        private $post_type_base = 'g5portfolio_base';
        private $cat_base = 'g5portfolio_cat_base';
        private $tag_base = 'g5portfolio_tag_base';
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
                'g5portfolio_base',
                esc_html__('Portfolio base','g5-portfolio'),
                array( $this, 'post_type_input_callback' ),
                'permalink',
                'optional'
            );

            add_settings_field(
                'g5portfolio_cat_base',
                esc_html__('Portfolio category base','g5-portfolio'),
                array( $this, 'cat_input_callback' ),
                'permalink',
                'optional'
            );

            add_settings_field(
                'g5portfolio_tag_base',
                esc_html__('Portfolio tag base','g5-portfolio'),
                array( $this, 'tag_input_callback' ),
                'permalink',
                'optional'
            );

            $this->permalink = $this->get_settings();
        }

        public function post_type_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->post_type_base) ?>" placeholder="portfolio" class="regular-text code" value="<?php echo esc_attr($this->permalink['post_type_base']) ?>">
            <?php
        }

        public function cat_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->cat_base) ?>" placeholder="portfolio-cat" class="regular-text code" value="<?php echo esc_attr($this->permalink['cat_base']) ?>">
            <?php
        }

        public function tag_input_callback() {
            ?>
            <input type="text" name="<?php echo esc_attr($this->tag_base) ?>" placeholder="portfolio-tag" class="regular-text code" value="<?php echo esc_attr($this->permalink['tag_base']) ?>">
            <?php
        }

        public function get_settings() {
            $permalink = wp_parse_args((array)get_option($this->option_key, array()), array(
                'post_type_base' => '',
                'cat_base'  => '',
                'tag_base'       => '',
            ));

            // Ensure rewrite slugs are set.
            $permalink['post_type_rewrite_slug'] = untrailingslashit(empty($permalink['post_type_base']) ? _x('portfolio', 'slug', 'g5-portfolio') : $permalink['post_type_base']);
            $permalink['cat_rewrite_slug'] = untrailingslashit(empty($permalink['cat_base']) ? _x('portfolio-cat', 'slug', 'g5-portfolio') : $permalink['cat_base']);
            $permalink['tag_rewrite_slug'] = untrailingslashit(empty($permalink['tag_base']) ? _x('portfolio-tag', 'slug', 'g5-portfolio') : $permalink['tag_base']);
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
                $permalink['tag_base'] = sanitize_title_with_dashes(trim($_POST[$this->tag_base]));
                update_option($this->option_key, $permalink);
            }
        }


    }
}