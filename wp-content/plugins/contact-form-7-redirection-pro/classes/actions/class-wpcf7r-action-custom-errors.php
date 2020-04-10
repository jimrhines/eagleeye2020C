<?php
/**
 * Class WPCF7R_Action_custom-errors file.
 *
 * @package cf7r
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
register_wpcf7r_actions(
    'custom_errors',
    __('Custom errors' , 'wpcf7r-redirect'),
    'WPCF7R_Action_custom_errors'
);
/**
 * Class Class WPCF7R_Action_custom-errors
 * Handle custom error messages
 *
 * @version  1.0.0
 */

class WPCF7R_Action_custom_errors extends WPCF7R_Action{

    public function __construct($post){
        parent::__construct($post);

        $this->api_base = new WPCF7R_Action_send_to_api($post);

		$this->priority = 1;
    }
    /**
     * Get the fields relevant for this action
     * @return [type] [description]
     */
    public function get_action_fields(){

        return array_merge( array(
                array(
                    'name' => 'conditional_tags_mapping',
                    'type' => 'repeater',
                    'label' => '',
                    'sub_title' => __( 'Conditions.', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'show_selector' => '',
                    'value'  => maybe_unserialize( $this->get('conditional_tags_mapping') ),
                    'fields' => array(
                        'tag' => array(
                            'name' => 'tag',
                            'type' => 'select',
                            'label' => __( 'Tag', 'wpcf7-redirect' ),
                            'placeholder' => __( 'Select a tag', 'wpcf7-redirect' ),
                            'value' => $this->get('tag') ? $this->get('tag') : '',
                            'options' => $this->get_mail_tags_array(),
                            'class' => '',
                        ),
                        array(
                            'name' => 'condition',
                            'type' => 'select',
                            'label' => __( 'Condition', 'wpcf7-redirect' ),
                            'sub_title' => '',
                            'placeholder' => '',
                            'show_selector' => '',
                            'class' => '',
                            'value' => '',
                            'options' => array(
                                'contain' => __('Contains' , 'wpcf7-redirect'),
                                'not_contain' => __('Does not Contain' , 'wpcf7-redirect'),
                                'equal' => __('Equal' , 'wpcf7-redirect'),
                                'not_equal' => __('Not Equal' , 'wpcf7-redirect'),
                                'greater_than' => __('Greater Than' , 'wpcf7-redirect'),
                                'less_than' => __('Less Than' , 'wpcf7-redirect'),
								'is_null' => __('Is Empty' , 'wpcf7-redirect'),
								'is_not_null' => __('Is Not Empty' , 'wpcf7-redirect'),
								'numeric_only' => __('Is Numeric' , 'wpcf7-redirect'),
								'valid_email' => __('Is Email' , 'wpcf7-redirect'),
								'user_exists' => __('User Exists' , 'wpcf7-redirect'),
								'email_exists' => __('Email Exists' , 'wpcf7-redirect'),
                            )
                        ),
                        array(
                            'name' => 'value',
                            'type' => 'text',
                            'label' => __( 'Value', 'wpcf7-redirect' ),
                            'sub_title' => '',
                            'placeholder' => '',
                            'show_selector' => '',
                            'class' => '',
                            'value' => '',
                        ),
                        array(
                            'name' => 'error_message',
                            'type' => 'text',
                            'label' => __( 'Error message', 'wpcf7-redirect' ),
                            'sub_title' => '',
                            'placeholder' => '',
                            'show_selector' => '',
                            'class' => '',
                            'value' => '',
                        ),
                    )
                ),
            ),
            parent::get_default_fields()
        );
    }

    /**
     * Get settings page
     * @return [type] [description]
     */
    public function get_action_settings(){
        $this->get_settings_template('html-action-send-to-email.php');
    }
	/**
	 * Get a clean array of error messages from the action settings
	 * @return [type] [description]
	 */
	public function get_error_rules(){
		$error_rules = maybe_unserialize( $this->get('conditional_tags_mapping') );

		return $error_rules;
	}
	/**
	 * Process validation action
	 * This function will be called on validation hook
	 * @return [type] [description]
	 */
	public function process_validation( $submission ){
		$api_result = array();

		$api_result['invalid_tags'] = array();

		$tags = $this->get_validation_mail_tags();

		$error_rules = $this->get_error_rules();

		if( $error_rules && $tags ){
			foreach( $error_rules as $error_rule ){
				$validation_result = $this->is_valid_tag( $error_rule , $submission );

				if( is_wp_error( $validation_result )){
					$api_result['invalid_tags'][] = $validation_result;
				}
			}
		}

		return $api_result;
	}
    /**
     * Handle a simple redirect rule
     * @param  [type] $rules    [description]
     * @param  [type] $response [description]
     * @return [type]           [description]
     */
    public function process( $submission ){

    }
	/**
	 * Validate the rule
	 * @return boolean [description]
	 */
	public function is_valid_tag( $error_rule , $submission ){
		$tag = $this->get_validation_mail_tags( $error_rule['tag'] );
		$valid = true;

		$posted_data = $submission->get_posted_data();

        if( isset( $error_rule['condition'] ) && $error_rule['condition'] ){
			$posted_value = $posted_data[$error_rule['tag']];
			$compare_value = $error_rule['value'];

            switch( $error_rule['condition'] ){
                case 'equal':
                    if( is_array( $posted_data ) && isset( $posted_value )){
                        $valid = in_array( $compare_value , $posted_value ) || $compare_value ==  $posted_value ? true : false;
                    }else{
                        $valid = $compare_value === $posted_value;
                    }
                    break;
                case 'not-equal':
                    if( is_array( $posted_data ) ){
                        $valid = ! in_array( $compare_value , $posted_value );
                    }else{
                        $valid = $compare_value !== $posted_value;
                    }
                    break;
                case 'contain':
                    $valid = strpos( $posted_value , $compare_value) === false;
                    break;
                case 'not-contain':
                    $valid = strpos( $posted_value , $compare_value) !== false;
                    break;
				case 'greater_than':
                    $valid = $posted_value > $compare_value;
                    break;
				case 'less_than':
                    $valid = $posted_value < $compare_value;
                    break;
				case 'is_null':
                    $valid = $posted_value === '';
                    break;
				case 'is_not_null':
                    $valid = $posted_value !== '';
                    break;
				case 'numeric_only':
                    $valid = is_numeric($posted_value);
                    break;
				case 'valid_email':
					$valid = is_email($posted_value);
					break;
				case 'user_exists':
					$valid = ! username_exists($posted_value);
					break;
				case 'email_exists':
					$valid = ! email_exists($posted_value);
					break;
            }
        }

		if( ! $valid){
			$error = array(
				'tag' => $tag,
				'error_message' => $error_rule['error_message']
			);

			$valid = new WP_error('tag_invalid' , $error );
		}

		return $valid;
	}

    public function get_tag_rule( $tag_name ){
        if( ! isset( $this->tag_rules ) ){
            $this->tag_rules = maybe_unserialize( $this->get('conditional_tags_mapping') );
        }
    }
}
