<?php

/**
 *	Front Class
 *
 *	@author  Corrado Porzio <corradoporzio@gmail.com>
 *	@package YITH WooCommerce Product Add-ons
 *	@version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Front' ) ) {

	/**
	 *	Front class.
	 *	The class manage all the frontend behaviors.
	 */
	class YITH_WAPO_Front {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO_Front
		 */
		protected static $instance;

		/**
		 * Current product price
		 *
		 * @var float
		 */
		public $current_product_price = 0;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO_Front | YITH_WAPO_Front_Premium
		 */
		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );
			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Print Options
			if ( get_option( 'yith_wapo_options_position' ) == 'after' ) {
				add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'print_container' ) );
			} else { // Default
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'print_container' ) );
			}
			// Ajax live print
			add_action( 'wp_ajax_live_print_blocks', array( $this, 'live_print_blocks' ) );
			add_action( 'wp_ajax_nopriv_live_print_blocks', array( $this, 'live_print_blocks' ) );
			// Ajax upload file
			add_action( 'wp_ajax_upload_file', array( $this, 'ajax_upload_file' ) );
			add_action( 'wp_ajax_nopriv_upload_file', array( $this, 'ajax_upload_file' ) );

			// Shortcodes
			add_shortcode( 'yith_wapo_show_options', array( $this, 'yith_wapo_show_options_shortcode' ) );
			add_action( 'yith_wapo_show_options_shortcode', array( $this, 'print_container' ) );

		}

		/**
		 * Front enqueue scripts
		 */
		public function enqueue_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// CSS
			wp_enqueue_style( 'yith_wapo_front', YITH_WAPO_URL . 'assets/css/_new_yith-wapo-front.css', false, rand(10000,99999) );
			wp_enqueue_style( 'yith_wapo_jquery-ui', YITH_WAPO_URL . 'assets/css/_new_jquery-ui-1.12.1.css', false, rand(10000,99999) );
			wp_enqueue_style( 'yith_wapo_jquery-ui-timepicker', YITH_WAPO_URL . 'assets/css/_new_jquery-ui-timepicker-addon.css', false, rand(10000,99999) );

			// JS
			wp_register_script( 'yith_wapo_front', YITH_WAPO_URL . 'assets/js/_new_yith-wapo-front.js', array( 'jquery', 'jquery-ui-datepicker', 'wc-add-to-cart-variation' ), rand(10000,99999), true );
			wp_enqueue_script( 'yith_wapo_front' );
			wp_register_script( 'yith_wapo_jquery-ui-timepicker', YITH_WAPO_URL . 'assets/js/_new_jquery-ui-timepicker-addon.js', array( 'jquery', 'jquery-ui-datepicker', 'wc-add-to-cart-variation' ), rand(10000,99999), true );
			wp_enqueue_script( 'yith_wapo_jquery-ui-timepicker' );
			
		}

		public function ajax_upload_file() {

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile = $_FILES['file'];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				echo $movefile['url'];
			} else {
				echo 'ERROR!';
			}
			wp_die();

		}

		public function custom_price( $price, $product ) {
			return (float) $price;
		}

		public function custom_variable_price( $price, $variation, $product ) {
			return (float) $price;
		}

		public function live_print_blocks() {

			// Simple, grouped and external products
			add_filter( 'woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'custom_price' ), 99, 2 );
			// Variations 
			add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'custom_price' ), 99, 2 );
			add_filter( 'woocommerce_product_variation_get_price', array( $this, 'custom_price' ), 99, 2 );
			// Variable (price range)
			add_filter( 'woocommerce_variation_prices_price', array( $this, 'custom_variable_price' ), 99, 3 );
			add_filter( 'woocommerce_variation_prices_regular_price', array( $this, 'custom_variable_price' ), 99, 3 );

			global $woocommerce, $product, $variation;
			$woocommerce = WC();
			$product_id = 0;
			$variation_id = 0;
			$variation = false;
			foreach ( $_POST['addons'] as $key => $input ) {
				if ( $input['name'] == 'yith_wapo_product_id' ) {
					$product_id = $input['value'];
				}
				if ( $input['name'] == 'variation_id' ) {
					$variation_id = $input['value'];
					if ( $variation_id > 0 ) {
						$variation = new WC_Product_Variation( $variation_id );
					}
				}
			}

			$product = wc_get_product( $product_id );
			$this->print_blocks();
			wp_die();
		}

		public function print_container() {
			global $product;

			$exclude_global = apply_filters( 'yith_wapo_exclude_global', get_post_meta( $product->get_id(), '_wapo_disable_global', true ) === 'yes' ? 1 : 0 );
			if ( ! $exclude_global ) {
				do_action( 'yith_wapo_before_main_container' ); ?>
				<div id="yith-wapo-container">
					<script type="text/javascript">
						var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
						var wapo_upload_allowed_file_types = '<?php echo get_option( 'yith_wapo_upload_allowed_file_types' ); ?>';
						var wapo_upload_max_file_size = '<?php echo get_option( 'yith_wapo_upload_max_file_size' ); ?>';
					</script>
					<?php $this->print_blocks(); ?>
				</div><!-- #yith-wapo-container -->
				<?php
				do_action( 'yith_wapo_before_main_container' );
			}
		}

		public function print_blocks() {

			global $product, $variation;

			$blocks_product_price = floatval( isset( $_POST['price'] ) ? $_POST['price'] : ( $variation ? $variation->regular_price : $product->get_price() ) );
			$blocks_product_price = $blocks_product_price + ( ( $blocks_product_price / 100 ) * yith_wapo_get_tax_rate() );
			$this->current_product_price = $blocks_product_price;

			echo '<input type="hidden" id="yith_wapo_product_id" name="yith_wapo_product_id" value="' . $product->get_id() . '">';
			echo '<input type="hidden" id="yith_wapo_product_img" name="yith_wapo_product_img" value="">';
			
			$color_array_default = array( 'color' => '' );
			$dimensions_array_default = array( 'dimensions' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', ) );

			$setting_hide_images = get_option( 'yith_wapo_hide_images' );

			// Style options
			$style_addon_titles = get_option( 'yith_wapo_style_addon_titles', 'h3' );
			$style_addon_background = get_option( 'yith_wapo_style_addon_background', $color_array_default )['color'];
			$style_addon_padding = get_option( 'yith_wapo_style_addon_padding', $dimensions_array_default )['dimensions'];
			$style_form_style = get_option( 'yith_wapo_style_form_style' );
			$style_accent_color = get_option( 'yith_wapo_style_accent_color', $color_array_default )['color'];
			$style_borders_color = get_option( 'yith_wapo_style_borders_color', $color_array_default )['color'];
			$style_label_font_size = get_option( 'yith_wapo_style_label_font_size' );
			$style_description_font_size = get_option( 'yith_wapo_style_description_font_size' );
			// Color Swatches
			$style_color_swatch_style = get_option( 'yith_wapo_style_color_swatch_style' );
			$style_color_swatch_size = get_option( 'yith_wapo_style_color_swatch_size' );
			// Label / Images
			$style_images_position = get_option( 'yith_wapo_style_images_position' );
			$style_images_equal_height = get_option( 'yith_wapo_style_images_equal_height' );
			$style_images_height = get_option( 'yith_wapo_style_images_height' );
			$style_label_position = get_option( 'yith_wapo_style_label_position' );
			$style_description_position = get_option( 'yith_wapo_style_description_position' );
			$style_label_padding = get_option( 'yith_wapo_style_label_padding', $dimensions_array_default )['dimensions'];

			// $product_categories = (array) wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) );
			$product_categories = $product->get_category_ids();

			$show_addons_hook = false;
			$show_total_price_box = false;
			foreach ( yith_wapo_get_blocks() as $key => $block ) {

				if ( $block->visibility == 1 ) {

					$show_in = $block->get_rule('show_in');
					$included_product_check = in_array( $product->get_id(), (array) $block->get_rule('show_in_products') );
					$included_category_check = count( array_intersect( (array) $block->get_rule('show_in_categories'), $product_categories ) ) > 0;
					$exclude_in = $block->get_rule('exclude_products');
					$excluded_product_check = ( $show_in == 'all' || $show_in == 'categories' ) && in_array( $product->get_id(), (array) $block->get_rule('exclude_products_products') );
					$excluded_categories_check = $show_in == 'all' && count( array_intersect( (array) $block->get_rule('exclude_products_categories'), $product_categories ) ) > 0;

					$show_to = $block->get_rule('show_to');
					$show_to_user_roles = $block->get_rule('show_to_user_roles');
					$show_to_membership = $block->get_rule('show_to_membership');

					// Include
					if ( $show_in == 'all' || ( $show_in == 'products' && ( $included_product_check || $included_category_check ) ) ) {
						// Exclude
						if (
							$exclude_in != 'yes'
							|| ( $exclude_in == 'yes' && ! $excluded_product_check && ! $excluded_categories_check )
						) {
							// Show to
							if (
								$show_to == ''
								|| $show_to == 'all'
								|| ( $show_to == 'logged_users' && is_user_logged_in() )
								// || ( $show_to == 'user_roles' && in_array( $show_to_user_roles, (array) wp_get_current_user()->roles ) )
								|| ( $show_to == 'user_roles' && count( array_intersect( (array) $show_to_user_roles , (array) wp_get_current_user()->roles ) ) > 0 )
								|| ( $show_to == 'membership' && yith_wcmbs_user_has_membership( get_current_user_id(), $show_to_membership ) )
							) {
								$addons = yith_wapo_get_addons_by_block_id( $block->id );
								$total_addons = sizeof( $addons );
								if ( $total_addons > 0 ) {
									if ( ! $show_addons_hook ) {
										$show_addons_hook = true;
										do_action( 'yith_wapo_before_addons' );
									}
									$show_total_price_box = true;
									include YITH_WAPO_DIR . '/templates/front/block.php';
								}
							}
						}
					}
				}
			}
			if ( $show_addons_hook ) {
				do_action( 'yith_wapo_after_addons' );
			}

			$total_price_box = get_option( 'yith_wapo_total_price_box' );
			if ( $total_price_box != 'hide_all' && $show_total_price_box ) : ?>

<div id="wapo-total-price-table">
	<table class="<?php echo $total_price_box; ?>">
		<?php if ( $blocks_product_price > 0 ) : ?>
		<tr style="<?php echo $total_price_box == 'only_final' ? 'display: none;' : ''; ?>">
			<th><?php echo __( 'Product price', 'yith-woocommerce-product-add-ons' ); ?>:</th>
			<td id="wapo-total-product-price"><?php echo wc_price( $blocks_product_price ); ?></td>
		</tr>
		<?php endif; ?>
		<tr style="<?php echo $total_price_box != 'all' ? 'display: none;' : ''; ?>">
			<th><?php echo __( 'Total options', 'yith-woocommerce-product-add-ons' ); ?>:</th>
			<td id="wapo-total-options-price"></td>
		</tr>
		<tr>
			<th><?php echo __( 'Order total', 'yith-woocommerce-product-add-ons' ); ?>:</th>
			<td id="wapo-total-order-price"></td>
		</tr>
	</table>
	<script type="text/javascript">

		jQuery('form.cart *').change( function() { yith_wapo_calculate_total_price(); });
		jQuery( function() { yith_wapo_calculate_total_price(); });

		function yith_wapo_calculate_total_price( ) {
			jQuery('#wapo-total-price-table').css('opacity', '0.5');
			setTimeout( function() {
				var addonID = 0;
				var totalPrice = 0;
				var firstFreeOptions = 0;
				jQuery('form.cart input, form.cart select').each( function() {
					if ( jQuery(this).data('addon-id') ) {
						if ( jQuery(this).is(':checked') || jQuery(this).find(':selected').is('option')
							|| ( jQuery(this).is('input:not([type=checkbox])') && jQuery(this).is('input:not([type=radio])') && jQuery(this).val() != '' ) ) {

							var option = false;
							if ( jQuery(this).is('select') ) { option = jQuery(this).find(':selected'); }
							else { option = jQuery(this); }

							if ( addonID != option.data('addon-id') ) {
								addonID = option.data('addon-id');
								firstFreeOptions = option.data('first-free-options');
							}
							
							if ( option.data('first-free-enabled') == 'yes' && firstFreeOptions > 0 ) {
								firstFreeOptions--;
							} else {
								if ( typeof option.data('price-sale') != 'undefined' && option.data('price-sale') > 0 ) {
									price = parseFloat( option.data('price-sale') );
									totalPrice += price;
								} else if ( typeof option.data('price') != 'undefined' && option.data('price') != '' ) {
									price = parseFloat( option.data('price') );
									totalPrice += price;
								}
							}
							
						}
					}
				});

				if ( totalPrice > 0 ) { jQuery('.hide_options tr').fadeIn(); }

				var totalCurrency = '<?php echo get_woocommerce_currency_symbol(); ?>';
				var totalCurrencyPos = '<?php echo get_option( 'woocommerce_currency_pos' ); ?>';
				var totalThousandSep = '<?php echo get_option( 'woocommerce_price_thousand_sep' ); ?>';
				var totalDecimalSep = '<?php echo get_option( 'woocommerce_price_decimal_sep' ); ?>';
				var totalPriceNumDec = '<?php echo get_option( 'woocommerce_price_num_decimals', 0 ); ?>';

				var totalOptionsPrice = parseFloat( totalPrice ).toFixed( totalPriceNumDec ).replace( '.', totalDecimalSep ).replace( /(\d)(?=(\d{3})+(?!\d))/g, '$1' + totalThousandSep );
				var totalOrderPrice = parseFloat( totalPrice + <?php echo $blocks_product_price; ?> ).toFixed( totalPriceNumDec ).replace( '.', totalDecimalSep ).replace( /(\d)(?=(\d{3})+(?!\d))/g, '$1' + totalThousandSep );

				if ( totalCurrencyPos == 'right' ) {
					totalOptionsPrice = totalOptionsPrice + totalCurrency;
					totalOrderPrice = totalOrderPrice + totalCurrency;
				} else {
					totalOptionsPrice = totalCurrency + totalOptionsPrice;
					totalOrderPrice = totalCurrency + totalOrderPrice;
				}

				jQuery('#wapo-total-options-price').html( totalOptionsPrice );
				jQuery('#wapo-total-order-price').html( totalOrderPrice );
				<?php if ( get_option( 'yith_wapo_replace_product_price' ) == 'yes' ) : ?>
				jQuery('.product p.price').html( '<span class="woocommerce-Price-amount amount"><bdi>' + totalOrderPrice + '</bdi></span>' );
				var productPrice = jQuery('.single-product .price .amount').html();
				productPrice = productPrice.replace(/[^0-9]/g,'');
				if ( productPrice === '000' ) {
					jQuery('.single-product .price .amount bdi').remove();
				}
				<?php endif; ?>
				jQuery('#wapo-total-price-table').css('opacity', '1');
			}, 500);
		}
	</script>
</div>
<?php endif; ?>

<style type="text/css">
	<?php for ( $i=1; $i<20; $i++ ) : ?>
		.yith-wapo-block .yith-wapo-addon .options.per-row-<?php echo $i; ?> .yith-wapo-option { width: auto; float: left; }
		.yith-wapo-block .yith-wapo-addon .options.per-row-<?php echo $i; ?> .yith-wapo-option:nth-child(<?php echo $i; ?>n+1) { clear: both; }
		.yith-wapo-block .yith-wapo-addon .options.grid.per-row-<?php echo $i; ?> .yith-wapo-option { width: <?php echo ( 100 / $i ) - 2; ?>%; margin-right: 2%; float: left; clear: none; }
		.yith-wapo-block .yith-wapo-addon .options.grid.per-row-<?php echo $i; ?> .yith-wapo-option:nth-child(<?php echo $i; ?>n+1) { clear: both; }
	<?php endfor; ?>

	<?php if ( $style_form_style == 'custom' ) : ?>
		/* COLOR */
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-color .yith-wapo-option label:hover span.color,
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-color .yith-wapo-option.selected label span.color { border: 2px solid <?php echo $style_accent_color; ?>; }
		/* LABEL */
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-label .yith-wapo-option label { border: 1px solid <?php echo $style_borders_color; ?>; }
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-label .yith-wapo-option label:hover,
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-label .yith-wapo-option.selected label { border: 1px solid <?php echo $style_accent_color; ?>; }
		/* PRODUCT */
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-product .yith-wapo-option label { border: 1px solid <?php echo $style_borders_color; ?>; }
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-product .yith-wapo-option label:hover,
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-product .yith-wapo-option.selected label { border: 1px solid <?php echo $style_accent_color; ?>; }
		/* CUSTOM RADIO & CHECKBOX */
		.yith-wapo-block .yith-wapo-addon span.checkboxbutton { width: 20px; height: 20px; position: relative; display: block; float: left; }
		.yith-wapo-block .yith-wapo-addon span.checkboxbutton input[type="checkbox"] { width: 20px; height: 20px; opacity: 0; position: absolute; top: 0; left: 0; cursor: pointer; }
		.yith-wapo-block .yith-wapo-addon span.checkboxbutton:before {
			content: '';
			background: #ffffff;
			width: 20px;
			height: 20px;
			line-height: 20px;
			border: 1px solid <?php echo $style_borders_color; ?>;
			border-radius: <?php echo get_option('yith_wapo_style_checkbox_style') == 'rounded' ? '50%' : '5px'; ?>;
			margin-right: 10px;
			text-align: center;
			font-size: 17px;
			vertical-align: middle;
			cursor: pointer;
			margin-bottom: 5px;
			transition: background-color ease 0.3s;
			display: inline-block;
		}
		.yith-wapo-block .yith-wapo-addon span.checkboxbutton.checked:before {
			background-image: url('<?php echo YITH_WAPO_URL; ?>/assets/img/check.svg') !important;
			background-size: 65%;
			background-position: center center;
			background-repeat: no-repeat !important;
			background-color: <?php echo $style_accent_color; ?>;
			border-color: <?php echo $style_accent_color; ?>;
			color: #ffffff;
		}
		.yith-wapo-block .yith-wapo-addon span.radiobutton { width: 20px; height: 20px; position: relative; display: block; float: left; }
		.yith-wapo-block .yith-wapo-addon span.radiobutton input[type="radio"] { width: 20px; height: 20px; opacity: 0; position: absolute; top: 0; left: 0; cursor: pointer; }
		.yith-wapo-block .yith-wapo-addon span.radiobutton:before { content: '';
			background: #ffffff;
			background-clip: content-box;
			width: 20px;
			height: 20px;
			line-height: 20px;
			border: 1px solid <?php echo $style_borders_color; ?>;
			border-radius: 100%;
			padding: 2px;
			margin-bottom: 0px;
			margin-right: 0px;
			font-size: 20px;
			text-align: center;
			display: inline-block;
			float: left;
			cursor: pointer;
		}
		.yith-wapo-block .yith-wapo-addon span.radiobutton.checked:before {
			background-color: <?php echo $style_accent_color; ?>;
			background-clip: content-box !important;
		}

		input[type=text], input[type=email], input[type=url], input[type=password], input[type=search], input[type=number],
		input[type=tel], input[type=range], input[type=date], input[type=month], input[type=week], input[type=time],
		input[type=datetime], input[type=datetime-local], input[type=color], textarea, input[type=file] { padding: 15px; }

		/* FONT SIZE */
		.yith-wapo-block .yith-wapo-addon .yith-wapo-option label { font-size: <?php echo $style_label_font_size ?>px; }
		.yith-wapo-block .yith-wapo-addon .yith-wapo-option .description { font-size: <?php echo $style_description_font_size ?>px; }

		/* ACCENT COLOR */
		.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-label .yith-wapo-option.selected label:after { background-color: <?php echo $style_accent_color; ?>; }
		
	<?php endif; ?>
	
	/* COLOR SWATCHES */
	.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-color .yith-wapo-option label { height: <?php echo $style_color_swatch_size; ?>px; }
	.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-color .yith-wapo-option label span.color {
		width: <?php echo $style_color_swatch_size; ?>px;
		height: <?php echo $style_color_swatch_size; ?>px;
		<?php if ( $style_color_swatch_style == 'square' ) : ?>border-radius: 0px;<?php endif; ?>
	}
	.yith-wapo-block .yith-wapo-addon.yith-wapo-addon-type-color .yith-wapo-option.selected label:after {
		margin: 0px -<?php echo $style_color_swatch_size / 2 + 5; ?>px <?php echo $style_color_swatch_size - 18 + 5; ?>px 0px;
	}
	
	/* LABEL / IMAGES */

	/* TOOLTIP */
	.yith-wapo-block .yith-wapo-addon .yith-wapo-option .tooltip span { background-color: <?php echo get_option( 'yith_wapo_tooltip_color' )['background']; ?>; color: <?php echo get_option( 'yith_wapo_tooltip_color' )['text']; ?>; }
	.yith-wapo-block .yith-wapo-addon .yith-wapo-option .tooltip span:after { border-top-color: <?php echo get_option( 'yith_wapo_tooltip_color' )['background']; ?>; }
	.yith-wapo-block .yith-wapo-addon .yith-wapo-option .tooltip.position-bottom span:after { border-bottom-color: <?php echo get_option( 'yith_wapo_tooltip_color' )['background']; ?>; }
</style>
<script type="text/javascript">
<?php if ( get_option( 'yith_wapo_hide_button_if_required' ) == 'yes' ) : ?>
	jQuery('form.cart *').change( function() { yith_wapo_check_required_fields( 'hide' ); });
	jQuery( function() { yith_wapo_check_required_fields( 'hide' ); });
<?php else : ?>
	// jQuery('form.cart').on( 'click', '.single_add_to_cart_button', function(){ return yith_wapo_check_required_fields( 'highlight' ); console.log('yith_wapo_check_required_fields'); });
<?php endif; ?>
</script>
<?php
		} // end print_blocks()

		public function get_product_blocks( $product_id ) {
			return yith_wapo_get_blocks();
		}

		/**
		 * Show Options Shortcode
		 *
		 * @return array
		 * @since  1.3.3
		 */
		function yith_wapo_show_options_shortcode( $atts ) {
			ob_start();
			if ( is_product() ) { do_action( 'yith_wapo_show_options_shortcode' ); }
			else { echo '<strong>' . __( 'This is not a product page!', 'yith-woocommerce-product-add-ons' ) . '</strong>'; }
			return ob_get_clean();
		}

	}
	
}

/**
 * Unique access to instance of YITH_WAPO_Front class
 *
 * @return YITH_WAPO_Front
 */
function YITH_WAPO_Front() {
	return YITH_WAPO_Front::get_instance();
}
