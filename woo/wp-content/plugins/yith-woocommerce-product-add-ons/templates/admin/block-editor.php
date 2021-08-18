<?php

/**
 *	Block Editor Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$block = new YITH_WAPO_Block( $block_id );

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith-wapo-panel-block" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<a href="admin.php?page=yith_wapo_panel&tab=blocks">< <?php echo __( 'Back to blocks list', 'yith-woocommerce-product-add-ons' ); ?></a>
			<div class="list-table-title">
				<h2><?php echo is_numeric( $block_id ) ? __( 'Edit block', 'yith-woocommerce-product-add-ons' ) : __( 'Add new block', 'yith-woocommerce-product-add-ons' ); ?></h2>
			</div>

			<form action="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo $block_id; ?>" method="post" id="block">

				<!-- Option field -->
				<div class="field-wrap">
					<label for="block-name"><?php echo __( 'Block name', 'yith-woocommerce-product-add-ons' ); ?></label>
					<div class="field">
						<input type="text" name="block_name" id="block-name" value="<?php echo $block->name; ?>">
						<span class="description"><?php echo __( 'Enter a name to identify this block of options.', 'yith-woocommerce-product-add-ons' ); ?></span>
					</div>
				</div>
				<!-- End option field -->

				<!-- Option field -->
				<div class="field-wrap">
					<label for="block-priority"><?php echo __( 'Block priority level', 'yith-woocommerce-product-add-ons' ); ?></label>
					<div class="field">
						<input type="number" name="block_priority" id="block-priority" value="<?php echo round( $block->priority ); ?>" min="0" max="9999">
						<span class="description">
							<?php echo __( 'Set the priority level assigned to this rule. The priority level is important to arrange the different rules that apply to the same products. 1 has the highest priority level.', 'yith-woocommerce-product-add-ons' ); ?>
						</span>
					</div>
				</div>
				<!-- End option field -->

				<div id="addons-tabs">
					<a href="#addons-tabs" id="-addons" class="selected"><?php echo __( 'OPTIONS', 'yith-woocommerce-product-add-ons' ); ?></a>
					<a href="#addons-tabs" id="-rules"><?php echo __( 'RULES', 'yith-woocommerce-product-add-ons' ); ?></a>
				</div>

				<script type="text/javascript">
					jQuery('#addons-tabs a').click(function(){
						jQuery('#addons-tabs a').removeClass('selected');
						jQuery(this).addClass('selected');
						var tab = jQuery(this).attr('id');
						jQuery( '#addons-tab > div' ).hide();
						jQuery( '#addons-tab #block' + tab ).show();
					});
				</script>

				<div id="addons-tab">

					<div id="block-addons">
						<div id="block-addons-container">
							<ul id="sortable-addons">
								<?php
								$addons = yith_wapo_get_addons_by_block_id( $block_id );
								$total_addons = sizeof( $addons );
								if ( $total_addons > 0 ) :
									foreach ( $addons as $key => $addon ) :
										if ( yith_wapo_is_addon_type_available( $addon->type ) ) :
											$total_options = is_array( $addon->options ) ? sizeof( array_values( $addon->options )[0] ) : 0; ?>
											<li id="addon-<?php echo $addon->id; ?>" data-id="<?php echo $addon->id; ?>" data-priority="<?php echo $addon->priority; ?>">
												<span class="addon-icon <?php echo $addon->type; ?>">
													<span class="wapo-icon wapo-icon-<?php echo $addon->type; ?>"></span>
													<!--<span style="background-image: url('<?php echo YITH_WAPO_URL; ?>/assets/img/addons-icons/<?php echo $addon->type; ?>.svg');"></span>-->
												</span>
												<span class="addon-name">
													<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo $block->id; ?>&addon_id=<?php echo $addon->id; ?>&addon_type=<?php echo $addon->type; ?>">
														<!--[<?php echo $addon->id; ?>][priority:<?php echo $addon->priority; ?>] - -->
														<?php
															echo $addon->get_setting('title') ? $addon->get_setting('title') . ' - ' : '';
															// echo ucwords( str_replace( 'html', 'HTML', str_replace( '_', ' ', $addon->type ) ) );
															if ( strpos( $addon->type, 'html' ) === false ) {
																// echo ' ' . __( 'with', 'yith-woocommerce-product-add-ons' ) . ' ';
																echo $total_options . ' ';
																if ( $total_options == 1 ) {
																	echo __( 'option', 'yith-woocommerce-product-add-ons' );
																} else {
																	echo __( 'options', 'yith-woocommerce-product-add-ons' );
																}
															}
														?>
													</a>
												</span>
												<span class="addon-actions" style="display: none;">
													<a class="edit" href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo $block->id; ?>&addon_id=<?php echo $addon->id; ?>&addon_type=<?php echo $addon->type; ?>"></a>
													<a class="duplicate" href="admin.php?page=yith_wapo_panel&wapo_action=duplicate-addon&block_id=<?php echo $block_id; ?>&addon_id=<?php echo $addon->id; ?>"></a>
													<a class="remove" href="admin.php?page=yith_wapo_panel&wapo_action=remove-addon&block_id=<?php echo $block_id; ?>&addon_id=<?php echo $addon->id; ?>" onclick="return confirm('Are you sure?')"></a>
													<a class="move" href="#"></a>
												</span>
												<span class="addon-onoff">
													<?php
														yith_plugin_fw_get_field( array(
															'id' => 'yith-wapo-active-addon-' . $addon->id,
															'type' => 'onoff',
															'value' => $addon->visibility == 1 ? 'yes' : 'no',
														), true );
													?>
												</span>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
							<div id="add-option">
								<?php if ( ! $total_addons > 0 ) : ?>
									<p><?php echo __( 'Start to add your options to this block!', 'yith-woocommerce-product-add-ons' ); ?></p>
								<?php endif; ?>
								<input type="submit" name="add_options_after_save" value="<?php echo __( 'Add options', 'yith-woocommerce-product-add-ons' ); ?>" class="yith-add-button">

							</div>
						</div>
					</div>

					<!-- BLOCK RULES -->
					<?php include YITH_WAPO_DIR . '/templates/admin/block-rules.php'; ?>

				</div>

				<input type="hidden" name="wapo_action" value="save-block">
				<input type="hidden" name="id" value="<?php echo $block_id; ?>">

				<div id="save-button">
					<button class="yith-save-button"><?php echo __( 'Save', 'yith-woocommerce-product-add-ons' ); ?></button>
				</div>

			</form>

		</div>
	</div>

	<?php if ( isset( $_REQUEST['addon_id'] ) ) { include YITH_WAPO_DIR . '/templates/admin/addon-editor.php'; } ?>

</div>
