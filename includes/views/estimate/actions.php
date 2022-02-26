<?php
/**
 * Estimate actions View.
 *
 * @author  Ajit Bohra <ajit@lubus.in>
 * @license MIT
 *
 * @see   https://www.munim.com/
 *
 * @copyright 2019 LUBUS
 * @package   Munim
 */

use LubusIN\Munim\Estimates;

global $post;
?>

<div class="tw-flex tw-justify-center tw-mt-5 tw-mb-3 tw-flex-1">
	<a
		class="tw-flex tw-rounded-l tw-shadow tw-cursor-pointer tw-border tw-border-gray-400 tw-bg-gray-200 tw-px-3 tw-py-2"
		href="<?php echo esc_attr( Estimates::get_url( 'view' ) ); ?>"
		target="_blank">
		<svg class="tw-fill-current tw-w-3 tw-mr-1" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
				<g id="icon-shape">
					<path d="M4,18 L4,2 L12,2 L12,6 L16,6 L16,18 L4,18 Z M2,19 L2,0 L3,0 L12,0 L14,0 L18,4 L18,6 L18,20 L17,20 L2,20 L2,19 Z" id="Combined-Shape"></path>
				</g>
			</g>
		</svg>
		<?php esc_html_e( 'View', 'munim' ); ?>
	</a>
	<a
		class="tw-flex tw-content-center tw-shadow tw-cursor-pointer tw-border-t tw-border-b tw-border-gray-400 tw-bg-gray-200 tw-px-3 tw-py-2"
		href="<?php echo esc_attr( Estimates::get_url( 'download' ) ); ?>">
		<svg class="tw-fill-current tw-w-3 tw-mr-1" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
				<g id="icon-shape">
					<path d="M13,8 L13,2 L7,2 L7,8 L2,8 L10,16 L18,8 L13,8 Z M0,18 L20,18 L20,20 L0,20 L0,18 Z" id="Combined-Shape"></path>
				</g>
			</g>
		</svg>
		<?php esc_html_e( 'Download', 'munim' ); ?>
	</a>
	<a
		class="tw-flex tw-content-center tw-shadow tw-cursor-pointer tw-rounded-r tw-border tw-border-gray-400 tw-bg-gray-200 tw-text-gray-800 tw-px-3 tw-py-2"
		href="<?php echo esc_attr( Estimates::get_url( 'email' ) ); ?>">
		<svg class="tw-fill-current tw-w-3 tw-mr-1" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
				<g id="icon-shape">
					<path d="M14.8780488,10.097561 L20,14 L20,16 L13.627451,11.0980392 L10,14 L6.37254902,11.0980392 L0,16 L0,14 L5.12195122,10.097561 L0,6 L0,4 L10,12 L20,4 L20,6 L14.8780488,10.097561 Z M18.0092049,2 C19.1086907,2 20,2.89451376 20,3.99406028 L20,16.0059397 C20,17.1072288 19.1017876,18 18.0092049,18 L1.99079514,18 C0.891309342,18 0,17.1054862 0,16.0059397 L0,3.99406028 C0,2.8927712 0.898212381,2 1.99079514,2 L18.0092049,2 Z" id="Combined-Shape"></path>
				</g>
			</g>
		</svg>
		<?php esc_html_e( 'Email', 'munim' ); ?>
	</a>
</div>
