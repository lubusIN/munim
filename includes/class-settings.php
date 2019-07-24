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
			'title'        => 'Munim Settings > Business',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'business',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Business',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
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
			'title'        => 'Munim Settings > Invoice',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'invoice',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Invoice',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
			'parent_slug'  => 'admin.php?page=munim_settings_business',
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

		$invoice_settings->add_field(
			[
				'name'    => 'Date Format',
				'id'      => 'date_format',
				'type'    => 'text',
				'default' => 'd/m/Y',
			]
		);

		$invoice_settings->add_field(
			[
				'name'    => 'TDS %',
				'id'      => 'tds',
				'type'    => 'text',
				'default' => '10',
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
			'title'        => 'Munim Settings > Bank',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'bank',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Bank',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
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
			'title'        => 'Munim Settings > Template',
			'object_types' => [ 'options-page' ],
			'option_key'   => self::$options_prefix . 'template',
			'tab_group'    => 'munim_settings',
			'tab_title'    => 'Template',
			'display_cb'   => [ __CLASS__, 'render_settings' ],
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
					'minimal' => __( 'Minimal <br/> <br/><img width="200px" src="' . $screenshot_path . 'minimal/screenshot.png" />', 'munim' ),
					'modern'  => __( 'Modern <br/> <br/><img width="200px" src="' . $screenshot_path . 'modern/screenshot.png" />', 'munim' ),
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
	public static function render_settings( $cmb_options ) {
		$tabs = self::get_tabs( $cmb_options );
		include 'views/settings.php';
	}
}
