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
	 * Prefix for options.
	 *
	 * @var string
	 */
	private static $options_prefix = 'munimji_settings_';

	/**
	 * Init  plugin settings.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_settings' ] );
	}

	/**
	 * Register plugin settings using cmb2
	 *
	 * @return void
	 */
	public static function register_settings() {
		// Register business settings.
		$args = [
			'id'           => self::$options_prefix . 'business',
			'title'        => 'Munimji Settings > Business',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'business',
			'tab_group'    => 'munimji_settings',
			'tab_title'    => 'Business',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
			'parent_slug'  => 'admin.php?page=munimji',
			'menu_title'   => 'Settings',
		];

		$business_settings = new_cmb2_box( $args );

		// Business setting fields.
		$business_settings->add_field(
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$business_settings->add_field(
			[
				'name'         => 'Logo',
				'id'           => 'logo',
				'type'         => 'file',
				'options'      => [ 'url' => false ],
				'text'         => [ 'add_upload_file_text' => 'Add File' ],
				'query_args'   => [
					'type' => [
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'thumbnail',
			]
		);

		$business_settings->add_field(
			[
				'name'         => 'Secondary Logo',
				'id'           => 'secondary_logo',
				'type'         => 'file',
				'options'      => [ 'url' => false ],
				'text'         => [ 'add_upload_file_text' => 'Add File' ],
				'query_args'   => [
					'type' => [
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'thumbnail',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Address 1',
				'id'   => 'address_1',
				'type' => 'text',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Address 2',
				'id'   => 'address_2',
				'type' => 'text',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'City',
				'id'   => 'city',
				'type' => 'text_medium',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'State',
				'id'   => 'state',
				'type' => 'text_medium',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Zip',
				'id'   => 'zip',
				'type' => 'text_small',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Country',
				'id'   => 'country',
				'type' => 'text_small',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Email',
				'id'   => 'email',
				'type' => 'text_email',
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Website',
				'id'   => 'website',
				'type' => 'text',
			]
		);

		// Invoice Settings.
		$args = [
			'id'           => self::$options_prefix . 'invoice',
			'title'        => 'Munimji Settings > Invoice',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'invoice',
			'tab_group'    => 'munimji_settings',
			'tab_title'    => 'Invoice',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
			'parent_slug'  => 'admin.php?page=munimji_settings_business',
		];

		$invoice_settings = new_cmb2_box( $args );

		// Invoice settings field.
		$invoice_settings->add_field(
			[
				'name'    => 'Last Number',
				'id'      => 'last_number',
				'type'    => 'text',
				'default' => '0000',
			]
		);

		$invoice_info = $invoice_settings->add_field(
			[
				'id'         => 'info',
				'desc'       => __( 'Info to be displayed on invoice e.g. Tax No, Pan No etc', 'munimji' ),
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Info {#}', 'munimji' ),
					'add_button'     => __( 'Add Info', 'munimji' ),
					'remove_button'  => __( 'Remove Info', 'munimji' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munimji' ),
				],
			]
		);

		$invoice_settings->add_group_field(
			$invoice_info,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$invoice_settings->add_group_field(
			$invoice_info,
			[
				'name' => 'Value',
				'id'   => 'value',
				'type' => 'text',
			]
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
