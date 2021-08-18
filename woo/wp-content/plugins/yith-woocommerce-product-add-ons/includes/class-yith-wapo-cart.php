<?php

/**
 *	Cart Class
 *
 *	@author  Corrado Porzio <corradoporzio@gmail.com>
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Cart' ) ) {

	/**
	 *	YITH_WAPO Cart Class
	 */
	class YITH_WAPO_Cart {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO_Instance
		 */
		public static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO_Instance
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Loop add to cart button
			if ( get_option( 'yith_wapo_button_in_shop' ) == 'select' ) {
				add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 50, 1 );
				add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 1 );
				add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 6 );
			}

			// Add to cart validation
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 10, 4 );
			// Add options to cart item
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 25, 2 );
			// Display custom product thumbnail in cart
			if ( get_option( 'yith_wapo_show_image_in_cart', 'no' ) == 'yes' ) {
				add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 3 );
			}
			// Display options in cart and checkout page.
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 25, 2 );
			// Before calculate totals
			add_action( 'woocommerce_before_calculate_totals', array( $this, 'before_calculate_totals' ), 9999, 1);
			// Update cart total
			// add_filter( 'woocommerce_calculated_total', array( $this, 'custom_calculated_total' ), 10, 2 );
			// Add order item meta
			add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta' ), 10, 3 );

		}

		// Validation addons
		function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null ) {

			if ( false /* empty( $_POST['field'] ) */ ) {
				$passed = false;
				wc_add_notice( __( 'Required field.', 'yith-woocommerce-product-add-ons' ), 'error' );
			}

			// Disable add_to_cart_button class on shop page
			if ( is_ajax() && ! isset( $_REQUEST['yith_wapo_is_single'] ) ) {
				return false;
			}

			return $passed;
		}

		// Set the data for the cart item in cart object
		function add_cart_item_data( $cart_item_data, $product_id, $post_data = null, $sold_individually = false ) {
			if ( is_null( $post_data ) ) { $post_data = $_POST; }
			$data = array();
			if ( isset( $post_data['yith_wapo'] ) && is_array( $post_data['yith_wapo'] ) ) {
				$cart_item_data['yith_wapo_product_img'] = $post_data['yith_wapo_product_img'];
				foreach ( $post_data['yith_wapo'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						$cart_item_data['yith_wapo_options'][$index][$key] = $data[$key] = $value;
					}
				}
			}
			return $cart_item_data;
		}

		// Change the product image with the addon one (if selected)
		function cart_item_thumbnail ( $_product_img, $cart_item, $cart_item_key ) {
			if ( isset( $cart_item['yith_wapo_product_img'] ) ) {
				$image_url = $cart_item['yith_wapo_product_img'];
				if ( ! empty( $image_url ) ) {
					return '<img src="' . $image_url . '" />';
				}
			}
			return $_product_img;
		}
		
		// Update cart items info
		function get_item_data( $cart_data, $cart_item ) {
			if ( ! empty( $cart_item['yith_wapo_options'] ) ) {
				// $total_options_price = 0;
				$cart_data_array = array();
				$first_free_options_count = 0;
				foreach ( $cart_item['yith_wapo_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && $value ) {

							$explode = explode( '-', $key );
							if ( isset( $explode[1] ) ) {
								$addon_id = $explode[0];
								$option_id = $explode[1];
							} else {
								$addon_id = $key;
								$option_id = $value;
							}

							$info = yith_wapo_get_option_info( $addon_id, $option_id );

							if ( $info['price_type'] == 'percentage' ) {

								$_product = wc_get_product( $cart_item['product_id'] );
								// WooCommerce Measurement Price Calculator (compatibility)
								if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) { $product_price = $cart_item['pricing_item_meta_data']['_price']; }
								else { $product_price = floatval( $_product->get_price() ); }

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

							$sign = $info['price_method'] == 'decrease' ? '-' : '+';

							// First X free options check
							if ( $info['addon_first_options_selected'] == 'yes' && $first_free_options_count < $info['addon_first_free_options'] ) {
								$option_price = 0;
								$first_free_options_count++;
							} else {
								$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;
							}

							$cart_data_name = ( ( isset( $info['addon_label'] ) && $info['addon_label'] != '' ) ? $info['addon_label'] : '' );

							if ( in_array( $info['addon_type'], array( 'checkbox', 'color', 'label', 'radio', 'select' ) ) ) {
								$value = $info['label'];
							} else if ( in_array( $info['addon_type'], array( 'product' ) ) ) {
								$option_product_info = explode( '-', $value );
								$option_product_id = $option_product_info[1];
								$option_product_qty = $option_product_info[2];
								$option_product = wc_get_product( $option_product_id );
								$value = $option_product->get_title();

								// product prices
								$product_price = $option_product->get_price();
								if ( $info['price_method'] == 'product' ) {
									$option_price = $product_price;
								} else if ( $info['price_method'] == 'discount' ) {
									$option_discount_value = $option_price;
									$option_price = $product_price - $option_discount_value;
									if ( $info['price_type'] == 'percentage' ) {
										$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
									}
								}

							} else if ( in_array( $info['addon_type'], array( 'file' ) ) ) {
								$file_url = explode( '/', $value );
								$value = '<a href="' . $value . '" target="_blank">' . end( $file_url ) . '</a>';
							} else {
								$cart_data_name = $info['label'];
							}

							$option_price = $option_price != '' ? ( $option_price + ( ( $option_price / 100 ) * yith_wapo_get_tax_rate() ) ) : 0;

							if ( get_option( 'yith_wapo_show_options_in_cart' ) == 'yes' ) {
								if ( ! isset( $cart_data_array[ $cart_data_name ] ) ) { $cart_data_array[ $cart_data_name ] = ''; }
								$cart_data_array[ $cart_data_name ] .= '<div>' . $value . ( $option_price != '' ? ' (' . $sign . wc_price( $option_price ) . ')' : '' ) . '</div>';
							}

						}
					}
				}
				foreach ( $cart_data_array as $key => $value ) {
					$cart_data[] = array(
						'name'    => $key,
						'display' => $value,
					);
				}

			}
			return $cart_data;
		}

		// Calculate cart items prices
		function before_calculate_totals( $cart ) {

			// This is necessary for WC 3.0+
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) { return; }

			// Avoiding hook repetition (when using price calculations for example)
			if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) { return; }

			// Loop through cart items
			foreach ( $cart->get_cart() as $cart_item ) {

				if ( ! empty( $cart_item['yith_wapo_options'] ) ) {
					$total_options_price = 0;
					$first_free_options_count = 0;
					foreach ( $cart_item['yith_wapo_options'] as $index => $option ) {
						foreach ( $option as $key => $value ) {
							if ( $key && $value ) {

								$explode = explode( '-', $key );
								if ( isset( $explode[1] ) ) {
									$addon_id = $explode[0];
									$option_id = $explode[1];
								} else {
									$addon_id = $key;
									$option_id = $value;
								}

								$info = yith_wapo_get_option_info( $addon_id, $option_id );

								if ( $info['price_type'] == 'percentage' ) {
									$_product = wc_get_product( $cart_item['product_id'] );
									// WooCommerce Measurement Price Calculator (compatibility)
									if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
										$product_price = $cart_item['pricing_item_meta_data']['_price'];
									} else {
										$product_price = floatval( $_product->get_price() );
									}
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

								// First X free options check
								if ( $info['addon_first_options_selected'] == 'yes' && $first_free_options_count < $info['addon_first_free_options'] ) {
									$first_free_options_count++;
								} else {
									$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;


									if ( in_array( $info['addon_type'], array( 'product' ) ) && ( $info['price_method'] == 'product' || $info['price_method'] == 'discount' ) ) {
										$option_product_info = explode( '-', $value );
										$option_product_id = $option_product_info[1];
										$option_product_qty = $option_product_info[2];
										$option_product = wc_get_product( $option_product_id );
										$value = $option_product->get_title();
										$product_price = $option_product->get_price();
										if ( $info['price_method'] == 'product' ) {
											$option_price = $product_price;
										} else if ( $info['price_method'] == 'discount' ) {
											$option_discount_value = $option_price;
											$option_price = $product_price - $option_discount_value;
											if ( $info['price_type'] == 'percentage' ) {
												$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
											}
										}
										$total_options_price += floatval( $option_price );

									} else if ( $info['price_method'] == 'decrease' ) {
										$total_options_price -= floatval( $option_price );
									} else {
										$total_options_price += floatval( $option_price );
									}
								}

							}
						}
					}

					$cart_item_price = $cart_item['data']->get_price();
					$cart_item['data']->set_price( $cart_item_price + $total_options_price );

				}

			}

		}

		function add_order_item_meta( $item_id, $cart_item, $cart_item_key ) {
			if ( isset( $cart_item[ 'yith_wapo_options' ] ) ) {
				foreach( $cart_item[ 'yith_wapo_options' ] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && $value ) {

							$explode = explode( '-', $key );
							if ( isset( $explode[1] ) ) {
								$addon_id = $explode[0];
								$option_id = $explode[1];
							} else {
								$addon_id = $key;
								$option_id = $value;
							}

							$info = yith_wapo_get_option_info( $addon_id, $option_id );

							if ( $info['price_type'] == 'percentage' ) {
								$_product = wc_get_product( $cart_item['product_id'] );
								// WooCommerce Measurement Price Calculator (compatibility)
								if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
									$product_price = $cart_item['pricing_item_meta_data']['_price'];
								} else {
									$product_price = floatval( $_product->get_price() );
								}
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

							$sign = $info['price_method'] == 'decrease' ? '-' : '+';

							$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;

							$name = ( ( isset( $info['addon_label'] ) && $info['addon_label'] != '' ) ? $info['addon_label'] : '' );

							if ( in_array( $info['addon_type'], array( 'checkbox', 'color', 'label', 'radio', 'select' ) ) ) {
								$value = $info['label'];
							} else if ( in_array( $info['addon_type'], array( 'product' ) ) ) {
								$option_product_info = explode( '-', $value );
								$option_product_id = $option_product_info[1];
								$option_product_qty = $option_product_info[2];
								$option_product = wc_get_product( $option_product_id );
								$value = $option_product->get_title();

								// product prices
								$product_price = $option_product->get_price();
								if ( $info['price_method'] == 'product' ) {
									$option_price = $product_price;
								} else if ( $info['price_method'] == 'discount' ) {
									$option_discount_value = $option_price;
									$option_price = $product_price - $option_discount_value;
									if ( $info['price_type'] == 'percentage' ) {
										$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
									}
								}

								// stock
								if ( $option_product->get_manage_stock() ) {
									$qty = ( isset( $cart_item['quantity'] ) && $cart_item['quantity'] > 1 ) ? $cart_item['quantity'] : 1;
									$stock_qty = $option_product->get_stock_quantity() - $qty;
									wc_update_product_stock( $option_product, $stock_qty , 'set' );
									wc_delete_product_transients( $option_product );
								}
							} else if ( in_array( $info['addon_type'], array( 'file' ) ) ) {
								$file_url = explode( '/', $value );
								$value = '<a href="' . $value . '" target="_blank">' . end( $file_url ) . '</a>';
							} else {
								$name = $info['label'];
							}

							$display_value = $value . ( $option_price != '' ? ' ( ' . $sign . ' ' . wc_price( $option_price ) . ' )' : '' );

							wc_add_order_item_meta( $item_id, $name, $display_value );
						}
					}
				}
			}
		}

		public function add_to_cart_url( $url = '' ) {
			global $product;
			$product_id = yit_get_base_product_id( $product );
			return get_permalink( $product_id );
		}

		public function add_to_cart_text( $text = '' ) {
			global $product, $post;
			if ( is_object( $product ) && ! is_single( $post ) ) {
				return get_option( 'yith_wapo_select_options_label', 'Select options' );
			}
			return $text;
		}

	}
}

/**
 * Unique access to instance of YITH_WAPO_Cart class
 *
 * @return YITH_WAPO_Cart
 */
function YITH_WAPO_Cart() {
	return YITH_WAPO_Cart::get_instance();
}
