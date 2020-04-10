<?php
/**
 * Class WPCF7R_Action_save_lead file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

register_wpcf7r_actions(
    'save_lead',
    __('Save lead' , 'wpcf7r-redirect'),
    'WPCF7R_Action_save_lead'
);

/**
 * Class WPCF7R_Action_save_lead
 * A Class that handles send send to api process
 *
 * @version  1.0.0
 */
class WPCF7R_Action_save_lead extends WPCF7R_Action{

    /**
     * Init the parent action class
     * @param [type] $post [description]
     *
     * @version  1.0.0
     */
    public function __construct($post){
        parent::__construct($post);

        $this->priority = 1;
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

        return array_merge( array(
                // array(
                //     'name' => 'tags_map_mapping_section',
                //     'type' => 'section',
                //     'title' => __( 'Tags mapping(Optional)', 'wpcf7-redirect' ),
                //     'sub_title' => __( '', 'wpcf7-redirect' ),
                //     'fields' => array(
                //         array(
                //             'name' => 'tags_map',
                //             'type' => 'tags_map',
                //             'label' => '',
                //             'sub_title' => '',
                //             'placeholder' => '',
                //             'show_selector' => '',
                //             'class' => 'hide_api_key',
                //             'value' => maybe_unserialize( $this->get('tags_map') ),
                //             'tags_defaults' => maybe_unserialize( $this->get('tags_defaults') ),
                //             'tags' => WPCF7R_Form::get_mail_tags()
                //         ),
                //     )
                // ),
                'action_status' => array(
                    'name' => 'action_status',
                    'type' => 'checkbox',
                    'label' => $this->get_action_status_label(),
                    'sub_title' => 'if this is off the rule will not be applied',
                    'placeholder' => '',
                    'show_selector' => '',
                    'toggle-label' => json_encode(
                        array(
                            '.field-wrap-action_status .checkbox-label,.column-status a' => array(
                                __( 'Enabled', 'wpcf7-redirect' ) ,
                                __( 'Disabled', 'wpcf7-redirect' )
                             )
                        )
                    ),
                    'value' => $this->get('action_status')
                ),
            ),
            $parent_fields
        );
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
     */
    public function process( $submission ){
        $contact_form = $submission->get_contact_form();
        //insert the lead to the DB
        $lead = WPCF7R_Leads_Manger::insert_lead( $contact_form->id() , $submission->get_posted_data() , 'contact' );

        self::set_lead_id( $lead->post_id );

        return $response = array(
            'type' => 'save_lead',
            'data' => array(
                'lead_id' => $lead->post_id
            )
        );;
    }

}
