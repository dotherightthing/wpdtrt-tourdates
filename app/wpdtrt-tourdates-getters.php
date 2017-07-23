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
 * Get the ID of the ACF term
 * so we can get the values of the ACF fields attached to this term
 *
 * @param string $tour_type The type of tour (tour|tour_leg)
 * @return array ($term_id, $acf_term_id) The id of the term, and the ID as required by ACF to query attached fields
 */
function wpdtrt_tourdates_get_term_ids($tour_type) {

  global $post;
  $post_id = $post->ID;
  $term_id = null;
  $acf_term_id = null;

  $taxonomy_name = 'tours';

  // get associated taxonomy_terms
  // get_the_category() doesn't work with custom post type taxonomies
  $terms = get_the_terms( $post_id, $taxonomy_name );

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

          $acf_term_id = $taxonomy_name . '_' . $term_id;

          $acf_term_type = get_field('wpdtrt_tourdates_acf_tour_category_type', $acf_term_id);

          if ( $acf_term_type === $tour_type ) {
            break;
          }
        }
      }
    }
  }

  return array(
    'term_id' => $term_id,
    'acf_term_id' => $acf_term_id
  );
}

/**
 * Get the number of a tour day, relative to the tour start date
 * Note that the post has to be published on (for) the target date,
 * else this will show the creation date
 * @return number $post_daynumber The day number
 */
function wpdtrt_tourdates_get_post_daynumber() {

  //if ( ! is_single() ) {
  //  return 0;
  //}

  global $post;
  $post_id = $post->ID;

  $tour_start_date =  wpdtrt_tourdates_get_tour_start_date( 'tour' );
  $post_date =        get_the_date( "Y-n-j 00:01:00", $post_id );
  $post_daynumber =   wpdtrt_tourdates_get_tour_days_elapsed( $tour_start_date, $post_date );

  return $post_daynumber;
}

/**
 * Get the first date in a tour
 *
 * @param number $tour_type The type of tour (tour|tour_leg)
 * @param string $date_format An optional date format
 * @return string $tour_start_date The date when the tour started (Y-n-j 00:01:00)
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_start_date($tour_type, $date_format=null) {

  $term_ids = wpdtrt_tourdates_get_term_ids($tour_type);
  $acf_term_id = $term_ids['acf_term_id'];

  $tour_start_date = get_field('wpdtrt_tourdates_acf_tour_category_start_date', $acf_term_id);

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
 * @param number $tour_type The type of tour (tour|tour_leg)
 * @return number $tour_start_day The day when the tour started
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_start_day( $tour_type ) {

  if ( $tour_type === 'tour' ) {
    $tour_start_day = 1;
  }
  else if ( $tour_type === 'tour_leg' ) {
    $tour_start_date =      wpdtrt_tourdates_get_tour_start_date( 'tour' );
    $tour_leg_start_date =  wpdtrt_tourdates_get_tour_start_date( 'tour_leg' );
    $tour_start_day =       wpdtrt_tourdates_get_tour_days_elapsed( $tour_start_date, $tour_leg_start_date );
  }

  return $tour_start_day;
}

/**
 * Get the last date in a tour
 *
 * @param number $tour_type The type of tour (tour|tour_leg)
 * @param string $date_format An optional PHP date format
 * @return string $tour_end_date The date when the tour ended (Y-n-j 00:01:00)
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_end_date($tour_type, $date_format=null) {

  $term_ids = wpdtrt_tourdates_get_term_ids($tour_type);
  $acf_term_id = $term_ids['acf_term_id'];

  $tour_end_date = get_field('wpdtrt_tourdates_acf_tour_category_end_date', $acf_term_id);

  if ( $date_format !== null ) {
    $date = new DateTime($tour_end_date);
    $tour_end_date = date_format($date, $date_format);
  }

  return $tour_end_date;
}

/**
 * Get the start month & year in a tour
 * @param number $tour_type The type of tour
 * @return string $tour_leg_start_month The month when the tour started (Month YYYY)
 */
