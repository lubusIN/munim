<?php
/**
 * Dashboard Trend Chart.
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
<div id="munim-monthly-trend" class="tw-w-full md:tw-w-1/2 xl:tw-w-1/3 tw-px-2 tw-mt-4 tw-flex tw-flex-col">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">
		<?php esc_html_e( 'Trend', 'munim' ); ?>
	</h2>
	<div class="tw-flex tw-flex-1 tw-flex-wrap tw-bg-white tw-border tw-border-2 tw-border-gray-300 tw-content-center">
		<div id="munim-trend-chart" class="w-full">
			<!-- Chart rendered by apexcharts -->
		</div>
	</div>
</div>
