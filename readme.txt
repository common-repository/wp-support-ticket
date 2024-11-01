=== WP Support Ticket ===
Contributors: avimegladon
Donate link: https://www.aviplugins.com/donate/
Tags: support, support ticket, user support, wordpress support, reply, ticket, help, user help, ticket support, support email, email notification, email, email support, support plugin, user support plugin, plugin support, user, agent, support agent, customer care, care user, customer support
Requires at least: 2.0.2
Tested up to: 6.2.2
Stable tag: 3.4.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

User support plugin. Registered users will be able to create/ reply support tickets. Admin can reply on the tickets from admin panel.

== Description ==

Use this plugin to create your very own customer support system. Let customers of your site to generate a support ticket whenever they require assistance from you, and you will be able to solve their issues by posting replies to the tickets. The customers will get email notifications when you post a reply message to the ticket.

* Registered Users / Customers can create support tickets.
* Customers can search tickets from tickets list.
* Admin can reply on support tickets from admin panel.
* Files can be attached to the reply messages. Supported files that can be uploaded are jpg, jpeg, png, gif, doc, docx, pdf, zip ( Multiple files can be attached in the <a href="https://www.aviplugins.com/wp-support-pro/" target="_blank">PRO</a> version with option to select which type of files can be uploaded. )
* Ticket can be marked as Open / Closed / Resolved from admin panel. If a customer post a reply in the Resolved ticket the status of the ticket will be changed to Open.

= Usage Shortcodes =
* [create_support] to display cretate new suppor ticket form.
* [ticket] to display tickets created by logged in user.
* [ticket_search] to display ticket search form.

= WP Support (PRO) =
There is a PRO version of this plugin that supports additional features. You can get it <a href="https://www.aviplugins.com/wp-support-pro/" target="_blank">here</a> in <strong>USD 2.00</strong>

* Administrators can post replies by logging in to admin panel. Additionally a new user role <strong>Agent</strong> will be created. Administraton can assign this role to the support agents so that they can login to the admin panel and reply to the support tickets. Agents will have limited access (<strong>Only Support Ticket Section</strong>) of the admin panel.
* Multiple files can be attached with new tickets and ticket replies.
* Create <strong>Custom Fields</strong> in the ticket form.
* Create Support Ticket (Widget).
* Support Ticket List (Widget). To list recent/ specific tickets in the widget area.
* Additional file types are supported in the PRO version. Set supported file types from admin panel.
* Manage email contents from admin panel. Emails that are fired when a new ticket is created or admin posts a reply.
* Option to list all support tickets submitted by users. So that visitors can view tickets and replies.
* Set custom name for the administrator.
* Advanced ticket search.

> Post your plugin related queries at <a href="https://www.aviplugins.com/support.php" target="_blank">https://www.aviplugins.com/support.php</a>


== Installation ==

1. Upload `wp-support-ticket.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to `Settings-> WP Support`, and set some options. It's really easy.
4. Use shortcodes or functions to display uploaded files in frontend.

Registered in users will be able to create support tickets.

1. Create a new page and put [create_support] Shortcode in that page. This will be the page from where users will be able to create a support ticket thread.

2. Create another page and put [ticket] Shortcode in that page. The tickets created by the registered users will be listed in this page. From this page users will be able to reply to already created tickets.

3. Admin can View/ Reply to a ticket from admin panel.

4. Emails will be sent to respective Admin or Users each time a ticket is created or replied.


= Translations =

* German translation is provided by Luca Passoni

If you want to translate the plugin in your language please translate the sample .PO file and mail me the the file at demoforafo@gmail.com and I will include that in the language file. Sample .PO file can be downloaded from <a href="https://www.aviplugins.com/language-sample/wp-support-ticket-da_DK.po">here</a>

== Frequently Asked Questions ==

= When I click on a Ticket I get 404 page not found error =
1. Make sure the "Ticket Shortcode Page" is selected at Settings -> WP Support. Please select the page where you have entered the [ticket] shortcode. 

2. Please go to Settings -> Permalinks and Save the Permalink Settings.

For other plugin related queries please email me at demoforafo@gmail.com or Post your queries here https://www.aviplugins.com/support.php

== Screenshots ==

1. Support tickets listing in admin panel.
2. Ticket edit page admin panel.
3. Create support ticket front end.
4. Search ticket front end.
5. Support tickets listing front end.
6. Settings view 1
7. Settings view 2
8. Admin Panel Notification
9. Frontend Notification

== Changelog ==

= 3.4.7 =
* Bug fixed.

= 3.4.6 =
* Improvements.

= 3.4.5 =
* Bug fixed.

= 3.4.4 =
* Different classes added on ticket reply DIVs.

= 3.4.3 =
* Dismiss all notifications bug fixed.

= 3.4.2 =
* Bug fixed.

= 3.4.1 =
* Bug fixed.

= 3.4.0 =
* New ticket getting scheduled status bug fixed and some other bug fixes.

= 3.3.2 =
* Some design modifications are made in plugin settings section.

= 3.3.1 =
* Plugin code structure updated. Now the plugin will work much faster.

= 3.3.0 =
* Plugin code updated. jQuery validation plugin added. Notificatio message display updated.

= 3.2.9 =
* Sort by last post date. This will work after a new reply is added on the old tickets. New tickets will not have any issues.

= 3.2.8 =
* Bug fixed.

= 3.2.7 =
* Bug fixed.

= 3.2.6 =
* loopback issue fixed.

= 3.2.5 =
* Message display updated.

= 3.2.4 =
* Bug fixed.

= 3.2.3 =
* Ticket Notification Message added in admin panel and front-end. Notification messages will be displayed when admin logs-in to admin panel or users logs-in to their account. The notification messages will be displayed throughout the site and will not be removed untill the new message is viewed. There is also an option to Clear All the message at once. Please Note: For this to work, once the plugin is updated please deactivate and reactivate the plugin once.

= 3.2.3 =
* In this update Notification messages will be displayed after login in admin panel as well as in the front-end. Notification messages will be displayed when new ticket is created or new reply is posted.

= 3.2.2 =
* Plugin code updated.

= 3.2.1 =
* Plugin code updated. Plugin Dashboard structure updated.

= 3.2.0 =
* Plugin code updated.

= 3.1.1 =
* Plugin CSS style updated for simpler display.

= 3.1.0 =
* Plugin code modifications.

= 3.0.0 =
* Code updated with some security modifications.

= 2.2.3 =
* aviplugins.com news feed dashboard widget added.

= 2.2.2 =
* Admin settings page updated.

= 2.2.1 =
* Some bug fixed.

= 2.2.0 =
* File upload bug fixed in admin panel.

= 2.1.0 =
* The plugin is now multilingual. Fixed ticket details link bug.

= 2.0.0 =
* Notice message bug fixed.

= 1.1.1 =
* Little modificatios done in ticket reply page (admin panel). 

= 1.0.2 =
* Email notifications are added.

= 1.0.1 =
* This is the first release.

== Upgrade Notice ==

= 1.0 =
I will update this plugin when ever it is required.
