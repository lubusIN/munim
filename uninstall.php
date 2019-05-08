<?php
/**
 * Uninstall Munim
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete options
 */
delete_option( 'munim_settings_business' );
delete_option( 'munim_settings_invoice' );
delete_option( 'munim_settings_bank' );
