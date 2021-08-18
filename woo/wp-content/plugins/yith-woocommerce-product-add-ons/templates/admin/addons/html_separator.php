<?php

/**
 *	Separator Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="options-editor-color">

	<!--<h3><?php echo __( 'Separator', 'yith-woocommerce-product-add-ons' ); ?></h3>-->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-separator-style"><?php echo __( 'Separator style', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field rule">
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-separator-style',
				'name'	=> 'option_separator_style',
				'type'	=> 'select',
				'value'	=> $addon->get_setting('separator_style'),
				'options'	=> array(
					'simple_border'	=> __( 'Simple Border', 'yith-woocommerce-product-add-ons' ),
					'double_border'	=> __( 'Double Border', 'yith-woocommerce-product-add-ons' ),
					'dotted_border'	=> __( 'Dotted Border', 'yith-woocommerce-product-add-ons' ),
					'dashed_border'	=> __( 'Dashed Border', 'yith-woocommerce-product-add-ons' ),
					'empty_space'	=> __( 'Empty Space', 'yith-woocommerce-product-add-ons' ),
				),
			), true );
			?>
			<span class="description"><?php echo __( 'Choose the separator style.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-separator-width"><?php echo __( 'Width', 'yith-woocommerce-product-add-ons' ); ?> (%)</label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'option-separator-width',
				'name'	=> 'option_separator_width',
				'type'	=> 'slider',
				'min'	=> 1,
				'max'	=> 100,
				'step'	=> 1,
				'value'	=> $addon->get_setting('separator_width', 100),
			), true ); ?>
			<span class="description">
				<?php echo __( 'Set the width value of the separator.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-separator-size"><?php echo __( 'Height', 'yith-woocommerce-product-add-ons' ); ?> (px)</label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'option-separator-size',
				'name'	=> 'option_separator_size',
				'type'	=> 'number',
				'min'	=> 0,
				'value'	=> $addon->get_setting('separator_size', 2),
			), true ); ?>
			<span class="description">
				<?php echo __( 'Set the height value of the separator.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-separator-color" style="<?php echo $addon->get_setting('separator_style') == 'empty_space' ? 'display: none;' : ''; ?>">
		<label for="option-separator-color"><?php echo __( 'Border color', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field rule">
			<?php
			$value = $addon->get_setting( 'separator_color', '#AA0000' );
			yith_plugin_fw_get_field( array(
				'id'			=> 'option-separator-color',
				'name'			=> 'option_separator_color',
				'type'			=> 'colorpicker',
				'alpha_enabled'	=> false,
				'default'		=> '#AA0000',
				'value'			=> $value,
			), true );
			?>
			<span class="description"><?php echo __( 'Set the color for the separator border.', 'yith-woocommerce-product-add-ons' ); ?></span>
		</div>
	</div>
	<!-- End option field -->

</div><!-- #options-editor-color -->
