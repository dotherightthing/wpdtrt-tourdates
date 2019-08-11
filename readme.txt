=== DTRT Tour Dates ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: cycle-touring, travel
Requires at least: 4.8.2
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 1.1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Organise bike touring content by tour dates.

== Description ==

Organise bike touring content by tour dates.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-tourdates` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->DTRT Tour Dates screen to configure the plugin
4. Settings->Permalinks: Post name

A day number is assigned to each post as it is (re)published.

== Features ==

1. Assign inclusive **Date Range** (start & end dates) to a category (the hierarchical Tour taxonomy) (e.g. `01.01.2017 - 10.04.2017`)
2. Display **Current/Elapsed Day** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `#32`).
3. Display **Total Duration** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `100`)
4. Display **Relative Duration** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `32%`)
5. Display **Relative Day Duration** on a sub-category in a hierarchical archive page (Tour taxonomy) (e.g. `Days #1-#32`)
6. Display **Relative Date Duration** on a sub-category in a hierarchical archive page (Tour taxonomy) (e.g. `January to February 2017`)

= Notes =

1. This is relative to the Total Duration, not the published duration. This prevents mangling of the stats when content is being published retrospectively.

== Shortcodes & Template Tags ==

= previous/next arrow navigation =

* Shortcode: `[wpdtrt_tourdates_navigation posttype="tourdiaries" taxonomy="tours"]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_navigation posttype="tourdiaries" taxonomy="tours"]' );`

= the elapsed day number =

* Shortcode: `[wpdtrt_tourdates_daynumber]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_daynumber]' );`

= the total number of days in a period =

* Shortcode: `[wpdtrt_tourdates_daytotal]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_daytotal]' );`

== Screenshots ==

1. The caption for ./assets/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./assets/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 1.1.6 =
* Don't use Content ID if it is an empty string

= 1.1.5 =
Documentation

= 1.1.4 =
* Add a Content ID field to tour admin pages, to embed a content page below the description
* Redirect `/content-foo` pages to `/` (via `.htaccess`)
* Update Composer and NPM dependencies
* Fix casing of Composer dependency
* Travis: Update Node version to match local dev
* Travis: start MySQL as this no longer happens automatically
* Housekeeping and minor text fixes

= 1.1.3 =
* Add links to India tour pages.

= 1.1.2 =
* Nest SCSS, add support for Title: Subtitle, adjust title padding
* Add location to page H1 after day number, adjust padding
* Relocate H1 title manipulations to wpdtrt-dbth
* Update wpdtrt-plugin-boilerplate to 1.5.3
* Update other dependencies
* Documentation

= 1.1.1 =
* Update wpdtrt-plugin-boilerplate to 1.5.3
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.2

= 1.1.0 =
* Update wpdtrt-plugin-boilerplate to 1.5.0
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.0

= 1.0.16 =
* Update wpdtrt-plugin-boilerplate to 1.4.39
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.27

= 1.0.15 =
* Update wpdtrt-plugin-boilerplate to 1.4.38
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.25

= 1.0.14 =
* Update wpdtrt-plugin-boilerplate to 1.4.25
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.20 

= 1.0.13 =
* Update wpdtrt-plugin to wpdtrt-plugin-boilerplate
* Update wpdtrt-plugin-boilerplate to 1.4.24
* Fix package name
* Fixes for PHPCS
* Remove redundant widget
* Fix taxonomy name, class and i18n strings
* Migrate icons from wpdtrt-dbth
* Prefer stable versions but allow dev versions
* Fix config test
* Don't run PHP Unit directly from Travis
* Disable missing terms test

= 1.0.12 =
* Update wpdtrt-plugin to 1.4.15

= 1.0.11 =
* Update wpdtrt-plugin to 1.4.14

= 1.0.10 =
* Update WordPress plugin header format so that gulp bump can update the version
* Demote ambrosite/nextprevious-post-link-plus to require-dev (test dependency)
* Fix path to autoloader when loaded as a test dependency

= 1.0.9 =
* Include release number in wpdtrt-plugin namespaces
* Update wpdtrt-plugin to 1.4.6

= 1.0.8 =
* Update wpdtrt-plugin to 1.3.6

= 1.0.7 =
* Migrate Bower & NPM to Yarn
* Update Node from 6.11.2 to 8.11.1
* Add messages required by shortcode demo
* Add SCSS partials for project-specific extends and variables
* Change tag badge to release badge
* Fix default .pot file
* Document dependencies
* Update wpdtrt-plugin to 1.3.1

= 1.0.6 =
* Use environmental variables in build

= 1.0.5 =
* Use Private Packagist

= 1.0.4 =
* Update dependencies

= 1.0.3 =
* Update dependencies

= 1.0.2 =
* Fix constant which informs include path
* Add project logo to README

= 1.0.1 =
* Update dependencies
* Update documentation
* Update API key to troubleshoot Github authentication
* Replace `--pluginrole` CLI arg with directory check
* Properly exclude `node_modules` from release zip

= 1.0.0 =
* Use wpdtrt-plugin

= 0.1 =
* Initial version

== Upgrade Notice ==

= 0.1 =
* Initial release
