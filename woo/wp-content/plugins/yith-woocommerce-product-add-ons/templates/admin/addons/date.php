<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

?>

<div class="title"><span class="icon"></span> <?php echo __( 'DATE FIELD', 'yith-woocommerce-product-add-ons' ); ?> - <?php echo $addon->get_option( 'label', $x ); ?></div>

<div class="fields">
	
	<?php include YITH_WAPO_DIR . '/templates/admin/option-common-fields.php'; ?>

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo __( 'Date format', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date-format',
				'name'	=> 'options[date_format][]',
				'type'	=> 'select',
				'value'	=> $addon->get_option( 'date_format', $x, 'dd/mm/yy' ),
				'options'	=> array(
					'd/m/Y'	=> __( 'Day / Month / Year', 'yith-woocommerce-product-add-ons' ),
					'm/d/Y'	=> __( 'Month / Day / Year', 'yith-woocommerce-product-add-ons' ),
					'd.m.Y'	=> __( 'Day . Month . Year', 'yith-woocommerce-product-add-ons' ),
				),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo __( 'Year', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<small><?php echo __( 'START YEAR', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[start_year][]" id="option-start-year" value="<?php echo $addon->get_option( 'start_year', $x ); ?>" class="mini">
		</div>
		<div class="field">
			<small><?php echo __( 'END YEAR', 'yith-woocommerce-product-add-ons' ); ?></small>
			<input type="text" name="options[end_year][]" id="option-end-year" value="<?php echo $addon->get_option( 'end_year', $x ); ?>" class="mini">
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label><?php echo __( 'Default date', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date-default-' . $x,
				'class'	=> 'option-date-default',
				'name'	=> 'options[date_default][]',
				'type'	=> 'select',
				'value'	=> $addon->get_option( 'date_default', $x, '' ),
				'options'	=> array(
					''			=> __( 'None', 'yith-woocommerce-product-add-ons' ),
					'today'		=> __( 'Current day', 'yith-woocommerce-product-add-ons' ),
					'tomorrow'	=> __( 'Current day', 'yith-woocommerce-product-add-ons' ) . ' + 1',
					'specific'	=> __( 'Set a specific day', 'yith-woocommerce-product-add-ons' ),
					'interval'	=> __( 'Set a time interval from current day', 'yith-woocommerce-product-add-ons' ),
				),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-date-default-day option-date-default-day-<?php echo $x; ?>" style="<?php echo $addon->get_option( 'date_default', $x ) != 'specific' ? 'display: none;' : ''; ?>">
		<label><?php echo __( 'Specific day', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date-default-day-' . $x,
				'name'	=> 'options[date_default_day][]',
				'type'	=> 'datepicker',
				'value'	=> $addon->get_option( 'date_default_day', $x, '' ),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-date-default-interval option-date-default-interval-<?php echo $x; ?>" style="<?php echo $addon->get_option( 'date_default', $x ) != 'interval' ? 'display: none;' : ''; ?>">
		<label><?php echo __( 'For default date, calculate', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php

			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date-default-interval-num-' . $x,
				'name'	=> 'options[date_default_calculate_num][]',
				'class'	=> 'micro',
				'type'	=> 'select',
				'value'	=> $addon->get_option( 'date_default_calculate_num', $x, '' ),
				'options'	=> array( 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31 ),
			), true );
			?>
		</div>
		<div class="field">
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date-default-interval-type-' . $x,
				'name'	=> 'options[date_default_calculate_type][]',
				'class'	=> 'micro',
				'type'	=> 'select',
				'value'	=> $addon->get_option( 'date_default_calculate_type', $x, '' ),
				'options'	=> array(
					'days'		=> __( 'Days', 'yith-woocommerce-product-add-ons' ),
					'months'	=> __( 'Months', 'yith-woocommerce-product-add-ons' ),
					'years'		=> __( 'Years', 'yith-woocommerce-product-add-ons' ),
				),
			), true );
			?>
		</div>
		<span style="line-height: 35px;"><?php echo __( 'from current day', 'yith-woocommerce-product-add-ons' ); ?></span>
	</div>
	<!-- End option field -->

	<div class="field-wrap">
		<label><?php echo __( 'Selectable dates', 'yith-woocommerce-product-add-ons' ); ?>:</label>
		<div class="field">
			<?php
			$selectable_dates_option = $addon->get_option( 'selectable_dates', $x, '' );
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-selectable-dates-' . $x,
				'class'	=> 'option-selectable-dates',
				'name'	=> 'options[selectable_dates][]',
				'type'	=> 'select',
				'value'	=> $selectable_dates_option,
				'options'	=> array(
					''			=> __( 'Set no limits', 'yith-woocommerce-product-add-ons' ),
					'days'		=> __( 'Set a range of days', 'yith-woocommerce-product-add-ons' ),
					'date'		=> __( 'Set a specific date range', 'yith-woocommerce-product-add-ons' ),
				),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-selectable-days-ranges" style="<?php echo $selectable_dates_option == 'days' ? '' : 'display: none;'; ?>">
		<label><?php echo __( 'Selectable days ranges', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field datepicker-micro">
			<small><?php echo __( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_min][]" id="option-days-min" value="<?php echo $addon->get_option( 'days_min', $x ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-days_min-' . $x,
				'name'	=> 'options[days_min][]',
				'type'	=> 'text',
				'value'	=> $addon->get_option( 'days_min', $x, '' ),
			), true );
			?>
		</div>
		<div class="field datepicker-micro">
			<small><?php echo __( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_max][]" id="option-days-max" value="<?php echo $addon->get_option( 'days_max', $x ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-days_max-' . $x,
				'name'	=> 'options[days_max][]',
				'type'	=> 'text',
				'value'	=> $addon->get_option( 'days_max', $x, '' ),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap option-selectable-date-ranges" style="<?php echo $selectable_dates_option == 'date' ? '' : 'display: none;'; ?>">
		<label><?php echo __( 'Selectable date ranges', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field datepicker-micro">
			<small><?php echo __( 'MIN', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_min][]" id="option-days-min" value="<?php echo $addon->get_option( 'days_min', $x ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date_min-' . $x,
				'name'	=> 'options[date_min][]',
				'type'	=> 'datepicker',
				'value'	=> $addon->get_option( 'date_min', $x, '' ),
			), true );
			?>
		</div>
		<div class="field datepicker-micro" style="margin-left: -23px;">
			<small><?php echo __( 'MAX', 'yith-woocommerce-product-add-ons' ); ?></small>
			<!--<input type="text" name="options[days_max][]" id="option-days-max" value="<?php echo $addon->get_option( 'days_max', $x ); ?>" class="micro">-->
			<?php
			yith_plugin_fw_get_field( array(
				'id'	=> 'option-date_max-' . $x,
				'name'	=> 'options[date_max][]',
				'type'	=> 'datepicker',
				'value'	=> $addon->get_option( 'date_max', $x, '' ),
			), true );
			?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap">
		<label for="date-rule"><?php echo __( 'Enable / disable specific days', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div class="field">
			<?php yith_plugin_fw_get_field( array(
				'id'	=> 'option-enable-disable-days-' . $x,
				'name'	=> 'options[enable_disable_days][]',
				'class'	=> 'enabler',
				'type'	=> 'onoff',
				'value'	=> $addon->get_option( 'enable_disable_days', $x ),
			), true ); ?>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="field-wrap enabled-by-option-enable-disable-days-<?php echo $x; ?>" style="display: none;">
		<label><?php echo __( 'Rule type', 'yith-woocommerce-product-add-ons' ); ?></label>
		<div id="disable-date-rules-<?php echo $x; ?>">
			<div class="field rules-type" style="margin-bottom: 10px;">
				<?php
				yith_plugin_fw_get_field( array(
					'id'	=> 'option-enable-disable-days-type-' . $x,
					'name'	=> 'options[enable_disable_date_rules][]',
					'class'	=> 'micro',
					'type'	=> 'select',
					'value'	=> $addon->get_option( 'enable_disable_date_rules', $x, 'enable' ),
					'options'	=> array(
						'enable'	=> __( 'Enable', 'yith-woocommerce-product-add-ons' ),
						'disable'	=> __( 'Disable', 'yith-woocommerce-product-add-ons' ),
					),
				), true );
				?>
			</div>
			<span style="line-height: 35px;"><?php echo __( 'these dates in calendar', 'yith-woocommerce-product-add-ons' ); ?></span>
			<div id="date-rules-<?php echo $x; ?>" class="date-rules" style="clear: both;">

				<?php
					$date_rules_count = count( (array) $addon->get_option( 'date_rule_what', $x ) );
					for ( $y=0; $y<$date_rules_count; $y++ ) :
						$date_rule_what = $addon->get_option( 'date_rule_what', $x, 'enable' )[$y];
				?>
					<div class="rule" style="margin-bottom: 10px;">
						<div class="field what">
							<?php
							yith_plugin_fw_get_field( array(
								'id'	=> 'date-rule-what-' . $x . '-' . $y,
								'name'	=> 'options[date_rule_what][' . $x . '][]',
								'class'	=> 'micro select_what',
								'type'	=> 'select',
								'value'	=> $date_rule_what,
								'options'	=> array(
									'days'		=> __( 'Days', 'yith-woocommerce-product-add-ons' ),
									'months'	=> __( 'Months', 'yith-woocommerce-product-add-ons' ),
									'years'		=> __( 'Years', 'yith-woocommerce-product-add-ons' ),
								),
							), true );
							?>
						</div>
						
						<div class="field days" <?php echo $date_rule_what != 'months' && $date_rule_what != 'years' ? '' : 'style="display: none;"'; ?>>
							<?php
							yith_plugin_fw_get_field( array(
								'id'	=> 'date-rule-value-days-' . $x . '-' . $y,
								'name'	=> 'options[date_rule_value_days][' . $x . '][' . $y . ']',
								'type'	=> 'datepicker',
								'value'	=> isset( $addon->get_option( 'date_rule_value_days', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_days', $x, '' )[$y] : '',
							), true );
							?>
						</div>
					
						<div class="field months" <?php echo $date_rule_what == 'months' ? '' : 'style="display: none;"'; ?>>
							<?php
							yith_plugin_fw_get_field( array(
								'id'		=> 'date-rule-value-months-' . $x . '-' . $y,
								'name'		=> 'options[date_rule_value_months][' . $x . '][' . $y . ']',
								'type'		=> 'select',
								'multiple'	=> true,
								'class'		=> 'wc-enhanced-select',
								'options'	=> array(
									'1'		=> __( 'January', 'yith-woocommerce-product-add-ons' ),
									'2'		=> __( 'February', 'yith-woocommerce-product-add-ons' ),
									'3'		=> __( 'March', 'yith-woocommerce-product-add-ons' ),
									'4'		=> __( 'April', 'yith-woocommerce-product-add-ons' ),
									'5'		=> __( 'May', 'yith-woocommerce-product-add-ons' ),
									'6'		=> __( 'June', 'yith-woocommerce-product-add-ons' ),
									'7'		=> __( 'July', 'yith-woocommerce-product-add-ons' ),
									'8'		=> __( 'August', 'yith-woocommerce-product-add-ons' ),
									'9'		=> __( 'September', 'yith-woocommerce-product-add-ons' ),
									'10'	=> __( 'October', 'yith-woocommerce-product-add-ons' ),
									'11'	=> __( 'November', 'yith-woocommerce-product-add-ons' ),
									'12'	=> __( 'December', 'yith-woocommerce-product-add-ons' ),
								),
								'value'	=> isset( $addon->get_option( 'date_rule_value_months', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_months', $x, '' )[$y] : '',
							), true );
							?>
						</div>
					
						<div class="field years" <?php echo $date_rule_what == 'years' ? '' : 'style="display: none;"'; ?>>
							<?php
							$years = array();
							for ( $year = date('Y'); $year < date('Y') + 10 ; $year++ ) { 
								$years[ $year ] = $year;
							}
							yith_plugin_fw_get_field( array(
								'id'		=> 'date-rule-value-years' . $x . '-' . $y,
								'name'		=> 'options[date_rule_value_years][' . $x . '][' . $y . ']',
								'type'		=> 'select',
								'multiple'	=> true,
								'class'		=> 'wc-enhanced-select',
								'options'	=> $years,
								'value'	=> isset( $addon->get_option( 'date_rule_value_years', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_years', $x, '' )[$y] : '',
							), true );
							?>
						</div>

						<img src="<?php echo YITH_WAPO_URL; ?>/assets/img/delete.png" class="delete-rule" style="display: none;">
						
						<div class="clear"></div>
					</div>
				<?php endfor; ?>

				<style type="text/css">
					.date-rules .rule { position: relative; }
					.date-rules .rule .delete-rule { width: 8px; height: 10px; padding: 12px; cursor: pointer; position: absolute; left: 400px; }
					.date-rules .rule .delete-rule:hover { opacity: 0.5; }
					.date-rules span.select2.select2-container { border-radius: 0 !important; padding: 0px 5px !important }
					.date-rules span.selection span.select2-selection.select2-selection--multiple { min-height: 20px !important; }
				</style>

				<div id="add-date-rule" class="add-date-rule" style="clear: both;"><a href="#">+ <?php echo __( 'Add rule', 'yith-woocommerce-product-add-ons' ); ?></a></div>

			</div>
		</div>
	</div>
	<!-- End option field -->

</div>
