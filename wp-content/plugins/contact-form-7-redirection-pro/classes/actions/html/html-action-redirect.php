<?php
/**
 * html-action-redirect.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Html itterator to display redirect actions fields.
 *
 * @version  1.0.0
 */

foreach( $this->get_action_fields() as $field ):
 $this->render_field( $field , $prefix );
endforeach;
