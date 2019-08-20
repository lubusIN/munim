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
		add_action( 'init', [ __CLASS__, 'register_cpt' ], 0 );
		add_filter( 'gettext', [ __CLASS__, 'rename_text'] );
		add_filter( 'ngettext', [ __CLASS__, 'rename_text'] );
		add_action( 'init', [ __CLASS__, 'register_status' ] );
		add_action( 'admin_footer-edit.php', [ __CLASS__, 'render_status_in_quick_edit' ] );
		add_action( 'admin_footer-post.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post-new.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'post_row_actions', [ __CLASS__, 'render_row_actions' ], 10, 2 );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'register_cmb' ] );
		add_action( 'save_post_munim_invoice', [ __CLASS__, 'update_number' ], 10, 3 );
		add_action( 'wp_insert_post', [ __CLASS__, 'update_totals' ], 10, 3 );
		add_action( 'admin_init', [ __CLASS__, 'generate_pdf' ] );
		add_action( 'admin_init', [ __CLASS__, 'generate_zip' ] );
		add_action( 'post_submitbox_misc_actions', [ __CLASS__, 'add_pdf_actions' ] );
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
			'show_in_menu'        => 'admin.php?page=munim',
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
	 * @param string $translated
	 * @return void
	 */
	public static function rename_text( $translated ) {
		// Bailout if not invoices.
		if ( 'munim_invoice' !== get_post_type() ) {
			return $translated;
		}

		$words = array(
			// 'word to translate' = > 'translation'
			'Published' => 'Issued',
			'Publish'   => 'Issue',
		);

		$translated = str_ireplace(  array_keys($words),  $words,  $translated );
		return $translated;
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
	}

	/**
	 * Render status in quick edit screen.
	 *
	 * @return void
	 */
	public static function render_status_in_quick_edit() {
		// Bailout if not invoices.
		if ( 'munim_invoice' !== get_post_type() ) {
			return;
		}

		$invoice_status_slug = get_post_status();

		$script = "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"_status\"]' )
						.append( '<option value=\"outstanding\">Outstanding</option>' )
						.append( '<option value=\"paid\">Paid</option>' )
						.append( '<option value=\"partial\">Partial</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' )
						.val('%1\$s');
					});
			</script>";

			echo sprintf( $script, $invoice_status_slug);
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

		$invoice_status_slug = get_post_status();
		$invoice_status_label = get_post_status_object( $invoice_status_slug )->label;

		$script = "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"]' )
						.append( '<option value=\"outstanding\">Outstanding</option>' )
						.append( '<option value=\"paid\">Paid</option>' )
						.append( '<option value=\"partial\">Partial</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' )
						.val('%1\$s');
				});
				jQuery( '#post-status-display' ).text( '%2\$s' );
			</script>";

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
			unset( $actions['view'] ); // Remove post preview.

			$actions['view']     = '<a href="' . self::get_view_url() . '" target="_blank">View</a>';
			$actions['download'] = '<a href="' . self::get_download_url() . '">Download</a>';
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
				'attributes' => array(
					'readonly' => 'readonly',
				),
				'column'     => [
					'position' => 1,
					'name'     => 'Invoice No',
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
				'date_format' => isset( $munim_settings_invoice['date_format']) ? $munim_settings_invoice['date_format'] : 'd/m/Y',
				'column'     => [
					'position' => 3,
					'name'     => 'Invoice Date',
				],
			]
		);

		$invoice_details->add_field(
			[
				'name'        => 'TDS',
				'id'          => self::$meta_prefix . 'tds',
				'type'        => 'checkbox',
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
		$tds   = ! empty( $settings ) && isset( $settings['tds'] ) ? $settings['tds'] : 10;

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
		if ( 'auto-draft' === $post->post_status ) {
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
		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		// Update items and taxes total.
		$items_total = array_sum( wp_list_pluck( $post->munim_invoice_items, 'amount' ) );
		update_post_meta( $post_id, 'munim_invoice_subtotal', $items_total );

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
		// Bailout.
		if ( ! isset( $_REQUEST['munim_action'], $_REQUEST['nonce'] ) ) {
			return;
		}

		if ( 'save' !== $action && ! isset( $_REQUEST['munim_invoice_id'] ) ) {
			return;
		}

		// sanitize data and verify nonce.
		$action     = sanitize_key( 'zip' ===  $_REQUEST['munim_action'] ? $action :  $_REQUEST['munim_action']  );
		$nonce      = sanitize_key( $_REQUEST['nonce'] );
		$invoice_id = sanitize_key( 'save' === $action ? $invoice_id : $_REQUEST['munim_invoice_id'] );

		if ( 'save' !== $action && ! wp_verify_nonce( $nonce, $action ) ) {
			wp_die( 'Invalid request.' );
		}

		// Get template.
		$munim_settings_template = get_option( 'munim_settings_template', [] );
		$munim_template_path     = MUNIM_PLUGIN_DIR . 'templates/' . $munim_settings_template['template'];

		// Get HTML.
		ob_start();
		include $munim_template_path . '/invoice.php';
		$html = ob_get_contents();
		ob_end_clean();

		// Generate pdf.
		$dompdf = new DOMPDF();
		$dompdf->loadHtml( $html );
		$dompdf->setPaper( 'A4', 'portrait' );
		$dompdf->setBasePath( $munim_template_path );
		$dompdf->render();

		if ( 'save' === $action ) {
			file_put_contents( MUNIM_PLUGIN_UPLOAD . Helpers::get_file_name( $invoice_id ), $dompdf->output() ); // Save pdf
		} else {
			// View or download pdf
			$dompdf->stream(
				Helpers::get_file_name( $invoice_id ),
				[
					'compress'   => true,
					'Attachment' => ( 'download' === $action ),
				]
			);
			exit();
		}
	}

	/**
	 * PDF view url.
	 *
	 * @return array
	 */
	public static function get_view_url() {
		global $post;
		$view_url = add_query_arg(
			[
				'munim_action'     => 'view',
				'munim_invoice_id' => $post->ID,
				'nonce'            => wp_create_nonce( 'view' ),
			]
		);
		return $view_url;
	}

	/**
	 * PDF download url.
	 *
	 * @return array
	 */
	public static function get_download_url() {
		global $post;
		$download_url = add_query_arg(
			[
				'munim_action'     => 'download',
				'munim_invoice_id' => $post->ID,
				'nonce'            => wp_create_nonce( 'download' ),
			]
		);
		return $download_url;
	}

	/**
	 * Add pdf actions to publish metabox
	 *
	 * @param WP_Post $post invoice post object.
	 * @return void
	 */
	public static function add_pdf_actions( $post ) {
		if ( 'munim_invoice' === $post->post_type ) {
			echo '<div class="misc-pub-section misc-pub-section-pdf">';
			echo '<a href="' . self::get_view_url() . '" class="button button-secondary" target="_blank"><span class="dashicons dashicons-visibility" style="margin-top: 3px"></span> View</a>';
			echo '&nbsp;&nbsp;';
			echo '<a href="' . self::get_download_url() . '" class="button button-secondary"><span class="dashicons dashicons-download" style="margin-top: 3px"></span>Download</a>';
			echo '</div>';
		}
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

		if ( ! wp_verify_nonce( $_POST['nonce'], 'zip' ) ) {
			wp_die( 'invalid request' );
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
			'meta_query'      =>  [
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
			self::generate_pdf( $invoice->ID, 'save' );
		}

		// Generate zip.
		$invoice_month = Date( 'F-Y', strtotime( 'last month' ) );
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
			readfile( $zip_path );
		}

		// Clean up all files.
		array_map( 'unlink', glob( MUNIM_PLUGIN_UPLOAD . '*' ) );
	}
}
