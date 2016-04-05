<?php

/*
Plugin Name: Popup4Phone
Plugin URI: http://popup4phone.com
Description: Popover/popup dialog for collecting the user`s phone numbers
Version: 1.2.3
Author: Ivan Skorodumov
Author URI: http://popup4phone.com/author
Developer: Ivan Skorodumov
Developer URI: http://popup4phone.com/author
Text Domain: popup4phone
Domain Path: /lang

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

$cfg = array(
	'version' => '1.2.3',
	'id'      => 'popup4phone',
	'file'    => plugin_basename( __FILE__ ),
	'name'    => "Popup4Phone",
	'url'     => "http://popup4phone.com/",
);

define("POPUP4PHONE_CFG", json_encode($cfg));

include 'includes/code/_safe.php';

class Popup4Phone_Filters
{
	const SETTINGS_JS_BEFORE_PUBLISH = 'popup4phone_settings_js_before_publish';
	const SETTINGS_FIELDS = 'popup4phone_settings_fields';
	const SETTINGS_TABS = 'popup4phone_settings_tabs';
	const FORM_SUBMIT_BEFORE_SAVE = 'popup4phone_form_submit_before_save';
	const FORM_SUBMIT_AFTER_SAVE = 'popup4phone_form_submit_after_save';
	const FORM_POST_REQUEST_VALIDATE = 'popup4phone_form_post_request_validate';
};

class Popup4Phone_Actions
{
	const POPUP_SHOWN_AUTO = 'popup4phone_popup_shown_auto';
	const FOOTER_BEFORE = 'popup4phone_footer_before';
	const FOOTER_AFTER = 'popup4phone_footer_after';
	const FORM_GENERAL_FIELDS_AFTER = 'popup4phone_form_general_fields_after';
}

include 'includes/_autoload.php';

new Popup4Phone_Main();
