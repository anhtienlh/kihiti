<?php
/**
 *    Plugin Name: Thallo Addons
 *    Plugin URI: http://g5plus.net
 *    Description: Addons For TheThallo Theme
 *    Version: 1.0.3
 *    Author: G5
 *    Author URI: http://g5plus.net
 *
 *    Text Domain: thallo-addons
 *    Domain Path: /languages/
 *
 * @author G5
 *
 **/
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!defined('G5CORE_CURRENT_THEME')) {
	define('G5CORE_CURRENT_THEME', basename(get_template_directory()));
}

if (!class_exists('G5ThemeAddons')) {
	class G5ThemeAddons {
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $meta_prefix = 'gta_';

		public function __construct()
		{
			/*Auto load file*/
			spl_autoload_register( array( $this, 'auto_load' ) );
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
			add_action( 'g5core_init', array( $this, 'init' ) );
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_action('g5blog_init',array($this->blog(),'init'));
            add_action('g5element_init',array($this->element(),'init'));
			add_action('g5portfolio_init',array($this->portfolio(),'init'));
			add_action('g5services_init',array($this->services(),'init'));
			add_action('g5staff_init',array($this->staff(),'init'));
			add_action('g5works_init',array($this->works(),'init'));
			add_action('g5careers_init',array($this->careers(),'init'));

		}

		public function init() {
            remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
			$this->init_modules();
			$this->setup_theme_data()->init();
			$this->setup_install_demo()->init();

		}


        public function admin_notices() {
            ?>
            <div class="error">
                <p><?php esc_html_e('Thallo Addons is enabled but not effective. It requires G5 Core in order to work.','thallo-addons'); ?></p>
            </div>
            <?php
        }

		public function load_file( $path ) {
			if ( $path && is_readable( $path ) ) {
				include_once $path;

				return true;
			}

			return false;
		}

		public function plugin_dir( $path = '' ) {
			return plugin_dir_path( __FILE__ ) . $path;
		}

		public function plugin_url( $path = '' ) {
			return trailingslashit( plugins_url( basename( __DIR__ ) ) ) . $path;
		}

		public function init_modules() {
			$this->includes();
		}

		public function includes() {
			$this->load_file($this->plugin_dir('inc/functions/functions.php'));
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'thallo-addons', false, $this->plugin_dir('languages'));
		}

		public function auto_load($class){
			$file_name = preg_replace('/^G5ThemeAddons_/', '', $class);
			if ($file_name !== $class) {
				$path  = '';
				$file_name = strtolower($file_name);
				$file_name = str_replace('_', '-', $file_name);
				$this->load_file($this->plugin_dir("inc/{$path}{$file_name}.class.php"));

			}
		}

		public function assets_handle($handle = '') {
			return $this->meta_prefix . $handle;
		}


		public function asset_url($file) {
			if (!file_exists($this->plugin_dir($file)) || (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG)) {
				$ext = explode('.', $file);
				$ext = end($ext);
				$normal_file = preg_replace('/((\.min\.css)|(\.min\.js))$/', '', $file);
				if ($normal_file != $file) {
					$normal_file = untrailingslashit($normal_file) . ".{$ext}";
					if (file_exists($this->plugin_dir($normal_file))) {
						return $this->plugin_url(untrailingslashit($normal_file));
					}
				}
			}
			return $this->plugin_url(untrailingslashit($file));
		}

		public function get_plugin_template($slug, $args = array()) {
			if ($args && is_array($args)) {
				extract($args);
			}
			$located = $this->plugin_dir($slug );
			if (!file_exists($located)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $slug), '1.0.1');

				return '';
			}
			include($located);

			return $located;
		}

		public function get_template( $template_name, $args = array() ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}

			$located = $this->locate_template($template_name, $args);
			if ($located !== '') {
				do_action( 'gta_before_template_part', $template_name, $located, $args );
				include( $located );
				do_action( 'gta_after_template_part', $template_name, $located, $args );
			}
		}

		public function locate_template($template_name, $args = array()) {
			$located = '';

			// Theme or child theme template
			$template = trailingslashit(get_stylesheet_directory()) . 'g5plus/gta/' . $template_name;
			if (file_exists($template)) {
				$located = $template;
			}

			// Plugin template
			if (! $located) {
				$located = $this->plugin_dir() . 'templates/' . $template_name;
			}

			$located = apply_filters( 'gta_locate_template', $located, $template_name, $args);

			if (!file_exists($located)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '1.0.1');
				return '';
			}

			// Return what we found.
			return $located;
		}

		public function plugin_ver() {
			if (G5CORE()->cache()->exists('gta_plugin_version')) {
				return G5CORE()->cache()->get('gta_plugin_version');
			}
			if (!function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_ver = isset($plugin_data['Version']) ? $plugin_data['Version'] : '1.0.1';
			if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) {
				$plugin_ver = mt_rand() . '';
			}

			G5CORE()->cache()->set('gta_plugin_version', $plugin_ver);
			return $plugin_ver;
		}

		/**
		 * @return G5ThemeAddons_Setup_Theme_Data
		 */
		public function setup_theme_data() {
			return G5ThemeAddons_Setup_Theme_Data::getInstance();
		}


		/**
		 * @return G5ThemeAddons_Setup_Install_Demo
		 */
		public function setup_install_demo() {
			return G5ThemeAddons_Setup_Install_Demo::getInstance();
		}



        /**
         * @return G5ThemeAddons_Blog
         */
        public function blog() {
            return G5ThemeAddons_Blog::getInstance();
        }

        /**
         * @return G5ThemeAddons_Core
         */
        public function core() {
            return G5ThemeAddons_Core::getInstance();
        }

		/**
		 * @return G5ThemeAddons_Element
		 */
        public function element() {
        	return G5ThemeAddons_Element::getInstance();
        }

		/**
		 * @return G5ThemeAddons_Portfolio
		 */
		public function portfolio() {
			return G5ThemeAddons_Portfolio::getInstance();
		}

		/**
		 * @return G5ThemeAddons_Services
		 */
		public function services() {
			return G5ThemeAddons_Services::getInstance();
		}

		/**
		 * @return G5ThemeAddons_Staff
		 */
		public function staff() {
			return G5ThemeAddons_Staff::getInstance();
		}

		/**
		 * @return G5ThemeAddons_Works
		 */
		public function works() {
			return G5ThemeAddons_Works::getInstance();
		}

		/**
		 * @return G5ThemeAddons_Careers
		 */
		public function careers() {
			return G5ThemeAddons_Careers::getInstance();
		}
	}

	function GTA() {
		return G5ThemeAddons::getInstance();
	}

	GTA();
}
