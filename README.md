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

## WordPress Installation and Usage

Please read the [WordPress readme.txt](readme.txt).
