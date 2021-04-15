<?php
/**
 * The file that defines the core plugin class
 * Description of what this module (or file) is doing.
 * A class definition that includes tabs section
 * admin-facing side of the site and the admin area.
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/includes
 */

/**
 * The core plugin class.
 * Description of what this module (or file) is doing.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.3
 * @package    Floating_Cart
 * @subpackage Floating_Cart/includes
 */
class Woo_Floating_Cart_Settings_Tab {
	/**
	 * Bootstraps the class and hooks required actions & filters.
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_settings_tab_floating_cart', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_settings_tab_floating_cart', __CLASS__ . '::update_settings' );
	}


	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['settings_tab_floating_cart'] = __( 'Floating Cart Setting', 'woocommerce-settings-tab-floating_cart' );
		return $settings_tabs;
	}
	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}
	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}
	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function get_floating_cart_pages() {
		$pages_arr = array(
			'is_front_page'       => 'Home Page',
			'is_shop'             => 'Shop Page',
			'is_product_category' => 'Product Category Page',
		);

		$args  = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => -1,
		);
		$pages = get_posts( $args );
		foreach ( $pages as $page ) {
			$pages_arr[ $page->ID ] = $page->post_title;

		}
		return $pages_arr;
	}

	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function get_floating_cart_icons() {
		$cart_icons = array(
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_1.png' => 'Cart Icon 1',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_2.png' => 'Cart Icon 2',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_3.png' => 'Cart Icon 3',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_4.png' => 'Cart Icon 4',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_5.png' => 'Cart Icon 5',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_6.png' => 'Cart Icon 6',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_7.png' => 'Cart Icon 7',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_8.png' => 'Cart Icon 8',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_9.png' => 'Cart Icon 9',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_10.png' => 'Cart Icon 10',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_11.png' => 'Cart Icon 11',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_12.png' => 'Cart Icon 12',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_13.png' => 'Cart Icon 13',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_14.png' => 'Cart Icon 14',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_15.png' => 'Cart Icon 15',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_16.png' => 'Cart Icon 16',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_17.png' => 'Cart Icon 17',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_18.png' => 'Cart Icon 18',
			FLOATING_CART_PLUGIN_URL . '/public/images/cart_icons/shape_19.png' => 'Cart Icon 19',
		);
		return $cart_icons;
	}

	/**Woocommerce settings panel */
	public static function get_settings() {
		$settings = array(
			'section_title'                               => array(
				'name' => __( 'Floating Cart Settings', 'woocommerce-settings-tab-floating_cart' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'Woo_Floating_Cart_Settings_Tab_section_title',
			),
			'floating_cart_enable_shop'                   => array(
				'name'    => __( 'Visible cart on', 'woocommerce-settings-tab-floating_cart' ),
				'type'    => 'multiselect',
				'options' => self::get_floating_cart_pages(),
				'desc'    => __( 'Select pages from list. On selected pages the cart will visible if its not empty.' ),
				'class'   => 'wc-enhanced-select wc_floating_cart_pages',
				'id'      => 'Woo_Floating_Cart_Settings_Tab_enable',
			),

			'floating_enable_cart'                        => array(
				'name' => __( 'Add AJAX effect', 'woocommerce-settings-tab-floating_cart' ),
				'type' => 'checkbox',
				'desc' => 'Enable AJAX effect on default cart',
				'id'   => 'Woo_Floating_Cart_Settings_floating_enable_cart',
			),

			'floating_enable_cart_icon'                   => array(
				'name' => __( 'Enable Cart Icon', 'woocommerce-settings-tab-floating_cart' ),
				'type' => 'checkbox',
				'desc' => 'Enable floating cart icon',
				'id'   => 'Woo_Floating_Cart_Settings_floating_enable_cart_icon',
			),

			'floating_redirect_setting'                   => array(
				'name' => __( 'Redirect To Cart', 'woocommerce-settings-tab-floating_cart' ),
				'type' => 'checkbox',
				'desc' => 'Redirect on cart page after adding product in cart',
				'id'   => 'Woo_Floating_Cart_Settings_floating_redirect_setting',
			),

			'floating_cart_icon'                          => array(
				'name'    => __( 'Select Cart Icon', 'woocommerce-settings-tab-floating_cart' ),
				'type'    => 'select',
				'options' => self::get_floating_cart_icons(),
				'desc'    => __( '<img id="floating_cart_icon" src="">' ),
				'id'      => 'Woo_Floating_Cart_Settings_floating_cart_icon',
			),

			'floating_cart_icon_url'                      => array(
				'name'        => __( 'Custom Cart Icon', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'url',
				'placeholder' => 'URL',
				'desc'        => __( 'Keep blank to use default icons' ),
				'id'          => 'Woo_Floating_Cart_Settings_floating_cart_icon_url',
			),

			'floating_cart_bg_color'                      => array(
				'name'  => __( 'Cart Banner Background Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color',
			),

			'floating_cart_bg_color_transparancy'         => array(
				'name'        => __( 'Banner Transparancy', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'number',
				'placeholder' => '0.9',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_bg_color_transparancy',
			),

			'floating_cart_checkout_popup_bg_color'       => array(
				'name'  => __( 'Checkout PopUp Background Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color',
			),

			'floating_cart_checkout_popup_bg_color_transparancy' => array(
				'name'        => __( 'PopUp Transparancy', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'number',
				'placeholder' => '0.9',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_popup_bg_color_transparancy',
			),

			'floating_cart_button_text_color'             => array(
				'name'  => __( 'Button Text Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_text_color',
			),

			'floating_cart_button_background_color'       => array(
				'name'  => __( 'Button Background Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_background_color',
			),

			'floating_cart_button_hover_text_color'       => array(
				'name'  => __( 'Button Hover Text Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_text_color',
			),

			'floating_cart_button_hover_background_color' => array(
				'name'  => __( 'Button Hover Background Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_button_hover_background_color',
			),

			'floating_cart_loader_color_first'            => array(
				'name'  => __( 'Loader Primary Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_first',
			),

			'floating_cart_loader_color_second'           => array(
				'name'  => __( 'Loader Secondary Color', 'woocommerce-settings-tab-floating_cart' ),
				'type'  => 'text',
				'class' => 'skrotron-color',
				'id'    => 'Woo_Floating_Cart_Settings_Tab_floating_cart_loader_color_second',
			),

			'floating_cart_icon_horizontal_position'      => array(
				'name'    => __( 'Icon Horizontal Position', 'woocommerce-settings-tab-floating_cart' ),
				'type'    => 'select',
				'options' => array(
					'right' => 'Right',
					'left'  => 'Left',
				),
				'id'      => 'Woo_Floating_Cart_Settings_Tab_floating_cart_icon_horizontal_position',
			),

			'floating_cart_icon_vertical_position'        => array(
				'name'        => __( 'Icon Vertical Position', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'number',
				'placeholder' => '80',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_icon_vertical_position',
			),

			'floating_cart_checkout_text'                 => array(
				'name'        => __( 'Checkout Text', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'text',
				'placeholder' => 'Checkout',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_checkout_text',
			),

			'floating_cart_close_text'                    => array(
				'name'        => __( 'Close Text', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'text',
				'placeholder' => 'CLOSE',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_close_text',
			),

			'floating_cart_empty_text'                    => array(
				'name'        => __( 'Cart Empty Text', 'woocommerce-settings-tab-floating_cart' ),
				'type'        => 'text',
				'placeholder' => 'Cart is empty!',
				'id'          => 'Woo_Floating_Cart_Settings_Tab_floating_cart_empty_text',
			),

			'section_end'                                 => array(
				'type' => 'sectionend',
				'id'   => 'Woo_Floating_Cart_Settings_Tab_section_end',
			),
		);
		return apply_filters( 'woo_floating_cart_settings_tab_settings', $settings );
	}

}
Woo_Floating_Cart_Settings_Tab::init();
