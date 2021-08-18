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

$images_position = 'above';
$images_height = '';
$label_position = 'inside';
$label_padding = '';
$label_content_align = '';
$description_position = 'outside';
if ( $addon->get_setting('custom_style') == 'yes' ) {
	$images_position = $addon->get_setting('options_images_position', 'above');
	if ( $addon->get_setting('image_equal_height') == 'yes' ) { $images_height = 'width: auto; max-width: none; height: ' . $addon->get_setting('images_height', 100) . 'px'; }
	$label_position = $addon->get_setting('label_position', 'inside');
	$label_content_align = $addon->get_setting('label_content_align', 'left');
	if ( is_array( $addon->get_setting('label_padding') ) ) {
		$label_padding_dim = $addon->get_setting('label_padding')['dimensions'];
		$label_padding = 'padding: ' . $label_padding_dim['top'] . 'px ' . $label_padding_dim['right'] . 'px ' . $label_padding_dim['bottom'] . 'px ' . $label_padding_dim['left'] . 'px;';
	}
	$description_position = $addon->get_setting('description_position', 'outside');
} else {
	$images_position = $style_images_position;
	if ( $style_images_equal_height == 'yes' ) { $images_height = 'width: auto; max-width: none; height: ' . $style_images_height . 'px'; }
	$label_position = $style_label_position;
	$label_padding = 'padding: ' . $style_label_padding['top'] . 'px ' . $style_label_padding['right'] . 'px ' . $style_label_padding['bottom'] . 'px ' . $style_label_padding['left'] . 'px;';
	$description_position = $style_description_position;
}

$label_price_html = '<div class="label_price">';
$label_price_html .= ! $hide_option_label ? $addon->get_option( 'label', $x ) : '';
$label_price_html .= $required ? ' <span class="required">*</span>' : '';
$label_price_html .= ! $hide_option_prices ? ' ' . $addon->get_option_price_html( $x ) : '';
$label_price_html .= '</div>';

$description_html = $option_description != '' ? '<p class="description">' . $option_description . '</p>' : '';

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>"
	class="yith-wapo-option selection-<?php echo $selection_type; ?>
	<?php echo $selected; ?>"
	data-replace-image="<?php if ( $addon_image_replacement == 'addon' ) { echo $addon_image; } else if ( $addon_image_replacement == 'options' ) { echo $addon->get_option( 'image', $x ); } ?>"
	style="">

	<!-- INPUT -->
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

	<div class="label_container <?php echo $images_position; ?> <?php echo $label_position; ?>" style="display: inline-block; <?php echo $options_width_css; ?>">

		<?php if ( $label_position == 'outside' && $images_position == 'under' ) : ?>
			<?php echo $label_price_html; ?>
		<?php endif; ?>

		<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>" style="width: 100%; <?php echo $label_padding; ?> text-align: <?php echo $label_content_align; ?>;">

			<?php if ( $label_position == 'inside' && $images_position == 'under' ) : ?>
				<?php echo $label_price_html; ?>
			<?php endif; ?>

			<?php if ( $addon->get_option( 'show_image', $x ) && $addon->get_option( 'image', $x ) != '' && $hide_option_images != 'yes' && $setting_hide_images != 'yes' ) : ?>
				<div class="image" style="display: inline-block;">
					<img src="<?php echo $addon->get_option( 'image', $x ); ?>" style="<?php echo $images_height; ?>">
				</div>
			<?php endif; ?>
			
			<?php if ( $label_position == 'inside' && $images_position != 'under' ) : ?>
				<?php echo $label_price_html; ?>
			<?php endif; ?>

			<?php if ( $description_position == 'inside' ) : ?>
				<?php echo $description_html; ?>
			<?php endif; ?>

		</label>

		<?php if ( $label_position == 'outside' && $images_position != 'under' ) : ?>
			<?php echo $label_price_html; ?>
		<?php endif; ?>

		<?php if ( $description_position == 'outside' ) : ?>
			<?php echo $description_html; ?>
		<?php endif; ?>

	</div>

	<?php if ( $required ) : ?>
		<small class="required-error" style="color: #f00; padding: 5px 0px; display: none;"><?php echo __( 'This option is required.', 'yith-woocommerce-product-add-ons' ); ?></small>
	<?php endif; ?>

	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
			<!--<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/description-icon.png">-->
		</span>
	<?php endif; ?>

</div>
