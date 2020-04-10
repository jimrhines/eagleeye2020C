<?php
/**
 * Class WPCF7R_Redirect_actions file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * Class WPCF7R_Redirect_actions
 * A helper class for managing form actions
 *
 * @version  1.0.0
 */

class WPCF7R_Redirect_actions {
	/**
	 * our main constructor
	 * @param [type] $post_id     [description]
	 * @param [type] $wpcf7r_form [description]
	 *
	 *  * @version  1.0.0

	 */
	public function __construct( $post_id , $wpcf7r_form ){
		$this->post_type = 'wpcf7r_action';

		$this->wpcf7_post_id = $post_id;

		$this->html = new WPCF7R_html( WPCF7R_Form::$mail_tags );
	}

	/**
	 * Get all actions that are relevant to this form
	 * @param  [type]  $rule_id [description]
	 * @param  integer $count   [description]
	 * @param  boolean $active  [description]
	 * @return [type]           [description]
	 *
	 *  @version  1.0.0
	 */
	public function get_actions( $rule_id , $count = -1 , $active = false , $args = array() ){

        $this->actions = array();

		$actions_posts =  $this->get_action_posts( $rule_id , $count , $active , $args );

		$actions = array();

		if( $actions_posts ){
            $counter = 0;
			foreach( $actions_posts as $action_post ){
				$action = WPCF7R_Action::get_action($action_post);

                $actions[$action->priority.'_'.$counter] = $action;
                $counter++;
			}
		}

        ksort($actions);
        $this->actions = $actions;

		return $this->actions;
	}

	/**
	 * Get and return the posts that are used as actions
	 * @param  [type]  $rule_id [description]
	 * @param  integer $count   [description]
	 * @param  boolean $active  [description]
	 * @return [type]           [description]
	 *
	 * @version  1.0.0
	 */
	public function get_action_posts( $rule_id , $count = -1 , $active = false , $extra_args = array() ){
		$args = array(
			'post_type'	 => $this->post_type,
			'posts_per_page' => $count,
			'post_status' => 'private',
			'orderby'	=> 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'wpcf7_id',
					'value' => $this->wpcf7_post_id,
				),
				array(
					'key' => 'wpcf7_rule_id',
					'value' => $rule_id,
				)
			)
		);

        $args = array_merge( $args , $extra_args );

		if( $active ){
			$args['meta_query'][] = array(
				'key' => 'action_status',
				'value' => 'on',
			);
		}

		$actions = get_posts( $args );

		return $actions;
	}

	/**
	 * Echo the templates used for the javascript process
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function html_fregments(){
		if( ! isset( $this->wpcf7_post_id ) ){
			return;
		}

		$action = new WPCF7R_Action();

		$new_block = array(
			'block_title' => __('New Block' , 'wpcf7-redirect' ),
			'groups'      => $action->get_groups(),
			'block_key'   =>'new_block',
		);

		$default_group = $action->get_group_fields();

		$prefix = '[actions][action_id]';

		$fields         = $this->get_plugin_default_fields_values();
		$row_template   = $this->html->get_conditional_row_template( $new_block['block_key'] , 'new_group' , 'new_row' , reset( $default_group ) , $prefix );

		$options['row_html']         = $row_template;
		$options['group_html']       = $this->html->group_display( 'new_block' , 'new_group' , reset( $new_block['groups'] ) , $prefix );
		$options['block_html']       = $this->html->get_block_html( 'new_block' , $new_block , false , false  , $prefix );
		$options['block_title_html'] = $this->html->get_block_title( 'new_block' , $new_block , false , false , $prefix  );
		$options['mail_tags']        = WPCF7R_Form::$mail_tags;

		echo "<script>";
			echo 'var wpcfr_template = '.json_encode( $options );
		echo "</script>";
	}

	/**
     * Get form values
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_plugin_default_fields_values(){
		$fields = WPCF7_Redirect_Form::get_plugin_default_fields();

		foreach ( $fields as $field ) {
			$values[ $field['name'] ] = '';//get_post_meta( $this->wpcf7_post_id, '_wpcf7_redirect_' . $field['name'] , true );
		}

		return $values;
	}

}
