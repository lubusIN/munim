<?php
/**
 * Lubus Invoice Template.
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

// Settings.
$munim_settings_business     = get_option( 'munim_settings_business', [] );
$munim_settings_bank         = get_option( 'munim_settings_bank', [] )['info'];
$munim_settings_invoice      = get_option( 'munim_settings_invoice', [] );
$munim_settings_invoice_info = $munim_settings_invoice['info'];

// Invoice Data.
$invoice_id   = $invoice_id;
$invoice_name = get_the_title( $invoice_id );
$invoice_data = Helpers::array_shift( get_post_meta( $invoice_id ) );
$invoice_date = date( $munim_settings_invoice['date_format'], $invoice_data['munim_invoice_date'] );

$invoice_client_id   = $invoice_data['munim_invoice_client_id'];
$invoice_client_data = Helpers::array_shift( get_post_meta( $invoice_client_id ) );
$invoice_client_info = isset( $invoice_client_data['munim_client_additional_info'] ) ? maybe_unserialize( $invoice_client_data['munim_client_additional_info'] ) : false;
$invoice_client_name = get_the_title( $invoice_client_id );
$invoice_items       = maybe_unserialize( $invoice_data['munim_invoice_items'] );
$invoice_currency    = $invoice_client_data['munim_client_currency'];
$invoice_tax_items   = isset( $invoice_data['munim_invoice_taxes'] ) ? maybe_unserialize( $invoice_data['munim_invoice_taxes'] ) : [];
$invoice_logo        = get_attached_file( $munim_settings_business['logo_id'] );
$invoice_icon        = get_attached_file( $munim_settings_business['secondary_logo_id'] );
$invoice_note        = isset( $munim_settings_invoice['note'] ) ? $munim_settings_invoice['note'] : '';

// Totals.
$invoice_subtotal = array_sum( wp_list_pluck( $invoice_items, 'amount' ) );
$invoice_tax      = Helpers::get_tax_total( $invoice_tax_items, $invoice_subtotal );
$invoice_total    = $invoice_subtotal + $invoice_tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php esc_html_e( 'Invoice', 'munim' ); ?></title>
	<?php // phpcs:ignore ?>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="invoice">
		<div id="meta">
			<div id="header">
				<div id="logo" class="float-left width-half">
					<img src="<?php echo esc_url( $invoice_logo ); ?>" alt="Logo">
				</div>

				<div id="info" class="float-right text-right width-half">
					<h1><?php esc_html_e( 'Tax Invoice', 'munim' ); ?></h1>
					<ul class="data-list">
						<li>#<?php echo esc_html( $invoice_data['munim_invoice_number'] ); ?></li>
						<li><?php echo esc_html( $invoice_date ); ?></li>
					</ul>
				</div>
			</div>

			<div id="details" class="clear-both">
				<div id="business" class="float-left width-40">
					<h2><?php esc_html_e( 'Company Details', 'munim' ); ?></h2>
					<ul class="data-list">
						<?php
						if ( is_array( $munim_settings_invoice_info ) ) {
							foreach ( $munim_settings_invoice_info as $info ) {
								if ( ! isset( $info['hide_on_dashboard'] ) || ! $info['hide_on_dashboard'] ) {
									?>
										<li><?php echo esc_html( sprintf( '%s: %s', $info['name'], $info['value'] ) ); ?></li>
									<?php
								}
							}
						}
						?>
					</ul>
				</div>

				<div id="client" class="float-right width-60 text-right">
					<h2><?php esc_html_e( 'Recipient', 'munim' ); ?></h2>
					<ul class="data-list">
						<li><?php echo esc_html( $invoice_client_name ); ?></li>
						<li>
							<?php
								echo isset( $invoice_client_data['munim_client_address_1'] )
								? esc_html( $invoice_client_data['munim_client_address_1'] ) . '<br />'
								: '';
							?>

							<?php
								echo isset( $invoice_client_data['munim_client_address_2'] )
								? esc_html( $invoice_client_data['munim_client_address_2'] ) . '<br />'
								: '';
							?>
							

							<?php
								echo isset( $invoice_client_data['munim_client_city'] )
								? esc_html( $invoice_client_data['munim_client_city'] ) . ','
								: '';
							?>
								

							<?php
								echo isset( $invoice_client_data['munim_client_state'] )
								? esc_html( $invoice_client_data['munim_client_state'] ) . ', <br />'
								: '';
							?>

							<?php
								echo isset( $invoice_client_data['munim_client_country'] )
								? esc_html( $invoice_client_data['munim_client_country'] ) . ','
								: '';
							?>

							<?php
								echo isset( $invoice_client_data['munim_client_zip'] )
								? esc_html( $invoice_client_data['munim_client_zip'] )
								: '';
							?>
						</li>
						<?php
						if ( is_array( $invoice_client_info ) ) {
							foreach ( $invoice_client_info as $info ) {
								?>
								<li><?php echo esc_html( sprintf( '%s: %s', $info['name'], $info['value'] ) ); ?></li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>

		<div id="items" class="clear-both">
			<table width="100%">
				<thead>
					<tr class="border-bottom">
						<th ><?php esc_html_e( 'Item', 'munim' ); ?></th>
						<th class="text-right"><?php esc_html_e( 'Amount', 'munim' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $invoice_items ) ) {
						foreach ( $invoice_items as $item ) {
							?>
							<tr class="border-bottom">
								<td><?php echo esc_html( $item['name'] ); ?></td>
								<td class="text-right">
									<span class="currency-symbol">
										<?php echo esc_html( get_munim_currency_symbol( $invoice_currency ) ); ?>
									</span><?php echo number_format( $item['amount'] ); ?>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>

		<div id="taxes" class="clear-both">
			<table width="100%">
				<tbody>
					<?php if ( is_array( $invoice_tax_items ) ) { ?>
						<!-- Sub Total -->
						<tr id="sub-total" class="border-bottom">
							<td width="30%" class="filler"></td>
							<td width="30%" class="filler"></td>
							<td width="20%"><?php esc_html_e( 'Sub-Total', 'munim' ); ?></td>
							<td width="20%" class="text-right">
							<span class="currency-symbol">
								<?php echo esc_html( get_munim_currency_symbol( $invoice_currency ) ); ?>
							</span><?php echo number_format( $invoice_subtotal ); ?>
							</td>
						</tr>

						<!-- Taxes -->
						<?php foreach ( $invoice_tax_items as $tax_item ) { ?>
							<tr class="border-bottom">
								<td width="30%" class="filler"></td>
								<td width="30%" class="filler"></td>
								<td width="20%"><?php echo esc_html( sprintf( '%s (%s%%)', $tax_item['name'], $tax_item['rate'] ) ); ?></td>
								<td width="20%" class="text-right">
									<span class="currency-symbol">
										<?php echo esc_html( get_munim_currency_symbol( $invoice_currency ) ); ?>
									</span><?php echo number_format( round( ( $tax_item['rate'] / 100 ) * $invoice_subtotal ) ); ?>
								</td>
							</tr>
						<?php } // End Taxes ?>
					<?php } // End Invoice Taxes check ?>

					<!-- Total -->
					<tr id="total">
						<td width="30%" class="filler"></td>
						<td width="30%" class="filler"></td>
						<td width="20%"><h2><?php esc_html_e( 'Total', 'munim' ); ?></h2></td>
						<td width="20%" class="text-right">
							<h2>
									<span class="currency-symbol">
										<?php echo esc_html( get_munim_currency_symbol( $invoice_currency ) ); ?>
									</span><?php echo number_format( round( $invoice_total ) ); ?>
							</h2>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="footer" class="clear-both">
			<div id="bank" class="float-left width-half">
				<ul class="data-list">
					<li><h2>Bank Transfer</h2></li>
					<?php
					foreach ( $munim_settings_bank as $bank_info ) :
						if ( ! isset( $bank_info['hide_on_invoice'] ) || ! $bank_info['hide_on_invoice'] ) :
							?>
							<li class="bank-info-item">
								<?php 
									echo esc_html(
										sprintf(
											'%s: %s',
											$bank_info['name'],
											$bank_info['value'] 
										)
									); 
								?>
							</li>
							<?php
						endif;
					endforeach;
					?>
					
				</ul>
			</div>
			<div id="note" class="float-right width-half">
				<p><?php echo esc_html( $invoice_note ); ?></p>
				<h2>
					<img id="heart" src="<?php echo MUNIM_PLUGIN_DIR . 'templates/minimal/img/heart.png' ?>" alt="heart">
					<?php esc_html_e( 'Thank You', 'munim' ); ?>
				</h2>
			</div>
			
			<div id="contact" class="clear-both">
				<?php
					echo esc_html(
						sprintf(
							'%s %s %s, %s | %s | %s',
							$munim_settings_business['address_1'],
							$munim_settings_business['address_2'],
							$munim_settings_business['city'],
							$munim_settings_business['zip'],
							$munim_settings_business['email'],
							$munim_settings_business['website'] 
						)
					);
				?>
			</div>
		</div>

	</div>
</body>
</html>
