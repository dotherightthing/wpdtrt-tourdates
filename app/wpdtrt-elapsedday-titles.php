<?php
/**
 * Titles
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */

if ( !function_exists( 'wpdtrt_elapsedday_post_title_add_day' ) ) {

  /*
   * Add the ACF day to the post title
   * @see https://wordpress.org/support/topic/the_title-filter-only-for-page-title-display
   * @todo: this is outputting into the Primary Navigation menu, need to check !if_menu
   */
  add_filter( 'the_title', 'wpdtrt_elapsedday_post_title_add_day' );

  function wpdtrt_elapsedday_post_title_add_day( $title, $id = NULL ) {

    // http://php.net/manual/en/functions.arguments.php
    //if ( is_null($id) ) {
    //  $day = get_field('acf_daynumber');
   // }
    //else {
     // $day = get_post_field('acf_daynumber', $id);
    //}

    $day = wpdtrt_elapsedday_get_post_daynumber($id);

    $day_html = '<span class="wpdtrt-elapsedday-day theme-text_secondary"><span class="wpdtrt-elapsedday-day--day">Day </span><span class="wpdtrt-elapsedday-day--number">' . $day . '</span><span class="wpdtrt-elapsedday-day--period">, </span></span>';
    $title_html = '<span class="wpdtrt-elapsedday-day--title">' . $title . '</span>';
    $simple_title_html = '<span class="wpdtrt-elapsedday-day--title">' . $title . '</span>';

    // if in the loop / rendering the post
    // || is_active_widget(false, 'widget_recent_entries')
    if ( $day && in_the_loop() && is_single() && ! is_admin() && ( !is_active_widget() || is_active_widget(false,'widget_recent_entries') ) ) {
      return $day_html . $title_html;
    }
    // if is category listings or similar
    else if ( $day && in_the_loop() && ( is_archive() || is_search() || is_home() ) && ( !is_active_widget() || is_active_widget(false,'widget_recent_entries') ) ) {
      if ( is_admin() ) { // excludes media library
        return $title;
      }
      else {
        return $day_html . $title_html;
      }
    }
    // else if the dashboard etc
    else {
      if ( is_admin() ) {
        return $title;
      }
      else {
        return $simple_title_html;
      }
    }
  }
}

if ( !function_exists( 'wpdtrt_elapsedday_attachment_title_remove_day' ) ) {

  function wpdtrt_elapsedday_attachment_title_remove_day( $attachment_title = '', $fallback = '' ) {

    $regex = '/<span class="wpdtrt-elapsedday-day theme-text_secondary">.*<\/span><span class="wpdtrt-elapsedday-day--title">/';
    $output = preg_replace($regex, '<span class="wpdtrt-elapsedday-day--title">', $attachment_title);
    $html_len = strlen('<span class="wpdtrt-elapsedday-day--title"></span>');
    $output = trim($output);

    if ( ( strlen($output) - $html_len ) === 0 ) {
      $output = $fallback;
    }

    return $output;
  }
}

if ( !function_exists( 'wpdtrt_elapsedday_attachment_title_add_day' ) ) {

  function wpdtrt_elapsedday_attachment_title_add_day( $attachment_title, $parent_title, $parent_id ) {

    // http://php.net/manual/en/functions.arguments.php
    //if ( is_null($id) ) {
    //  $day = get_field('acf_daynumber');
   // }
    //else {
     // $day = get_post_field('acf_daynumber', $id);
    //}

    global $post;

    $parent_day = wpdtrt_elapsedday_get_post_daynumber($parent_id);
    $attachment_title = wpdtrt_elapsedday_attachment_title_remove_day( $attachment_title );
    $title_text = 'Gallery image';

    if ( $attachment_title ) {
      $title_text .= ': ' . $attachment_title;
    }

    $day_html = '<span class="wpdtrt-elapsedday-day theme-text_secondary"><span class="wpdtrt-elapsedday-day--day">Day </span><span class="wpdtrt-elapsedday-day--number">' . $parent_day . ': ' . $parent_title . '</span><span class="wpdtrt-elapsedday-day--period">. </span></span>';
    $title_html = '<span class="wpdtrt-elapsedday-day--title">' . $title_text . '</span>';

    return $day_html . $title_html;
  }
}

?>