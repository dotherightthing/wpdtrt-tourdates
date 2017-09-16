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
      'post_type' => '"tourdiaries"',
      'meta_key' => 'wpdtrt_tourdates_cf_daynumber',
      'loop' => false,
      'max_length' => 9999,
      'format' => '%link',
      'link' => '<span class="stack--navigation--text says">' . $tooltip_prefix . ': Day DAY_NUMBER</span> <span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span>',
      'tooltip' => $tooltip_prefix . ': Day DAY_NUMBER.',
      'in_same_tax' => 'tours',
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