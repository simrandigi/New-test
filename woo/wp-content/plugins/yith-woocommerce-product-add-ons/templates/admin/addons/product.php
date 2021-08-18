<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title"><span class="icon"></span> <?php echo __( 'PRODUCT', 'yith-woocommerce-product-add-ons' ); ?> - <?php echo $addon->get_option( 'label', $x ); ?></div>

<div class="fields">

	<!-- Option field -->
	<div class="field-wrap">
		<label for="option-product"><?php echo __( 'Product', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'option-product-' . $x,
				'name'	=> 'options[product][]',
				'type'	=> 'ajax-products',
				'value'	=> $addon->get_option( 'product', $x ),
				'data' => array(
					'action' => 'woocommerce_json_search_products_and_variations',
					'security' => wp_create_nonce( 'search-products' )
				),
			), true ); ?>
		</div>
	</div>
	<!-- End option field -->
	
	<?php include YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

</div>
