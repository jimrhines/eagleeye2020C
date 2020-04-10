<?php
/**
 * Class WPCF7R_Action_api_url_request file.
 *
 * @package cf7r
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_wpcf7r_actions(
    'api_json_xml_request',
    __('API XML/JSON Request' , 'wpcf7r-redirect'),
    'WPCF7R_Action_api_json_xml_request'
);
 /**
 * Class WPCF7R_Action_api_url_request
 * A Class that handles send send to api process
 *
 * @version  1.0.0
 */

class WPCF7R_Action_api_json_xml_request extends WPCF7R_Action{

    public function __construct($post){
        parent::__construct($post);

        $this->api_base = new WPCF7R_Action_send_to_api($post);
    }
    /**
     * Get the fields relevant for this action
     * @return [type] [description]
     */
    public function get_action_fields(){

        $xml_example = '<update>
            				<user clientid="" username="user_name" password="mypassword" />
            				<reports>
            					<report tag="NEW">
                					<field>
                					   <field id="1" name="REFERENCE_ID" value="[your-name]" />
                				       <field id="2" name="DESCRIPTION" value="[your-email]" />
                					</field>
                                </report>
            				</reports>
            			</update>';

        $json_example = '{ "name":"[fullname]", "age":30, "car":null }';

        $args = array_merge( array(
                array(
                    'name' => 'base_url',
                    'type' => 'url',
                    'label' => __( 'Base API Url', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '',
                    'value' => $this->get('base_url'),
                ),
                array(
                    'name' => 'input_type',
                    'type' => 'select',
                    'label' => __( 'Api Input type', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '',
                    'value' => $this->get('input_type'),
                    'options' => array(
                        'post' => __('POST', 'wpcf7-redirect'),
                        'get' => __('GET', 'wpcf7-redirect'),
                    ),
                ),
                array(
                    'name' => 'record_type',
                    'type' => 'select',
                    'label' => __( 'Record type', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '',
                    'value' => $this->get('record_type'),
                    'options' => array(
                        'xml' => __('XML', 'wpcf7-redirect'),
                        'json' => __('Json', 'wpcf7-redirect'),
                    ),
                ),
                array(
                    'name' => 'request_template',
                    'type' => 'json',
                    'label' => __( 'Xml/Json template', 'wpcf7-redirect' ),
                    'sub_title' => '<div class="wpcf7-redirect-butify-wrap"><a href="#" class="wpcf7-redirect-butify">'.__('Validate Json/Xml' ,'wpcf7-redirect').'</a></div>',
					'footer' => '<div>You can use the following tags: '.$this->get_formatted_mail_tags().'</div>',
                    'placeholder' => '',
                    'show_selector' => '',
                    'value' => $this->get('request_template'),
                ),
                array(
                    'name' => 'json_xml_notice',
                    'type' => 'notice',
                    'label' => __( '<strong>Notice!</strong>', 'wpcf7-redirect' ),
                    'sub_title' => __( 'Make sure all your tags are correctly closed.', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'class' => 'field-notice-alert',
                    'show_selector' => '',
                ),
                array(
                    'name' => 'tags_map_mapping_section',
                    'type' => 'section',
                    'title' => __( 'Tags mapping(Optional)', 'wpcf7-redirect' ),
                    'sub_title' => __( '', 'wpcf7-redirect' ),
                    'fields' => array(
                        array(
                            'name' => 'tags_map',
                            'type' => 'tags_map',
                            'label' => '',
                            'sub_title' => '',
                            'placeholder' => '',
                            'show_selector' => '',
                            'class' => 'hide_api_key',
                            'value' => maybe_unserialize( $this->get('tags_map') ),
                            'tags_functions' => maybe_unserialize( $this->get('tags_functions') ),
                            'tags_defaults' => maybe_unserialize( $this->get('tags_defaults') ),
                            'tags' => WPCF7R_Form::get_mail_tags()
                        ),
                    )
                ),
                array(
                    'name' => 'api_headers_section',
                    'type' => 'section',
                    'title' => __( 'Headers(Optional)', 'wpcf7-redirect' ),
                    'fields' => array(
                        array(
                            'name' => 'api_headers',
                            'type' => 'repeater',
                            'label' => '',
                            'sub_title' => __( 'Use this to send custom headers.', 'wpcf7-redirect' ),
                            'placeholder' => '',
                            'show_selector' => '',
                            'value'  => maybe_unserialize( $this->get('api_headers') ),
                            'fields' => array(
                                array(
                                    'name' => 'header_key',
                                    'type' => 'text',
                                    'label' => __( 'Header Key', 'wpcf7-redirect' ),
                                    'sub_title' => '',
                                    'placeholder' => '',
                                    'show_selector' => '',
                                    'class' => '',
                                    'value' => '',
                                ),
                                array(
                                    'name' => 'header_value',
                                    'type' => 'text',
                                    'label' => __( 'Header value', 'wpcf7-redirect' ),
                                    'sub_title' => '',
                                    'placeholder' => '',
                                    'show_selector' => '',
                                    'class' => '',
                                    'value' => '',
                                ),
                            )
                        ),
                    )
            ),
            array(
                'name' => 'test_section',
                'type' => 'section',
                'title' => __( 'Test', 'wpcf7-redirect' ),
                'sub_title' => __( '<span class="dashicons dashicons-warning"></span> Before testing dont forget to save your changes', 'wpcf7-redirect' ),
                'fields' => array(
                    array(
                        'name' => 'test_tags_map',
                        'type' => 'tags_map',
                        'label' => '',
                        'sub_title' => '',
                        'placeholder' => '',
                        'show_selector' => '',
                        'value' => maybe_unserialize( $this->get('tags_map') ),
                        'tags_functions' => maybe_unserialize( $this->get('tags_functions') ),
                        'tags_defaults' => maybe_unserialize( $this->get('tags_defaults') ),
                        'defaults_name' => 'test_values',
                        'tags' => WPCF7R_Form::get_mail_tags()
                    ),
					array(
						'name' => 'test_button',
						'type' => 'button',
						'label' => __( 'Test', 'wpcf7-redirect' ),
						'placeholder' => '',
						'show_selector' => '.field-wrap-api_debug_url',
						'value' => '',
						'class' => '',
						'attr' => array(
							'data-ruleid' => $this->get_rule_id(),
							'data-action_id' => $this->get_id(),
							'data-cf7_id' => $this->get_action_wpcf7_id(),
						)
					),
                )
            ),
            array(
                    'name' => 'xml_example',
                    'type' => 'notice',
                    'label' => __( '<strong>XML Example!</strong><br/>*** THIS IS AN EXAMPLE ** USE YOUR JSON ACCORDING TO YOUR API DOCUMENTATION **<br/>', 'wpcf7-redirect' ),
                    'sub_title' => esc_html($xml_example),
                    'placeholder' => '',
                    'class' => 'field-notice',
                    'show_selector' => '',
                ),
                array(
                    'name' => 'json_example',
                    'type' => 'notice',
                    'label' => __( '<strong>JSON Example!</strong><br/>*** THIS IS AN EXAMPLE ** USE YOUR XML ACCORDING TO YOUR API DOCUMENTATION **<br/>', 'wpcf7-redirect' ),
                    'sub_title' => esc_html($json_example),
                    'placeholder' => '',
                    'class' => 'field-notice',
                    'show_selector' => '',
                ),
                array(
                    'name' => 'show_debug',
                    'type' => 'checkbox',
                    'label' => __( 'Show debug log', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '.field-wrap-api_debug_url',
                    'value' => '',
                    'class' => ''
                ),
                array(
                    'name' => 'api_debug_url',
                    'type' => 'debug_log',
                    'class' => 'field-hidden',
                    'label' => __( 'Debug log', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '',
                    'fields' => array(
                        __('Debug Url', 'wpcf7-redirect' ) => $this->get('api_debug_url'),
                        __('Sent Parameters', 'wpcf7-redirect' ) => $this->get('api_debug_params'),
                        __('Api Results', 'wpcf7-redirect' ) => $this->get('api_debug_result'),
                    )
                ),

            ),
            parent::get_default_fields()
        );
        return $args;
    }
    /**
     * Get settings page
     * @return [type] [description]
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
     * @version 1.1.7
     */
    public function process( $submission ){

        $tags = array();

        foreach( WPCF7R_Form::get_mail_tags() as $tag ){
            if( is_array( $tag->raw_values ) && $tag->raw_values ){
                $tags[$tag->name] = $tag->raw_values;
            }else{
                $tags[$tag->name] = '';
            }
        }

        $headers = maybe_unserialize( $this->get('api_headers') );

        $key_value_headers = array();

        if( $headers ){
            foreach( $headers as $header ){
                $key_value_headers[$header['header_key']] = $header['header_value'];
            }
        }

        $args = array(
            'base_url' => $this->get('base_url'),
            'headers' => maybe_unserialize( $this->get('api_headers') ),
            'record_type' => $this->get('record_type'),
            'input_type' => $this->get('input_type'),
            'tags' => $tags,
            'submission' => $submission,
            'request_template' => $this->get('request_template'),
            'api_headers' => $key_value_headers,
            'tags_functions' => maybe_unserialize( $this->get('tags_functions') ),
            'tags_defaults' => maybe_unserialize( $this->get('tags_defaults') ),
        );

        $args = apply_filters('process_api_xml_json' , $args );

        $api_result = $this->api_base->qs_cf7_send_data_to_api($args);

        return $api_result;
    }

}
