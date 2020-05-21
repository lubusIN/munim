<?php
/**
 * Helpers
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
 * Munim Helpers
 */
class Helpers {
	/**
	 * Array shift for nested items
	 *
	 * @param array $array data array.
	 * @return array
	 */
	public static function array_shift( $array ) {
		$shifted_array = array_map(
			function ( $item ) {
				return array_shift( $item );
			},
			$array
		);

		return $shifted_array;
	}

	/**
	 * Get total tax
	 *
	 * @param array $taxes taxes on invoice.
	 * @param   int   $subtotal gross amount before taxes.
	 * @return  int   sum
	 */
	public static function get_tax_total( $taxes, $subtotal ) {
		$tax_amount = [];

		foreach ( $taxes as $tax_item ) {
			$tax_amount[] = ( $tax_item['rate'] / 100 ) * $subtotal;
		}

		return array_sum( $tax_amount );
	}

	/**
	 * Get file name for pdf
	 *
	 * @param int $invoice_id invoice post id.
	 * @return string
	 */
	public static function get_file_name( $invoice_id ) {
		$invoice_number = get_post_meta( $invoice_id, 'munim_invoice_number', true );
		$invoice_slug   = get_post_field( 'post_name', $invoice_id );
		$pdf_filename   = $invoice_number . '.' . $invoice_slug . '.pdf';
		return $pdf_filename;
	}

