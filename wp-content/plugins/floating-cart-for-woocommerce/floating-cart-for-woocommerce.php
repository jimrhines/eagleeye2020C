<?php
/**
 * Plugin Name: Floating Cart For WooCommerce
 * Plugin URI: http://www.skrotron.com/woospinwheel/shop/
 * Description: Floating Cart is a WooCommerce extension that provide a “Smart Cart Feature” to your site which Boost Sales & Conversions with a Floating Cart.
 * Version: 1.1.3
 * Author: SkroTron
 * Author URI: http://www.skrotron.com/
 * Developer: SkroTron
 * Developer URI: http://www.skrotron.com/
 * Text Domain: floating-cart-for-woocommerce
 * Domain Path: /languages
 *
 * @package Floating_Cart
 *
 * Woo: 5468851:e70f5d32fe81a1d8196781bb139fd1c4
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'FLOATING_CART_PLUGIN_DIR' ) ) {
	define( 'FLOATING_CART_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'FLOATING_CART_PLUGIN_URL' ) ) {
	define( 'FLOATING_CART_PLUGIN_URL', plugins_url() . '/floating-cart-for-woocommerce' );
}

/**
 * Currently plugin version.
 * Start at version 1.1.3 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FLOATING_CART_VERSION', '1.1.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-floating-cart-activator.php
 */
function activate_floating_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-floating-cart-activator.php';
	Floating_Cart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-floating-cart-deactivator.php
 */
function deactivate_floating_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-floating-cart-deactivator.php';
	Floating_Cart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_floating_cart' );
register_deactivation_hook( __FILE__, 'deactivate_floating_cart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-floating-cart.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-floating-cart-settings-tab.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.3
 */
function run_floating_cart() {

	$plugin = new Floating_Cart();
	$plugin->run();

}
run_floating_cart();
