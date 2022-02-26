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
	<div class="tw-flex tw-flex-col ">
		<div class="tw-w-full tw-flex">
			<span class="tw-w-1/2 tw-text-gray-500">
				<?php esc_html_e( 'Subtotal', 'munim' ); ?>
			</span>
			<span class="tw-w-1/2 tw-text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_subtotal ?? 0 ); ?>
			</span>
		</div>
		<div class="tw-w-full tw-flex tw-mb-2">
			<span class="tw-w-1/2 tw-text-gray-500">
				<?php esc_html_e( 'Taxes', 'munim' ); ?>
			</span>
			<span class="tw-w-1/2 tw-text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_taxes_total ?? 0 ); ?>
			</span>
		</div>
		<div class="tw-w-full tw-flex tw-border-t tw-border-gray-300 tw-pt-2">
			<span class="tw-w-1/2 tw-text-gray-500">
				<?php esc_html_e( 'Total', 'munim' ); ?>
			</span>
			<span class="tw-w-1/2 tw-text-right">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( $post->munim_invoice_total ?? 0 ); ?>
			</span>
		</div>
	</div>
</div>
