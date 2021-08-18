<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title">
    <span class="icon"></span>
    <?php echo __( 'UPLOAD FILE', 'yith-woocommerce-product-add-ons' ); ?> -
    <?php echo $addon->get_option( 'label', $x ); ?>
</div>

<div class="fields">
	<?php include YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>
</div>