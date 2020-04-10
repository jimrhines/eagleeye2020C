<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?><div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php _e("Account Name",'contact-form-netsuite-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? $name : 'Account #'.$id; ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>
                

    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_api"><?php _e("Integration Method",'contact-form-netsuite-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <label for="vx_api"><input type="radio" name="crm[api]" value="" id="vx_api" class="vx_tabs_radio" <?php if($this->post('api',$info) != "token"){echo 'checked="checked"';} ?>> <?php _e('Username/Password Auth','contact-form-netsuite-crm'); ?></label>
  <label for="vx_web" style="margin-left: 15px;"><input type="radio" name="crm[api]" value="token" id="vx_web" class="vx_tabs_radio" <?php if($this->post('api',$info) == 'token'){echo 'checked="checked"';} ?>> <?php _e('Token Based Auth','contact-form-netsuite-crm');  ?></label> 
  </div>
  <div class="clear"></div>
  </div>
  
  <div class="vx_tabs" id="tab_vx_web" style="<?php if($this->post('api',$info) != "token"){echo 'display:none';} ?>">
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_key"><?php _e('Consumer Key','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="text" id="vx_key" name="crm[key]" class="crm_text req" placeholder="<?php _e('Consumer Key','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('key',$info)); ?>">
  <span class="howto"><?php echo sprintf(__('Go to Setup -> Integration -> Manage Integration -> New , enter App Name and check "Token Based Auth", %sView ScreenShots%s ','contact-form-netsuite-crm'),'<a href="https://www.crmperks.com/connect-netsuite-to-wordpress/" target="_blank">','</a>'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_sec"><?php _e('Consumer Secret','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="vx_sec" name="crm[secret]" class="crm_text req" placeholder="<?php _e('Consumer Secret','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('secret',$info)); ?>">
  </div>
  <div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Key','contact-form-netsuite-crm'); ?>"><?php _e('Show Key','contact-form-netsuite-crm') ?></a>
  
  </div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_token"><?php _e('Token ID','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="text" id="vx_token" name="crm[token]" class="crm_text req" placeholder="<?php _e('Token ID','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('token',$info)); ?>">
 <span class="howto"><?php _e('Go to Setup -> Users/Roles -> Access Tokens -> New , select application , user and role  ','contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_token_sec"><?php _e('Token Secret','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="vx_token_sec" name="crm[token_secret]" class="crm_text req" placeholder="<?php _e('Token Secret','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('token_secret',$info)); ?>">
  </div>
  <div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Key','contact-form-netsuite-crm'); ?>"><?php _e('Show Key','contact-form-netsuite-crm') ?></a>
  
  </div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  
  </div>

  <div class="vx_tabs" id="tab_vx_api" style="<?php if($this->post('api',$info) == "token"){echo 'display:none';} ?>">
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_email"><?php _e('Email','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="email" id="vx_email" name="crm[email]" class="crm_text" placeholder="<?php _e('Netsuite Login email','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('email',$info)); ?>" required>

  </div>
  <div class="clear"></div>
  </div>
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_pass"><?php _e('Password','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="vx_pass" name="crm[pass]" class="crm_text" placeholder="<?php _e('Password','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('pass',$info)); ?>" required>
  </div>
  <div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Key','contact-form-netsuite-crm'); ?>"><?php _e('Show Key','contact-form-netsuite-crm') ?></a>
  
  </div>
  </div>
  </div>
  <div class="clear"></div>
  </div> 
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_app"><?php _e('Application Id','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="text" id="vx_app" name="crm[app_id]" class="crm_text" placeholder="<?php _e('Application Id','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('app_id',$info)); ?>" required>
  <span class="howto"><?php _e('Goto Setup -> Integrations -> Manage Integrations. Here you can create new application or use id of any application','contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>

  </div>
   <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_url"><?php _e('Netsuite URL','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="url" id="vx_url" name="crm[url]" class="crm_text" placeholder="https://system.na1.netsuite.com" value="<?php echo esc_html($this->post('url',$info)); ?>" required>
 <span class="howto"><?php _e('Enter Netsuite system url, not webservice url','contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
      <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_url_web"><?php _e('Netsuite WebService URL','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="url" id="vx_url_web" name="crm[service_url]" class="crm_text" placeholder="https://ACCOUNT_ID.suitetalk.api.netsuite.com" value="<?php echo esc_html($this->post('service_url',$info)); ?>">
 <span class="howto"><?php _e('E.g. https://ACCOUNT_ID.suitetalk.api.netsuite.com','gravity-forms-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
  
 <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_role"><?php _e('Netsuite User Role','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="text" id="vx_role" name="crm[role]" class="crm_text" placeholder="3" value="<?php echo esc_html($this->post('role',$info)); ?>">
 <span class="howto"><?php _e('Enter Netsuite user role Id (optional).','contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_account"><?php _e('Account Id','contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">

  <input type="text" id="vx_account" name="crm[account_id]" class="crm_text" placeholder="<?php _e('Account Id','contact-form-netsuite-crm'); ?>" value="<?php echo esc_html($this->post('account_id',$info)); ?>" required>
  <span class="howto"><?php _e('Goto Setup -> Integration -> Web Services Preferences','contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>
      <?php if(isset($info['api_token'])  && $info['api_token']!="") {
  ?>
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e("Test Connection",'contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php _e("Test Connection",'contact-form-netsuite-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php _e("Notify by Email on Errors",'contact-form-netsuite-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php _e("Enter comma separated email addresses",'contact-form-netsuite-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? $info['error_email'] : ""; ?></textarea>
  <span class="howto"><?php _e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Netsuite. Leave blank to disable.",'contact-form-netsuite-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   
 
  <button type="submit" value="save" class="button-primary" title="<?php _e('Save Changes','contact-form-netsuite-crm'); ?>" name="save"><?php _e('Save Changes','contact-form-netsuite-crm'); ?></button>  
  </div>  
    <script type="text/javascript">
  jQuery(document).ready(function($){
      verify_req();
  $(".vx_tabs_radio").click(function(){
  $(".vx_tabs").hide();   
  $("#tab_"+this.id).show(); 
  verify_req();  
  });
  function verify_req(){
      $(".vx_tabs .crm_text").removeAttr('required');
      $(".vx_tabs:visible").find(".crm_text").attr('required','required');
  } 
  });
  </script>