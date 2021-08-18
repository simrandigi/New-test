<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$date_format = $addon->get_option( 'date_format', $x );
$date_format_js = str_replace( 'd', 'dd', $date_format );
$date_format_js = str_replace( 'm', 'mm', $date_format_js );
$date_format_js = str_replace( 'Y', 'yy', $date_format_js );
$default_date = '';
$default_date_type = $addon->get_option( 'date_default', $x );
if ( $default_date_type == 'today' ) {
	$default_date = date( $date_format );
} elseif ( $default_date_type == 'tomorrow' ) {
	$default_date = date( $date_format, strtotime( '+1 day' ) );
} elseif ( $default_date_type == 'specific' ) {
	$default_specific_day = $addon->get_option( 'date_default_day', $x );
	$default_date = date( $date_format, strtotime( $default_specific_day ) );
} elseif ( $default_date_type == 'interval' ) {
	$default_calculate_num = $addon->get_option( 'date_default_calculate_num', $x );
	$default_calculate_type = $addon->get_option( 'date_default_calculate_type', $x );
	$default_date = date( $date_format, strtotime( '+' . $default_calculate_num . ' ' . $default_calculate_type ) );
}

$required = $addon->get_option( 'required', $x ) == 'yes';

?>

<div id="yith-wapo-option-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="yith-wapo-option">

	<?php if ( $addon->get_option( 'show_image', $x ) && $addon->get_option( 'image', $x ) != '' && $setting_hide_images != 'yes' ) : ?>
		<div class="image position-<?php echo $addon_options_images_position; ?>">
			<img src="<?php echo $addon->get_option( 'image', $x ); ?>">
		</div>
	<?php endif; ?>

	<div class="label">
		<label for="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>">
			<?php echo ! $hide_option_label ? $addon->get_option( 'label', $x ) : ''; ?>
			<?php echo ! $hide_option_prices ? $addon->get_option_price_html( $x ) : ''; ?>
		</label>
	</div>
	
	<input type="text" id="yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>" class="datepicker" name="yith_wapo[][<?php echo $addon->id . '-' . $x; ?>]"
		value="<?php echo $default_date; ?>"
		data-price="<?php echo $addon->get_option_price( $x ); ?>"
		data-price-sale="<?php echo $price_sale; ?>"
		data-price-type="<?php echo $price_type; ?>"
		data-price-method="<?php echo $price_method; ?>"
		data-first-free-enabled="<?php echo $addon->get_setting( 'first_options_selected', 'no' ); ?>"
		data-first-free-options="<?php echo $addon->get_setting( 'first_free_options', 0 ); ?>"
		data-addon-id="<?php echo $addon->id; ?>"
		<?php echo $required ? 'required' : '' ?>>

	<?php if ( $addon->get_option( 'tooltip', $x ) != '' ) : ?>
		<span class="tooltip">
			<span><?php echo $addon->get_option( 'tooltip', $x ); ?></span>
		</span>
	<?php endif; ?>

	<?php if ( $option_description != '' ) : ?>
		<p class="description">
			<?php echo $option_description; ?>
		</p>
	<?php endif; ?>

</div>

<?php

$start_year			= $addon->get_option( 'start_year', $x );
$end_year			= $addon->get_option( 'end_year', $x );
$selectable_dates	= $addon->get_option( 'selectable_dates', $x );
$days_min			= $addon->get_option( 'days_min', $x );
$days_max			= $addon->get_option( 'days_max', $x );
$date_min			= $addon->get_option( 'date_min', $x );
$date_max			= $addon->get_option( 'date_max', $x );

// selectable dates
$selectable_days = '';
if ( $selectable_dates == 'days' && $days_min >= -365 && $days_max > $days_min ) {
	for ( $z = $days_min; $z < $days_max; $z++ ) {
		$selectable_days .= '"' . date( 'j-n-Y', strtotime( '+' . $z . ' day' ) ) . '", ';
	}
} elseif ( $selectable_dates == 'date' ) {
	$z = 0;
	$selectable_date_min = date( 'j-n-Y', strtotime( $date_min ) );
	$selectable_date_max = date( 'j-n-Y', strtotime( $date_max ) );
	$selectable_days .= '"' . $selectable_date_min . '", ';
	while ( ++$z ) {
		$calculated_date = date( 'j-n-Y', strtotime( $date_min . ' +' . $z . ' day' ) );
		$selectable_days .= '"' . $calculated_date . '", ';
		if ( $calculated_date == $selectable_date_max ) {
			break;
		}
	}
}

