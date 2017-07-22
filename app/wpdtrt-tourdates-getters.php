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
 * Get the ID of the category which controls tour dates, to ensure accurate day counts
 * Category Types:
 * 1. Region: New Zealand is a generic geographical category
 * 2. Tour Leg: The Rainbow Road (2017) category controls 6 days and is a child of the New Zealand category
 * 3. Tour: The East Asia (2015-6) category controls 298 days and is a parent to the legs China, Mongolia, etc
 * @param number $post_id The id of the current post
 * @param string $tour_type The type of tour (tour|tour_leg)
 * @return number $daycontroller_id The id of the category id which controls a tour's start and end dates
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @see https://developer.wordpress.org/reference/functions/get_the_category/
 */
function wpdtrt_tourdates_get_daycontroller_id($post_id, $tour_type) {
  $daycontroller_id = '';
  $taxonomy_name = 'tours';

  // get associated taxonomy_terms
  // get_the_category() doesn't work with custom post type taxonomies
  $taxonomy_terms = get_the_terms( $post_id, $taxonomy_name );

  if ( is_array( $taxonomy_terms ) ) {
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
    uasort ( $taxonomy_terms , function ( $term_a, $term_b ) {
      return strnatcmp( $term_a->parent, $term_b->parent );
    });

    if ( !is_wp_error( $taxonomy_terms ) ) {
      foreach ( $taxonomy_terms as $term ) {
        if ( !empty( $term ) && is_object( $term ) ) {

          $taxonomy_term_id = $term->term_id;

          $acf_taxonomy_term_id = $taxonomy_name . '_' . $taxonomy_term_id;

          $taxonomy_term_type = get_field('wpdtrt_tourdates_acf_tour_category_type', $acf_taxonomy_term_id);

          if ( $taxonomy_term_type === $tour_type ) {
            $daycontroller_id = $taxonomy_term_id;
            break;
          }
        }
        // !empty
      }
      // loop
    }
    // is_wp_error
  }
  // if array

  return $daycontroller_id;
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

  $tour_start_date = wpdtrt_tourdates_get_tour_start_date( $post_id );
  $post_date = get_the_date( "Y-n-j 00:01:00", $post_id );
  $post_daynumber = wpdtrt_tourdates_get_tour_days_elapsed( $tour_start_date, $post_date );

  /*
  $post_day_number = get_post_meta($post_id, 'acf_daynumber', true);
  //$post_day_number = get_field('acf_daynumber');

  if ($post_day_number) {
    return $post_day_number;
  }
  */

  return $post_daynumber;
}

/**
 * Get the number of a tour leg day, relative to the tour start date
 * @param number $tour_id The id of the tour
 * @param string $tour_leg_date The tour leg date
 * @return number $tour_leg_daynumber The day number
 */
function wpdtrt_tourdates_get_tour_leg_daynumber($tour_id, $tour_leg_date) {

  $tour_start_date = wpdtrt_tourdates_get_tour_start_date( -1, $tour_id );
  $tour_leg_daynumber = wpdtrt_tourdates_get_tour_days_elapsed( $tour_start_date, $tour_leg_date );

  return $tour_leg_daynumber;
}

/**
 * Get the first date in a tour
 * @param number $post_id The id of the current post (if a post)
 * @param number $taxonomy_term_id The id of the tour (if a category archive page)
 * @return string $tour_start_date The date when the tour started (Y-n-j 00:01:00)
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_start_date($post_id=-1, $taxonomy_term_id=-1) {

  $taxonomy_name = 'tours';
  $wpdtrt_tourdates_acf_tour_category_type = 'tour'; // region|tour|tour_leg

  if ( $taxonomy_term_id === -1 ) {
    $taxonomy_term_id = wpdtrt_tourdates_get_daycontroller_id( $post_id, $wpdtrt_tourdates_acf_tour_category_type );
  }

  $acf_taxonomy_term_id = $taxonomy_name . '_' . $taxonomy_term_id;
  $tour_start_date = get_field('wpdtrt_tourdates_acf_tour_category_start_date', $acf_taxonomy_term_id);

  //wpdtrt_log('$tour_start_date=' . $tour_start_date); // ok
  return $tour_start_date;
}

/**
 * Get the last date in a tour
 * @param number $post_id The id of the current post (if a post)
 * @param number $taxonomy_term_id The id of the tour (if a category archive page)
 * @return string $tour_start_date The date when the tour ended (Y-n-j 00:01:00)
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_end_date($post_id=-1, $taxonomy_term_id=-1) {

  $taxonomy_name = 'tours';
  $wpdtrt_tourdates_acf_tour_category_type = 'tour'; // region|tour|tour_leg

  if ( $taxonomy_term_id === -1 ) {
    $taxonomy_term_id = wpdtrt_tourdates_get_daycontroller_id( $post_id, $wpdtrt_tourdates_acf_tour_category_type );
  }

  $acf_taxonomy_term_id = $taxonomy_name . '_' . $taxonomy_term_id;
  $tour_end_date = get_field('wpdtrt_tourdates_acf_tour_category_end_date', $acf_taxonomy_term_id);

  //wpdtrt_log('$tour_end_date=' . $tour_end_date); // ok
  return $tour_end_date;
}

/**
 * Get tour length in days
 * @param number $post_id The id of the current post (if a post)
 * @param number $tour_id The id of the tour (if a category archive page)
 * @param string $text_before Text to display if more than one leg
 * @param string $text_after Text to display if more than one leg
 * @return string $tour_length_days The length of the tour
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @todo replace with filter of legs by wpdtrt_tourdates_acf_tour_category_first_visit
 */
