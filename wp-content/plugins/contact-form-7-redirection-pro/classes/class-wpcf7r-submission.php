<?php

/**
 * Class WPCF7_Redirect_Submission file.
 *
 * @package cf7r
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact form 7 redirect Submission handler
 *
 * @version  1.0.0
 */
class WPCF7_Redirect_Submission{

	public function __construct(){

	}
    /**
     * Change the response object returned to the client
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function manipulate_cf7_response_object($response){

        if( isset( $this->response ) && $this->response ){
            $response = array_merge( $this->response , $response );
        }

        return apply_filters( 'wpcf7r_manipulate_cf7_response_object' , $response , $this );
    }

	/**
	 * Add plugin support to browsers that don't support ajax
	 *
	 * @version  1.0.0
	 */
	public function non_ajax_redirection( $wpcf7 ) {

		if ( ! WPCF7_Submission::is_restful() ) {

			$submission = WPCF7_Submission::get_instance();

			$wpcf7r_Form = get_cf7r_form( $wpcf7 , $submission );

			if ( $wpcf7r_Form->get_submission_status() == 'mail_sent' ) {

				$results = $this->handle_form_submission( $wpcf7 );

				if( $results ){
					foreach( $results as $result_type => $result_actions ){
						if( $result_type == 'fire_script' && $result_action){
							$this->scripts = $result_actions;

							add_action('wp_head' , array( $this , 'add_header_script'));
						}

						foreach( $result_actions as $result_action ){
							if( $result_type == 'redirect' ){
								$this->redirect_url = $result_action['redirect_url'];

								if ( $result_action['type'] == 'new_tab' ) {
									add_action('wp_head' , array( $this , 'open_new_tab'));
								} else {
									//do this last
									add_action('wp_head' , array( $this , 'redirect') , 9999);
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Print header scripts for non ajax submits
	 *
	 * @version  1.0.0
	 */
	public function add_header_script(){
		if( isset( $this->scripts ) && $this->scripts ){
			foreach( $this->scripts as $script ){
				wp_add_inline_script( 'wpcf7-redirect-script', $script );
			}
		}
	}
	/**
	 * Redirect support for non ajax browsers
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function redirect(){
		//allow other plugins to manipulate the redirect url settings
		$redirect_url = apply_filters( 'wpcf7-redirect-url' , $this->redirect_url );

		wp_add_inline_script( 'wpcf7-redirect-script', 'window.location ="'. $redirect_url .'";' );
	}
	/**
	 * Open new tab on non ajax submits
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function open_new_tab(){
		//allow other plugins to manipulate the redirect url settings
		$redirect_url = apply_filters( 'wpcf7-redirect-url' , $this->redirect_url );

		wp_add_inline_script( 'wpcf7-redirect-script', 'window.open("'. $redirect_url .'");' );
	}
    /**
     * This function is fired before send email
     * @return [type] [description]
     *
     * @version  1.4.3
     *
     */
    public function handle_valid_actions( $wpcf7 ){

		$submission = WPCF7_Submission::get_instance();

		$wpcf7_Form = get_cf7r_form( $wpcf7 , $submission );

    	if ( $submission && $wpcf7_Form->has_actions() ) {
			//get the submitted data
			$this->posted_data = $submission->get_posted_data();

			//process all relevant actions
			$this->response = $wpcf7_Form->process_actions();

			//skip default contact form 7 email
			if( isset( $this->response['send_mail'] ) && $this->response['send_mail'] ){
				add_filter( 'wpcf7_skip_mail', function(){
					return true;
				} );
			}

			$this->save_lead_actions( $this->response );

			$wpcf7_Form->maybe_perform_pre_result_action();

			return $this->response;
    	}
    }
	/**
	 * Save lead actions
	 * @param  [type] $actions_results [description]
	 * @return [type]                  [description]
	 */
	public function save_lead_actions( $actions_results ){

		foreach( $actions_results as $action_type => $actions_result ){
			WPCF7R_Leads_Manger::save_action( WPCF7R_Action::get_lead_id() , $action_type , $actions_result );
		}
	}

	/**
	 * Early hook to catch cf7 submissions before they are processed
	 * @return [type] [description]
	 */
	public function after_cf7_object_created( $cf7 ){
		if ( WPCF7_Submission::is_restful() || isset( $_POST['_wpcf7'] ) ) {
			//get an instance of contact form 7 redirection post
			$wpcf7_Form = get_cf7r_form( $cf7 );

			//check if the form has validation actions
	    	if ( $actions = $wpcf7_Form->get_active_actions() ) {
				foreach( $actions as $action ){
					$results = $action->process_pre_submit_actions();
					//saved reference for the items removed from the $_POST data
					if( isset( $results['removed_params'] ) && $results['removed_params'] ) {
						$wpcf7_Form->set_removed_posted_data($results['removed_params']);
					}
				}
			}
		}
	}

	/**
	 * Handle validation actions
	 * @param  [type] $results [description]
	 * @param  [type] $tags    [description]
	 * @return [type]          [description]
	 */
	public function handle_validation_actions( $wpcf_validation_obj , $tags ){
		//store refrence to the form tags status
		$wpcf_validation_obj->tags = $tags;
		//get an instance of the form submission
		$submission = WPCF7_Submission::get_instance();
		//get an instance of the contact form 7 form
		$cf7_form = $submission->get_contact_form();
		//get an instance of contact form 7 redirection post
		$wpcf7_Form = get_cf7r_form( $cf7_form , $submission , $wpcf_validation_obj );
		//check if the form has validation actions
    	if ( $submission && $wpcf7_Form->has_actions() ) {
			//process all actions
			$invalid_tags = $wpcf7_Form->process_validation_actions();
		}

		/**
		 * Invalidate all invalid tags
		 * @var [type]
		 */
		if( isset( $invalid_tags ) && $invalid_tags ){
			foreach( reset( $invalid_tags ) as $custom_error_action_results ){
				if( isset( $custom_error_action_results['invalid_tags'] )  && $custom_error_action_results['invalid_tags'] ){
					foreach( $custom_error_action_results['invalid_tags'] as $wp_error ){
						$error = $wp_error->get_error_message();

						$wpcf_validation_obj->invalidate( $error['tag'] , __( $error['error_message'] ) );
					}
				}
			}
		}

		return $wpcf_validation_obj;
	}
}
