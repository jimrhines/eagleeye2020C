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
    'register',
    __('User Registration' , 'wpcf7r-redirect'),
    'WPCF7R_Action_register'
);
 /**
 * Class WPCF7R_Action_login
 * A Class that handles send mail action
 *
 * @version  1.7.0
 */

class WPCF7R_Action_register extends WPCF7R_Action{

    public function __construct($post){
        parent::__construct($post);

    }
    /**
     * Get the fields relevant for this action
     * @return [type] [description]
     *
     * @version  1.7.0
     */
    public function get_action_fields(){

        $parent_fields = parent::get_default_fields();

        $tags = $this->get_mail_tags_array();

        $tags_optional = $this->get_tags_optional();

        $roles = $this->get_available_user_roles();

        $fields = array_merge( array(
                array(
                    'name' => 'user_email',
                    'type' => 'select',
                    'label' => __( 'User Email field', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User email', 'wpcf7-redirect' ),
                    'value' => $this->get('user_email'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags
                ),
                array(
                    'name' => 'user_pass',
                    'type' => 'select',
                    'label' => __( 'User password', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User field', 'wpcf7-redirect' ),
                    'value' => $this->get('user_pass'),
                    'class' => 'qs-col qs-col-6',
                    'options' => array_merge( $tags , array('auto_generate' => __('Auto Generate' , 'wpcf7-redirect' ) ) )
                ),
                array(
                    'name' => 'password_repeat',
                    'type' => 'select',
                    'label' => __( 'Repeat password(Optional)', 'wpcf7-redirect' ),
                    'tooltip' => __('If selected a validation will be made' , 'wpcf-redirect'),
                    'placeholder' => __( 'Repeat password field', 'wpcf7-redirect' ),
                    'value' => $this->get('password_repeat'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags_optional
                ),
                array(
                    'name' => 'user_login',
                    'type' => 'select',
                    'label' => __( 'Username field (optional)', 'wpcf7-redirect' ),
                    'placeholder' => __( 'Username field', 'wpcf7-redirect' ),
                    'tooltip' => __('If empty the user email will be used as login'),
                    'value' => $this->get('user_login'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags_optional
                ),
                array(
                    'name' => 'user_role',
                    'type' => 'select',
                    'label' => __( 'This role will be assigned to the new user', 'wpcf7-redirect' ),
                    'value' => $this->get('user_role'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $roles
                ),
                array(
                    'name' => 'first_name',
                    'type' => 'select',
                    'label' => __( 'User first name', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User first name field', 'wpcf7-redirect' ),
                    'value' => $this->get('first_name'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags_optional
                ),
                array(
                    'name' => 'last_name',
                    'type' => 'select',
                    'label' => __( 'User last name', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User last name field', 'wpcf7-redirect' ),
                    'value' => $this->get('last_name'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags_optional
                ),
                array(
                    'name' => 'description',
                    'type' => 'text',
                    'label' => __( 'User description', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User description', 'wpcf7-redirect' ),
                    'value' => $this->get('description'),
                    'class' => 'qs-col qs-col-6'
                ),

                array(
                    'name' => 'user_url',
                    'type' => 'select',
                    'label' => __( 'User url field', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User url field (optional)', 'wpcf7-redirect' ),
                    'value' => $this->get('user_login'),
                    'class' => 'qs-col qs-col-6',
                    'options' => $tags_optional
                ),
                array(
                    'name' => 'user_nicename',
                    'type' => 'text',
                    'label' => __( 'User nicename', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User nicename field', 'wpcf7-redirect' ),
                    'value' => $this->get('user_nicename'),
                    'class' => 'qs-col qs-col-6'
                ),
                array(
                    'name' => 'display_name',
                    'type' => 'text',
                    'label' => __( 'User display name field', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'User display name field', 'wpcf7-redirect' ),
                    'value' => $this->get('display_name'),
                    'class' => 'qs-col qs-col-6'
                ),

                array(
                    'name' => 'user_field_mapping',
                    'type' => 'section',
                    'footer' => '<div>'.$this->get_formatted_mail_tags().'</div>',
                    'title' => __( 'User extra fields', 'wpcf7-redirect' ),
                    'fields' => array(
                        array(
                            'name' => 'user_fields',
                            'type' => 'repeater',
                            'label' => __( 'Map user meta fields.', 'wpcf7-redirect' ),
                            'placeholder' => '',
                            'show_selector' => '',
                            'tooltip' => __('Fields added here will be created as meta boxes on the user screen'),
                            'value'  => maybe_unserialize( $this->get('user_fields') ),
                            'fields' => array(
                                array(
                                    'name' => 'user_field_key',
                                    'type' => 'text',
                                    'label' => __( 'User meta key', 'wpcf7-redirect' ),
                                    'placeholder' => '',
                                    'show_selector' => '',
                                    'class' => 'validate-eng',
                                    'input_attr' => 'data-rule-validatenospace="true" data-rule-validateenglishnumbers="true"',
                                    'value' => '',
                                ),
                                array(
                                    'name' => 'form_field',
                                    'type' => 'text',
                                    'label' => __( 'Form field (cf7 shortcode)', 'wpcf7-redirect' ),
                                    'placeholder' => '',
                                    'show_selector' => '',
                                    'class' => '',
                                    'value' => '',
                                ),
                            )
                        )
                    )
                ),
                array(
                    'name' => 'auto_login',
                    'type' => 'checkbox',
                    'label' => __( 'Auto login after registration', 'wpcf7-redirect' ),
                    'tooltip' => __( 'User will be logged in Automatically'),
                    'value' => $this->get('auto_login'),
                    'class' => ''
                ),
                array(
                    'name' => 'send_notification',
                    'type' => 'checkbox',
                    'label' => __( 'Send new user notification', 'wpcf7-redirect' ),
                    'tooltip' => __( 'Uses the basic wordpress user notification process'),
                    'value' => $this->get('send_notification'),
                    'class' => '',
                    'show_selector' => '.email_notification',
                ),
                array(
                    'name' => 'email_subject',
                    'type' => 'text',
                    'label' => __( 'Email subject', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'Email subject', 'wpcf7-redirect' ),
                    'value' => $this->get('email_subject'),
                    'class' => $this->get('send_notification') ? 'qs-col qs-col-12 email_notification' : 'qs-col qs-col-6 field-hidden email_notification'
                ),
                array(
                    'name' => 'email_body',
                    'type' => 'editor',
                    'label' => __( 'Email body', 'wpcf7-redirect' ),
                    'tooltip' => __( 'You can combine cf7 tags([tag1] [tag2])', 'wpcf7-redirect' ),
                    'placeholder' => __( 'Email body', 'wpcf7-redirect' ),
                    'value' => $this->get('email_body'),
                    'class' => $this->get('send_notification') ? 'qs-col qs-col-12 email_notification' : 'qs-col qs-col-6 field-hidden email_notification',
                    'footer' => '<div>'.$this->get_formatted_mail_tags().'</div>',
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
     * @version  1.7.0
     */
    public function get_action_settings(){
        $this->get_settings_template('html-action-send-to-email.php');
    }

    /**
     * Map the required registration fields
     * @return [type] [description]
     */
    public function get_mapped_fields( $submission ){

        if( isset( self::$data['mapped_data'] ) ){
            return self::$data['mapped_data'];
        }

        $posted_data = $submission->get_posted_data();

        $mapped_fields = array(
            'user_email',
            'user_pass',
            'first_name',
            'last_name',
            'User description',
            'password_repeat',
            'user_login',
            'user_role',
            'user_nicename',
            'user_url',
            'display_name'
        );

        $mapped_data = array();

        foreach( $mapped_fields as $mapped_field_key => $mapped_field ){
            $form_field = $this->get( $mapped_field );
            $value = isset( $posted_data[$form_field] ) ? $posted_data[$form_field] : $this->replace_tags($form_field);

            //get removed data
            if( isset( WPCF7R_Form::$removed_data[$mapped_field] ) && WPCF7R_Form::$removed_data[$mapped_field] ){
                $value = WPCF7R_Form::$removed_data[$mapped_field];
            }

            if( $value == 'auto_generate' ){
                $value = wp_generate_password();
            }

            $mapped_data[$mapped_field] = array(
                'value' => $value,
                'tag' => $form_field ? $this->get_validation_mail_tags($form_field) : ''
            );
        }

        $user_meta = maybe_unserialize( $this->get('user_fields') );

        $user_meta_array = array();

        if( $user_meta ){
            foreach( $user_meta as $user_meta_key => $user_meta_value ){
                $user_meta_array[$user_meta_key] = isset( $posted_data[$user_meta_key] ) ? $posted_data[$user_meta_key] : $this->replace_tags($user_meta_value);
            }

            $mapped_data['usermeta'] = $user_meta_array;
        }

        self::$data['mapped_data'] = $mapped_data;

        return $mapped_data;
    }

    private function get_validation_error( $tag , $error_message ){
        $error = array(
            'tag' => $tag,
            'error_message' => $error_message
        );

        $valid = new WP_error('tag_invalid' , $error );

        return $valid;
    }
    /**
     * Check if the username or email exists
     * @return [type] [description]
     */
    public function process_validation( $submission ){
        $mapped_user_data = $this->get_mapped_fields( $submission );

        $api_result = array();
        //this is a mandatory field
        if( isset( $mapped_user_data['user_pass'] ) && $mapped_user_data['user_pass']['value'] ){
            //check password repeat
            if( isset( $mapped_user_data['password_repeat'] ) && $mapped_user_data['password_repeat']['value'] ){
                if( $mapped_user_data['password_repeat']['value'] !== $mapped_user_data['user_pass']['value'] ){
                    $api_result['invalid_tags'][] = $this->get_validation_error( $mapped_user_data['password_repeat']['tag'] , __('Passwords are not the same' , 'wpcf7-redirect'));
                }
                //rempove the password to not save it on the db
                unset($_POST[$mapped_user_data['password_repeat']['tag']]);
            }
            //rempove the password to not save it on the db
            unset($_POST[$mapped_user_data['user_pass']['tag']]);
        }else{
            $api_result['invalid_tags'][] = $this->get_validation_error( $mapped_user_data['password_repeat']['tag'] , __('Password is required' , 'wpcf7-redirect'));
        }

        //this is a mandatory field
        if( isset( $mapped_user_data['user_email'] ) && $mapped_user_data['user_email']['value'] ){
            if( username_exists( $mapped_user_data['user_email']['value'] ) || email_exists( $mapped_user_data['user_email']['value'] ) ){
                $api_result['invalid_tags'][] = $this->get_validation_error( $mapped_user_data['user_email']['tag'] , __('Username/Email Already exists' , 'wpcf7-redirect'));
            }
        }else{
            $api_result['invalid_tags'][] = $this->get_validation_error( $mapped_user_data['user_email']['tag'] , __('Username/Email Is required' , 'wpcf7-redirect'));
        }

        return $api_result;
    }

    /**
     * Process the action
     * @param  [type] $submission [description]
     * @return [type]             [description]
     */
    public function process( $submission ){

        $mapped_user_data = $this->get_mapped_fields( $submission );

        $this->register_user( $mapped_user_data );

        return $api_result;
    }

    public function register_user( $mapped_user_data ){

        $userdata = array(
            'user_login'    => $mapped_user_data['user_login']['value'],
            'first_name'    => $mapped_user_data['first_name']['value'],
            'last_name'     => $mapped_user_data['last_name']['value'],
            'description'   => $mapped_user_data['description']['value'],
            'user_url'      => $mapped_user_data['user_url']['value'],
            'user_pass'     => $mapped_user_data['user_pass']['value'],
            'user_email'    => $mapped_user_data['user_email']['value'],
            'user_nicename' => $mapped_user_data['user_nicename']['value'],
            'display_name'  => $mapped_user_data['display_name']['value'],
            'role'          => $this->get('user_role')
        );

        $user_id = wp_insert_user( $userdata ) ;

        if( ! is_wp_error( $user_id ) && isset( $mapped_user_data['usermeta'] ) ){
            foreach( $mapped_user_data['usermeta'] as $meta ){
                update_user_meta( $user_id , $meta['user_field_key'] , $meta['form_field'] );
            }

            /**
             * Send new user notification
             * @var [type]
             */
            if( $this->get('send_notification') ){
                if ( ! is_wp_error( $user_id ) ) {
                    $this->welcome_email( $user_id );
                }
            }

            /**
             * Auto sign on user
             * @var [type]
             */
            if( $this->get('auto_login') ){
                $credentials = array();
            	$credentials['user_login'] = $userdata['user_login'];
            	$credentials['user_password'] = $userdata['user_pass'];

                wp_signon( $credentials, true );
            }

        }
    }
    /**
     * If the authenication was succesfull sign in the user
     * @return [type] [description]
     */
    public function maybe_perform_pre_result_action(){

    }

    public function welcome_email( $user_id ){
          $user = get_userdata($user_id);
          $user_email = $user->user_email;
          // for simplicity, lets assume that user has typed their first and last name when they sign up
          $user_full_name = $user->user_firstname . $user->user_lastname;

          $to = $user_email;

          if( ! ( $subject = $this->get('email_subject') ) ){
              $subject = "Hi " . $user_full_name . ", welcome to our site!";
          }

          $subject = $this->replace_tags($subject);

          if( ! ( $body = $this->get('email_body') ) ){
              $body = '
                    <h1>Dear ' . $user_full_name . ',</h1></br>
                    <p>Thank you for joining our site. Your account is now active.</p>
                    <p>Please go ahead and navigate around your account.</p>
                    <p>Let me know if you have further questions, I am here to help.</p>
                    <p>Enjoy the rest of your day!</p>
                    <p>Kind Regards,</p>
              ';
          }

          $body = $this->replace_tags($body);

          add_filter( 'wp_mail_content_type','wpcf7r_send_emails_as_html' );

          wp_mail( $to , $subject, $body, $headers);

          remove_filter( 'wp_mail_content_type','wpcf7r_send_emails_as_html' );
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
        $password_field = $this->get('user_pass');
        $password_repeat_field = $this->get('password_repeat');

        $results = array(
            'removed_params' => array(
                'user_pass' => isset( $_POST[$password_field] ) ? sanitize_text_field($_POST[$password_field]) : '',
                'password_repeat' => isset( $_POST[$password_repeat_field] ) ? sanitize_text_field($_POST[$password_repeat_field]) : '',
            )
        );

        if( isset( $_POST[$password_field] ) ){
            $_POST[$password_field] = '_removed';
        }

        if( isset( $_POST[$password_repeat_field] ) ){
            $_POST[$password_repeat_field] = '_removed@by_login.action;';
        }

        return $results;
    }
}
