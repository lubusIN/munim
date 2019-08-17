<?php
use LubusIN\Munim\Helpers;

/**
 * Dashboard Status.
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
<div id="munim-status" class="w-full md:w-1/2 xl:w-1/3 px-2 mt-4 md:mt-0">
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
						<?php echo Helpers::get_invoice_status_count( 'all', 'previous' ) - Helpers::get_invoice_status_count( ['paid', 'cancelled'], 'previous' );  ?>
					</span>
					<span class="text-gray-500">Overdue</span>
				</div>
				<div class="w-full flex flex-col p-4">
					<button class="flex justify-center w-full xl:w-1/2 border-2 border-blue-500 bg-blue-100 rounded-lg p-2 mx-auto align-center">
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
