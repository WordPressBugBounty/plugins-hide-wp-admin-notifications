=== Hide WP Admin Notifications ===
Contributors: yodaofwp
Tags: hide, notifications, admin, dashboard
Requires at least: 5.2
Tested up to: 7.0
Requires PHP: 7.0
Stable tag: 0.2.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Hides WordPress admin notices until you choose to show them again from Settings.

== Description ==
Hide WP Admin Notifications keeps the WordPress admin area cleaner by hiding dashboard notices until you decide to show them again.

The plugin adds a single setting under `Settings > Hide WP Admin Notifications`. If you enable notices, they appear again. If you disable them, they stay hidden. Your saved preference is kept during plugin updates.

Features:
* Hides WordPress admin notices by default.
* Lets you turn notices back on from a simple settings page.
* Preserves the saved setting when the plugin is updated.
* Uses a WordPress-native admin interface.

== Installation ==
1. Upload the `hide-wp-admin-notifications` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the 'Settings' menu and click on 'Hide WP Admin Notifications' to configure the plugin.

== Frequently Asked Questions ==
= How do I enable dashboard notices temporarily? =
Go to `Settings > Hide WP Admin Notifications` and check the box to show admin notices again.

== Screenshots ==
1. The settings page of the Hide WP Admin Notifications plugin.

== Changelog ==

= 0.2.3 =
* Confirmed compatibility with WordPress 7.0.
* Fixed the settings checkbox so disabling notices saves correctly after they were previously enabled.
* Keeps the settings saved notice in the standard WordPress admin position.
* Kept the existing notice preference unchanged during updates.
* Refreshed the settings screen and WordPress.org icon/banner assets.

= 0.2.2 =
* Confirmed compatibility with WordPress 6.9.
* Improved security by properly escaping output in the settings page.
* Minor internal cleanup while preserving existing user settings.

= 0.2.1 =
* Confirmed compatibility with WordPress 6.8.

= 0.2 =
* Updated compatibility to WordPress 6.7.
* Ensured user settings are retained during updates.

= 0.1 =
* Initial release.
