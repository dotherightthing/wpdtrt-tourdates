<?php
/**
 * Getters
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

/**
 * Elapsed day
 * Display the relative position of content within an assigned date-range.
 * Used for:
 *  Permalinks? - no, manually set
 *  Page title
 *  Prev/Next navigation
 *  Post Page Heading 1 (post day number)
 *  Post Archive Heading 2 (category day range)
 *  Post Archive Heading 3 (post day number)
 *  Post Archive anchor nav (category day duration)
 * @example 10/7/2017-13/7/2017 = Day 1, Day 2, Day 3, Day 4
 */

/**
 * Get the term ID
 * get_query_var('term') gets the parent page, not the included partial
 * @return string $term_id
 * @todo prefix term_id with plugin prefix
 */
function wpdtrt_tourdates_get_partial_term_id() {

  $term_id = get_query_var( 'term_id' ); // manually set in taxonomy template

  return $term_id;
}

/**
 * Get the term
 * get_query_var('term') gets the parent page, not the included partial
 * @return object $term
 * @see http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/#comment-110459
 */
function wpdtrt_tourdates_get_partial_term( $term_id ) {

  $term = get_term_by( 'id', $term_id, get_query_var( 'taxonomy' ) );

  return $term;
}

/**
 * Get the tour type
 * @param number $id The ID of the term
 * @return string $term_type (tour|tour_leg)
 */
function wpdtrt_tourdates_get_term_type( $term_id ) {

  $term_type = get_field('wpdtrt_tourdates_acf_tour_category_type', get_query_var( 'taxonomy' ) . '_' . $term_id);

  return $term_type;
}

/**
 * Get the ID of the ACF term
 * so we can get the values of the ACF fields attached to this term
 *
 * @param string $term_type The term type (tour|tour_leg)
 * @return number $term_id The term ID
 */
function wpdtrt_tourdates_get_post_term_ids($term_type) { // // this is returning tour leg start date rather than tour start date

  global $post;
  $post_id = $post->ID;
  $taxonomy = get_query_var('taxonomy');

  wpdtrt_log( 'wpdtrt_tourdates_get_post_term_ids', $term_type );

  $term_id = null;

  // get associated taxonomy_terms
  // get_the_category() doesn't work with custom post type taxonomies
  $terms = get_the_terms( $post_id, $taxonomy );

  if ( is_array( $terms ) ) {
    /**
     * Sort terms into hierarchical order
     *
     * Has parent: $term->parent === n
     * No parent: $term->parent === 0
     * strnatcmp = Natural string comparison
     *
     * @see https://developer.wordpress.org/reference/functions/get_the_terms/
     * @see https://wordpress.stackexchange.com/questions/172118/get-the-term-list-by-hierarchy-order
     * @see https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
     * @see https://wpseek.com/function/_get_term_hierarchy/
     * @see https://wordpress.stackexchange.com/questions/137926/sorting-attributes-order-when-using-get-the-terms
     * @uses WPDTRT helpers/permalinks.php
     */
    uasort ( $terms , function ( $term_a, $term_b ) {
      return strnatcmp( $term_a->parent, $term_b->parent );
    });

    if ( !is_wp_error( $terms ) ) {
      foreach ( $terms as $term ) {
        if ( !empty( $term ) && is_object( $term ) ) {

          $term_id = $term->term_id;
          $acf_term_type = get_field('wpdtrt_tourdates_acf_tour_category_type', $taxonomy . '_' . $term_id);

          wpdtrt_log( $acf_term_type . ' vs ' . $term_type );

          if ( $acf_term_type === $term_type ) {
            break;
          }
        }
      }
    }
  }

  return $term_id;
}

/**
 * Get the number of a tour day, relative to the tour start date
 * Note that the post has to be published on (for) the target date,
 * else this will show the creation date
 * @param number $post_id The post ID
 * @return number $post_daynumber The day number
 */
function wpdtrt_tourdates_get_post_daynumber($post_id) {

  $tour_start_date =  wpdtrt_tourdates_get_term_start_date( $post_id );
  $post_date =        get_the_date( "Y-n-j 00:01:00", $post_id );
  $post_daynumber =   wpdtrt_tourdates_get_term_days_elapsed( $tour_start_date, $post_date );

  wpdtrt_log( 'tour_start_date=' . $tour_start_date );

  return $post_daynumber;
}

