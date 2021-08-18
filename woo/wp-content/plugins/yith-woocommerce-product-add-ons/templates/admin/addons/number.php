<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title"><span class="icon"></span> <?php echo __( 'NUMBER FIELD', 'yith-woocommerce-product-add-ons' ); ?> - <?php echo $addon->get_option( 'label', $x ); ?></div>

<div class="fields">
	
	<?php include YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

    <?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>

        <!-- Option field -->
        <div class="field-wrap">
            <label for="option-number-limit"><?php echo __( 'Limit the input number', 'yith-woocommerce-product-add-ons' ); ?>:</label>
            <div class="field">
                <?php yith_plugin_fw_get_field( array(
                    'id'    => 'option-number-limit',
                    'class' => 'enabler',
                    'name'  => 'options[number_limit][]',
                    'type'  => 'onoff',
                    'value' => $addon->get_option( 'number_limit', $x ),
                ), true ); ?>
            </div>
        </div>
        <!-- End option field -->

        <!-- Option field -->
        <div class="field-wrap enabled-by-option-number-limit" style="display: none;">
            <label for="option-number-limit"><?php echo __( 'Number limits', 'yith-woocommerce-product-add-ons' ); ?>:</label>
            <div class="field">
                <small><?php echo __( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
                <input type="text" name="options[number_limit_min][]" id="option-number-limit-min" value="<?php echo $addon->get_option( 'number_limit_min', $x ); ?>" class="mini">
            </div>
            <div class="field">
                <small><?php echo __( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
                <input type="text" name="options[number_limit_max][]" id="option-number-limit-max" value="<?php echo $addon->get_option( 'number_limit_max', $x ); ?>" class="mini">
            </div>
        </div>
        <!-- End option field -->

    <?php endif; ?>

</div>