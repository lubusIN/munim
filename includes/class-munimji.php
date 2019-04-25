<?php
/**
 * Munimji
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munimiji.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munimji
 */

namespace LubusIN\Munimji;

/**
 * Bootstrap plugin
 */
final class Munimji {

	/**
	 * Instance.
	 *
	 * @since
	 *
	 * @var Munimji
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
	 * @return Munimji
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
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'custom_menu_order', array( $this, 'reorder_menu' ) );

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
		munimji_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'munimji' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since  1.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		munimji_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'munimji' ), '1.0' );
	}

	/**
	 * Register Scripts.
	 */
	public function register_assets() {
		// Scripts.
		wp_register_script(
			'munimji-script',
			MUNIMJI_PLUGIN_URL . '/assets/script.js',
			array(),
			filemtime( MUNIMJI_PLUGIN_DIR . '/assets/script.js' ),
			true
		);
		wp_enqueue_script( 'munimji-script' );

		// Styles.
		wp_register_style(
			'munimji',
			MUNIMJI_PLUGIN_URL . '/assets/style.css',
			array(),
			filemtime( MUNIMJI_PLUGIN_DIR . '/assets/style.css' )
		);
		wp_enqueue_style( 'munimji' );
	}

	/**
	 * Register Menu
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Munimji - Simple Invoicing', 'munimji' ),
			'Munimji',
			'manage_options',
			'admin.php?page=munimji',
			[ $this, 'render_page' ],
			'dashicons-analytics'
		);

		add_submenu_page(
			'admin.php?page=munimji',
			'Munimji > Dashboard',
			'Dashboard',
			'manage_options',
			'admin.php?page=munimji'
		);
	}

	/**
	 * Reorder munimji submenu.
	 *
	 * @return void
	 */
	public function reorder_menu() {
		global $submenu;
		$munimji_submenu = [];
		foreach ( $submenu as $menu_name => $menu_items ) {
			if ( 'admin.php?page=munimji' === $menu_name ) {
				$munimji_submenu[0]                = $menu_items[2]; // Dashboard.
				$munimji_submenu[1]                = $menu_items[0]; // Clients.
				$munimji_submenu[2]                = $menu_items[1]; // Invoices.
				$munimji_submenu[3]                = $menu_items[3]; // Settings.
				$submenu['admin.php?page=munimji'] = $munimji_submenu;
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
