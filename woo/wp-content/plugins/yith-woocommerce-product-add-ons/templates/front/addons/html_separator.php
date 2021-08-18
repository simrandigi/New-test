<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$separator_style = $addon->get_setting('separator_style');
$separator_width = $addon->get_setting('separator_width', 100);
$separator_size = $addon->get_setting('separator_size', 2);
$separator_color = $addon->get_setting('separator_color');

if ( $separator_style == 'empty_space' ) {
	$css = 'height: ' . $separator_size . 'px';
} else {
	$css = 'width: ' . $separator_width . '%; border-width: ' . $separator_size . 'px; border-color: ' . $separator_color . ';';
}

?>

<div class="yith-wapo-separator <?php echo $separator_style; ?>" style="<?php echo $css; ?>"></div>