<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.skrotron.com/
 * @since      1.1.3
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.3
 * @package    Floating_Cart
 * @subpackage Floating_Cart/includes
 */
class Floating_Cart_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.3
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'floating-cart',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
