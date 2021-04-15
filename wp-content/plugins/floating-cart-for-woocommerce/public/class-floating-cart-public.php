<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.skrotron.com/
 * @since      1.1.3
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/public
 */
class Floating_Cart_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.3
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.3
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.3
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.1.3
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Floating_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Floating_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/floating-cart-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.1.3
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Floating_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Floating_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$enable_on_cart = get_option( 'Woo_Floating_Cart_Settings_floating_enable_cart', false );
		if ( isset( $enable_on_cart ) && '' !== $enable_on_cart && 'yes' === $enable_on_cart ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/floating-cart-smart.js', array( 'jquery' ), $this->version, false );
		} else {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/floating-cart-public.js', array( 'jquery' ), $this->version, false );
		}
		wp_enqueue_script( 'jquery-ui.min', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js', array(), '1.11.3', false );
		wp_enqueue_script( 'myscript', plugin_dir_url( __FILE__ ) . 'js/floating-cart-count.js', array(), '1.1.3', true );

	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_ajaxurl() {
		echo '<script type="text/javascript">
				var ajax_url = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '";
			  </script>';
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_ajax_add_to_cart_js() {
		if ( function_exists( 'is_product' ) && is_product() ) {
			wp_enqueue_script( 'woocommerce-ajax-add-to-cart', plugin_dir_url( __FILE__ ) . 'js/ajax-add-to-cart.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function load_in_footer_function() {
		require_once FLOATING_CART_PLUGIN_DIR . 'public/partials/floating-cart-public-display.php';
		echo '<style>';
		echo esc_attr( $this->floating_cart_get_style() );
		echo '</style>';
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_force_non_logged_user_wc_session() {
		if ( is_user_logged_in() || is_admin() ) {
			return;
		}
		if ( isset( WC()->session ) ) {
			if ( ! WC()->session->has_session() ) {
				WC()->session->set_customer_session_cookie( true );
			}
		}
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_ajax_add_to_cart() {
		global $product;
		if ( isset( $_POST['floatingcart_nonce'] ) && '' !== $_POST['floatingcart_nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['floatingcart_nonce'] ) ), 'floating_cart_nonce' ) ) {
			if ( isset( $_POST['grouped'] ) && 'yes' === $_POST['grouped'] ) {
				$url1     = ! empty( $_POST['url1'] ) ? '?add-to-cart=' . sanitize_text_field( wp_unslash( $_POST['url1'] ) ) . '&' : '';
				$url2_str = ! empty( $_POST['url2'] ) ? sanitize_text_field( wp_unslash( $_POST['url2'] ) ) : '';
				$url2_ids = explode( ',', $url2_str );
				$url_2    = array();
				foreach ( $url2_ids as $ids ) {
					list( $prd_id, $qty ) = explode( '-', $ids );
					$url_2[]              = 'quantity[' . $prd_id . ']=' . $qty;
				}
				$url3 = implode( '&', $url_2 );
				$url  = $url1 . $url3;
				echo esc_url( wp_send_json( $url ) );
			} else {
				$product_id        = ! empty( $_POST['product_id'] ) ? apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) ) : '';
				$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
				$variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : '';
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				$product_status    = get_post_status( $product_id );

				if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {

					do_action( 'woocommerce_ajax_added_to_cart', $product_id );

					if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
						wc_add_to_cart_message( array( $product_id => $quantity ), true );
					}

					WC_AJAX::get_refreshed_fragments();
				} else {

					$data = array(
						'error'       => true,
						'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
					);

					echo wp_kses_post( wp_send_json( $data ) );
				}
			}
		}
		wp_die();
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_ajax_grouped_add_to_cart() {
		if ( isset( $_POST['floatingcart_nonce'] ) && '' !== $_POST['floatingcart_nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['floatingcart_nonce'] ) ), 'floating_cart_nonce' ) ) {
			if ( isset( $_POST['product_id'] ) ) {
				$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
				$product    = wc_get_product( $product_id );
				$output     = array();
				foreach ( $product->get_children() as $child ) {
					$output[] = $child;
				}
				echo wp_json_encode( $output );
			}
		}
		wp_die();
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_ajax_get_cart_contents() {
		if ( isset( $_POST['floatingcart_nonce'] ) && '' !== $_POST['floatingcart_nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['floatingcart_nonce'] ) ), 'floating_cart_nonce' ) ) {
			$data            = '';
			$count           = WC()->cart->get_cart_contents_count();
			$checkout_text   = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_text', false );
			$close_text      = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_close_text', false );
			$cart_empty_text = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_empty_text', false );
			if ( empty( $checkout_text ) ) {
				$checkout_text = 'Checkout';
			}
			if ( empty( $cart_empty_text ) ) {
				$cart_empty_text = 'Cart is empty!';
			}
			if ( empty( $close_text ) ) {
				$close_text = 'CLOSE';
			}

			if ( $count > 0 ) {

				$data = '<div class="floating_container" style="display:none;"></div>
						<table>
							<thead>
								<tr><td colspan="4" style="text-align:right;padding: 5px 0 25px 0;"><a class="close_chk_pp">' . esc_attr( $close_text ) . '</a></td></tr>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr><td colspan="4" style="padding: 15px;"> </td></tr>';

										global $woocommerce;
										$items = $woocommerce->cart->get_cart();

				foreach ( $items as $item => $values ) {
					$_product = wc_get_product( $values['data']->get_id() );
					$data    .= '<tr>';

					$data .= '<td>' .
						apply_filters(
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">×</a>',
								esc_url( WC()->cart->get_remove_url( $values['key'] ) ),
								esc_html__( 'Remove this item', 'deepsoul' ),
								esc_attr( $values['product_id'] ),
								esc_attr( $values['data']->get_sku() ),
								esc_attr( $values['key'] )
							),
							$values['key']
						) . '</td>';

					$getproductdetail = wc_get_product( $values['product_id'] );

					if ( $values['variation_id'] > 0 ) {
						$variation = new WC_Product_Variation( $values['variation_id'] );
						$picture   = $variation->get_image();
					} else {
						$picture = $getproductdetail->get_image( 'thumbnail' );
					}

					$data .= '<td width="80">' . $picture . '</td>';

					if ( $values['variation_id'] > 0 ) {
						$title = get_the_title( $values['variation_id'] );
					} else {
						$title = $_product->get_title();
					}
					$data .= '<td><b>' . $title . '</b></td>';
					if ( $values['variation_id'] > 0 ) {
						if ( ! empty( get_post_meta( $values['variation_id'], '_sale_price', true ) ) ) {
							$price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values['_sale_price'], $item );
						} else {
							$price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values['_regular_price'], $item );
						}
					} else {
						$price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values, $item );
					}

					$data .= '<td>' . $values['quantity'] . ' x ' . $price . '</td>';
					$data .= '</tr>';
				}

										$data .= '<tr><td></td><td></td>';
										$data .= '<td style="text-align:right;"><b>Subtotal : </b></td>';
										$data .= '<td><b>' . WC()->cart->get_cart_subtotal() . '</b></td>';
										$data .= '</tr>';

										$data .= '<table><tr><td></td><td></td><td></td>';
										$data .= '<td style="text-align:right;padding:20px 0 10px 0;"><a class="chkout btn wc-forward" href="' . wc_get_checkout_url() . '">' . esc_attr( $checkout_text ) . '</a></td>';
										$data .= '</tr>';
										$data .= '</table>
							</tbody>
						</table>';
			} else {
				$data = '<table>
						<thead>
							<tr><td style="text-align:right;padding:5px 0 25px 0;"><a class="close_chk_pp">' . esc_attr( $close_text ) . '</a></td></tr>
							<tr><td style="padding: 15px;"> </td></tr>
							<tr>
								<th style="text-align:center;"><p>' . esc_attr( $cart_empty_text ) . '</p></th>
							</tr>
							<tr><td style="padding: 15px;"> </td></tr>
						</thead></table>';
			}

			echo wp_kses_post( wp_send_json( $data ) );
		}
		wp_die();
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_ajax_product_remove() {
		if ( isset( $_POST['floatingcart_nonce'] ) && '' !== $_POST['floatingcart_nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['floatingcart_nonce'] ) ), 'floating_cart_nonce' ) ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ( isset( $_POST['cart_item_key'] ) && isset( $_POST['product_id'] ) ) {
					if ( intval( $_POST['product_id'] ) === $cart_item['product_id'] && $_POST['cart_item_key'] === $cart_item_key ) {
						WC()->cart->remove_cart_item( $cart_item_key );
						$count = WC()->cart->get_cart_contents_count();
						echo esc_attr( $count );
					}
				}
			}
		}

		die();
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_cart_get_style() {
		$floating_cart_bg              = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color', false );
		$floating_cart_bg_tr           = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color_transparancy', false );
		$floating_chk_bg               = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color', false );
		$floating_chk_bg_tr            = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color_transparancy', false );
		$floating_btn_color            = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_text_color', false );
		$floating_btn_bg_color         = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_background_color', false );
		$floating_btn_hover_text_color = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_text_color', false );
		$floating_btn_hover_bg_color   = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_background_color', false );
		$floating_loader_primary       = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_first', false );
		$floating_loader_secondary     = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_second', false );
		$floating_icon_horizontal_pos  = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_icon_horizontal_position', false );
		$floating_icon_vertical_pos    = get_option( 'Woo_Floating_Cart_Settings_Tab_floating_cart_icon_vertical_position', false );
		if ( empty( $floating_btn_color ) ) {
			$floating_btn_color = '#FFFFFF';
		}
		if ( empty( $floating_btn_bg_color ) ) {
			$floating_btn_bg_color = '#96588a';
		}
		if ( empty( $floating_btn_hover_text_color ) ) {
			$floating_btn_hover_text_color = '#FFFFFF';
		}
		if ( empty( $floating_btn_hover_bg_color ) ) {
			$floating_btn_hover_bg_color = '#96588a';
		}
		if ( empty( $floating_cart_bg ) ) {
			$floating_cart_bg_r = 0;
			$floating_cart_bg_g = 0;
			$floating_cart_bg_b = 0;
		} else {
			list($floating_cart_bg_r, $floating_cart_bg_g, $floating_cart_bg_b) = sscanf( $floating_cart_bg, '#%02x%02x%02x' );
		}

		if ( empty( $floating_cart_bg_tr ) ) {
			$floating_cart_bg_a = 0.8;
		} else {
			$floating_cart_bg_a = $floating_cart_bg_tr;
		}

		if ( empty( $floating_chk_bg ) ) {
			$floating_chk_bg_r = 0;
			$floating_chk_bg_g = 0;
			$floating_chk_bg_b = 0;
		} else {
			list($floating_chk_bg_r, $floating_chk_bg_g, $floating_chk_bg_b) = sscanf( $floating_chk_bg, '#%02x%02x%02x' );
		}

		if ( empty( $floating_chk_bg_tr ) ) {
			$floating_chk_bg_a = 0.8;
		} else {
			$floating_chk_bg_a = $floating_chk_bg_tr;
		}
		if ( empty( $floating_loader_primary ) ) {
			$floating_loader_primary = '#96588a';
		}
		if ( empty( $floating_loader_secondary ) ) {
			$floating_loader_secondary_r = 150;
			$floating_loader_secondary_g = 88;
			$floating_loader_secondary_b = 138;
		} else {
			list($floating_loader_secondary_r, $floating_loader_secondary_g, $floating_loader_secondary_b) = sscanf( $floating_loader_secondary, '#%02x%02x%02x' );
		}
		if ( empty( $floating_icon_horizontal_pos ) ) {
			$floating_icon_horizontal_pos = 'right';
		}
		if ( empty( $floating_icon_vertical_pos ) ) {
			$floating_icon_vertical_pos = 80;
		}

		$style = '
		#floating-buy-now-dock{
			background:rgba(' . esc_attr( $floating_cart_bg_r ) . ',' . esc_attr( $floating_cart_bg_g ) . ',' . esc_attr( $floating_cart_bg_b ) . ',' . esc_attr( $floating_cart_bg_a ) . ');
		}
		#floating-checkout-popup{
			background: rgba(' . esc_attr( $floating_chk_bg_r ) . ',' . esc_attr( $floating_chk_bg_g ) . ',' . esc_attr( $floating_chk_bg_b ) . ',' . esc_attr( $floating_chk_bg_a ) . ');
		}
		#woo_floating_simple_cart .button, #woo_floating_grouped_cart .button, #floating-checkout-popup .chkout.btn, .woo_variation_form .button {
			background-color: ' . esc_attr( $floating_btn_bg_color ) . ' !important;
			color: ' . esc_attr( $floating_btn_color ) . ' !important;
		}
		#woo_floating_simple_cart .button:hover, #woo_floating_grouped_cart .button:hover, #floating-checkout-popup .chkout.btn:hover, .woo_variation_form .button:hover {
			background-color: ' . esc_attr( $floating_btn_hover_bg_color ) . ' !important;
			color: ' . esc_attr( $floating_btn_hover_text_color ) . ' !important;
		}
		#floating-buy-now-dock-pages{
			background:rgba(' . esc_attr( $floating_cart_bg_r ) . ',' . esc_attr( $floating_cart_bg_g ) . ',' . esc_attr( $floating_cart_bg_b ) . ',' . esc_attr( $floating_cart_bg_a ) . ');
		}
		#floating-checkout-popup{
			background: rgba(' . esc_attr( $floating_chk_bg_r ) . ',' . esc_attr( $floating_chk_bg_g ) . ',' . esc_attr( $floating_chk_bg_b ) . ',' . esc_attr( $floating_chk_bg_a ) . ');
		}
		#floating-buy-now-dock-pages .floating_show_cart, #floating-checkout-popup .chkout.btn{
			background-color: ' . esc_attr( $floating_btn_bg_color ) . ' !important;
			color: ' . esc_attr( $floating_btn_color ) . ' !important;
		}
		#floating-buy-now-dock-pages .floating_show_cart:hover, #floating-checkout-popup .chkout.btn:hover{
			background-color: ' . esc_attr( $floating_btn_hover_bg_color ) . ' !important;
			color: ' . esc_attr( $floating_btn_hover_text_color ) . ' !important;
		}
		@-webkit-keyframes load5 {
			0%,
			100% {
			  box-shadow: 0em -2.6em 0em 0em ' . esc_attr( $floating_loader_primary ) . ', 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7);
			}
			12.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 1.8em -1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5);
			}
			25% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 2.5em 0em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			37.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 1.75em 1.75em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			50% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 0em 2.5em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			62.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -1.8em 1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			75% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -2.6em 0em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			87.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -1.8em -1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ';
			}
		  }
		  @keyframes load5 {
			0%,
			100% {
			  box-shadow: 0em -2.6em 0em 0em ' . esc_attr( $floating_loader_primary ) . ', 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7);
			}
			12.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 1.8em -1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5);
			}
			25% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 2.5em 0em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			37.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 1.75em 1.75em 0 0em ' . esc_attr( $floating_loader_primary ) . ', 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			50% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), 0em 2.5em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			62.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -1.8em 1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			75% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -2.6em 0em 0 0em ' . esc_attr( $floating_loader_primary ) . ', -1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2);
			}
			87.5% {
			  box-shadow: 0em -2.6em 0em 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.8em -1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 2.5em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 1.75em 1.75em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), 0em 2.5em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.2), -1.8em 1.8em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.5), -2.6em 0em 0 0em rgba(' . esc_attr( $floating_loader_secondary_r ) . ', ' . esc_attr( $floating_loader_secondary_g ) . ', ' . esc_attr( $floating_loader_secondary_b ) . ', 0.7), -1.8em -1.8em 0 0em ' . esc_attr( $floating_loader_primary ) . ';
			}
		  }
		  #floating-icon, #floating-icon-pages{
			top: ' . $floating_icon_vertical_pos . 'vh;
			' . $floating_icon_horizontal_pos . ': 5px;
		  }';

		return $style;
	}

	/**
	 * This function belongs to floating cart.
	 */
	public function floating_get_cart_count() {
		echo esc_attr( WC()->cart->get_cart_contents_count() );
		wp_die();
	}
}
