<?php

/**
 *	Debug Tab
 *
 *	@package YITH WooCommerce Product Add-ons
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$debug = array(
	'debug' => array(
		'debug-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wapo_debug_tab'
		),
	),
);

return apply_filters( 'yith_wapo_panel_debug_options', $debug );
