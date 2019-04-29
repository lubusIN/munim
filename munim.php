<?php
/**
 * Plugin Name: Munim - Simple Invoices
 * Plugin URI: https://www.munim.com
 * Description: Simple invoicing in your admin panel.
 * Author: lubus
 * Author URI: https://www.lubus.in
 * Version: 0.1.0
 * Text Domain: munim
 * Domain Path: /languages
 * Tags: invoice, invoicing,
 * Requires at least: 3.0.1
 * Tested up to:  5.0.3
 * Stable tag: 1.0.0
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package munim
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Setup Constants
 */
// Plugin version.
if ( ! defined( 'MUNIM_VERSION' ) ) {
	define( 'MUNIM_VERSION', '0.1.0' );
}
// Plugin Root File.
if ( ! defined( 'MUNIM_PLUGIN_FILE' ) ) {
	define( 'MUNIM_PLUGIN_FILE', __FILE__ );
}
// Plugin Folder Path.
if ( ! defined( 'MUNIM_PLUGIN_DIR' ) ) {
	define( 'MUNIM_PLUGIN_DIR', plugin_dir_path( MUNIM_PLUGIN_FILE ) );
}
// Plugin Folder URL.
if ( ! defined( 'MUNIM_PLUGIN_URL' ) ) {
	define( 'MUNIM_PLUGIN_URL', plugin_dir_url( MUNIM_PLUGIN_FILE ) );
}
// Plugin Basename aka: "munim/munim.php".
if ( ! defined( 'MUNIM_PLUGIN_BASENAME' ) ) {
	define( 'MUNIM_PLUGIN_BASENAME', plugin_basename( MUNIM_PLUGIN_FILE ) );
}

// Autoloader.
require_once 'vendor/autoload.php';
require_once 'vendor/cmb2/init.php';
require_once 'vendor/cmb2-field-post-search-ajax/cmb-field-post-search-ajax.php';

// Bootstrap Munim.
use LubusIN\Munim\Munim;

/**
 * Main instance of Munim.
 *
 * Returns the main instance of Munim to prevent the need to use globals.
 *
 * @since  0.1.0
 * @return Munim
 */
function munim() {
	return Munim::get_instance();
}

munim();
