<?php
/**
 * Class WPCF7R_Leads_Manger file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }
 /**
 * Class WPCF7R_Leads_Manger
 * A Container class that handles leads management
 *
 * @version  1.0.0
 */
class WPCF7R_Leads_Manger{
    /**
     * Save a reference to the last lead inserted to the DB
     * @var [type]
     */
    public static $new_lead_id;

    public function __construct( $cf7_id ){
        $this->cf7_id = $cf7_id;

        $this->leads = array();
    }

    /**
     * Initialize leads table tab
     * @return [type] [description]
     */
    public function init(){
        include( WPCF7_PRO_REDIRECT_TEMPLATE_PATH . 'leads.php');
    }

    /**
     * Get leads
     * @return [type] [description]
     */
    public function get_leads(){
        $args = array(
            'post_type' => 'wpcf7r_leads',
            'post_status' => 'private',
            'posts_per_page' => 20,
            'meta_query' => array(
                array(
                    'key' => '_wpcf7',
                    'value' => $this->cf7_id
                )
            )
        );

        $leads_posts = get_posts( $args );

        if( $leads_posts ){
            foreach( $leads_posts as $leads_post){
                $lead = new WPCF7R_Lead( $leads_post );

                $this->leads[] = $lead;
            }
        }

        return $this->leads;
    }
    /**
     * Insert new lead
     * @return [type] [description]
     */
    public static function insert_lead( $cf7_form_id , $args , $lead_type ){

        $args['cf7_form'] = $cf7_form_id;

        $contact_form_title = get_the_title($cf7_form_id);

        $new_post = array(
            'post_type' => 'wpcf7r_leads',
            'post_status' => 'private',
            'post_title' => _('Lead From contact form: ' . $contact_form_title )
        );

        self::$new_lead_id = wp_insert_post( $new_post );

        $lead = new WPCF7R_Lead(self::$new_lead_id);

        $lead->update_lead_data( $args );

        $lead->update_lead_type( $lead_type );

        return $lead;
    }
    /**
     * Save the action to the db lead
     * @param  [type] $lead_id     [description]
     * @param  [type] $action_name [description]
     * @param  [type] $details     [description]
     * @return [type]              [description]
     */
    public static function save_action( $lead_id , $action_name , $details ){
        add_post_meta( $lead_id , 'action-'.$action_name , $details);
    }
    /**
     * Get a single action row
     * @return [type] [description]
     *
     * @version  1.0.0
     */
    public function get_lead_row( $lead ){
        ob_start();
        do_action('before_wpcf7r_lead_row' , $this );
        ?>
        <tr class="primary" data-postid="<?php echo $lead->get_id();?>">
            <td class="manage-column column-primary sortable desc edit column-id">
                <?php echo $lead->get_id();?>
                <div class="row-actions">
                    <span class="edit">
                        <a href="<?php echo get_edit_post_link( $lead->get_id());?>" data-id="<?php echo $lead->get_id();?>" aria-label="<?php _e('View' , 'wpcf7-redirect');?>" target="_blank"><?php _e('View' , 'wpcf7-redirect');?></a> |
                    </span>
                    <span class="trash">
                        <a href="#" class="submitdelete" data-id="<?php echo $lead->get_id();?>" aria-label="<?php _e('Move to trash' , 'wpcf7-redirect');?>"><?php _e('Move to trash' , 'wpcf7-redirect');?></a> |
                    </span>
                    <?php do_action('wpcf7r-after-lead-links' , $lead );?>
                </div>
            </td>
            <td class="manage-column column-primary sortable desc edit column-date">
                <?php echo $lead->get_date();?>
            </td>
            <td class="manage-column column-primary sortable desc edit column-time"><?php echo $lead->get_time();?></td>
            <td class="manage-column column-primary sortable desc edit column-type"><?php echo $lead->get_lead_type();?></td>
            <td></td>
        </tr>
        <?php

        do_action('after_wpcf7r_lead_row' , $this );

        return apply_filters( 'wpcf7r_get_lead_row' , ob_get_clean() , $this );
    }
}
