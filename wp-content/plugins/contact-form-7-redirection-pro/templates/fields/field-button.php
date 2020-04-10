<?php
/**
 * field-checkbox.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Displays a checkbox field
 * @return [type] [description]
 *
 * @version  1.1.7
 */

?>
<div class="field-wrap field-wrap-<?php echo $field['name'];?> wpcf7r-button <?php echo isset( $field['class'] ) ? $field['class'] : '';?>" data-toggle="<?php echo isset( $field['show_selector'] ) ? $field['show_selector'] :'';?>">
        <?php if( isset( $field['label'] ) && $field['label'] ) :?>
            <label class="wpcf7-redirect-<?php echo $field['name'];?>"><?php echo $field['label'];?></label>
        <?php endif;?>
        <input type="button" class="button-primary wpcf7-redirect-<?php echo $field['name'];?>-fields"
        name="wpcf7-redirect<?php echo $prefix;?>[<?php echo $field['name'];?>]"
        <?php echo isset( $field['attr']) ? wpcf7r_implode_attributes( $field['attr'] ) : '';?>
        value="<?php echo esc_html_e($field['label']);?>"
        />
</div>