/**
 * Get the legs in a tour
 *
 * @param number $term_id The term ID
 * @return string $tour_leg_term_ids The tour leg IDs in order of start date
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_legs($term_id) {

  $tour_leg_term_ids = get_term_children( $term_id, get_query_var( 'taxonomy' ) );
  $tour_leg_terms = array();

  // build array containing term objects
  foreach( $tour_leg_term_ids as $tour_leg_term_id ) {
    $term = get_term_by( 'id', $tour_leg_term_id, get_query_var( 'taxonomy' ) );
    $tour_leg_terms[] = $term;
  }

  // sort term objects by start date
  uasort ( $tour_leg_terms , function ( $term_a, $term_b ) {
    $term_a_id = $term_a->term_id;
    $term_a_start_date = wpdtrt_tourdates_get_term_start_date( $term_a_id );

    $term_b_id = $term_b->term_id;
    $term_b_start_date = wpdtrt_tourdates_get_term_start_date( $term_b_id );

    return strnatcmp( $term_a_start_date, $term_b_start_date );
  });

  // reset term ids array
  $tour_leg_term_ids = array();

  foreach( $tour_leg_terms as $tour_leg_term ) {
    $tour_leg_term_ids[] = $tour_leg_term->term_id;
  }

  return $tour_leg_term_ids;
}

/**
 * Get the first date in a tour
 *
 * @param number $id The ID of the post or term
 * @param string $date_format An optional date format
 * @return string $tour_start_date The date when the tour started (Y-n-j 00:01:00)
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_term_start_date($id, $date_format=null) {

  if ( term_exists( $id, get_query_var('taxonomy') ) ) {
    $term_id = $id;
  }
  else { // if post
    $post_id = $id;

    // get the term_id
    $term_id = 0; // TODO

    wpdtrt_log( 'term_id=? for post_id=' . $post_id );

    $term_type = wpdtrt_tourdates_get_term_type( $term_id );

    wpdtrt_log( 'term_type=' . $term_type . ' for post_id=' . $post_id );

    // wpdtrt_tourdates_get_post_term_ids is the full hierarchy
    // $term type allows us to narrow it down
    // but we need the term id to figure out which term type we want
    $term_id = wpdtrt_tourdates_get_post_term_ids( $term_type );
  }

  $tour_start_date = get_field('wpdtrt_tourdates_acf_tour_category_start_date', get_query_var('taxonomy') . '_' . $term_id);

  if ( $date_format !== null ) {
    $date = new DateTime($tour_start_date);
    $tour_start_date = date_format($date, $date_format);
  }

  return $tour_start_date;
}

/**
 * Get the first day in a tour type
 * If this is a tour leg, calculate how many days it starts,
 * after the tour starts
 *
 * @param number $term_id The term ID
 * @return number $tour_start_day The day when the tour started
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_term_start_day( $term_id ) {

  $term = get_term_by( 'id', $term_id, get_query_var( 'taxonomy' ) );
  $term_type = wpdtrt_tourdates_get_term_type( $term_id );

    wpdtrt_log( 'term_type=' . $term_type );


  if ( $term_type === 'tour' ) {
    $tour_start_day = 1;
  }
  else if ( $term_type === 'tour_leg' ) {
    $parent_term_id = $term->parent;
    $tour_start_date =      wpdtrt_tourdates_get_term_start_date( $parent_term_id );
    $tour_leg_start_date =  wpdtrt_tourdates_get_term_start_date( $term_id );
    $tour_start_day =       wpdtrt_tourdates_get_term_days_elapsed( $tour_start_date, $tour_leg_start_date );

    wpdtrt_log( 'parent_term_id=' . $parent_term_id );
    wpdtrt_log( 'tour_start_date=' . $tour_start_date );
    wpdtrt_log( 'tour_leg_start_date=' . $tour_leg_start_date );
    wpdtrt_log( 'tour_start_day=' . $tour_start_day );
  }

  return $tour_start_day;
}

/**
 * Get the last date in a tour
 *
 * @param number $term_id The term ID
 * @param string $date_format An optional PHP date format
 * @return string $tour_end_date The date when the tour ended (Y-n-j 00:01:00)
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_term_end_date($term_id, $date_format=null) {

  $tour_end_date = get_field('wpdtrt_tourdates_acf_tour_category_end_date', get_query_var('taxonomy') . '_' . $term_id);

  if ( $date_format !== null ) {
    $date = new DateTime($tour_end_date);
    $tour_end_date = date_format($date, $date_format);
  }

  return $tour_end_date;
}

/**
 * Get the start month & year in a tour
 * @param number $term_id The term ID
 * @return string $tour_leg_start_month The month when the tour started (Month YYYY)
 */
function wpdtrt_tourdates_get_term_start_month( $term_id ) {
  $tour_leg_start_month = wpdtrt_tourdates_get_term_start_date($term_id, 'F Y');

  return $tour_leg_start_month;
}

/**
 * Get the end month & year in a tour
 * @param number $term_id The term ID
 * @return string $tour_leg_end_month The month when the tour ended (Month YYYY)
 */
