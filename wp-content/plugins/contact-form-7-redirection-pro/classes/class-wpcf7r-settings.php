<?php
/**
 * Class WPCF7_Redirect_Settings file.
 *
 * @package cf7r
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact form 7 redirect settings panel
 *
 * @version  1.0.0
 */
class WPCF7_Redirect_Settings{

    public $product_url = WPCF7_PRO_REDIRECT_PLUGIN_PAGE_URL;

    public function __construct(){

        $this->page_slug = 'wpc7_redirect';

        $this->api = new wpcf7r_qs_api();

        add_action( 'admin_menu', array( $this , 'create_plugin_settings_page') );
        add_action( 'admin_init', array( $this , 'wpcf7r_register_options') );
        add_filter( 'plugin_row_meta', array( $this , 'register_plugin_links') , 10, 2);


        if( isset( $_GET['wpcf7r_deactivate_license'] ) ){
            add_action( 'admin_init', array( $this , 'deactivate_license') );
        }
    }

    /**
     * Deactivate the license
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function deactivate_license(){
        $serial = WPCF7_Redirect_Utilities::get_serial_key();

        $activation_id = WPCF7_Redirect_Utilities::get_activation_id();

        $this->api->deactivate_liscense( $activation_id , $serial );

        $this->reset_activation();

        wp_redirect( WPCF7_Redirect_Utilities::get_plugin_settings_page_url() );
    }

    /**
     * Register plugin options
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function wpcf7r_register_options(){
         add_settings_section( 'serial_section', __('License Information' , 'wpcf7-redirect' ) , array( $this, 'section_callback' ), $this->page_slug );

         $fields = array(
              array(
                  'uid' => 'wpcf7r_serial_number',
                  'label' => 'Serial Number',
                  'section' => 'serial_section',
                  'type' => 'text',
                  'options' => false,
                  'placeholder' => 'Type your serial here',
                  'helper' => '',
                  'supplemental' => 'This process will send your serial/domain to a 3rd party validation server to validate the key authenticity',
                  'default' => ''
              )
          );

          foreach( $fields as $field ){
              add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), $this->page_slug, $field['section'], $field );

              $args['sanitize_callback'] = array( $this , 'validate_serial_key' );

              register_setting( $this->page_slug, $field['uid'] , $args );
          }
    }

    /**
     * Validate serial key process
     * @param  [type] $serial [description]
     * @return [type]         [description]
     *
     * @version  1.0.0
     */
    public function validate_serial_key( $serial ){

        if( ! $serial ){
            return;
        }

        if( ! ( $activation_id = WPCF7_Redirect_Utilities::get_activation_id() ) ){
            $is_valid = $this->api->activate_serial( $serial );
        }else{
            $is_valid = $this->api->validate_serial( $activation_id , $serial );
        }

        //serial was not valid
        if( is_wp_error( $is_valid )){

            $message = $is_valid->get_error_message();

            if( is_object( $message ) && isset( $message->license_key ) ){
                $message = $message->license_key[0];
            }

            add_settings_error(
    			'wpcf7r_serial_number',
    			'not-valid-serial',
    			$message,
    			'error'
    		);

            $this->reset_activation();

            return false;
        }elseif( ! $activation_id ){
            //serial was valid, update the activation key for future validation
            $this->set_activation( $is_valid->data );
        }

        if( isset( $_GET['deactivate'] ) ){
            return '';
        }

        return $serial;
    }

    /**
     * Delete all activation data
     * Use in case activation validation or activation returns an error
     *
     * @version  1.0.0
     */
    public function reset_activation(){
        delete_option( 'wpcf7r_activation_id' );
        delete_option( 'wpcf7r_activation_expiration' );
        delete_option( 'wpcf7r_activation_data' );

        WPCF7_Redirect_Utilities::delete_serial_key();
    }

