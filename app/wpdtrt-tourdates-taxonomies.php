<?php
/**
 * Taxonomies
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */
if ( function_exists( 'wpdtrt_taxonomy_create' ) ) {

  wpdtrt_taxonomy_create(
    array(
      'slug' => 'elapsedday',
      'fallback' => 'no-elapsedday',
      'post_type_slug' => 'tourdiaryday',
      'description' => 'The count of a tour day relative to the tour date range',
      'label_single' => 'Tour Dates',
      'label_plural' => 'Tour Datess',
      'hierarchical' => true,
      'public' => true,
      'label_prefix' => '',
      'terms' => false
    )
  );
}

?>