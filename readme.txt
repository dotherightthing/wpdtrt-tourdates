=== DTRT Tour Dates ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: cycle-touring, travel
Requires at least: 5.3.3
Tested up to: 5.3.3
Requires PHP: 7.2.15
Stable tag: 1.2.8
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

== Frequently Asked Questions ==

See [WordPress Usage](README.md#wordpress-usage).

== Screenshots ==

1. The caption for ./assets/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./assets/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 1.2.8 =
* [520d3e1] Update wpdtrt-plugin-boilerplate from 1.7.15 to 1.7.16
* [af4f521] Remove redundant classes

= 1.2.7 =
* [ac8032e] Docs
* [348063f] Update wpdtrt-npm-scripts to 0.3.30
* [d9335c5] Update dependencies
* [6e28c08] Update wpdtrt-scss
* [2373576] Update wpdtrt-plugin-boilerplate from 1.7.14 to 1.7.15
* [46627b7] Update wpdtrt-plugin-boilerplate from 1.7.13 to 1.7.14
* [cf9c3c2] Update wpdtrt-plugin-boilerplate from 1.7.12 to 1.7.13
* [d257559] Fix documented path to CSS variables
* [9a0178f] Add placeholders for string replacements
* [7cf8a4c] Load boilerplate JS, as it is not compiled by the boilerplate

= 1.2.6 =
* [dfe3ddb] Update wpdtrt-plugin-boilerplate from 1.7.9 to 1.7.12
* [c2307d0] Move styles to wpdtrt-scss
* [33a3f46] Lint PHP
* [7a7a418] Move styles to wpdtrt-scss
* [fa83a4d] Ignore files sources from wpdtrt-npm-scripts

= 1.2.5 =
* [9e24a19] Update dependencies, incl wpdtrt-plugin-boilerplate from 1.7.8 to 1.7.9 (fixes #33)

= 1.2.4 =
* [4c04a4e] Update dependencies, incl wpdtrt-plugin-boilerplate from 1.7.7 to 1.7.8 (fixes #33)

= 1.2.3 =
* [7833cb9] Update dependencies, incl wpdtrt-plugin-boilerplate from 1.7.6 to 1.7.7 to use Composer v1
* [21afe2b] Print styles
* [a0dc1d1] Update wpdtrt-plugin-boilerplate from 1.7.5 to 1.7.6 to fix saving of admin field values

= 1.2.2 =
* Use CSS variables, compile CSS variables to separate file
* Update wpdtrt-npm-scripts to fix release
* Update wpdtrt-plugin-boilerplate to 1.7.5 to support CSS variables

= 1.2.1 =
* Accessibly label navigation bar
* Update required WP and PHP versions

= 1.2.0 =
* Bump version

= 1.1.8 =
* Disable content filters in entry-summary portion derived from page content, to prevent injection of wpdtrt-gallery__section (https://github.com/dotherightthing/wpdtrt-gallery/issues/96)
* Add defaults for instance options (https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/43)
* Log and comment out failing tests
* Fix assert argument order
* filter_post_title_add_day has been replaced by wpdtrt_dbth_filter_post_title_add_day
* Update wpdtrt-plugin-boilerplate to 1.7.0
* Remove line breaks from summary
* Use BEM, remove redundant selectors and elements
* Fix/ignore linting errors
* Update Composer dependencies
* Use BEM, CSS variables, move theme-specific styling to wpdtrt-dbth
* Optimise breakpoints
* Replace Gulp build scripts with wpdtrt-npm-scripts
* If the tour description doesn't end in appropriate punctuation, end it with a fullstop and a space.
* Replace Travis with Github Actions

= 1.1.7 =
* Update wpdtrt-plugin-boilerplate from 1.5.5 to 1.5.6
* Sync with generator-wpdtrt-plugin-boilerplate (0.8.2 to 0.8.3)

= 1.1.6 =
* Don't use Content ID if it is an empty string

= 1.1.5 =
* Documentation

= 1.1.4 =
* Add a Content ID field to tour admin pages, to embed a content page below the description
* Redirect `/content-foo` pages to `/` (via `.htaccess`)
* Update Composer and NPM dependencies
* Fix casing of Composer dependency
* Travis: Update Node version to match local dev
* Travis: Start MySQL as this no longer happens automatically
* Housekeeping and minor text fixes

= 1.1.3 =
* Add links to India tour pages.

= 1.1.2 =
* Nest SCSS, add support for Title: Subtitle, adjust title padding
* Add location to page H1 after day number, adjust padding
* Relocate H1 title manipulations to wpdtrt-dbth
* Update wpdtrt-plugin-boilerplate from 1.5.3 to 1.5.5
* Update other dependencies
* Documentation

= 1.1.1 =
* Update wpdtrt-plugin-boilerplate from 1.5.0 to 1.5.3
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.2

= 1.1.0 =
* Update wpdtrt-plugin-boilerplate to 1.4.39 to 1.5.0
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.0

= 1.0.16 =
* Update wpdtrt-plugin-boilerplate from 1.4.38 to 1.4.39
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
