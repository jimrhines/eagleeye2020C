<?php
/**
 * Class wpcf7r_qs_api file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class wpcf7r_qs_api
 * A class used for contactinf Query solutions API
 *
 * @version  1.0.0
 */
class wpcf7r_qs_api{
    public function __construct(){
        $this->activation_url = WPCF7_PRO_REDIRECT_PLUGIN_ACTIVATION_URL;
        $this->api_url = WPCF7_PRO_REDIRECT_PLUGIN_UPDATES;
        $this->store_id = WPCF7_PRO_REDIRECT_PLUGIN_ID;
        $this->sku = WPCF7_PRO_REDIRECT_PLUGIN_SKU;
    }
    /**
     * Make the API call
     * @param  [type] $url    [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     *
     * @version  1.0.0
     */
    public function api_call( $url , $params ){

        $args = array(
            'timeout'     => 20,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'cookies'     => array(),
            'body'        => $params,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => true,
            'stream'      => false,
            'filename'    => null
        );

        $results = wp_remote_post( $url , $args );


        if( ! is_wp_error( $results ) ){
            $body = wp_remote_retrieve_body($results);

            $results = json_decode( $body );

            if( ! isset( $results->error ) ){
                return new WP_Error( $params['action'] , 'Unknow error' );
            }

            if( $results->error ){
                return new WP_Error( $params['action'] , $results->errors );
            }

            if( isset( $results->data )){
                $results->data->downloadable = "";
            }
        }

        return $results;
    }
    /**
     * Get the domain where the plugin is intalled for authentication
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_current_domain(){
        return isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
    }

    /**
     * Check if the serial is valid
     * @param  [type] $activation_id [description]
     * @param  [type] $serial        [description]
     * @return [type]                [description]
     *
     * @version  1.0.0
     */
    public function validate_serial( $activation_id , $serial ){
        $params = array(
            'action'        => 'license_key_validate',
            'store_code'    => $this->store_id,
            'license_key'   => $serial,
            'domain'        => $this->get_current_domain(),
            'sku'           => $this->sku,
            'activation_id' => $activation_id
        );

        $results = $this->api_call( $this->activation_url , $params );

        return  $results;
    }

    /**
     * Deactivate the specific activation serial
     * @param  [type] $activation_id [description]
     * @param  [type] $serial        [description]
     * @return [type]                [description]
     *
     * @version  1.0.0
     */
    public function deactivate_liscense( $activation_id , $serial ){
        $params = array(
            'action'      => 'license_key_deactivate',
            'store_code'  => $this->store_id,
            'license_key' => $serial,
            'domain'      => $this->get_current_domain(),
            'sku'         => $this->sku,
            'activation_id' => $activation_id
        );

        $results = $this->api_call( $this->activation_url , $params );

        return $results;
    }

    /**
     * Activate the serial
     * @param  [type] $serial [description]
     * @return [type]         [description]
     *
     * @version  1.0.0
     */
    public function activate_serial( $serial ){
        $params = array(
            'action'      => 'license_key_activate',
            'store_code'  => $this->store_id,
            'license_key' => $serial,
            'domain'      => $this->get_current_domain(),
            'sku'         => $this->sku
        );

        $results = $this->api_call( $this->activation_url , $params );

        if( $this->is_valid_response( $results ) ){
            return $results;
        }

        return $results;
    }

    /**
     * Check if the API returned a vlid response
     * @param  [type]  $results [description]
     * @return boolean          [description]
     *
     * @version  1.0.0
     */
    public function is_valid_response( $results ){
        if( ! is_wp_error( $results ) ){

            if( is_object( $results ) ){
                if( isset( $results->error ) && ! $results->error ){
                    return true;
                }
            }else{
                if( isset( $results['error'] ) && ! $results['error'] ){
                    return true;
                }
            }

        }

        return false;
    }
}
