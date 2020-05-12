<?php
/**
 * The template part for displaying the tour summary
 *
 * @link        http://dotherightthing.co.nz
 * @since       1.0.0
 * @package     WPDTRT_Tourdates
 * @see         TourdatesTest\test_shortcodes
 */

// Predeclare variables
//
// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets.
$before_widget = null; // register_sidebar.
$before_title  = null; // register_sidebar.
$title         = null;
$after_title   = null; // register_sidebar.
$after_widget  = null; // register_sidebar.

// Shortcode options.
$term_id = null;

// Access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/.
extract( $options, EXTR_IF_EXISTS );

// WordPress widget options (widget, not shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;

// Logic.
$taxonomy   = $plugin->get_the_taxonomy( $term_id );
$summary    = '';
$content_id = $plugin->get_meta_content_id( $term_id );
$content    = '';

// Get the term with the passed ID, rather than the parent page term.
$term             = get_term_by( 'id', $term_id, $taxonomy );
$tour_slug        = $term->slug;
$tour_description = $term->description;
$term_type        = $plugin->get_meta_term_type( $term_id );

if ( 'tour' === $term_type ) {

	if ( shortcode_exists( 'wpdtrt_tourdates_shortcode_tourlengthdays' ) ) {
		$tour_length_days = do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $term_id . '" text_before="This tour lasted " text_after=" days"]' );
	}

	$tour_leg_count = $plugin->get_term_leg_count( $term_id, ' and traversed ', ' countries' );

} elseif ( 'tour_leg' === $term_type ) {

	if ( shortcode_exists( 'wpdtrt_tourdates_shortcode_tourlengthdays' ) ) {
		$tour_length_days = do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $term_id . '" text_before="This leg of the tour lasted " text_after=" days"]' );
	}

	$tour_leg_count = '';
}

if ( isset( $tour_description ) ) {
	$summary .= $tour_description;

	// if the description doesn't end in appropriate punctuation
	// end it with a fullstop and a space.
	$last_letter = substr( trim( $tour_description ), -1 );
	preg_match( '/[.!;]/', $last_letter, $matches );

	if ( count( $matches ) < 1 ) {
		$summary .= '. ';
	}
}

if ( isset( $tour_description, $tour_length_days, $tour_leg_count ) ) {
	$summary .= '<br/>';
}

if ( isset( $tour_length_days, $tour_leg_count ) ) {
	$summary .= ( $tour_length_days . $tour_leg_count . '.' );
}

if ( isset( $content_id ) && ( '' !== $content_id ) ) {
	$post    = get_post( $content_id );
	$content = apply_filters( 'the_content', $post->post_content );
}
?>

<div class="entry-summary-wrapper">
	<div class="entry-summary">
		<p>
			<?php echo str_replace( '<br/>', '', $summary ); ?>
		</p>
	</div>
	<?php echo $content; ?>
</div>

<?php
// Output widget customisations (not output with shortcode).
echo $after_widget;
