<?php
/**
 * Dashboard Recent Invoices.
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
<div id="munim-recent-invoices" class="w-full md:w-1/2 xl:w-1/3 px-2 mt-4 xl:mt-0 flex flex-col">
	<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">
		<?php esc_html_e( 'Recent', 'munim' ); ?>
	</h2>
	<div class="flex flex-wrap bg-white border border-2 border-gray-300 flex flex-1">
		<ul class="py-4 w-full">
			<?php foreach ( Helpers::get_recent_invoices() as $invoice ) : ?>
				<li class="flex flex-wrap px-4 mb-4">
					<span class="w-4/6 text-blue-600">
						<a href="<?php echo esc_url( get_edit_post_link( $invoice ) ); ?>">
							<?php echo esc_html( $invoice->post_title ); ?>
						</a>
					</span>
					<span class="w-1/6">
						<?php echo esc_html( get_munim_currency_symbol() ); ?> <?php echo number_format( $invoice->munim_invoice_total ); ?>
					</span>
					<span class="w-1/6">
						<?php $invoice_status = Helpers::get_invoice_status( $invoice->post_status ); ?>
						<span class="<?php echo esc_attr( Helpers::get_status_classes( $invoice_status ) ); ?> block rounded-full text-center text-xs font-medium">
							<?php echo esc_html( $invoice_status ); ?>
						</span>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="p-4 w-full text-center border-t border-gray-300 font-medium self-end">
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=munim_invoice' ) ); ?>">
				<?php esc_html_e( 'View All', 'munim' ); ?>
			</a>
		</div>
	</div>
</div>
