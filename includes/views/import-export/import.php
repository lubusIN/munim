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
<div id="munim-import" class="tw-w-full md:tw-w-1/2 tw-px-2 tw-mb-4 md:tw-mb-0 tw-flex tw-flex-col">
	<h2 class="tw-font-bold tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-b-0 tw-border-gray-300">Import Settings</h2>
	<div class="tw-flex tw-flex-col tw-flex-wrap tw-bg-white tw-border tw-border-gray-300 tw-p-4 tw-flex tw-flex-1">
		<p class="mb-4">
			Import setting from `.json` file exported from other munim setup.
		</p>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="munim_action" value="import_settings">
			<input type="file" name="munim_import_file" accept="application/json" required>
			<?php wp_nonce_field( 'munim_import_nonce', 'munim_import_nonce' ); ?>
			<input class="tw-px-4 tw-py-2 tw-mt-2 md:tw-mt-2 tw-cursor-pointer tw-bg-blue-500 tw-text-white tw-rounded" id="munim_import" name="munim_import" type="submit" value="Import">
		</form>
	</div>
</div>
