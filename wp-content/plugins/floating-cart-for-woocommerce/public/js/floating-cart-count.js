/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * @package           Floating_Cart
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
	jQuery( window ).on(
		"load",
		function(){
			jQuery( "body" ).append( '<script>jQuery("body").on("added_to_cart", function( e, fragments, cart_hash, this_button ) { var data = {action: "floating_get_cart_count"};jQuery.ajax({type: "post",url: ajax_url,data: data,success: function (response) {jQuery(".floating-cart-icon-pages span" ).html( response );},}); });</script>' )
		}
	);
})( jQuery );
