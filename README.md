# ![Tour Dates Navigation](images/github-header.png)

# DTRT WP Tour Dates

[![GitHub release](https://img.shields.io/github/release/dotherightthing/wpdtrt-tourdates.svg)](https://github.com/dotherightthing/wpdtrt-tourdates/releases) [![Build Status](https://github.com/dotherightthing/wpdtrt-tourdates/workflows/Build%20and%20release%20if%20tagged/badge.svg)](https://github.com/dotherightthing/wpdtrt-tourdates/actions?query=workflow%3A%22Build+and+release+if+tagged%22) [![GitHub issues](https://img.shields.io/github/issues/dotherightthing/wpdtrt-tourdates.svg)](https://github.com/dotherightthing/wpdtrt-tourdates/issues)

Organise bike touring content by tour dates.

## Setup and Maintenance

Please read [DTRT WordPress Plugin Boilerplate: Workflows](https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Workflows).

### .htaccess

Please prefix the WordPress block in your `.htaccess` file with the following:

```
# wpdtrt-tourdates content partials
RewriteEngine On
RewriteRule "^(content-)+.*" "/" [L,R=301]
```

## WordPress Installation

Please read the [WordPress readme.txt](readme.txt).

## WordPress Usage

### Features

#### Tour taxonomy

`wpdtrt_tourdates_taxonomy_tour`

* Options (Term type):
   * `region` (e.g. *Asia*)
   * `tour` (e.g. *East Asia (2015)*)
   * `tour_leg` (e.g. *Japan*)
* `start_date` (e.g. *2015-09-02*)
* `end_date` (e.g. *2016-06-25*)
* `first_visit` (if `tour_leg` - Used in country traversal counts)
* `leg_count` (if `tour_leg` - Used in country traversal counts)
* `thumbnail_id` (if `tour`)
* `content_id` (Inserts the content page with this ID below the description on the tour page)
* `disabled` (if `tour_leg` - Disables terms which have no posts yet)

### Shortcodes, Template Tags and public functions

#### Day number (elapsed day number)

When a post is assigned to the tour and its publish date is within the Date Range:

* display **Current/Elapsed Day** on day maps and in daily statistics (e.g. `#32`).
* display **Relative Duration** on a post (e.g. `32%`).

Note: Day number is relative to the Total Duration, not the published duration. This prevents mangling of the stats when content is being published retrospectively.

Usage:

* Shortcode: `[wpdtrt_tourdates_shortcode_daynumber]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_daynumber]' );`

The public function is used when generating links and titles:

* `$wpdtrt_tourdates_plugin->get_post_daynumber()`

#### Day total (total number of days in a period)

When a post is assigned to the tour and its publish date is within the Date Range:

* display **Total Duration** in daily statistics (e.g. `100`)
* display **Relative Duration** on a post (in conjunction with Day Number) (e.g. `32%`)

Usage:

* Shortcode: `[wpdtrt_tourdates_shortcode_daytotal]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_daytotal]' );`

#### Navigation

Navigate previous/next post via arrow icons.

* Shortcode: `[wpdtrt_tourdates_shortcode_navigation posttype="tourdiaries" taxonomy="tours"]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_navigation posttype="tourdiaries" taxonomy="tours"]' );`

#### Start and end month

Display **Relative Date Duration** on a sub-category in a hierarchical archive page (Tour taxonomy) (e.g. `January to February 2017`)

The public function is used when generating `tour` summaries:

* `$wpdtrt_tourdates_plugin->get_term_start_month()`
* `$wpdtrt_tourdates_plugin->get_term_end_month()`

#### Summary

* Shortcode: `[wpdtrt_tourdates_shortcode_summary]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_summary]' );`

#### Term IDs sorted by start date

The public function is used on sitemaps and tour landing pages:

* `$wpdtrt_tourdates_plugin->helper_order_tour_terms_by_date()`

#### Terms sorted by hierarchical order

Not used.

* `$wpdtrt_tourdates_plugin->helper_order_tour_terms_by_hierarchy()`

#### Term type

* `$wpdtrt_tourdates_plugin->get_meta_term_type()`

#### Thumbnail

Display a custom CSS background image on `region`, `tour` and `tour_leg` landing pages.

* Shortcode: `[wpdtrt_tourdates_shortcode_thumbnail]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_thumbnail]' );`

The public function is used to get the custom image ID in order to generate different sizes:

* `$wpdtrt_tourdates_plugin->get_meta_thumbnail_id()`

#### Tour disabled state

The public function is used to determine whether the tour should be shown as active or disabled:

* `$wpdtrt_tourdates_plugin->get_meta_term_disabled()`

#### Tour length in days

Display **Relative Day Duration** on a sub-category in a hierarchical archive page (e.g. `tour_leg`) (e.g. `Days #1-#32`)

* Shortcode: `[wpdtrt_tourdates_shortcode_tourlengthdays]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays]' );`

### Styling

Core CSS properties may be overwritten by changing the variable values in your theme stylesheet.

See `scss/variables/_css.scss`.
