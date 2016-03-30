=== Popup4Phone ===
Contributors: ivanweb
Tags: popup dialog, popover, callback, phone, contacts, google analytics, universal analytics, utm labels, lead source, referrer, widget, contact form
Requires at least: 4.0.1
Tested up to: 4.4
Stable tag: trunk
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html
Author URI: http://popup4phone.com/author
Plugin URI: http://popup4phone.com
Donate link: http://popup4phone.com/donate

Popup4Phone plugin allows you to get more leads. Phones of visitors collected by popup form / button. Also you can analyze ads efficiency.

== Description ==

> ** ===== >> [DEMO](http://popup4phone.com/?utm_source=repository&utm_medium=description&utm_content=readme&utm_campaign=readme) << ===== **

Translations: English, Russian, Deutsche (German), Dutch (Nederlands), Turkish (started)

Popup4Phone plugin allows you to receive more leads from your web-site.

Visitor of your site will see popup form (shown automatically or by click to popover button) where he/she can specify phone and submit form. You will receive email notification about new lead, also web-analytics data will be saved for future use, so you can analyze ads efficiency.

Comparing to other popup plugins, this plugin don't require using of email address field and allow to make leads database inside WordPress without any 3rd party services.

**Shortcodes:**
* [popup4phone_inline_form_no_popup] - inline form, lock auto popup
* [popup4phone_inline_form] - inline form, NOT lock auto popup
* [popup4phone_button_inline] - inline button for open form

**Features:**

* Fully translatable/editable front-end dialogs (form field labels, messages)
* Top front icon for open popup on each page
* Flexible settings for fields - email, message fields in the popup form can be hidden or visible
* Leads database inside WordPress without any 3rd party services
* Settings for popover button (offset, size, color)
* Automatic popup (can be disabled). Available settings for automatic popup: delay after load page and length of pause between repeat shows for same visitor
* Integration with Google Analytics so you can track what ads causes calls requests and improve your marketing
* You will know about new leads immediately by email
* Leads will be saved with traffic/behavior data - where lead come from (referrer), landing page, UTM labels, URL and title of page where form was submitted, visited pages on your site
* Shortcode for inline form - just insert [popup4phone_inline_form] in any page or post
* Export all leads as single file (in .csv format)
* You can specify custom javascript code for execute after submit (for integrate with other analytics / conversion tracking systems, etc.)


> ** Premium version advantages (http://popup4phone.com/premium/) **
> * Custom title per page (loaded from tag with some CSS selector specified in settings, e.g. &quot;.popup4phone-custom-title&quot;). For example, you have page about product A. You can add hidden block:<br><i> &lt;div class = 'popup4phone-custom-title'&gt;Do you have any questions about the product A?&lt;/div&gt;</i><br>And content of this block will be used as title<
> * Open popup dialog on the end of page scrolling
> * Custom CSS
> * Disable popup if element with some CSS selector (specified in settings too) present on the page. For example, if you want to hide Popup4Phone on WooCommerce checkout pages you can specify &quot;.form[name=checkout], .woocommerce-thankyou-order-received&quot; and Popup4Phone will not appear on these pages
> * Spam protection
> * Settings for auto show popup only once per IP
> * Open on click to any specified element by CSS selector. For example, dialog can be openeed by click by any element with class popup4phone-click-to-open
> Click to order: http://popup4phone.com/premium/ 

**Planned features (not implemented yet, please let me know if you interested in it)**

* SMS notifications for you about new leads and for sender (so he/she will sure know their request is received). It will use some SMS gateway
* Immediate connection (callback) between your phone and phone specified by user. It will use integation with cloud communications system / PBX
* Export leads in Excel (.XLS/.XLSX) formats
* Comments to leads
* Ability to integrate as Javascript code to other sites/domains (including non-WordPress)
* Something you want, but was not specified here?


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/popup4phone` directory, or install the plugin through the WordPress plugins screen directly.

2. Activate the plugin through the 'Plugins' screen in WordPress

3. Use the Popup4Phone->Settings menu to configure the plugin

4. In the settings you can configure whether you want to show popup automatically, enable/disable popover button and many other settings.

5. Also in the settings you can configure email - where to send notifications about new leads. I recommend to use some trigger emails service (like Mandrill) for better deliverability.

6. If you want to use inline form you may use these two shortcodes: [shortcode popup4phone_inline_form_no_popup] - show inline form and lock auto popup form, [shortcode popup4phone_inline_form] - show inline form, NOT lock auto popup form.

7. By default only users/roles with capability `manage_options` have access to backend of the plugin. If you want to configure access independently - you can use these capabilities: `popup4phone_edit_leads` (access to all backend pages except options) and  `popup4phone_manage_options` (access to options). For assigning these capabilities to roles you can use great plugin `Capability Manager Enhanced`

== Screenshots ==
1. Popover button for open form
2. Opened form
3. Leads list in the backend
4. Web-statistics per lead

== Changelog ==

= 1.2.0 =
- popover icon by SVG image
- responsive popup form on mobile screens
- added ability to use caption instead icon for popover button (including text/font-size settings)
- shortcode for inline insertion callback button
- multiple emails for notifications separated by comma
- filters / actions / js-events for customization
- fix version saving / dbDelta updates
- remove H2 tag from dialog title to avoid SEO problems

= 1.1.0 =
* added Dutch (Nederlands translation)
* fields labels / placeholder / presence / requiretment
* settings for notifications (subject, content)
* settings for submit button label
* settings for messages after/during form submission
* fix CSS conflicts
* added custom capabilites to access backend

= 1.0.11 =
* added settings for size/color for popover button
* fixed HTML content-type for email notifications (improving devilerability)
* default sort for leads - new leads first

= 1.0.10 =
* added setting for enable/disable popover button animation (bounce)

= 1.0.9 =
* fix styles loading for popover button
* German (Deutsch) translation is added

= 1.0.8 =
* fix leads deleting

= 1.0.7 =
* fix style for popover button
* added settings for popover button (right/bottom offset) in order to avoid overlapping with "Scroll to Top" button

= 1.0.6 =
* fix style for popover button
* fix shortocodes name in help page
* fix encoding on the web stat page

= 1.0.5 =
* fix styles (box-sizing for input fields)
* z-index for popover button is auto calculated for guarantee maximimum top position
* Russian translation is added
* Internal name of Google Analytics "enabled" option has been changed, option was renamed to "Send event", so please check this option in the settings after upgrading
* Fix for translations (now can be translated with Loco Translate, etc.)

= 1.0.4 =
Initial Commit

== Upgrade Notice ==

= 1.0.11 =
Added settings for size/color for popover button, fixed HTML content-type for email notifications (improving devilerability),

= 1.0.10 =
Added setting for enable/disable popover button animation (bounce)

= 1.0.8 =
Fix leads deleting

= 1.0.7 =
Fix style, added settings for popover button (right/bottom offset) in order to avoid overlapping with "Scroll to Top" button

= 1.0.5 =
Fix styles, translations loading, added Russian translation
