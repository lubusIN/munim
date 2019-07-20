<?php
/**
 * Munim
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munimiji.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

namespace LubusIN\Munim;

/**
 * Bootstrap plugin
 */
final class Munim {

	/**
	 * Instance.
	 *
	 * @since
	 *
	 * @var Munim
	 */
	private static $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Get instance.
	 *
	 * @since
	 *
	 * @return Munim
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since  1.0.0
	 */
	private function init_hooks() {
		// Set up init Hook.
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'custom_menu_order', [ $this, 'reorder_menu' ] );

		// Modules.
		Settings::init();
		Clients::init();
		Invoices::init();
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object, therefore we don't want the object to be cloned.
	 *
	 * @since  1.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		munim_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'munim' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since  1.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		munim_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'munim' ), '1.0' );
	}

	/**
	 * Register Scripts.
	 */
	public function register_assets() {

		$screen = get_current_screen();

		// Bailout if not munim dashboard
		if ( 'toplevel_page_admin?page=munim' !== $screen->id ) {
			return;
		}

		// Scripts.
		wp_register_script(
			'apexcharts-script',
			'https://cdn.jsdelivr.net/npm/apexcharts',
			[],
			null,
			true
		);
		wp_enqueue_script( 'apexcharts-script' );

		wp_register_script(
			'munim-script',
			MUNIM_PLUGIN_URL . 'assets/js/script.js',
			['apexcharts-script'],
			filemtime( MUNIM_PLUGIN_DIR . 'assets/js/script.js' ),
			true
		);
		wp_enqueue_script( 'munim-script' );

		wp_register_script(
			'munim-dashboard',
			MUNIM_PLUGIN_URL . 'assets/js/dashboard.js',
			[],
			filemtime( MUNIM_PLUGIN_DIR . 'assets/js/dashboard.js' ),
			true
		);
		wp_enqueue_script( 'munim-dashboard' );

		// Styles.
		wp_register_style(
			'munim',
			MUNIM_PLUGIN_URL . 'assets/css/style.css',
			[],
			filemtime( MUNIM_PLUGIN_DIR . 'assets/css/style.css' )
		);
		wp_enqueue_style( 'munim' );
	}

	/**
	 * Register Menu
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Munim - Simple Invoicing', 'munim' ),
			'Munim',
			'manage_options',
			'admin.php?page=munim',
			[ $this, 'render_page' ],
			'dashicons-analytics'
		);

		add_submenu_page(
			'admin.php?page=munim',
			'Munim > Dashboard',
			'Dashboard',
			'manage_options',
			'admin.php?page=munim'
		);
	}

	/**
	 * Reorder munim submenu.
	 *
	 * @return void
	 */
	public function reorder_menu() {
		global $submenu;
		$munim_submenu = [];
		foreach ( $submenu as $menu_name => $menu_items ) {
			if ( 'admin.php?page=munim' === $menu_name ) {
				$munim_submenu[0]                = $menu_items[2]; // Dashboard.
				$munim_submenu[1]                = $menu_items[0]; // Clients.
				$munim_submenu[2]                = $menu_items[1]; // Invoices.
				$munim_submenu[3]                = $menu_items[3]; // Settings.
				$submenu['admin.php?page=munim'] = $munim_submenu;
				break;
			}
		}
	}

	/**
	 * Render plugin landing page.
	 *
	 * @return void
	 */
	public function render_page() {
		include 'views/dashboard.php';
	}
}