    /**
     * Set all data related with the plugin activation
     * @param [type] $validation_data [description]
     *
     * @version  1.0.0
     */
    public function set_activation( $validation_data ){
        update_option( 'wpcf7r_activation_id' , $validation_data->activation_id );
        update_option( 'wpcf7r_activation_expiration' , $validation_data->expire );
        update_option( 'wpcf7r_activation_data' , $validation_data );

    }

    /**
     * A function for displaying a field on the admin settings page
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     *
     * @version  1.0.0
     */
    public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
        if( ! $value ) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }

        // Check which type of field we want
        switch( $arguments['type'] ){
            case 'text': // If it is a text field
            case 'password':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="widefat" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
        }

        // If there is help text
        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper ); // Show it
        }

        // If there is supplemental text
        if( $supplimental = $arguments['supplemental'] ){
            printf( '<p class="description">%s</p>', $supplimental ); // Show it
        }
    }

    /**
     * Main call for creating the settings page
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function create_plugin_settings_page(){
        // Add the menu item and page
        $page_title = 'WPCF7 redirect';
        $menu_title = 'WPCF7 redirect';
        $capability = 'manage_options';
        $callback = array( $this, 'plugin_settings_page_content' );
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        add_options_page(
          __( $page_title , 'wpcf7-redirect'),
          __( $page_title , 'wpcf7-redirect'),
          $capability,
          $this->page_slug,
          $callback
        );
    }

    /**
     * The setting page template HTML
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function plugin_settings_page_content() {
        ?>
        <h2>
            <span>
                <?php _e( 'WPCF7 redirect' , 'wpcf7-redirect' );?>
            </span>
        </h2>
        <div class="wrap wrap-wpcf7redirect">
            <div class="postbox">
                <div class="padbox">
                    <form method="POST" action="options.php" name="wpcfr7_settings">
                        <?php
                            do_action('before_settings_fields');

                            settings_fields( $this->page_slug );
                            do_settings_sections( $this->page_slug );

                            submit_button();
                        ?>
                    </form>
                    <div class="license-data">
                        <?php if( $activation_id = WPCF7_Redirect_Utilities::get_activation_id() ):?>
                            <div class="license-data-row">
                                <?php _e('Activation id:' , 'wpcf7-redirect' );?> <?php echo $activation_id; ?>
                            </div>
                        <?php endif;?>
                        <?php if( $activation_expiration = WPCF7_Redirect_Utilities::get_activation_expiration() ):?>
                            <div class="license-data-row">
                                <?php _e('Activation Expiration date:' , 'wpcf7-redirect' );?> <?php echo date_i18n( get_option( 'date_format' ) , $activation_expiration ); ?>
                            </div>
                        <?php endif;?>
                        <?php if( $activation_data = WPCF7_Redirect_Utilities::get_activation_data() ):?>
                            <div class="license-data-row">
                                <a href="<?php echo $activation_data->url; ?>" target="_blank">
                                    <?php _e('View your license details' , 'wpcf7-redirect' );?>
                                </a>
                            </div>
                        <?php endif;?>
                        <?php if( $activation_id ):?>
                            <?php $url = WPCF7_Redirect_Utilities::get_deactivation_link(); ?>
                            <div class="license-data-row">
                                <a href="<?php echo $url;?>">
                                    <?php _e('Deactivate license' , 'wpcf7-redirect' );?>
                                </a>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div> <?php
    }

    /**
     * Create a section on the admin settings page
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     *
     * @version  1.0.0
     */
    public function section_callback( $arguments ) {
        switch( $arguments['id'] ){
            case 'serial_section':
                echo sprintf( "In order to gain access to plugin updates, please enter your license key below. If you don't have a licence key, please <a href='%s' target='_blank'>click Here</a>." , $this->product_url );
                break;
        }
    }

    /**
     * Add a link to the options page to the plugin description block.
     *
     * @version  1.0.0
     */
    function register_plugin_links( $links, $file ) {
      if ( $file == WPCF7_PRO_REDIRECT_BASE_NAME ) {
         $links[] = WPCF7_Redirect_Utilities::get_settings_link();
      }

      return $links;
    }
}
