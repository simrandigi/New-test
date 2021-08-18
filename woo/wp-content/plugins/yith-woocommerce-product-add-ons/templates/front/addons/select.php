<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$selected = $addon->get_option( 'default', $x ) == 'yes' ? 'selected="selected"' : '';

?>

<!--<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option">-->

	<option value="<?php echo $x; ?>" <?php echo $selected; ?>
		data-price="<?php echo $addon->get_option_price( $x ); ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		data-replace-image="<?php
			if ( $addon_image_replacement == 'addon' ) { echo $addon_image; }
			else if ( $addon_image_replacement == 'options' ) { echo $addon->get_option( 'image', $x ); }
		?>">
		<?php echo ! $hide_option_label ? $addon->get_option( 'label', $x ) : ''; ?>
		<?php echo ! $hide_option_prices ? $addon->get_option_price_html( $x ) : ''; ?>
	</option>

<!-- </div> -->