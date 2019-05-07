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
$munim_settings_invoice      = get_option( 'munim_settings_invoice', [] );
$munim_settings_invoice_info = $munim_settings_invoice['info'];

// Invoice Data.
$invoice_id          = $_GET['munim_invoice_id'];
$invoice_name        = get_the_title( $invoice_id );
$invoice_data        = Helpers::array_shift( get_post_meta( $invoice_id ) );
$invoice_client_id   = $invoice_data['munim_invoice_client_id'];
$invoice_client_data = Helpers::array_shift( get_post_meta( $invoice_client_id ) );
$invoice_client_name = get_the_title( $invoice_client_id );
$invoice_items       = maybe_unserialize( $invoice_data['munim_invoice_items'] );
$invoice_tax_items   = maybe_unserialize( $invoice_data['munim_invoice_taxes'] );
$invoice_logo        = get_attached_file( $munim_settings_business['logo_id'] );
$invoice_icon        = get_attached_file( $munim_settings_business['secondary_logo_id'] );

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
	<title>Invoice</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="invoice">
		<div id="meta">
			<div id="header">
				<div id="logo" class="float-left width-half">
					<img src="<?php echo $invoice_logo; ?>" alt="Logo">
				</div>

				<div id="info" class="float-right text-right width-half">
					<h1>Tax Invoice</h1>
					<ul class="data-list">
						<li>#<?php echo $invoice_data['munim_invoice_number']; ?></li>
						<li><?php echo $invoice_data['munim_invoice_date']; ?></li>
					</ul>
				</div>
			</div>

			<div id="details" class="clear-both">
				<div id="business" class="float-left width-40">
					<h2>Company Details</h2>
					<ul class="data-list">
						<?php foreach ( $munim_settings_invoice_info as $info ) { ?>
							<li><?php echo $info['name'] . ': ' . $info['value']; ?></li>
						<?php } ?>
					</ul>
				</div>

				<div id="client" class="float-right width-60 text-right">
					<h2>Recipient</h2>
					<ul class="data-list">
						<li><?php echo $invoice_client_name; ?></li>
						<li>
							<?php echo $invoice_client_data['munim_client_address_1']; ?><br />
							<?php echo $invoice_client_data['munim_client_address_2']; ?><br />
							<?php echo $invoice_client_data['munim_client_city']; ?>,
							<?php echo $invoice_client_data['munim_client_state']; ?>
							<?php echo $invoice_client_data['munim_client_zip']; ?>
						</li>
						<li>GSTIN: <?php echo $invoice_client_data['munim_client_gstin']; ?></li>
					</ul>
				</div>
			</div>
		</div>

		<div id="items" class="clear-both">
			<table width="100%">
				<thead>
					<tr class="border-bottom">
						<th >Item</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $invoice_items as $item ) { ?>
						<tr class="border-bottom">
							<td><?php echo $item['name']; ?></td>
							<td class="text-right">INR <?php echo $item['amount']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

		<div id="taxes" class="clear-both">
			<table width="100%">
				<tbody>
					<!-- Sub Total -->
					<tr id="sub-total" class="border-bottom">
						<td width="30%" class="filler"></td>
						<td width="30%" class="filler"></td>
						<td width="20%">Sub-Total</td>
						<td width="20%" class="text-right">INR <?php echo $invoice_subtotal; ?></td>
					</tr>

					<!-- Taxes -->
					<?php foreach ( $invoice_tax_items as $tax_item ) { ?>
						<tr class="border-bottom">
							<td width="30%" class="filler"></td>
							<td width="30%" class="filler"></td>
							<td width="20%"><?php echo $tax_item['name']; ?> (<?php echo $tax_item['rate']; ?>%)</td>
							<td width="20%" class="text-right">INR <?php echo round ( ( $tax_item['rate'] / 100 ) * $invoice_subtotal ); ?></td>
						</tr>
					<?php } ?>

					<!-- Total -->
					<tr id="total">
						<td width="30%" class="filler"></td>
						<td width="30%" class="filler"></td>
						<td width="20%"><h2>Total</h2></td>
						<td width="20%" class="text-right"><h2>INR <?php echo round( $invoice_total ); ?></h2></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="footer" class="clear-both">
			<div id="contact" class="float-left">
				<ul class="data-list">
					<li id="brand-icon"><img src="<?php echo $invoice_icon; ?>" alt="Logo"></li>
					<li>E - <?php echo $munim_settings_business['email']; ?></li>
					<li>W - <?php echo $munim_settings_business['website']; ?></li>
					<li>
						<?php echo $munim_settings_business['address_1']; ?><br />
						<?php echo $munim_settings_business['address_2'] . ' ' . $munim_settings_business['city'] . '-' . $munim_settings_business['zip']; ?><br />
					</li>
				</ul>
			</div>
			<div id="note" class="float-right">
				<h1>Thank You</h1>
			</div>
		</div>

	</div>
</body>
</html>
