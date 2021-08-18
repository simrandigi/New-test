<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$heading_text = $addon->get_setting('heading_text');
$heading_type = $addon->get_setting('heading_type');
$heading_color = $addon->get_setting('heading_color');

?>

<<?php echo $heading_type; ?> style="color: <?php echo $heading_color; ?>;">

	<?php echo $heading_text; ?>

</<?php echo $heading_type; ?>>