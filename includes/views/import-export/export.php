<?php
/**
 * Settings export.
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
<div id="munim-export" class="tw-w-full md:tw-w-1/2 tw-px-2 tw-flex tw-flex-col">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">Export Settings</h2>
	<div class="tw-flex tw-flex-col tw-flex-1 tw-flex-wrap tw-bg-white tw-border tw-border-gray-300 tw-p-4">
		<p class="mb-4">
			Export settings to `.json` file for migrating settings to other munim setup.
		</p>
		<form method="post">
			<input type="hidden" name="munim_action" value="export_settings">
			<?php wp_nonce_field( 'munim_export_nonce', 'munim_export_nonce' ); ?>
			<input class="tw-px-4 tw-py-2 tw-cursor-pointer tw-bg-blue-500 tw-text-white tw-rounded" id="munim_export" name="munim_export" type="submit" value="Export">
		</form>
	</div>
</div>
