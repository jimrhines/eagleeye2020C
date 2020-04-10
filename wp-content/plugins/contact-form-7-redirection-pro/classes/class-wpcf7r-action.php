<?php
/**
 * Class WPCF7R_Action file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7R_Action
 * A Parent class that handles all redirect actions
 *
 * @version  1.0.0
 */

class WPCF7R_Action{
    //save a reference to the lead id in case the save lead action is on
    public static $lead_id;
    //saved data from validation action to submission action
    public static $data;
    /**
     * Class constructor
     * Set required parameters
     * @param string $post [description]
     *
     * @version  1.0.0
     */
    public function __construct( $post = "" ){

        $this->priority = 2;

        if( $post ){
            //save a refference to the action post
            $this->action_post = $post;
            //set the action post ID
            $this->action_post_id = $post->ID;
            //get the type of action
            $this->action = self::get_action_type( $this->action_post_id );
            //get tje contact form 7 post id
            $this->wpcf7_id = self::get_action_wpcf7_id( $this->action_post_id );
            //get tje status of the action (is it active or not)
            $this->action_status = self::get_action_status( $this->action_post_id );
            //get the custom action fields
            $this->fields_values = get_post_custom( $this->action_post_id );
        }
    }
    /**
     * Process validation action
     * This function will be called on validation hook
     * @return [type] [description]
     */
    public function process_validation( $submission ){

    }
    /**
     * Adds a blank select option for select fields
     * @return [type] [description]
     */
    public function get_tags_optional(){
        $tags = $this->get_mail_tags_array();

        $tags_optional = array_merge( array( __( 'Select', 'wpcf7-redirect' ) ) , $tags );

        return $tags_optional;
    }
    /**
     * save a reference to the lead id in case the save lead action is on
     * @param [type] $lead_id [description]
     */
    public function set_lead_id( $lead_id ){
        self::$lead_id = $lead_id;
    }

    public function get_available_user_roles(){
        return wp_roles()->get_names();
    }
    /**
     * Return the current lead id if it is availavle
     * @return [type] [description]
     */
    public static function get_lead_id(){
        return isset( self::$lead_id ) ? self::$lead_id : '';
    }

    /**
     * General function to retrieve meta
     * @param  [type] $key [description]
     * @return [type]      [description]
     *
     * @version  1.0.0
     */
    public function get($key){
        return isset( $this->fields_values[$key][0] ) ? $this->fields_values[$key][0] : '';
    }
    /**
     * Get the contact form 7 related post id
     * @return [type] [description]
     */
    public function get_cf7_post_id(){
        return isset( $this->wpcf7_id ) ? $this->wpcf7_id : '';
    }
    /**
     * Set action property
     * @param [type] $key   [description]
     * @param [type] $value [description]
     */
    public function set( $key , $value ){
        update_post_meta( $this->action_post_id , $key , $value );
        $this->fields_values[$key][0] = $value;

    }
    /**
     * Get a set of fields/specific field settings by key
     * @param  [type] $fields_key [description]
     * @return [type]             [description]
     */
    public function get_fields_settings( $fields_key ){
        $fields = $this->get_action_fields();

        return $fields[$fields_key];
    }
    /**
     * Get the id of the rule
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_rule_id(){
        return $this->get('wpcf7_rule_id');
    }

    /**
     * Get all fields values
     *
     * @param integer $post_id Form ID.
     * @return array of fields values keyed by fields name
     *
     * @version  1.0.0
     */
    public function get_fields_values() {
        $fields = $this->get_action_fields();

        foreach ( $fields as $field ) {
            $values[ $field['name'] ] = $this->get_field_value( $field );
        }

        return $values;
    }
    /**
     * Get mail tags objects
     * @return [type] [description]
     */
    public function get_mail_tags(){
        $mail_tags = WPCF7R_Form::get_mail_tags();

        return $mail_tags;
    }
    /**
     * Get mail tags objects
     * @return [type] [description]
     */
    public function get_mail_tags_array(){
        $mail_tags = WPCF7R_Form::get_mail_tags();

        $mail_tags_array = array();

        if( $mail_tags ){
            foreach( $mail_tags as $mail_tag ){
                $mail_tags_array[$mail_tag->name] = $mail_tag->name;
            }
        }

        return $mail_tags_array;
    }
    /**
     * Get mail tags to display on the settings panel
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_formatted_mail_tags( $clean = false ){

        $formatted_tags = array();

        foreach( WPCF7R_Form::get_mail_tags() as $mail_tag ){
            $formatted_tags[] = "<span class='mailtag code'>[{$mail_tag->name}]</span>";
        }

        foreach( WPCF7R_Form::get_special_mail_tags() as $mail_tag ){
            $formatted_tags[] = "<span class='mailtag code'>[".$mail_tag->field_name()."]</span>";
        }

        $formatted_tags = implode('' , $formatted_tags );

        if( $clean ){
            $formatted_tags = str_replace( array(']') , ', ' , $formatted_tags );
            $formatted_tags = str_replace( array('[') , '' , $formatted_tags );
        }

        ob_start();
        ?>
        <div class="mail-tags-wrapper">
            <div class="mail-tags-title" data-toggle=".mail-tags-wrapper-inner">
                <strong><?php _e('Available mail tags');?></strong> <span class="dashicons dashicons-arrow-down"></span>
            </div>
            <div class="mail-tags-wrapper-inner field-hidden">
                <?php echo $formatted_tags;?>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Replace lead id from the lead manager
     * @param  [type] $template [description]
     * @return [type]           [description]
     */
    public function replace_lead_id_tag( $template ){
        return str_replace('[lead_id]' , self::get_lead_id() , $template );
    }
    /**
     * Replace all mail tags in a string
     * @param  [type] $content [description]
     * @param  string $args    [description]
     * @return [type]          [description]
     *
     * @version  1.0.0
     */
    public function replace_tags( $content, $args = '' ){
		if ( true === $args ) {
			$args = array( 'html' => true );
		}

		$args = wp_parse_args( $args, array(
			'html' => false,
			'exclude_blank' => false,
		) );

		$replaced_tags = wpcf7_mail_replace_tags( $content, $args );

        $replaced_tags = do_shortcode( $replaced_tags );

        $replaced_tags = $this->replace_lead_id_tag( $replaced_tags );

        return $replaced_tags;
    }

