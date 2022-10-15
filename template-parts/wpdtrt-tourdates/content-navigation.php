<?php
/**
 * The template part for displaying the next/previous post navigation
 *
 * @link        http://dotherightthing.co.nz
 * @since       1.0.0
 * @package     WPDTRT_Tourdates
 * @todo        TourdatesTest\test_shortcodes
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
$page_position = null;
$posttype      = null;

// Access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/.
extract( $options, EXTR_IF_EXISTS );

global $post;
$post_id = $post->ID;

// Logic.
$previous  = $plugin->render_navigation_link( 'previous', $posttype );
$next      = $plugin->render_navigation_link( 'next', $posttype );
$daynumber = $plugin->get_post_daynumber( $post_id );

$page_position_id     = '';
$page_position_string = '';

if ( $page_position ) {
	$page_position_id     = '-' . $page_position;
	$page_position_string = ' (' . $page_position . ')';
}

// WordPress widget options (widget, not shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<div class="wpdtrt-tourdates-navigation">
	<nav aria-labelledby="wpdtrt-tourdates-navigation__title<?php echo $page_position_id; ?>">
		<h2 class="says" id="wpdtrt-tourdates-navigation__title<?php echo $page_position_id; ?>">Tour diary menu<?php echo $page_position_string; ?></h2>
		<ul>
			<li class="wpdtrt-tourdates-navigation--previous">
				<?php echo $previous; ?>
			</li>
			<li class="wpdtrt-tourdates-navigation__current">
				<strong class="wpdtrt-tourdates-navigation__text">
					<span class="says">Current page: Day <?php echo $daynumber; ?></span>
					<span class="wpdtrt-tourdates-icon-directions_bike"></span>
				</strong>
			</li>
			<li class="wpdtrt-tourdates-navigation--next">
				<?php echo $next; ?>
			</li>
		</ul>
	</nav>
</div>
<!-- stack-navigation -->

<?php
// Output widget customisations (not output with shortcode).
echo $after_widget;
