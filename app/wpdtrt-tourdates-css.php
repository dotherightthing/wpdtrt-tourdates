<?php
/**
 * CSS imports
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

if ( !function_exists( 'wpdtrt_tourdates_css' ) ) {

  /**
   * Attach CSS for front-end widgets and shortcodes
   *
   * @since       0.1.0
   */
  function wpdtrt_tourdates_css() {

    wp_enqueue_style( 'wpdtrt_tourdates_css',
      WPDTRT_ELAPSEDDAY_URL . 'css/wpdtrt-tourdates.min.css',
      array(),
      WPDTRT_ELAPSEDDAY_VERSION
      //'all'
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_tourdates_css' );

}

if ( !function_exists( 'wpdtrt_tourdates_admin_css' ) ) {

  /**
   * Attach CSS for Settings > Tour Dates
   *
   * @since       0.1.0
   */
  function wpdtrt_tourdates_admin_css() {

    wp_enqueue_style( 'wpdtrt_tourdates_admin_css',
      WPDTRT_ELAPSEDDAY_URL . 'css/wpdtrt-tourdates-admin.min.css',
      array(),
      WPDTRT_ELAPSEDDAY_VERSION
      //'all'
    );
  }

  add_action( 'admin_head', 'wpdtrt_tourdates_admin_css' );

}

?>
