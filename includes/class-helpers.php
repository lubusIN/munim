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
	 * @param string $status
	 * @param string $period
	 * @return int
	 */
	public static function get_invoice_status_count( $status = '', $period = 'current' ) {
		$count = 0;
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
		$count = $count_query->found_posts;

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
			'post_type' => 'munim_invoice',
		];

		$invoices = new \WP_Query( $recent_args );

		return $invoices->get_posts();
	}

	/**
	 * Get status for display
	 *
	 * @param string $status
	 * @return string
	 */
	public static function get_invoice_status( $status ) {
		$status = 'publish' === $status ? 'issued' : $status;
		return $status;
	}

	/**
	 * Get css classes for status
	 *
	 * @param string $status
	 * @return string
	 */
	public static function get_status_classes( $status ){
		$classes = '';
		switch ( $status ) {
			case 'outstanding':
					$classes = 'bg-red-100 border-2 border-red-200 text-red-500';
				break;

			case 'paid':
					$classes = 'bg-green-100 border-2 border-green-200 text-green-500';
				break;

			case 'partial':
					$classes = 'bg-orange-100 border-2 border-orange-200 text-orange-500';
				break;
			default:
					$classes = 'bg-indigo-100 border-2 border-indigo-200 text-indigo-500';
				break;
		}

		return $classes;
	}

	public static function get_total( $data, $period = 'current', $status = 'all'  ) {
		$stat = 0;

		$stat_args = [
			'posts_per_page' => '-1',
			'post_type' => 'munim_invoice',
		];

		if ( 'all' === $status ) {
			$stat_args['post_status'] = [
				'publish',
				'paid',
				'partial',
			];
		} else {
			$stat_args['post_status'] = $status;
		}

		if ( 'current' === $period ) {
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => '>',
					'value'   => strtotime( 'last day of previous month', time() ),
					'type'    => 'numeric',
				],
			];
		}

		if ( 'previous' === $period ) {
			$stat_args['meta_query'] = [
				[
					'key'     => 'munim_invoice_date',
					'compare' => 'BETWEEN',
					'value'   => [
						strtotime( 'last day of -2 months', time() ),
						strtotime( 'last day of previous month', time() )
					],
					'type'    => 'numeric',
				],
			];
		}

		$stat_query = new \WP_Query( $stat_args );
		$stat_invoices = $stat_query->get_posts();

		if ( $stat_invoices ) {
			foreach ( $stat_invoices as $invoice ) {
				switch ($data) {
					case 'gross':
							$stat += $invoice->munim_invoice_total;
						break;

					case 'net':
							$stat += $invoice->munim_invoice_subtotal;
						break;

					case 'taxes':
							$stat += $invoice->munim_invoice_taxes_total;
						break;

					default:
							$stat = 0;
						break;
				}
			}
		}

		return $stat;
	}
}
