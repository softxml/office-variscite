=== Slugs Manager: Delete Old Permalinks from WordPress Database ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: slugs manager, old slugs, regenerate slugs
Requires at least: 3.5.1
Tested up to: 6.4
Stable tag: 2.7.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Plugin helps you manage slugs (permalinks) in WordPress, for example, remove old slugs from database.

== Description ==

Plugin **removes old slugs** (permalinks) from database.

To remove old slugs, go to "Tools > Slugs Manager > Old Slugs" and click "Remove all old slugs" or "Remove selected old slugs" button.

Plugin is based on this [code snippet](https://wpcodebook.com/snippets/delete-posts-old-slugs-from-database-in-wordpress/).

### &#127942; Premium Version ###

[Slugs Manager: Delete Old Permalinks from WordPress Database Pro](https://wpfactory.com/item/slugs-manager-wordpress-plugin/) allows you to:

* Set old slugs to be cleared **periodically** (every minute, hourly, twice daily, daily, weekly).
* Set old slugs to be cleared **automatically**, when post is saved.
* **Regenerate slugs** from title for all posts.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Visit [plugin page](https://wpfactory.com/item/slugs-manager-wordpress-plugin/).

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. All options will be automatically added "Tools > Slugs Manager" page.

== Frequently Asked Questions ==

= Do I need to keep the plugin enabled once I run its tools (e.g., removed old slugs)? =

No, you can disable the plugin.

== Screenshots ==

1. Tools > Slugs Manager > Old Slugs.

== Changelog ==

= 2.7.0 - 31/01/2024 =
* Dev - Old Slugs - User permissions check and nonce added.
* Dev - Automatic Clean Ups - User permissions check and nonce added.
* Dev - Regenerate Slugs - User permissions check and nonce added.
* Dev - Flush Rewrite Rules - Nonce added.
* Dev - Code refactoring.
* Plugin contributors updated.

= 2.6.7 - 26/11/2023 =
* Plugin contributors updated.

= 2.6.6 - 26/11/2023 =
* Tested up to: 6.4.
* Plugin name updated.

= 2.6.5 - 06/11/2023 =
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.

= 2.6.4 - 26/09/2023 =
* Tested up to: 6.3.

= 2.6.3 - 18/06/2023 =
* Tested up to: 6.2.

= 2.6.2 - 09/11/2022 =
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 2.6.1 - 13/04/2022 =
* Tested up to: 5.9.

= 2.6.0 - 27/06/2021 =
* Dev - Regenerate Slugs - "Post types" option added.
* Dev - Remove Old Slugs - Link added to the "Post Title" column.
* Dev - Code refactoring.

= 2.5.1 - 05/05/2021 =
* Dev - Extra Tools - "Flush rewrite rules" tool added.

= 2.5.0 - 29/03/2021 =
* Dev - Plugin renamed (was "Remove Old Slugs").
* Dev - Remove Old Slugs - It is now possible to delete only certain old slugs (i.e., vs all at once).
* Dev - Localisation - `load_plugin_textdomain()` function moved to the `init` hook.
* Dev - Admin settings restyled; descriptions updated.
* Dev - Code refactoring.
* Tested up to: 5.7.

= 2.4.1 - 12/08/2020 =
* Tested up to: 5.5.

= 2.4.0 - 03/12/2019 =
* Dev - Major code refactoring.
* Dev - Admin settings restyled.
* Dev - Sanitizing all input now.
* Dev - Escaping all output now.
* Tested up to: 5.3.

= 2.3.0 - 18/10/2019 =
* Dev - "Regenerate Slugs" section added.

= 2.2.1 - 13/07/2019 =
* Tested up to: 5.2.
* Plugin URI updated.

= 2.2.0 - 31/05/2018 =
* Dev - Pro - Old slugs clean up on save post.
* Dev - Pro - Scheduled old slugs clean up.

= 2.1.0 - 05/08/2017 =
* Dev - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Dev - Minor code refactoring and clean up.
* Dev - Donate link updated.

= 2.0.1 - 25/10/2016 =
* Dev - Language (POT) file added.

= 2.0.0 - 25/10/2016 =
* Dev - Major code refactoring.
* Dev - WordPress Multisite support added.
* Dev - Language (translations) support added.
* Dev - Plugin renamed.

= 1.0.1 =
* Refresh link added.
* Minor bug fixed.

= 1.0.0 =
* Initial Release
