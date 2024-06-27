=== Redirect 404 Error Page to Homepage or Custom Page with Logs ===
Contributors: wpvibes
Tags: 404 error, 404 page, redirect, redirect 404, redirection, 404, 301, 302, SEO, permalink, page not found, homepage, server error
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.4
Stable tag: 1.8.8
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Redirect the 404 error page to the homepage or any other page with logs. Supports permanent (301), temporary (302) redirects & not found (404). Super easy to set up & use.

== Description ==
Easily redirect WordPress 404 error pages to the homepage or any other page. The plugin supports permanent redirects (HTTP response status code 301) and temporary redirects (HTTP response status code 302). The plugin will redirect 404 error pages to the homepage when you activate it by using the 301 permanent redirects. 

To configure it open **`Dashboard >> Settings >> General Settings and scroll down to the bottom.`**

**Note** Permalinks have to be enabled in **`Dashboard >> Settings >> Permalinks`** in order for the 404 Redirect to work properly.

## New Feature
- 404 errors can be logged with this plugin so you can examine what pages may be missing.

Redirect 404 Error Page is very useful for themes with an ugly, uninformative 404 server error page that can't be changed without missing code and set to still return 404. Simply create a new custom 404 error page and activate the plugin - problem solved!

It's also beneficial in situations when a lot of content has been removed from the site. For those situations, create a new page explaining what happened and simply configure Redirect 404 to use that page.

It is an ideal plugin for missing page redirection.

== Screenshots ==
1. Redirect 404 Error Page Admin Page Screenshot

== Installation ==
Follow the standard procedure:
1. Upload the unzipped plugin folder to the **`/wp-content/plugins/`** directory or install the plugin from **`Dashboard >> Plugins >> Add New.`**
2. Activate the plugin through the Plugins menu.
3. Head to **`Dashboard >> Settings >> Redirect 404 Settings`** to configure it.

== Frequently Asked Questions ==
= 404 Error Pages are not being redirected =
 Please activate the plugin and enable the permalinks. If the issue still persists, then [Visit the Support Forum](https://wordpress.org/support/plugin/redirect-404-error-page-to-homepage-or-custom-page).


== Changelog ==

= 1.8.8 = 
* Improved sql queries for better security. 

= 1.8.7 =
* Fix: Delete log entries functionality

= 1.8.6 =
* Update libraries

= 1.8.5 =
* Change ASTRA generated title for 404 page to the custom page title

= 1.8.4 =
* add buy me a coffee donation

= 1.8.3 =
* update library

= 1.8.2 =
* fix plugin deletion process

= 1.8.1 =
* update donation library

= 1.8.0 =
* remove Freemius library
* add donate information

= 1.7.9 =
* Library update
* Security Update
* Test on 5.8

= 1.7.8 =
* Library update
* improved escaping
* test on 5.7

= 1.7.7 =
* Library update

= 1.7.6 =
* Remove link

= 1.7.5 (2019/11/13) =
* 5.3 update
* settings update

= 1.7.4 (2019/07/04) =
* Upgrade Freemius library to 2.3.0

= 1.7.3 (2019/04/03) =
* Change to 404 option to ensure that 301 is not sent before 404

= 1.7.2 (2019/02/28) =
* Minor improvement

= 1.7.1 (2019/02/25) =
* Minor improvement

= 1.7 (2018/12/07) =
* Minor tweak to admin to cater to the 5.0 block editor

= 1.6 (2018/09/06) =
* Added capturing template 404 redirects, where plugins like Mailpoet handle redirects directly to the template.

= 1.5 (2018/09/01) =
* Added logging capability

= 1.4 (2018/06/01) =
* Changed to keep admin redirects, e.g., admin/login, etc

= 1.3 (2018/08/01) =
* Transferred to Fullworks for future development
* Changed permalink warning to admin notice
* Made language translatable
* Made pages selectable
* Added reset to default to settings
* Added Freemius opt-in
* Added 404 redirect type for custom 404 pages

= 1.2 (2018/07/20) =
* Added 301/302 redirect type option
* reworked readme.txt

= 1.1 (2018/05/09) =
* WebFactory took over development

= 1.0 (2016/05/27) =
* Initial release