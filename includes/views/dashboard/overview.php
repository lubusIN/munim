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
<div id="munim-at-glance" class="w-full md:w-1/2 xl:w-1/3 px-2">
	<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">
		<?php esc_html_e( 'Overview', 'munim' ); ?>
	</h2>
	<div class="flex flex-wrap bg-white border border-gray-300">
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'gross' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Gross For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'net' ) - Helpers::get_total( 'tds', 'current' ) ); ?></span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Net For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_receipts() ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Receipts For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'gross', 'current', 'publish' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Due For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'taxes', 'previous' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Tax Payable', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'taxes' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'Tax Current', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-r border-gray-300">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'tds', 'current' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'TDS For Month', 'munim' ); ?>
			</span>
		</div>
		<div class="w-1/2 flex flex-col p-4">
			<span class="text-xl mb-2">
				<?php echo esc_html( $munim_currency_symbol ); ?> <?php echo number_format( Helpers::get_total( 'tds', 'financial' ) ); ?>
			</span>
			<span class="text-gray-500">
				<?php esc_html_e( 'TDS For Year', 'munim' ); ?>
			</span>
		</div>
	</div>
</div>
