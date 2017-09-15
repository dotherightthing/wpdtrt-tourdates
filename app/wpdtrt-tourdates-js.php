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
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

if ( !function_exists( 'wpdtrt_tourdates_js' ) ) {

  /**
   * Attach JS for front-end widgets and shortcodes
   *    Generate a configuration object which the JavaScript can access.
   *    When an Ajax command is submitted, pass it to our function via the Admin Ajax page.
   *
   * @since       0.1.0
   * @see         https://codex.wordpress.org/AJAX_in_Plugins
   * @see         https://codex.wordpress.org/Function_Reference/wp_localize_script
   */
  function wpdtrt_tourdates_js() {

    wp_enqueue_script( 'wpdtrt_tourdates_js',
      WPDTRT_TOURDATES_URL . 'js/wpdtrt-tourdates.min.js',
      array('jquery'),
      WPDTRT_TOURDATES_VERSION,
      true
    );

    wp_localize_script( 'wpdtrt_tourdates_js',
      'wpdtrt_tourdates_config',
      array(
        'ajax_url' => admin_url( 'admin-ajax.php' ) // wpdtrt_tourdates_config.ajax_url
      )
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_tourdates_js' );

}

?>
