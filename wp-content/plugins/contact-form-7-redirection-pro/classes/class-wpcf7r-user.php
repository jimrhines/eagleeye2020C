<?php
/**
 * Class WPCF7R_user file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7R_user
 * A Parent class that handles all redirect actions
 *
 * @version  1.0.0
 */

class WPCF7R_User{
    /**
     * Class constructor
     * Set required parameters
     * @param string $post [description]
     *
     * @version  1.0.0
     */
    public function __construct(){
        add_action( 'show_user_profile', array( $this , 'additional_profile_fields' ) );
        add_action( 'edit_user_profile', array( $this , 'additional_profile_fields' ) );


        add_action( 'personal_options_update', array( $this , 'save_custom_user_fields' ) );
        add_action( 'edit_user_profile_update', array( $this , 'save_custom_user_fields' ) );

    }

    private function get_all_forms_fields(){
        $this->fields = array();

        $cf7Forms = $this->get_cf7_forms();

        if( $cf7Forms ){
            foreach( $cf7Forms as $cf7Form ){
                $wpcf7r_form = get_cf7r_form( $cf7Form->ID );

                $actions = $wpcf7r_form->get_actions('default');

                foreach( $actions as $action ){
                    $action_type = $action->get('action_type');
                    if( $action_type === 'register' ){

                        $fields_mapping = maybe_unserialize($action->get('user_fields'));

                        if( $fields_mapping ){
                            $this->fields = array_merge( $fields_mapping , $this->fields );
                        }
                    }
                }
            }
        }

        return $this->fields;
    }

    private function get_cf7_forms(){
        $args = array(
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1
        );

        $cf7Forms = get_posts( $args );

        return $cf7Forms;
    }

    /**
     * Add new fields above 'Update' button.
     *
     * @param WP_User $user User object.
     */
    public function save_custom_user_fields( $user_id ) {

        if ( ! current_user_can( 'edit_user', $user_id ) ) {
       	 return false;
        }

        $fields = $this->get_all_forms_fields();

        if( ! $fields ){
            return;
        }

        foreach( $fields as $field ){
            $value = isset( $_POST[$field['user_field_key']] ) ? $_POST[$field['user_field_key']] : '';
            update_usermeta( $user_id, $field['user_field_key'] , $value );
        }
    }

    /**
     * Add new fields above 'Update' button.
     *
     * @param WP_User $user User object.
     */
    public function additional_profile_fields( $user ) {

        $fields = $this->get_all_forms_fields();

        if( ! $fields ){
            return;
        }
        ?>
        <h3><?php _e('Extra profile information' , 'wpcf7-redirect' );?></h3>

        <table class="form-table">
            <?php foreach( $fields as $field ):?>
                <?php $value = get_user_meta( $user->ID , $field['user_field_key'], true ); ?>
                <tr>
                    <th>
                        <label for="<?php echo $field['user_field_key'];?>"><?php echo $field['user_field_key'];?></label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo $field['user_field_key'];?>" value="<?php echo $value;?>">
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
        <?php
    }

}
