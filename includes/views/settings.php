<?php
/**
 * Settings View.
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
<div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
	<?php if ( get_admin_page_title() ) : ?>
		<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
	<?php endif; ?>
	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
			<a class="nav-tab
							<?php
							if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) :
								?>
											nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
		<?php endforeach; ?>
	</h2>
	<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
		<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
		<?php $cmb_options->options_page_metabox(); ?>
		<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
	</form>
</div>
