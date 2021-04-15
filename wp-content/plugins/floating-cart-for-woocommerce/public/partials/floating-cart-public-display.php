<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.skrotron.com/
 * @since      1.1.3
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/public/partials
 */

?>

<?php
$enable_cart_icon    = get_option( 'Woo_Floating_Cart_Settings_floating_enable_cart_icon', false );
$float_cart_icon_url = get_option( 'Woo_Floating_Cart_Settings_floating_cart_icon_url', true );
$float_cart_icon     = get_option( 'Woo_Floating_Cart_Settings_floating_cart_icon', true );
$enb_pg              = get_option( 'Woo_Floating_Cart_Settings_Tab_enable', true );

if ( empty( $float_cart_icon_url ) || 1 === intval( $float_cart_icon_url ) ) {
	if ( empty( $float_cart_icon ) || 1 === intval( $float_cart_icon ) ) {
		$float_cart_icon = FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_1.png';
	}
} else {
	$float_cart_icon = $float_cart_icon_url;
}

if ( is_product() ) {
	?>
	<div id="floating-error-notice"></div>
	<div id="floating-buy-now-dock">
		<div id="close_cart_popup_btn">CLOSE</div>
		<?php
		global $product;
		if ( $product->is_type( 'variable' ) ) {
			?>
			<div id="woo_floating_variation_cart">
				<div class="woo_variation_form">
					<div class="woo_variation_pro">
						<?php
							printf(
								'<span class="floating-buy-now-product-name">%s</span> <span class="floating-buy-now-product-price">%s</span>',
								esc_attr( $product->get_name() ),
								wp_kses_post( $product->get_price_html() )
							);
						?>
					</div>
					<?php woocommerce_variable_add_to_cart(); ?>
				</div>
			</div>
			<?php
		} else {
			?>
			<div id="<?php echo ( $product->is_type( 'grouped' ) ) ? 'woo_floating_grouped_cart' : 'woo_floating_simple_cart'; ?>">
				<div class="woocommerce"></div>
				<div class="woo_simple_product">
					<?php
						printf(
							'<span class="floating-buy-now-product-name">%s</span> <span class="floating-buy-now-product-price">%s</span>',
							esc_attr( $product->get_name() ),
							wp_kses_post( $product->get_price_html() )
						);
					?>
				</div>                        
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>
			<?php
		}
		?>
		<input type="hidden" value="<?php echo ( $product->is_type( 'variable' ) ) ? 'yes' : 'no'; ?>" id="check_pro">
		<input type="hidden" value="<?php echo ( $product->is_type( 'grouped' ) ) ? 'yes' : 'no'; ?>" id="check_grp_pro">
		<input type="hidden" value="<?php echo ( $product->is_type( 'external' ) ) ? 'yes' : 'no'; ?>" id="check_ext_pro">
		<input type="hidden" value="<?php echo esc_url( wc_get_cart_url() ); ?>" id="floating_cart_url">
		<input type="hidden" value="<?php echo esc_attr( get_option( 'Woo_Floating_Cart_Settings_floating_redirect_setting', true ) ); ?>" id="floating_redirect_setting">
		<input type="hidden" name="floatingcart_nonce" id="floatingcart_nonce" value="<?php echo esc_attr( wp_create_nonce( 'floating_cart_nonce' ) ); ?>">
	</div>

	<div id="floating-checkout-popup" style="display:none;"></div>

	<?php
	if ( isset( $enable_cart_icon ) && '' !== $enable_cart_icon && 'yes' === $enable_cart_icon ) {
		?>
	<div id="floating-icon" class="floating-cart-icon">
		<img class="float_cart_icon" src="<?php echo esc_url( $float_cart_icon ); ?>">
	</div>
	<?php } ?>

	<script>
	<?php
	if ( $product->is_type( 'variable' ) ) {
		$var       = $product->get_available_variations();
		$variables = array();
		foreach ( $var as $v ) {
			$variables[ $v['variation_id'] ] = array(
				'name'  => get_the_title( $v['variation_id'] ),
				'sku'   => $v['sku'],
				'price' => $v['price_html'],
			);
		}
		echo 'floating_variables = ' . wp_json_encode( $variables ) . ';';
		?>
		var variation_id = parseInt( jQuery('input.variation_id').val() );
		floating_set_variation( variation_id, floating_variables );

		function floating_set_variation( varid, variables ){
			if( varid == 0 || isNaN(varid) ){
				jQuery("#floating-buy-now-add-to-cart").text("Select Options");
					jQuery('.floating-buy-now-product-name').html( '<?php echo esc_attr( $product->get_name() ); ?>' );
					jQuery('.floating-buy-now-product-sku').html( '<?php echo ! empty( $product->get_sku() ) ? '(SKU: ' . esc_attr( $product->get_sku() ) . ')' : ''; ?>' );
					jQuery('.floating-buy-now-product-price').html( '<?php echo wp_kses_post( $product->get_price_html() ); ?>' );
			}else{
				jQuery("#floating-buy-now-add-to-cart").text("Add To Cart");
				var floating_variation = variables[varid];
				if( typeof floating_variation.name !== 'undefined' ){
					jQuery('.floating-buy-now-product-name').html( floating_variation.name );
				}
				if( typeof floating_variation.sku !== 'undefined' ){
					jQuery('.floating-buy-now-product-sku').html( "(SKU: "+floating_variation.sku+")" );
				}
				if( typeof floating_variation.price !== 'undefined' ){
					jQuery('.floating-buy-now-product-price').html( floating_variation.price );
				}
			}
		}
		jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
			var variation_id = parseInt( jQuery('input.variation_id').val() );
			floating_set_variation( variation_id, floating_variables );
		}); 

		<?php
	}
	?>
	</script>
	<?php
}

if ( isset( $enable_cart_icon ) && '' !== $enable_cart_icon && 'yes' === $enable_cart_icon ) {
	foreach ( $enb_pg as $cond ) {

		if ( 'is_front_page' === $cond || 'is_product_category' === $cond || 'is_shop' === $cond ) {

			if ( $cond() === true ) {
				$count = WC()->cart->get_cart_contents_count();
				?>
				<div id="floating-icon-pages" class="floating-cart-icon-pages">
					<span><?php echo esc_attr( $count ); ?></span>
					<img class="float_cart_icon" src="<?php echo esc_url( $float_cart_icon ); ?>">
				</div>
				<?php
			}
		}

		if ( is_page( $cond ) === true ) {
			$count = WC()->cart->get_cart_contents_count();
			?>

			<div id="floating-icon-pages" class="floating-cart-icon-pages">
				<span><?php echo esc_attr( $count ); ?></span>
				<img class="float_cart_icon" src="<?php echo esc_url( $float_cart_icon ); ?>">
			</div>
			<?php
		}
	}
}
?>
<input type="hidden" name="floatingcart_nonce" id="floatingcart_nonce" value="<?php echo esc_attr( wp_create_nonce( 'floating_cart_nonce' ) ); ?>">
<div id="floating-checkout-popup" style="display:none;"></div>
