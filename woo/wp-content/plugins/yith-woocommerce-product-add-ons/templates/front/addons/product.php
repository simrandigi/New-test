<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$option_product = $addon->get_option( 'product', $x );
$_product = wc_get_product( $option_product );
$_product_name = $_product->get_title();
$_product_price = $_product->get_price();
$_product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $option_product ), 'single-post-thumbnail' );

$selected = $addon->get_option( 'default', $x ) == 'yes' ? 'selected' : '';
$checked = $addon->get_option( 'default', $x ) == 'yes' ? 'checked="checked"' : '';

$option_price = $addon->get_option_price( $x );
$option_price_html = '';
if ( $addon->get_option( 'price_method', $x ) == 'product' ) {
	$option_price = $_product_price;
	$option_price = $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() );
	$option_price_html = $addon->get_setting('hide_products_prices') != 'yes' ? '<small class="option-price">' . wc_price( $option_price ) . '</small>' : '';

} elseif ( $addon->get_option( 'price_method', $x ) == 'discount' ) {
	$option_price = $_product_price;
	$option_discount_value = floatval( $addon->get_option( 'price', $x ) );
	$price_sale = $option_price - $option_discount_value;
	$option_price = $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() );
	if ( $addon->get_option( 'price_type', $x ) == 'percentage' ) {
		$price_sale = $option_price - ( ( $option_price / 100 ) * $option_discount_value );
	}
	$option_price_html = $addon->get_setting('hide_products_prices') != 'yes' ?
		'<small class="option-price"><del>' . wc_price( $option_price ) . '</del> ' . wc_price( $price_sale ) . '</small>' : '';
} else {
	$option_price_html = $addon->get_option_price_html( $x );
}

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>"
	class="yith-wapo-option selection-<?php echo $selection_type; ?>
	<?php echo $selected; ?>"
	data-replace-image="<?php if ( $addon_image_replacement == 'addon' ) { echo $addon_image; } else if ( $addon_image_replacement == 'options' ) { echo $addon->get_option( 'image', $x ); } ?>"
	>

	<?php if ( $addon_options_images_position == 'left' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<input type="checkbox"
		id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>"
		class="yith-proteo-standard-checkbox"
		name="yith_wapo[][<?php echo $addon->id . '-' . $x; ?>]"
		value="<?php echo 'product-' . $_product->get_id() . '-1'; ?>"
		data-price="<?php echo $option_price; ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		<?php echo $checked; ?>
		style="display: none;">

	<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>" style="<?php echo $options_width_css; ?>">
    	<img src="<?php  echo $_product_image[0]; ?>" data-id="<?php echo $option_product; ?>">
    	<div>

    		<!-- PRODUCT NAME -->
			<?php echo $_product_name; ?>

			<?php
				$stock_status = $_product->is_in_stock() ? __( 'in stock', 'yith-woocommerce-product-add-ons' ) : __( 'out of stock', 'yith-woocommerce-product-add-ons' );
				$stock_qty = $_product->get_manage_stock() ? $_product->get_stock_quantity() : false;
			?>

			<?php // echo $_product->get_stock_quantity(); ?>

			<br />

			<!-- PRICE -->
			<?php echo ! $hide_option_prices ? $option_price_html : ''; ?>

			<?php if ( $addon->get_setting('show_add_to_cart') == 'yes' ) : ?>
				<div class="option-add-to-cart">
					<a href="<?php echo $_product->add_to_cart_url(); ?>" class="button add_to_cart_button">
						<?php echo __( 'Add to cart', 'yith-woocommerce-product-add-ons' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</label>

	<?php if ( $addon_options_images_position == 'right' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
			<!--<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/description-icon.png">-->
		</span>
	<?php endif; ?>

	<?php if ( $addon_options_images_position == 'above' ) { include YITH_WAPO_DIR . '/templates/front/option-image.php'; } ?>

	<?php if ( $option_description != '' ) : ?>
		<p class="description">
			<?php echo $option_description; ?>
		</p>
	<?php endif; ?>

</div>