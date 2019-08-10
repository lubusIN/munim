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
		<div id="munim-at-glance" class="w-1/3 px-2 just">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Overview</h2>
			<div class="flex flex-wrap bg-white border border-gray-300">
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'gross' ) ); ?></span>
					<span class="text-gray-500">Gross For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">
						₹ <?php echo number_format( Helpers::get_total( 'net' ) - Helpers::get_total( 'tds', 'current' ) ); ?></span>
					<span class="text-gray-500">Net For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'gross', 'current', 'paid' ) - Helpers::get_total( 'tds', 'current' ) ); ?></span>
					<span class="text-gray-500">Receipts For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'gross', 'current', 'publish' ) ); ?></span>
					<span class="text-gray-500">Due For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'taxes', 'previous' ) ); ?></span>
					<span class="text-gray-500">Tax Payable</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'taxes' ) ); ?></span>
					<span class="text-gray-500">Tax Current</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-r border-gray-300">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'tds', 'current' ) ); ?></span>
					<span class="text-gray-500">TDS For Month</span>
				</div>
				<div class="w-1/2 flex flex-col p-4">
					<span class="text-xl mb-2">₹ <?php echo number_format( Helpers::get_total( 'tds', 'financial' ) ); ?></span>
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
						<?php echo Helpers::get_invoice_status_count( 'all', 'previous' ) - Helpers::get_invoice_status_count( ['paid', 'cancelled'], 'previous' );  ?>
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
							<span class="w-4/6 text-blue-600">
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

		<div id="munim-business-info" class="w-1/3 px-2 mt-4">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Business Info</h2>
			<div class="flex flex-wrap bg-white border border-2 border-gray-300 p-4">

			<?php
				// Get bank details from settings
				$munim_settings_bank = get_option( 'munim_settings_bank', [] )['info'];
				$munim_settings_invoice = get_option( 'munim_settings_invoice', [] )['info'];
				?>

				<div class="w-1/2 mb-2">
					<div class="munim-bank-details">
						<?php foreach ($munim_settings_bank as $bank_info ): ?>

							<div class="flex flex-col mt-2">
								<span class="text-xs text-gray-600"><?php echo $bank_info['name']; ?></span>
								<span class="text-base"><?php echo $bank_info['value']; ?></span>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
				<div class="w-1/2 relative">
 					<div class="munim-invoice-details">
						<?php foreach ($munim_settings_invoice as $invoice_info ): ?>

							<div class="flex flex-col my-2">
								<span class="text-xs text-gray-600"><?php echo $invoice_info['name']; ?></span>
								<span class="text-base"><?php echo $invoice_info['value']; ?></span>
							</div>

						<?php endforeach; ?>
					</div>

					<textarea class="invisible" name="munim-copy-details" id="munim-copy-details" cols="25" rows="6"><?php
						foreach ($munim_settings_bank as $bank_info ):
						printf( '%s: %s &#10;', $bank_info['name'], $bank_info['value'] );
						endforeach;
						foreach ($munim_settings_bank as $bank_info ):
						printf( '%s: %s &#10;', $bank_info['name'], $bank_info['value'] );
						endforeach;?>
					</textarea>

					<?php
						$clipboard_data= "";
						foreach ($munim_settings_bank as $bank_info ):
							$clipboard_data .= sprintf( '%s: %s &#10;', $bank_info['name'], $bank_info['value'] );
						endforeach;
						foreach ($munim_settings_bank as $bank_info ):
							$clipboard_data .= sprintf( '%s: %s &#10;', $bank_info['name'], $bank_info['value'] );
						endforeach;
					?>
					<button
						class="clipboard-btn absolute bottom-0 right-0 inline-block leading-none cursor-pointer text-sm rounded p-2 border border-solid bg-gray-300"
						data-clipboard-text="<?php echo $clipboard_data; ?>"
					>
							<svg class="fill-current w-3 inline-block" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
									<g id="icon-shape">
										<path d="M12.9728369,2.59456737 C12.7749064,1.12946324 11.5193533,0 10,0 C8.48064666,0 7.2250936,1.12946324 7.02716314,2.59456737 L5,3 L5,4 L3.99406028,4 C2.89451376,4 2,4.8927712 2,5.99406028 L2,18.0059397 C2,19.1054862 2.8927712,20 3.99406028,20 L16.0059397,20 C17.1054862,20 18,19.1072288 18,18.0059397 L18,5.99406028 C18,4.89451376 17.1072288,4 16.0059397,4 L15,4 L15,3 L12.9728369,2.59456737 Z M5,6 L4,6 L4,18 L16,18 L16,6 L15,6 L15,7 L5,7 L5,6 Z M10,4 C10.5522847,4 11,3.55228475 11,3 C11,2.44771525 10.5522847,2 10,2 C9.44771525,2 9,2.44771525 9,3 C9,3.55228475 9.44771525,4 10,4 Z" id="Combined-Shape"></path>
									</g>
								</g>
							</svg> copy
					</button>

					<button
						class="clipboard-copied-btn hidden absolute bottom-0 right-0 inline-block leading-none cursor-pointer text-sm rounded p-2 border border-solid bg-teal-100 text-teal-500"
					>
						<svg class="fill-current w-3 inline-block" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
								<g id="icon-shape">
									<polygon id="Path-126" points="0 11 2 9 7 14 18 3 20 5 7 18"></polygon>
								</g>
							</g>
						</svg> copied
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
