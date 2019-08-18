<?php
/**
 * Settings import.
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
<div id="munim-import" class="w-full md:w-1/2 px-2">
	<h2 class="font-bold px-4 py-2 bg-white border border-b-0 border-gray-300">Import Settings</h2>
	<div class="flex flex-col flex-wrap bg-white border border-gray-300 p-4">
		<p class="mb-4">
			Import setting from `.json` file exported from other munim setup.
		</p>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="munim_action" value="import_settings">
			<input type="file" name="munim_import_file" accept="application/json" required>
			<?php wp_nonce_field( 'munim_import_nonce', 'munim_import_nonce' ); ?>
			<input class="px-4 py-2 cursor-pointer bg-blue-500 text-white rounded" id="munim_import" name="munim_import" type="submit" value="Import">
		</form>
	</div>
</div>
