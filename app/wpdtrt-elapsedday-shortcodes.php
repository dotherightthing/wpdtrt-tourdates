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
 * @example     [wpdtrt_elapsedday_navigation]
 * @example     do_shortcode( '[wpdtrt_elapsedday_navigation]' );
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */

if ( !function_exists( 'wpdtrt_elapsedday_navigation_shortcode' ) ) {

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
  add_shortcode( 'wpdtrt_elapsedday_navigation', 'wpdtrt_elapsedday_navigation_shortcode' );

  function wpdtrt_elapsedday_navigation_shortcode( $atts, $content = null ) {

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

    $wpdtrt_elapsedday_options = get_option('wpdtrt_elapsedday');
    $wpdtrt_elapsedday_data = $wpdtrt_elapsedday_options['wpdtrt_elapsedday_data'];
    */

    // vars to pass to template partial
    $previous = wpdtrt_elapsedday_navigation_link('previous');
    $next = wpdtrt_elapsedday_navigation_link('next');
    //$day = get_field('acf_daynumber');
    $daynumber = wpdtrt_elapsedday_get_post_daynumber($post_id);

    /**
     * ob_start — Turn on output buffering
     * This stores the HTML template in the buffer
     * so that it can be output into the content
     * rather than at the top of the page.
     */
    ob_start();

    require(WPDTRT_ELAPSEDDAY_PATH . 'partials/wpdtrt-elapsedday-navigation.php');

    /**
     * ob_get_clean — Get current buffer contents and delete current output buffer
     */
    $content = ob_get_clean();

    return $content;
  }
}

if ( !function_exists( 'wpdtrt_elapsedday_daynumber_shortcode' ) ) {

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
  add_shortcode( 'wpdtrt_elapsedday_daynumber', 'wpdtrt_elapsedday_daynumber_shortcode' );

  function wpdtrt_elapsedday_daynumber_shortcode( $atts, $content = null ) {
    global $post;
    $post_id = $post->ID;
    $daynumber = wpdtrt_elapsedday_get_post_daynumber($post_id);

    return $daynumber;
  }
}

if ( !function_exists( 'wpdtrt_elapsedday_daytotal_shortcode' ) ) {

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
  add_shortcode( 'wpdtrt_elapsedday_daytotal', 'wpdtrt_elapsedday_daytotal_shortcode' );

  function wpdtrt_elapsedday_daytotal_shortcode( $atts, $content = null ) {
    global $post;
    $post_id = $post->ID;
    $tour_start_date = wpdtrt_elapsedday_get_tour_start_date( $post_id );
    $tour_end_date = wpdtrt_elapsedday_get_tour_end_date( $post_id );
    $day_total = wpdtrt_elapsedday_get_tour_days_elapsed( $tour_start_date, $tour_end_date );

    return $day_total;
  }
}

?>
