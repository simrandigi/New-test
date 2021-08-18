<?php

/**
 *	Addon Conditional Logic Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$conditional_array = array( 0 => '-' );
$conditional_addons = yith_wapo_get_addons_by_block_id( $block_id );
$total_conditional_addons = sizeof( $conditional_addons );
if ( $total_conditional_addons > 0 ) {
	foreach ( $conditional_addons as $key => $conditional_addon ) {
		if ( $conditional_addon->id != $addon_id ) {
			// $conditional_array[$conditional_addon->id] = $conditional_addon->get_setting('title') . ' (#' . $conditional_addon->id . ')';
			$conditional_array[$conditional_addon->id] = $conditional_addon->get_setting('title');
			$options_total = is_array( $conditional_addon->options ) && isset( array_values( $conditional_addon->options )[0] ) ? sizeof( array_values( $conditional_addon->options )[0] ) : 1;
			for ( $x = 0; $x < $options_total; $x++ ) {
				// include YITH_WAPO_DIR . '/templates/admin/addons/' . $addon_type . '.php';
				// $conditional_array[$conditional_addon->id . '-' . $x] =  '(#' . $conditional_addon->id . ') - ' . __( 'Option', 'yith-woocommerce-product-add-ons' ) . ' #' . ( $x + 1 );
				// $conditional_array[$conditional_addon->id . '-' . $x] =  '- ' . __( 'Option', 'yith-woocommerce-product-add-ons' ) . ' #' . ( $x + 1 );
				if ( isset( $conditional_addon->options['label'][$x] ) ) {
					$option_name = $conditional_addon->options['label'][$x];
					if ( strlen( $option_name ) > 25 ) {
						$option_name = substr( $option_name, 0, 22 ) . '...';
					}
					$conditional_array[$conditional_addon->id . '-' . $x] =  $conditional_addon->get_setting('title') . ' - ' . $option_name;
				}
			}
		}
	}
}

// Get variations
$args = [
	'type'		=> 'variable',
	'status'	=> 'publish',
	'orderby'	=> 'name',
	'order'		=> 'ASC',
	'limit'		=> -1,
];
$all_products = wc_get_products( $args );
foreach ( $all_products as $key => $product ) {
	if ( $product->get_type() == 'variable' ) {

		$variations = $product->get_available_variations();
		$variations_id = wp_list_pluck( $variations, 'variation_id' );

		foreach ( $variations_id as $vkey => $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$conditional_array[ 'v-' . $product->get_id() . '-' . $variation_id ] = '[ ' . __( 'Variation', 'yith-woocommerce-product-add-ons' ) . ' ] ' . strip_tags( $variation->get_formatted_name() );
		}

		/*
		foreach ( $product->get_variation_attributes() as $variations ) {
			foreach ( $variations as $variation ) {
				echo $product->get_title() . " - " . $variation;
			}
		}
		*/
	}
}

?>

<div id="tab-conditional-logic" style="display: none;">
	
	<!-- Option field -->
	<div class="field-wrap">
		<label for="addon-enable-rules"><?php echo __( 'Set conditions to show or hide this set of options', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'addon-enable-rules',
				'name'	=> 'addon_enable_rules',
				'class'	=> 'enabler',
				'type'	=> 'onoff',
				'value'	=> $addon->get_setting('enable_rules'),
			), true ); ?>
			<span class="description">
				<?php echo __( 'Enable to set rules to hide or show the options.', 'yith-woocommerce-product-add-ons' ); ?>
			</span>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-addon-enable-rules" style="display: none;">
		<label for="conditional-logic-rules"><?php echo __( 'Display rules', 'yith-woocommerce-product-add-ons' ); ?></label>

		<div id="conditional-display-rules">
			<div class="field display-rules">
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-conditional-logic-display',
					'name'	=> 'addon_conditional_logic_display',
					'type'	=> 'select',
					'value'	=> $addon->get_setting('conditional_logic_display'),
					'options'	=> array(
						'show'	=> __( 'Show', 'yith-woocommerce-product-add-ons' ),
						'hide'	=> __( 'Hide', 'yith-woocommerce-product-add-ons' ),
					),
				), true ); ?>
				<span><?php echo __( 'this set of options if', 'yith-woocommerce-product-add-ons' ); ?></span>
				<?php yith_plugin_fw_get_field( array(
					'id'	=> 'addon-conditional-logic-display-if',
					'name'	=> 'addon_conditional_logic_display_if',
					'type'	=> 'select',
					'value'	=> $addon->get_setting('conditional_logic_display_if'),
					'options'	=> array(
						'all'	=> __( 'All of these rules', 'yith-woocommerce-product-add-ons' ),
						'any'	=> __( 'Any of these rules', 'yith-woocommerce-product-add-ons' ),
					),
				), true ); ?>
				<span><?php echo __( 'match', 'yith-woocommerce-product-add-ons' ); ?>:</span>
			</div>
		</div>

		<div id="conditional-rules">
			<?php
				$conditional_rule_addon = (array) $addon->get_setting('conditional_rule_addon');
				$conditional_rules_count = count( $conditional_rule_addon );
				for ( $y=0; $y<$conditional_rules_count; $y++ ) :
					$conditional_rule = isset( $conditional_rule_addon[$y] ) ? $conditional_rule_addon[$y] : '';
			?>
				<div class="field rule">
					<?php yith_plugin_fw_get_field( array(
						'id'		=> 'addon-conditional-rule-addon',
						'name'		=> 'addon_conditional_rule_addon[]',
						'type'		=> 'select',
						'class'		=> 'wc-enhanced-select',
						'value'		=> $conditional_rule,
						'options'	=> $conditional_array,
					), true ); ?>
					<span><?php echo __( 'is', 'yith-woocommerce-product-add-ons' ); ?></span>
					<?php yith_plugin_fw_get_field( array(
						'id'	=> 'addon-conditional-rule-addon-is',
						'name'	=> 'addon_conditional_rule_addon_is[]',
						'type'	=> 'select',
						'value'	=> isset( $addon->get_setting('conditional_rule_addon_is')[$y] ) ? $addon->get_setting('conditional_rule_addon_is')[$y] : '',
						'options'	=> array(
							''			=> '-',
							'selected'		=> __( 'Selected', 'yith-woocommerce-product-add-ons' ),
							'not-selected'	=> __( 'Not selected', 'yith-woocommerce-product-add-ons' ),
							'empty'			=> __( 'Empty', 'yith-woocommerce-product-add-ons' ),
							'not-empty'		=> __( 'Not empty', 'yith-woocommerce-product-add-ons' ),
						),
					), true ); ?>
					<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/delete.png" class="delete-rule" style="width: 8px; height: 10px; padding: 0px 10px; cursor: pointer;">
				</div>
			<?php endfor; ?>
			<div id="add-conditional-rule"><a href="#">+ <?php echo __( 'Add rule', 'yith-woocommerce-product-add-ons' ); ?></a></div>
		</div>

	</div>
	<!-- End option field -->

	<script type="text/javascript">
		jQuery('#add-conditional-rule a').click( function() {
			var ruleTemplate = jQuery('#conditional-rules .field.rule:first-child');
			var clonedOption = ruleTemplate.clone( true );
			clonedOption.find('input[type=number]').val('');
			clonedOption.insertBefore('#add-conditional-rule');
		});
		jQuery('#conditional-rules').on( 'click', '.delete-rule', function() {
			jQuery(this).parent().remove();
		});
	</script>

</div>