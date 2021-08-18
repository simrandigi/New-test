<?php

/**
 *	Import Export Tab
 *
 *	@package YITH WooCommerce Product Add-ons
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$impexp = array(
	'impexp' => array(
		'impexp-tab' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wapo_impexp_tab'
		),
	),
);

return apply_filters( 'yith_wapo_panel_impexp_options', $impexp );
