<?php

/**
 *	Addon Display Options Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="tab-display-settings" style="display: none;">

	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-show-image"><?php echo __( 'Show an image for this add-on', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-show-image',
				'class'	=> 'enabler',
				'name'	=> 'addon_show_image',
				'type'	=> 'onoff',
				'value'	=> $addon->get_setting('show_image'),
			), true ); ?>
			<span class="description"><?php echo __( 'Enable to show an additional image or icon near the title.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-addon-show-image" style="display: none;">
		<label for="addon-image"><?php echo __( 'Add-on image', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-image',
				'name'	=> 'addon_image',
				'type'	=> 'upload',
				'value'	=> $addon->get_setting('image'),
			), true ); ?>
		</div>
	</div>
	<!-- End option field -->

	<?php if ( $addon_type != 'product' ) : ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label><?php echo __( 'Product image replacing options', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php
				yith_plugin_fw_get_field( array(
					'id'	=> 'addon-image-replacement',
					'name'	=> 'addon_image_replacement',
					'type'	=> 'select',
					'value'	=> $addon->get_setting('image_replacement', 'no'),
					'options'	=> array(
						'no'		=> __( 'Don\'t replace the image', 'yith-woocommerce-product-add-ons' ),
						'addon'		=> __( 'Replace with add-on image', 'yith-woocommerce-product-add-ons' ),
						'options'	=> __( 'Replace with options images', 'yith-woocommerce-product-add-ons' ),
					),
				), true );
				?>
				<span class="description"><?php echo __( 'Choose to replace the default product image when an option is selected and which image to use to replace it.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<?php if ( $addon_type != 'label' ) : ?>
			<!-- Option field -->
			<div class="field-wrap">
				<label><?php echo __( 'Options images position', 'yith-woocommerce-product-add-ons' ); ?></label>
				<div class="field">
					<?php yith_plugin_fw_get_field( array(
						'id'	=> 'addon-options-images-position',
						'name'	=> 'addon_options_images_position',
						'type'	=> 'select',
						'value'	=> $addon->get_setting('options_images_position', 'above'),
						'options'	=> array(
							'above'		=> __( 'Above label', 'yith-woocommerce-product-add-ons' ),
							'under'		=> __( 'Under label', 'yith-woocommerce-product-add-ons' ),
							'left'		=> __( 'Left side', 'yith-woocommerce-product-add-ons' ),
							'right'		=> __( 'Right side', 'yith-woocommerce-product-add-ons' ),
						),
					), true );
					?>
					<span class="description"><?php echo __( 'Choose the position of the options images.', 'yith-woocommerce-product-add-ons' ); ?></span>
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

	<?php endif; ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-show-as-toggle"><?php echo __( 'Show as toggle', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-show-as-toggle',
				'name'	=> 'addon_show_as_toggle',
				'type'	=> 'select',
				'value'	=> $addon->get_setting('show_as_toggle', 'no'),
				'options'	=> array(
					'no'		=> __( 'No', 'yith-woocommerce-product-add-ons' ),
					'open'		=> __( 'Yes, with toggle opened by default', 'yith-woocommerce-product-add-ons' ),
					'closed'	=> __( 'Yes, with toggle closed by default', 'yith-woocommerce-product-add-ons' ),
				),
			), true ); ?>
			<span class="description"><?php echo __( 'Choose whether to show options in a toggle section.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<?php if ( $addon_type == 'product' ) : ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-hide-products-prices"><?php echo __( 'Hide product prices', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-hide-products-prices',
					'name'	=> 'addon_hide_products_prices',
					'type'	=> 'onoff',
					'value'	=> $addon->get_setting('hide_products_prices', 'no'),
				), true ); ?>
				<span class="description"><?php echo __( 'Enable if you want to hide the product prices.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<div class="field-wrap">
			<label for="addon-show-add-to-cart"><?php echo __( 'Show "Add to cart" button', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-show-add-to-cart',
					'name'	=> 'addon_show_add_to_cart',
					'type'	=> 'onoff',
					'value'	=> $addon->get_setting('show_add_to_cart', 'no'),
				), true ); ?>
				<span class="description"><?php echo __( 'Enable if you want to show the "Add to cart" button.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

	<?php else : ?>

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-hide-options-images"><?php echo __( 'Hide options images', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-hide-options-images',
					'name'	=> 'addon_hide_options_images',
					'type'	=> 'onoff',
					'value'	=> $addon->get_setting('hide_options_images'),
				), true ); ?>
				<span class="description"><?php echo __( 'Enable if you want to hide the options images.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-hide-options-label"><?php echo __( 'Hide options labels', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-hide-options-label',
					'name'	=> 'addon_hide_options_label',
					'type'	=> 'onoff',
					'value'	=> $addon->get_setting('hide_options_label'),
				), true ); ?>
				<span class="description"><?php echo __( 'Enable if you want to hide the options labels.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

		<!-- Option field -->
		<div class="field-wrap">
			<label for="addon-hide-options-prices"><?php echo __( 'Hide options prices', 'yith-woocommerce-product-add-ons' ); ?></label>
			<div class="field">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-hide-options-prices',
					'name'	=> 'addon_hide_options_prices',
					'type'	=> 'onoff',
					'value'	=> $addon->get_setting('hide_options_prices'),
				), true ); ?>
				<span class="description"><?php echo __( 'Enable if you want to hide the options prices.', 'yith-woocommerce-product-add-ons' ); ?></span>
			</div>
		</div>
		<!-- End option field -->

	<?php endif; ?>

	<h3><?php echo __( 'Options layout', 'yith-woocommerce-product-add-ons' ); ?></h3>

	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-options-per-row"><?php echo __( 'Options per row', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-options-per-row',
				'name'	=> 'addon_options_per_row',
				'type'	=> 'slider',
				'min'	=> 1,
				'max'	=> 10,
				'step'	=> 1,
				'value'	=> $addon->get_setting('options_per_row', 5),
			), true ); ?>
			<span class="description">
				<?php echo __( 'Enter how many options to display for each row.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-show-in-a-grid"><?php echo __( 'Use a grid layout', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-show-in-a-grid',
				'class'	=> 'enabler',
				'name'	=> 'addon_show_in_a_grid',
				'type'	=> 'onoff',
				'value'	=> $addon->get_setting('show_in_a_grid'),
			), true ); ?>
			<span class="description"><?php echo __( 'Enable to adjust the options in a grid based on the page width.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-addon-show-in-a-grid" style="display: none;">
		<label for="addon-options-width"><?php echo __( 'Options width', 'yith-woocommerce-product-add-ons' ); ?> (%)</label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-options-width',
				'name'	=> 'addon_options_width',
				'type'	=> 'slider',
				'min'	=> 1,
				'max'	=> 100,
				'step'	=> 1,
				'value'	=> $addon->get_setting('options_width', 100),
			), true ); ?>
			<span class="description">
				<?php echo __( 'Select the options field size.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<!--
	<div class="field-wrap">
		<label for="addon-show-quantity-selector"><?php echo __( 'Show quantity selector', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-show-quantity-selector',
				'name'	=> 'addon_show_quantity_selector',
				'type'	=> 'onoff',
				'value'	=> $addon->get_setting('show_quantity_selector'),
			), true ); ?>
			<span class="description"><?php echo __( 'Enable if you want to show a quantity selector for this option', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	-->
	<!-- End option field -->

</div>