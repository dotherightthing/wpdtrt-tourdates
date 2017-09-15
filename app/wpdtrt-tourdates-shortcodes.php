<?php
/**
 * Generate a shortcode, to embed the widget inside a content area or template.
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @link        https://generatewp.com/shortcodes/
 * @since       0.1.0
 *
 * @example     [wpdtrt_tourdates_navigation]
 * @example     do_shortcode( '[wpdtrt_tourdates_navigation]' );
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

if ( !function_exists( 'wpdtrt_tourdates_navigation_shortcode' ) ) {

  /**
   * add_shortcode
   * @param       string $tag
   *    Shortcode tag to be searched in post content.
   * @param       callable $func
   *    Hook to run when shortcode is found.
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   * @see         http://php.net/manual/en/function.ob-start.php
   * @see         http://php.net/manual/en/function.ob-get-clean.php
   */
  add_shortcode( 'wpdtrt_tourdates_navigation', 'wpdtrt_tourdates_navigation_shortcode' );

  function wpdtrt_tourdates_navigation_shortcode( $atts, $content = null ) {

    // post object to get info about the post in which the shortcode appears
    global $post;
    $post_id = $post->ID;

    // initialise extracted variables to aid debugging
    $before_widget = null;
    $before_title = null;
    $title = null;
    $after_title = null;
    $after_widget = null;

    /*
    extract( shortcode_atts(
      array(
        'number' => '4',
        'enlargement' => 'yes'
      ),
      $atts,
      ''
    ) );

    if ( $enlargement === 'yes') {
      $enlargement = '1';
    }

    if ( $enlargement === 'no') {
      $enlargement = '0';
    }

    $wpdtrt_tourdates_options = get_option('wpdtrt_tourdates');
    $wpdtrt_tourdates_data = $wpdtrt_tourdates_options['wpdtrt_tourdates_data'];
    */

    // vars to pass to template partial
    $previous =   wpdtrt_tourdates_navigation_link('previous');
    $next =       wpdtrt_tourdates_navigation_link('next');
    $daynumber =  wpdtrt_tourdates_get_post_daynumber($post_id);

    /**
     * ob_start — Turn on output buffering
     * This stores the HTML template in the buffer
     * so that it can be output into the content
     * rather than at the top of the page.
     */
    ob_start();

    require(WPDTRT_TOURDATES_PATH . 'templates/wpdtrt-tourdates-navigation.php');

    /**
     * ob_get_clean — Get current buffer contents and delete current output buffer
     */
    $content = ob_get_clean();

    return $content;
  }
}

if ( !function_exists( 'wpdtrt_tourdates_daynumber_shortcode' ) ) {

  /**
   * add_shortcode
   * @param       string $tag
   *    Shortcode tag to be searched in post content.
   * @param       callable $func
   *    Hook to run when shortcode is found.
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   */
  add_shortcode( 'wpdtrt_tourdates_daynumber', 'wpdtrt_tourdates_daynumber_shortcode' );

  function wpdtrt_tourdates_daynumber_shortcode( $atts, $content = null ) {
    global $post;
    $post_id = $post->ID;
    $daynumber = wpdtrt_tourdates_get_post_daynumber($post_id);

    return $daynumber;
  }
}

if ( !function_exists( 'wpdtrt_tourdates_daytotal_shortcode' ) ) {

  /**
   * add_shortcode
   * @param       string $tag
   *    Shortcode tag to be searched in post content.
   * @param       callable $func
   *    Hook to run when shortcode is found.
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   */
  add_shortcode( 'wpdtrt_tourdates_daytotal', 'wpdtrt_tourdates_daytotal_shortcode' );

  function wpdtrt_tourdates_daytotal_shortcode( $atts, $content = null ) {
    global $post;
    $post_id = $post->ID;
    $tour_start_date = wpdtrt_tourdates_get_term_start_date( $post_id );
    $tour_end_date = wpdtrt_tourdates_get_term_end_date( $post_id );
    $day_total = wpdtrt_tourdates_get_term_days_elapsed( $tour_start_date, $tour_end_date );

    return $day_total;
  }
}

if ( !function_exists( 'wpdtrt_tourdates_tourlengthdays_shortcode' ) ) {

  /**
   * add_shortcode
   * @param       string $tag
   *    Shortcode tag to be searched in post content.
   * @param       callable $func
   *    Hook to run when shortcode is found.
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   */
  add_shortcode( 'wpdtrt_tourdates_tourlengthdays', 'wpdtrt_tourdates_tourlengthdays_shortcode' );

  /**
   * Get tour length in days
   *
   * @param number $term_id The term ID
   * @param string $text_before Translatable text displayed before the tour length
   * @param string $text_after Translatable text displayed after the tour length
   * @return string $tour_length_days The length of the tour
   */
  function wpdtrt_tourdates_tourlengthdays_shortcode( $atts, $content = null ) {

    // initialise extracted variables to aid debugging
    $term_id = null;
    $text_before = null;
    $text_after = null;

    extract( shortcode_atts(
      array(
        'term_id' => null,
        'text_before' => '',
        'text_after' => ''
      ),
      $atts,
      ''
    ) );

    // convert shortcode argument to a number
    if ( isset( $term_id ) ) {
      $term_id = (int)$term_id;
    }

    $tour_start_date = wpdtrt_tourdates_get_term_start_date( $term_id );
    $tour_end_date = wpdtrt_tourdates_get_term_end_date( $term_id );
    $tour_length_days = wpdtrt_tourdates_get_term_days_elapsed($tour_start_date, $tour_end_date);

    return $text_before . $tour_length_days . $text_after;
  }
}

?>
