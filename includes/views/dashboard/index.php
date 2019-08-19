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
	<h2 class="flex">
		<svg class="fill-current w-6 h-6 mr-2" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
				<g id="icon-shape">
					<path d="M1,10 L4,10 L4,20 L1,20 L1,10 Z M6,0 L9,0 L9,20 L6,20 L6,0 Z M11,8 L14,8 L14,20 L11,20 L11,8 Z M16,4 L19,4 L19,20 L16,20 L16,4 Z" id="Combined-Shape"></path>
				</g>
			</g>
		</svg>
		<?php echo wp_kses_post( get_admin_page_title() ); ?>
	</h2>

	<div class="flex flex-wrap -mx-2 mt-4">
		<?php
			require 'overview.php';
			require 'status.php';
			require 'recent.php';
			require 'trend.php';
			require 'business.php';
		?>
	</div>
</div>
