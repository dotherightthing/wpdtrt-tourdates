<?php
/**
 * The template part for displaying the background tour thumbnail
 *
 * @link        http://dotherightthing.co.nz
 * @since       1.0.0
 * @see 		http://www.mikejohnsondesign.com/add-wordpress-featured-image-as-background-image/
 * @see 		https://wordpress.org/support/topic/insert-featured-image-in-div-style-background
 * @see 		https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
 *
 * @package     WPDTRT_Tourdates
 * @todo        TourdatesTest\test_shortcodes
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
	// todo add background/foreground option
	$term_id = null;

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

	// Logic
    $thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );
    $featured_image_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail', true, '' );

	if ( isset( $featured_image_src ) ):
?>

	<style id="style-post-<?php echo $term_id ? $term_id : ''; ?>">
		#post-<?php echo $term_id; ?> .stack--wrapper {
			background-image: url(<?php echo $featured_image_src[0]; ?> );
		}
	</style>

<?php
	endif;

	// output widget customisations (not output with shortcode)
	echo $after_widget;
?>
