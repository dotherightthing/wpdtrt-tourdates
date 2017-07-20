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

  $daynumber = wpdtrt_tourdates_get_post_daynumber();

  wpdtrt_taxonomy_create(
    array(
      'slug' => 'tourdates',
      'fallback' => 'no-tourdates',
      'post_type_slug' => 'tourdiaryday',
      'description' => 'The count of a tour day relative to the tour date range',
      'label_single' => 'Tour Date',
      'label_plural' => 'Tour Dates',
      'hierarchical' => true,
      'public' => true,
      'label_prefix' => '',
      'terms' => array( $daynumber )
    )
  );
}

?>