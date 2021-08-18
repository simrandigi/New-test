<?php

/**
 *	Block Class
 *
 *	@author  Corrado Porzio <corradoporzio@gmail.com>
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Block' ) ) {

	/**
	 *	Block class.
	 *	The class manage all the Block behaviors.
	 */
	class YITH_WAPO_Block {

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
		 *	Visibility
		 *
		 *	@var array
		 */
		public $visibility = 1;

		/**
		 *	Name
		 *
		 *	@var string
		 */
		public $name = '';

		/**
		 *	Priority
		 *
		 *	@var int
		 */
		public $priority = 0;

		/**
		 *	Rules
		 *
		 *	@var array
		 */
		public $rules = array();

		/**
		 *	Constructor
		 */
		public function __construct( $id ) {

			global $wpdb;

			if ( $id > 0 ) {

				$query = "SELECT * FROM {$wpdb->prefix}yith_wapo_blocks WHERE id='$id'";
				$row = $wpdb->get_row( $query );

				if ( isset( $row ) && $row->id == $id ) {

					$this->id			= $row->id;
					$this->settings		= @unserialize( $row->settings );
					$this->priority		= $row->priority;
					$this->visibility	= $row->visibility;

					// Settings
					$this->name		= isset( $this->settings['name'] ) ? $this->settings['name'] : '';
					$this->rules	= isset( $this->settings['rules'] ) ? $this->settings['rules'] : array();

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
		 *	Get Rule
		 */
		public function get_rule( $name, $default = '' ) {
			return isset( $this->rules[ $name ] ) ? $this->rules[ $name ] : $default;
		}

	}

}