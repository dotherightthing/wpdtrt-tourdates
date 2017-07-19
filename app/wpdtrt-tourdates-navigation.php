<?php
/**
 * Navigation
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
if ( !function_exists( 'wpdtrt_tourdates_daynumber_custom_field' ) ) {

  add_action('save_post', 'wpdtrt_tourdates_daynumber_custom_field');

  function wpdtrt_tourdates_daynumber_custom_field() {
      global $post;
      $post_id = $post->ID;

      if( ! wp_is_post_revision($post) ) {
        add_post_meta($post_id, 'cf_wpdtrt_tourdates_daynumber', wpdtrt_tourdates_get_post_daynumber($post_id), true);
      }
  }
}

if ( !function_exists( 'wpdtrt_tourdates_navigation_link' ) ) {

  /**
   * Link to next/previous post
   * @requires http://www.ambrosite.com/plugins/next-previous-post-link-plus-for-wordpress
   * @param $direction string previous|next
   * @todo Update to limit to the daycontroller category
   */
  function wpdtrt_tourdates_navigation_link($direction) {

    global $post;
    $id = $post->ID;

    $the_link = false;

    if ( $direction == 'previous' ) {
      $tooltip_prefix = 'Previous';
      $icon = 'left';
    }
    else if ( $direction == 'next' ) {
      $tooltip_prefix = 'Next';
      $icon = 'right';
    }

    $config = array(
      'order_by' => 'meta_key',
      'post_type' => '"tourdiaryday"',
      'meta_key' => 'cf_wpdtrt_tourdates_daynumber',
      'loop' => false,
      'max_length' => 9999,
      'format' => '%link',
      'link' => '<span class="stack--navigation--text says">' . $tooltip_prefix . ': Day DAY_NUMBER</span> <span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span>',
      'tooltip' => $tooltip_prefix . ': Day DAY_NUMBER.',
      'in_same_tax' => 'tour',
      'echo' => false
    );

    if ( $direction == 'previous' ) {
      $the_id = previous_post_link_plus( array('return' => 'id') );
      $the_daynumber = wpdtrt_tourdates_get_post_daynumber($the_id);
      $the_link = previous_post_link_plus( $config );
      $the_link = str_replace('DAY_NUMBER', $the_daynumber, $the_link);
    }
    else if ( $direction == 'next' ) {
      $the_id = next_post_link_plus( array('return' => 'id') );
      $the_daynumber = wpdtrt_tourdates_get_post_daynumber($the_id);
      $the_link = next_post_link_plus( $config );
      $the_link = str_replace('DAY_NUMBER', $the_daynumber, $the_link);
    }

    if ( !$the_link ) {
      $the_link = '<span class="a"><span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span></span>';
    }

    return $the_link;
  }
}

?>