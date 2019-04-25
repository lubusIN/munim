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

use Dompdf\Dompdf;
use LubusIN\Munimji\Helpers;

/**
 * Munimji Invoices
 */
class Invoices {

	/**
	 * Prefix for custom meta fields.
	 *
	 * @var string
	 */
	private static $meta_prefix = 'munimji_invoice_';

	/**
	 * Init invoice
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ], 0 );
		add_action( 'init', [ __CLASS__, 'register_status' ] );
		add_action( 'admin_footer-edit.php', [ __CLASS__, 'render_status_in_quick_edit' ] );
		add_action( 'admin_footer-post.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post-new.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'post_row_actions', [ __CLASS__, 'render_row_actions' ], 10, 2 );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_cmb' ] );
		add_action( 'wp_insert_post', [ __CLASS__, 'update_number' ], 10, 3 );
		add_action( 'admin_init', [ __CLASS__, 'generate_pdf' ] );
	}

	/**
	 * Register custom posttype
	 *
	 * @return void
	 */
	public static function register_cpt() {
		$labels = [
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
		];
		$args   = array(
			'label'               => __( 'Invoice', 'munimji' ),
			'description'         => __( 'Munimji Invoices', 'munimji' ),
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
		);
		register_post_type( 'munimji_invoice', $args );
	}

	/**
	 * Register custom status
	 *
	 * @return void
	 */
	public static function register_status() {
		// Outstanding.
		$args = [
			'label'                     => _x( 'outstanding', 'Outstanding Invoices', 'munimji' ),
			/* translators: Outstanding invoices count */
			'label_count'               => _n_noop( 'outstanding (%s)', 'outstanding (%s)', 'munimji' ),
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'outstanding', $args );

		// Paid.
		$args = [
			'label'                     => _x( 'paid', 'Paid Invoices', 'munimji' ),
			/* translators: Paid invoices count */
			'label_count'               => _n_noop( 'paid (%s)', 'paid (%s)', 'munimji' ),
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'paid', $args );

		// Cancelled.
		$args = [
			'label'                     => _x( 'cancelled', 'Cancelled Invoices', 'munimji' ),
			/* translators: Cancelled invoices count */
			'label_count'               => _n_noop( 'cancelled (%s)', 'cancelled (%s)', 'munimji' ),
			'public'                    => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'cancelled', $args );
	}

