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
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
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
			TO_PLUGIN_URL . '/assets/script.js',
			array(),
			filemtime( TO_PLUGIN_DIR . '/assets/script.js' ),
			true
		);
		wp_enqueue_script( 'munimji-script' );

		// Styles.
		wp_register_style(
			'munimji',
			TO_PLUGIN_URL . '/assets/style.css',
			array(),
			filemtime( TO_PLUGIN_DIR . '/assets/style.css' )
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
			'',
			'dashicons-analytics'
		);
	}
}
