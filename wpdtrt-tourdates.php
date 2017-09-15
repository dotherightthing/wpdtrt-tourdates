<?php
/*
Plugin Name:  DTRT Tour Dates
Plugin URI:   https://github.com/dotherightthing/wpdtrt-soundcloud-pages
Description:  Display the relative position of content within an assigned date-range.
Version:      0.1.0
Author:       Dan Smith
Author URI:   http://dotherightthing.co.nz
License:      GPLv2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpdtrt-tourdates
Domain Path:  /languages
*/

/**
 * Constants
 * WordPress makes use of the following constants when determining the path to the content and plugin directories.
 * These should not be used directly by plugins or themes, but are listed here for completeness.
 * WP_CONTENT_DIR  // no trailing slash, full paths only
 * WP_CONTENT_URL  // full url
 * WP_PLUGIN_DIR  // full path, no trailing slash
 * WP_PLUGIN_URL  // full url, no trailing slash
 *
 * WordPress provides several functions for easily determining where a given file or directory lives.
 * Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 * or using the WordPress internal constants.
 * plugins_url()
 * plugin_dir_url()
 * plugin_dir_path()
 * plugin_basename()
 *
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

/**
 * Plugin version
 * WP provides get_plugin_data(), but it only works within WP Admin,
 * so we define a constant instead.
 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
 * @link https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
 */
if( ! defined( 'WPDTRT_TOURDATES_VERSION' ) ) {
  define( 'WPDTRT_TOURDATES_VERSION', '0.1' );
}

/**
 * plugin_dir_path
 * @param string $file
 * @return The filesystem directory path (with trailing slash)
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 */
if( ! defined( 'WPDTRT_TOURDATES_PATH' ) ) {
  define( 'WPDTRT_TOURDATES_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * The version information is only available within WP Admin
 * @param string $file
 * @return The URL (with trailing slash)
 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 */
if( ! defined( 'WPDTRT_TOURDATES_URL' ) ) {
  define( 'WPDTRT_TOURDATES_URL', plugin_dir_url( __FILE__ ) );
}


/**
 * Store all of our plugin options in an array
 * So that we only use have to consume one row in the WP Options table
 * WordPress automatically serializes this (into a string)
 * because MySQL does not support arrays as a data type
 */
  $wpdtrt_tourdates_options = array();

/**
 * Include plugin logic
 */

  // API data
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-acf.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-getters.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-navigation.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-permalink-placeholders.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-setters.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-taxonomies.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-titles.php');

  // Views
  //require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-options.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-widget.php');

  // Theming
  //require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-html.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-css.php');
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-js.php');

  // Shortcode
  require_once(WPDTRT_TOURDATES_PATH . 'app/wpdtrt-tourdates-shortcodes.php');

?>
