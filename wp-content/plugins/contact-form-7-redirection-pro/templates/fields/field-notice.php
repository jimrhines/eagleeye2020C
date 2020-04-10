<?php
/**
 * field-notice.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Displays a notice block
 * @return [type] [description]
 *
 * @version  1.0.0
 */

?>
<div class="field-wrap">
    <div class="field-notice field-wrap-<?php echo $field['name'];?> <?php echo isset( $field['class'] ) ? $field['class'] : '';?>">
        <strong>
            <?php echo $field['label'];?>
        </strong>
        <?php echo $field['sub_title'];?>
    </div>

</div>
