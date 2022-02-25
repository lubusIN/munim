<?php
/**
 * Estimates
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
 * Munim Estimates
 */
class Estimates {
    /**
	 * Prefix for custom meta fields.
	 *
	 * @var string
	 */
	private static $meta_prefix = 'munim_estimate_';


	/**
	 * Init Estimate
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
		add_filter( 'manage_munim_estimate_posts_columns', [ __CLASS__, 'admin_columns' ] );
		add_action( 'manage_munim_estimate_posts_custom_column', [ __CLASS__, 'admin_columns_render' ], 10, 2 );


		// Status.
		add_action( 'init', [ __CLASS__, 'register_status' ] );
		add_action( 'admin_footer-edit.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post.php', [ __CLASS__, 'render_status_in_edit' ] );
		add_action( 'admin_footer-post-new.php', [ __CLASS__, 'render_status_in_edit' ] );

        // Processing data.
		add_action( 'save_post_munim_estimate', [ __CLASS__, 'update_number' ], 10, 3 );
		add_action( 'wp_insert_post', [ __CLASS__, 'update_totals' ], 10, 3 );

        // Edit screen info / actions.
		add_action( 'add_meta_boxes', [ __CLASS__, 'info_box' ] );
    }


	/**
	 * Register custom posttype
	 *
	 * @return void
	 */
	public static function register_cpt() {
		$labels = [
			'name'                  => _x( 'Estimates', 'Post Type General Name', 'munim' ),
			'singular_name'         => _x( 'Estimate', 'Post Type Singular Name', 'munim' ),
			'menu_name'             => __( 'Estimates', 'munim' ),
			'name_admin_bar'        => __( 'Estimates', 'munim' ),
			'archives'              => __( 'Item Archives', 'munim' ),
			'attributes'            => __( 'Item Attributes', 'munim' ),
			'parent_item_colon'     => __( 'Parent Item:', 'munim' ),
			'all_items'             => __( 'Estimates', 'munim' ),
			'add_new_item'          => __( 'Add New Estimate', 'munim' ),
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
			'label'               => __( 'Estimate', 'munim' ),
			'description'         => __( 'Munim Estimates', 'munim' ),
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
		register_post_type( 'munim_estimate', $args );
	}

    /**
	 * Register custom meta boxes
	 *
	 * @return void
	 */
	public static function register_cmb() {
		// Register CMB2 for estimate info.
		$args = [
			'id'           => self::$meta_prefix . 'details',
			'title'        => 'Details',
			'object_types' => [ 'munim_estimate' ],
		];

		$estimate_details = new_cmb2_box( $args );

		// Custom fields for estimate.
		$estimate_details->add_field(
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
					'name'     => 'Number',
				],
			]
		);