function wpdtrt_tourdates_get_tour_length($post_id=-1, $tour_id=-1, $text_before='', $text_after='') {
  $tour_start_date = wpdtrt_tourdates_get_tour_start_date( $post_id, $tour_id );
  $tour_end_date = wpdtrt_tourdates_get_tour_end_date( $post_id, $tour_id );
  $tour_length_days = wpdtrt_tourdates_get_tour_days_elapsed($tour_start_date, $tour_end_date);

  //wpdtrt_log('$tour_length_days=' . $tour_length_days); // ok
  return $text_before . $tour_length_days . $text_after;
}

/**
 * Get the number of unique tour legs
 * @param number $tour_id The ID of the tour
 * @param string $text_before Text to display if more than one leg
 * @param string $text_after Text to display if more than one leg
 * @return string $tour_leg_count The number of unique tour legs
 * @see https://www.advancedcustomfields.com/resources/get_field/
 * @todo wpdtrt_tourdates_acf_tour_category_leg_count can be determined from filtering child categories to wpdtrt_tourdates_acf_tour_category_first_visit
 */
function wpdtrt_tourdates_get_tour_leg_count($tour_id, $text_before='', $text_after='') {
  $daycontroller_id = $tour_id;
  $acf_category_id = 'tour_' . $daycontroller_id;
  $tour_leg_count = get_field('wpdtrt_tourdates_acf_tour_category_leg_count', $acf_category_id);

  if ( $tour_leg_count > 1 ) {
    $str = $text_before . $tour_leg_count . $text_after;
    $tour_leg_count = $str;
  }

  //wpdtrt_log('$tour_leg_count=' . $tour_leg_count); // ok
  return $tour_leg_count;
}

/**
 * Get the first date in a tour leg
 * @param number $category_slug The slug of the tour leg category
 * @param string $date_format An optional date format
 * @return string $tour_leg_start_date The date when the tour leg started (Y-n-j 00:01:00)
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_leg_start_date($category_slug, $date_format=null) {
  $daycontroller_id = wpdtrt_tourdates_get_tour_leg_id( $category_slug );
  $acf_category_id = 'tour_' . $daycontroller_id;
  $tour_leg_start_date = get_field('wpdtrt_tourdates_acf_tour_category_start_date', $acf_category_id);

  if ( $date_format !== null ) {
    $date = new DateTime($tour_leg_start_date);
    $tour_leg_start_date = date_format($date, $date_format);
    //wpdtrt_log('$tour_leg_start_date_formatted=' . $tour_leg_start_date); // ok
  }

  //wpdtrt_log('$tour_leg_start_date=' . $tour_leg_start_date); // ok
  return $tour_leg_start_date;
}

/**
 * Get the last date in a tour leg
 * @param number $category_slug The slug of the tour leg category
 * @param string $date_format An optional date format
 * @return string $tour_leg_end_date The date when the tour leg ended (Y-n-j 00:01:00)
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_leg_end_date($category_slug, $date_format=null) {
  $daycontroller_id = wpdtrt_tourdates_get_tour_leg_id( $category_slug );
  $acf_category_id = 'tour_' . $daycontroller_id;
  $tour_leg_end_date = get_field('wpdtrt_tourdates_acf_tour_category_end_date', $acf_category_id);

  if ( $date_format !== null ) {
    $date = new DateTime($tour_leg_end_date);
    $tour_leg_end_date = date_format($date, $date_format);
    //wpdtrt_log('$tour_leg_end_date_formatted=' . $tour_leg_end_date); // ok
  }

  //wpdtrt_log('$tour_leg_end_date=' . $tour_leg_end_date); // ok
  return $tour_leg_end_date;
}

/**
 * Get the start month & year in a tour leg
 * @param number $category_slug The slug of the tour leg category
 * @return string $tour_leg_start_month The month when the tour leg started (Month YYYY)
 */
