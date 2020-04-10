<?php
/**
 * block-title.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Displais a conditional block title
 * @return [type] [description]
 *
 * @version  1.0.0
 */

?>
<div class="block-title <?php echo $active_tab_title; $active_tab_title = '';?>" data-rel="<?php echo $group_block_key;?>">
    <span class="dashicons dashicons-edit"></span>
    <span class="dashicons dashicons-yes show-on-edit" data-rel="<?php echo $group_block_key;?>"></span>
    <span class="dashicons dashicons-no show-on-edit" data-rel="<?php echo $group_block_key;?>"></span>
    <span class="dashicons dashicons-minus show-on-edit remove-block"></span>
    <input type="text" name="wpcf7-redirect<?php echo $prefix;?>[blocks][<?php echo $group_block_key;?>][block_title]" value="<?php _e( $group_block['block_title'] , 'wpcf7-redirect' );?>" data-original="<?php _e( $group_block['block_title'] , 'wpcf7-redirect' );?>" readonly="readonly">
</div>
