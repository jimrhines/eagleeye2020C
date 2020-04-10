<?php
/**
 * Class WPCF7_Redirect_Base file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7_Redirect_Base
 * The main class that manages the plugin
 *
 * @version  1.0.0
 */
class WPCF7_Redirect_Plugin_Base{

	public static $instance;

	/**
	 * class constructor
	 *
	 * @version  1.0.0
	 */
    public function __construct(){
		self::$instance = $this;

		$this->plugin_path = WPCF7_PRO_REDIRECT_BASE_PATH;

		$this->version  = WPCF7_PRO_REDIRECT_PLUGIN_VERSION;

		$this->load_dependencies();

		$this->init_plugin_dependencies();

		$this->post_types();

        $this->add_action_hooks();

        $this->add_ajax_hooks();

    }
	/**
	 * Create instances of all required objects
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function init_plugin_dependencies(){
		$this->wpcf_settings = new WPCF7_Redirect_Settings();

		$this->wpcf7_redirect = new WPCF7_Redirect_Form();

		$this->wpcf7_utils = new WPCF7_Redirect_Utilities();

		$this->wpcf7_submission = new WPCF7_Redirect_Submission();

		$this->wpcf7_updates_class = new Wpcf7_redirect_AutoUpdate( $this->version , WPCF7_PRO_REDIRECT_BASE_NAME);

        $this->wpcf7_updates_class = new WPCF7R_User();
	}

	/**
	 * Register the post type required for the action managment
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function post_types(){
		new WPCF7R_post_types();
	}

	/**
	 * Get a singelton
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Some general plugin hooks
     *
     * @version  1.0.0
     */
    public function add_action_hooks(){
		//display banner on the redirect settings page
        //the banner will be used to the premium version
        //add_action( 'before_redirect_settings_tab_title' , array( $this->wpcf7_utils , 'get_banner' ) , 10 );
 		add_action( 'before_settings_fields' , array( $this->wpcf7_utils , 'show_admin_notices' ) , 10 );
 		//form submission hook
 		add_action( 'wpcf7_before_send_mail', array( $this->wpcf7_submission , 'handle_valid_actions' ) );
        //validation actions
        add_filter( 'wpcf7_validate', array( $this->wpcf7_submission , 'handle_validation_actions' ) ,10 ,2 );
 		//handle contact form response
 		add_filter( 'wpcf7_ajax_json_echo' , array( $this->wpcf7_submission , 'manipulate_cf7_response_object' ) , 10 , 2 );
		//support for browsers that does not support ajax
		add_action( 'wpcf7_submit', array( $this->wpcf7_submission, 'non_ajax_redirection' ) );

 		add_action( 'after_plugin_row_' . WPCF7_PRO_REDIRECT_BASE_NAME , array( $this->wpcf7_utils , 'license_details_message' ), 10, 2 );
        //handle form duplication
        add_action( 'wpcf7_after_create', array( $this->wpcf7_utils, 'duplicate_form_support' ) );
        //handle form deletion
        add_action( 'before_delete_post', array( $this->wpcf7_utils, 'delete_all_form_actions' ) );
        //catch submission for early $_POST manupulations
        add_action( 'wpcf7_contact_form' , array( $this->wpcf7_submission, 'after_cf7_object_created' ) );
    }

    /**
     * Register plugins ajax hooks
     *
     * @version  1.0.0
     */
    public function add_ajax_hooks(){
        add_action( 'wp_ajax_close_ad_banner' , array( $this->wpcf7_utils , 'close_banner' ) );

		add_action( 'wp_ajax_wpcf7r_delete_action' , array( $this->wpcf7_utils , 'delete_action_post' ) );

		add_action( 'wp_ajax_wpcf7r_add_action' , array( $this->wpcf7_utils , 'add_action_post' ) );
        //save actions order
        add_action( 'wp_ajax_wpcf7r_set_action_menu_order', array( $this->wpcf7_utils, 'set_action_menu_order' ) );
        //make an api test
        add_action( 'wp_ajax_wpcf7r_make_api_test', array( $this->wpcf7_utils, 'make_api_test' ) );
        //get mailchimp lists
        add_action( 'wp_ajax_wpcf7r_get_mailchimp_lists', array( $this->wpcf7_utils, 'get_mailchimp_lists' ) );
        //alert conflicts
        add_action( 'admin_init' , array( $this , 'alert_conflicts' ) );
    }

    public function alert_conflicts(){
        if( class_exists('Mailchimp') ){
            $reflector = new ReflectionClass('Mailchimp');

            WPCF7_Redirect_Utilities::add_admin_notice('alert' , sprintf( __('There might be a conflict that causing mailchimp action not to function properly, disable any mailchimp plugin to avoid problems, mailchimp is already defined on %s') , $reflector->getFileName() ));
        }
    }
    /**
     * Get files required to run the plugin
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function load_dependencies(){
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-leads-manager.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-lead.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-settings.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-submission.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-utils.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-form-helper.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-post-types.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-actions.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-form.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-html.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-qs-api.php' );
		require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-updates.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-action.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-user.php' );
        require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-mailchimp.php' );
        require_once( WPCF7_PRO_REDIRECT_BASE_PATH . 'vendor/autoload.php' );

        if( ! class_exists('Mailchimp') ){
            require_once( WPCF7_PRO_REDIRECT_BASE_PATH . 'vendor/autoload.php' );
            require_once( WPCF7_PRO_REDIRECT_CLASSES_PATH . 'class-wpcf7r-mailchimp.php' );
        }
        
        /*
         * Load all actions
         * @var [type]
         */
        foreach ( glob( WPCF7_PRO_REDIRECT_CLASSES_PATH. "actions/*.php") as $filename){
            require_once($filename);
        }
    }
}
