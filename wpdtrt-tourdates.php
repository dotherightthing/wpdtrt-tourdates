<?php
/**
 * Plugin Name:  DTRT Tour Dates
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-tourdates
 * Description:  Organise bike touring content by tour dates.
 * Version:      1.0.13
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-tourdates
 * Domain Path:  /languages
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
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

if ( ! defined( 'WPDTRT_TOURDATES_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * WP provides get_plugin_data(), but it only works within WP Admin,
	 * so we define a constant instead.
	 *
	 * @see $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
	 * @see https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
	 */
	define( 'WPDTRT_TOURDATES_VERSION', '1.0.13' );
}

if ( ! defined( 'WPDTRT_TOURDATES_PATH' ) ) {
	/**
	 * Plugin directory filesystem path.
	 *
	 * @param string $file
	 * @return The filesystem directory path (with trailing slash)
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_TOURDATES_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPDTRT_TOURDATES_URL' ) ) {
	/**
	 * Plugin directory URL path.
	 *
	 * @param string $file
	 * @return The URL (with trailing slash)
	 * @see https://codex.wordpress.org/Function_Reference/plugin_dir_url
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_TOURDATES_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * ===== Dependencies =====
 */

/**
 * Determine the correct path, from wpdtrt-plugin-boilerplate to the PSR-4 autoloader
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/51
 */
if ( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
	define( 'WPDTRT_PLUGIN_CHILD', true );
}

/**
 * Determine the correct path, from wpdtrt-foobar to the PSR-4 autoloader
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/104
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-WordPress-plugin-dependencies
 */
if ( defined( 'WPDTRT_TOURDATES_TEST_DEPENDENCY' ) ) {
	$project_root_path = realpath( __DIR__ . '/../../..' ) . '/';
} else {
	$project_root_path = '';
}

require_once $project_root_path . 'vendor/autoload.php';

// sub classes, not loaded via PSR-4.
// remove the includes you don't need, edit the files you do need.
require_once WPDTRT_TOURDATES_PATH . 'src/class-wpdtrt-tourdates-plugin.php';
require_once WPDTRT_TOURDATES_PATH . 'src/class-wpdtrt-tourdates-shortcode.php';
require_once WPDTRT_TOURDATES_PATH . 'src/class-wpdtrt-tourdates-taxonomy.php';

// log & trace helpers.
global $debug;
$debug = new DoTheRightThing\WPDebug\Debug;

/**
 * ===== WordPress Integration =====
 *
 * Comment out the actions you don't need.
 *
 * Notes:
 *  Default priority is 10. A higher priority runs later.
 *  register_activation_hook() is run before any of the provided hooks.
 *
 * @see https://developer.wordpress.org/plugins/hooks/actions/#priority
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook.
 */
register_activation_hook( dirname( __FILE__ ), 'wpdtrt_tourdates_helper_activate' );

add_action( 'init', 'wpdtrt_tourdates_plugin_init', 0 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_daynumber_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_daytotal_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_navigation_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_summary_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_tourlengthdays_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_shortcode_thumbnail_init', 100 );
add_action( 'init', 'wpdtrt_tourdates_taxonomy_init', 100 );

register_deactivation_hook( dirname( __FILE__ ), 'wpdtrt_tourdates_helper_deactivate' );

/**
 * ===== Plugin config =====
 */

/**
 * Register functions to be run when the plugin is activated.
 *
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_tourdates_helper_activate() {
	flush_rewrite_rules();
}

/**
 * Register functions to be run when the plugin is deactivated.
 * (WordPress 2.0+)
 *
 * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_tourdates_helper_deactivate() {
	flush_rewrite_rules();
}

/**
 * Plugin initialisaton
 *
 * We call init before widget_init so that the plugin object properties are available to it.
 * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
 * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
 * widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
 *
 * @see https://wp-mix.com/wordpress-widget_init-not-working/
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 * @todo Add a constructor function to WPDTRT_Tourdates_Plugin, to explain the options array
 */
function wpdtrt_tourdates_plugin_init() {
	// pass object reference between classes via global
	// because the object does not exist until the WordPress init action has fired
	global $wpdtrt_tourdates_plugin;

	/**
	 * Global options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-global-options Options: Adding global options
	 */
	$plugin_options = array();

	/**
	 * Shortcode or Widget options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-shortcode-or-widget-options Options: Adding shortcode or widget options
	 */
	$instance_options = array(
		'term_id'     => array(
			'type'  => 'number',
			'label' => esc_html__( 'Term ID', 'wpdtrt-tourdates' ),
		),
		'text_before' => array(
			'type'  => 'text',
			'label' => esc_html__( 'Text before', 'wpdtrt-tourdates' ),
		),
		'text_after'  => array(
			'type'  => 'text',
			'label' => esc_html__( 'Text after', 'wpdtrt-tourdates' ),
		),
		'posttype'    => array(
			'type'  => 'text',
			'label' => esc_html__( 'Custom Post Type', 'wpdtrt-tourdates' ),
			'tip'   => esc_html__( 'Used for the previous/next navigation bar', 'wpdtrt-tourdates' ),
		),
		/*
		'posttype' => array(
			'type'  => 'text',
			'label' => esc_html__('Post type', 'wpdtrt-tourdates'),
		),
		'taxonomy' => array(
			'type'  => 'text',
			'label' => esc_html__('Taxonomy', 'wpdtrt-tourdates'),
			'tip'   => 'tours'
		)
		*/
	);

	/**
	 * Plugin dependencies
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-WordPress-plugin-dependencies Options: Adding WordPress plugin dependencies
	 */
	$plugin_dependencies = array(
		'name'     => 'Ambrosite Next/Previous Post Link Plus',
		'slug'     => 'ambrosite-nextprevious-post-link-plus',
		'required' => true,
	);

	/**
	 *  UI Messages
	 */
	$ui_messages = array(
		'demo_data_description'       => __( 'This demo was generated from the following data', 'wpdtrt-tourdates' ),
		'demo_data_displayed_length'  => __( 'results displayed', 'wpdtrt-tourdates' ),
		'demo_data_length'            => __( 'results', 'wpdtrt-tourdates' ),
		'demo_data_title'             => __( 'Demo data', 'wpdtrt-tourdates' ),
		'demo_date_last_updated'      => __( 'Data last updated', 'wpdtrt-tourdates' ),
		'demo_sample_title'           => __( 'Demo sample', 'wpdtrt-tourdates' ),
		'demo_shortcode_title'        => __( 'Demo shortcode', 'wpdtrt-tourdates' ),
		'insufficient_permissions'    => __( 'Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-tourdates' ),
		'no_options_form_description' => __( 'There aren\'t currently any options.', 'wpdtrt-tourdates' ),
		'noscript_warning'            => __( 'Please enable JavaScript', 'wpdtrt-tourdates' ),
		'options_form_description'    => __( 'Please enter your preferences.', 'wpdtrt-tourdates' ),
		'options_form_submit'         => __( 'Save Changes', 'wpdtrt-tourdates' ),
		'options_form_title'          => __( 'General Settings', 'wpdtrt-tourdates' ),
		'loading'                     => __( 'Loading latest data...', 'wpdtrt-tourdates' ),
		'success'                     => __( 'settings successfully updated', 'wpdtrt-tourdates' ),
	);

	/**
	 * Demo shortcode
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Settings-page:-Adding-a-demo-shortcode Settings page: Adding a demo shortcode
	 */
	$demo_shortcode_params = array();

	/**
	 * Plugin configuration
	 */
	$wpdtrt_tourdates_plugin = new WPDTRT_Tourdates_Plugin(
		array(
			'path'                  => WPDTRT_TOURDATES_PATH,
			'url'                   => WPDTRT_TOURDATES_URL,
			'version'               => WPDTRT_TOURDATES_VERSION,
			'prefix'                => 'wpdtrt_tourdates',
			'slug'                  => 'wpdtrt-tourdates',
			'menu_title'            => __( 'Tour Dates', 'wpdtrt-tourdates' ),
			'settings_title'        => __( 'Settings', 'wpdtrt-tourdates' ),
			'developer_prefix'      => 'DTRT',
			'messages'              => $ui_messages,
			'plugin_options'        => $plugin_options,
			'instance_options'      => $instance_options,
			'plugin_dependencies'   => $plugin_dependencies,
			'demo_shortcode_params' => $demo_shortcode_params,
		)
	);
}

/**
 * ===== Rewrite config =====
 */

/**
 * Register Rewrite
 */
function wpdtrt_tourdates_rewrite_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_rewrite = new WPDTRT_Tourdates_Rewrite(
		array()
	);
}

/**
 * ===== Shortcode config =====
 */

function wpdtrt_tourdates_shortcode_daynumber_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_daynumber = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_daynumber',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'daynumber',
			'selected_instance_options' => array(),
		)
	);
}

