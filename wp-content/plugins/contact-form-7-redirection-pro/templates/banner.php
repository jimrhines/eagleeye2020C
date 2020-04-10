<?php
/**
 * banner.php file.
 *
 * @package cf7r
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

/**
 * Display banner on the admin tab
 * @return [type] [description]
 *
 * @version  1.0.0
 */
?>
<div class="wpcfr-banner">
    <div class="wpcfr-banner-holder">
        <span class="dashicons dashicons-no close-banner" title="close me and i wont bother you untill the next update"></span>
        <a href="http://querysol.com/product/contact-form-7-redirection/" target="_blank">
            <img src="<?php echo wpcf7r_get_redirect_plugin_url();?>/assets/images/banner.png" alt="Get pro version" />
        </a>
    </div>
</div>
