<?php

/**
 *	WAPO Functions
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

/**
 * Get Addons by Block ID Function
 */
if ( ! function_exists( 'yith_wapo_get_addons_by_block_id' ) ) {
	function yith_wapo_get_addons_by_block_id( $block_id ) {

		global $wpdb;
		
		$query = "SELECT id FROM {$wpdb->prefix}yith_wapo_addons WHERE block_id='$block_id' AND deleted='0' ORDER BY priority ASC";
		$results = $wpdb->get_results( $query );

		$addons = array();
		foreach ( $results as $key => $addon ) {
			$addons[] = new YITH_WAPO_Addon( $addon->id );
		}

		return $addons;

	}
}

/**
 * Get Blocks Function
 */
if ( ! function_exists( 'yith_wapo_get_blocks' ) ) {
	function yith_wapo_get_blocks() {

		global $wpdb;

		// YITH Multi Vendor integration
		$vendor_check = '';
		if ( class_exists( 'YITH_Vendors' ) ) {
			$vendor = yith_get_vendor( 'current', 'user' );
			if ( $vendor->is_valid() ) {
				$vendor_check = "AND vendor_id='$vendor->id'";
			}
		}

		$query = "SELECT id FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0' $vendor_check ORDER BY priority ASC";
		$results = $wpdb->get_results( $query );

		$blocks = array();
		foreach ( $results as $key => $block ) {
			$blocks[] = new YITH_WAPO_Block( $block->id );
		}

		return $blocks;

	}
}

/**
 * Get Option Info
 */
if ( ! function_exists( 'yith_wapo_get_option_info' ) ) {
	function yith_wapo_get_option_info( $addon_id, $option_id ) {

		$info = array();

		if ( $addon_id > 0 ) {

			$addon = new YITH_WAPO_Addon( $addon_id );

			// Option
			$info['color'] = $addon->get_option( 'color', $option_id );
			$info['label'] = $addon->get_option( 'label', $option_id );
			$info['price_method'] = $addon->get_option( 'price_method', $option_id );
			$info['price_type'] = $addon->get_option( 'price_type', $option_id );
			$info['price'] = $addon->get_option( 'price', $option_id );
			$info['price_sale'] = $addon->get_option( 'price_sale', $option_id );

			// Addon settings
			$info['addon_label'] = $addon->get_setting( 'title' );
			$info['addon_type'] = $addon->get_setting( 'type' );

			// Addon advanced
			$info['addon_first_options_selected'] = $addon->get_setting( 'first_options_selected' );
			$info['addon_first_free_options'] = $addon->get_setting( 'first_free_options' );

		}

		return $info;

	}
}

/**
 * Get Option Price
 */
if ( ! function_exists( 'yith_wapo_get_option_price' ) ) {
	function yith_wapo_get_option_price( $product_id, $addon_id, $option_id ) {

		$info = yith_wapo_get_option_info( $addon_id, $option_id );
		$option_price = '';
		$option_price_sale = '';
		if ( $info['price_type'] == 'percentage' ) {
			$_product = wc_get_product( $product_id );
			// WooCommerce Measurement Price Calculator (compatibility)
			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
				$product_price = $cart_item['pricing_item_meta_data']['_price'];
			} else { $product_price = floatval( $_product->get_price() ); }
			// end WooCommerce Measurement Price Calculator (compatibility)
			$option_percentage = floatval( $info['price'] );
			$option_percentage_sale = floatval( $info['price_sale'] );
			$option_price = ( $product_price / 100 ) * $option_percentage;
			$option_price_sale = ( $product_price / 100 ) * $option_percentage_sale;
		} else if ( $info['price_type'] == 'multiplied' ) {
			$option_price = $info['price'] * $value;
			$option_price_sale = $info['price'] * $value;
		} else {
			$option_price = $info['price'];
			$option_price_sale = $info['price_sale'];
		}

		return array( 'price' => $option_price, 'price_sale' => $option_price_sale );

	}
}

/**
 * Get WooCommerce Tax Rate
 */
if ( ! function_exists( 'yith_wapo_get_tax_rate' ) ) {
	function yith_wapo_get_tax_rate() {
		$wc_tax_rate = false;
		if ( get_option( 'woocommerce_calc_taxes', 'no' ) == 'yes' ) {
			$wc_tax_rates = WC_Tax::get_rates();
			if ( get_option( 'woocommerce_prices_include_tax' ) == 'no' && get_option( 'woocommerce_tax_display_shop' ) == 'incl' ) {
				$wc_tax_rate = reset( $wc_tax_rates )['rate'];
			}
			if ( get_option( 'woocommerce_prices_include_tax' ) == 'yes' && get_option( 'woocommerce_tax_display_shop' ) == 'excl' ) {
				$wc_tax_rate = - reset( $wc_tax_rates )['rate'];
			}
		}
		return $wc_tax_rate;
	}
}

/**
 * Is addon type available
 */
if ( ! function_exists( 'yith_wapo_is_addon_type_available' ) ) {
	function yith_wapo_is_addon_type_available( $addon_type ) {
		if ( $addon_type == '' || substr( $addon_type, 0, 5 ) === 'html_' || in_array( $addon_type, YITH_WAPO()->get_available_addon_types() ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Previous version check
 */
if ( ! function_exists( 'yith_wapo_previous_version_exists' ) ) {
	function yith_wapo_previous_version_exists() {
		global $wpdb;
		$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_groups WHERE del='0'";
		$groups = $wpdb->get_results( $query );
		return sizeof( $groups ) > 0 ? true : false;
	}
}


/**
 * Product has blocks
 */
if ( ! function_exists( 'yith_wapo_product_has_blocks' ) ) {
	function yith_wapo_product_has_blocks( $product ) {
		
	}
}
