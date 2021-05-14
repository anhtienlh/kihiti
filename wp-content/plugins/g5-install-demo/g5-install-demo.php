<?php
/**
 *    Plugin Name: G5 Install Demo
 *    Plugin URI: http://g5plus.net
 *    Description: Install site demo data with one click.
 *    Version: 1.0.1
 *    Author: G5
 *    Author URI: http://g5plus.net
 *
 *    Text Domain: gid
 *    Domain Path: /languages/
 *
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'G5InstallDemo' ) ) {
	class G5InstallDemo {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public $meta_prefix = 'gid_';

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'g5core_init', array( $this, 'init' ) );
		}

		public function admin_notices() {
			?>
			<div class="error">
				<p><?php esc_html_e( 'G5 Install Demo is enabled but not effective. It requires G5 Core in order to work.', 'gid' ); ?></p>
			</div>
			<?php
		}

		public function init() {
			remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
			$this->includes();
			do_action('gid_init');
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

		public function includes() {
			$this->load_file( $this->plugin_dir( 'inc/assets.php' ) );
			$this->load_file( $this->plugin_dir( 'inc/install-setup.php' ) );
			$this->load_file( $this->plugin_dir( 'inc/dashboard-menu.php' ) );
			$this->load_file( $this->plugin_dir( 'inc/ajax.php' ) );
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'gid', false, $this->plugin_dir( 'languages' ) );
		}

		public function assets_handle( $handle = '' ) {
			return apply_filters( 'gid_assets_prefix', $this->meta_prefix ) . $handle;
		}

		public function asset_url( $file ) {
			if ( ! file_exists( $this->plugin_dir( $file ) ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
				$ext         = explode( '.', $file );
				$ext         = end( $ext );
				$normal_file = preg_replace( '/((\.min\.css)|(\.min\.js))$/', '', $file );
				if ( $normal_file != $file ) {
					$normal_file = untrailingslashit( $normal_file ) . ".{$ext}";
					if ( file_exists( $this->plugin_dir( $normal_file ) ) ) {
						return $this->plugin_url( untrailingslashit( $normal_file ) );
					}
				}
			}

			return $this->plugin_url( untrailingslashit( $file ) );
		}

		public function get_plugin_template( $slug, $args = array() ) {
			if ( $args && is_array( $args ) ) {
				extract( $args );
			}
			$located = $this->plugin_dir( $slug );
			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $slug ), '1.0' );

				return '';
			}
			include( $located );

			return $located;
		}

		public function get_template( $template_name, $args = array() ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}

			$located = $this->locate_template( $template_name, $args );
			if ( $located !== '' ) {
				do_action( 'gid_before_template_part', $template_name, $located, $args );
				include( $located );
				do_action( 'gid_after_template_part', $template_name, $located, $args );
			}
		}

		public function locate_template( $template_name, $args = array() ) {
			$located = '';

			// Theme or child theme template
			$template = trailingslashit( get_stylesheet_directory() ) . 'g5plus/gid/' . $template_name;
			if ( file_exists( $template ) ) {
				$located = $template;
			}

			// Plugin template
			if ( ! $located ) {
				$located = $this->plugin_dir() . 'templates/' . $template_name;
			}

			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0' );

				return '';
			}

			// Return what we found.
			return apply_filters( 'gid_locate_template', $located, $template_name, $args );
		}

		public function plugin_ver() {
			if ( G5CORE()->cache()->exists( 'gid_plugin_version' ) ) {
				return G5CORE()->cache()->get( 'gid_plugin_version' );
			}
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_ver  = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '1.0';
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
				$plugin_ver = mt_rand() . '';
			}

			G5CORE()->cache()->set( 'gid_plugin_version', $plugin_ver );

			return $plugin_ver;
		}
	}

	function GID() {
		return G5InstallDemo::getInstance();
	}

	GID();
}