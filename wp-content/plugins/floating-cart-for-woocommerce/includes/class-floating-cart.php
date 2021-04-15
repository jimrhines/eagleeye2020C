<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.skrotron.com/
 * @since      1.1.3
 *
 * @package    Floating_Cart
 * @subpackage Floating_Cart/includes
 */

/**
 * The core plugin class.
 *
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
class Floating_Cart {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.3
	 * @var      Floating_Cart_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.3
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.3
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.1.3
	 */
	public function __construct() {
		if ( defined( 'FLOATING_CART_VERSION' ) ) {
			$this->version = FLOATING_CART_VERSION;
		} else {
			$this->version = '1.1.3';
		}
		$this->plugin_name = 'floating-cart';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Floating_Cart_Loader. Orchestrates the hooks of the plugin.
	 * - Floating_Cart_i18n. Defines internationalization functionality.
	 * - Floating_Cart_Admin. Defines all hooks for the admin area.
	 * - Floating_Cart_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.1.3
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-floating-cart-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-floating-cart-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-floating-cart-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-floating-cart-public.php';

		$this->loader = new Floating_Cart_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Floating_Cart_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.1.3
	 */
	private function set_locale() {

		$plugin_i18n = new Floating_Cart_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.1.3
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Floating_Cart_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'skrotron_floating_cart_enqueue_color_picker' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.1.3
	 */
	private function define_public_hooks() {

		$plugin_public = new Floating_Cart_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'floating_cart_ajaxurl' );
		$this->loader->add_action( 'woocommerce_init', $plugin_public, 'floating_cart_force_non_logged_user_wc_session' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'floating_cart_ajax_add_to_cart_js', 99 );
		$this->loader->add_action( 'wp_ajax_floating_cart_ajax_add_to_cart', $plugin_public, 'floating_cart_ajax_add_to_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_floating_cart_ajax_add_to_cart', $plugin_public, 'floating_cart_ajax_add_to_cart' );
		$this->loader->add_action( 'wp_ajax_floating_cart_ajax_grouped_add_to_cart', $plugin_public, 'floating_cart_ajax_grouped_add_to_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_floating_cart_ajax_grouped_add_to_cart', $plugin_public, 'floating_cart_ajax_grouped_add_to_cart' );
		$this->loader->add_action( 'wp_ajax_floating_cart_ajax_get_cart_contents', $plugin_public, 'floating_cart_ajax_get_cart_contents' );
		$this->loader->add_action( 'wp_ajax_nopriv_floating_cart_ajax_get_cart_contents', $plugin_public, 'floating_cart_ajax_get_cart_contents' );
		$this->loader->add_action( 'wp_ajax_floating_ajax_product_remove', $plugin_public, 'floating_ajax_product_remove' );
		$this->loader->add_action( 'wp_ajax_nopriv_floating_ajax_product_remove', $plugin_public, 'floating_ajax_product_remove' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'load_in_footer_function' );
		$this->loader->add_action( 'wp_ajax_floating_get_cart_count', $plugin_public, 'floating_get_cart_count' );
		$this->loader->add_action( 'wp_ajax_nopriv_floating_get_cart_count', $plugin_public, 'floating_get_cart_count' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.1.3
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.1.3
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.1.3
	 * @return    Floating_Cart_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.1.3
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
