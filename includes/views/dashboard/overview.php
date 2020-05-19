<?php
/**
 * Dashboard Overview.
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

use LubusIN\Munim\Helpers;

$munim_currency_symbol = get_munim_currency_symbol();
?>
<div id="munim-at-glance" class="tw-w-full md:tw-w-1/2 xl:tw-w-1/3 tw-px-2">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">
		<?php esc_html_e( 'Overview', 'munim' ); ?>
	</h2>
	<div class="tw-flex tw-flex-wrap tw-bg-white tw-border tw-border-gray-300">
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-r tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'gross' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Gross For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'net' ) - Helpers::get_total( 'tds', 'current' ) ); ?></span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Net For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-r tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_receipts() ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Receipts For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'gross', 'current', 'publish' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Due For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-r tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'taxes', 'previous' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Tax Payable', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-b tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'taxes' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'Tax Current', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4 tw-border-r tw-border-gray-300">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'tds', 'current' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'TDS For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="tw-w-1/2 tw-flex tw-flex-col tw-p-4">
			<span class="tw-text-xl tw-mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'tds', 'financial' ) ); ?>
			</span>
			<span class="tw-text-gray-500">
				<?php esc_html_e( 'TDS For Year', 'munim' ); ?>
			</span>
		</div>
	</div>
</div>
