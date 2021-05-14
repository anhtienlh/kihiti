<?php
/**
 *    Plugin Name: G5 Services
 *    Plugin URI: http://g5plus.net
 *    Description: The G5 Services plugin.
 *    Version: 1.0.3
 *    Author: G5
 *    Author URI: http://g5plus.net
 *
 *    Text Domain: g5-services
 *    Domain Path: /languages/
 *
 **/
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
if (!class_exists('G5SERVICES')) {
	class G5SERVICES {
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $meta_prefix = 'g5services_';

		public function __construct()
		{
			add_action('g5core_init',array($this,'init'));
			add_action( 'plugins_loaded', array($this,'load_text_domain'));
			spl_autoload_register(array($this, 'auto_load'));
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		public function init() {
			remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
			$this->includes();
			$this->assets()->init();
			$this->config_options()->init();
			$this->templates()->init();
			$this->listing()->init();
			$this->admin()->init();
			$this->shortcodes()->init();
			$this->widget()->init();
			$this->query()->init();
			do_action('g5services_init');
		}

		public function admin_notices() {
			?>
			<div class="error">
				<p><?php esc_html_e( 'G5 Services is enabled but not effective. It requires G5 Core in order to work.', 'g5-services' ); ?></p>
			</div>
			<?php
		}

		public function includes() {
			$this->load_file($this->plugin_dir('inc/functions/function.php'));
		}

		public function load_file($path) {
			if ( $path && is_readable($path) ) {
				include_once $path;
				return true;
			}
			return false;
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'g5-services', false, $this->plugin_dir('languages'));
		}

		public function plugin_dir($path = '') {
			return plugin_dir_path(__FILE__) . $path;
		}

		public function plugin_url($path = '') {
			return trailingslashit( plugins_url( '/', __FILE__ ) ) . $path;
		}

		public function theme_dir($path = '')
		{
			return trailingslashit(get_template_directory()) . $path;
		}

		public function theme_url($path = '')
		{
			return trailingslashit(get_template_directory_uri()) . $path;
		}

		public function auto_load($class) {
			$file_name = preg_replace('/^G5Services_/', '', $class);
			if ($file_name !== $class) {
				$path  = '';
				if ( 0 === strpos( $class, 'G5Services_Widget' )  ) {
					if (preg_match('/^G5Services_Widget_/',$class)) {
						$file_name = preg_replace('/^G5Services_Widget_/', '', $class);
					}
					$path = 'widgets/';
				} elseif (0 === strpos( $class, 'G5Services_Admin' )) {
					if (preg_match('/^G5Services_Admin_/',$class)) {
						$file_name = preg_replace('/^G5Services_Admin_/', '', $class);
					}
					$path = 'admin/';
				}

				$file_name = strtolower($file_name);
				$file_name = str_replace('_', '-', $file_name);
				$this->load_file($this->plugin_dir("inc/{$path}{$file_name}.class.php"));
			}
		}

		public function assets_handle($handle = '') {
			return apply_filters('g5services_assets_prefix', 'g5services_') . $handle;
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
			$located = $this->plugin_dir($slug . '.php');
			if (!file_exists($located)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $slug), '1.0.3');

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
				do_action( 'g5services_before_template_part', $template_name, $located, $args );
				include( $located );
				do_action( 'g5services_after_template_part', $template_name, $located, $args );
			}
		}

		public function template_path() {
			return apply_filters('g5services_template_path', 'g5plus/services/');
		}

		public function locate_template($template_name, $args = array()) {
			$located = '';

			// Theme or child theme template
			$template = trailingslashit(get_stylesheet_directory()) . $this->template_path() . $template_name;
			if (file_exists($template)) {
				$located = $template;
			} else {
				$template = trailingslashit(get_template_directory()) . $this->template_path() . $template_name;
				if (file_exists($template)) {
					$located = $template;
				}
			}

			// Plugin template
			if (! $located) {
				$located = $this->plugin_dir() . 'templates/' . $template_name;
			}

			$located = apply_filters( 'g5services_locate_template', $located, $template_name, $args);

			if (!file_exists($located)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '1.0.3');
				return '';
			}

			// Return what we found.
			return $located;
		}

		public function plugin_ver() {
			if (G5CORE()->cache()->exists('g5services_plugin_version')) {
				return G5CORE()->cache()->get('g5services_plugin_version');
			}

			if (!function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_ver = isset($plugin_data['Version']) ? $plugin_data['Version'] : '1.0.3';
			if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) {
				$plugin_ver = mt_rand() . '';
			}

			G5CORE()->cache()->set('g5services_plugin_version', $plugin_ver);
			return $plugin_ver;
		}

		/**
		 * @return G5Services_Assets
		 */
		public function assets(){
			return G5Services_Assets::getInstance();
		}

		/**
		 * @return G5Services_Config_Options
		 */
		public function config_options() {
			return G5Services_Config_Options::getInstance();
		}

		/**
		 * @return G5Services_Options
		 */
		public function options() {
			return G5Services_Options::getInstance();
		}

		/**
		 * @return G5Services_Settings
		 */
		public function settings() {
			return G5Services_Settings::getInstance();
		}

		/**
		 * @return G5Services_Templates
		 */
		public function templates() {
			return G5Services_Templates::getInstance();
		}

		/**
		 * @return G5Services_Listing
		 */
		public function listing() {
			return G5Services_Listing::getInstance();
		}

		/**
		 * @return G5Services_Admin
		 */
		public function admin() {
			return G5Services_Admin::getInstance();
		}

		/**
		 * @return G5Services_ShortCodes
		 */
		public function shortcodes() {
			return G5Services_ShortCodes::getInstance();
		}


		/**
		 * @return G5Services_Widget
		 */
		public function widget() {
			return G5Services_Widget::getInstance();
		}

		/**
		 * @return G5Services_Query
		 */
		public function query() {
			return G5Services_Query::getInstance();
		}
	}

	function G5SERVICES() {
		return G5SERVICES::getInstance();
	}

	G5SERVICES();
}
