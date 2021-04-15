<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.skrotron.com/
 * @since      1.1.3
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/admin
 */
class Floating_Cart_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/floating-cart-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/floating-cart-admin.js', array( 'jquery' ), $this->version, false );

	}

	/** Function to get color field.
	 *
	 * @param string $skrotronhook_suffix refer color.
	 */
	public function skrotron_floating_cart_enqueue_color_picker( $skrotronhook_suffix ) {
		$handle = 'skrotron-color-handle.js';
		$list   = 'enqueued';
		if ( wp_script_is( $handle, $list ) ) {
			return;
		} else {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'skrotron-color-handle', plugins_url( 'js/skrotron-color-handle.js', __FILE__ ), array( 'wp-color-picker' ), $this->version, true );
		}
	}


}