function wpdtrt_tourdates_shortcode_daytotal_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_daytotal = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_daytotal',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'daytotal',
			'selected_instance_options' => array(),
		)
	);
}



function wpdtrt_tourdates_shortcode_navigation_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_navigation = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_navigation',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'navigation',
			'selected_instance_options' => array(
				'posttype',
			),
		)
	);
}

function wpdtrt_tourdates_shortcode_summary_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_summary = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_summary',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'summary',
			'selected_instance_options' => array(
				'term_id',
			),
		)
	);
}

function wpdtrt_tourdates_shortcode_thumbnail_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_thumbnail = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_thumbnail',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'thumbnail',
			'selected_instance_options' => array(
				'term_id',
			),
		)
	);
}

function wpdtrt_tourdates_shortcode_tourlengthdays_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_shortcode_tourlengthdays = new WPDTRT_Tourdates_Shortcode(
		array(
			'name'                      => 'wpdtrt_tourdates_shortcode_tourlengthdays',
			'plugin'                    => $wpdtrt_tourdates_plugin,
			'template'                  => 'tourlengthdays',
			'selected_instance_options' => array(
				'term_id',
				'text_before',
				'text_after',
			),
		)
	);
}

/**
 * ===== Taxonomy config =====
 */

/**
 * Register Taxonomy
 *
 * @return object Taxonomy/
 */