// rules
$enable_disable_days = $addon->get_option( 'enable_disable_days', $x );
$enable_disable_date_rules = $addon->get_option( 'enable_disable_date_rules', $x, 'enable' );
$selected_days = '';
$date_rules_count = count( (array) $addon->get_option( 'date_rule_what', $x ) );
for ( $y=0; $y<$date_rules_count; $y++ ) {
	$date_rule_what = isset( $addon->get_option( 'date_rule_what', $x )[$y] ) ? $addon->get_option( 'date_rule_what', $x )[$y] : '';
	$date_rule_days = isset( $addon->get_option( 'date_rule_value_days', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_days', $x, '' )[$y] : '';
	$date_rule_months = isset( $addon->get_option( 'date_rule_value_months', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_months', $x, '' )[$y] : '';
	$date_rule_years = isset( $addon->get_option( 'date_rule_value_years', $x, '' )[$y] ) ? $addon->get_option( 'date_rule_value_years', $x, '' )[$y] : '';
	if ( $date_rule_what == 'days' ) {
		$selected_days .= '"' . date( 'j-n-Y', strtotime( $date_rule_days ) ) . '", ';
	} else if ( $date_rule_what == 'months' ) {
		$year = date('Y');
		foreach ( $date_rule_months as $key => $month ) {
			for ( $day=1; $day<32 ; $day++ ) {
				$selected_days .= '"' . $day . '-' . $month . '-' . $year . '", ';
			}
		}
	} else if ( $date_rule_what == 'years' ) {
		foreach ( $date_rule_years as $key => $year ) {
			for ( $month=1; $month<13 ; $month++ ) {
				for ( $day=1; $day<32 ; $day++ ) {
					$selected_days .= '"' . $day . '-' . $month . '-' . $year . '", ';
				}
			}
		}
	}
}

?>

<script type="text/javascript">

	var selectableDays = [<?php echo $selectable_days; ?>];
	var selectedDays = [<?php echo $selected_days; ?>];

	function setAvailableDays( date ) {

		<?php if ( $selectable_days != '' ) : ?>
			for ( i = 0; i < selectableDays.length; i++ ) {
				var currentDate = date.getDate() + '-' + ( date.getMonth() + 1 ) + '-' + date.getFullYear();
				if ( jQuery.inArray( currentDate, selectableDays ) != -1 ) {
					return [true];
				} else {
					return [false];
				}
			}
		<?php endif; ?>

		<?php if ( $enable_disable_days == 'yes' ) : ?>
			for ( i = 0; i < selectedDays.length; i++ ) {
				var currentDate = date.getDate() + '-' + ( date.getMonth() + 1 ) + '-' + date.getFullYear();
				if ( jQuery.inArray( currentDate, selectedDays ) != -1 ) {
					return <?php echo $enable_disable_date_rules == 'disable' ? '[false]' : '[true]'; ?>;
				} else {
					return <?php echo $enable_disable_date_rules == 'disable' ? '[true]' : '[false]'; ?>;
				}
			}
		<?php endif; ?>

		return [true];

	}

	jQuery( function() {
		jQuery( '#yith-wapo-<?php echo $addon->id; ?>-<?php echo $x; ?>' ).datepicker({
			dateFormat: '<?php echo $date_format_js; ?>',
			<?php if ( $start_year > 0 ) : ?>minDate: new Date('<?php echo $start_year; ?>-01-01'),<?php endif; ?>
			<?php if ( $end_year > 0 ) : ?>maxDate: new Date('<?php echo $end_year; ?>-12-31'),<?php endif; ?>
			beforeShowDay: setAvailableDays,
		});
	});

</script>