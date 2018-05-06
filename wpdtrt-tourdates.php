<?php
/**
 * Plugin Name:  DTRT Tour Dates
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-tourdates
 * Description:  Organise bike touring content by tour dates.
 * Version:      1.0.8
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-tourdates
 * Domain Path:  /languages
 */

require_once plugin_dir_path( __FILE__ ) . "vendor/autoload.php";

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
  * Determine the correct path to the autoloader
  * @see https://github.com/dotherightthing/wpdtrt-plugin/issues/51
  */
if( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
  define( 'WPDTRT_PLUGIN_CHILD', true );
}

if( ! defined( 'WPDTRT_TOURDATES_VERSION' ) ) {
/**
 * Plugin version.
 *
 * WP provides get_plugin_data(), but it only works within WP Admin,
 * so we define a constant instead.
 *
 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
 * @link https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
 *
 * @since     1.0.0
 * @version   1.0.0
 */
  define( 'WPDTRT_TOURDATES_VERSION', '1.0.9' );
}

if( ! defined( 'WPDTRT_TOURDATES_PATH' ) ) {
/**
 * Plugin directory filesystem path.
 *
 * @param string $file
 * @return The filesystem directory path (with trailing slash)
 *
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @since     1.0.0
 * @version   1.0.0
 */
  define( 'WPDTRT_TOURDATES_PATH', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'WPDTRT_TOURDATES_URL' ) ) {
/**
 * Plugin directory URL path.
 *
 * @param string $file
 * @return The URL (with trailing slash)
 *
 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @since     1.0.0
 * @version   1.0.0
 */
  define( 'WPDTRT_TOURDATES_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Include plugin logic
 *
 * @since     1.0.0
 * @version   1.0.0
 */

  // base class
  // redundant, but includes the composer-generated autoload file if not already included
  require_once(WPDTRT_TOURDATES_PATH . 'vendor/dotherightthing/wpdtrt-plugin/index.php');

  // sub classes
  require_once(WPDTRT_TOURDATES_PATH . 'src/class-wpdtrt-tourdates-plugin.php');

  // log & trace helpers
  global $debug;
  $debug = new DoTheRightThing\WPDebug\Debug;

  /**
   * Plugin initialisaton
   *
   * We call init before widget_init so that the plugin object properties are available to it.
   * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
   * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
   * └─ widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
   *
   * @see https://wp-mix.com/wordpress-widget_init-not-working/
   * @see https://codex.wordpress.org/Plugin_API/Action_Reference
   * @todo Add a constructor function to WPDTRT_Blocks_Plugin, to explain the options array
   */
  function wpdtrt_tourdates_init() {
    // pass object reference between classes via global
    // because the object does not exist until the WordPress init action has fired
    global $wpdtrt_tourdates_plugin;

    /**
     * Admin settings
     *
     * Changed to $taxonomy_options and retained for legacy support - may not be reqd
     */
    $plugin_options = array();

    /**
     * All options available to Widgets and Shortcodes
     */
    $instance_options = array(
      'term_id' => array(
        'type' => 'number',
        'label' => esc_html__('Term ID', 'wpdtrt-tourdates'),
      ),
      'text_before' => array(
        'type' => 'text',
        'label' => esc_html__('Text before', 'wpdtrt-tourdates'),
      ),
      'text_after' => array(
        'type' => 'text',
        'label' => esc_html__('Text after', 'wpdtrt-tourdates'),
      ),
      'posttype' => array(
        'type' => 'text',
        'label' => esc_html__('Custom Post Type', 'wpdtrt-tourdates'),
        'tip' => esc_html__('Used for the previous/next navigation bar', 'wpdtrt-tourdates')
      ),
      /*
      'posttype' => array(
        'type' => 'text',
        'label' => esc_html__('Post type', 'wpdtrt-tourdates'),
      ),
      'taxonomy' => array(
        'type' => 'text',
        'label' => esc_html__('Taxonomy', 'wpdtrt-tourdates'),
        'tip' => 'tours'
      )
      */
    );

    $wpdtrt_tourdates_plugin = new WPDTRT_TourDates_Plugin(
      array(
        'url' => WPDTRT_TOURDATES_URL,
        'prefix' => 'wpdtrt_tourdates',
        'slug' => 'wpdtrt-tourdates',
        'menu_title' => __('Tour Dates', 'wpdtrt-tourdates'),
        'settings_title' => __('Settings', 'wpdtrt-tourdates'),
        'developer_prefix' => 'DTRT',
        'path' => WPDTRT_TOURDATES_PATH,
        'messages' => array(
          'loading' => __('Loading latest data...', 'wpdtrt-tourdates'),
          'success' => __('settings successfully updated', 'wpdtrt-tourdates'),
          'insufficient_permissions' => __('Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-tourdates'),
          'options_form_title' => __('General Settings', 'wpdtrt-tourdates'),
          'options_form_description' => __('Please enter your preferences.', 'wpdtrt-tourdates'),
          'no_options_form_description' => __('There aren\'t currently any options.', 'wpdtrt-tourdates'),
          'options_form_submit' => __('Save Changes', 'wpdtrt-tourdates'),
          'noscript_warning' => __('Please enable JavaScript', 'wpdtrt-tourdates'),
          'post_terms_missing' => __('Tour Dates plugin error: Please assign all three "Tour" levels to $post id = N', 'wpdtrt-tourdates'),
          'demo_sample_title' => __('Demo sample', 'wpdtrt-tourdates'),
          'demo_data_title' => __('Demo data', 'wpdtrt-tourdates'),
          'demo_shortcode_title' => __('Demo shortcode', 'wpdtrt-tourdates'),
          'demo_data_description' => __('This demo was generated from the following data', 'wpdtrt-tourdates'),
          'demo_date_last_updated' => __('Data last updated', 'wpdtrt-tourdates'),
          'demo_data_length' => __('results', 'wpdtrt-tourdates'),
          'demo_data_displayed_length' => __('results displayed', 'wpdtrt-tourdates'),
        ),
        'plugin_options' => $plugin_options,
        'instance_options' => $instance_options,
        'version' => WPDTRT_TOURDATES_VERSION,
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_init', 0 );

  /**
   * Register Tour Taxonomy
   * @return object Taxonomy
   */
  function wpdtrt_tourdates_taxonomy_tour_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_taxonomy_tour = new DoTheRightThing\WPPlugin\r_1_4_6\Taxonomy(
      array(
        'name' => 'wpdtrt_tourdates_taxonomy_tour',
        'plugin' => $wpdtrt_tourdates_plugin,
        'selected_instance_options' => array(),
        'taxonomy_options' => array(
          'term_type' => array(
            'type' => 'select',
            'label' => esc_html__('Term type', 'wpdtrt-tourdates'),
            'admin_table' => true,
            'admin_table_label' => esc_html__('Type', 'wpdtrt-tourdates'),
            'admin_table_sort' => true,
            'options' => array(
              'region' => array(
                'text' => __('Region', 'wpdtrt-tourdates')
              ),
              'tour' => array(
                'text' => __('Tour', 'wpdtrt-tourdates')
              ),
              'tour_leg' => array(
                'text' => __('Tour Leg', 'wpdtrt-tourdates')
              ),
            ),
          ),
          'start_date' => array(
            'type' => 'text',
            'label' => esc_html__('Start date', 'wpdtrt-tourdates'),
            'admin_table' => true,
            'admin_table_label' => esc_html__('Start', 'wpdtrt-tourdates'),
            'admin_table_sort' => true,
            'tip' => 'YYYY-M-D',
            'todo_condition' => 'term_type !== "region"'
          ),
          'end_date' => array(
            'type' => 'text',
            'label' => esc_html__('End date', 'wpdtrt-tourdates'),
            'admin_table' => true,
            'admin_table_label' => esc_html__('End', 'wpdtrt-tourdates'),
            'admin_table_sort' => true,
            'tip' => 'YYYY-M-D',
            'todo_condition' => 'term_type !== "region"'
          ),
          'first_visit' => array(
            'type' => 'checkbox',
            'label' => esc_html__('First visit on tour', 'wpdtrt-tourdates'),
            'admin_table' => false,
            'admin_table_label' => esc_html__('First', 'wpdtrt-tourdates'),
            'admin_table_sort' => false,
            'tip' => 'Used in country traversal counts.',
            'todo_condition' => 'term_type == "tour_leg"'
          ),
          'leg_count' => array(
            'type' => 'number',
            'label' => esc_html__('Number of unique tour legs', 'wpdtrt-tourdates'),
            'admin_table' => false,
            'admin_table_label' => esc_html__('Legs', 'wpdtrt-tourdates'),
            'admin_table_sort' => false,
            'tip' => 'Used in country traversal counts.',
            'todo_condition' => 'term_type == "tour"'
          ),
          'thumbnail_id' => array(
            'type' => 'number',
            'label' => esc_html__('Thumbnail ID', 'wpdtrt-tourdates'),
            'admin_table' => false,
            'admin_table_label' => esc_html__('Thumb', 'wpdtrt-tourdates'),
            'admin_table_sort' => false,
            'tip' => 'Media &gt; Library',
            'todo_condition' => 'term_type == "tour"'
          ),
          'disabled' => array(
            'type' => 'checkbox',
            'label' => esc_html__('Disabled', 'wpdtrt-tourdates'),
            'admin_table' => true,
            'admin_table_label' => esc_html__('X', 'wpdtrt-tourdates'),
            'admin_table_sort' => true,
            'tip' => 'Disables terms which have no posts yet',
            'todo_condition' => 'term_type == "tour_leg"'
          ),
        ),
        'labels' => array(
          'slug' => 'tours',
          'singular' => __('Tour', 'wpdtrt-tourdates'),
          'plural' => __('Tours', 'wpdtrt-tourdates'),
          'description' => __('Multiday rides', 'wpdtrt-tourdates'),
          'posttype' => 'tourdiaries'
        ),
      )
    );

    // return a reference for unit testing
    return $wpdtrt_tourdates_taxonomy_tour;
  }

  add_action( 'init', 'wpdtrt_tourdates_taxonomy_tour_init', 100 );

  /**
   * Register Shortcode 1
   */
  function wpdtrt_tourdates_shortcode_navigation_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_navigation = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_navigation',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'navigation',
        'selected_instance_options' => array(
          'posttype'
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_navigation_init', 100 );

  /**
   * Register Shortcode 2
   */
  function wpdtrt_tourdates_shortcode_daynumber_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_daynumber = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_daynumber',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'daynumber',
        'selected_instance_options' => array()
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_daynumber_init', 100 );

  /**
   * Register Shortcode 3
   */
  function wpdtrt_tourdates_shortcode_daytotal_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_daytotal = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_daytotal',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'daytotal',
        'selected_instance_options' => array()
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_daytotal_init', 100 );

  /**
   * Register Shortcode 4
   */
  function wpdtrt_tourdates_shortcode_tourlengthdays_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_tourlengthdays = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_tourlengthdays',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'tourlengthdays',
        'selected_instance_options' => array(
          'term_id',
          'text_before',
          'text_after'
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_tourlengthdays_init', 100 );

  /**
   * Register Shortcode 5
   */
  function wpdtrt_tourdates_shortcode_thumbnail_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_thumbnail = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_thumbnail',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'thumbnail',
        'selected_instance_options' => array(
          'term_id',
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_thumbnail_init', 100 );

  /**
   * Register Shortcode 6
   */
  function wpdtrt_tourdates_shortcode_summary_init() {

    global $wpdtrt_tourdates_plugin;

    $wpdtrt_tourdates_shortcode_summary = new DoTheRightThing\WPPlugin\r_1_4_6\Shortcode(
      array(
        'name' => 'wpdtrt_tourdates_shortcode_summary',
        'plugin' => $wpdtrt_tourdates_plugin,
        'template' => 'summary',
        'selected_instance_options' => array(
          'term_id',
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_tourdates_shortcode_summary_init', 100 );

  /**
   * Register functions to be run when the plugin is activated.
   *
   * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
   *
   * @since     0.6.0
   * @version   1.0.0
   */
  function wpdtrt_tourdates_activate() {
    // $this->set_rewrite_rules()
    flush_rewrite_rules();
  }

  register_activation_hook(__FILE__, 'wpdtrt_tourdates_activate');

  /**
   * Register functions to be run when the plugin is deactivated.
   *
   * (WordPress 2.0+)
   *
   * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
   *
   * @since     0.6.0
   * @version   1.0.0
   */
  function wpdtrt_tourdates_deactivate() {
    flush_rewrite_rules();
  }

  register_deactivation_hook(__FILE__, 'wpdtrt_tourdates_deactivate');

?>