function wpdtrt_tourdates_taxonomy_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_taxonomy = new WPDTRT_Tourdates_Taxonomy(
		array(
			'name'                       => 'wpdtrt_tourdates_taxonomy_tour',
			'singular_name'              => __( 'Tour', 'wpdtrt-tourdates' ),
			'menu_name'                  => __( 'Tours', 'wpdtrt-tourdates' ),
			'all_items'                  => __( 'All Tours', 'wpdtrt-tourdates' ),
			'add_new_item'               => __( 'Add New Tour', 'wpdtrt-tourdates' ),
			'edit_item'                  => __( 'Edit Tour', 'wpdtrt-tourdates' ),
			'view_item'                  => __( 'View Tour', 'wpdtrt-tourdates' ),
			'update_item'                => __( 'Update Tour', 'wpdtrt-tourdates' ),
			'new_item_name'              => __( 'New Tour Name', 'wpdtrt-tourdates' ),
			'parent_item'                => __( 'Parent tour', 'wpdtrt-tourdates' ),
			'parent_item_colon'          => __( 'Parent tour:', 'wpdtrt-tourdates' ),
			'search_items'               => __( 'Search tours', 'wpdtrt-tourdates' ),
			'popular_items'              => __( 'Popular tours', 'wpdtrt-tourdates' ),
			'separate_items_with_commas' => __( 'Separate tours with commas', 'wpdtrt-tourdates' ),
			'add_or_remove_items'        => __( 'Add or remove tours', 'wpdtrt-tourdates' ),
			'choose_from_most_used'      => __( 'Choose from the most used tours', 'wpdtrt-tourdates' ),
			'not_found'                  => __( 'No tours found', 'wpdtrt-tourdates' ),
			'plugin'                     => $wpdtrt_tourdates_plugin,
			'selected_instance_options'  => array(),
			'taxonomy_options'           => array(
				'term_type'    => array(
					'type'              => 'select',
					'label'             => esc_html__( 'Term type', 'wpdtrt-tourdates' ),
					'admin_table'       => true,
					'admin_table_label' => esc_html__( 'Type', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => true,
					'options'           => array(
						'region'   => array(
							'text' => __( 'Region', 'wpdtrt-tourdates' ),
						),
						'tour'     => array(
							'text' => __( 'Tour', 'wpdtrt-tourdates' ),
						),
						'tour_leg' => array(
							'text' => __( 'Tour Leg', 'wpdtrt-tourdates' ),
						),
					),
				),
				'start_date'   => array(
					'type'              => 'text',
					'label'             => esc_html__( 'Start date', 'wpdtrt-tourdates' ),
					'admin_table'       => true,
					'admin_table_label' => esc_html__( 'Start', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => true,
					'tip'               => 'YYYY-M-D',
					'todo_condition'    => 'term_type !== "region"',
				),
				'end_date'     => array(
					'type'              => 'text',
					'label'             => esc_html__( 'End date', 'wpdtrt-tourdates' ),
					'admin_table'       => true,
					'admin_table_label' => esc_html__( 'End', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => true,
					'tip'               => 'YYYY-M-D',
					'todo_condition'    => 'term_type !== "region"',
				),
				'first_visit'  => array(
					'type'              => 'checkbox',
					'label'             => esc_html__( 'First visit on tour', 'wpdtrt-tourdates' ),
					'admin_table'       => false,
					'admin_table_label' => esc_html__( 'First', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => false,
					'tip'               => 'Used in country traversal counts.',
					'todo_condition'    => 'term_type == "tour_leg"',
				),
				'leg_count'    => array(
					'type'              => 'number',
					'label'             => esc_html__( 'Number of unique tour legs', 'wpdtrt-tourdates' ),
					'admin_table'       => false,
					'admin_table_label' => esc_html__( 'Legs', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => false,
					'tip'               => 'Used in country traversal counts.',
					'todo_condition'    => 'term_type == "tour"',
				),
				'thumbnail_id' => array(
					'type'              => 'number',
					'label'             => esc_html__( 'Thumbnail ID', 'wpdtrt-tourdates' ),
					'admin_table'       => false,
					'admin_table_label' => esc_html__( 'Thumb', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => false,
					'tip'               => 'Media &gt; Library',
					'todo_condition'    => 'term_type == "tour"',
				),
				'disabled'     => array(
					'type'              => 'checkbox',
					'label'             => esc_html__( 'Disabled', 'wpdtrt-tourdates' ),
					'admin_table'       => true,
					'admin_table_label' => esc_html__( 'X', 'wpdtrt-tourdates' ),
					'admin_table_sort'  => true,
					'tip'               => 'Disables terms which have no posts yet',
					'todo_condition'    => 'term_type == "tour_leg"',
				),
			),
			'labels'                     => array(
				'slug'                 => 'tours',
				'singular'             => __( 'Tour', 'wpdtrt-tourdates' ),
				'plural'               => __( 'Tours', 'wpdtrt-tourdates' ),
				'description'          => __( 'Multiday rides', 'wpdtrt-tourdates' ),
				'posttype'             => 'tourdiaries',
				'taxonomy_menu_name'   => __( 'Tours', 'wpdtrt-tourdates' ),
				'taxonomy_name'        => __( 'Tours', 'taxonomy general name' ),
				'taxonomy_single_name' => _x( 'Tour', 'taxonomy singular name' ),
			),
		)
	);

	// return a reference for unit testing.
	return $wpdtrt_tourdates_taxonomy;
}

/**
 * ===== Widget config =====
 */

/**
 * Register a WordPress widget, passing in an instance of our custom widget class
 * The plugin does not require registration, but widgets and shortcodes do.
 * Note: widget_init fires before init, unless init has a priority of 0
 *
 * @uses        ../../../../wp-includes/widgets.php
 * @see         https://codex.wordpress.org/Function_Reference/register_widget#Example
 * @see         https://wp-mix.com/wordpress-widget_init-not-working/
 * @see         https://codex.wordpress.org/Plugin_API/Action_Reference
 * @uses        https://github.com/dotherightthing/wpdtrt/tree/master/library/sidebars.php
 * @todo        Add form field parameters to the options array
 * @todo        Investigate the 'classname' option
 */
function wpdtrt_tourdates_widget_init() {

	global $wpdtrt_tourdates_plugin;

	$wpdtrt_tourdates_widget = new WPDTRT_Tourdates_Widget(
		array()
	);

	register_widget( $wpdtrt_tourdates_widget );
}
