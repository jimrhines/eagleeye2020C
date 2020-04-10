<?php
/**
 * Class WPCF7_Redirect_Utilities file.
 *
 * @package cf7r
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact form 7 redirect utilities
 *
 * @version  1.0.0
 */
class WPCF7_Redirect_Utilities{

    public $banner_version = 1.01;

	public static $instance;

	public static $actions_list = array();

    public function __construct(){
		self::$instance = $this;

		$this->api = new wpcf7r_qs_api();
    }

	/**
	 * Add a message to the session collector
	 * @param [type] $type    [description]
	 * @param [type] $message [description]
	 *
	 * @version  1.0.0
	 */
	public static function add_admin_notice( $type , $message ){
		$_SESSION['wpcf7r_admin_notices'][$type] = $message;
	}
	/**
	 * Register a new type of action
	 * @param  [type] $name  [description]
	 * @param  [type] $title [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function register_wpcf7r_actions( $name , $title , $class ){
	    self::$actions_list[$name] = array(
			'label' => $title,
			'attr' => '',
			'handler' => $class
		);
	}

	/**
	 * Get the available actions
	 * @return [type] [description]
	 */
	public static function get_wpcf7r_actions(){
		return self::$actions_list;
	}

	/**
	 * Duplicate all action posts and connect it to the new created form
	 * @param  [type] $cf7 [description]
	 * @return [type]      [description]
	 */
	public function duplicate_form_support( $new_cf7 ){

		if( isset( $_POST['wpcf7-copy'] ) && $_POST['wpcf7-copy'] == 'Duplicate' || ( isset( $_GET['action'] ) && $_GET['action'] == 'copy' ) ){

			$original_post_id = isset( $_POST['post_ID'] ) ? (int)$_POST['post_ID'] : (int)$_GET['post'];
			$original_cf7 = get_cf7r_form( $original_post_id );

			$original_action_posts = $original_cf7->get_actions('default');

			if( $original_action_posts ){
				foreach( $original_action_posts as $original_action_post ){
					$new_post_id = $this->duplicate_post( $original_action_post->action_post );
					update_post_meta( $new_post_id , 'wpcf7_id' , $new_cf7->id() );
				}
			}
		}
	}

	/**
	 * After form deletion delete all its actions
	 * @return [type] [description]
	 */
	public function delete_all_form_actions( $post_id ){
		global $post_type;

		if ( get_post_type($post_id) == 'wpcf7_contact_form' ){

			$wpcf7r = get_cf7r_form( $post_id );

			$action_posts = $wpcf7r->get_actions('default');

			if( $action_posts ){
				foreach( $action_posts as $action_post ){
					wp_delete_post( $action_post->get_id() );
				}
			}
		};

	}
	/**
	 * Dupplicate contact form and all its actions
	 * @param  [type] $post [description]
	 * @return [type]       [description]
	 */
	public function duplicate_post( $post ){
		global $wpdb;

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		$post_id = $post->ID;
		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset( $post ) && $post != null) {
			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'private',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			if( $taxonomies ){
				foreach ($taxonomies as $taxonomy) {
					$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
					wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
				}
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos) != 0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query( $sql_query );
			}