	/**
	 * Get invoice status count
	 *
	 * @param string $status invoice status.
	 * @param string $period period for invoices (current, previous).
	 * @return int
	 */
	public static function get_invoice_status_count( $status = '', $period = 'current' ) {
		$count      = 0;
		$count_args = [
			'post_type' => 'munim_invoice',
		];

		if ( ! empty( $status ) ) {
			$count_args['post_status'] = $status;
		} else {
			$count_args['post_status'] = [
				'publish',
				'paid',
				'cancelled',
				'partial',
			];
		}

		if ( 'current' === $period ) {
			// phpcs:ignore
			$count_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => '>',
					'value'   => strtotime( 'last day of previous month', time() ),
					'type'    => 'numeric',
				],
			];
		}

		if ( 'previous' === $period ) {
			// phpcs:ignore
			$count_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => '<',
					'value'   => strtotime( 'first day of this month', time() ),
					'type'    => 'numeric',
				],
			];
		}

		$count_query = new \WP_Query( $count_args );
		$count       = $count_query->found_posts;

		return $count;
	}

	/**
	 * Get Recent Invoices
	 *
	 * @return array_object
	 */
	public static function get_recent_invoices() {
		$recent_args = [
			'posts_per_page' => '5',
			'post_type'      => 'munim_invoice',
		];

		$invoices = new \WP_Query( $recent_args );

		return $invoices->get_posts();
	}

	/**
	 * Get status for display
	 *
	 * @param string $status invoice status.
	 * @return string
	 */
	public static function get_invoice_status( $status ) {
		$status = 'publish' === $status ? 'issued' : $status;
		return $status;
	}

	/**
	 * Get css classes for status
	 *
	 * @param string $status invoice status.
	 * @return string
	 */
	public static function get_status_classes( $status ) {
		$classes = '';
		switch ( $status ) {
			case 'outstanding':
					$classes = 'tw-bg-red-100 tw-border-2 tw-border-red-200 tw-text-red-500';
				break;

			case 'paid':
					$classes = 'tw-bg-green-100 tw-border-2 tw-border-green-200 tw-text-green-500';
				break;

			case 'partial':
					$classes = 'tw-bg-orange-100 tw-border-2 tw-border-orange-200 tw-text-orange-500';
				break;

			case 'overdue':
					$classes = 'tw-bg-red-100 tw-border-2 tw-border-red-200 tw-text-red-500';
				break;

			case 'cancelled':
					$classes = 'tw-bg-gray-100 tw-border-2 tw-border-gray-200 tw-text-gray-500';
				break;

			default:
					$classes = 'tw-bg-indigo-100 tw-border-2 tw-border-indigo-200 tw-text-indigo-500';
				break;
		}

		return $classes;
	}

	/**
	 * Get data total
	 *
	 * @param string $data type of data to fetch (gross,net,taxes,tds).
	 * @param string $period for invoices (current, previous, financial).
	 * @param string $status invoice status.
	 * @return int total
	 */
	public static function get_total( $data, $period = 'current', $status = 'all' ) {
		$stat                 = 0;
		$financial_year_to    = ( date( 'm' ) > 3 ) ? date( 'y' ) + 1 : date( 'y' );
		$financial_year_from  = $financial_year_to - 1;
		$financial_start_date = date( $financial_year_from . '-04-01' );
		$financial_end_date   = date( $financial_year_to . '-03-31' );

		$stat_args = [
			'posts_per_page' => '-1',
			'post_type'      => 'munim_invoice',
		];

		if ( 'all' === $status ) {
			$stat_args['post_status'] = [
				'publish',
				'paid',
				'partial',
				'overdue',
			];
		} else {
			$stat_args['post_status'] = $status;
		}

		if ( 'current' === $period ) {
			// phpcs:ignore
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => '>',
					'value'   => strtotime( 'last day of previous month', time() ),
					'type'    => 'numeric',
				],
			];
		}

		if ( 'financial' === $period ) {
			// phpcs:ignore
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => 'BETWEEN',
					'value'   => [
						strtotime( $financial_start_date ),
						strtotime( $financial_end_date ),
					],
					'type'    => 'numeric',
				],
			];
		}

		if ( is_array( $period ) ) {
			// phpcs:ignore
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => 'BETWEEN',
					'value'   => [
						strtotime( $period['start_date'] ),
						strtotime( $period['end_date'] ),
					],
					'type'    => 'numeric',
				],
			];
		}

		if ( 'previous' === $period ) {
			// phpcs:ignore
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => 'BETWEEN',
					'value'   => [
						strtotime( 'last day of -2 months', time() ),
						strtotime( 'last day of previous month', time() ),
					],
					'type'    => 'numeric',
				],
			];
		}

		if ( 'tds' === $data ) {
			$stat_args['meta_query']['relation'] = 'AND';
			$stat_args['meta_query'][]           =
				[
					'key'     => 'munim_invoice_tds',
					'compare' => '=',
					'value'   => 'on',
				];
		}

		$stat_query    = new \WP_Query( $stat_args );
		$stat_invoices = $stat_query->get_posts();

		if ( $stat_invoices ) {
			foreach ( $stat_invoices as $invoice ) {
				switch ( $data ) {
					case 'gross':
							$stat += $invoice->munim_invoice_total;
						break;

					case 'net':
							$stat += $invoice->munim_invoice_subtotal;
						break;

					case 'taxes':
							$stat += $invoice->munim_invoice_taxes_total;
						break;
					case 'tds':
							$stat += $invoice->munim_invoice_tds_amount;
						break;

					default:
							$stat = 0;
						break;
				}
			}
		}

		return round( $stat );
	}

	/**
	 * Get monthly turnover
	 *
	 * @param string $data type of data to fetch (gross/net/taces/tds).
	 * @return array
	 */
	public static function get_monthly_trend( $data = 'net' ) {
		$financial_year_to   = ( date( 'm' ) > 3 ) ? date( 'Y' ) + 1 : date( 'Y' );
		$financial_year_from = $financial_year_to - 1;

		// Financial year months.
		$months = [
			'Apr' => sprintf( 'Apr %s', $financial_year_from ),
			'May' => sprintf( 'May %s', $financial_year_from ),
			'Jun' => sprintf( 'Jun %s', $financial_year_from ),
			'Jul' => sprintf( 'Jul %s', $financial_year_from ),
			'Aug' => sprintf( 'Aug %s', $financial_year_from ),
			'Sep' => sprintf( 'Sep %s', $financial_year_from ),
			'Oct' => sprintf( 'Oct %s', $financial_year_from ),
			'Nov' => sprintf( 'Nov %s', $financial_year_from ),
			'Dec' => sprintf( 'Dec %s', $financial_year_from ),
			'Jan' => sprintf( 'Jan %s', $financial_year_to ),
			'Feb' => sprintf( 'Feb %s', $financial_year_to ),
			'Mar' => sprintf( 'Mar %s', $financial_year_to ),
		];

		$trend_data = [];

		// Process data.
		foreach ( $months as $key => $value ) {

			// Get total (net/gross).
			$total = self::get_total(
				$data,
				[
					'start_date' => sprintf( 'first day of %s', $value ),
					'end_date'   => sprintf( 'last day of %s', $value ),
				]
			);

			// Subtract tds from net total if net requested.
			if ( 'net' === $data ) {
				$tds   = self::get_total(
					'tds',
					[
						'start_date' => sprintf( 'first day of %s', $value ),
						'end_date'   => sprintf( 'last day of %s', $value ),
					]
				);
				$total = $total - $tds;
			}

			$trend_data[ $key ] = $total;
		}

		return array_values( $trend_data );
	}

	/**
	 * Get net receipts
	 *
	 * @param string $period peroid for invoice.
	 * @return int
	 */
	public static function get_receipts( $period = 'current' ) {
		$total = self::get_total( 'gross', $period, 'paid' );
		$tds   = self::get_total( 'tds', $period );

		$receipts = $total > 0 ? $total - $tds : 0;

		return $receipts;
	}

	/**
	 * Add admin notices.
	 *
	 * @param string $type (error, warning, info, success).
	 * @param string $msg notice message.
	 * @return void
	 */
	public static function add_admin_notice( $type, $msg ) {
		// Notice message.
		$notice = '<div class="notice notice-%s">
					<p>%s</p>
				  </div>';

		// Add to admin.
		add_action(
			'admin_notices',
			function () use ( $notice, $type, $msg ) {
				// phpcs:ignore
				echo sprintf( $notice, $type, $msg );
			}
		);
	}


	/**
	 * Check if invoice is for international client.
	 *
	 * @param int $client_currency client currency code.
	 * @return boolean
	 */
	public static function is_international( $client_currency ) {

		$international = false;
		$international = get_munim_currency() !== $client_currency;

		return $international;
	}

	/**
	 * Get exchange rate
	 *
	 * @param string $from from currency.
	 * @param string $to to currency.
	 * @return int
	 */
	public static function get_exchange_rate( $from, $to ) {
		$rate = 0;

		// Return cached rates if available.
		$exchange_rates = get_transient( 'munim_exchange_rates_' . $from );

		if ( $exchange_rates ) {
			$rate = $exchange_rates->conversion_rates->$to;

			return $rate;
		}

		// Get API Key.
		$invoice_settings = get_option( 'munim_settings_invoice' );
		$api_key          = $invoice_settings['exchange_rate_api_key'];

		// Fetch and store live rates if not already cached.
		$request_uri      = "https://prime.exchangerate-api.com/v5/${api_key}/latest/${from}";
		$request_response = wp_remote_get( $request_uri );

		if ( ! is_wp_error( $request_response ) ) {
			$exchange_rates = json_decode( wp_remote_retrieve_body( $request_response ) );
			$rate           = $exchange_rates->conversion_rates->$to;

			set_transient( 'munim_exchange_rates_' . $from, $exchange_rates, DAY_IN_SECONDS );
		}

		return $rate;
	}

	/**
	 * Get exchange rate amount
	 *
	 * @param int    $amount amount to convert.
	 * @param string $from from currency.
	 * @param string $to to currency.
	 * @return int
	 */
	public static function get_exchange_rate_amount( $amount, $from, $to ) {
		$rate   = self::get_exchange_rate( $from, $to );
		$amount = $amount * $rate;

		return $amount;
	}

	/**
	 * Convert amount using exchange rates if international invoice
	 *
	 * @param int    $amount amount to convert.
	 * @param string $client_currency client currency code.
	 * @return int
	 */
	public static function maybe_convert_amount( $amount, $client_currency ) {
		$base_currency = get_munim_currency();

		if ( $base_currency !== $client_currency ) {
			$amount = self::get_exchange_rate_amount( $amount, $client_currency, $base_currency );
		}

		return $amount;
	}
}
