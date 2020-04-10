<?php
/**
 * field-page-select.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }
?>
<div class="field-wrap field-wrap-<?php echo $field['name'];?> <?php echo isset( $field['class'] ) ? $field['class'] : '';?>">
    <label for="wpcf7-redirect-<?php echo $field['name'];?>">
        <strong><?php echo esc_html_e($field['label']);?></strong>
    </label>
    <?php
        echo wp_dropdown_pages( array(
                'echo'              => 0,
                'name'              => 'wpcf7-redirect'.$prefix.'['.$field['name'].']',
                'show_option_none'  => $field['placeholder'],
                'option_none_value' => '0',
                'selected'          => $field['value'],
                'id'                => '',
                'class'             => 'wpcf7-redirect-'.$field['name'].'-fields'
            )
        );
    ?>
</div>
