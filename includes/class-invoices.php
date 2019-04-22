<?php
/**
 * Invoices
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
 * Munimji Invoices
 */
class Invoices {
	/**
	 * Init invoice
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ], 0 );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_cmb' ] );
		add_action( 'wp_insert_post', [ __CLASS__, 'update_number' ], 10, 3 );
	}

	/**
	 * Register custom posttype
	 *
	 * @return void
	 */
	public static function register_cpt() {
		$labels = array(
			'name'                  => _x( 'Invoices', 'Post Type General Name', 'munimji' ),
			'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'munimji' ),
			'menu_name'             => __( 'Invoices', 'munimji' ),
			'name_admin_bar'        => __( 'Invoices', 'munimji' ),
			'archives'              => __( 'Item Archives', 'munimji' ),
			'attributes'            => __( 'Item Attributes', 'munimji' ),
			'parent_item_colon'     => __( 'Parent Item:', 'munimji' ),
			'all_items'             => __( 'Invoices', 'munimji' ),
			'add_new_item'          => __( 'Add New Invoice', 'munimji' ),
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
			'label'               => __( 'Invoice', 'munimji' ),
			'description'         => __( 'Munimji Invoices', 'munimji' ),
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
		register_post_type( 'munimji_invoice', $args );
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
			'id'           => 'munimji_invoice_info',
			'title'        => 'Invoice Info',
			'object_types' => array( 'munimji_invoice' ),
		);

		$invoice_info = new_cmb2_box( $args );

		/**
		 * Options fields ids only need
		 * to be unique within this box.
		 * Prefix is not needed.
		 */
		$invoice_info->add_field(
			array(
				'name'       => 'Number',
				'id'         => 'number',
				'type'       => 'text_small',
				'default_cb' => [ __CLASS__, 'get_number' ],
				'attributes' => array(
					'readonly' => 'readonly',
				),
			)
		);

		$invoice_info->add_field(
			array(
				'name'       => __( 'Client', 'munimji' ),
				'id'         => 'client',
				'type'       => 'post_search_ajax',
				'desc'       => __( '(Start typing client name)', 'munimji' ),
				'limit'      => 1,
				'query_args' => array(
					'post_type'      => array( 'munimji_client' ),
					'post_status'    => array( 'publish' ),
					'posts_per_page' => -1,
				),
			)
		);

		$invoice_info->add_field(
			array(
				'name' => 'Date',
				'id'   => 'date',
				'type' => 'text_date',
			)
		);

		/**
		 * Registers tax items.
		 */
		$args = array(
			'id'           => 'munimji_invoice_items',
			'title'        => 'Invoice Items',
			'object_types' => array( 'munimji_invoice' ),
			'option_key'   => 'munimji-invoice-items',
		);

		$invoice_items = new_cmb2_box( $args );

		/**
		 * Options fields ids only need
		 * to be unique within this box.
		 * Prefix is not needed.
		 */

		// Invoice Items.
		$invoice_item = $invoice_items->add_field(
			array(
				'id'         => 'invoice_item',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => array(
					'group_title'    => __( 'Invoice item {#}', 'munimji' ),
					'add_button'     => __( 'Add Item', 'munimji' ),
					'remove_button'  => __( 'Remove Item', 'munimji' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munimji' ),
				),
			)
		);

		$invoice_items->add_group_field(
			$invoice_item,
			array(
				'name' => 'Service',
				'id'   => 'service',
				'type' => 'text',
			)
		);

		$invoice_items->add_group_field(
			$invoice_item,
			array(
				'name'         => 'Amount',
				'id'           => 'amount',
				'type'         => 'text_money',
				'before_field' => '₹',
			)
		);

		// Invoice Taxes.
		/**
		 * Registers tax items.
		 */
		$args = array(
			'id'           => 'munimji_invoice_taxes',
			'title'        => 'Invoice Taxes',
			'object_types' => array( 'munimji_invoice' ),
			'option_key'   => 'munimji-invoice-taxes',
		);

		$invoice_taxes = new_cmb2_box( $args );

		$invoice_tax = $invoice_taxes->add_field(
			array(
				'id'         => 'invoice_tax',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => array(
					'group_title'    => __( 'Invoice tax {#}', 'munimji' ),
					'add_button'     => __( 'Add Tax', 'munimji' ),
					'remove_button'  => __( 'Remove Tax', 'munimji' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munimji' ),
				),
			)
		);

		$invoice_taxes->add_group_field(
			$invoice_tax,
			array(
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			)
		);

		$invoice_taxes->add_group_field(
			$invoice_tax,
			array(
				'name'         => 'Rate',
				'id'           => 'rate',
				'type'         => 'text_money',
				'before_field' => '%',
			)
		);
	}

	/**
	 * Generate invoice number
	 *
	 * @return string invoice number
	 */
	public static function get_number() {
		$settings = get_option( 'munimji-settings', array() );
		$number   = ! empty( $settings ) && isset( $settings['last_invoice_number'] ) ? $settings['last_invoice_number'] + 1 : '0001';

		return $number;
	}

	/**
	 * Update last invoice number
	 *
	 * @param  int     $post_id post id.
	 * @param  WP_Post $post post object.
	 * @param  bool    $update $updated_settings.
	 * @return array   settings with updated last invoice number
	 */
	public static function update_number( $post_id, $post, $update ) {
		// Bail out if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bailout if its revision.
		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		// Bailout if post type is not munimji_invoice.
		if ( 'munimji_invoice' !== $post->post_type ) {
			return;
		}

		// Bailout if post status is auto-draft.
		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		// Update invoice number.
		$settings            = get_option( 'munimji-settings', array() );
		$invoice_number      = $settings['last_invoice_number'] + 1;
		$last_invoice_number = array( 'last_invoice_number' => $invoice_number );
		$updated_settings    = wp_parse_args( $last_invoice_number, $settings );

		update_option( 'munimji-settings', $updated_settings );
	}
}
