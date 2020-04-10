<?php
/**
 * default-settings.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Displays the list of actions
 * @return [type] [description]
 *
 * @version  1.4
 */

?>
<h2><?php _e('Leads manager', 'wpcf7-redirect'); ?></h2>
<div class="leads-list">
    <div class="actions">
        <table class="wp-list-table widefat fixed striped pages" data-wrapid="leads">
            <thead>
                <tr>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('ID' , 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('Date', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('Time', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('Type', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column check-column">

                    </th>
                </tr>
            </thead>
            <tbody id="the_list">
                <?php if( $leads = $this->get_leads() ):?>
                    <?php foreach( $leads as $lead):?>
                        <?php echo $this->get_lead_row( $lead );?>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