function wpdtrt_tourdates_get_tour_start_month($tour_type) {
  $tour_leg_start_month = wpdtrt_tourdates_get_tour_start_date($tour_type, 'F Y');
  return $tour_leg_start_month;
}

/**
 * Get the end month & year in a tour
 * @param number $tour_type The type of tour
 * @return string $tour_leg_end_month The month when the tour ended (Month YYYY)
 */
function wpdtrt_tourdates_get_tour_end_month($tour_type) {
  $tour_leg_end_month = wpdtrt_tourdates_get_tour_end_date($tour_type, 'F Y');
  return $tour_leg_end_month;
}

/**
 * Get tour length in days
 *
 * @param string $tour_type The type of tour (tour|tour_leg)
 * @param string $text_before Text to display if more than one leg
 * @param string $text_after Text to display if more than one leg
 * @return string $tour_length_days The length of the tour
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @todo replace with filter of legs by wpdtrt_tourdates_acf_tour_category_first_visit
 */
function wpdtrt_tourdates_get_tour_length($tour_type, $text_before='', $text_after='') {

  $tour_start_date = wpdtrt_tourdates_get_tour_start_date( $tour_type );
  $tour_end_date = wpdtrt_tourdates_get_tour_end_date( $tour_type );
  $tour_length_days = wpdtrt_tourdates_get_tour_days_elapsed($tour_start_date, $tour_end_date);

  //wpdtrt_log('$tour_length_days=' . $tour_length_days); // ok
  return $text_before . $tour_length_days . $text_after;
}

/**
 * Get the number of unique tour legs
 * @param string $text_before Text to display if more than one leg
 * @param string $text_after Text to display if more than one leg
 * @return string $tour_leg_count The number of unique tour legs
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @todo wpdtrt_tourdates_acf_tour_category_leg_count can be determined from filtering child categories to wpdtrt_tourdates_acf_tour_category_first_visit
 */
function wpdtrt_tourdates_get_tour_leg_count($text_before='', $text_after='') {

  $term_ids = wpdtrt_tourdates_get_term_ids('tour');
  $acf_term_id = $term_ids['acf_term_id'];

  $tour_leg_count = get_field('wpdtrt_tourdates_acf_tour_category_leg_count', $acf_term_id);

  if ( $tour_leg_count > 1 ) {
    $str = $text_before . $tour_leg_count . $text_after;
    $tour_leg_count = $str;
  }

  return $tour_leg_count;
}

/**
 * Get days elapsed since tour started
 * @param number $start_date The start date
 * @param number $end_date The end date
 * @return number $tour_days_elapsed Days elapsed
 * @see http://www.timeanddate.com/date/durationresult.html?d1=2&m1=9&y1=2015&d2=30&m2=6&y2=2016
 */
function wpdtrt_tourdates_get_tour_days_elapsed($start_date, $end_date) {
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
function wpdtrt_tourdates_get_tour_leg_name($tour_leg_slug) {
  $tour_leg_name = '';

  $tour_leg = get_term_by('slug', $tour_leg_slug, 'tours');
  //wpdtrt_log( $term ); // ok

  $tour_leg_name = $tour_leg->name;
  //wpdtrt_log( $tour_leg_name ); // ok

  return $tour_leg_name;
}

/**
 * Get the ID of a tour leg
 * @param string $tour_leg_slug The slug of the tour leg
 * @return string $tour_leg_id The ID of the tour leg
 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
 * @see https://codex.wordpress.org/Function_Reference/get_term_by
 */
function wpdtrt_tourdates_get_tour_leg_id($tour_leg_slug) {
  $tour_leg = get_term_by('slug', $tour_leg_slug, 'tours');

  $tour_leg_id = $tour_leg->term_id;
  //wpdtrt_log( 'tour_leg_id='.$tour_leg_id ); // ok

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