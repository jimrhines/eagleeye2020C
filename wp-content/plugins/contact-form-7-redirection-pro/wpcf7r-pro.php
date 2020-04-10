<?php
/**
 * Plugin Name:  Contact Form 7 Redirection Pro
 * Plugin URI:   http://querysol.com/product/contact-form-7-redirection/
 * Description:  Contact Form 7 Add-on - Redirect after mail sent And much more.
 * Version:      1.8.2
 * Author:       Query Solutions
 * Author URI:   http://querysol.com
 * Contributors: querysolutions
 * Requires at least: 4.7.0
 *
 * Text Domain: wpcf7-redirect
 * Domain Path: /lang
 *
 * @package Contact Form 7 Redirection
 * @category Contact Form 7 Addon
 * @author Query Solutions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined('CF7_REDIRECT_DEBUG') ){
	define('CF7_REDIRECT_DEBUG' , false );
}
define( 'WPCF7_PRO_REDIRECT_PLUGIN_VERSION' , '1.8.2' );

class CF7R_PRO_Initializer{

	public function init(){
		$this->define();

		$this->load_dependencies();

		$this->cf7_redirect_base = new WPCF7_Redirect_Plugin_Base();

		add_action( 'plugins_loaded' , array( $this , 'notice_to_remove_old_plugin') );
	}

	public function load_dependencies(){
		/**
         * Load all actions
         * @var [type]
         */
        foreach ( glob( WPCF7_PRO_REDIRECT_BASE_PATH. "modules/*.php") as $filename){
            require_once($filename);
        }

		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-base.php' );
	}

	public function notice_to_remove_old_plugin(){
		if( !function_exists('is_plugin_active') ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'wpcf7-redirect/wpcf7-redirect.php' ) ) {
		    add_action( 'admin_notices', 'wpcf7_remove_old_plugin_notice' );
		}

		if ( is_plugin_active( 'cf7-to-api/cf7-to-api.php' ) ) {
			add_action( 'admin_notices', 'wpcf7_remove_contact_form_7_to_api' );
		}
	}

	public function define(){
		define( 'WPCF7_PRO_REDIRECT_PLUGIN_PAGE_URL' , 'https://querysol.com/product/contact-form-7-redirection/' );
		define( 'WPCF7_PRO_REDIRECT_PLUGIN_ACTIVATION_URL' , 'https://querysol.com/wp-admin/admin-ajax.php' );
		define( 'WPCF7_PRO_REDIRECT_PLUGIN_UPDATES' , 'https://querysol.com/wp-json/api-v1/check-for-updates/' );
		define( 'WPCF7_PRO_REDIRECT_PLUGIN_ID' , '6XpU046EOVs7v6O' );
		define( 'WPCF7_PRO_REDIRECT_PLUGIN_SKU' , 'wpcfr-pro' );
		define( 'WPCF7_PRO_REDIRECT_BASE_NAME' ,plugin_basename( __FILE__ ) );
		define( 'WPCF7_PRO_REDIRECT_BASE_PATH' , plugin_dir_path( __FILE__ ) );
		define( 'WPCF7_PRO_REDIRECT_BASE_URL' , plugin_dir_url( __FILE__ ) );
		define( 'WPCF7_PRO_REDIRECT_CLASSES_PATH' , WPCF7_PRO_REDIRECT_BASE_PATH.'classes/' );
		define( 'WPCF7_PRO_REDIRECT_PLUGINS_PATH' , plugin_dir_path( dirname( __FILE__ ) ) );
		define( 'WPCF7_PRO_REDIRECT_TEMPLATE_PATH' , WPCF7_PRO_REDIRECT_BASE_PATH.'templates/' );
		define( 'WPCF7_PRO_REDIRECT_ACTIONS_TEMPLATE_PATH' , WPCF7_PRO_REDIRECT_CLASSES_PATH.'actions/html/' );
		define( 'WPCF7_PRO_REDIRECT_FIELDS_PATH' , WPCF7_PRO_REDIRECT_TEMPLATE_PATH.'fields/' );
		define( 'WPCF7_PRO_REDIRECT_POPUP_TEMPLATES_PATH' , WPCF7_PRO_REDIRECT_TEMPLATE_PATH.'popups/' );
	}

}

register_activation_hook(__FILE__, 'wpcf7_pro_activation_process');

function wpcf7_pro_activation_process(){
	if( ! get_option('wpcf7_redirect_pro_verion') ){
		update_option('wpcf7_redirect_pro_verion' , WPCF7_PRO_REDIRECT_PLUGIN_VERSION );
	}

	deactivate_plugins( 'cf7-to-api/contact-form-7-api.php' );
	deactivate_plugins( 'wpcf7-redirect/wpcf7-redirect.php' );
}

require_once( plugin_dir_path( __FILE__ ) . 'wpcf7r-functions.php' );


function wpcf7_redirect_pro_init(){
	// globals
	global $cf7_redirect;

	// initialize
	if( !isset( $cf7_redirect ) ) {
		$cf7_redirect = new CF7R_PRO_Initializer();
		$cf7_redirect->init();
	}


	// return
	return $cf7_redirect;
}

wpcf7_redirect_pro_init();
