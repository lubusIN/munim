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
<div id="munim-recent-invoices" class="tw-w-full md:tw-w-1/2 xl:tw-w-1/3 tw-px-2 tw-mt-4 xl:tw-mt-0 tw-flex tw-flex-col">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">
		<?php esc_html_e( 'Recent', 'munim' ); ?>
	</h2>
	<div class="tw-flex tw-flex-wrap tw-bg-white tw-border tw-border-2 tw-border-gray-300 tw-flex tw-flex-1">
		<ul class="tw-py-4 tw-w-full">
			<?php foreach ( Helpers::get_recent_invoices() as $invoice ) : ?>
				<li class="tw-flex tw-flex-wrap tw-px-4 tw-mb-4">
					<span class="tw-w-4/6 tw-text-blue-600">
						<a href="<?php echo esc_url( get_edit_post_link( $invoice ) ); ?>">
							<?php echo esc_html( $invoice->post_title ); ?>
						</a>
					</span>
					<span class="tw-w-1/6">
						<?php echo esc_html( get_munim_currency_symbol() ); ?> <?php echo number_format( $invoice->munim_invoice_total ); ?>
					</span>
					<span class="tw-w-1/6">
						<?php $invoice_status = Helpers::get_invoice_status( $invoice->post_status ); ?>
						<span class="<?php echo esc_attr( Helpers::get_status_classes( $invoice_status ) ); ?> tw-block tw-rounded-full tw-text-center tw-text-xs tw-font-medium">
							<?php echo esc_html( $invoice_status ); ?>
						</span>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="tw-p-4 tw-w-full tw-text-center tw-border-t tw-border-gray-300 tw-font-medium tw-self-end">
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=munim_invoice' ) ); ?>">
				<?php esc_html_e( 'View All', 'munim' ); ?>
			</a>
		</div>
	</div>
</div>
