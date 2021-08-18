<?php

/**
 *	Blocks Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div id="yith-wapo-block-<?php echo $block->id; ?>" class="yith-wapo-block">

	<?php foreach ( $addons as $key => $addon ) :
		if ( $addon->visibility == 1 && yith_wapo_is_addon_type_available( $addon->type ) ) :

			// Display settings
			$addon_title = $addon->get_setting('title');
			$addon_show_image = $addon->get_setting('show_image');
			$addon_image = $addon->get_setting('image');
			$addon_image_replacement = $addon->get_setting('image_replacement');
			$addon_options_images_position = $addon->get_setting('options_images_position');

			if ( get_option( 'yith_wapo_show_in_toggle' ) == 'yes' ) {
				$toggle_addon = 'toggle';
				$toggle_default = get_option( 'yith_wapo_show_toggle_opened' ) == 'yes' ? 'default-open' : 'default-closed';
			} else {
				$toggle_addon = $addon->get_setting('show_as_toggle') == 'yes' ? 'toggle' : '';
				$toggle_default = $addon->get_setting('show_as_toggle') == 'open' ? 'default-open' : 'default-closed';
			}

			$hide_option_images = $addon->get_setting('hide_options_images') == 'yes' ? true : false;
			$hide_option_label = $addon->get_setting('hide_options_label') == 'yes' ? true : false;
			$hide_option_prices = $addon->get_setting('hide_options_prices') == 'yes' ? true : false;
			
			// Layout
			$options_per_row = $addon->get_setting( 'options_per_row', 1 );
			$show_in_a_grid = $addon->get_setting('show_in_a_grid') == 'yes' ? true : false;
			$options_width = $addon->get_setting( 'options_width', 100 );
			$options_width_css = $show_in_a_grid ? 'width: ' . $options_width . '%;' : '';

			// Advanced settings
			$first_options_selected = $addon->get_setting('first_options_selected');
			$first_free_options = $addon->get_setting('first_free_options');
			$selection_type = $addon->get_setting('selection_type');
			$enable_min_max = $addon->get_setting('enable_min_max');
			$min_max_rule = $addon->get_setting('min_max_rule');
			$min_max_value = $addon->get_setting('min_max_value');
			$min_max_values = array( 'min' => '', 'max' => '', 'exa' => '' );
			if ( $enable_min_max == 'yes' && is_array( $min_max_rule ) ) {
				for ( $y = 0; $y < count( $min_max_rule ); $y++ ) {
					$min_max_values[ $min_max_rule[ $y ] ] = $min_max_value[ $y ];
				}
			}

			// Conditional logic
			$conditional_logic = $addon->get_setting('enable_rules');
			$conditional_logic_class = $conditional_logic == 'yes' ? 'conditional_logic' : '';
			$conditional_logic_display = $addon->get_setting('conditional_logic_display');
			$conditional_logic_display_if = $addon->get_setting('conditional_logic_display_if');
			$conditional_rule_addon = (array) $addon->get_setting('conditional_rule_addon');
			$conditional_rule_addon_is = (array) $addon->get_setting('conditional_rule_addon_is');

			?>

			<div id="yith-wapo-addon-<?php echo $addon->id; ?>"
				class="yith-wapo-addon yith-wapo-addon-type-<?php echo $addon->type;?> <?php echo $toggle_addon; ?> <?php echo $conditional_logic_class; ?>"
				data-min="<?php echo $min_max_values['min']; ?>"
				data-max="<?php echo $min_max_values['max']; ?>"
				data-exa="<?php echo $min_max_values['exa']; ?>"
				<?php if ( $conditional_logic == 'yes' ) : ?>
				data-addon_id="<?php echo $addon->id; ?>"
				data-conditional_logic_display="<?php echo $conditional_logic_display; ?>"
				data-conditional_logic_display_if="<?php echo $conditional_logic_display_if; ?>"
				data-conditional_rule_addon="<?php echo implode( '|', $conditional_rule_addon ); ?>"
				data-conditional_rule_addon_is="<?php echo implode( '|', $conditional_rule_addon_is ); ?>"
				<?php endif; ?>
				style="<?php
					echo 'background-color: ' . $style_addon_background . ';';
					echo ' padding: ' . $style_addon_padding['top'] . 'px ' . $style_addon_padding['right'] . 'px ' . $style_addon_padding['bottom'] . 'px ' . $style_addon_padding['left'] . 'px;';
					echo $conditional_logic == 'yes' ? ' display: none;' : '';
					?>">

				<?php if ( $addon_show_image == 'yes' && $addon_image != '' ) : ?>
					<div class="title-image">
						<img src="<?php echo $addon_image; ?>">
					</div>
				<?php endif; ?>

				<?php if ( $addon_title != '' ) : ?>
					<<?php echo $style_addon_titles; ?> class="wapo-block-title"><?php echo $addon_title; ?></<?php echo $style_addon_titles; ?>>
				<?php endif; ?>

				<?php

					if ( $addon->type == 'html_heading' || $addon->type == 'html_separator' || $addon->type == 'html_text' ) {
						include YITH_WAPO_DIR . '/templates/front/addons/' . $addon->type . '.php';
					} else {

						echo '<div class="options ' . $toggle_default . ' per-row-' . $options_per_row . ( $show_in_a_grid ? ' grid' : '' ) . '">';

						if ( $addon->type == 'select' ) {
							echo '<select id="yith-wapo-' . $addon->id . '" name="yith_wapo[][' . $addon->id . ']" data-addon-id="' . $addon->id . '" style="' . $options_width_css . '">
								<option value="">' . __( 'Select an option', 'yith-woocommerce-product-add-ons' ) . '</option>';
						}

						$options_total = is_array( $addon->options ) ? sizeof( array_values( $addon->options )[0] ) : 1;
						for ( $x = 0; $x < $options_total; $x++ ) {
							if ( file_exists( YITH_WAPO_DIR . '/templates/front/addons/' . $addon->type . '.php' ) ) {

								$option_image = $addon->get_option( 'image', $x );
								$option_description = $addon->get_option( 'description', $x );
								$price_method = $addon->get_option( 'price_method', $x );
								$price_type = $addon->get_option( 'price_type', $x );
								$price = $addon->get_option( 'price', $x );
								$price_sale = $addon->get_option( 'price_sale', $x);
								if ( $price_method == 'free' ) {
									$price = '0';
									$price_sale = '0';
								} else if ( $price_method == 'decrease' ) {
									$price = $price > 0 ? - $price : 0;
									$price_sale = '0';
								} else if ( $price_method == 'product' ) {
									$price = $price > 0 ? $price : 0;
									$price_sale = '0';
								} else {
									$price = $price > 0 ? $price : '0';
									$price_sale = $price_sale > 0 ? $price_sale : '0';
								}

								include YITH_WAPO_DIR . '/templates/front/addons/' . $addon->type . '.php';
							}
						}

						if ( $addon->type == 'select' ) {
							echo '</select>';
						}

						echo '<div class="clear"></div>';

						if ( $enable_min_max == 'yes' ) : ?>
							<small class="min-error" style="color: #f00; padding: 5px 0px; display: none;">
								<?php echo __( 'Please select', 'yith-woocommerce-product-add-ons' ); ?>
								<span class="min-error-an" style="display: none;"><?php echo __( 'an', 'yith-woocommerce-product-add-ons' ); ?></span>
								<span class="min-error-qty" style="display: none;"></span>
								<span class="min-error-option" style="display: none;"><?php echo __( 'option', 'yith-woocommerce-product-add-ons' ); ?></span>
								<span class="min-error-options" style="display: none;"><?php echo __( 'options', 'yith-woocommerce-product-add-ons' ); ?></span>
							</small>
						<?php endif;

						echo '</div>';
						
					}

				?>

			</div>

		<?php endif; ?>
	<?php endforeach; ?>

</div>
