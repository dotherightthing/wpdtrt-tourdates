<?php
/**
 * CSS imports
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */

if ( !function_exists( 'wpdtrt_elapsedday_css' ) ) {

  /**
   * Attach CSS for front-end widgets and shortcodes
   *
   * @since       0.1.0
   */
  function wpdtrt_elapsedday_css() {

    wp_enqueue_style( 'wpdtrt_elapsedday_css',
      WPDTRT_ELAPSEDDAY_URL . 'css/wpdtrt-elapsedday.min.css',
      array(),
      WPDTRT_ELAPSEDDAY_VERSION
      //'all'
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_elapsedday_css' );

}

if ( !function_exists( 'wpdtrt_elapsedday_admin_css' ) ) {

  /**
   * Attach CSS for Settings > Elapsed Day
   *
   * @since       0.1.0
   */
  function wpdtrt_elapsedday_admin_css() {

    wp_enqueue_style( 'wpdtrt_elapsedday_admin_css',
      WPDTRT_ELAPSEDDAY_URL . 'css/wpdtrt-elapsedday-admin.min.css',
      array(),
      WPDTRT_ELAPSEDDAY_VERSION
      //'all'
    );
  }

  add_action( 'admin_head', 'wpdtrt_elapsedday_admin_css' );

}

?>
