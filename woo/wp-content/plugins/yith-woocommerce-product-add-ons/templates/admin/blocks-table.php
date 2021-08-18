<?php

/**
 *	Blocks Table Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;

$blocks = yith_wapo_get_blocks();

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith_wapo_panel_blocks" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<?php if ( count( $blocks ) > 0 ) : ?>

				<div class="list-table-title">
					<h2><?php echo __( 'Blocks list', 'yith-woocommerce-product-add-ons' ); ?></h2>
					<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button"><?php echo __( 'Add block', 'yith-woocommerce-product-add-ons' ); ?></a>
				</div>

				<table class="form-table wp-list-table widefat fixed striped table-view-list">
					<thead>
						<tr class="list-table">
							<th class="name"><?php echo __( 'Name', 'yith-woocommerce-product-add-ons' ); ?></th>
							<!--<th class="priority"><?php echo __( 'Priority', 'yith-woocommerce-product-add-ons' ); ?></th>-->
							<th class="products"><?php echo __( 'Show on products:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<th class="categories"><?php echo __( 'Show on categories:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
								<th class="exc-products"><?php echo __( 'Exclude products:', 'yith-woocommerce-product-add-ons' ); ?></th>
								<th class="exc-categories"><?php echo __( 'Exclude categories:', 'yith-woocommerce-product-add-ons' ); ?></th>
							<?php endif; ?>
							<th class="active"><?php echo __( 'Active', 'yith-woocommerce-product-add-ons' ); ?></th>
						</tr>
					</thead>
					<tbody id="sortable-blocks">
						<?php foreach ( $blocks as $key => $block ) : ?>

							<tr id="block-<?php echo $block->id; ?>" data-id="<?php echo $block->id; ?>" data-priority="<?php echo $block->priority; ?>">
								<td class="name">
									<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=<?php echo $block->id; ?>">
										<?php echo empty( $block->name ) ? '-' : $block->name; ?>
									</a>
								</td>
								<!--<td class="priority"><?php echo $block->priority; ?></td>-->
								<td class="products">
									<?php
										$included_products = $block->get_rule('show_in_products');
										if ( is_array( $included_products ) ) {
											foreach ( $included_products as $key => $value ) {
												if ( $value > 0 ) {
													$_product = wc_get_product( $value );
													if ( is_object( $_product ) ) {
														echo '<div><a href="' . $_product->get_permalink() . '" target="_blank">' . $_product->get_title() . ' (#' . $_product->get_id() . ')</a></div>';
													}
												}
											}
										} else { echo '-'; }
									?>
								</td>
								<td class="categories">
									<?php
										$included_categories = $block->get_rule('show_in_categories');
										if ( is_array( $included_categories ) ) {
											foreach ( $included_categories as $key => $value ) {
												if ( $term = get_term_by( 'id', $value, 'product_cat' ) ) {
													echo '<div><a href="' . get_term_link( $term->term_id, 'product_cat' ) . '" target="_blank">' . $term->name . ' (#' . $term->term_id . ')</div>';
												}
											}
										} else { echo '-'; }
									?>
								</td>
								<?php if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) : ?>
									<td class="exc-products">
										<?php
											$included_products = $block->get_rule('exclude_products_products');
											if ( is_array( $included_products ) ) {
												foreach ( $included_products as $key => $value ) {
													if ( $value > 0 ) {
														$_product = wc_get_product( $value );
														if ( is_object( $_product ) ) {
															echo '<div><a href="' . $_product->get_permalink() . '" target="_blank">' . $_product->get_title() . ' (#' . $_product->get_id() . ')</a></div>';
														}
													}
												}
											} else { echo '-'; }
										?>
									</td>
									<td class="exc-categories">
										<?php
											$included_categories = $block->get_rule('exclude_products_categories');
											if ( is_array( $included_categories ) ) {
												foreach ( $included_categories as $key => $value ) {
													if ( $term = get_term_by( 'id', $value, 'product_cat' ) ) {
														echo '<div><a href="' . get_term_link( $term->term_id, 'product_cat' ) . '" target="_blank">' . $term->name . ' (#' . $term->term_id . ')</div>';
													}
												}
											} else { echo '-'; }
										?>
									</td>
								<?php endif; ?>
								<td class="active">
									<div class="actions" style="display: none;">
										<?php
											$actions = array(
												'edit'      => array(
													'title'  => __( 'Edit', 'yith-woocommerce-product-add-ons' ),
													'action' => 'edit',
													'url'    => add_query_arg(
														array(
															'page'     => 'yith_wapo_panel',
															'tab'      => 'blocks',
															'block_id' => $block->id,
														),
														admin_url( 'admin.php' )
													),
												),
												'duplicate' => array(
													'title'  => __( 'Duplicate', 'yith-woocommerce-product-add-ons' ),
													'action' => 'duplicate',
													'icon'   => 'clone',
													'url'    => add_query_arg(
														array(
															'page'        => 'yith_wapo_panel',
															'wapo_action' => 'duplicate-block',
															'block_id'    => $block->id,
														),
														admin_url( 'admin.php' )
													),
												),
												'delete'    => array(
													'title'        => __( 'Delete', 'yith-woocommerce-product-add-ons' ),
													'action'       => 'delete',
													'icon'         => 'trash',
													'url'          => add_query_arg(
														array(
															'page'        => 'yith_wapo_panel',
															'wapo_action' => 'remove-block',
															'block_id'    => $block->id,
														),
														admin_url( 'admin.php' )
													),
													'confirm_data' => array(
														'title'               => __( 'Confirm delete', 'yith-woocommerce-product-add-ons' ),
														'message'             => __( 'Are you sure to delete this block?', 'yith-woocommerce-product-add-ons' ),
														'confirm-button'      => _x( 'Yes, delete', 'Delete confirmation action', 'yith-woocommerce-product-add-ons' ),
														'confirm-button-type' => 'delete',
													),
												),
												'move' => array(
													'title'  => __( 'Move', 'yith-woocommerce-product-add-ons' ),
													'action' => 'move',
													'icon'   => 'drag',
													'url'    => '#',
												),
											);

											yith_plugin_fw_get_action_buttons( $actions, true );
										?>
									</div>
									<?php
										yith_plugin_fw_get_field( array(
											'id'	=> 'yith-wapo-active-block-' . $block->id,
											'type'	=> 'onoff',
											'value' => $block->visibility == 1 ? 'yes' : 'no',
										), true );
									?>
								</td>
							</tr>

						<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>

				<div id="empty-state">
					<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/empty-state.png">
					<p>
						<?php echo __( 'You have no options blocks created yet.', 'yith-woocommerce-product-add-ons' ); ?><br />
						<?php echo __( 'Now build your first block!', 'yith-woocommerce-product-add-ons' ); ?>
					</p>
					<a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button"><?php echo __( 'Add block', 'yith-woocommerce-product-add-ons' ); ?></a>
				</div>

			<?php endif; ?>

			<?php if ( yith_wapo_previous_version_exists() ) : ?>
				<a href="edit.php?post_type=product&page=yith_wapo_groups&yith_wapo_v2=no" class="yith-update-button">
					<?php echo __( 'Switch to the 1.x version', 'yith-woocommerce-product-add-ons' ); ?>
				</a>
			<?php else : update_option( 'yith_wapo_settings_disable_wccl', 'yes' ); endif; ?>
		
		</div>
	</div>
</div>
