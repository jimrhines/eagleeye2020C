<?php
/**
 * Class WPCF7R_Action_Mailchimp file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

register_wpcf7r_actions(
    'mailchimp',
    __('Mailchimp' , 'wpcf7r-redirect'),
    'WPCF7R_Action_Mailchimp'
);
/**
* Class WPCF7R_Action_Mailchimp
* A Class that handles redirect actions
*
* @version  1.0.0
*/

class WPCF7R_Action_Mailchimp extends WPCF7R_Action{

    /**
     * Init the parent action class
     * @param [type] $post [description]
     *
     * @version  1.0.0
     */
    public function __construct($post){
        parent::__construct($post);

    }

    /**
     * Get the action admin fields
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_fields(){

        $parent_fields = parent::get_default_fields();

        unset( $parent_fields['action_status'] );

        $tags_optional = $this->get_tags_optional();

        $lists_json = esc_attr(json_encode(maybe_unserialize($this->get('mailchimp_lists'))));

        return array_merge( array(
                'mailchimp_api_key' => array(
                    'name' => 'mailchimp_api_key',
                    'type' => 'text',
                    'label' => __( 'Mailchimp API Key', 'wpcf7-redirect' ),
                    'placeholder' => __( '', 'wpcf7-redirect' ),
                    'value' => $this->get('mailchimp_api_key'),
                    'input_attr' => ' required ',
                    'footer' => __( '<small><a href="https://mailchimp.com/help/about-api-keys/" target="_blank">Get help here</a></small>', 'wpcf7-redirect' ),
                    'class' => 'qs-col qs-col-12'
                ),
                'mailchimp_list_id' => array(
                    'name' => 'mailchimp_list_id',
                    'type' => 'select',
                    'label' => __( 'Mailchimp list', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'value' => $this->get('mailchimp_list_id'),
                    'options' => $this->get_lists() ? $this->get_lists() : array( '' => __( 'Click the get lists button', 'wpcf7-redirect' ) ),
                    'input_attr' => ' required ',
                    'footer' => __( '<small><a href="https://mailchimp.com/help/about-api-keys/" target="_blank">Get help here</a></small>', 'wpcf7-redirect' ),
                    'class' => 'qs-col qs-col-6'
                ),
                'get_mailchimp_lists' => array(
                    'name' => 'get_mailchimp_lists',
                    'type' => 'button',
                    'label' => __( 'Get lists', 'wpcf7-redirect' ),
                    'class' => 'qs-col qs-col-2'
                ),
                // 'create_list' => array(
                //     'name' => 'create_list',
                //     'type' => 'button',
                //     'label' => __('Create new list' ,'wpcf7-redirect' ),
                //     'class' => 'qs-col qs-col-2'
                // ),
                'email_address' => array(
                    'name' => 'email_address',
                    'type' => 'select',
                    'label' => __( 'Subscribers email tag', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'value' => $this->get('email_address'),
                    'options' => $this->get_mail_tags_array(),
                    'input_attr' => ' required',
                    'footer' => __( '<small>The tag that is used to collect the user email</small>', 'wpcf7-redirect' ),
                    'class' => 'qs-col qs-col-6 clear'
                ),
                'mailchimp_acceptance' => array(
                    'name' => 'mailchimp_acceptance',
                    'type' => 'select',
                    'label' => __( 'Acceptance tag', 'wpcf7-redirect' ),
                    'placeholder' => '',
                    'value' => $this->get('mailchimp_acceptance'),
                    'options' => $tags_optional,
                    'input_attr' => '',
                    'footer' => __( '<small>The tag/tags that holds the user acceptance (checkbox)</small>', 'wpcf7-redirect' ). '<div>'.$this->get_formatted_mail_tags().'</div><div class="wpcf7-redirect-butify-wrap"></div>',
                    'class' => 'qs-col qs-col-6'
                ),
                'mailchimp_settings' => array(
                    'name' => 'mailchimp_settings',
                    'type' => 'section',
                    'title' => __( 'Merge tags (Optional)', 'wpcf7-redirect' ),
                    'attr' => 'data-lists="'.$lists_json.'"',
                    'fields' => array(
                        array(
                            'name' => 'merge_tags',
                            'type' => 'repeater',
                            'label' => '',
                            'sub_title' => __( 'Map mailchimp extra fields.', 'wpcf7-redirect' ),
                            'placeholder' => '',
                            'show_selector' => '',
                            'footer' => '',
                            'value'  => maybe_unserialize( $this->get('merge_tags') ),
                            'fields' => array(
                                'mailchimp_key' => array(
                                    'name' => 'mailchimp_key',
                                    'type' => 'select',
                                    'label' => __( 'Mailchimp key', 'wpcf7-redirect' ),
                                    'placeholder' => __('', 'wpcf7-redirect' ),
                                    'options' => $this->get_selected_list_fields()
                                ),
                                'form_key' => array(
                                    'name' => 'form_key',
                                    'type' => 'text',
                                    'label' => __( 'Form key', 'wpcf7-redirect' ),
                                    'placeholder' => __('[form-key]', 'wpcf7-redirect' ),
                                ),
                            )
                        )
                    ),
                    'footer' => __( '<small><a href="https://mailchimp.com/help/manage-list-and-signup-form-fields/#Add_and_Delete_Fields_in_the_List_Settings" target="_blank">What is this ?</a></small>' )
                ),
                'double_opt_in' => array(
                    'name' => 'double_opt_in',
                    'type' => 'checkbox',
                    'label' => __( 'Enable double opt-in', 'wpcf7-redirect' ),
                    'sub_title' => '',
                    'placeholder' => '',
                    'show_selector' => '.field-wrap-api_debug_url',
                    'value' => $this->get('double_opt_in'),
                    'class' => ''
                ),
            ),
            $parent_fields
        );
    }

    /**
     * Get the list f
     * @return [type] [description]
     */
    public function get_selected_list_fields(){
        $lists_raw = maybe_unserialize($this->get('mailchimp_lists'));

        $selected_list = $this->get('mailchimp_list_id');

        $fields = array();

        if( $lists_raw ){
            if( $selected_list && isset( $lists_raw[$selected_list] ) ){
                $fields = $lists_raw[$selected_list]['list_fields'];
            }else{
                $selected_lists = reset($lists_raw);
                $selected_list_settings = reset($lists_raw);
                $fields = $selected_list_settings['list_fields'];
            }
            if( $fields ){
                ksort($fields);
            }

            array_unshift( $fields, __('Select field') );

        }
        return $fields;

    }
    /**
     * Get the lists
     * @return [type] [description]
     */
    public function get_lists(){
        $lists_raw = $this->get('mailchimp_lists');

        $mailchimp_lists = WPCF7_Mailchimp_helper::get_lists_array($lists_raw);

        return $mailchimp_lists;
    }
    /**
     * Get an HTML of the
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_settings(){
        $this->get_settings_template('html-action-redirect.php');
    }

    /**
     * Handle a simple redirect rule
     * @param  [type] $rules    [description]
     * @param  [type] $response [description]
     * @return [type]           [description]
     *
     * @version  1.0.0
     */
    public function process( $submission ){
        $response = array();

        $this->posted_data = $submission->get_posted_data();

        $api_key = $this->get('mailchimp_api_key');
        $list_id = $this->get('mailchimp_list_id');

        $post_params['email_address'] = "[".$this->get('email_address')."]";
        $post_params['status'] = "[".$this->get('mailchimp_acceptance')."]";
        $post_params['double_opt_in'] = $this->get('double_opt_in') ? true : '';
        $post_params['email_type'] = 'html';

        $merge_tags = maybe_unserialize( $this->get('merge_tags') );

        foreach( $post_params as $arg_key => $arg_value ){
            $post_params[$arg_key] = $this->replace_tags($arg_value);
        }

        if( $merge_tags ){
            foreach( $merge_tags as $arg_key => $arg_value ){
                $arg_value['form_key'] = $this->replace_tags($arg_value['form_key']);

                $merge_tags[$arg_key] = $arg_value;

                $post_params['merge_fields'][$arg_value['mailchimp_key']] = $arg_value['form_key'];
            }
        }

        $post_params['status'] = $post_params['double_opt_in'] ? 'pending' : 'subscribed';

        $results = WPCF7_Mailchimp_helper::create_mailchimp_user( $list_id , $api_key , $post_params );

        return $results;
    }
}
