<?php
use LubusIN\Munim\Helpers;

/**
 * Dashboard.
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
<div class="wrap">
	<h1>Dashboard</h1>

	<div class="flex flex-wrap -mx-2 mt-4">
		<?php
			include '_overview.php';
			include '_status.php';
			include '_recent.php';
			include '_trend.php';
			include '_business.php';
		?>
	</div>
</div>
