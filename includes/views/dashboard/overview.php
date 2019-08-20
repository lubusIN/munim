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
?>
<div id="munim-at-glance" class="w-full md:w-1/2 xl:w-1/3 px-2">
	<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Overview</h2>
	<div class="flex flex-wrap bg-white border border-gray-300">
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'gross' ) ); ?></span>
			<span class="text-gray-500">Gross For Month</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2">
				<?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'net' ) - Helpers::get_total( 'tds', 'current' ) ); ?></span>
			<span class="text-gray-500">Net For Month</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_receipts() ); ?></span>
			<span class="text-gray-500">Receipts For Month</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'gross', 'current', 'publish' ) ); ?></span>
			<span class="text-gray-500">Due For Month</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'taxes', 'previous' ) ); ?></span>
			<span class="text-gray-500">Tax Payable</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'taxes' ) ); ?></span>
			<span class="text-gray-500">Tax Current</span>
		</div>
		<div class="w-1/2 flex flex-col p-4 border-r border-gray-300">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'tds', 'current' ) ); ?></span>
			<span class="text-gray-500">TDS For Month</span>
		</div>
		<div class="w-1/2 flex flex-col p-4">
			<span class="text-xl mb-2"><?php echo get_munim_currency_symbol(); ?> <?php echo number_format( Helpers::get_total( 'tds', 'financial' ) ); ?></span>
			<span class="text-gray-500">TDS For Year</span>
		</div>
	</div>
</div>
