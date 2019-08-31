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
 * @package   Munim
 */

namespace LubusIN\Munim;

/**
 * Munim Settings
 */
class Settings {

	/**
	 * Prefix for options.
	 *
	 * @var string
	 */
	private static $options_prefix = 'munim_settings_';

	/**
	 * Option keys without prefix
	 *
	 * @var array
	 */
	private static $option_keys = [
		'business',
		'invoice',
		'bank',
		'template',
	];

	/**
	 * Init  plugin settings.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register' ] );
		add_action( 'admin_init', [ __CLASS__, 'process_data' ] );
	}

	/**
	 * Register plugin settings using cmb2
	 *
	 * @return void
	 */
	public static function register() {
		// Register business settings.
		$args = [
			'id'           => self::$options_prefix . 'business',
			'title'        => 'Business',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'business',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Business',
			'display_cb'   => [ __CLASS__, 'render' ],
			'parent_slug'  => 'admin.php?page=munim',
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
				'name'             => 'Currency',
				'desc'             => 'Select an currency',
				'id'               => 'currency',
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => get_munim_currencies(),
			]
		);

		$business_settings->add_field(
			[
				'name' => 'Contact',
				'id'   => 'contact',
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
			'title'        => 'Invoice',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'invoice',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Invoice',
			'display_cb'   => [ __CLASS__, 'render' ],
			'parent_slug'  => 'admin.php?page=munim_settings_business',
		];

		$invoice_settings = new_cmb2_box( $args );

		// Invoice settings field.
		$invoice_settings->add_field(
			[
				'name'    => 'Last Number',
				'id'      => 'last_number',
				'type'    => 'text_small',
				'default' => '0000',
			]
		);

		$invoice_settings->add_field(
			[
				'name'    => 'Date Format',
				'id'      => 'date_format',
				'type'    => 'text_small',
				'default' => 'd/m/Y',
			]
		);

		$invoice_settings->add_field(
			[
				'name'    => 'TDS %',
				'id'      => 'tds',
				'type'    => 'text_small',
				'default' => '10',
			]
		);

		$invoice_settings->add_field(
			[
				'name'    => 'Credit Period',
				'id'      => 'credit',
				'type'    => 'text_small',
				'default' => '15',
			]
		);

		$invoice_settings->add_field(
			[
				'name' => 'Note',
				'id'   => 'note',
				'type' => 'text',
			]
		);

		$invoice_info = $invoice_settings->add_field(
			[
				'id'         => 'info',
				'desc'       => __( 'Info to be displayed on invoice e.g. Tax No, Pan No etc', 'munim' ),
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Info {#}', 'munim' ),
					'add_button'     => __( 'Add Info', 'munim' ),
					'remove_button'  => __( 'Remove Info', 'munim' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munim' ),
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

		// Bank Settings.
		$args = [
			'id'           => self::$options_prefix . 'bank',
			'title'        => 'Bank',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'bank',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Bank',
			'display_cb'   => [ __CLASS__, 'render' ],
			'parent_slug'  => 'admin.php?page=munim_settings_business',
		];

		$bank_settings = new_cmb2_box( $args );

		$bank_info = $bank_settings->add_field(
			[
				'id'         => 'info',
				'desc'       => __( 'Bank Account Details', 'munim' ),
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Info {#}', 'munim' ),
					'add_button'     => __( 'Add Info', 'munim' ),
					'remove_button'  => __( 'Remove Info', 'munim' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munim' ),
				],
			]
		);

		$bank_settings->add_group_field(
			$bank_info,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$bank_settings->add_group_field(
			$bank_info,
			[
				'name' => 'Value',
				'id'   => 'value',
				'type' => 'text',
			]
		);

		// Template Settings.
		$args = [
			'id'           => self::$options_prefix . 'template',
			'title'        => 'Template',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'template',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Template',
			'display_cb'   => [ __CLASS__, 'render' ],
			'parent_slug'  => 'admin.php?page=munim_settings_business',
		];

		$template_settings = new_cmb2_box( $args );
		$screenshot_path   = MUNIM_PLUGIN_URL . 'templates/';

		$template_settings->add_field(
			[
				'name'    => 'Use',
				'id'      => 'template',
				'type'    => 'radio_inline',
				'options' => array(
					'minimal' => sprintf(
						'Minimal <br/> <br/><img width="200px" src="%sminimal/screenshot.png" />',
						$screenshot_path
					),
				),
				'default' => 'minimal',
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
	public static function render( $cmb_options ) {
		$tabs = self::get_tabs( $cmb_options );
		include 'views/settings.php';
	}

	/**
	 * Process settings import / export
	 *
	 * @return void
	 */
	public static function process_data() {
		// Bailout.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( $_POST['munim_action'] ) ) {
			return;
		}

		// Process action.
		ignore_user_abort( true );

		if ( isset( $_REQUEST['munim_import_nonce'] ) || isset( $_REQUEST['munim_export_nonce'] ) ) {
			switch ( $_POST['munim_action'] ) {
				case 'import_settings':
					$import_nonce = sanitize_text_field( wp_unslash( $_REQUEST['munim_import_nonce'] ) );

					if ( wp_verify_nonce( $import_nonce, 'munim_import_nonce' ) ) {
						self::import();
					}
					break;
				case 'export_settings':
					$export_nonce = sanitize_text_field( wp_unslash( $_REQUEST['munim_export_nonce'] ) );

					if ( wp_verify_nonce( $export_nonce, 'munim_export_nonce' ) ) {
						self::export();
					}
					break;
			}
		}
	}

	/**
	 * Import settings from .json file
	 *
	 * @return void
	 */
	public static function import() {
		// File validation.
		if ( isset( $_FILES['munim_import_file'] ) ) {

			$import_file = sanitize_text_field( wp_unslash( $_FILES['munim_import_file'] ) );

			$filename  = explode( '.', $import_file['name'] );
			$extension = end( $filename );
			if ( 'json' !== $extension ) {
				Helpers::add_admin_notice( 'error', 'Please upload a valid .json file' );
			}

			$import_file = $import_file['tmp_name'];
			if ( empty( $import_file ) ) {
				Helpers::add_admin_notice( 'error', 'Please upload a file to import' );
			}

			// Process import.
			// phpcs:ignore
			$settings = json_decode( file_get_contents( $import_file ), true );

			foreach ( $settings as $key => $value ) {
				update_option( $key, $value );
			}

			Helpers::add_admin_notice( 'success', 'Settings imported successfully' );

		} else {
			Helpers::add_admin_notice( 'error', 'Please upload valid settings file' );
		}
	}

	/**
	 * Export settings to .json file
	 *
	 * @return void
	 */
	public static function export() {
		// Get settings.
		$settings = [];

		foreach ( self::$option_keys as $option ) {
			$option_id              = self::$options_prefix . $option;
			$settings[ $option_id ] = get_option( $option_id );
		}

		// Generate .json file.
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=munim-settings-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );
		echo wp_json_encode( $settings );

		exit;
	}
}
