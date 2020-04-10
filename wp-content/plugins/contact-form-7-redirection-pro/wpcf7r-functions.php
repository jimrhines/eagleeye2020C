<?php
/**
 * wpcf7r-functions.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Get an array and transform it to html attributes
 * @param  [type] $attributes [description]
 * @return [type]             [description]
 */
function wpcf7r_implode_attributes( $attributes ){
    $result = join(' ', array_map(function($key) use ($attributes) {
       if(is_bool($attributes[$key]))
       {
          return $attributes[$key]?$key:'';
       }
       return $key.'="'.$attributes[$key].'"';
    }, array_keys($attributes)));

    return $result;
}

/**
 * Send emails as HTML
 */
function wpcf7r_send_emails_as_html(){
    return "text/html";
}
/**
 * Helper function
 * @return [type] [description]
 *
 * @version  1.4
 */
function register_wpcf7r_actions( $name , $title , $class ){
    WPCF7_Redirect_Utilities::register_wpcf7r_actions( $name , $title , $class );
}

/**
 * Get a list of available actions
 * @return [type] [description]
 *
 * @version  1.4
 */
function wpcf7r_get_available_actions(){
    $actions = WPCF7_Redirect_Utilities::get_wpcf7r_actions();

    ksort($actions);

	return apply_filters('wpcf7r-get_available_actions' , $actions );
}

/**
 * Get an instance of contact form 7 redirect form
 * @param  [type] $form_id [description]
 * @return [type]          [description]
 *
 * @version  1.0.0
 */
function get_cf7r_form( $form_id , $submission = "" , $validation_obj = "" ){
	return new WPCF7R_Form( $form_id , $submission , $validation_obj );
}

/**
 * Create HTML tooltip
 * @param  [type] $tip [description]
 * @return [type]      [description]
 */
function cf7r_tooltip( $tip ){
    $tip = esc_attr( $tip );

    return '<i class="dashicons dashicons-editor-help qs-tooltip"><span class="qs-tooltip-inner">'.$tip.'</span></i>';
}
/**
 * Get plugin base url
 * @return [type] [description]
 *
 * @version  1.0.0
 */
function wpcf7r_get_redirect_plugin_url(){
    return WPCF7_PRO_REDIRECT_BASE_URL;
}

/**
 * Get the value of a single block field
 * @param  [type] $key       [description]
 * @param  [type] $block_key [description]
 * @param  [type] $fields    [description]
 * @return [type]            [description]
 *
 * @version  1.0.0
 */
function block_field_value( $key , $block_key, $fields ){
    return isset( $fields['blocks'][$block_key][$key] ) ? $fields['blocks'][$block_key][$key] : '';
}

/**
 * A notice to remove the free plugin
 * @return [type] [description]
 *
 * @version  1.0.0
 */
function wpcf7_remove_old_plugin_notice(){
    ?>

    <div class="wpcf7-redirect-error error notice">
        <h3>
            <?php esc_html_e( 'Contact Form Redirection', 'wpcf7-redirect' );?>
        </h3>
        <p>
            <?php esc_html_e( 'Error: It is recommended to deactivate and remove Contact form 7 redirection plugin for the PRO version to work.', 'wpcf7-redirect' );?>
        </p>
    </div>

    <?php
}

/**
 * A notice to remove the free plugin
 * @return [type] [description]
 *
 * @version  1.0.0
 */
function wpcf7_remove_contact_form_7_to_api(){
    ?>

    <div class="wpcf7-redirect-error error notice">
        <h3>
            <?php esc_html_e( 'Contact Form Redirection', 'wpcf7-redirect' );?>
        </h3>
        <p>
            <?php esc_html_e( 'Error: It is recommended to deactivate and remove Contact form 7 To APi plugin.', 'wpcf7-redirect' );?>
        </p>
    </div>

    <?php
}

function wpcf7_get_cf7_ver(){
    if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
        $wpcf7_path = WPCF7_PRO_REDIRECT_PLUGINS_PATH . 'contact-form-7/wp-contact-form-7.php';
        $wpcf7_data = get_plugin_data( $wpcf7_path, false, false );

        return $wpcf7_data['Version'];
    }

    return false;
}
/**
 * Show admin notices
 * @return [type] [description]
 *
 * @version  1.0.0
 */
function wpcf7r_admin_notice() {
    if ( $ver = wpcf7_get_cf7_ver() ) {
        // If CF7 version is < 4.8.
        if ( $ver < 4.8 ) {
            ?>

            <div class="wpcf7-redirect-error error notice">
                <h3>
                    <?php esc_html_e( 'Contact Form Redirection', 'wpcf7-redirect' );?>
                </h3>
                <p>
                    <?php esc_html_e( 'Error: Contact Form 7 version is too old. Contact Form Redirection is compatible from version 4.8 and above. Please update Contact Form 7.', 'wpcf7-redirect' );?>
                </p>
            </div>

            <?php
        }
    } else {
        // If CF7 isn't installed and activated, throw an error.
        ?>
        <div class="wpcf7-redirect-error error notice">
            <h3>
                <?php esc_html_e( 'Contact Form Redirection', 'wpcf7-redirect' );?>
            </h3>
            <p>
                <?php esc_html_e( 'Error: Please install and activate Contact Form 7.', 'wpcf7-redirect' );?>
            </p>
        </div>

        <?php
    }
}

add_shortcode( 'qs_date' , 'qs_date' );

/**
 * Shortcode for creating date
 * @param  [type] $atts [description]
 * @return [type]       [description]
 */
function qs_date( $atts ){
    $atts = shortcode_atts(
        array(
            'format' => 'Ydm',
        ), $atts, 'wpcf7-redirect' );

    return date( $atts['format'] , time() );
}

function is_wpcf7r_debug(){
    return defined('CF7_REDIRECT_DEBUG') && CF7_REDIRECT_DEBUG ? true : false;
}
