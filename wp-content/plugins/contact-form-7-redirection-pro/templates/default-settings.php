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
 * @version  1.2
 */

?>
<?php $rule_id = 'default';?>
<h2><?php _e('Submission Actions', 'wpcf7-redirect'); ?></h2>
<legend><?php _e('You can add actions that will be fired on submission. For details and support, check <a href="https://querysol.com/product/contact-form-7-redirection/" target="_blank">The plugin page</a>', 'wpcf7-redirect');?></legend>
<div class="actions-list">
    <div class="actions">
        <table class="wp-list-table widefat fixed striped pages" data-wrapid="<?php echo $rule_id;?>">
            <thead>
                <tr>
                    <th class="manage-column check-column">
                        <a href="#"><?php _e('No.', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-title column-primary sortable desc">
                        <a href="#"><?php _e('Title', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('Type', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column column-primary sortable desc">
                        <a href="#"><?php _e('Active', 'wpcf7-redirect'); ?></a>
                    </th>
                    <th class="manage-column check-column">

                    </th>
                </tr>
            </thead>
            <tbody id="the_list">
                <?php if( $actions = $this->get_actions($rule_id) ) :?>
                    <?php foreach( $actions as $action):?>
                        <?php echo $action->get_action_row(); ?>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>

    </div>
    <div class="add-new-action-wrap">
        <select class="new-action-selector" name="new-action-selector">
            <option value=""><?php _e('Choose Action');?></option>
            <?php foreach( wpcf7r_get_available_actions() as $available_action_key => $available_action_label):?>
                <option value="<?php echo $available_action_key;?>" <?php echo $available_action_label['attr'];?>><?php echo $available_action_label['label'];?></option>
            <?php endforeach;?>
        </select>
        <button type="button" name="button" class="button-primary wpcf7-add-new-action" data-ruleid="<?php echo $rule_id;?>" data-id="<?php echo $this->get_id();?>"><?php _e('Add new action', 'wpcf7-redirect');?></button>
        <?php if( ! $this->has_migrated( 'migrate_from_cf7_api' ) && $this->has_old_data( 'migrate_from_cf7_api' ) ):?>
            <a href="#" data-migration-type="migrate_from_cf7_api" class="migrate-from-send-to-api" data-ruleid="<?php echo $rule_id;?>" data-id="<?php echo $this->get_id();?>"><?php _e('Migrate from API' , 'wpcf7-redirect');?></a>
            <br />
        <?php endif;?>
        <?php if( ! $this->has_migrated( 'migrate_from_cf7_redirect' ) && $this->has_old_data( 'migrate_from_cf7_redirect' ) ):?>
            <a href="#" data-migration-type="migrate_from_cf7_redirect" class="migrate-from-redirection" data-ruleid="<?php echo $rule_id;?>" data-id="<?php echo $this->get_id();?>"><?php _e('Migrate from Redirection' , 'wpcf7-redirect');?></a>
        <?php endif;?>
    </div>
</div>
