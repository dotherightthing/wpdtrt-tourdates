<?php
/**
 * The template part for displaying the tour summary
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
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
?>

<?php
	// Logic
	$taxonomy = $plugin->get_the_taxonomy( $term_id );
	$summary = '';

	// get the term with the passed ID, rather than the parent page term
	$term = get_term_by( 'id', $term_id, $taxonomy );
	$tour_slug = $term->slug;

	$term_type = $plugin->get_meta_term_type( $term_id );

 	if ( $term_type === 'tour' ) {
 		if ( shortcode_exists('wpdtrt_tourdates_shortcode_tourlengthdays') ) {
 			$tour_length_days = do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $term_id .'" text_before="My tour lasted " text_after=" days"]' );
    	}

    	$tour_leg_count = $plugin->get_term_leg_count( $term_id, ' and traversed ', ' countries' );
    }
    else if ( $term_type === 'tour_leg' ) {
 		if ( shortcode_exists('wpdtrt_tourdates_shortcode_tourlengthdays') ) {
    		$tour_length_days = do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $term_id .'" text_before="This tour leg lasted " text_after=" days"]' );
    	}

		$tour_leg_count = '';
    }

	if ( isset( $tour_length_days, $tour_leg_count ) ):
?>

<div class="entry-summary-wrapper">
	<div class="entry-date"></div>
	<div class="entry-summary">
		<p>
			<?php echo $tour_length_days . $tour_leg_count . '.'; ?>
  		</p>
	</div>
	<?php if ( $tour_slug === 'east-asia' ): ?>
		<p>There's a lot to write-up! Please follow <a href="http://www.facebook.com/dontbelievethehypenz">my Facebook page</a> or subscribe to the <a href="/feed/?post_type=tourdiaries">Tour Diary RSS feed</a>, to be notified of new entries as they are added.</p>
	<?php endif; ?>
</div>

<?php
	endif;

	// output widget customisations (not output with shortcode)
	echo $after_widget;
?>
