<?php
/**
 * Taxonomies
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */
if ( function_exists( 'wpdtrt_taxonomy_create' ) ) {

  wpdtrt_taxonomy_create(
    array(
      'slug' => 'elapsedday',
      'fallback' => 'no-elapsedday',
      'post_type_slug' => 'tourdiaryday',
      'description' => 'The count of a tour day relative to the tour date range',
      'label_single' => 'Elapsed Day',
      'label_plural' => 'Elapsed Days',
      'hierarchical' => true,
      'public' => true,
      'label_prefix' => '',
      'terms' => false
    )
  );
}

?>