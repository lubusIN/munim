<?php
/**
 * Cients
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

namespace LubusIN\Munim;

/**
 * Munim Cients
 */
class Clients {

	/**
	 * Prefix for custom meta fields
	 *
	 * @var string
	 */
	private static $meta_prefix = 'munim_client_';

	/**
	 * Init client
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ], 0 );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_cmb' ] );
	}

	/**
	 * Register custom posttype
	 *
	 * @return void
	 */
	public static function register_cpt() {
		$labels = [
			'name'                  => _x( 'Clients', 'Post Type General Name', 'munim' ),
			'singular_name'         => _x( 'Client', 'Post Type Singular Name', 'munim' ),
			'menu_name'             => __( 'Clients', 'munim' ),
			'name_admin_bar'        => __( 'Clients', 'munim' ),
			'archives'              => __( 'Item Archives', 'munim' ),
			'attributes'            => __( 'Item Attributes', 'munim' ),
			'parent_item_colon'     => __( 'Parent Item:', 'munim' ),
			'all_items'             => __( 'Clients', 'munim' ),
			'add_new_item'          => __( 'Add New Client', 'munim' ),
			'add_new'               => __( 'Add New', 'munim' ),
			'new_item'              => __( 'New Item', 'munim' ),
			'edit_item'             => __( 'Edit Item', 'munim' ),
			'update_item'           => __( 'Update Item', 'munim' ),
			'view_item'             => __( 'View Item', 'munim' ),
			'view_items'            => __( 'View Items', 'munim' ),
			'search_items'          => __( 'Search Item', 'munim' ),
			'not_found'             => __( 'Not found', 'munim' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'munim' ),
			'featured_image'        => __( 'Featured Image', 'munim' ),
			'set_featured_image'    => __( 'Set featured image', 'munim' ),
			'remove_featured_image' => __( 'Remove featured image', 'munim' ),
			'use_featured_image'    => __( 'Use as featured image', 'munim' ),
			'insert_into_item'      => __( 'Insert into item', 'munim' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'munim' ),
			'items_list'            => __( 'Items list', 'munim' ),
			'items_list_navigation' => __( 'Items list navigation', 'munim' ),
			'filter_items_list'     => __( 'Filter items list', 'munim' ),
		];
		$args   = [
			'label'               => __( 'Client', 'munim' ),
			'description'         => __( 'Munim Clients', 'munim' ),
			'labels'              => $labels,
			'supports'            => [ 'title' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'munim',
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		];
		register_post_type( 'munim_client', $args );
	}

	/**
	 * Register custom meta boxes
	 *
	 * @return void
	 */
	public static function register_cmb() {
		// Register CMB2 for client details.
		$args = [
			'id'           => self::$meta_prefix . 'details',
			'title'        => 'Details',
			'object_types' => [ 'munim_client' ],
		];

		$client_details = new_cmb2_box( $args );

		// Custom fields for client.
		$client_details->add_field(
			[
				'name' => 'Address 1',
				'id'   => self::$meta_prefix . 'address_1',
				'type' => 'text',
			]
		);

		$client_details->add_field(
			[
				'name' => 'Address 2',
				'id'   => self::$meta_prefix . 'address_2',
				'type' => 'text',
			]
		);

		$client_details->add_field(
			[
				'name' => 'City',
				'id'   => self::$meta_prefix . 'city',
				'type' => 'text_medium',
			]
		);

		$client_details->add_field(
			[
				'name' => 'State',
				'id'   => self::$meta_prefix . 'state',
				'type' => 'text_medium',
			]
		);

		$client_details->add_field(
			[
				'name' => 'Zip',
				'id'   => self::$meta_prefix . 'zip',
				'type' => 'text_small',
			]
		);

		$client_details->add_field(
			[
				'name' => 'Country',
				'id'   => self::$meta_prefix . 'country',
				'type' => 'text_small',
			]
		);

		$client_details->add_field(
			[
				'name'             => 'Currency',
				'desc'             => 'Select an currency',
				'id'               => self::$meta_prefix . 'currency',
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => get_munim_currencies(),
				'column'     => [
					'position' => 2,
					'name'     => 'Currency',
				],
			]
		);

		$client_details->add_field(
			[
				'name' => 'Hourly Rate',
				'id'   => self::$meta_prefix . 'hourly_rate',
				'type' => 'text_small',
				'column'     => [
					'position' => 3,
					'name'     => 'Hourly Rate',
				],
			]
		);

		// Register CMB2 for client additional info.
		$args = [
			'id'           => self::$meta_prefix . 'info',
			'title'        => 'Additional Info',
			'object_types' => [ 'munim_client' ],
		];

		$client_additional_info = new_cmb2_box( $args );

		// Additional info.
		$client_info = $client_additional_info->add_field(
			[
				'id'         => self::$meta_prefix . 'additional_info',
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

		$client_additional_info->add_group_field(
			$client_info,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$client_additional_info->add_group_field(
			$client_info,
			[
				'name' => 'Value',
				'id'   => 'value',
				'type' => 'text',
			]
		);
	}
}
