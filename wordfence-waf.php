<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

// This file was the current value of auto_prepend_file during the Wordfence WAF installation (Sun, 23 Feb 2020 14:55:43 +0000)
if (file_exists('/opt/nas/www/common/production/auto_prepends.php')) {
	include_once '/opt/nas/www/common/production/auto_prepends.php';
}
if (file_exists('/nas/content/live/eepowers/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/nas/content/live/eepowers/wp-content/wflogs/');
	include_once '/nas/content/live/eepowers/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>