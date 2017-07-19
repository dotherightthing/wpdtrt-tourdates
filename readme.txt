
=== WPDTRT Elapsed Day ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: boilerplate, placeholder
Requires at least: 4.8
Tested up to: 4.8
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a sequential day count from a posts publish date relative to a date ranged category.

== Description ==

Creates a sequential day count from a posts publish date relative to a date ranged category.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-elapsedday` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin

== Frequently Asked Questions ==

= How do I use the widget? =

One or more widgets can be displayed within one or more sidebars:

1. Locate the widget: Appearance > Widgets > *WPDTRT Elapsed Day Widget*
2. Drag and drop the widget into one of your sidebars
3. Add a *Title*
4. Specify *Number of blocks to display*
5. Toggle *Link to enlargement?*

= How do I use the shortcode? =

One or more shortcodes can be used within the content editor:

* Specify *Number of blocks to display* - `number`
* Toggle *Link to enlargement?* - `enlargement` (`yes` | `no`)

```
[wpdtrt_elapsedday_blocks number="2" enlargement="yes"]

[wpdtrt_elapsedday_blocks number="4" enlargement="no"]
```

= How do I use the template tag? =

One or more template tags can be used within your `.php` templates:

* Specify *Number of blocks to display* - `number`
* Toggle *Link to enlargement?* - `enlargement` (`yes` | `no`)

```
<?php
    do_shortcode( '[wpdtrt_elapsedday_blocks number="2" enlargement="yes"]' );
?>

<?php
    do_shortcode( '[wpdtrt_elapsedday_blocks number="4" enlargement="no"]' );
?>
```

== Screenshots ==

1. The caption for ./assets/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./assets/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.1 =
* Initial version

== Upgrade Notice ==

= 0.1 =
* Initial release
