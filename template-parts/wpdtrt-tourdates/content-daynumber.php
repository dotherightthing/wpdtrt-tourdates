<?php
/**
 * The template part for displaying the daynumber
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @see 		TourdatesTest\test_shortcodes
 */
?>

<?php
	// Predeclare variables

	// Internal WordPress arguments available to widgets
	// This allows us to use the same template for shortcodes and front-end widgets
	$before_widget = null; // register_sidebar
	$before_title = null; // register_sidebar
	$title = null;
	$after_title = null; // register_sidebar
	$after_widget = null; // register_sidebar

	// shortcode options

	// access to plugin
	$plugin = null;

	// Options: display $args + widget $instance settings + access to plugin
	$options = get_query_var( 'options' );

	// Overwrite variables from array values
	// @link http://kb.network.dan/php/wordpress/extract/
	extract( $options, EXTR_IF_EXISTS );

	// WordPress widget options (widget, not shortcode)
	echo $before_widget;
	echo $before_title . $title . $after_title;

	global $post;
	$post_id = $post->ID;
	$daynumber = $plugin->get_post_daynumber($post_id);
	echo $daynumber;

	// output widget customisations (not output with shortcode)
	echo $after_widget;
?>
