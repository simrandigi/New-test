<?php

/**
 *	Addon Template
 *
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

$text_content = $addon->get_setting('text_content');

?>

<p>
	<?php echo $text_content; ?>
</p>