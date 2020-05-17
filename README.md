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

1. Assign inclusive **Date Range** (start & end dates) to a category (the hierarchical Tour taxonomy) (e.g. `01.01.2017 - 10.04.2017`)
2. Display **Current/Elapsed Day** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `#32`).
3. Display **Total Duration** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `100`)
4. Display **Relative Duration** on a post, when it is assigned to the tour and its publish date is within the Date Range (e.g. `32%`)
5. Display **Relative Day Duration** on a sub-category in a hierarchical archive page (Tour taxonomy) (e.g. `Days #1-#32`)
6. Display **Relative Date Duration** on a sub-category in a hierarchical archive page (Tour taxonomy) (e.g. `January to February 2017`)

### Notes

1. This is relative to the Total Duration, not the published duration. This prevents mangling of the stats when content is being published retrospectively.

### Shortcodes & Template Tags

#### previous/next arrow navigation

* Shortcode: `[wpdtrt_tourdates_navigation posttype="tourdiaries" taxonomy="tours"]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_navigation posttype="tourdiaries" taxonomy="tours"]' );`

#### the elapsed day number

* Shortcode: `[wpdtrt_tourdates_daynumber]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_daynumber]' );`

#### the total number of days in a period

* Shortcode: `[wpdtrt_tourdates_daytotal]`
* Template Tag: `echo do_shortcode( '[wpdtrt_tourdates_daytotal]' );`
