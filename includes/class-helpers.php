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
}
