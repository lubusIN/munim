<?php
/**
 * Dashboard Business Info.
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
<div id="munim-business-info" class="tw-w-full md:tw-w-1/2 xl:tw-w-1/3 tw-px-2 tw-mt-4">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">
		<?php esc_html_e( 'Business Info', 'munim' ); ?>
	</h2>
	<div class="tw-flex tw-flex-wrap tw-bg-white tw-border tw-border-2 tw-border-gray-300 tw-p-4">

	<?php
		// Get bank details from settings.
		$munim_settings_bank    = get_option( 'munim_settings_bank', [] )['info'];
		$munim_settings_invoice = get_option( 'munim_settings_invoice', [] )['info'];
		$munim_clipboard_info   = array_merge( $munim_settings_bank, $munim_settings_invoice );
	?>

		<div class="tw-w-1/2 tw-mb-2">
			<div class="munim-bank-details">
				<?php
				foreach ( $munim_settings_bank as $bank_info ) :
					if ( ! isset( $bank_info['hide_on_dashboard'] ) || ! $bank_info['hide_on_dashboard'] ) :
						?>
						<div class="tw-flex tw-flex-col tw-mt-2">
							<span class="tw-text-xs tw-text-gray-600">
								<?php echo esc_html( $bank_info['name'] ); ?>
							</span>
							<span class="tw-text-base">
								<?php echo esc_html( $bank_info['value'] ); ?>
							</span>
						</div>
						<?php
					endif;
				endforeach;
				?>
			</div>
		</div>
		<div class="tw-w-1/2 tw-relative">
			<div class="munim-invoice-details">
				<?php
				foreach ( $munim_settings_invoice as $invoice_info ) :
					if ( ! isset( $invoice_info['hide_on_dashboard'] ) || ! $invoice_info['hide_on_dashboard'] ) :
						?>
						<div class="tw-flex tw-flex-col tw-my-2">
							<span class="tw-text-xs tw-text-gray-600">
								<?php echo esc_html( $invoice_info['name'] ); ?>
							</span>
							<span class="tw-text-base">
								<?php echo esc_html( $invoice_info['value'] ); ?>
							</span>
						</div>
						<?php
					endif;
				endforeach;
				?>
			</div>

			<?php
			$clipboard_data = '';
			foreach ( $munim_clipboard_info as $info ) {
				if ( ! isset( $info['hide_on_dashboard'] ) || ! $info['hide_on_dashboard'] ) {
					$clipboard_data .= sprintf( '%s: %s &#10;', $info['name'], $info['value'] );
				}
			}
			?>
			<button
				class="clipboard-btn tw-absolute tw-bottom-0 tw-right-0 tw-inline-block tw-leading-none tw-cursor-pointer tw-text-sm rounded tw-p-2 tw-border tw-border-solid tw-bg-gray-300"
				data-clipboard-text="<?php echo esc_attr( $clipboard_data ); ?>"
			>
					<svg class="tw-fill-current tw-w-3 tw-inline-block" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
							<g id="icon-shape">
								<path d="M12.9728369,2.59456737 C12.7749064,1.12946324 11.5193533,0 10,0 C8.48064666,0 7.2250936,1.12946324 7.02716314,2.59456737 L5,3 L5,4 L3.99406028,4 C2.89451376,4 2,4.8927712 2,5.99406028 L2,18.0059397 C2,19.1054862 2.8927712,20 3.99406028,20 L16.0059397,20 C17.1054862,20 18,19.1072288 18,18.0059397 L18,5.99406028 C18,4.89451376 17.1072288,4 16.0059397,4 L15,4 L15,3 L12.9728369,2.59456737 Z M5,6 L4,6 L4,18 L16,18 L16,6 L15,6 L15,7 L5,7 L5,6 Z M10,4 C10.5522847,4 11,3.55228475 11,3 C11,2.44771525 10.5522847,2 10,2 C9.44771525,2 9,2.44771525 9,3 C9,3.55228475 9.44771525,4 10,4 Z" id="Combined-Shape"></path>
							</g>
						</g>
					</svg> <?php esc_html_e( 'copy', 'munim' ); ?>
			</button>

			<button
				class="clipboard-copied-btn tw-hidden tw-absolute tw-bottom-0 tw-right-0 tw-inline-block tw-leading-none tw-cursor-pointer tw-text-sm tw-rounded tw-p-2 tw-border tw-border-solid tw-bg-teal-100 tw-text-teal-500"
			>
				<svg class="tw-fill-current tw-w-3 tw-inline-block" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
						<g id="icon-shape">
							<polygon id="Path-126" points="0 11 2 9 7 14 18 3 20 5 7 18"></polygon>
						</g>
					</g>
				</svg> <?php esc_html_e( 'copied', 'munim' ); ?>
			</button>
		</div>
	</div>
</div>
