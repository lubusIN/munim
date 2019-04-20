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
		$labels = array(
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
		);
		$args   = array(
			'label'               => __( 'Client', 'munimji' ),
			'description'         => __( 'Munimji Clients', 'munimji' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
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
		);
		register_post_type( 'munimji_client', $args );
	}

	/**
	 * Register custom meta boxes
	 *
	 * @return void
	 */
	public static function register_cmb() {
		/**
		 * Registers main options page menu item and form.
		 */
		$args = array(
			'id'           => 'munimji_client_info',
			'title'        => 'Client Info',
			'object_types' => array( 'munimji_client' ),
			'option_key'   => 'munimji-client',
		);

		$client_info = new_cmb2_box( $args );

		/**
		 * Options fields ids only need
		 * to be unique within this box.
		 * Prefix is not needed.
		 */

		$client_info->add_field(
			array(
				'name' => 'Address',
				'id'   => 'address',
				'type' => 'textarea_small',
			)
		);

		$client_info->add_field(
			array(
				'name' => 'GSTIN Number',
				'id'   => 'gstin_no',
				'type' => 'text',
			)
		);
	}
}