    /**
     * Get the value of a specific field
     * @param  [type] $field [description]
     * @return [type]        [description]
     *
     * @version  1.0.0
     */
    public function get_field_value( $field ){
        if( is_array( $field ) ){
            return get_post_meta( $this->action_post_id, '_wpcf7_redirect_' . $field['name'] , true );
        }else{
            return get_post_meta( $this->action_post_id, '_wpcf7_redirect_' . $field , true );
        }
    }
    /**
     * Get an instance of the relevant action class
     * @param  [type] $post [description]
     * @return [type]       [description]
     *
     * @version  1.0.0
     */
    public static function get_action( $post ){
        if( is_int($post) ){
            $post = get_post($post);
        }

        $action_type = self::get_action_type( $post->ID );;

        $class = "WPCF7R_Action_{$action_type}";

        $action = "";

        if( class_exists( $class ) ){
            $action = new $class($post);
        }

        return $action;
    }

    /**
     * get the action post_id
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_id(){
        return $this->action_post_id;
    }

    /**
     * Get the type of the action
     * @param  [type] $post_id [description]
     * @return [type]          [description]
     *
     * @version  1.0.0
     */
    public static function get_action_type( $post_id ){
        return get_post_meta( $post_id , 'action_type' , true);
    }

    /**
     * Get action status
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_status(){
        return $this->get('action_status');
    }

    /**
     * Get action status
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_status_label(){
        return $this->get_action_status() == 'on' ? __('Enabled' ,'wpcf7-redirect') : __('Disabled' ,'wpcf7-redirect');
    }
    /**
     * Get contact form id
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_wpcf7_id(){
        return $this->get('wpcf7_id');
    }
    /**
     * Get the action title
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_title(){
        return $this->action_post->post_title;
    }

    /**
     * Get the action type
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_type(){
        return $this->action;
    }
    /**
     * Get the action preety name
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_type_label(){
        $actions = wpcf7r_get_available_actions();
        $type = $actions[$this->get_type()]['label'];

        return $type;
    }
    /**
     * Get the action status
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_status(){
        return $this->action_status;
    }

    /**
     * Get the action menu order
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_menu_order(){
        return $this->action_post->menu_order;
    }

    	/**
    	 * Get the tags used on the form
    	 * @return [type] [description]
    	 *
    	 * @version 1.5.4
    	 */
    	public function get_validation_mail_tags( $tag_name = "" ){
    		$tags = WPCF7R_Form::get_validation_obj_tags();

    		if( $tag_name ){
    			foreach( $tags as $tag ){
    				if( $tag->name == $tag_name ){
    					return $tag;
    				}
    			}
    		}else{
    			return $tags;
    		}

    	}
    /**
     * Get default actions field
     * This actions will apply for all child action classes
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    function get_default_fields(){
        return array(
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
            'conditional_logic' => array(
                'name' => 'conditional_logic',
                'type' => 'checkbox',
                'label' => __( 'Conditional Logic', 'wpcf7-redirect' ),
                'sub_title' => '',
                'placeholder' => '',
                'show_selector' => '.conditional-logic-blocks',
                'value' => $this->get('conditional_logic')
            ),
            'blocks'=> array(
                'name' => 'blocks',
                'type' => 'blocks',
                'has_conditional_logic' => $this->get('conditional_logic'),
                'blocks' => $this->get_conditional_blocks()
            ),
        );
    }

    /**
     * Reset all action fields
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function delete_all_fields(){
        $fields = $this->get_action_fields();

        foreach( $fields as $field ){
            delete_post_meta( $this->action_post_id , $field['name'] );
        }
    }
    /**
     * Get the template to display on the admin field
     * @param  [type] $template [description]
     * @return [type]           [description]
     *
     * @version  1.0.0
     */
    public function get_settings_template( $template ){
        $prefix = "[actions][$this->action_post_id]";

        include WPCF7_PRO_REDIRECT_ACTIONS_TEMPLATE_PATH.'rule-title.php';

        include WPCF7_PRO_REDIRECT_ACTIONS_TEMPLATE_PATH.$template;
    }

