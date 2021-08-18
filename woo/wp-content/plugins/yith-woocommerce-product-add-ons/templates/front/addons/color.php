<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$required = $addon->get_option( 'required', $x ) == 'yes';
$checked = $addon->get_option( 'default', $x ) == 'yes';
$selected = $checked ? 'selected' : '';
$color_type = $addon->get_option( 'color_type', $x, 'single' );

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option selection-<?php echo $selection_type; ?> <?php echo $selected; ?>" data-replace-image="<?php
	if ( $addon_image_replacement == 'addon' ) { echo $addon_image; }
	else if ( $addon_image_replacement == 'options' ) { echo $addon->get_option( 'image', $x ); }
	?>">

	<input type="checkbox"
		id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>"
		class="yith-proteo-standard-checkbox"
		name="yith_wapo[][<?php echo $addon->id . '-' . $x; ?>]"
		value="<?php echo $addon->get_option( 'label', $x ); ?>"
		data-price="<?php echo $addon->get_option_price( $x ); ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		<?php echo $required ? 'required' : '' ?>
		<?php echo $checked ? 'checked="checked"' : ''; ?>
		style="display: none;">

	<!-- UNDER IMAGE -->
	<?php if ( $addon_options_images_position == 'above' || $addon_options_images_position == 'left' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<!-- LABEL -->
	<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>">
		<span class="color" style="background: <?php
				if ( $color_type == 'double' ) {
					echo '-webkit-linear-gradient( left, ' . $addon->get_option( 'color', $x ) . ' 50%, ' . $addon->get_option( 'color_b', $x ) . ' 50%)';
				} else { echo $addon->get_option( 'color', $x ); }
			?>;">
			<?php if ( $color_type == 'image' ) { echo '<img src="' . $addon->get_option( 'color_image', $x ) . '">'; } ?>
		</span>
		<small><?php echo ! $hide_option_label ? $addon->get_option( 'label', $x ) : ''; ?></small>
		<?php echo ! $hide_option_prices ? $addon->get_option_price_html( $x ) : ''; ?>
	</label>

	<!-- REQUIRED -->
	<?php if ( $required ) : ?>
		<small class="required-error" style="color: #f00; padding: 5px 0px; display: none;"><?php echo __( 'This option is required.', 'yith-woocommerce-product-add-ons' ); ?></small>
	<?php endif; ?>

	<!-- TOOLTIP -->
	<?php if ( get_option( 'yith_wapo_show_tooltips' ) == 'yes' && $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip position-<?php echo get_option( 'yith_wapo_tooltip_position' ); ?>">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
		</span>
	<?php endif; ?>

	<!-- UNDER IMAGE -->
	<?php if ( $addon_options_images_position == 'under' || $addon_options_images_position == 'right' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<!-- DESCRIPTION -->
	<?php if ( $option_description != '' ) : ?>
		<p class="description"><?php echo $option_description; ?></p>
	<?php endif; ?>

</div>
