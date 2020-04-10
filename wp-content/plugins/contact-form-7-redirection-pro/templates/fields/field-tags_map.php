<?php
/**
 * field-tags-map.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 /**
 * tags mapping for api
 *
 * @version  1.0.0
 */

$defaults_name = isset( $field['defaults_name'] ) ? $field['defaults_name'] : 'tags_defaults';

?>
<div class="field-wrap field-wrap-<?php echo $field['name'];?> <?php echo isset( $field['class'] ) ? $field['class'] : '';?>">
    <?php if( isset( $field['title'] ) && $field['title'] ):?>
        <label for="wpcf7-redirect-<?php echo $field['name'];?>">
            <h3><?php echo esc_html_e($field['label']);?></h3>
            &nbsp;
            <?php if( isset( $field['sub_title'] ) && $field['sub_title'] ):?>
                <label for="wpcf7-redirect-<?php echo $field['name'];?>"><?php echo esc_html_e($field['sub_title']);?></label>
                <br/>&nbsp;
            <?php endif;?>
        </label>
    <?php endif;?>
    <div class="cf7_row">
        <table class="wp-list-table widefat fixed striped pages wp-list-table-inner">
            <tr>
                <td><strong><?php _e('Form fields' , 'wpcf7-redirect');?></strong></td>

                <?php if( $field['name'] !='test_tags_map' ):?>
                    <td class="tags-map-api-key"><strong><?php _e('Api key' , 'wpcf7-redirect');?></strong><?php echo cf7r_tooltip( __('The API key as your api provider required' ));?></td>
                <?php endif;?>

                <?php if( $field['name'] !='test_tags_map' ):?>
                    <td><strong><?php _e('Defaults' , 'wpcf7-redirect');?></strong><?php echo cf7r_tooltip( __('Send default values if not selected by the user' ));?></td>
                <?php else:?>
                    <td><strong><?php _e('Value' , 'wpcf7-redirect');?></strong><?php echo cf7r_tooltip( __('Which value to send' ));?></td>
                <?php endif;?>

                <?php if( $field['name'] !='test_tags_map' && isset( $field['tags_functions'] ) ):?>
                    <td><strong><?php _e('Function' , 'wpcf7-redirect');?></strong><?php echo cf7r_tooltip( __('Perform actions on the submitted value' ));?></td>
                <?php endif;?>

            </tr>
            <?php foreach( $field['tags'] as $mail_tag) :?>
                <?php if( $mail_tag->type == 'checkbox' ):?>
                    <?php foreach( $mail_tag->values as $checkbox_row ):?>
                        <tr>
                            <td class="<?php echo $mail_tag->name;?>"><?php echo $mail_tag->name;?> (<?php echo $checkbox_row;?>)</td>
                            <?php if( $field['name'] !='test_tags_map' ):?>
                                <td class="tags-map-api-key">
                                    <input type="text"
                                        name="wpcf7-redirect<?php echo $prefix;?>[<?php echo $field['name'];?>][<?php echo $mail_tag->name;?>][<?php echo $checkbox_row;?>]"
                                        class="large-text"
                                        value="<?php echo isset($field['value'][$mail_tag->name][$checkbox_row]) ? esc_html($field['value'][$mail_tag->name][$checkbox_row]) : "";?>" />
                                </td>
                            <?php endif;?>
                            <td>
                                <?php $selected_value = isset( $field[$defaults_name]["{$mail_tag->name}"]["{$checkbox_row}"] ) ? $field[$defaults_name]["{$mail_tag->name}"]["{$checkbox_row}"] : ''; ?>
                                <input type="text" name="wpcf7-redirect<?php echo $prefix;?>[<?php echo $defaults_name;?>][<?php echo $mail_tag->name;?>][<?php echo $checkbox_row;?>]" value="<?php echo $selected_value;?>" />
                            </td>
                            <?php if( $field['name'] != 'test_tags_map' && isset( $field['tags_functions'] )):?>
                                <td>
                                    <?php $selected_function = isset( $field['tags_functions']["{$mail_tag->name}"]["{$checkbox_row}"] ) ? $field['tags_functions']["{$mail_tag->name}"]["{$checkbox_row}"] : ''; ?>
                                    <select class="" name="wpcf7-redirect<?php echo $prefix;?>[tags_functions][<?php echo $mail_tag->name;?>][<?php echo $checkbox_row;?>]">
                                        <?php $functions = WPCF7_Redirect_Utilities::get_available_text_functions();?>
                                        <option value=""><?php _e('Select');?></option>
                                        <?php foreach( array_keys($functions) as $function_name ):?>
                                            <option value="<?php echo $function_name;?>" <?php selected( $selected_function , $function_name );?>><?php echo $function_name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                            <?php endif;?>
                        </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td class="<?php echo $mail_tag->name;?>"><?php echo $mail_tag->name;?></td>
                        <?php if( $field['name'] !='test_tags_map' ):?>
                            <td class="tags-map-api-key">
                                <input type="text" id="sf-<?php echo $mail_tag->name;?>"
                                name="wpcf7-redirect<?php echo $prefix;?>[<?php echo $field['name'];?>][<?php echo $mail_tag->name;?>]"
                                class="large-text"
                                value="<?php echo isset($field['value'][$mail_tag->name]) ? esc_html($field['value'][$mail_tag->name]) : "";?>" />
                            </td>
                        <?php endif;?>
                        <td>
                            <?php $selected_value = isset( $field[$defaults_name]["{$mail_tag->name}"] ) ? $field[$defaults_name]["{$mail_tag->name}"] : ''; ?>
                            <input type="text" name="wpcf7-redirect<?php echo $prefix;?>[<?php echo $defaults_name;?>][<?php echo $mail_tag->name;?>]" value="<?php echo $selected_value;?>" />
                        </td>
                        <?php if( $field['name'] !='test_tags_map' && isset( $field['tags_functions'] ) ):?>
                            <td>
                                <?php $selected_function = isset( $field['tags_functions']["{$mail_tag->name}"] ) ? $field['tags_functions']["{$mail_tag->name}"] : ''; ?>
                                <select class="" name="wpcf7-redirect<?php echo $prefix;?>[tags_functions][<?php echo $mail_tag->name;?>]">
                                    <?php $functions = WPCF7_Redirect_Utilities::get_available_text_functions();?>
                                    <option value=""><?php _e('Select');?></option>
                                    <?php foreach( array_keys($functions) as $function_name ):?>
                                        <option value="<?php echo $function_name;?>" <?php selected( $selected_function , $function_name );?>><?php echo $function_name;?></option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                        <?php endif;?>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
        </table>
    </div>
</div>
