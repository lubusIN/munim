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
$munim_settings_estimate      = get_option( 'munim_settings_estimate', [] );
$munim_settings_estimate_info = $munim_settings_estimate['info'];
$munim_settings_invoice      = get_option( 'munim_settings_invoice', [] );
$munim_settings_invoice_info = $munim_settings_invoice['info'];

// Estimate Data.
$estimate_id   = $estimate_id;
$estimate_name = get_the_title( $estimate_id );
$estimate_data = Helpers::array_shift( get_post_meta( $estimate_id ) );
$estimate_date = date( $munim_settings_invoice['date_format'], $estimate_data['munim_estimate_date'] );

$estimate_client_id   = $estimate_data['munim_estimate_client_id'];
$estimate_client_data = Helpers::array_shift( get_post_meta( $estimate_client_id ) );
$estimate_client_info = isset( $estimate_client_data['munim_client_additional_info'] ) ? maybe_unserialize( $estimate_client_data['munim_client_additional_info'] ) : false;
$estimate_client_name = get_the_title( $estimate_client_id );
$estimate_items       = maybe_unserialize( $estimate_data['munim_estimate_items'] );
$estimate_currency    = $estimate_client_data['munim_client_currency'];
$estimate_tax_items   = isset( $estimate_data['munim_estimate_taxes'] ) ? maybe_unserialize( $estimate_data['munim_estimate_taxes'] ) : [];
$estimate_logo        = $munim_settings_business['logo'];
$estimate_icon        = $munim_settings_business['secondary_logo'];
$estimate_note        = isset( $munim_settings_estimate['note'] ) ? $munim_settings_estimate['note'] : '';

// Totals.
$estimate_subtotal = array_sum( wp_list_pluck( $estimate_items, 'amount' ) );
$estimate_tax      = Helpers::get_tax_total( $estimate_tax_items, $estimate_subtotal );
$estimate_total    = $estimate_subtotal + $estimate_tax;


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php esc_html_e( 'Invoice', 'munim' ); ?></title>
	<?php // phpcs:ignore ?>
	<link rel="stylesheet" type="text/css" href="<?php echo MUNIM_PLUGIN_URL; ?>/templates/minimal/style.css">
</head>
<body>
	<div id="estimate">
		<div id="meta">
			<div id="header">
				<div id="logo" class="float-left width-half">
					<img src="<?php echo esc_url( $estimate_logo ); ?>" alt="Logo">
				</div>

				<div id="info" class="float-right text-right width-half">
					<h1><?php esc_html_e( 'Estimate', 'munim' ); ?></h1>
					<ul class="data-list">
						<li>#<?php echo esc_html( $estimate_data['munim_estimate_number'] ); ?></li>
						<li><?php echo esc_html( $estimate_date ); ?></li>
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

				<div id="client" class="float-right text-right width-60">
					<h2><?php esc_html_e( 'Recipient', 'munim' ); ?></h2>
					<ul class="data-list">
						<li><?php echo esc_html( $estimate_client_name ); ?></li>
						<li>
							<?php
								echo isset( $estimate_client_data['munim_client_address_1'] )
								? esc_html( $estimate_client_data['munim_client_address_1'] ) . '<br />'
								: '';
							?>

							<?php
								echo isset( $estimate_client_data['munim_client_address_2'] )
								? esc_html( $estimate_client_data['munim_client_address_2'] ) . '<br />'
								: '';
							?>


							<?php
								echo isset( $estimate_client_data['munim_client_city'] )
								? esc_html( $estimate_client_data['munim_client_city'] ) . ','
								: '';
							?>


							<?php
								echo isset( $estimate_client_data['munim_client_state'] )
								? esc_html( $estimate_client_data['munim_client_state'] ) . ', <br />'
								: '';
							?>

							<?php
								echo isset( $estimate_client_data['munim_client_country'] )
								? esc_html( $estimate_client_data['munim_client_country'] ) . ','
								: '';
							?>

							<?php
								echo isset( $estimate_client_data['munim_client_zip'] )
								? esc_html( $estimate_client_data['munim_client_zip'] )
								: '';
							?>
						</li>
						<?php
						if ( is_array( $estimate_client_info ) ) {
							foreach ( $estimate_client_info as $info ) {
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
					if ( is_array( $estimate_items ) ) {
						foreach ( $estimate_items as $item ) {
							?>
							<tr class="border-bottom">
								<td><?php echo esc_html( $item['name'] ); ?></td>
								<td class="text-right">
									<span class="currency-symbol">
										<?php echo esc_html( get_munim_currency_symbol( $estimate_currency ) ); ?>
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
					<?php if ( is_array( $estimate_tax_items ) ) { ?>
						<!-- Sub Total -->
						<tr id="sub-total" class="border-bottom">
							<td width="30%" class="filler"></td>
							<td width="30%" class="filler"></td>
							<td width="20%"><?php esc_html_e( 'Sub-Total', 'munim' ); ?></td>
							<td width="20%" class="text-right">
							<span class="currency-symbol">
								<?php echo esc_html( get_munim_currency_symbol( $estimate_currency ) ); ?>
							</span><?php echo number_format( $estimate_subtotal ); ?>
							</td>
						</tr>

						<!-- Taxes -->
						<?php foreach ( $estimate_tax_items as $tax_item ) { ?>
							<tr class="border-bottom">
								<td width="30%" class="filler"></td>
								<td width="30%" class="filler"></td>
								<td width="20%"><?php echo esc_html( sprintf( '%s (%s%%)', $tax_item['name'], $tax_item['rate'] ) ); ?></td>
								<td width="20%" class="text-right">
									<span class="currency-symbol">
										<?php echo esc_html( get_munim_currency_symbol( $estimate_currency ) ); ?>
									</span><?php echo number_format( round( ( $tax_item['rate'] / 100 ) * $estimate_subtotal ) ); ?>
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
										<?php echo esc_html( get_munim_currency_symbol( $estimate_currency ) ); ?>
									</span><?php echo number_format( round( $estimate_total ) ); ?>
							</h2>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="footer" class="clear-both">

			<div id="contact-info" class="float-left width-half">
                <ul class="data-list">
                    <li class="contact-info-item"><?php echo esc_html($munim_settings_business['website']); ?></li>
                    <li class="contact-info-item"><?php echo esc_html($munim_settings_business['email']); ?></li>
                    <li class="contact-info-item"><?php echo esc_html($munim_settings_business['address_1']); ?></li>
                    <li class="contact-info-item"><?php echo esc_html($munim_settings_business['address_2']); ?></li>
                    <li class="contact-info-item"><?php echo esc_html($munim_settings_business['city']); ?>, <?php echo esc_html($munim_settings_business['zip']); ?>
                </ul>
			</div>
			<div id="note" class="float-right width-half">
				<p><?php echo esc_html( $estimate_note ); ?></p>
				<h2>
					<img id="heart" src="<?php echo MUNIM_PLUGIN_URL . 'templates/minimal/img/heart.png' ?>" alt="heart">
					<?php esc_html_e( 'Thank You', 'munim' ); ?>
				</h2>
			</div>
		</div>

	</div>
</body>
</html>
