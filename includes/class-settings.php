<?php
/**
 * Settings
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
 * Munimji Settings
 */
class Settings {
	/**
	 * Init  plugin settings.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'cmb2_admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Register plugin settings using cmb2
	 *
	 * @return void
	 */
	public static function register_settings() {
		/**
		 * Registers main options page menu item and form.
		 */
		$args = array(
			'id'           => 'munimji_business_info',
			'title'        => 'Munimji Settings',
			'object_types' => array( 'options-page' ),
			'option_key'   => 'munimji-settings',
			'tab_group'    => 'munimji',
			'tab_title'    => 'Business Info',
			'display_cb'   => array( __CLASS__, 'render_settings' ),
			'parent_slug'  => 'admin.php?page=munimji',
			'menu_title'   => 'Settings',
		);

		$business_info = new_cmb2_box( $args );

		/**
		 * Options fields ids only need
		 * to be unique within this box.
		 * Prefix is not needed.
		 */
		$business_info->add_field(
			array(
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			)
		);

		$business_info->add_field(
			array(
				'name'         => 'Logo',
				'id'           => 'logo',
				'type'         => 'file',
				'options'      => array(
					'url' => false,
				),
				'text'         => array(
					'add_upload_file_text' => 'Add File',
				),
				'query_args'   => array(
					'type' => array(
						'image/jpeg',
						'image/png',
					),
				),
				'preview_size' => 'medium',
			)
		);

		$business_info->add_field(
			array(
				'name'         => 'Secondary Logo',
				'id'           => 'secondary_logo',
				'type'         => 'file',
				'options'      => array(
					'url' => false,
				),
				'text'         => array(
					'add_upload_file_text' => 'Add File',
				),
				'query_args'   => array(
					'type' => array(
						'image/jpeg',
						'image/png',
					),
				),
				'preview_size' => 'medium',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'Address',
				'id'   => 'address',
				'type' => 'textarea_small',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'Email',
				'id'   => 'email',
				'type' => 'text_email',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'Website',
				'id'   => 'website',
				'type' => 'text_url',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'Last Invoice Number',
				'id'   => 'last_invoice_number',
				'type' => 'text_small',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'State Of Supply',
				'id'   => 'state_of_supply',
				'type' => 'text',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'Service Code',
				'id'   => 'service_code',
				'type' => 'text',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'PAN Number',
				'id'   => 'pan_no',
				'type' => 'text',
			)
		);

		$business_info->add_field(
			array(
				'name' => 'GSTIN Number',
				'id'   => 'gstin_no',
				'type' => 'text',
			)
		);
	}

	/**
	 * Get settings tabs.
	 *
	 * @param array $cmb_options cmb2 options.
	 * @return array Array of tab information.
	 */
	public static function get_tabs( $cmb_options ) {
		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = array();
		foreach ( \CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
					? $cmb->prop( 'tab_title' )
					: $cmb->prop( 'title' );
			}
		}
		return $tabs;
	}

	/**
	 * Render settings pages
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 * @return void
	 */
	public static function render_settings( $cmb_options ) {
		$tabs = self::get_tabs( $cmb_options );
		include 'views/settings.php';
	}
}
