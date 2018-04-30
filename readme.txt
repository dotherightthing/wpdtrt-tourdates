
=== DTRT Tour Dates ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: cycle-touring, travel,
Requires at least: 4.8.2
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 1.0.7
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

= 1.0.7 =
* Migrate Bower & NPM to Yarn
* Update Node from 6.11.2 to 8.11.1
* Add messages required by shortcode demo
* Add SCSS partials for project-specific extends and variables
* Change tag badge to release badge
* Fix default .pot file
* Document dependencies
* Update wpdtrt-plugin

= 1.0.6 =
* Use environmental variables in build

= 1.0.5 =
* Use Private Packagist

= 1.0.0 =
* Use wpdtrt-plugin

= 0.1 =
* Initial version

== Upgrade Notice ==

= 0.1 =
* Initial release
