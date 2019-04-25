<?php
/**
 * Lubus Invoice Template.
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munimiji.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munimji
 */

use LubusIN\Munimji\Helpers;

// Settings.
$munimji_settings_business = get_option( 'munimji_settings_business', [] );
$munimji_settings_invoice  = get_option( 'munimji_settings_invoice', [] );
$munimji_settings_info     = $munimji_settings_invoice['munimji_settings_invoice_info'];

// Invoice Data.
$invoice_id          = $_GET['munimji_invoice_id'];
$invoice_name        = get_the_title( $invoice_id );
$invoice_data        = Helpers::array_shift( get_post_meta( $invoice_id ) );
$invoice_client_id   = $invoice_data['munimji_invoice_client_id'];
$invoice_client_data = Helpers::array_shift( get_post_meta( $invoice_client_id ) );
$invoice_client_name = get_the_title( $invoice_client_id );
$invoice_items       = maybe_unserialize( $invoice_data['munimji_invoice_items'] );
$invoice_tax_items   = maybe_unserialize( $invoice_data['munimji_invoice_taxes'] );
$invoice_logo        = get_attached_file( $munimji_settings_business['logo_id'] );
$invoice_icon        = get_attached_file( $munimji_settings_business['secondary_logo_id'] );

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
						<li>#<?php echo $invoice_data['munimji_invoice_number']; ?></li>
						<li><?php echo $invoice_data['munimji_invoice_date']; ?></li>
					</ul>
				</div>
			</div>

			<div id="details" class="clear-both">
				<div id="business" class="float-left width-half">
					<h2>Company Details</h2>
					<ul class="data-list">
						<?php foreach ( $munimji_settings_info as $info ) { ?>
							<li><?php echo $info['name'] . ': ' . $info['value']; ?></li>
						<?php } ?>
					</ul>
				</div>

				<div id="client" class="float-right width-half text-right">
					<h2>Recipient</h2>
					<ul class="data-list">
						<li><?php echo $invoice_client_name; ?></li>
						<li>
							<?php echo $invoice_client_data['munimji_client_address_1']; ?><br />
							<?php echo $invoice_client_data['munimji_client_address_2'] . ' ' . $invoice_client_data['munimji_client_city'] . '-' . $invoice_client_data['munimji_client_zip']; ?><br />
							<?php //echo $invoice_client_data['munimji_client_state'] . ', ' . $invoice_client_data['munimji_client_country']; ?>
						</li>
						<li>GSTIN: <?php echo $invoice_client_data['munimji_client_gstin']; ?></li>
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
							<td width="20%" class="text-right">INR <?php echo ( $tax_item['rate'] / 100 ) * $invoice_subtotal; ?></td>
						</tr>
					<?php } ?>

					<!-- Total -->
					<tr id="total">
						<td width="30%" class="filler"></td>
						<td width="30%" class="filler"></td>
						<td width="20%"><h2>Total</h2></td>
						<td width="20%" class="text-right"><h2>INR <?php echo $invoice_total; ?></h2></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="footer" class="clear-both">
			<div id="contact" class="float-left">
				<ul class="data-list">
					<li id="brand-icon"><img src="<?php echo $invoice_icon; ?>" alt="Logo"></li>
					<li>E - <?php echo $munimji_settings_business['email']; ?></li>
					<li>W - <?php echo $munimji_settings_business['website']; ?></li>
					<li>
						<?php echo $munimji_settings_business['address_1']; ?><br />
						<?php echo $munimji_settings_business['address_2'] . ' ' . $munimji_settings_business['city'] . '-' . $munimji_settings_business['zip']; ?><br />
						<?php //echo $munimji_settings_business['state'] . ', ' . $munimji_settings_business['country']; ?>
					</li>
				</ul>
			</div>
			<div id="note" class="float-right">
				<h1><img id="heart" src="img/heart.png" alt="heart">Thank You</h1>
			</div>
		</div>

	</div>
</body>
</html>
