=== WP Activity Log for Gravity Forms ===
Contributors: WPWhiteSecurity
Plugin URI: https://wpactivitylog.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.html
Tags: activity log for Gravity Forms, WP Activity Log extension, activity logs
Requires at least: 5.0
Tested up to: 6.1.1
Stable tag: 1.2.3
Requires PHP: 7.2

Keep a log of changes your team do in the Gravity Forms plugin, forms, entries (leads) & more.

== Description ==

Website forms allow your website visitors to communicate with you and your team, buy products, subscribe to a service you are offering, become a member of your business and much more! Therefore it is critical that forms are always working and you know of any changes that happen to them.

To ensure this, ease troubleshooting, eliminate guesswork and improve user accountability, keep a log of the changes that your team and you do to the forms and the Gravity Forms plugin settings.

With this extension for the [WP Activity Log](https://wpactivitylog.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description) plugin, you keep a record of the changes that happen in your Gravity Forms plugin install, and you will know when someone creates, modifies or deletes a form, deletes an entry, and much more.

Refer to [activity log for Gravity Forms](https://wpactivitylog.com/extensions/gravity-forms-activity-log/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description) for more detailed information on this integration.

#### About WP Activity Log
[WP Activity Log](https://wpactivitylog.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description) is the most comprehensive real time activity log plugin for WordPress. It helps thousands administrators and security professionals keep an eye on what is happening on their websites and multisite networks.

WP Activity Log is also the most highly rated WordPress activity log plugin and have been featured on popular sites such as GoDaddy, ManageWP, Pagely, Shout Me Loud and WPKube.

### Getting started: activity logs for Gravity Forms

To keep a log of the changes that happen on your Gravity Forms plugin install, forms, entries and other plugin components simply:

1. Install the [WP Activity Log plugin](https://wpactivitylog.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description)
1. Install this extension from the section <i>Enable/disable events</i> > <i>Third party extensions</i>.

### With this extension you can keep a log of:

Below are some of the user and Gravity Forms plugin changes you keep a log of when you install this extension with the WP Activity Log plugin:

* Adds a new form
* Modifies, duplicates, renames or deletes a form
* Adds a new field in a form
* Modifies, or deletes a field from a form
* Deletes or modifies an entry (lead)
* Adds, enables, modifies or disable notifications in forms
* Someone submits a form (user and visitor (optional))
* Changes in the plugin's settings

Refer to the [activity logs event IDs for WPForms](https://wpactivitylog.com/support/kb/list-wordpress-activity-log-event-ids/#gravity-forms?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description)) for a complete list of the changes the plugin can keep a log of.

== Installation ==

=== Install this extension for Gravity Forms from within WP Activity Log (easiest method) ===

1. Navigate to the section <i>Enable/disable events</i> > <i>Third party extensions</i>.
1. Click <i>Install extension</i> under the Gravity Forms logo and extension description.

=== Install this extension from within WordPress ===

1. Ensure WP Activity Log is already installed.
1. Visit 'Plugins > Add New'.
1. Search for 'WP Activity Log extension for Gravity Forms'.
1. Install and activate the extension.

=== Install this extension manually ===

1. Ensure WP Activity Log is already installed.
1. Download the plugin and extract the files.
1. Upload the `activity-log-gravity-forms` folder to the `/wp-content/plugins/` folder on your website.
1. Activate the WP Activity Log extension for Gravity Forms plugin from the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Support and Documentation =
Please refer to our [Support & Documentation pages](https://wpactivitylog.com/support/kb/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WSAL&utm_content=plugin+repos+description) for all the technical information and support documentation on the WP Activity Log plugin.

== Screenshots ==

1. Forms, entries, notifications and other Gravity Forms plugin changes reported in the WordPress activity log.
1. The list of forms, notifications, entries and other Gravity Form plugin changes the WP Activity Log plugin can keep a log of.

== Changelog ==

= 1.2.3 (2023-03-23) =

* **Plugin improvements**
	* Support for WP Activity Log 4.5 (upcoming update).

= 1.2.2 (2022-11-17) =

* **Improvements**
	* Ensure compatability with current and upcoming [WP Activity Log](https://wpactivitylog.com) release.
	* Ensure correct positioning and filtering of items under the Enable/Disable Events view.

* **Bug fixes**
	* PHP Error reported during the creaction of a form confirmation.
  
= 1.2.1 (2022-06-14) =

* **Improvements**
	* Updated plugin inline with recent WP Activity Log Changes.
	* Improved coding standards.
	
* **Bug fixes**
	* PHP Error during changes to ‘background updates’ setting

= 1.2.0 (2022-03-24) =

Extensions: Release notes: [Yoast SEO, WPForms & Gravity Forms activity log extension updates](https://wpactivitylog.com/extensions-march-2022-update/)

* **New event IDs:**
	* 5714: Added / delete note from entry (lead).
	* 5717: Entry (lead) was modified.
	* 5718: Exported entries from a form.
	* 5719: Imported / exported a form.

* **Bug fixes:**
	* Fixed: Links to forms do not work when both Gravity Forms and WPForms extensions are installed at the same time.
	* Fixed: PHP fatal error reported when a forum is submitted.
	* Fixed: Event ID 5710 not reported when an entry is starred / unstarred.

= 1.1 (2021-04-28) =

Release notes: [Major update of all the activity log extensions](https://wpactivitylog.com/core-update-extensions-2-0/)

* **Improvements**
	* Events now use the latest event format used in [WP Activity Log](https://wpactivitylog.com).
	* Updated the core to the latest improved core (better performance and more efficient).
	* Extension can now be activated only at network level.
	* Extension name added to plugin's admin notices.
	
* **Bug fixes**
	* Fixed broken backward compatability issue.

= 1.0 =

	*Initial release.
