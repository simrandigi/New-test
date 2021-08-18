<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option quantity">

	<?php if ( $addon->get_option( 'show_image', $x ) && $addon->get_option( 'image', $x ) != '' && $hide_option_images != 'yes' && $setting_hide_images != 'yes' ) : ?>
		<div class="image position-<?php echo $addon_options_images_position; ?>">
			<img src="<?php echo $addon->get_option( 'image', $x ); ?>">
		</div>
	<?php endif; ?>

	<div class="label">
		<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>">

			<!-- LABEL -->
			<?php echo ! $hide_option_label ? $addon->get_option( 'label', $x ) : ''; ?>
			
			<!-- PRICE -->
			<?php echo ! $hide_option_prices ? $addon->get_option_price_html( $x ) : ''; ?>

		</label>
	</div>

	<!-- INPUT -->
	<input type="number"
		id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>"
		name="yith_wapo[][<?php echo $addon->id . '-' . $x; ?>]"
		value=""
		<?php if ( $addon->get_option( 'number_limit', $x ) == 'yes' ) : ?>
			min="<?php echo $addon->get_option( 'number_limit_min', $x ); ?>"
			max="<?php echo $addon->get_option( 'number_limit_max', $x ); ?>"
		<?php endif; ?>
		data-price="<?php echo $addon->get_option_price( $x ); ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		<?php echo $addon->get_option( 'required', $x ) == 'yes' ? 'required' : '' ?>>

	<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>

	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
		</span>
	<?php endif; ?>

	<?php if ( $option_description != '' ) : ?>
		<p class="description">
			<?php echo $option_description; ?>
		</p>
	<?php endif; ?>

</div>