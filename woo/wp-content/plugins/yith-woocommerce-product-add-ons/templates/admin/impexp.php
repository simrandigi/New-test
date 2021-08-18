<?php

/**
 *	Import Export Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith_wapo_panel_impexp" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<div class="list-table-title">
				<h2><?php echo __( 'Import / Export', 'yith-woocommerce-product-add-ons' ); ?></h2>
			</div>

			<?php
				if ( isset( $_GET['wapo_import'] ) && $_GET['wapo_import'] == 'copy' ) {
					yith_wapo_copy_old_elements();
					wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );
				}
			?>

			<a href="http://localhost/yithemes.com/wp-admin/admin.php?page=yith_wapo_panel&tab=impexp&wapo_import=copy" class="yith-update-button">
				<?php echo __( 'Import old add-ons', 'yith-woocommerce-product-add-ons' ); ?>
			</a>
		
		</div>
	</div>
</div>
