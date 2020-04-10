<?php
/**
 * html-action-send-to-email file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * send to mail action html fields
 *
 * @version  1.0.0
 */

foreach( $this->get_action_fields() as $field ):
    $this->render_field( $field , $prefix );
endforeach;
