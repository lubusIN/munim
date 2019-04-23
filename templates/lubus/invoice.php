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

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Invoice</title>
</head>
<body>
	<div id="invoice">

	<div id="meta">
		<div id="header">
			<div id="logo">
				Logo <?php echo $myID; ?>
			</div>

			<div id="info">
				Tax Invoice
				#1001
				1/01/2019
			</div>
		</div>

		<div id="details">
			<div id="business">
				Company Details
			</div>

			<div id="client">
				Clinet Details
			</div>
		</div>
	</div>

		<div id="items">
			<table>
				<thead>
					<tr>
						<th>Service</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Server</td>
						<td>5,000</td>
					</tr>
					<tr>
						<td>Webdesign</td>
						<td>10,000</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="taxes">
			<table>
				<tbody>
					<tr>
						<td>CGST(9%)</td>
						<td>9,00</td>
					</tr>
					<tr>
						<td>SGST(9%)</td>
						<td>9,00</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="footer">
			Footer
		</div>
	</div>

</body>
</html>
