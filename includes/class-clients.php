<?php
/**
 * Cients
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
 * Munimji Cients
 */
class Clients {

	/**
	 * Prefix for custom meta fields
	 *
	 * @var string
	 */
	private static $meta_prefix = 'munimji_client_';

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
			'name'                  => _x( 'Clients', 'Post Type General Name', 'munimji' ),
			'singular_name'         => _x( 'Client', 'Post Type Singular Name', 'munimji' ),
			'menu_name'             => __( 'Clients', 'munimji' ),
			'name_admin_bar'        => __( 'Clients', 'munimji' ),
			'archives'              => __( 'Item Archives', 'munimji' ),
			'attributes'            => __( 'Item Attributes', 'munimji' ),
			'parent_item_colon'     => __( 'Parent Item:', 'munimji' ),
			'all_items'             => __( 'Clients', 'munimji' ),
			'add_new_item'          => __( 'Add New Client', 'munimji' ),
			'add_new'               => __( 'Add New', 'munimji' ),
			'new_item'              => __( 'New Item', 'munimji' ),
			'edit_item'             => __( 'Edit Item', 'munimji' ),
			'update_item'           => __( 'Update Item', 'munimji' ),
			'view_item'             => __( 'View Item', 'munimji' ),
			'view_items'            => __( 'View Items', 'munimji' ),
			'search_items'          => __( 'Search Item', 'munimji' ),
			'not_found'             => __( 'Not found', 'munimji' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'munimji' ),
			'featured_image'        => __( 'Featured Image', 'munimji' ),
			'set_featured_image'    => __( 'Set featured image', 'munimji' ),
			'remove_featured_image' => __( 'Remove featured image', 'munimji' ),
			'use_featured_image'    => __( 'Use as featured image', 'munimji' ),
			'insert_into_item'      => __( 'Insert into item', 'munimji' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'munimji' ),
			'items_list'            => __( 'Items list', 'munimji' ),
			'items_list_navigation' => __( 'Items list navigation', 'munimji' ),
			'filter_items_list'     => __( 'Filter items list', 'munimji' ),
		];
		$args   = [
			'label'               => __( 'Client', 'munimji' ),
			'description'         => __( 'Munimji Clients', 'munimji' ),
			'labels'              => $labels,
			'supports'            => [ 'title' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'admin.php?page=munimji',
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
		register_post_type( 'munimji_client', $args );
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
			'object_types' => [ 'munimji_client' ],
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
				'name' => 'GSTIN',
				'id'   => self::$meta_prefix . 'gstin',
				'type' => 'text_medium',
			]
		);
	}
}
