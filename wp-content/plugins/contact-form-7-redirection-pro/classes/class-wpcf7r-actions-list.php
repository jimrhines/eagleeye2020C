<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Actions_list{

    public static $action_posts;
	/** Class constructor */
	public function __construct( $actions_posts = "" ) {

        self::$action_posts = $actions_posts;

		parent::__construct( [
			'singular' => __( 'Action', 'wpcf7-redirect' ), //singular name of the listed records
			'plural'   => __( 'Actions', 'wpcf7-redirect' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}

    public function record_count() {
      return count(self::$action_posts);
    }

    /** Text diwpcf7-redirectlayed when no customer data is available */
    public function no_items() {
      _e( 'No Actions Avaliable.', 'wpcf7-redirect' );
    }
    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
      $actions = [
        'bulk-delete' => 'Delete'
      ];

      return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = 10;
        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );

        $this->items = self::$action_posts;
    }

    public function process_bulk_action() {

      //Detect when a bulk action is being triggered...
      if ( 'delete' === $this->current_action() ) {

        // In our file that handles the request, verify the nonce.
        $nonce = esc_attr( $_REQUEST['_wpnonce'] );

        if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
          die( 'Go get a life script kiddies' );
        }
        else {
          self::delete_customer( absint( $_GET['customer'] ) );

          wp_redirect( esc_url( add_query_arg() ) );
          exit;
        }

      }

      // If the delete bulk action is triggered
      if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
           || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
      ) {

        $delete_ids = esc_sql( $_POST['bulk-delete'] );

        // loop over the array of record IDs and delete them
        foreach ( $delete_ids as $id ) {
          self::delete_customer( $id );

        }

        wp_redirect( esc_url( add_query_arg() ) );
        exit;
      }
    }
}