			return $new_post_id;
		}
	}
	/**
	 * Set actions order
	 *
	 * @version  1.0.0
	 */
	public function set_action_menu_order(){
		global $wpdb;

		parse_str( $_POST['data']['order'], $data );

		if ( !is_array( $data ) ) return false;

		// get objects per now page
		$id_arr = array();
		foreach( $data as $key => $values ) {
			foreach( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}

		foreach( $id_arr as $key => $post_id ) {
			foreach( $values as $position => $id ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => intval( $post_id ) ) );
			}
		}
	}

	/**
	 * Delete an action
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function delete_action_post(){
		$data = isset( $_POST['data'] ) ?  $_POST['data'] : '';

		$response['status'] = 'failed';

		if( $data ){
			foreach( $data as $post_to_delete ){
				if( $post_to_delete ){
					wp_delete_post( $post_to_delete['post_id'] );
					$response['status'] = 'deleted';
				}
			}
		}


		wp_send_json($response);
	}

	/**
	 * Show notices on admin panel
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function show_admin_notices(){
		global $wp_sessions;

		if( ! isset( $_SESSION['wpcf7r_admin_notices'] ) ){
			return;
		}

		foreach( $_SESSION['wpcf7r_admin_notices'] as $notice ):
		?>
	     <div class="notice notice-error is-dismissible <?php echo $this->notice_type;?>">
	         <p><?php _e( 'Error!', 'wpcf7-redirect' ); ?></p>
			 <p><?php echo $notice;?></p>
	     </div>
	     <?php
	 	endforeach;
	}

	/**
	 * Create a new action post
	 *
	 * @version  1.2
	 */
	public function add_action_post(){
		$results['action_row'] = '';

		$post_id = isset( $_POST['data']['post_id'] ) ? (int)sanitize_text_field( $_POST['data']['post_id'] ) : '';
		$rule_id = isset( $_POST['data']['rule_id'] ) ? sanitize_text_field( $_POST['data']['rule_id'] ) : '';
		$action_type = isset( $_POST['data']['action_type'] ) ? sanitize_text_field( $_POST['data']['action_type'] ) : '';

		$rule_name = __('New Rule' ,'wpcf7-redirect');

		$this->cf7r_form = get_cf7r_form( $post_id );

		$actions = array();
		//migrate from old api plugin
		if( $action_type == 'migrate_from_cf7_api' || $action_type == 'migrate_from_cf7_redirect' ){
			if( ! $this->cf7r_form->has_migrated( $action_type ) ){
				$actions = $this->convert_to_action( $action_type , $post_id , $rule_name , $rule_id );
				$this->cf7r_form->update_migration( $action_type );
			}
		}else{
			$actions[] = $this->create_action( $post_id , $rule_name , $rule_id , $action_type );
		}

		if( $actions ){
			foreach( $actions as $action ){
				$results['action_row'].= $action->get_action_row();
			}
		}else{
			$results['action_row'] = '';
		}

		wp_send_json($results);
	}

	/**
	 * Convert old plugin data to new structure
	 * @param  [type] $required_conversion [description]
	 * @param  [type] $post_id             [description]
	 * @param  [type] $rule_name           [description]
	 * @param  [type] $rule_id             [description]
	 * @return [type]                      [description]
	 *
	 * @version 1.2
	 */
	public function convert_to_action( $required_conversion , $post_id , $rule_name , $rule_id ){
		$actions = array();

		if( $required_conversion == 'migrate_from_cf7_redirect' ){
			$old_api_action = $this->cf7r_form->get_cf7_redirection_settings();

			if( $old_api_action ){

				//CREATE JAVSCRIPT ACTION
				if( $old_api_action['fire_sctipt'] ){
					$javscript_action = $this->create_action( $post_id , __('MIGRATED JAVSACRIPT DATA FROM OLD PLUGIN') , $rule_id , 'fire_script' );

					$javscript_action->set( 'script' , $old_api_action['fire_sctipt'] );
					$javscript_action->set( 'action_status' , 'on' );

					unset($old_api_action['fire_sctipt']);

					$actions[] = $javscript_action;
				}

				//CREATE REDIRECT ACTION
				$action = $this->create_action( $post_id , __('MIGRATED DATA FROM OLD PLUGIN') , $rule_id , 'redirect' );

				$action->set( 'action_status' , 'on' );

				foreach( $old_api_action as $key => $value ){
					$action->set( $key , $value );
				}

				$actions[] = $action;
			}

		}else if( $required_conversion == 'migrate_from_cf7_api' ){
			$old_api_action = $this->cf7r_form->get_cf7_api_settings();

			$old_api__wpcf7_api_data = $old_api_action['_wpcf7_api_data'];
			$old_tags_map = $old_api_action['_wpcf7_api_data_map'];

			if( $old_api__wpcf7_api_data['input_type'] == 'params' ){
				$action_type = 'api_url_request';
			}elseif( $old_api__wpcf7_api_data['input_type'] == 'xml' || $old_api__wpcf7_api_data['input_type'] == 'json' ){
				$action_type = 'api_json_xml_request';
			}

			$action = $this->create_action( $post_id , __('MIGRATED DATA FROM OLD PLUGIN') , $rule_id , $action_type );

			$action->set('base_url' , $old_api__wpcf7_api_data['base_url']);
			$action->set('input_type' , strtolower($old_api__wpcf7_api_data['method']) );
			$action->set('record_type' , strtolower($old_api__wpcf7_api_data['input_type']) );
			$action->set('show_debug' , '');
			$action->set('action_status' , $old_api__wpcf7_api_data['send_to_api'] );

			$tags_map = array();

			if( $old_tags_map ){
				foreach( $old_tags_map as $tag_key => $tag_api_key ){
					$tags_map[$tag_key] = $tag_api_key;
				}

				$action->set('tags_map' , $tags_map );
			}

			if( isset( $old_api_action['_template'] ) && $old_api_action['_template'] ){
				$action->set('request_template' , $old_api_action['_template'] );
			}elseif( isset( $old_api_action['_json_template'] ) && $old_api_action['_json_template'] ){
				$action->set('request_template' , $old_api_action['_json_template'] );
			}

			$actions[] = $action;
		}

		return $actions;
	}
	/**
	 * Create new post that will hold the action
	 * @param  [type] $rule_name   [description]
	 * @param  [type] $rule_id     [description]
	 * @param  [type] $action_type [description]
	 * @return [type]              [description]
	 *
	 * @version  1.0.0
	 */
	public function create_action( $post_id , $rule_name , $rule_id , $action_type ){
		$new_action_post = array(
			'post_type' => 'wpcf7r_action',
			'post_title' => $rule_name,
			'post_status' => 'private',
			'menu_order' => 1,
			'meta_input' => array(
				'wpcf7_id' => $post_id,
				'wpcf7_rule_id' => $rule_id,
				'action_type' => $action_type,
				'action_status' => 'on'
			)
		);

		$new_action_id = wp_insert_post($new_action_post);

		return WPCF7R_Action::get_action( $new_action_id , $post_id );
	}
	/**
	 * [get_instance description]
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_instance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
    /**
     * Get the banner template
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_banner(){
        if( $this->get_option( 'last_banner_displayed' ) == $this->banner_version ){
            return;
        }
        ob_start();

        include( WPCF7_PRO_REDIRECT_TEMPLATE_PATH . 'banner.php' );

        $banner_html = ob_get_clean();

        echo $banner_html;
    }

	/**
	 * Show a message containing the license details
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public function license_details_message(){
		if( ! self::get_activation_id() ){
			printf(
					'<tr class="plugin-update-tr active" id="wpcf7-redirect-pro-update" data-slug="wpcf7-redirect-pro" data-plugin="contact-form-7-redirection-pro/wpcf7-redirect-pro.php"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt"><p><strong>%s</strong> %s</p></div></td></tr>',
					__('Please activate plugin license for updates'),
					self::get_settings_link()
				);

		}

	}
	/**
     * Get all data related with plugin activation
     * @return [type] [description]
     */
    public static function get_activation_data(){
        return get_option('wpcf7r_activation_data');
    }
    /**
     * Get date of plugin license ex
     *
     * @version  1.0.0piration
     * @return [type] [description]
     */
    public static function get_activation_expiration(){
        return get_option('wpcf7r_activation_expiration');
    }

	/**
	 * A validation function to test the serial key
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function validate_serial_key(){
		$instance = self::get_instance();

		$serial = self::get_serial_key();
		$activation_id = self::get_activation_id();

		return $instance->api->validate_serial( $activation_id , $serial );
	}

	/**
	 * Get the used setial key
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_serial_key(){
		return get_option( 'wpcf7r_serial_number' );
	}

	/**
	 * Delete the used setial key
	 * @return [type] [description]
	 * @version  1.0.0
	*/
	public static function delete_serial_key(){
		return delete_option( 'wpcf7r_serial_number' );
	}

	/**
	 * Get a url to deactivate plugin license
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_deactivation_link(){
		$url = self::get_plugin_settings_page_url();

		$url = add_query_arg( 'wpcf7r_deactivate_license' , '' , $url );

		return $url;
	}

	/**
	 * Get the plugin settings link
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_plugin_settings_page_url(){
		return get_admin_url(null, 'options-general.php?page=wpc7_redirect');
	}

    /**
     * Get the activation id
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public static function get_activation_id(){
        return get_option('wpcf7r_activation_id');
    }

	/**
	 * Get a link to the admin settings panel
	 * @return [type] [description]
	 *
	 * @version  1.0.0
	 */
	public static function get_settings_link(){
		return '<a href="' . self::get_plugin_settings_page_url() . '">' . __('Settings', 'example_plugin') . '</a>';
	}

    /**
     * [close_banner description]
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function close_banner(){
        $this->update_option( 'last_banner_displayed' , $this->banner_version );
    }

    /**
     * Get specific option by key
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_option($key){
        $options = $this->get_wpcf7_options();

        return isset( $options[$key] ) ? $options[$key] : '';
    }
    /**
     * Update a specific option
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     *
     * @version  1.0.0
     */
    public function update_option( $key , $value ){
        $options = $this->get_wpcf7_options();

        $options[$key] = $value;

        $this->save_wpcf7_options( $options );

    }
    /**
     * Get the plugin options
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_wpcf7_options(){
        return get_option( 'wpcf_redirect_options' );
    }
    /**
     * Save the plugin options
     * @param  [type] $value [description]
     * @return [type]        [description]
     *
     * @version  1.0.0
     */
    public function save_wpcf7_options( $options ){
        update_option( 'wpcf_redirect_options', $options );
    }

	/**
	 * Get a list of avaiable text functions and callbacks
	 * @return [type] [description]
	 */
	public static function get_available_text_functions( $func = ""){
		$functions = array(
			'md5' => array( 'WPCF7_Redirect_Utilities' , 'func_md5' ),
			'base64_encode' => array( 'WPCF7_Redirect_Utilities' , 'func_base64_encode' ),
			'utf8_encode' => array( 'WPCF7_Redirect_Utilities' , 'func_utf8_encode' ),
		);

		$functions = apply_filters( 'get_available_text_functions', $functions );

		if( $func ){
			return isset( $functions[$func] ) ? $functions[$func]: '';
		}
		return $functions;
	}

	/**
	 * [func_utf8_encode description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function func_utf8_encode( $value ){
		return apply_filters( 'func_utf8_encode' , utf8_encode($value) , $value );
	}

	/**
	 * [func_base64_encode description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function func_base64_encode( $value ){
		return apply_filters( 'func_base64_encode' , base64_encode($value) , $value );
	}

	/**
	 * md5 function
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function func_md5( $value ){
		return apply_filters( 'func_md5' , md5($value) , $value );
	}

	public function make_api_test(){
		parse_str( $_POST['data']['data'], $data );

		if ( !is_array( $data ) ) die('-1');

		$action_id = isset( $_POST['data']['action_id'] ) ? (int)sanitize_text_field( $_POST['data']['action_id'] ) : '';
		$cf7_id = isset( $_POST['data']['cf7_id'] ) ? (int)sanitize_text_field( $_POST['data']['cf7_id'] ) : '';
		$rule_id = isset( $_POST['data']['rule_id'] ) ? $_POST['data']['rule_id'] : '';

		add_filter('after_qs_cf7_api_send_lead' , array( $this , 'after_fake_submission') , 10 ,3 );

		if( isset( $data['wpcf7-redirect']['actions'] ) ){
			$response = array();

			$posted_action = reset( $data['wpcf7-redirect']['actions'] );
			$posted_action = $posted_action['test_values'];
			$_POST = $posted_action;
			//this will create a fake form submission
			$this->cf7r_form = get_cf7r_form( $cf7_id );
			$this->cf7r_form->enable_action( $action_id );

			$cf7_form = $this->cf7r_form->get_cf7_form_instance();
			$submission = WPCF7_Submission::get_instance( $cf7_form );


			if( $submission->get_status() == 'validation_failed' ){
				$invalid_fields = $submission->get_invalid_fields();
				$response['status'] = 'failed';
				$response['invalid_fields'] = $invalid_fields;
			}else{
				$response['status'] = 'success';
				$response['html'] = $this->get_test_api_results_html();
			}

			wp_send_json($response);
		}
	}
	/**
	 * Store the results from the API
	 * @param  [type] $result [description]
	 * @param  [type] $record [description]
	 * @return [type]         [description]
	 */
	public function after_fake_submission( $result , $record , $args ){
		$this->results = $result;
		$this->record = $record;
		$this->request = $args;

		return $result;
	}
	/**
	 * Get the popup html
	 * @return [type] [description]
	 */
	public function get_test_api_results_html(){
		ob_start();

		include( WPCF7_PRO_REDIRECT_TEMPLATE_PATH . 'popup-api-test.php' );

		return ob_get_clean();
	}

	/**
	 * Get lists
	 * Maybe create new list
	 * @return [type] [description]
	 */
	public function get_mailchimp_lists(){
		$data = $_POST['data'];

		$results = array();

		if ( !is_array( $data ) ) die('-1');

		$api_key = $data['mailchimp_api_key'];

		/**
		 * A request to create a list
		 * @var [type]
		 */
		if( isset( $data['list_name'] ) && $data['list_name'] ){
			$new_list_results = WPCF7_Mailchimp_helper::create_list( $api_key , $data['list_name'] );

			if( is_wp_error( $new_list_results ) ){
				$results['error'] = $new_list_results->get_error_message();
			}
		}

		/**
		 * In the end return the lists
		 * @var [type]
		 */
		if( ! isset( $results['error'] ) ){
			$lists = WPCF7_Mailchimp_helper::get_lists_clean($api_key);

			update_post_meta( $data['action_id'] , 'mailchimp_lists' , $lists );

			if( is_wp_error( $lists ) ){
				$results['error'] = $lists->get_error_message();
			}else{
				$results['lists'] = $lists;
			}
		}

		wp_send_json($results);
	}

}
