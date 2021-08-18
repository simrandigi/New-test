<?php

/**
 *	Addon Class
 *
 *	@author  Corrado Porzio <corradoporzio@gmail.com>
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Addon' ) ) {

	/**
	 *	Addon class.
	 *	The class manage all the Addon behaviors.
	 */
	class YITH_WAPO_Addon {

		/**
		 *	ID
		 *
		 *	@var int
		 */
		public $id = 0;

		/**
		 *	Settings
		 *
		 *	@var array
		 */
		public $settings = array();

		/**
		 *	Options
		 *
		 *	@var array
		 */
		public $options = array();

		/**
		 *	Priority
		 *
		 *	@var int
		 */
		public $priority = 0;

		/**
		 *	Visibility
		 *
		 *	@var array
		 */
		public $visibility = 1;

		/**
		 *	Type
		 *
		 *	@var string
		 */
		public $type = 0;

		/**
		 *	Constructor
		 */
		public function __construct( $id ) {

			global $wpdb;

			if ( $id > 0 ) {

				$row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}yith_wapo_addons WHERE id='$id'" );

				if ( isset( $row ) && $row->id == $id ) {

					$this->id			= $row->id;
					$this->settings		= @unserialize( $row->settings );
					$this->options		= @unserialize( $row->options );
					$this->priority		= $row->priority;
					$this->visibility	= $row->visibility;

					// Settings
					$this->type	= isset( $this->settings['type'] ) ? $this->settings['type'] : 'html_text';

				}

			}

		}

		/**
		 *	Get Setting
		 */
		public function get_setting( $option, $default = '' ) {
			return isset( $this->settings[ $option ] ) ? $this->settings[ $option ] : $default;
		}

		/**
		 *	Get Option
		 */
		public function get_option( $option, $index, $default = '' ) {
			if ( is_array( $this->options )
				&& isset( $this->options[ $option ] )
				&& is_array( $this->options[ $option ] )
				&& isset( $this->options[ $option ][ $index ] ) ) {
				return $this->options[ $option ][ $index ];
			}
			return $default;
		}

		/**
		 *	Get Option Price
		 */
		public function get_option_price( $index ) {
			global $product, $variation;
			$product_price = YITH_WAPO_Front()->current_product_price;
			$price = 0;
			if ( $this->get_option( 'price_method', $index ) != 'free' ) {
				if ( $this->get_option( 'price_type', $index ) == 'percentage' ) {
					// $product_price = floatval( $variation ? $variation->regular_price : $product->get_price() );
					$option_percentage = floatval( $this->get_option( 'price', $index ) );
					$option_percentage_sale = floatval( $this->get_option( 'price_sale', $index ) );
					$price = ( $product_price / 100 ) * $option_percentage;
				} else {
					$price = $this->get_option( 'price', $index );
				}
				if ( $this->get_option( 'price_method', $index ) == 'decrease' ) {
					$price = -1 * $price;
				}
				$price = $price + ( ( $price / 100 ) * yith_wapo_get_tax_rate() );
			}
			return apply_filters( 'yith_wapo_option_' . $this->id . '_' . $index . '_price', $price );
		}

		/**
		 *	Get Option Price HTML
		 */
		public function get_option_price_html( $index ) {
			global $product, $variation;
			$html_price = '';
			$product_price = YITH_WAPO_Front()->current_product_price;
			if ( $this->get_option( 'price_method', $index ) != 'free' ) {
				if ( $this->get_option( 'price_type', $index ) == 'percentage' ) {
					// $product_price = floatval( $variation ? $variation->regular_price : $product->get_price() );
					$option_percentage = floatval( $this->get_option( 'price', $index ) );
					$option_percentage_sale = floatval( $this->get_option( 'price_sale', $index ) );
					$option_price = ( $product_price / 100 ) * $option_percentage;
					$option_price_sale = ( $product_price / 100 ) * $option_percentage_sale;
				} else {
					$option_price = $this->get_option( 'price', $index );
					$option_price_sale = $this->get_option( 'price_sale', $index );
				}
				$sign = '+';
				$sign_class = 'positive';
				if ( $this->get_option( 'price_method', $index ) == 'decrease' ) {
					$sign = '-';
					$sign_class = 'negative';
					$option_price_sale = 0;
				}
				if ( $option_price != '' ) {
					$option_price = $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() );
					if ( $option_price_sale != '' && $option_price_sale > 0 ) {
						$html_price = '<small class="option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span><del>' . wc_price( $option_price ) . '</del> ' . wc_price( $option_price_sale ) . '<span class="brackets">)</span></small>';
					} else {
						$html_price = '<small class="option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span>' . wc_price( $option_price ) . '<span class="brackets">)</span></small>';
					}
				}
			}
			return apply_filters( 'yith_wapo_option_' . $this->id . '_' . $index . '_price_html', $html_price );
		}

	}

}