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
<div id="munim-export" class="w-full md:w-1/2 px-2 flex flex-col">
	<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Export Settings</h2>
	<div class="flex flex-col flex-1 flex-wrap bg-white border border-gray-300 p-4">
		<p class="mb-4">
			Export settings to `.json` file for migrating settings to other munim setup.
		</p>
		<form method="post">
			<input type="hidden" name="munim_action" value="export_settings">
			<?php wp_nonce_field( 'munim_export_nonce', 'munim_export_nonce' ); ?>
			<input class="px-4 py-2 cursor-pointer bg-blue-500 text-white rounded" id="munim_export" name="munim_export" type="submit" value="Export">
		</form>
	</div>
</div>
