<?php
/**
 * JS imports
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @see         https://codex.wordpress.org/AJAX_in_Plugins
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */

if ( !function_exists( 'wpdtrt_elapsedday_js' ) ) {

  /**
   * Attach JS for front-end widgets and shortcodes
   *    Generate a configuration object which the JavaScript can access.
   *    When an Ajax command is submitted, pass it to our function via the Admin Ajax page.
   *
   * @since       0.1.0
   * @see         https://codex.wordpress.org/AJAX_in_Plugins
   * @see         https://codex.wordpress.org/Function_Reference/wp_localize_script
   */
  function wpdtrt_elapsedday_js() {

    wp_enqueue_script( 'wpdtrt_elapsedday_js',
      WPDTRT_ELAPSEDDAY_URL . 'js/wpdtrt-elapsedday.min.js',
      array('jquery'),
      WPDTRT_ELAPSEDDAY_VERSION,
      true
    );

    wp_localize_script( 'wpdtrt_elapsedday_js',
      'wpdtrt_elapsedday_config',
      array(
        'ajax_url' => admin_url( 'admin-ajax.php' ) // wpdtrt_elapsedday_config.ajax_url
      )
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_elapsedday_js' );

}

?>
