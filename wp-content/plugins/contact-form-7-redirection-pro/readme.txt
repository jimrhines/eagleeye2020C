=== Contact Form 7 Redirection ===
Tags: contact form 7 redirect, contact form 7 thank you page, redirect cf7, redirect contact form 7, contact form 7 success page, cf7 redirect
Requires at least: 4.7.0
Tested up to: 5.1
Stable tag: 1.8.1

A Comprehensive plugin for managing actions that occur after form submissions.

== Description ==

A Comprehensive plugin for managing actions that occur after form submissions.
Send emails
Send to remote server(restful API)
Redirect
Conditional logic
Fire javascript

NOTE: This plugin requires Contact Form 7 version 4.8 or later.

== Usage ==

Simply go to your form settings, choose the "Redirect Settings" tab and set the page you want to be redirected to.

== Installation ==

Installing Contact Form 7 Redirection can be done either by searching for "Contact Form 7 Redirection" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Visit the settings screen and configure, as desired.

== Frequently Asked Questions ==

= Does the plugin disables Contact Form 7 Ajax? =

No, it doesn't. The plugin does not disables any of Contact Form 7 normal behavior, unlike all other plugins that do the same.

= Does this plugin uses "on_sent_ok" additional setting? =

No. One of the reasons we developed this plugin, is because on_send_ok is now deprecated, and is going to be abolished by the end of 2017. This plugin is the only redirect plugin for Contact Form 7 that has been updated to use [DOM events](https://contactform7.com/dom-events/) to perform redirect, as Contact Form 7 developer Takayuki Miyoshi recommends.


== Changelog ==
= 1.8.2 =
* Add auto login after registration

= 1.8.1 =
* Fix conditional rule equal notice

= 1.8 =
* Added register to mailchimp list
* Added notice to contact form 7 < 4.5 versions
* Some bug fixes

= 1.7.1 =
* Fix for PHP < 5.4

= 1.7 =
* Added registration action
* Added quantity field to paypal product
* Added support for cf7 shortcodes on pay with paypal fields
* Minor styling and bug fixes

= 1.6.2 =
* Fix send to api debug log
* Added shortcode to collect user data on redirect page

= 1.6.1 =
* Fix json send to api format
* Updated api calls server timeout to 50

= 1.6 =
* Added login user action after submission
* Added form duplication support
* Added auto removal of action posts when contact form is deleted
* Make field selection mandatory
* Fixed styling
* Fixed Saving conditional group issue when more than 2 groups are used
* Fixed php notice
* Fixed version details on plugin updates

= 1.5.3 =
* Fixed repeater field... again

= 1.5.2 =
* Fixed repeater field

= 1.5 =
* Added custom errors management
* Added comparison options to conditional logic
* Added an option to build a custom redirect url
* Fix errors on new form instance
* Fix repeater field

= 1.4.3 =
* Added g-captcha tag support

= 1.4.2 =
* Bug fixes

= 1.4.1 =
* Fixed bug on leads list (option to delete the post)
* Added ID to the leads list
* Added an option to use [lead_id] tags
* Fixed bug on headers repeater (more than 1 header)

= 1.4=
* Added Redirect to paypal action
* Added Leads management

= 1.3=
* Added API headers settings.
* Added API testing tool.
* Minor design changes
* Added Default api fields
* Added the option to use functions on user data for api usage
* Added optional tags mapping for xml/json api process
* Added XML/Json validation
* Added special mail tags support

= 1.2 =
* Added migration options from old plugins (cf7 to api/cf7 redirection)

= 1.1.6 =
* Fix json/xml fields not saving.

= 1.0.0 =
* Initial release.