    /**
     * Get a single action row
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_action_row(){
        ob_start();

        do_action('before_wpcf7r_action_row' , $this );

        ?>
        <tr class="drag primary <?php echo $this->get_action_status() ? 'active' : 'non-active';?>" data-actionid="<?php echo $this->get_id();?>" id="post-<?php echo $this->get_id();?>">
            <td class="manage-column check-column ">
                <span class="num"><?php echo $this->get_menu_order();?></span>
            </td>
            <td class="manage-column column-title column-primary sortable desc">
                <span class="edit">
                    <a href="#" class="column-post-title" aria-label="<?php _e('Edit' , 'wpcf7-redirect');?>"><?php echo $this->get_title();?></a>
                </span>
                <div class="row-actions">
                    <span class="edit">
                        <a href="#" aria-label="<?php _e('Edit' , 'wpcf7-redirect');?>"><?php _e('Edit' , 'wpcf7-redirect');?></a> |
                    </span>
                    <span class="trash">
                        <a href="#" class="submitdelete" data-id="<?php echo $this->get_id();?>" aria-label="<?php _e('Move to trash' , 'wpcf7-redirect');?>"><?php _e('Move to trash' , 'wpcf7-redirect');?></a> |
                    </span>
                    <?php do_action('wpcf7r-after-actions-links' , $this );?>
                </div>
            </td>
            <td class="manage-column column-primary sortable desc edit">
                <a href="#" aria-label="<?php _e('Edit' , 'wpcf7-redirect');?>"><?php echo $this->get_type_label();?></a>
            </td>
            <td class="manage-column column-primary sortable desc edit column-status">
                <a href="#" aria-label="<?php _e('Edit' , 'wpcf7-redirect');?>"><?php echo $this->get_action_status_label();?></a>
            </td>
            <td class="manage-column check-column">
                <input type="hidden" name="post[]" value="<?php echo $this->get_id();?>">
                <span class="dashicons dashicons-menu handle"></span>
            </td>
        </tr>
        <tr data-actionid="<?php echo $this->get_id();?>" class="action-container">
            <td colspan="5" >
                <div class="hidden-action">
                    <?php $this->get_action_settings();?>
                </div>
            </td>
        </tr>
        <?php

        do_action('after_wpcf7r_action_row' , $this );

        return apply_filters( 'wpcf7r_get_action_rows' , ob_get_clean() , $this );
    }

    /**
     * Render html field
     * @param  [type] $field  [description]
     * @param  [type] $prefix [description]
     * @return [type]         [description]
     *
     * @version  1.0.0
     */
    public function render_field( $field , $prefix ){
        WPCF7R_html::render_field( $field , $prefix );
    }

