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

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option"
	data-replace-image="<?php
		if ( $addon_image_replacement == 'addon' ) { echo $addon_image; }
		else if ( $addon_image_replacement == 'options' ) { echo $option_image; }
	?>">

	<!-- LEFT/ABOVE IMAGE -->
	<?php if ( $addon_options_images_position == 'left' || $addon_options_images_position == 'above' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<span class="radiobutton <?php echo $checked ? 'checked' : ''; ?>">

		<!-- INPUT -->
		<input type="radio"
			id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>"
			name="yith_wapo[][<?php echo $addon->id; ?>]"
			value="<?php echo $x; ?>"
			data-price="<?php echo $addon->get_option_price( $x ); ?>"
			data-price-sale="<?php echo $price_sale; ?>"
			data-price-type="<?php echo $price_type; ?>"
			data-price-method="<?php echo $price_method; ?>"
			data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
			data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
			data-addon-id="<?php echo $addon->id; ?>"
			<?php echo $required ? 'required' : '' ?>
			<?php echo $checked ? 'checked="checked"' : ''; ?>>

	</span>

	<!-- RIGHT IMAGE -->
	<?php if ( $addon_options_images_position == 'right' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<!-- LABEL -->
	<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>">
		<?php echo ! $hide_option_label ? $addon->get_option( 'label', $x ) : ''; ?>
		<?php echo $required ? '<span class="required">*</span>' : '' ?>

		<!-- PRICE -->
		<?php echo ! $hide_option_prices ? $addon->get_option_price_html( $x ) : ''; ?>
	</label>

	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
			<!--<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/description-icon.png">-->
		</span>
	<?php endif; ?>

	<!-- UNDER IMAGE -->
	<?php if ( $addon_options_images_position == 'under' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<!-- DESCRIPTION -->
	<?php if ( $option_description != '' ) : ?>
		<p class="description"><?php echo $option_description; ?></p>
	<?php endif; ?>

</div>