function wpdtrt_tourdates_get_term_end_month( $term_id ) {
  $tour_leg_end_month = wpdtrt_tourdates_get_term_end_date($term_id, 'F Y');

  return $tour_leg_end_month;
}

/**
 * Get tour length in days
 *
 * @param number $term_id The term ID
 * @param string $text_before Translatable text displayed before the tour length
 * @param string $text_after Translatable text displayed after the tour length
 * @return string $tour_length_days The length of the tour
 */
function wpdtrt_tourdates_get_term_length($term_id, $text_before='', $text_after='') {

  $tour_start_date = wpdtrt_tourdates_get_term_start_date( $term_id );
  $tour_end_date = wpdtrt_tourdates_get_term_end_date( $term_id );
  $tour_length_days = wpdtrt_tourdates_get_term_days_elapsed($tour_start_date, $tour_end_date);

  return $text_before . $tour_length_days . $text_after;
}

/**
 * Get the number of unique tour legs
 * @param number $term_id The Term ID
 * @param string $text_before Text to display if more than one leg
 * @param string $text_after Text to display if more than one leg
 * @return string $tour_leg_count The number of unique tour legs
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @todo wpdtrt_tourdates_acf_tour_category_leg_count can be determined from filtering child categories to wpdtrt_tourdates_acf_tour_category_first_visit
 */
function wpdtrt_tourdates_get_term_leg_count($term_id, $text_before='', $text_after='') {
  $tour_leg_count = get_field('wpdtrt_tourdates_acf_tour_category_leg_count', get_query_var('taxonomy') . '_' . $term_id);

  if ( $tour_leg_count > 1 ) {
    $str = $text_before . $tour_leg_count . $text_after;
    $tour_leg_count = $str;
  }
  else {
    $tour_leg_count = ''; // new zealand tour legs are tours
  }

  return $tour_leg_count;
}

/**
 * Get the term image
 * @param number $term_id The Term ID
 * @return string $term_thumbnail_id The thumbnail ID
 * @see https://www.advancedcustomfields.com/resources/image/
 */
function wpdtrt_tourdates_get_term_thumbnail_id( $term_id ) {
  $term_thumbnail_id = get_field('wpdtrt_tourdates_acf_tour_category_thumbnail', get_query_var('taxonomy') . '_' . $term_id);

  return $term_thumbnail_id;
}

/**
 * Get days elapsed since tour started
 * @param number $start_date The start date
 * @param number $end_date The end date
 * @return number $tour_days_elapsed Days elapsed
 * @see http://www.timeanddate.com/date/durationresult.html?d1=2&m1=9&y1=2015&d2=30&m2=6&y2=2016
 */
function wpdtrt_tourdates_get_term_days_elapsed($start_date, $end_date) {
  // http://stackoverflow.com/a/3923228
  $date1 = new DateTime($start_date);
  $date2 = new DateTime($end_date);

  if ( $date1 === $date2 ) {
    $tour_days_elapsed = 1;
  }
  else {
    $interval = $date1->diff($date2);
    $tour_days_elapsed = $interval->format("%r%a"); // ->d only gets days in the same month
  }

  return $tour_days_elapsed + 1;
}

/**
 * Get the name of a tour leg
 * @param string $tour_leg_slug The slug of the tour leg
 * @return string $tour_leg_name The name of the tour leg
 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
 * @see https://codex.wordpress.org/Function_Reference/get_term_by
 */
function wpdtrt_tourdates_get_term_leg_name($tour_leg_slug) {
  $tour_leg_name = '';

  $tour_leg = get_term_by('slug', $tour_leg_slug, 'tours');

  $tour_leg_name = $tour_leg->name;

  return $tour_leg_name;
}

/**
 * Get the ID of a tour leg
 * @param string $tour_leg_slug The slug of the tour leg
 * @return string $tour_leg_id The ID of the tour leg
 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
 * @see https://codex.wordpress.org/Function_Reference/get_term_by
 */
function wpdtrt_tourdates_get_term_leg_id($tour_leg_slug) {
  $tour_leg = get_term_by('slug', $tour_leg_slug, 'tours');

  $tour_leg_id = $tour_leg->term_id;

  return $tour_leg_id;
}

// http://www.tcbarrett.com/2013/05/wordpress-how-to-get-the-slug-of-your-post-or-page/#.Vz26ihV97eQ
/*
function get_the_slug( $id=null ){
  if( empty($id) ):
    global $post;
    if( empty($post) )
      return ''; // No global $post var available.
    $id = $post->ID;
  endif;

  $slug = basename( get_permalink($id) );
  return $slug;
}
*/

/**
 * Display the page or post slug
 *
 * Uses get_the_slug() and applies 'the_slug' filter.
 */
/*
function the_slug( $id=null ){
  echo apply_filters( 'the_slug', get_the_slug($id) );
}
*/



?>