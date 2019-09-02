<?php
/**
 * Invoice info View.
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

global $post;
$munim_currency_symbol = get_munim_currency_symbol();
?>
<div>
	<div class="flex flex-col ">
		<div class="w-full flex">
			<span class="w-1/2 text-gray-500">
				<?php esc_html_e( 'Subtotal', 'munim' ); ?>
			</span>
			<span class="w-1/2 text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_subtotal ); ?>
			</span>
		</div>
		<div class="w-full flex mb-2">
			<span class="w-1/2 text-gray-500">
				<?php esc_html_e( 'Taxes', 'munim' ); ?>
			</span>
			<span class="w-1/2 text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_taxes_total ); ?>
			</span>
		</div>
		<div class="w-full flex border-t border-gray-300 pt-2">
			<span class="w-1/2 text-gray-500">
				<?php esc_html_e( 'Total', 'munim' ); ?>
			</span>
			<span class="w-1/2 text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_total ); ?>
			</span>
		</div>
	</div>
</div>
