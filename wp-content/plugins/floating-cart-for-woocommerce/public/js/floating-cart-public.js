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
	$( document ).on(
		'click',
		'#floating-buy-now-dock .single_add_to_cart_button',
		function (e) {
			e.preventDefault();

			var $thisbutton  = $( this ),
				$form        = $thisbutton.closest( 'form.cart' ),
				id           = $thisbutton.val(),
				product_qty  = $form.find( 'input[name=quantity]' ).val() || 1,
				product_id   = $form.find( 'input[name=product_id]' ).val() || id,
				variation_id = $form.find( 'input[name=variation_id]' ).val() || 0;

			var floatingcart_nonce        = $( '#floating-buy-now-dock #floatingcart_nonce' ).val();
			var chekgrpprod               = $( '#floating-buy-now-dock #check_grp_pro' ).val();
			var chekextprod               = $( '#floating-buy-now-dock #check_ext_pro' ).val();
			var floating_redirect_setting = $( '#floating_redirect_setting' ).val();
			var floating_cart_url         = $( '#floating_cart_url' ).val();

			if ( chekextprod == "yes" ) {
				var ext_url = $form.attr( 'action' );
				window.open( ext_url, '_blank' );
				return;
			}

			var grouped = "no";

			if ( chekgrpprod == "yes" ) {

				$( "#floating-error-notice" ).fadeOut();
				$( "#floating-error-notice" ).html( '' );
				product_id = $form.find( 'input[name=add-to-cart]' ).val() || id;
				grouped    = "yes";

				var data = {
					action: 'floating_cart_ajax_grouped_add_to_cart',
					product_id: product_id,
					floatingcart_nonce: floatingcart_nonce,
				};

				$.ajax(
					{
						type: 'post',
						url: ajax_url,
						data: data,
						success: function (response) {
							process_grouped_pro( response,product_id,floatingcart_nonce );

						},
					}
				);

				function process_grouped_pro(response,grp_pr_id,floatingcart_nonce){
					var arr          = JSON.parse( response );
					var arrayLength  = arr.length;
					var url_1        = grp_pr_id;
					var url_2        = [];
					var quantity_grp = 0;
					for (var i = 0; i < arrayLength; i++) {

						var qty = $( '#product-' + arr[i] + ' .qty' ).val();
						if ( qty > 0 ) {
							var quantity_grp = qty;
						}
						url_2[i] = arr[i] + "-" + qty;

					}
					if ( quantity_grp == 0 ) {
						$( "#floating-error-notice" ).html( '<ul class="woocommerce-error" role="alert"><li>Please choose the quantity of items you wish to add to your cart…</li></ul>' );
						$( "#floating-error-notice" ).fadeIn();

						return;
					}
					var skx  = url_2.toString();
					var data = {
						action: 'floating_cart_ajax_add_to_cart',
						product_id: product_id,
						url1: url_1,
						url2: skx,
						variation_id: variation_id,
						grouped: grouped,
						floatingcart_nonce: floatingcart_nonce,
					};

					$.ajax(
						{
							type: 'post',
							url: ajax_url,
							data: data,
							beforeSend: function (response) {
								$thisbutton.removeClass( 'added' ).addClass( 'loading' );
							},
							complete: function (response) {
								$thisbutton.addClass( 'added' ).removeClass( 'loading' );
							},
							success: function (response) {

								if (response.error & response.product_url) {
									window.location = response.product_url;
									return;
								} else {
									$.get(
										response ,
										function(){
											if ( "yes" === floating_redirect_setting ) {
												window.location.href = floating_cart_url;
											} else {
												var data = {
													action: 'floating_cart_ajax_get_cart_contents',
													floatingcart_nonce: floatingcart_nonce,
												};

												$.ajax(
													{
														type: 'post',
														url: ajax_url,
														data: data,
														beforeSend: function (response) {
															$( "#floating-checkout-popup .floating_container" ).addClass( 'floatingloader' );
															$( "#floating-checkout-popup .floating_container" ).fadeIn();
														},
														complete: function (response) {
															$( "#floating-checkout-popup .floating_container" ).fadeOut();
															$( "#floating-checkout-popup .floating_container" ).removeClass( 'floatingloader' );
														},
														success: function (response) {
															$( "#floating-checkout-popup" ).html( response );
															$( "#floating-buy-now-dock" ).fadeOut( 200 );
															$( "#floating-checkout-popup" ).fadeIn( 200 );
														},
													}
												);
											}
										}
									);

									$( document.body ).trigger( 'added_to_cart', [response.fragments, response.cart_hash, $thisbutton] );
								}
							},
						}
					);

				}

			} else {

				if ( $thisbutton.hasClass( "disabled" ) ) {
					return;
				}

				var data = {
					action: 'floating_cart_ajax_add_to_cart',
					product_id: product_id,
					product_sku: '',
					quantity: product_qty,
					variation_id: variation_id,
					grouped: grouped,
					floatingcart_nonce: floatingcart_nonce,
				};

				$( document.body ).trigger( 'adding_to_cart', [$thisbutton, data] );

				var chekprod = $( '#floating-buy-now-dock #check_pro' ).val();
				if ( chekprod == "no" ) {
					variation_id = 1;
				}

				if ( parseInt( variation_id ) != 0) {

					$.ajax(
						{
							type: 'post',
							url: ajax_url,
							data: data,
							beforeSend: function (response) {
								$thisbutton.removeClass( 'added' ).addClass( 'loading' );
							},
							complete: function (response) {
								$thisbutton.addClass( 'added' ).removeClass( 'loading' );
							},
							success: function (response) {

								if (response.error & response.product_url) {
									window.location = response.product_url;
									return;
								} else {
									$( document.body ).trigger( 'added_to_cart', [response.fragments, response.cart_hash, $thisbutton] );

									if ( "yes" === floating_redirect_setting ) {
										window.location.href = floating_cart_url;
									} else {
										var data = {
											action: 'floating_cart_ajax_get_cart_contents',
											floatingcart_nonce: floatingcart_nonce,
										};

										$.ajax(
											{
												type: 'post',
												url: ajax_url,
												data: data,
												beforeSend: function (response) {
													$( "#floating-checkout-popup .floating_container" ).addClass( 'floatingloader' );
													$( "#floating-checkout-popup .floating_container" ).fadeIn();
												},
												complete: function (response) {
													$( "#floating-checkout-popup .floating_container" ).fadeOut();
													$( "#floating-checkout-popup .floating_container" ).removeClass( 'floatingloader' );
												},
												success: function (response) {
													$( "#floating-checkout-popup" ).html( response );
													$( "#floating-buy-now-dock" ).fadeOut( 200 );
													$( "#floating-checkout-popup" ).fadeIn( 200 );
												},
											}
										);
									}
								}
							},
						}
					);
				}

			}
			return false;
		}
	);

	// Ajax delete product in the cart.
	$( document ).on(
		'click',
		'#floating-checkout-popup a.remove',
		function (e)
		{
				e.preventDefault();

				product_id             = $( this ).attr( "data-product_id" ),
				cart_item_key          = $( this ).attr( "data-cart_item_key" ),
				product_container      = $( this ).parents( '.mini_cart_item' );
				var floatingcart_nonce = $( '#floatingcart_nonce' ).val();

				$.ajax(
					{
						type: 'POST',
						dataType: 'json',
						url: ajax_url,
						data: {
							action: "floating_ajax_product_remove",
							product_id: product_id,
							cart_item_key: cart_item_key,
							floatingcart_nonce: floatingcart_nonce,
						},
						success: function(response) {
							if ( response > 0 ) {
								$( ".floating-cart-icon-pages span" ).html( response );
							} else {
								$( ".floating-cart-icon-pages span" ).html( 0 );
							}
							var data = {
								action: 'floating_cart_ajax_get_cart_contents',
								floatingcart_nonce: floatingcart_nonce,
							};

							$.ajax(
								{
									type: 'post',
									url: ajax_url,
									data: data,
									beforeSend: function (response) {
										$( "#floating-checkout-popup .floating_container" ).addClass( 'floatingloader' );
										$( "#floating-checkout-popup .floating_container" ).fadeIn();
									},
									complete: function (response) {
										$( "#floating-checkout-popup .floating_container" ).fadeOut();
										$( "#floating-checkout-popup .floating_container" ).removeClass( 'floatingloader' );
									},
									success: function (response) {
										$( "#floating-checkout-popup" ).html( response );
										$( "#floating-checkout-popup" ).fadeIn( 200 );
									},
								}
							);
						}
					}
				);
		}
	);

	$( document ).ready(
		function() {
			setInterval(
				function(){
					$( "#floating-icon, #floating-icon-pages" ).effect( "bounce", {times:5}, 1000 );
				},
				3000
			);
		}
	);

	jQuery( document ).on(
		'click',
		'#floating-icon-pages a.added_to_cart, #floating-buy-now-dock a.added_to_cart',
		function(e){
			e.preventDefault();
			var floatingcart_nonce = $( '#floatingcart_nonce' ).val();
			var data               = {
				action: 'floating_cart_ajax_get_cart_contents',
				floatingcart_nonce: floatingcart_nonce,
			};

			$.ajax(
				{
					type: 'post',
					url: ajax_url,
					data: data,
					beforeSend: function (response) {
						$( "#floating-checkout-popup .floating_container" ).addClass( 'floatingloader' );
						$( "#floating-checkout-popup .floating_container" ).fadeIn();
					},
					complete: function (response) {
						$( "#floating-checkout-popup .floating_container" ).fadeOut();
						$( "#floating-checkout-popup .floating_container" ).removeClass( 'floatingloader' );
					},
					success: function (response) {
						$( "#floating-checkout-popup" ).html( response );
						$( "#floating-buy-now-dock" ).fadeOut( 200 );
						$( "#floating-checkout-popup" ).fadeIn( 200 );
					},
				}
			);

		}
	);

	jQuery( document ).on(
		'change',
		'form.cart input, form.cart .qty',
		function(){
			var curval  = jQuery( this ).val();
			var curname = jQuery( this ).attr( 'name' );
			jQuery( '[name="' + curname + '"]' ).val( curval );
		}
	)

	jQuery( document ).on(
		"click",
		"#floating-checkout-popup .close_chk_pp",
		function(){
			jQuery( "#floating-checkout-popup" ).slideUp( 200 );
			jQuery( "#floating-buy-now-dock" ).fadeIn( 200 );
			jQuery( ".floating-cart-icon-pages" ).attr( "id","floating-icon-pages" );
			jQuery( ".floating-cart-icon-pages" ).fadeIn( 200 );
		}
	)

	jQuery( document ).on(
		"click",
		"#floating-icon .float_cart_icon",
		function(){
			jQuery( "#floating-buy-now-dock" ).animate( {"right": "0"}, 500, "linear" );
			jQuery( this ).parent().removeAttr( "id" ).fadeOut( 200 );
		}
	)

	jQuery( document ).on(
		"click",
		"#close_cart_popup_btn",
		function(){
			jQuery( "#floating-buy-now-dock" ).animate( {"right": "-200vw"}, 500, "linear" );
			jQuery( ".floating-cart-icon" ).attr( "id","floating-icon" );
			jQuery( ".floating-cart-icon" ).fadeIn( 200 );
		}
	)

	jQuery( document ).on(
		"click",
		"#floating-icon-pages .float_cart_icon, #floating-buy-now-dock .float_cart_icon",
		function(){

			var floatingcart_nonce = $( '#floatingcart_nonce' ).val();
			var data               = {
				action: 'floating_cart_ajax_get_cart_contents',
				floatingcart_nonce: floatingcart_nonce,
			};

			$.ajax(
				{
					type: 'post',
					url: ajax_url,
					data: data,
					beforeSend: function (response) {
						$( "#floating-checkout-popup .floating_container" ).addClass( 'floatingloader' );
						$( "#floating-checkout-popup .floating_container" ).fadeIn();

					},
					complete: function (response) {
						$( "#floating-checkout-popup .floating_container" ).fadeOut();
						$( "#floating-checkout-popup .floating_container" ).removeClass( 'floatingloader' );
					},
					success: function (response) {
						$( ".floating-cart-icon-pages" ).removeAttr( "id" ).fadeOut( 200 );
						$( "#floating-checkout-popup" ).html( response );

						$( "#floating-checkout-popup" ).fadeIn( 200 );
					},
				}
			);
		}
	)

})( jQuery );
