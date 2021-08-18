<?php

/**
 *	Option Image Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( $addon->get_option( 'show_image', $x ) && $option_image != '' && $hide_option_images != 'yes' && $setting_hide_images != 'yes' ) : ?>

	<div class="image position-<?php echo $addon_options_images_position; ?>">
		<img src="<?php echo $option_image; ?>">
	</div>

<?php endif; ?>