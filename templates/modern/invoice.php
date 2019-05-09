<?php
/**
 * Modern - Munim Invoice Template.
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
$munim_settings_bank         = get_option( 'munim_settings_bank', [] );
$munim_settings_invoice_info = $munim_settings_invoice['info'];

// Invoice Data.
$invoice_id          = $_GET['munim_invoice_id'];
$invoice_name        = get_the_title( $invoice_id );
$invoice_data        = Helpers::array_shift( get_post_meta( $invoice_id ) );
$invoice_client_id   = $invoice_data['munim_invoice_client_id'];
$invoice_client_data = Helpers::array_shift( get_post_meta( $invoice_client_id ) );
$invoice_client_info = isset( $invoice_client_data['munim_client_additional_info'] ) ? maybe_unserialize( $invoice_client_data['munim_client_additional_info'] ) : false;
$invoice_client_name = get_the_title( $invoice_client_id );
$invoice_items       = maybe_unserialize( $invoice_data['munim_invoice_items'] );
$invoice_currency    = $invoice_client_data['munim_client_currency'];
$invoice_tax_items   = isset( $invoice_data['munim_invoice_taxes'] ) ? maybe_unserialize( $invoice_data['munim_invoice_taxes'] ) : false;
$invoice_logo        = get_attached_file( $munim_settings_business['logo_id'] );
$invoice_icon        = get_attached_file( $munim_settings_business['secondary_logo_id'] );

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
		<div id="header" class="clear-both">
			<img id="brand-header" src="img/header.jpeg" alt="Header">
			<div id="header-content">
				<img id="brand-logo" src="<?php echo $invoice_logo; ?>" alt="IDL Logo">
				<div id="business-address">
					<?php echo $munim_settings_business['address_1'] ?>
					<?php echo $munim_settings_business['address_2'] ?>
					<?php echo $munim_settings_business['city'] ?> -
					<?php echo $munim_settings_business['zip'] ?>
					<?php echo $munim_settings_business['country'] ?>
				</div>
				<div id="business-contact">
					<?php echo $munim_settings_business['contact'] ?>
					<span class="circle-sep">•</span>
					<?php echo $munim_settings_business['email'] ?>
				</div>
			</div>
		</div>
		<div id="body" class="clear-both">
			<div id="invoice-info" class="width-100 clear-both">
				<div id="invoice-number" class="float-left width-60">
					Invoice # <?php echo $invoice_data['munim_invoice_number']; ?>
				</div>
				<div id="invoice-date" class="float-right width-40 text-right">
					Date: <?php echo $invoice_data['munim_invoice_date']; ?>
				</div>
			</div>

			<div id="client-info" class="width-100 clear-both">
				<div id="client-details" class="float-left width-60">
					To,
					<ul class="data-list">
						<li><?php echo $invoice_client_name; ?></li>
						<li>
							<?php echo $invoice_client_data['munim_client_address_1']; ?><br />
							<?php echo $invoice_client_data['munim_client_address_2']; ?><br />
							<?php echo $invoice_client_data['munim_client_city']; ?>,
							<?php echo $invoice_client_data['munim_client_state']; ?>
							<?php echo $invoice_client_data['munim_client_zip']; ?>
						</li>
						<?php
						if ( $invoice_client_info ) {
							foreach ( $invoice_client_info as $info ) { ?>
								<li><?php echo $info['name'] ?>: <?php echo $info['value']; ?></li>
								<?php
							}
						}
						?>
					</ul>
				</div>
				<div id="vendor-info" class="float-right text-right width-40">
				<?php if ( $munim_settings_invoice_info ) { ?>
					<ul class="data-list">
						<?php foreach ( $munim_settings_invoice_info as $info ) { ?>
							<li><?php echo $info['name'] . ': ' . $info['value']; ?></li>
						<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>

			<div id="project-title" class="clear-both">
			<br/>
				<strong>Project:</strong> <?php echo $invoice_name; ?>
			</div>

			<table id="invoice-items">
				<thead>
					<tr class="border-bottom padding-top-0">
						<td with="5%">
							<strong>#</strong>
						</td>
						<td width="75%" class="border-left">
							<strong>Description</strong>
						</td>
						<td width="20%" class="border-left">
							<strong>Cost</strong>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $invoice_items as $item ) {
						$i++;
						?>
						<tr>
							<td>
								<?php echo$i; ?>
							</td>
							<td class="border-left">
								<?php echo $item['name']; ?>
							</td>
							<td class="border-left">
								<?php echo $item['amount']; ?>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<td>

						</td>
						<td class="border-left">

						</td>
						<td class="border-left" style="background-color: #e9e9e9">
							<?php echo $invoice_data['munimji_invoice_total']; ?>
						</td>
					</tr>
				</tbody>
			</table>

			<div id="footer">
				<strong>Bank Details</strong>
				<ul class="data-list">
				<?php
				if ( $munim_settings_bank ) {
					foreach ( $munim_settings_bank['info'] as $info ) { ?>
						<li><?php echo $info['name']; ?>: <?php echo $info['value']; ?></li>
						<?php
					}
				}
				?>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>
