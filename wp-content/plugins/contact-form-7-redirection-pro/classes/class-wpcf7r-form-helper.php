<?php
/**
 * Class WPCF7_Redirect_Form file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7_Redirect_Form
 * Adds contact form scripts and actions
 *
 * @version  1.0.0
 */
class WPCF7_Redirect_Form{

	public $textdomain = 'wpcf7-redirect';
	/**
	 * Construct class
	 *
	 *  @version  1.0.0
	 */
	public function __construct() {
		$this->plugin_url       = WPCF7_PRO_REDIRECT_BASE_URL;
        $this->assets_js_lib 	= WPCF7_PRO_REDIRECT_BASE_URL.'/assets/lib/';
		$this->assets_js_url 	= WPCF7_PRO_REDIRECT_BASE_URL.'/assets/js/';
		$this->assets_css_url 	= WPCF7_PRO_REDIRECT_BASE_URL.'/assets/css/';
        $this->build_js_url     = WPCF7_PRO_REDIRECT_BASE_URL.'/build/js/';
        $this->build_css_url     = WPCF7_PRO_REDIRECT_BASE_URL.'/build/css/';

		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 *  @version  1.0.0
	 */
	private function add_actions() {
		global $pagenow;

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'wpcf7_editor_panels', array( $this, 'add_panel' ) );
		add_action( 'wpcf7_after_save', array( $this, 'store_meta' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );
        //add contact form scripts
        add_action( 'wpcf7_contact_form' , array( $this, 'enqueue_front_end_scripts' )  );

        //add contact form scripts for admin panel
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend' ) );
	}
    /**
     * Only load scripts when contact form instance is created
     * @return [type] [description]
     */
    public function front_end_scripts(){
        wp_register_style( 'wpcf7-redirect-script-frontend', $this->assets_css_url . 'wpcf7-redirect-script-frontend-css.min.css' );
        wp_enqueue_style( 'wpcf7-redirect-script-frontend' );

        wp_register_script( 'wpcf7-redirect-script', $this->assets_js_url . 'wpcf7-redirect-frontend-script.js', array('jquery'), null, true );

        wp_enqueue_script( 'wpcf7-redirect-script' );
        wp_enqueue_script( 'wpcf7-redirect-script-popup' );


        //add support for other plugins
        do_action('wpcf7-redirect-enqueue-frontend' , $this );
    }

    /**
     * Enqueue theme styles and scripts - front-end
     *
     *  @version  1.0.0
     */
    public function enqueue_front_end_scripts( $cf7 ) {
        // $cf7r_form = get_cf7r_form($cf7);
        //
        // if( ! $cf7r_form->has_action('popup') ){
        //     wp_dequeue_script( 'wpcf7-redirect-script-popup' );
        // }
    }
	/**
	 * Check if the current page is the plugin settings page
	 * @return boolean [description]
	 *
	 *  @version  1.0.0
	 */
	public function is_wpcf7_settings_page(){
		return isset( $_GET['page'] ) && $_GET['page'] == 'wpc7_redirect';
	}

    public function is_wpcf7_lead_page(){
        return get_post_type() == 'wpcf7r_leads';
    }
	/**
	 * Check if the current page is the contact form edit screen
	 * @return boolean [description]
	 *
	 *  @version  1.0.0
	 */
	public function is_wpcf7_edit(){
		return isset( $_GET['page'] ) && $_GET['page'] == 'wpcf7' && isset( $_GET['post'] ) && $_GET['post'];
	}

	/**
	 * Load plugin textdomain.
	 *
	 *  @version  1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( $this->textdomain, false, basename( dirname( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Enqueue theme styles and scripts - back-end
	 *
	 *  @version  1.0.0
	 */
	public function enqueue_backend() {

         if( $this->is_wpcf7_edit() || $this->is_wpcf7_settings_page() || $this->is_wpcf7_lead_page() ){
             wp_enqueue_style( 'admin-assets', $this->build_css_url . 'admin-assets.min.css' );
             wp_enqueue_script( 'admin-build-js', $this->build_js_url . 'admin-build.min.js', array(), null, true );

             wp_enqueue_style( 'wpcf7-redirect-admin-style', $this->assets_css_url . 'wpcf-redirect-admin-style.min.css' );

             wp_enqueue_script( 'wpcf7-redirect-validate', $this->assets_js_lib . '/jquery-validate/jquery.validate.min.js', array(), null, true );

             wp_enqueue_script( 'wpcf7-redirect-admin-script', $this->assets_js_url . 'wpcf7-redirect-admin-script.js', array(), null, true );
             wp_enqueue_script( 'wpcf7-redirect-validations', $this->assets_js_url . 'wpcf7-redirect-validation.js', array('wpcf7-redirect-admin-script'), null, true );

            wp_enqueue_script(
                 array(
                   'jquery-ui-core',
                   'jquery-ui-sortable'
                 )
            );
             //add support for other plugins
             do_action('wpcf-7-redirect-admin-scripts' , $this );
         }

	}
	/**
	 * Store form data
	 * @param  [type] $cf7 [description]
	 * @return [type]      [description]
	 *
	 *  @version  1.0.0
	 */
	function store_meta($cf7){
		$form = get_cf7r_form( $cf7->id() );

		$form->store_meta( $cf7 );
	}

	/**
	 * Adds a tab to the editor on the form edit page
	 *
	 * @param array $panels An array of panels. Each panel has a callback function.
	 */
	public function add_panel( $panels ) {

        //disable plugin functionality for old contact form 7 installations
        if( wpcf7_get_cf7_ver() > 4.8 ){
            $panels['redirect-panel'] = array(
    			'title'     => __( 'Actions', $this->textdomain ),
    			'callback'  => array( $this, 'create_panel_inputs' ),
    		);

            $panels['leads-panel'] = array(
                'title'     => __( 'Leads', $this->textdomain ),
                'callback'  => array( $this, 'leads_manager' ),
            );
        }

		return $panels;
	}

	/**
	 * Get the default fields
	 * @return [type] [description]
	 *
	 *  @version  1.0.0
	 */
	public static function get_plugin_default_fields(){
		return array(
			array(
				'name' => 'redirect_type',
				'type' => 'text',
			)
		);
	}

	/**
	 * Handler to retrive banner to display
	 * At the moment used to display the pro version bannner
	 * @return [type] [description]
	 *
	 *  @version  1.0.0
	 */
	public function banner(){
		$banner_manager = new Banner_Manager();

		$banner_manager->show_banner();
	}
	/**
	 * Create the panel inputs
	 *
	 * @param  object $post Post object.
	 */
	 public function create_panel_inputs( $cf7 ) {

		 $form = get_cf7r_form( $cf7->id() );

		 $form->init();

 	}

    /**
     * Create a new contact form 7 tab to manage leads and orders
     * Display the tab only if the form has save leads action
     * @return [type] [description]
     */
    public function leads_manager( $cf7 ){
        $wpcf7r_form = get_cf7r_form( $cf7 );

        if( $wpcf7r_form->has_action('save_lead') ){
            $leads_manager = new WPCF7R_Leads_Manger( $cf7->id() );

            $leads_manager->init();
        }else{
            echo "<h3>".__('You have to define a Save lead action to manage leads on this form' , 'wpcfr-redirect' )."</h3>";
        }

    }
}
