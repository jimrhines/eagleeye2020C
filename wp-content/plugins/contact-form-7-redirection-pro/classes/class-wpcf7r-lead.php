<?php
/**
 * Class WPCF7R_Lead file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7R_Lead
 * A Container class that handles lead
 *
 * @version  1.4.0
 */
class WPCF7R_Lead{
    /**
     * Create an instance
     * Save the post id and post reference
     * @param string $post_id [description]
     */
    public function __construct( $post_id = "" ){
        if( is_object( $post_id ) ){
            $this->post_id = $post_id->ID;
            $this->post = $post_id;
        }else{
            $this->post_id = $post_id;
            $this->post = $post_id;
        }
    }

    /**
     * Update submitted form data
     * @param  array  $args [description]
     * @return [type]       [description]
     */
    public function update_lead_data( $args = array() ){
        if( $args ){
            foreach( $args as $meta_key => $meta_value ){
                update_post_meta( $this->post_id , $meta_key , $meta_value );
            }
        }
    }
    /**
     * Return the lead ID
     * @return [type] [description]
     */
    public function get_id(){
        return $this->post_id;
    }

    /**
     * Update the type of lead
     * @param  [type] $lead_type [description]
     * @return [type]            [description]
     */
    public function update_lead_type( $lead_type ){
        update_post_meta( $this->post_id , 'lead_type' , $lead_type );
    }

    /**
     * Save the action reference and results
     * @param [type] $action_id      [description]
     * @param [type] $action_type    [description]
     * @param [type] $action_results [description]
     */
    public function add_action_debug( $action_id , $action_type , $action_results ){
        $action_details = array(
            'action_id' => $action_id,
            'results'   =>$action_results
        );

        add_post_meta( $this->post_id , $action_type , $action_details );
    }

    /**
     * Get the creation post of the lead
     * @return [type] [description]
     */
    public function get_date(){
        return get_the_date( get_option('date_format'), $this->post_id );
    }

    /**
     * Get the creation post of the lead
     * @return [type] [description]
     */
    public function get_time(){
        return get_the_date( get_option('time_format'), $this->post_id );
    }
    /**
     * Get the lead type
     * @return [type] [description]
     */
    public function get_lead_type(){
        return get_post_meta( $this->post_id , 'lead_type' , true);
    }

    /**
     * Get lead fields
     * @return [type] [description]
     */
    public function get_lead_fields(){
        $fields = get_post_custom( $this->post_id );

        $lead_fields = array();

        foreach( $fields as $field_key => $field_value ){
            $value = maybe_unserialize($field_value[0]);

            if( is_array( $value ) ){
                foreach( $value as $value_field_key => $value_field_value ){
                    $lead_fields[$field_key."-".$value_field_key] = array(
                        'type' => 'text',
                        'placeholder' => '',
                        'label' => $field_key,
                        'name' => $field_key,
                        'value' => maybe_unserialize($value_field_value),
                        'prefix' => $field_key
                    );
                }
            }else{
                $lead_fields[$field_key] = array(
                    'type' => 'text',
                    'placeholder' => '',
                    'value' => $value,
                    'label' => $field_key,
                    'name' => $field_key,
                    'prefix' => $field_key
                );
            }
        }

        return $lead_fields;
    }
}
