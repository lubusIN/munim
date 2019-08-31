<?php
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

use LubusIN\Munim\Helpers;

?>
<div id="munim-status" class="w-full md:w-1/2 xl:w-1/3 px-2 mt-4 md:mt-0 flex flex-col">
			<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">
				<?php esc_html_e( 'Status', 'munim' ); ?>
			</h2>
			<div class="flex flex-1 flex-wrap bg-white border border-2 border-gray-300">
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300" >
					<span class="text-xl mb-2">
						<?php echo esc_html( Helpers::get_invoice_status_count() ); ?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Issued', 'munim' ); ?>
					</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">
						<?php echo esc_html( Helpers::get_invoice_status_count( 'cancelled' ) ); ?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Cancelled', 'munim' ); ?>
					</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r border-gray-300">
					<span class="text-xl mb-2">
						<?php echo esc_html( Helpers::get_invoice_status_count( 'paid' ) ); ?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Paid', 'munim' ); ?>
					</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b xborder-gray-300">
					<span class="text-xl mb-2">
						<?php echo esc_html( Helpers::get_invoice_status_count( 'partial' ) ); ?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Partially Paid', 'munim' ); ?>
					</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-r  border-gray-300">
					<span class="text-xl mb-2">
						<?php
							$munim_all_invoices   = Helpers::get_invoice_status_count();
							$munim_paid_cancelled = Helpers::get_invoice_status_count( [ 'paid', 'cancelled' ] );
							echo esc_html( $munim_all_invoices - $munim_paid_cancelled );
						?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Due', 'munim' ); ?>
					</span>
				</div>
				<div class="w-1/2 flex flex-col p-4 border-b border-gray-300">
					<span class="text-xl mb-2">
						<?php
							$munim_previous_all  = Helpers::get_invoice_status_count( 'all', 'previous' );
							$munim_previous_paid = Helpers::get_invoice_status_count( [ 'paid', 'cancelled' ], 'previous' );
							echo esc_html( $munim_previous_all - $munim_previous_paid );
						?>
					</span>
					<span class="text-gray-500">
						<?php esc_html_e( 'Overdue', 'munim' ); ?>
					</span>
				</div>
				<div class="w-full flex flex-col px-4 py-2 bg-blue-100">
					<form method="post" class="hidden lg:block">
						<input type="hidden" name="munim_action" value="zip">
						<?php wp_nonce_field( 'zip', 'nonce' ); ?>
						<button type="submit" class="flex justify-center w-full p-2 mx-auto align-center">
							<svg class="text-gray-500 fill-current w-8 mr-4 self-start" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
									<g id="icon-shape">
										<path d="M13,8 L13,2 L7,2 L7,8 L2,8 L10,16 L18,8 L13,8 Z M0,18 L20,18 L20,20 L0,20 L0,18 Z" id="Combined-Shape"></path>
									</g>
								</g>
							</svg>
							<div class="flex flex-col">
								<span class="text-base text-gray-700 font-medium leading-none">
									<?php esc_html_e( 'Download Invoices', 'munim' ); ?>
								</span>
								<span class="text-left text-xs font-medium leading-normal text-gray-500">
									<?php esc_html_e( 'for previous month', 'munim' ); ?>
								</span>
							</div>
						</button>
					</form>

					<svg class="lg:hidden text-gray-500 fill-current w-10 mx-auto" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M62.9,60.2h6.4v14h-6.4V60.2z M40.3,74.1h6.4v-6.5h-6.4V74.1z M97.9,60.2c0,7-2.5,13.8-7,19.4c0.8,1.6,2,2.8,3.4,3.7  c1.5,1,2.2,2.7,1.8,4.4c-0.4,1.7-1.7,2.9-3.5,3.2c-1.2,0.2-2.4,0.3-3.6,0.3c-3.6,0-6.9-0.7-9.9-2.2c-5.6,2.9-12,4.4-18.6,4.4  C39.8,93.4,23,78.5,23,60.2S39.8,27,60.5,27C81.1,27,97.9,41.9,97.9,60.2z M91.4,60.2c0-14.8-13.9-26.8-31-26.8  c-17.1,0-31,12-31,26.8c0,14.8,13.9,26.8,31,26.8c6.1,0,11.9-1.5,17-4.4l1.6-0.9l1.6,1c1.7,1,3.6,1.7,5.7,2c-1-1.3-1.7-2.8-2.3-4.4  l-0.7-1.8l1.3-1.4C89.1,72.1,91.4,66.3,91.4,60.2z M51.6,74.1H58V53.9h-6.4V74.1z M74.2,74.1h6.4V46.2h-6.4V74.1z M60.5,20.6  C60.5,20.6,60.5,20.6,60.5,20.6c-5.3-8.3-15.5-14-27.2-14C16.1,6.6,2.1,18.8,2.1,33.9c0,6.5,2.6,12.5,6.9,17.2  c-0.8,2.2-2.3,4.6-4.9,6.4c-0.7,0.4-0.5,1.5,0.3,1.6c2.8,0.4,7.5,0.5,11.9-2.2c0,0,0,0,0,0C18.3,36.6,37.3,20.6,60.5,20.6z"/></svg>
				</div>
			</div>
		</div>
