<?php
/**
 * Setters
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

/**
 * Create a custom field when a post is saved,
 * which can be queried by the next/previous_post_link_plus plugin
 * and used in the Yoast page title via %%cf_wpdtrt_tourdates_daynumber%%,
 *
 * @see https://wordpress.org/support/topic/set-value-in-custom-field-using-post-by-email/
 * @see https://wordpress.stackexchange.com/questions/61148/change-slug-with-custom-field
 * @todo meta_key workaround requires each post to be resaved/updated, this is not ideal
 */
if ( !function_exists( 'wpdtrt_tourdates_set_daynumber' ) ) {

  add_action('save_post', 'wpdtrt_tourdates_set_daynumber');

  function wpdtrt_tourdates_set_daynumber() {

      global $post;

      // if Update button used in Quick Edit view
      if ( ! $post ) {
        return;
      }

      $post_id = $post->ID;

      if( ! wp_is_post_revision($post) ) {

      	$daynumber = wpdtrt_tourdates_get_post_daynumber($post_id);

      	// update_post_meta runs add_post_meta if the $meta_key does not already exist
        update_post_meta($post_id, 'wpdtrt_tourdates_cf_daynumber', $daynumber, true);

        // note: https://developer.wordpress.org/reference/functions/get_post_meta/#comment-1894
        //$test = get_post_meta($post_id, 'wpdtrt_tourdates_cf_daynumber', true);
      }
  }
}

