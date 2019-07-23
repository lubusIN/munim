<?php
use LubusIN\Munim\Helpers;

/**
 * Dashboard View.
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

?>
<div class="wrap">
	<h1>Dashboard</h1>

	<div class="flex flex-wrap -mx-2 mt-4">
		<div id="munim-at-glance" class="w-1/3 px-2">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Overview</h2>
			<div class="flex flex-wrap bg-white border border-gray-300">
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ 10,000</span>
					<span class="text-gray-500">Gross For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">₹ 8,000</span>
					<span class="text-gray-500">Net For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ 6,000</span>
					<span class="text-gray-500">Receipts For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">₹ 4,000</span>
					<span class="text-gray-500">Outstanding For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ 2,000</span>
					<span class="text-gray-500">GST Payable</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">₹ 1,000</span>
					<span class="text-gray-500">GST Current</span>
				</div>
				<div class="w-full flex flex-col p-4">
					<span class="text-xl mb-2">₹ 10,000</span>
					<span class="text-gray-500">TDS For Year</span>
				</div>
			</div>
		</div>

		<div id="munim-status" class="w-1/3 px-2">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Status</h2>
			<div class="flex flex-wrap bg-white border border-2 border-gray-300">
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300" >
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count();  ?>
					</span>
					<span class="text-gray-500">Issued</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count( 'cancelled' );  ?>
					</span>
					<span class="text-gray-500">Cancelled</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count( 'paid' );  ?>
					</span>
					<span class="text-gray-500">Paid</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b xborder-gray-300">
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count( 'partial' );  ?>
					</span>
					<span class="text-gray-500">Partially Paid</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r  border-gray-300">
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count() - Helpers::get_invoice_status_count( ['paid', 'cancelled'] );  ?>
					</span>
					<span class="text-gray-500">Due</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">
						<?php echo Helpers::get_invoice_status_count( '', 'previous' ) - Helpers::get_invoice_status_count( ['paid', 'cancelled'], 'previous' );  ?>
					</span>
					<span class="text-gray-500">Overdue</span>
				</div>
				<div class="w-full flex flex-col p-4">
					<button class="flex justify-center w-1/2 border-2 border-blue-500 bg-blue-100 rounded-lg p-2 mx-auto align-center">
						<svg class="fill-current w-4 mr-2" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
								<g id="icon-shape">
									<path d="M13,8 L13,2 L7,2 L7,8 L2,8 L10,16 L18,8 L13,8 Z M0,18 L20,18 L20,20 L0,20 L0,18 Z" id="Combined-Shape"></path>
								</g>
							</g>
						</svg>
						<span class="text-base font-medium leading-normal">Download Invoices</span>
					</button>
				</div>
			</div>
		</div>

		<div id="munim-recent-invoices" class="w-1/3 px-2">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Recent</h2>
			<div class="flex flex-wrap bg-white border border-2 border-gray-300">
				<?php $munim_recent_invoices = Helpers::get_recent_invoices(); ?>
				<ul class="py-4 w-full">
					<li class="flex flex-wrap font-bold px-4 mb-4">
							<span class="w-4/6">Name</span>
							<span class="w-1/6">Amount</span>
							<span class="w-1/6">Status</span>
					</li>

					<?php foreach ($munim_recent_invoices as $invoice) : ?>
						<li class="flex flex-wrap px-4 mb-4">
							<span class="w-4/6">
								<a href="<?php echo get_edit_post_link( $invoice );  ?>">
									<?php echo $invoice->post_title; ?>
								</a>
							</span>
							<span class="w-1/6">
								₹ <?php echo number_format( $invoice->munim_invoice_total ); ?>
							</span>
							<span class="w-1/6">
								<?php $invoice_status = Helpers::get_invoice_status( $invoice->post_status ); ?>
								<span class="<?php echo Helpers::get_status_classes( $invoice_status ) ?> block rounded-full text-center text-xs font-medium">
									<?php echo $invoice_status; ?>
								</span>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="p-4 w-full text-center border-t border-gray-300 font-medium">
					<a href="<?php echo admin_url( 'edit.php?post_type=munim_invoice' ); ?>">View All</a>
				</div>
			</div>
		</div>

		<div id="munim-monthly-trend" class="w-1/3 px-2 mt-4">
		<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Trend</h2>
			<div class="flex flex-wrap bg-white border border-2 border-gray-300 p-4">
				<div id="chart"></div>
			</div>
		</div>
	</div>
</div>
