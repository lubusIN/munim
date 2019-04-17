<?php
/**
 * Plugin Name: Munimji - Simple Invoices
 * Plugin URI: https://www.munimji.com
 * Description: Simple invoicing in your admin panel.
 * Author: lubus
 * Author URI: https://www.lubus.in
 * Version: 0.1.0
 * Text Domain: munimji
 * Domain Path: /languages
 * Tags: invoice, invoicing,
 * Requires at least: 3.0.1
 * Tested up to:  5.0.3
 * Stable tag: 1.0.0
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package munimji
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Setup Constants
 */
// Plugin version.
if ( ! defined( 'MUNIMJI_VERSION' ) ) {
	define( 'MUNIMJI_VERSION', '0.1.0' );
}
// Plugin Root File.
if ( ! defined( 'MUNIMJI_PLUGIN_FILE' ) ) {
	define( 'MUNIMJI_PLUGIN_FILE', __FILE__ );
}
// Plugin Folder Path.
if ( ! defined( 'MUNIMJI_PLUGIN_DIR' ) ) {
	define( 'MUNIMJI_PLUGIN_DIR', plugin_dir_path( MUNIMJI_PLUGIN_FILE ) );
}
// Plugin Folder URL.
if ( ! defined( 'MUNIMJI_PLUGIN_URL' ) ) {
	define( 'MUNIMJI_PLUGIN_URL', plugin_dir_url( MUNIMJI_PLUGIN_FILE ) );
}
// Plugin Basename aka: "munimji/munimji.php".
if ( ! defined( 'MUNIMJI_PLUGIN_BASENAME' ) ) {
	define( 'MUNIMJI_PLUGIN_BASENAME', plugin_basename( MUNIMJI_PLUGIN_FILE ) );
}

// Autoloader.
require_once 'vendor/autoload.php';

use LubusIN\Munimji\Munimji;

/**
 * Main instance of Munimji.
 *
 * Returns the main instance of Munimji to prevent the need to use globals.
 *
 * @since  0.1.0
 * @return Munimji
 */
function munimji() {
	return Munimji::get_instance();
}

munimji();
