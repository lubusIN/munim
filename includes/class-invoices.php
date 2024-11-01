<?php
/**
 * Invoices
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

use Dompdf\Dompdf;
use LubusIN\Munim\Helpers;

/**
 * Munim Invoices
 */
class Invoices {

	/**
	 * Prefix for custom meta fields.
	 *
	 * @var string
	 */
	private static $meta_prefix = 'munim_invoice_';

	/**
	 * Init invoice
	 *
	 * @return void
	 */
	public static function init() {
		// Register CPT, CMB and renaming words.
		add_action( 'init', [ __CLASS__, 'register_cpt' ], 0 );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_cmb' ] );
		add_filter( 'gettext', [ __CLASS__, 'rename_text' ] );
		add_filter( 'ngettext', [ __CLASS__, 'rename_text' ] );

		// Admin columns.
		add_filter( 'manage_munim_invoice_posts_columns', [ __CLASS__, 'admin_columns' ] );
		add_action( 'manage_munim_invoice_posts_custom_column', [ __CLASS__, 'admin_columns_render' ], 10, 2 );

		// Status.
		add_action( 'init', [ __CLASS__, 'register_status' ] );
		add_action( 'admin_footer-edit.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post-new.php', [ __CLASS__, 'render_status_in_edit' ] );

		// Edit screen info / actions.
		add_action( 'add_meta_boxes', [ __CLASS__, 'info_box' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'actions_box' ] );
		add_filter( 'preview_post_link', [ __CLASS__, 'preview_invoice_link' ], 10, 2 );

		// Processing data.
		add_action( 'save_post_munim_invoice', [ __CLASS__, 'update_number' ], 10, 3 );
		add_action( 'wp_insert_post', [ __CLASS__, 'update_totals' ], 10, 3 );

		// Process action.
		add_action( 'admin_init', [ __CLASS__, 'generate_pdf' ] );
		add_action( 'admin_init', [ __CLASS__, 'generate_zip' ] );
		add_action( 'admin_init', [ __CLASS__, 'send_email' ] );

		// Row actions.
		add_action( 'post_row_actions', [ __CLASS__, 'render_row_actions' ], 10, 2 );

		// Schedule events.
		add_action( 'munim_update_status', [ __CLASS__, 'munim_update_status' ] );
		add_action( 'wp', [ __CLASS__, 'munim_schedule_status_update' ] );
	}