    /**
     * Check if the action has conditional rules
     * @return boolean [description]
     *
     * @version  1.0.0
     */
    public function has_conditional_logic(){
        return $this->get('conditional_logic') ? true : false;
    }
    /**
     * Maybe perform actions before sending results to the user
     * @return [type] [description]
     */
    public function maybe_perform_pre_result_action(){

    }

    public function get_posted_data(){
        return $this->submission_data;
    }
    /**
     * This will process the required rules
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function process_action( $cf7r_form ){
        $results = array();

        $this->cf7r_form = $cf7r_form;

        $this->submission_data = $this->cf7r_form->get_submission();

        $this->posted_data_raw = $this->submission_data->get_posted_data();

        if( ! $this->has_conditional_logic() ){
            //if no conditions are defined
            $results = $this->process( $this->submission_data );
        }elseif( $rule = $this->get_valid_rule_block() ){
            //if valid rules are found process the action
            $results = $this->process( $this->submission_data );
        }

        return $results;
    }

    /**
     * Get all saved blocks
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_conditional_blocks(){

        $blocks = $this->get('blocks');

        if( ! $blocks ){
            $blocks = array(
                array(
                    'block_title' => 'Block title',
                    'groups' => $this->get_groups(),
                    'block_key' => 'block_1',
                )
            );
        }else{
            $blocks = maybe_unserialize( $blocks );

            $blocks['block_1']['block_key'] = 'block_1';
            $blocks['block_1']['block_title'] = 'Block title';

        }

        return $blocks;
    }

    /**
     * This function will find the relevant rule to use
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_valid_rule_block(){

        $blocks = $this->get('blocks');

        $blocks = maybe_unserialize( $blocks );

        if( isset( $blocks ) && $blocks ){
            foreach( $blocks as $block ){
                if( isset( $block['groups'] ) && $block['groups'] ){
                    foreach( $block['groups'] as $and_rows ){
                        $valid = true;
                        if( $and_rows ){
                            foreach( $and_rows as $and_row ){
                                if( ! $this->is_valid( $and_row )){
                                    $valid = false;
                                    break;
                                }
                            }
                            if( $valid ){
                                break;
                            }
                        }
                    }
                    if( $valid ){
                        return $block;
                    }
                }
            }
        }
    }
    /**
     * Check rule
     * @return boolean [description]
     *
     * @version  1.0.0
     */
    public function is_valid( $and_row ){
        $valid = false;

        if( isset( $and_row['condition'] ) && $and_row['condition'] ){

            $posted_value = isset( $this->posted_data_raw[$and_row['if']] ) ? $this->posted_data_raw[$and_row['if']] : '';

            $compare_value = $and_row['value'];

            switch( $and_row['condition'] ){
                case 'equal':
                    if( is_array( $posted_value ) && isset( $posted_value )){
                        $valid = in_array( $compare_value , $posted_value ) || $compare_value ==  $posted_value ? true : false;
                    }else{
                        $valid = $compare_value === $posted_value;
                    }
                    break;
                case 'not-equal':
                    if( is_array( $posted_value ) ){
                        $valid = ! in_array( $compare_value , $posted_value );
                    }else{
                        $valid = $compare_value !== $posted_value;
                    }
                    break;
                case 'contain':
                    $valid = strpos( $posted_value , $compare_value) !== false;
                    break;
                case 'not-contain':
                    $valid = strpos( $posted_value , $compare_value) == false;
                    break;
                case 'greater_than':
                    $valid = $posted_value > $compare_value;
                    break;
				case 'less_than':
                    $valid = $posted_value < $compare_value;
                    break;
                case 'is_null':
                    $valid = $posted_value === '';
                    break;
                case 'is_not_null':
                    $valid = $posted_value !== '';
                    break;
            }

        }

        return apply_filters( 'wpcf7r_is_valid' , $valid , $and_row );
    }
    /**
     * Get the fields relevant for conditional group
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_group_fields(){

        return array_merge(array(
                array(
                    'if' => '',
                    'condition' => '',
                    'value' => ''
                )
            )
        );
    }
    /**
     * Get the saved groups or display the default first one
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_groups(){
        $groups = array(
            'group-0' => $this->get_group_fields()
        );

        return $groups;
    }
    /**
     * Process all pre cf7 submit actions
     * @return [type] [description]
     */
    public function process_pre_submit_actions(){

    }
}