		$estimate_details->add_field(
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

		$munim_settings_estimate = get_option( 'munim_settings_estimate', [] );

		$estimate_details->add_field(
			[
				'name'        => 'Date',
				'id'          => self::$meta_prefix . 'date',
				'type'        => 'text_date_timestamp',
				'date_format' => isset( $munim_settings_estimate['date_format'] ) ? $munim_settings_estimate['date_format'] : 'd/m/Y',
				'column'      => [
					'position' => 3,
					'name'     => 'Estimate Date',
				],
			]
		);

		// Register CMB2 for estimate items.
		$args = [
			'id'           => self::$meta_prefix . 'items',
			'title'        => 'Items',
			'object_types' => [ 'munim_estimate' ],
		];

		$estimate_items = new_cmb2_box( $args );

		// Custom fields for items.
		$estimate_item = $estimate_items->add_field(
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

		$estimate_items->add_group_field(
			$estimate_item,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$estimate_items->add_group_field(
			$estimate_item,
			[
				'name'         => 'Amount',
				'id'           => 'amount',
				'type'         => 'text_small',
				'before_field' => get_munim_currency_symbol(),
			]
		);

		// Register CMB2 for estimate taxes.
		$args = [
			'id'           => self::$meta_prefix . 'taxes',
			'title'        => 'Taxes',
			'object_types' => [ 'munim_estimate' ],
		];

		$estimate_taxes = new_cmb2_box( $args );

		// Custom fields for tax.
		$estimate_tax = $estimate_taxes->add_field(
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

		$estimate_taxes->add_group_field(
			$estimate_tax,
			[
				'name' => 'Name',
				'id'   => 'name',
				'type' => 'text',
			]
		);

		$estimate_taxes->add_group_field(
			$estimate_tax,
			[
				'name'         => 'Rate',
				'id'           => 'rate',
				'type'         => 'text_small',
				'before_field' => '%',
			]
		);
	}

    /**
	 * Generate estimate number
	 *
	 * @return string estimate number
	 */
	public static function get_number() {
		$settings = get_option( 'munim_settings_estimate', array() );
		$number   = ! empty( $settings ) && isset( $settings['last_number'] ) ? ++$settings['last_number'] : 1;

		return $number;
	}


    /**
	 * Rename text
	 *
	 * @param string $translated translated text.
	 * @return string
	 */
	public static function rename_text( $translated ) {
		// Bailout if not estimates.
		if ( 'munim_estimate' !== get_post_type() ) {
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
			'munim_estimate_date'   => __( 'Estimate Date', 'munim' ),
			'munim_estimate_amount' => __( 'Amount', 'munim' ),
			'munim_estimate_status' => __( 'Status', 'munim' ),
			'date'                  => __( 'Date', 'munim' ),
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
			case 'munim_estimate_amount':
				if ( ! empty( get_post_meta( $post_id, 'munim_estimate_total', true ) ) ) {
					$amount = number_format( get_post_meta( $post_id, 'munim_estimate_total', true ) );
				} else {
					$amount = 0;
				}
				$currency_symbol = get_munim_currency_symbol();
				$render_amount   = sprintf( '%s %s', $currency_symbol, $amount );

				echo esc_html( $render_amount );
				break;

			case 'munim_estimate_status':
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
	 * Update last estimate number
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

		// Bailout if estimate number already exisit.
		if ( '' !== $post->munim_estimate_number ) {
			return;
		}

		// Update estimate number.
		$settings            = get_option( 'munim_settings_estimate', [] );
		$estimate_number      = $settings['last_number'];
		$last_estimate_number = [ 'last_number' => ++$estimate_number ];
		$updated_settings    = wp_parse_args( $last_estimate_number, $settings );

		update_option( 'munim_settings_estimate', $updated_settings );
	}

	/**
	 * Update last estimate number
	 *
	 * @param  int     $post_id post id.
	 * @param  WP_Post $post post object.
	 * @param  bool    $update $updated_settings.
	 * @return array   settings with updated last estimate number
	 */
	public static function update_totals( $post_id, $post, $update ) {
		// Bail out if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bailout if not estimates.
		if ( 'munim_estimate' !== get_post_type() ) {
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

		// Update estimate currency.
		$client_info     = Helpers::array_shift( get_post_meta( $post->munim_estimate_client_id ) );
		$client_currency = $client_info['munim_client_currency'];
		update_post_meta( $post_id, 'munim_estimate_currency', $client_currency );

		// Update subtotal.
		$items_total = array_sum( wp_list_pluck( $post->munim_estimate_items, 'amount' ) );
		$items_total = Helpers::maybe_convert_amount( $items_total, $client_currency );
		update_post_meta( $post_id, 'munim_estimate_subtotal', $items_total );

		// Update taxes.
		if ( isset( $post->munim_estimate_taxes ) ) {
			$taxes_total = Helpers::get_tax_total( $post->munim_estimate_taxes, $items_total );
			$total       = $items_total + $taxes_total;

			update_post_meta( $post_id, 'munim_estimate_taxes_total', $taxes_total );
			update_post_meta( $post_id, 'munim_estimate_total', $total );
		} else {
			update_post_meta( $post_id, 'munim_estimate_taxes_total', '0' );
			update_post_meta( $post_id, 'munim_estimate_total', $items_total );
		}
	}

    /**
	 * Register metabox for estimate info.
	 *
	 * @return void
	 */
	public static function info_box() {
		global $hook_suffix;

		if ( 'post.php' !== $hook_suffix ) {
			return;
		}

		add_meta_box(
			'munim_estimate_info_box',
			'Quick Info',
			[ __CLASS__, 'render_info_box' ],
			'munim_estimate',
			'side'
		);
	}

	/**
	 * Render estimate info box.
	 *
	 * @return void
	 */
	public static function render_info_box() {
		include_once 'views/estimate/info.php';
	}

    /**
	 * Register custom status
	 *
	 * @return void
	 */
	public static function register_status() {
        // Billed.
		$args = [
			'label'                     => _x( 'Billed', 'Billed estimates', 'munim' ),
			/* translators: Billed estimates count */
			'label_count'               => _n_noop( 'Billed <span class="count">(%s)</span>', 'Billed <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'billed', $args );

		// Invalid.
		$args = [
			'label'                     => _x( 'Invalid', 'Invalid estimates', 'munim' ),
			/* translators: Invalid estimates count */
			'label_count'               => _n_noop( 'Invalid <span class="count">(%s)</span>', 'Invalid <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'invalid', $args );


		// Cancelled.
		$args = [
			'label'                     => _x( 'Cancelled', 'Cancelled estimates', 'munim' ),
			/* translators: Cancelled estimates count */
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'munim' ),
			'public'                    => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'exclude_from_search'       => true,
		];
		register_post_status( 'cancelled', $args );
	}

	/**
	 * Render status in add/edit screen.
	 *
	 * @return void
	 */
	public static function render_status_in_edit() {
		// Bailout if not estimates.
		if ( 'munim_estimate' !== get_post_type() ) {
			return;
		}

		$estimate_status_slug  = get_post_status();
		$estimate_status_label = get_post_status_object( $estimate_status_slug )->label;

		$script = "<script>
				jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"], select[name=\"_status\"]' )
						.append( '<option value=\"billed\">Billed</option>' )
						.append( '<option value=\"invalid\">Invalid</option>' )
						.append( '<option value=\"cancelled\">Cancelled</option>' )
						.val('%1\$s');
				});
				jQuery( '#post-status-display' ).text( '%2\$s' );
			</script>";

		// phpcs:ignore
		echo sprintf( $script, $estimate_status_slug, $estimate_status_label );
	}

}