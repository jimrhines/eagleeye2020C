<?php
/**
 * leads.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Displays the leads panel
 * @return [type] [description]
 *
 * @version  1.0.0
 */

wp_nonce_field( 'wpcf7_redirect_page_leads', 'wpcf7_redirect_page_leads_nonce' );

/**
 * @var [type]
 *
 * @version  1.0.0
 */
do_action( 'before_leads_settings_tab_title' , $this );

?>
<fieldset>

    <div class="fields-wrap field-wrap-page-id">
        <div class="tab-wrap">
            <div class="wpcf7r-tab-wrap-inner">
                <div class="" data-tab-inner>
                    <?php include('leads/leads-table.php'); ?>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php

/**
 *
 * @version  1.0.0
 */

do_action('after_leads_settings_tab_title' , $this );
