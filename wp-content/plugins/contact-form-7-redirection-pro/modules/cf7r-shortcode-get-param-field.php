<?php
/**
** Get params from the redirect page
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_shortcode( 'get_param' , 'wpcf7r_get_param' );

/**
 * This function will collect the data from the query string by parameter
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function wpcf7r_get_param( $atts ){
    $atts = shortcode_atts(
		array(
			'param' => '',
		), $atts, 'wpcf7-redirect' );
		
	$param = "";

    if( isset( $_GET[$atts['param']] ) && $_GET[$atts['param']] ){
        $param = esc_attr( wp_kses( $_GET[$atts['param']] , array('') ) );
    }

	return $param;
}
