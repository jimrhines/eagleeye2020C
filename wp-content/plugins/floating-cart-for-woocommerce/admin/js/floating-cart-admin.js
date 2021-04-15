/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * @package Floating Cart.
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */

(function( $ ) {
	'use strict';

	jQuery( window ).load(
		function(){
			if ( jQuery( '#Woo_Floating_Cart_Settings_floating_cart_icon' ).val() != '' ) {
				jQuery( "#floating_cart_icon" ).attr( "src", jQuery( '#Woo_Floating_Cart_Settings_floating_cart_icon' ).val() );
			}

			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#000000' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color' ).attr( 'value','#000000' ).attr( 'data-default-color','#000000' );
			}
			jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color_transparancy' ).attr( 'min','0.1' ).attr( 'max','1.0' ).attr( 'step','0.1' );
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#000000' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color' ).attr( 'value','#000000' ).attr( 'data-default-color','#000000' );
			}
			jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color_transparancy' ).attr( 'min','0.1' ).attr( 'max','1.0' ).attr( 'step','0.1' );
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_text_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_text_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#FFFFFF' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_text_color' ).attr( 'value','#FFFFFF' ).attr( 'data-default-color','#FFFFFF' );
			}
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_background_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_background_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#96588a' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_background_color' ).attr( 'value','#96588a' ).attr( 'data-default-color','#96588a' );
			}
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_text_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_text_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#FFFFFF' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_text_color' ).attr( 'value','#FFFFFF' ).attr( 'data-default-color','#FFFFFF' );
			}
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_background_color' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_background_color' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#96588a' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_background_color' ).attr( 'value','#96588a' ).attr( 'data-default-color','#96588a' );
			}
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_first' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_first' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#96588a' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_first' ).attr( 'value','#96588a' ).attr( 'data-default-color','#96588a' );
			}
			if ( jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_second' ).val() == '' ) {
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_second' ).parent().parent().parent().find( 'button.wp-color-result' ).css( 'background-color','#96588a' );
				jQuery( '#Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_second' ).attr( 'value','#96588a' ).attr( 'data-default-color','#96588a' );
			}
		}
	);

	jQuery( document ).ready(
		function(){
			jQuery( "#Woo_Floating_Cart_Settings_floating_cart_icon" ).on(
				"change",
				function(){
					var img = jQuery( this ).val();
					jQuery( "#floating_cart_icon" ).attr( "src",img );
				}
			);
		}
	);

})( jQuery );