	/**
	 * Register custom posttype
	 *
	 * @return void
	 */
	public static function register_cpt() {
		$labels = [
			'name'                  => _x( 'Invoices', 'Post Type General Name', 'munim' ),
			'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'munim' ),
			'menu_name'             => __( 'Invoices', 'munim' ),
			'name_admin_bar'        => __( 'Invoices', 'munim' ),
			'archives'              => __( 'Item Archives', 'munim' ),
			'attributes'            => __( 'Item Attributes', 'munim' ),
			'parent_item_colon'     => __( 'Parent Item:', 'munim' ),
			'all_items'             => __( 'Invoices', 'munim' ),
			'add_new_item'          => __( 'Add New Invoice', 'munim' ),
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
		$args   = array(
			'label'               => __( 'Invoice', 'munim' ),
			'description'         => __( 'Munim Invoices', 'munim' ),
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
		);
		register_post_type( 'munim_invoice', $args );
	}

	/**
	 * Rename text
	 *
	 * @param string $translated translated text.
	 * @return string
	 */
	public static function rename_text( $translated ) {
		// Bailout if not invoices.
		if ( 'munim_invoice' !== get_post_type() ) {
			return $translated;
		}

		$words = array(
			'Published' => 'Issued',
			'Publish'   => 'Issue',
		);

		$translated = str_ireplace( array_keys( $words ), $words, $translated );
		return $translated;
	}

	/**
	 * Admin list columns.
	 *
	 * @param array $columns columns for admin list.
	 * @return array
	 */
	public static function admin_columns( $columns ) {
		$columns = [
			'cb'                   => $columns['cb'],
			'title'                => __( 'Title', 'munim' ),
			'munim_invoice_date'   => __( 'Invoice Date', 'munim' ),
			'munim_invoice_amount' => __( 'Amount', 'munim' ),
			'munim_invoice_status' => __( 'Status', 'munim' ),
			'date'                 => __( 'Date', 'munim' ),
		];

		return $columns;
	}

	/**
	 * Render admin column.
	 *
	 * @param string $column column name.
	 * @param int    $post_id post id.
	 * @return void
	 */
	public static function admin_columns_render( $column, $post_id ) {
		switch ( $column ) {
			case 'munim_invoice_amount':
				if ( ! empty( get_post_meta( $post_id, 'munim_invoice_total', true ) ) ) {
					$amount = number_format( get_post_meta( $post_id, 'munim_invoice_total', true ) );
				} else {
					$amount = 0;
				}
				$currency_symbol = get_munim_currency_symbol();
				$render_amount   = sprintf( '%s %s', $currency_symbol, $amount );

				echo esc_html( $render_amount );
				break;

			case 'munim_invoice_status':
					$html          = '<span class="%s tw-inline-block tw-rounded-full tw-px-2 tw-text-center tw-text-xs tw-font-medium">%s</span>';
					$status        = Helpers::get_invoice_status( get_post_status( $post_id ) );
					$classes       = Helpers::get_status_classes( $status );
					$render_column = sprintf( $html, $classes, $status );

					// phpcs:ignore
					echo $render_column;
				break;

			default:
				break;
		}
	}

	/**
	 * Register custom status
	 *
	 * @return void
	 */
	public static function register_status() {
		// Outstanding.
		$args = [
			'label'                     => _x( 'Outstanding', 'Outstanding Invoices', 'munim' ),
			/* translators: Outstanding invoices count */
			'label_count'               => _n_noop( 'Outstanding <span class="count">(%s)</span>', 'outstanding <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'outstanding', $args );

		// Paid.
		$args = [
			'label'                     => _x( 'Paid', 'Paid Invoices', 'munim' ),
			/* translators: Paid invoices count */
			'label_count'               => _n_noop( 'Paid <span class="count">(%s)</span>', 'Paid <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'paid', $args );

		// Partial.
		$args = [
			'label'                     => _x( 'Partial', 'Partial Invoices', 'munim' ),
			/* translators: Partial invoices count */
			'label_count'               => _n_noop( 'Partial <span class="count">(%s)</span>', 'Partial <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'partial', $args );

		// Cancelled.
		$args = [
			'label'                     => _x( 'Cancelled', 'Cancelled Invoices', 'munim' ),
			/* translators: Cancelled invoices count */
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'cancelled', $args );

		// Overdue.
		$args = [
			'label'                     => _x( 'Overdue', 'Overdue Invoices', 'munim' ),
			/* translators: Overdue invoices count */
			'label_count'               => _n_noop( 'Overdue <span class="count">(%s)</span>', 'Overdue <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'overdue', $args );
	}

	/**
	 * Render status in add/edit screen.
	 *
	 * @return void
	 */
	public static function render_status_in_edit() {
		// Bailout if not invoices.
		if ( 'munim_invoice' !== get_post_type() ) {
			return;
		}

		$invoice_status_slug  = get_post_status();
		$invoice_status_label = get_post_status_object( $invoice_status_slug )->label;

		$script = "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"], select[name=\"_status\"]' )
						.append( '<option value=\"outstanding\">Outstanding</option>' )
						.append( '<option value=\"paid\">Paid</option>' )
						.append( '<option value=\"partial\">Partial</option>' )
						.append( '<option value=\"overdue\">Overdue</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' )
						.val('%1\$s');
				});
				jQuery( '#post-status-display' ).text( '%2\$s' );
			</script>";

		// phpcs:ignore
		echo sprintf( $script, $invoice_status_slug, $invoice_status_label );
	}

	/**
	 * Add custom row actions
	 *
	 * @param  array   $actions exisiting actions.
	 * @param  WP_Post $post post object.
	 * @return array
	 */
	public static function render_row_actions( $actions, $post ) {
		if ( 'munim_invoice' === $post->post_type ) {
			$actions['view']     = sprintf( '<a href="%s" target="_blank">%s</a>', self::get_url( 'view' ), __( 'View', 'munim' ) );
			$actions['download'] = sprintf( '<a href="%s">%s</a>', self::get_url( 'download' ), __( 'Download', 'munim' ) );
		}

		return $actions;
	}

	/**
	 * Invoice preview link
	 *
	 * @param  string  $link preview link.
	 * @param  WP_Post $post current post object.
	 * @return string
	 */
	public static function preview_invoice_link( $link, $post ) {
		if ( 'munim_invoice' === $post->post_type ) {
			return self::get_url( 'view', admin_url( 'edit.php' ) );
		} else {
			return $link;
		}
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
			'object_types' => [ 'munim_invoice' ],
		];

		$invoice_details = new_cmb2_box( $args );

		// Custom fields for invoice.
		$invoice_details->add_field(
			[
				'name'       => 'Number',
				'id'         => self::$meta_prefix . 'number',
				'type'       => 'text_small',
				'default_cb' => [ __CLASS__, 'get_number' ],
				// 'attributes' => array(
				// 	'readonly' => 'readonly',
				// ),
				'column'     => [
					'position' => 1,
					'name'     => 'Invoice No',
				],
			]
		);

		$invoice_details->add_field(
			[
				'name'       => 'PO Number',
				'id'         => self::$meta_prefix . 'po_number',
				'type'       => 'text_small',
				'column'     => [
					'name'     => 'PO No',
				],
			]
		);

		$invoice_details->add_field(
			[
				'name'       => __( 'Client', 'munim' ),
				'id'         => self::$meta_prefix . 'client_id',
				'type'       => 'post_search_ajax',
				'desc'       => __( '(Start typing client name)', 'munim' ),
				'limit'      => 1,
				'query_args' => [
					'post_type'      => [ 'munim_client' ],
					'post_status'    => [ 'publish' ],
					'posts_per_page' => -1,
				],
			]
		);

		$munim_settings_invoice = get_option( 'munim_settings_invoice', [] );

		$invoice_details->add_field(
			[
				'name'        => 'Date',
				'id'          => self::$meta_prefix . 'date',
				'type'        => 'text_date_timestamp',
				'date_format' => isset( $munim_settings_invoice['date_format'] ) ? $munim_settings_invoice['date_format'] : 'd/m/Y',
				'column'      => [
					'position' => 2,
					'name'     => 'Invoice Date',
				],
			]
		);

		$invoice_details->add_field(
			[
				'name' => 'TDS',
				'id'   => self::$meta_prefix . 'tds',
				'type' => 'checkbox',
			]
		);

		$invoice_details->add_field(
			[
				'name'        => 'TDS Value',
				'id'          => self::$meta_prefix . 'tds_percent',
				'type'        => 'text_small',
				'after_field' => '%',
				'default_cb'  => [ __CLASS__, 'get_tds' ],
			]
		);

		// Register CMB2 for invoice items.
		$args = [
			'id'           => self::$meta_prefix . 'items',
			'title'        => 'Items',
			'object_types' => [ 'munim_invoice' ],
		];

		$invoice_items = new_cmb2_box( $args );

		// Custom fields for items.
		$invoice_item = $invoice_items->add_field(
			[
				'id'         => self::$meta_prefix . 'items',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Item {#}', 'munim' ),
					'add_button'     => __( 'Add Item', 'munim' ),
					'remove_button'  => __( 'Remove Item', 'munim' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munim' ),
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
				'before_field' => get_munim_currency_symbol(),
			]
		);

		// Register CMB2 for invoice taxes.
		$args = [
			'id'           => self::$meta_prefix . 'taxes',
			'title'        => 'Taxes',
			'object_types' => [ 'munim_invoice' ],
		];

		$invoice_taxes = new_cmb2_box( $args );

		// Custom fields for tax.
		$invoice_tax = $invoice_taxes->add_field(
			[
				'id'         => self::$meta_prefix . 'taxes',
				'type'       => 'group',
				'repeatable' => true,
				'options'    => [
					'group_title'    => __( 'Tax {#}', 'munim' ),
					'add_button'     => __( 'Add Tax', 'munim' ),
					'remove_button'  => __( 'Remove Tax', 'munim' ),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'munim' ),
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
	 * Register metabox for invoice info.
	 *
	 * @return void
	 */
	public static function info_box() {
		global $hook_suffix;

		if ( 'post.php' !== $hook_suffix ) {
			return;
		}

		add_meta_box(
			'munim_invoice_info_box',
			'Quick Info',
			[ __CLASS__, 'render_info_box' ],
			'munim_invoice',
			'side'
		);
	}

	/**
	 * Render invoice info box.
	 *
	 * @return void
	 */
	public static function render_info_box() {
		include_once 'views/invoice/info.php';
	}

	/**
	 * Register metabox for invoice actions.
	 *
	 * @return void
	 */
	public static function actions_box() {
		global $hook_suffix;

		if ( 'post.php' !== $hook_suffix ) {
			return;
		}

		add_meta_box(
			'munim_invoice_actions_box',
			'Actions',
			[ __CLASS__, 'render_actions_box' ],
			'munim_invoice',
			'side'
		);
	}

	/**
	 * Render invoice actions box.
	 *
	 * @return void
	 */
	public static function render_actions_box() {
		include_once 'views/invoice/actions.php';
	}

	/**
	 * Generate invoice number
	 *
	 * @return string invoice number
	 */
	public static function get_number() {
		$settings = get_option( 'munim_settings_invoice', array() );
		$number   = ! empty( $settings ) && isset( $settings['last_number'] ) ? ++$settings['last_number'] : 1;

		return $number;
	}

	/**
	 * Generate TDS %
	 *
	 * @return string TDS %
	 */
	public static function get_tds() {
		$settings = get_option( 'munim_settings_invoice', array() );
		$tds      = ! empty( $settings ) && isset( $settings['tds'] ) ? $settings['tds'] : 10;

		return $tds;
	}

	/**
	 * Update last invoice number
	 *
	 * @param  int     $post_id post id.
	 * @param  WP_Post $post post object.
	 * @param  bool    $update $updated_settings.
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

		// Bailout if post status is auto-draft.
		$allowed_status = [ 'draft', 'publish' ];
		if ( ! in_array( $post->post_status, $allowed_status, true ) ) {
			return;
		}

		// Bailout if invoice number already exisit.
		if ( '' !== $post->munim_invoice_number ) {
			return;
		}

		// Update invoice number.
		$settings            = get_option( 'munim_settings_invoice', [] );
		$invoice_number      = $settings['last_number'];
		$last_invoice_number = [ 'last_number' => ++$invoice_number ];
		$updated_settings    = wp_parse_args( $last_invoice_number, $settings );

		update_option( 'munim_settings_invoice', $updated_settings );
	}

	/**
	 * Update last invoice number
	 *
	 * @param  int     $post_id post id.
	 * @param  WP_Post $post post object.
	 * @param  bool    $update $updated_settings.
	 * @return array   settings with updated last invoice number
	 */
	public static function update_totals( $post_id, $post, $update ) {
		// Bail out if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bailout if not invoices.
		if ( 'munim_invoice' !== get_post_type() ) {
			return;
		}

		// Bailout if its revision.
		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		// Bailout if post status is auto-draft.
		if ( 'auto-draft' === $post->post_status || 'trash' === $post->post_status ) {
			return;
		}

		// Update invoice currency.
		$client_info     = Helpers::array_shift( get_post_meta( $post->munim_invoice_client_id ) );
		$client_currency = $client_info['munim_client_currency'];
		update_post_meta( $post_id, 'munim_invoice_currency', $client_currency );

		// Update subtotal.
		$items_total = array_sum( wp_list_pluck( $post->munim_invoice_items, 'amount' ) );
		$items_total = Helpers::maybe_convert_amount( $items_total, $client_currency );
		update_post_meta( $post_id, 'munim_invoice_subtotal', $items_total );

		// Update taxes.
		if ( isset( $post->munim_invoice_taxes ) ) {
			$taxes_total = Helpers::get_tax_total( $post->munim_invoice_taxes, $items_total );
			$total       = $items_total + $taxes_total;

			update_post_meta( $post_id, 'munim_invoice_taxes_total', $taxes_total );
			update_post_meta( $post_id, 'munim_invoice_total', $total );
		} else {
			update_post_meta( $post_id, 'munim_invoice_taxes_total', '0' );
			update_post_meta( $post_id, 'munim_invoice_total', $items_total );
		}

		// Update tds amount.
		$amount = ( $items_total * $post->munim_invoice_tds_percent ) / 100;
		update_post_meta( $post_id, 'munim_invoice_tds_amount', $amount );
	}

	/**
	 * Generate pdf.
	 *
	 * @param  int    $invoice_id post id.
	 * @param  string $action invoice action (view/download/save).
	 * @param  string $nonce $updated_settings.
	 * @return void
	 */
	public static function generate_pdf( $invoice_id = 0, $action = 'view', $nonce = null ) {
		$actions = [ 'view', 'save', 'download' ];

		// Bailout.
		if ( ! isset( $_REQUEST['munim_action'], $_REQUEST['nonce']) ) {
			return;
		}

		$action = sanitize_key( 'zip' === $_REQUEST['munim_action'] ? 'save' : $_REQUEST['munim_action'] );
		$nonce  = sanitize_key( $_REQUEST['nonce'] );

		if ( ! in_array( $action, $actions, true ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $nonce, 'zip' === $_REQUEST['munim_action'] ? 'zip': $action ) ) {
			wp_die( 'Invalid invoice pdf request.' );
		}

		$invoice_id = sanitize_key( 'save' === $action ? $invoice_id : $_REQUEST['munim_invoice_id'] );

		if(!$invoice_id) {
			return;
		}

		// Get template.
		$munim_settings_template = get_option( 'munim_settings_template', [] );
		$munim_template_path     = MUNIM_PLUGIN_DIR . 'templates/' . $munim_settings_template['template'];

		// Get HTML.
		ob_start();
		include $munim_template_path . '/invoice.php';
		$html = ob_get_contents();
		$html = str_replace('https://lubus.in', '/var/www/htdocs', $html);
		ob_end_clean();

		// Debug Output
		global $_dompdf_warnings;
		$_dompdf_warnings = array();
		global $_dompdf_show_warnings;
		$_dompdf_show_warnings = false;

		// Generate pdf.
		$dompdf = new DOMPDF([
			'debugLayout'   => false,
			'isRemoteEnabled' => true,
			'chroot' => '/var/www/htdocs',
		]);

		$dompdf->loadHtml( $html );
		$dompdf->setPaper( 'A4', 'portrait' );
		$dompdf->setBasePath( $munim_template_path );
		$dompdf->render();

		if ( 'save' === $action ) {
			// phpcs:ignore
			file_put_contents(
				MUNIM_PLUGIN_UPLOAD . Helpers::get_file_name( $invoice_id, 'invoice' ),
				$dompdf->output()
			); // Save pdf
		} else {
			// Show Debug Log
			if($_dompdf_show_warnings) {
				header('Content-type: text/plain');
				var_dump($_dompdf_warnings);
				die();
			}

			// View or download pdf.
			$dompdf->stream(
				Helpers::get_file_name( $invoice_id, 'invoice' ),
				[
					'compress'   => true,
					'Attachment' => ( 'download' === $action ),
				]
			);
			exit();
		}
	}

	/**
	 * Get url.
	 *
	 * @param string $action name of url action.
	 * @return string
	 */
	public static function get_url( $action, $id = null , $url = null ) {
		global $post;
		$url = add_query_arg(
			[
				'munim_action'     => $action,
				'munim_invoice_id' => $id ?? $post->ID,
				'nonce'            => wp_create_nonce( $action ),
			],
			$url
		);
		return $url;
	}

	/**
	 * Generate zip arvhive with pdf invoices for previous month
	 *
	 * @return void
	 */
	public static function generate_zip() {
		// Bailout.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( $_POST['munim_action'] ) ) {
			return;
		}

		if ( 'zip' !== $_POST['munim_action'] ) {
			return;
		}

		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'zip' ) ) {
			wp_die( 'Invalid invoice zip request' );
		}

		ignore_user_abort( true );

		// Get invoices for previous month.
		$invoice_args = [
			'posts_per_page' => '-1',
			'post_type'      => 'munim_invoice',
			'potst_status'   => [
				'publish',
				'paid',
				'partial',
			],
			// phpcs:ignore
			'meta_query'     => [
				[
					'key'     => 'munim_invoice_date',
					'compare' => 'BETWEEN',
					'value'   => [
						strtotime( 'last day of -2 months', time() ),
						strtotime( 'last day of previous month', time() ),
					],
					'type'    => 'numeric',
				],
			],
		];

		$invoice_query = new \WP_Query( $invoice_args );
		$invoices      = $invoice_query->get_posts();

		// Generate pdf.
		foreach ( $invoices as $invoice ) {
			self::generate_pdf( $invoice->ID, 'save', $_POST['nonce'] );
		}

		// Generate zip.
		$invoice_month = strtolower ( Date( 'F-Y', strtotime( 'last month' ) ) );
		$zip_file      = 'munim-' . $invoice_month . '.zip';
		$root_path     = MUNIM_PLUGIN_UPLOAD;
		$zip_path      = $root_path . $zip_file;

		$zip = new \ZipArchive();
		$zip->open( $zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE );

		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $root_path ),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ( $files as $name => $file ) {
			if ( ! $file->isDir() ) {
				$file_path     = $file->getRealPath();
				$relative_path = substr( $file_path, strlen( $root_path ) );
				$zip->addFile( $file_path, $relative_path );
			}
		}

		$zip->close();

		// Download.
		if ( file_exists( $zip_path ) ) {
			header( 'Content-Type: application/zip' );
			header( 'Content-Disposition: attachment; filename="' . basename( $zip_path ) . '"' );
			header( 'Content-Length: ' . filesize( $zip_path ) );

			flush();
			// phpcs:ignore
			readfile( $zip_path );

			// Clean up all files and exit.
			array_map( 'unlink', glob( MUNIM_PLUGIN_UPLOAD . '*' ) );
			exit();
		} else {
			Helpers::add_admin_notice( 'error', 'Error generating invoice archive !' );
		}
	}

	/**
	 * Send email.
	 *
	 * @return void
	 */
	public static function send_email() {
		// Bailout.
		if ( ! isset( $_REQUEST['munim_action'], $_REQUEST['nonce'], $_REQUEST['munim_invoice_id'] ) ) {
			return;
		}

		$action = sanitize_key( $_REQUEST['munim_action'] );

		if ( 'email' !== $action ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'email' ) ) {
			wp_die( 'Invalid invoice email request' );
		}

		Helpers::add_admin_notice( 'success', 'Will trigger email request' );
	}

	/**
	 * Action hook for invoice status update.
	 *
	 * @return void
	 */
	public static function munim_update_status() {
		// Get all issued invoices.
		$args = array(
			'post_type'   => 'munim_invoice',
			'post_status' => 'publish',
			'numberposts' => -1,
		);

		$invoices = get_posts( $args );

		if ( $invoices ) {
			// Get credit period.
			$munim_settings_invoice = get_option( 'munim_settings_invoice', [] );
			$credit_peroid          = trim( $munim_settings_invoice['credit'] );

			// Update status.
			foreach ( $invoices as $invoice ) {
				$invoice_date = $invoice->munim_invoice_date;
				// Check if today is more then invoice date + 15 days credit.
				if ( time() > strtotime( sprintf( '+%s days', $credit_peroid ), $invoice_date ) ) {
					$post = array(
						'ID'          => $invoice->ID,
						'post_status' => 'overdue',
					);
					wp_update_post( $post );
				}
			}
		}
	}

	/**
	 * Schedule event to updating invoice status.
	 *
	 * @return void
	 */
	public static function munim_schedule_status_update() {
		if ( ! wp_next_scheduled( 'munim_update_status' ) ) {
			wp_schedule_event( time(), 'daily', 'munim_update_status' );
		}
	}
}