	/**
	 * Render status in quick edit screen.
	 *
	 * @return void
	 */
	public static function render_status_in_quick_edit() {
		// Bailout if not invoices.
		if ( 'munimji_invoice' !== get_post_type() ) {
			return;
		}

		echo "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"_status\"]' )
						.append( '<option value=\"outstanding\">Outstanding</option>' )
						.append( '<option value=\"paid\">Paid</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' );
					});
			 </script>";
	}

	/**
	 * Render status in add/edit screen.
	 *
	 * @return void
	 */
	public static function render_status_in_edit() {
		// Bailout if not invoices.
		if ( 'munimji_invoice' !== get_post_type() ) {
			return;
		}

		echo "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"]' )
						.append( '<option value=\"outstanding\">Outstanding</option>' )
						.append( '<option value=\"paid\">Paid</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' );
				});
			 </script>";
	}

	/**
	 * Add custom row actions
	 *
	 * @param  array   $actions exisiting actions.
	 * @param  WP_Post $post post object.
	 * @return array
	 */
	public static function render_row_actions( $actions, $post ) {
		if ( 'munimji_invoice' === $post->post_type ) {
			unset( $actions['view'] ); // Remove post preview.

			// Action to view pdf.
			$view_url = add_query_arg(
				[
					'munimji_action'     => 'view',
					'munimji_invoice_id' => $post->ID,
					'nonce'              => wp_create_nonce( 'view' ),
				]
			);

			// Action to download pdf.
			$download_url = add_query_arg(
				[
					'munimji_action'     => 'download',
					'munimji_invoice_id' => $post->ID,
					'nonce'              => wp_create_nonce( 'download' ),
				]
			);

			$actions['view']     = '<a href="' . $view_url . '" target="_blank">View</a>';
			$actions['download'] = '<a href="' . $download_url . '">download</a>';
		}

		return $actions;
	}

	/**
	 * Register custom meta boxes
	 *
	 * @return void
	 */
	public static function register_cmb() {

		// Register CMB2 for invoice info.
		$args = [
			'id'           => self::$meta_prefix . 'details',
			'title'        => 'Details',
			'object_types' => [ 'munimji_invoice' ],
		];

		$invoice_details = new_cmb2_box( $args );

		// Custom fields for invoice.
		$invoice_details->add_field(
			[
				'name'       => 'Number',
				'id'         => self::$meta_prefix . 'number',
				'type'       => 'text_small',
				'default_cb' => [ __CLASS__, 'get_number' ],
				'attributes' => array(
					'readonly' => 'readonly',
				),
			]
		);

		$invoice_details->add_field(
			[
				'name'       => __( 'Client', 'munimji' ),
				'id'         => self::$meta_prefix . 'client_id',
				'type'       => 'post_search_ajax',
				'desc'       => __( '(Start typing client name)', 'munimji' ),
				'limit'      => 1,
				'query_args' => [
					'post_type'      => [ 'munimji_client' ],
					'post_status'    => [ 'publish' ],
					'posts_per_page' => -1,
				],
			]
		);

		$invoice_details->add_field(
			[
				'name' => 'Date',
				'id'   => self::$meta_prefix . 'date',
				'type' => 'text_date',
			]
		);

		// Register CMB2 for invoice items.
		$args = [
			'id'           => self::$meta_prefix . 'items',
			'title'        => 'Items',
			'object_types' => [ 'munimji_invoice' ],
		];

		$invoice_items = new_cmb2_box( $args );

		// Custom fields for items.
		$invoice_item = $invoice_items->add_field(
			[
				'id'         => self::$meta_prefix . 'items',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Item {#}', 'munimji' ),
					'add_button'     => __( 'Add Item', 'munimji' ),
					'remove_button'  => __( 'Remove Item', 'munimji' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munimji' ),
				],
			]
		);

		$invoice_items->add_group_field(
			$invoice_item,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$invoice_items->add_group_field(
			$invoice_item,
			[
				'name'         => 'Amount',
				'id'           => 'amount',
				'type'         => 'text_small',
				'before_field' => '₹',
			]
		);

		// Register CMB2 for invoice taxes.
		$args = [
			'id'           => self::$meta_prefix . 'taxes',
			'title'        => 'Taxes',
			'object_types' => [ 'munimji_invoice' ],
		];

		$invoice_taxes = new_cmb2_box( $args );

		// Custom fields for tax.
		$invoice_tax = $invoice_taxes->add_field(
			[
				'id'         => self::$meta_prefix . 'taxes',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Tax {#}', 'munimji' ),
					'add_button'     => __( 'Add Tax', 'munimji' ),
					'remove_button'  => __( 'Remove Tax', 'munimji' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munimji' ),
				],
			]
		);

		$invoice_taxes->add_group_field(
			$invoice_tax,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$invoice_taxes->add_group_field(
			$invoice_tax,
			[
				'name'         => 'Rate',
				'id'           => 'rate',
				'type'         => 'text_small',
				'before_field' => '%',
			]
		);
	}

	/**
	 * Generate invoice number
	 *
	 * @return string invoice number
	 */
	public static function get_number() {
		$settings = get_option( 'munimji_settings_invoice', array() );
		$number   = ! empty( $settings ) && isset( $settings['last_number'] ) ? ++$settings['last_number'] : 1;

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
		$settings            = get_option( 'munimji_settings_invoice', [] );
		$invoice_number      = $settings['last_number'];
		$last_invoice_number = [ 'last_number' => ++$invoice_number ];
		$updated_settings    = wp_parse_args( $last_invoice_number, $settings );

		update_option( 'munimji_settings_invoice', $updated_settings );
	}

	/**
	 * Check if pdf requested
	 *
	 * @return boolean
	 */
	public static function is_pdf_request() {
		return ( isset( $_GET['munimji_invoice_id'], $_GET['munimji_action'], $_GET['nonce'] ) );
	}

	/**
	 * Generate pdf.
	 *
	 * @return void
	 */
	public static function generate_pdf() {
		if ( ! self::is_pdf_request() ) {
			return;
		}

		// sanitize data and verify nonce.
		$invoice_id = sanitize_key( $_GET['munimji_invoice_id'] );
		$action     = sanitize_key( $_GET['munimji_action'] );
		$nonce      = sanitize_key( $_GET['nonce'] );

		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			wp_die( 'Invalid request.' );
		}

		// Get HTML.
		ob_start();
		include MUNIMJI_PLUGIN_DIR . 'templates/lubus/invoice.php';
		$html = ob_get_contents();
		ob_end_clean();

		// Generate pdf.
		$dompdf = new DOMPDF();
		$dompdf->loadHtml( $html );
		$dompdf->setPaper( 'A4', 'portrait' );
		$dompdf->setBasePath( MUNIMJI_PLUGIN_DIR . '/templates/lubus' );
		$dompdf->render();
		$dompdf->stream(
			Helpers::get_file_name( $invoice_id ),
			[
				'compress'   => false,
				'Attachment' => ( 'download' === $action ),
			]
		);
	}
}
