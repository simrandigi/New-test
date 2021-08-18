<?php

/**
 * Main Product Add-ons Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH WooCommerce Product Add-ons
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO' ) ) {

	/**
	 *	YITH_WAPO Class
	 */
	class YITH_WAPO {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO
		 */
		public static $instance;

		/**
		 * Admin object
		 *
		 * @var YITH_WAPO_Admin
		 */
		public $admin;

		/**
		 * Front object
		 *
		 * @var YITH_WAPO_Front
		 */
		public $front;

		/**
		 * Cart object
		 *
		 * @var YITH_WAPO_Cart
		 */
		public $cart;

		/**
         * Check if YITH Multi Vendor is installed
         *
         * @var boolean
         * @since 1.0.0
         */
        public static $is_vendor_installed;

        /**
         * Check if WPML is installed
         *
         * @var boolean
         * @since 1.0.0
         */
        public static $is_wpml_installed;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO|YITH_WAPO_Premium
		 */
		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		private function __construct() {

			// Load Plugin Framework
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// Actions
			if ( isset( $_REQUEST['wapo_action'] ) ) {
				if ( $_REQUEST['wapo_action'] == 'save-block' ) { $this->save_block( $_REQUEST ); }
				if ( $_REQUEST['wapo_action'] == 'duplicate-block' ) { $this->duplicate_block( $_REQUEST['block_id'] ); }
				if ( $_REQUEST['wapo_action'] == 'remove-block' ) { $this->remove_block( $_REQUEST['block_id'] ); }
				if ( $_REQUEST['wapo_action'] == 'save-addon' ) { $this->save_addon( $_REQUEST ); }
				if ( $_REQUEST['wapo_action'] == 'duplicate-addon' ) { $this->duplicate_addon( $_REQUEST['block_id'], $_REQUEST['addon_id'] ); }
				if ( $_REQUEST['wapo_action'] == 'remove-addon' ) { $this->remove_addon( $_REQUEST['block_id'], $_REQUEST['addon_id'] ); }
			}

			// Admin
			if ( is_admin() && ( ! isset( $_REQUEST['action'] ) || ( isset( $_REQUEST['action'] ) && 'yith_load_product_quick_view' !== $_REQUEST['action'] ) ) ) {
				$this->admin = YITH_WAPO_Admin();
			}

			// Front
			$is_ajax_request = defined( 'DOING_AJAX' ) && DOING_AJAX;
			if ( ! is_admin() || $is_ajax_request ) {
				$this->front = YITH_WAPO_Front();
				$this->cart = YITH_WAPO_Cart();
			}

			// yith_wapo_compatibility();

			// wccl settings
			$wccl_enable_in_loop = apply_filters( 'yith_wapo_wccl_enable_in_loop', 'no' );
			update_option( 'yith-wccl-enable-in-loop', $wccl_enable_in_loop );
		}

		/**
		 * Load Plugin Framework
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {
			if ( function_exists( 'YIT_Plugin_Licence' ) ) {
				YIT_Plugin_Licence()->register( YITH_WAPO_INIT, YITH_WAPO_SECRET_KEY, YITH_WAPO_SLUG );
			}
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			if ( function_exists( 'YIT_Upgrade' ) ) {
				YIT_Upgrade()->register( YITH_WAPO_SLUG, YITH_WAPO_INIT );
			}
		}

		/**
		 * Get HTML types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_html_types() {
			$html_types = array(
				array(
					'slug' => 'html_heading',
					'name' => __( 'Heading', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'html_text',
					'name' => __( 'Text', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'html_separator',
					'name' => __( 'Separator', 'yith-woocommerce-product-add-ons' ),
				),
			);
			return $html_types;
		}

		/**
		 * Get addon types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_addon_types() {
			$addon_types = array(
				array(
					'slug' => 'checkbox',
					'name' => __( 'Checkbox', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'radio',
					'name' => __( 'Radio', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'text',
					'name' => __( 'Input text', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'textarea',
					'name' => __( 'Textarea', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'color',
					'name' => __( 'Color swatch', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'number',
					'name' => __( 'Number', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'select',
					'name' => __( 'Select', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'label',
					'name' => __( 'Label or image', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'product',
					'name' => __( 'Product', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'date',
					'name' => __( 'Date', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'slug' => 'file',
					'name' => __( 'File upload', 'yith-woocommerce-product-add-ons' ),
				),
			);
			return $addon_types;
		}

		/**
		 * Get available addon types
		 *
		 * @return array
		 * @since 2.0.0
		 */
		public function get_available_addon_types() {
			return array( 'checkbox', 'radio', 'text', 'select' );
		}

		/**
		 * Save Block
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function save_block( $request ) {
			global $wpdb;

			if ( isset( $request['block_id'] ) ) {

				$rules = array(
					'show_in'						=> isset( $request['block_rule_show_in'] )							? $request['block_rule_show_in']						: 'all',
					'show_in_products'				=> isset( $request['block_rule_show_in_products'] )					? $request['block_rule_show_in_products']				: '',
					'show_in_categories'			=> isset( $request['block_rule_show_in_categories'] )				? $request['block_rule_show_in_categories']				: '',
					'exclude_products'				=> isset( $request['block_rule_exclude_products'] )					? $request['block_rule_exclude_products']				: '',
					'exclude_products_products'		=> isset( $request['block_rule_exclude_products_products'] )		? $request['block_rule_exclude_products_products']		: '',
					'exclude_products_categories'	=> isset( $request['block_rule_exclude_products_categories'] )		? $request['block_rule_exclude_products_categories']	: '',
					'show_to'						=> isset( $request['block_rule_show_to'] )							? $request['block_rule_show_to']						: '',
					'show_to_user_roles'			=> isset( $request['block_rule_show_to_user_roles'] )				? $request['block_rule_show_to_user_roles']				: '',
					'show_to_membership'			=> isset( $request['block_rule_show_to_membership'] )				? $request['block_rule_show_to_membership']				: '',
				);

				$settings = array(
					'name'		=> isset( $request['block_name'] )		? $request['block_name']		: '',
					'priority'	=> isset( $request['block_priority'] )	? $request['block_priority']	: 0,
					'rules'		=> $rules,
				);

				// YITH Multi Vendor integration
				$vendor_id = 0;
				if ( isset( $request['vendor_id'] ) ) {
					$vendor_id = $request['vendor_id'];
				} else if ( class_exists( 'YITH_Vendors' ) ) {
					$vendor = yith_get_vendor( 'current', 'user' );
					if ( $vendor->is_valid() ) {
						$vendor_id = $vendor->id;
					}
				}

				$data = array(
					'user_id'		=> isset( $request['user_id'] ) ? $request['user_id'] : get_current_user_id(),
					'vendor_id'		=> $vendor_id,
					'settings'		=> serialize( $settings ),
					'priority'		=> isset( $request['block_priority'] ) ? $request['block_priority'] : 0,
					'visibility'	=> 1,
				);

				$table = $wpdb->prefix . 'yith_wapo_blocks';
			
				if ( $request['block_id'] == 'new' ) {

					if ( ! isset( $request['block_priority'] ) || $request['block_priority'] == 0 ) {
						$new_priority = 0;
						// Get max priority value
						$max_priority = $wpdb->get_var( "SELECT MAX(priority) FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0'" );
						// Get number of blocks
						$res_priority = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE deleted='0'" );
						$total_blocks = $wpdb->num_rows;
						// New priority value
						if ( $max_priority > 0 && $total_blocks > 0 ) {
							$new_priority = $max_priority > $total_blocks ? $max_priority : $total_blocks;
						}
						$data['priority'] = $new_priority + 1;
					}

					$wpdb->insert( $table, $data );
					$block_id = $wpdb->insert_id;

					// New priority value
					/*
					$priority_data = array( 'priority' => $block_id );
					$wpdb->update( $table, $priority_data, array( 'id' => $block_id ) );
					*/

				} else if ( $request['block_id'] > 0 ) {
					$block_id = $request['block_id'];
					$wpdb->update( $table, $data, array( 'id' => $block_id ) );
				}
				
				if ( isset( $request['add_options_after_save'] ) ) {
					wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id . '&addon_id=new' ) );
				} else if ( isset( $request['wapo_action'] ) && $request['wapo_action'] == 'save-block' ) {
					wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );
				} else {
					return $block_id;
				}

			}

		}

		/**
		 * Duplicate Block
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function duplicate_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE id='$block_id'";
				$row = $wpdb->get_row( $query );

				if ( isset( $row ) && $row->id == $block_id ) {

					$data = array(
						'settings'		=> $row->settings,
						'priority'		=> $row->priority,
						'visibility'	=> $row->visibility,
					);

					$table = $wpdb->prefix . 'yith_wapo_blocks';
					$wpdb->insert( $table, $data );
					$block_id = $wpdb->insert_id;

					wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );

				}

			}

		}

		/**
		 * Remove Block
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function remove_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {
				$query = "UPDATE {$wpdb->prefix}yith_wapo_blocks SET deleted='1' WHERE id='$block_id'";
				$result = $wpdb->query( $query );
				wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel' ) );
			}

		}

		/**
		 * Save Addon
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function save_addon( $request ) {
			global $wpdb;
			
			if ( isset( $request['block_id'] ) && $request['block_id'] == 'new' ) {
				$request['block_id'] = $this->save_block( array( 'block_id' => 'new' ) );
			}

			if ( isset( $request['addon_id'] ) && isset( $request['block_id'] ) && $request['block_id'] > 0 ) {

				$conditional_logic = array();

				$settings = array(

					// General
					'type' => isset( $request['addon_type'] ) ? $request['addon_type'] : '',
					
					// Display options
					'title'						=> isset( $request['addon_title'] )						? str_replace( '"', '&quot;', $request['addon_title'] )	: '',
					'show_image'				=> isset( $request['addon_show_image'] )				? $request['addon_show_image']				: '',
					'image'						=> isset( $request['addon_image'] )						? $request['addon_image']					: '',
					'image_replacement'			=> isset( $request['addon_image_replacement'] )			? $request['addon_image_replacement']		: '',
					'options_images_position'	=> isset( $request['addon_options_images_position'] )	? $request['addon_options_images_position']	: '',
					'show_as_toggle'			=> isset( $request['addon_show_as_toggle'] )			? $request['addon_show_as_toggle']			: '',
					'hide_options_images'		=> isset( $request['addon_hide_options_images'] )		? $request['addon_hide_options_images']		: '',
					'hide_options_label'		=> isset( $request['addon_hide_options_label'] )		? $request['addon_hide_options_label']		: '',
					'hide_options_prices'		=> isset( $request['addon_hide_options_prices'] )		? $request['addon_hide_options_prices']		: '',
					'hide_products_prices'		=> isset( $request['addon_hide_products_prices'] )		? $request['addon_hide_products_prices']	: '',
					'show_add_to_cart'			=> isset( $request['addon_show_add_to_cart'] )			? $request['addon_show_add_to_cart']		: '',
					'show_in_a_grid'			=> isset( $request['addon_show_in_a_grid'] )			? $request['addon_show_in_a_grid']			: '',
					'options_per_row'			=> isset( $request['addon_options_per_row'] )			? $request['addon_options_per_row']			: '',
					'options_width'				=> isset( $request['addon_options_width'] )				? $request['addon_options_width']			: '',
					// 'show_quantity_selector'	=> isset( $request['addon_show_quantity_selector'] )	? $request['addon_show_quantity_selector']	: '',

					// Style settings
					'custom_style'				=> isset( $request['addon_custom_style'] )				? $request['addon_custom_style']			: '',
					'image_position'			=> isset( $request['addon_image_position'] )			? $request['addon_image_position']			: '',
					'label_content_align'		=> isset( $request['addon_label_content_align'] )		? $request['addon_label_content_align']		: '',
					'image_equal_height'		=> isset( $request['addon_image_equal_height'] )		? $request['addon_image_equal_height']		: '',
					'images_height'				=> isset( $request['addon_images_height'] )				? $request['addon_images_height']			: '',
					'label_position'			=> isset( $request['addon_label_position'] )			? $request['addon_label_position']			: '',
					'label_padding'				=> isset( $request['addon_label_padding'] )				? $request['addon_label_padding']			: '',
					'description_position'		=> isset( $request['addon_description_position'] )		? $request['addon_description_position']	: '',

					// Conditional logic
					'enable_rules'					=> isset( $request['addon_enable_rules'] )					? $request['addon_enable_rules']					: '',
					'conditional_logic_display'		=> isset( $request['addon_conditional_logic_display'] )		? $request['addon_conditional_logic_display']		: '',
					'conditional_logic_display_if'	=> isset( $request['addon_conditional_logic_display_if'] )	? $request['addon_conditional_logic_display_if']	: '',
					'conditional_rule_addon'		=> isset( $request['addon_conditional_rule_addon'] )		? $request['addon_conditional_rule_addon']			: '',
					'conditional_rule_addon_is'		=> isset( $request['addon_conditional_rule_addon_is'] )		? $request['addon_conditional_rule_addon_is']		: '',

					// Advanced options
					'first_options_selected'	=> isset( $request['addon_first_options_selected'] )	? $request['addon_first_options_selected']	: '',
					'first_free_options'		=> isset( $request['addon_first_free_options'] )		? $request['addon_first_free_options']		: '',
					'selection_type'			=> isset( $request['addon_selection_type'] )			? $request['addon_selection_type']			: '',
					'enable_min_max'			=> isset( $request['addon_enable_min_max'] )			? $request['addon_enable_min_max']			: '',
					'min_max_rule'				=> isset( $request['addon_min_max_rule'] )				? $request['addon_min_max_rule']			: '',
					'min_max_value'				=> isset( $request['addon_min_max_value'] )				? $request['addon_min_max_value']			: '',

					// HTML elements
					'text_content'		=> isset( $request['option_text_content'] )		? str_replace( '"', '&quot;', $request['option_text_content'] )	: '',
					'heading_text'		=> isset( $request['option_heading_text'] )		? str_replace( '"', '&quot;', $request['option_heading_text'] )	: '',
					'heading_type'		=> isset( $request['option_heading_type'] )		? $request['option_heading_type']		: '',
					'heading_color'		=> isset( $request['option_heading_color'] )	? $request['option_heading_color']		: '',
					'separator_style'	=> isset( $request['option_separator_style'] )	? $request['option_separator_style']	: '',
					'separator_width'	=> isset( $request['option_separator_width'] )	? $request['option_separator_width']		: '',
					'separator_size'	=> isset( $request['option_separator_size'] )	? $request['option_separator_size']		: '',
					'separator_color'	=> isset( $request['option_separator_color'] )	? $request['option_separator_color']	: '',

					// Rules
					'conditional_logic'	=> $conditional_logic,
				);
			
				$data = array(
					'block_id'		=> $request['block_id'],
					'settings'		=> serialize( $settings ),
					'options'		=> serialize( isset( $request['options'] ) ? $request['options'] : '' ),
					'visibility'	=> 1,
				);

				$table = $wpdb->prefix . 'yith_wapo_addons';

				if ( $request['addon_id'] == 'new' ) {
					$wpdb->insert( $table, $data );
					$addon_id = $wpdb->insert_id;

					// New priority value
					$priority_data = array( 'priority' => $addon_id );
					$wpdb->update( $table, $priority_data, array( 'id' => $addon_id ) );

				} else if ( $request['addon_id'] > 0 ) {
					$addon_id = $request['addon_id'];
					$wpdb->update( $table, $data, array( 'id' => $addon_id ) );
				}

				if ( isset( $request['wapo_action'] ) && $request['wapo_action'] == 'save-addon' ) {
					wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $request['block_id'] ));
				} else {
					return $addon_id;
				}

			}

		}

		/**
		 * Duplicate Addon
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function duplicate_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_addons WHERE id='$addon_id'";
				$row = $wpdb->get_row( $query );
			
				$data = array(
					'block_id'		=> $row->block_id,
					'settings'		=> $row->settings,
					'options'		=> $row->options,
					'priority'		=> $row->priority,
					'visibility'	=> $row->visibility,
				);

				$table = $wpdb->prefix . 'yith_wapo_addons';
				$wpdb->insert( $table, $data );
				$addon_id = $wpdb->insert_id;

				wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );

			}

		}

		/**
		 * Remove Addon
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function remove_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {
				$query = "UPDATE {$wpdb->prefix}yith_wapo_addons SET deleted='1' WHERE id='$addon_id'";
				$result = $wpdb->query( $query );
				wp_redirect( admin_url( '/admin.php?page=yith_wapo_panel&tab=blocks&block_id=' . $block_id ) );
			}

		}

		/**
		 * Save Option
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function save_option( $request ) {
			global $wpdb;
		}

		/**
		 *	Is Quick View
		 * 
		 *	@return bool
		 */
		private function is_quick_view() {
			return ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'yit_load_product_quick_view' || $_REQUEST['action'] == 'yith_load_product_quick_view'  || $_REQUEST['action'] == 'ux_quickview' ) ) ? true : false;
		}

		/**
		 * Get Current MultiVendor
		 * 
		 * @return null|YITH_Vendor
		 */
		public static function get_current_multivendor() {
			if ( YITH_WAPO::$is_vendor_installed && is_user_logged_in() ) {
				$vendor = yith_get_vendor( 'current', 'user' );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Get MultiVendor by ID
		 * 
		 * @param $id
		 * @param string $obj
		 * @return null|YITH_Vendor
		 */
		public static function get_multivendor_by_id( $id , $obj='vendor' ) {
			if ( YITH_WAPO::$is_vendor_installed ) {
				$vendor = yith_get_vendor( $id, $obj );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Is Plugin Enabled for Vendors
		 * 
		 * @return bool
		 */
		public static function is_plugin_enabled_for_vendors() {
			return get_option('yith_wpv_vendors_option_advanced_product_options_management') == 'yes';
		}

		/**
		 * Divi Builder Module Integration
		 * 
		 * @return void
		 */
		function divi_et_builder_module_integration() {
			if ( class_exists( 'ET_Builder_Module' ) ) {
				// include YITH_WAPO_DIR . 'includes/integrations/class.divi-et-builder_module.php';
			}
		}

	}
}

/**
 * Unique access to instance of YITH_WAPO class
 *
 * @return YITH_WAPO|YITH_WAPO_Premium
 * @since 1.0.0
 */
function YITH_WAPO() {
	return YITH_WAPO::get_instance();
}
