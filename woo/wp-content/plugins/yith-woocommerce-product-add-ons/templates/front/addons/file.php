<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$required = $addon->get_option( 'required', $x ) == 'yes';

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option">

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
	<input type="hidden"
		id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>"
		class="option"
		name="yith_wapo[][<?php echo $addon->id . '-' . $x; ?>]"
		value=""
		data-price="<?php echo $addon->get_option_price( $x ); ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		<?php echo $required ? 'required' : '' ?>>
	
	<input id="yith-wapo-file-<?php echo $addon->id; ?>-<?php echo $x; ?>" type="file" class="file">

	<div class="yith-wapo-ajax-uploader" style="<?php echo $options_width_css; ?>">
		<?php echo get_option( 'yith_wapo_uploads_text_to_show' ); ?>
		<?php if ( get_option( 'yith_wapo_uploads_link_to_show' ) == 'text' ) : ?>
			<a class="link"><?php echo __( 'browse', 'yith-woocommerce-product-add-ons' ); ?></a>
		<?php else : ?>
			<span class="button"><?php echo __( 'UPLOAD', 'yith-woocommerce-product-add-ons' ); ?></span>
		<?php endif; ?>
	</div>
	<div class="yith-wapo-uploaded-file" style="<?php echo $options_width_css; ?> display: none;">
		<span class="info"></span>
		<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/delete.svg" class="remove">
	</div>

	<?php if ( $required ) : ?>
		<small class="required-error" style="color: #f00; padding: 5px 0px; display: none;"><?php echo __( 'This option is required.', 'yith-woocommerce-product-add-ons' ); ?></small>
	<?php endif; ?>

	<!-- TOOLTIP -->
	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
		</span>
	<?php endif; ?>

	<!-- DESCRIPTION -->
	<?php if ( $option_description != '' ) : ?>
		<p class="description"><?php echo $option_description; ?></p>
	<?php endif; ?>

</div>