function wpdtrt_tourdates_get_tour_leg_start_month($category_slug) {
  $tour_leg_start_month = wpdtrt_tourdates_get_tour_leg_start_date($category_slug, 'F Y');

  //wpdtrt_log('$tour_leg_start_month=' . $tour_leg_start_month); // ok
  return $tour_leg_start_month;
}

/**
 * Get the end month & year in a tour leg
 * @param number $category_slug The slug of the tour leg category
 * @return string $tour_leg_end_month The month when the tour leg ended (Month YYYY)
 */
function wpdtrt_tourdates_get_tour_leg_end_month($category_slug) {
  $tour_leg_end_month = wpdtrt_tourdates_get_tour_leg_end_date($category_slug, 'F Y');

  //wpdtrt_log('$tour_leg_end_month=' . $tour_leg_end_month); // ok
  return $tour_leg_end_month;
}

/**
 * Get the first day in a tour leg
 * @param number $tour_leg_slug The slug of the tour leg category
 * @return number $tour_leg_start_day The day when the tour leg started
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_leg_start_day($tour_leg_slug) {
  $tour_id = wpdtrt_tourdates_get_tour_id( $tour_leg_slug );
  $tour_leg_start_date = wpdtrt_tourdates_get_tour_leg_start_date( $tour_leg_slug );
  $tour_leg_start_day = wpdtrt_tourdates_get_tour_leg_daynumber( $tour_id, $tour_leg_start_date );

  //wpdtrt_log('$tour_leg_start_day=' . $tour_leg_start_day); // ok
  return $tour_leg_start_day;
}

/**
 * Get the last day in a tour leg
 * @param number $tour_leg_slug The slug of the tour leg category
 * @return number $tour_leg_end_day The day when the tour leg ended
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_leg_end_day($tour_leg_slug) {
  $tour_id = wpdtrt_tourdates_get_tour_id( $tour_leg_slug );
  $tour_leg_end_date = wpdtrt_tourdates_get_tour_leg_end_date( $tour_leg_slug );
  $tour_leg_end_day = wpdtrt_tourdates_get_tour_leg_daynumber( $tour_id, $tour_leg_end_date );

  // wpdtrt_log('$tour_leg_end_day=' . $tour_leg_end_day); // ok
  return $tour_leg_end_day;
}

/**
 * Get tour leg length in days
 * @param number $category_slug The slug of the tour leg category
 * @return string $tour_leg_length_days The length of the tour leg
 * @see https://www.advancedcustomfields.com/resources/get_field/
 */
function wpdtrt_tourdates_get_tour_leg_length($category_slug) {
  $tour_leg_start_date = wpdtrt_tourdates_get_tour_leg_start_date( $category_slug );
  $tour_leg_end_date = wpdtrt_tourdates_get_tour_leg_end_date( $category_slug );
  $tour_leg_length_days = wpdtrt_tourdates_get_tour_days_elapsed($tour_leg_start_date, $tour_leg_end_date);

  //wpdtrt_log('$tour_length_days=' . $tour_length_days); // ok
  return $tour_leg_length_days;
}

/**
 * Get days elapsed since tour started
 * @param number $start_date The start date
 * @param number $end_date The end date
 * @return number $tour_days_elapsed Days elapsed
 */
function wpdtrt_tourdates_get_tour_days_elapsed($start_date, $end_date) {
  // http://stackoverflow.com/a/3923228
  $date1 = new DateTime($start_date);
  $date2 = new DateTime($end_date);
  $interval = $date1->diff($date2);
  $day_difference = $interval->format("%r%a"); // ->d only gets days in the same month

  // http://www.timeanddate.com/date/durationresult.html?d1=2&m1=9&y1=2015&d2=30&m2=6&y2=2016
  $default_day = 1;
  $tour_days_elapsed = $default_day + $day_difference;

  return $tour_days_elapsed;
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

/**
 * Get the ID of a tour
 * @param string $tour_leg_slug The slug of the tour leg
 * @return string $tour_id The ID of the tour which the leg is a part of
 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
 * @see https://codex.wordpress.org/Function_Reference/get_term_by
 */
function wpdtrt_tourdates_get_tour_id($tour_leg_slug) {
  $tour_leg = get_term_by('slug', $tour_leg_slug, 'tours');
  $tour_id = $tour_leg->parent;
  //wpdtrt_log( 'tour_id='.$tour_id ); // ok

  return $tour_id;
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