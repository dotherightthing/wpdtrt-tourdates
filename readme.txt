
=== DTRT Tour Dates ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: cycle-touring, travel,
Requires at least: 4.8
Tested up to: 4.8
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display the relative position of content within an assigned date-range.

== Description ==

Display the relative position of content within an assigned date-range.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-tourdates` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
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

* Shortcode: `[wpdtrt_tourdates_navigation]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_navigation]' );`

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

= 0.1 =
* Initial version

== Upgrade Notice ==

= 0.1 =
* Initial release
