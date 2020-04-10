<?php
/**
 * Class WPCF7R_Action_login file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

register_wpcf7r_actions(
    'login',
    __('User Login' , 'wpcf7r-redirect'),
    'WPCF7R_Action_login'
);
 /**
 * Class WPCF7R_Action_login
 * A Class that handles send mail action
 *
 * @version  1.6.0
 */

class WPCF7R_Action_login extends WPCF7R_Action{

    public function __construct($post){
        parent::__construct($post);

		$this->priority = 2;
    }
    /**
     * Get the fields relevant for this action
     * @return [type] [description]
     *
     * @version  1.6.0
     */
    public function get_action_fields(){

        $parent_fields = parent::get_default_fields();

        $tags = $this->get_mail_tags_array();

        $fields = array_merge( array(
                array(
                    'name' => 'login_field',
                    'type' => 'select',
                    'label' => __( 'The field that is used for username ([username])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'Username field', 'wpcf7-redirect' ),
                    'tooltip' => __('Add a text field to your form and save it' , 'wpcf-redirect'),
                    'footer' => '<div>'.$this->get_formatted_mail_tags().'</div>',
                    'value' => $this->get('login_field'),
                    'class' => '',
                    'options' => $tags
                ),
                array(
                    'name' => 'password_field',
                    'type' => 'select',
                    'label' => __( 'The field that is used for the user password', 'wpcf7-redirect' ),
                    'tooltip' => __('Passwords will be processed securly' , 'wpcf-redirect'),
                    'placeholder' => __( 'Password field', 'wpcf7-redirect' ),
                    'footer' => '<div>'.$this->get_formatted_mail_tags().'</div>',
                    'value' => $this->get('password_field'),
                    'class' => '',
                    'options' => $tags
                ),
                array(
                    'name' => 'validation_error',
                    'type' => 'text',
                    'label' => __( 'Validation error message', 'wpcf7-redirect' ),
                    'placeholder' => __( 'Validation error message', 'wpcf7-redirect' ),
                    'footer' => '',
                    'value' => $this->get('validation_error') ? $this->get('validation_error') : __('The user name or password are incorrect' , 'wpcf7-redirect'),
                    'class' => '',
                    'options' => $tags
                ),
                array(
                    'name' => 'remember_me',
                    'type' => 'checkbox',
                    'label' => __( 'Remember login ?', 'wpcf7-redirect' ),
                    'tooltip' => __( 'Acts as "remember me checkbox"', 'wpcf7-redirect' ),
                    'value' => $this->get('remember_me'),
                    'class' => '',
                ),
                'notice' => array(
                    'name' => 'general-alert',
                    'type' => 'notice',
                    'label' => __( 'For Security Reasons!', 'wpcf7-redirect' ),
                    'sub_title' => __( '<ul><li>1.Default form emails will be disabled.</li><li>2.User password will not be stored.</li><li>2.Default validations will not work here, setup custom errors action.</li><li>4.It is highly recommended to use <a href="https://en.wikipedia.org/wiki/HTTPS" target="_blank">https</a> for login forms.</li><li>5.To avoid risk of spam and brute force attempts it is recommended to setup <a href="https://contactform7.com/recaptcha/" target="_blank">google captcha</a></li><li>6.<strong>If you like the user to be redirected after login just setup another "Redirect" action and set a condition to use for logged-in user only</strong></li></ul>', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'class' => 'field-notice-alert',
                    'show_selector' => ''
                ),
            ),
            $parent_fields
        );

        return $fields;
    }

    /**
     * Get settings page
     * @return [type] [description]
     *
     * @version  1.6.0
     */
    public function get_action_settings(){
        $this->get_settings_template('html-action-send-to-email.php');
    }

    /**
     * Handle a simple redirect rule
     * @param  [type] $rules    [description]
     * @param  [type] $response [description]
     * @return [type]           [description]
     *
     * @version  1.6.0
     */
    public function process( $submission ){
        $api_result = array();

        $api_result['invalid_tags'] = array();

        /**
         * Get the tags that are used to send the username and password
         * @var [type]
         */
        $login_field = $this->get('login_field');
        $password_field = $this->get('password_field');

        /**
         * Get the posted username and password
         * @var [type]
         */
        $posted_username = WPCF7R_Form::$removed_data['username'];
        $posted_password = WPCF7R_Form::$removed_data['password'];

        $check = false;

        $results = array();

        $api_result = array();

        if( $user_exists = username_exists( $posted_username ) ){
            $this->check = wp_authenticate_username_password( NULL, $posted_username , $posted_password);

        }else{
            $this->check = new WP_Error('login' , __('Incorrect username or password' ,'wpcf7-redirect') );
        }

        if( is_wp_error( $this->check ) ){
            $error = array(
				'tag' => $login_field_tag,
				'error_message' => __('Incorrect username or password' ,'wpcf7-redirect')
			);

			$api_result['invalid_tags'][] = new WP_error('tag_invalid' , $error );
        }else{
            $this->check = array();
            $this->check['user_login'] = $posted_username;
            $this->check['user_password'] = $posted_password;
            $this->check['remember'] = $this->get('remember_me');
        }

        return $api_result;
    }

    /**
     * If the authenication was succesfull sign in the user
     * @return [type] [description]
     */
    public function maybe_perform_pre_result_action(){
        if( ! is_wp_error( $this->check ) ){
            $user = wp_signon( $this->check, false );
        }
    }

    /**
     * Remove username and password from posted data object
     * @return [type] [description]
     */
    public function remove_password_and_username( $posted_data ){
        $login_field = $this->get('login_field');
        $password_field = $this->get('password_field');

        $login_field_tag = $this->get_validation_mail_tags( $login_field );
        $password_field_tag = $this->get_validation_mail_tags( $password_field );

    }

    /**
     * Process all pre cf7 submit actions
     * @return [type] [description]
     */
    public function process_pre_submit_actions(){

        /**
         * Get the tags that are used to send the username and password
         * @var [type]
         */
        $login_field = $this->get('login_field');
        $password_field = $this->get('password_field');

        $results= array(
            'removed_params' => array(
                'username' => $_POST[$login_field],
                'password' => $_POST[$password_field]
            )
        );

        $_POST[$login_field] = '_removed';
        $_POST[$password_field] = '_removed@by_login.action;';

        return $results;
    }
}
