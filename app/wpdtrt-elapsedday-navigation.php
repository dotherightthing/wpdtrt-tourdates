<?php
/**
 * Navigation
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/app
 */

if ( !function_exists( 'wpdtrt-elapsedday-navigation_link' ) ) {

  /**
   * Link to next/previous post
   * @requires http://www.ambrosite.com/plugins/next-previous-post-link-plus-for-wordpress
   * @param $direction string previous|next
   * @todo Update to limit to the daycontroller category
   */
  function wpdtrt_elapsedday_navigation_link($direction) {

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
      'order_by' => 'custom', // order by 'meta_key'
      //'meta_key' => 'post_date', // ACF 'acf_daynumber' field
      'loop' => false,
      'max_length' => 9999,
      'format' => '%link',
      'link' => '<span class="stack--navigation--text says">' . $tooltip_prefix . ': Day %meta</span> <span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span>',
      'tooltip' => $tooltip_prefix . ': Day DAY_NUMBER.', // %meta = meta_key
      'in_same_tax' => 'elapsedday',
      //'ex_posts' => $ex_posts
      'echo' => false
    );

    if ( $direction == 'previous' ) {
      $the_id = previous_post_link_plus( array('return' => 'id') );
      $the_daynumber = wpdtrt_elapsedday_get_post_daynumber($the_id);
      $the_link = previous_post_link_plus( $config );
      $the_link = str_replace('DAY_NUMBER', $the_daynumber, $the_link);
    }
    else if ( $direction == 'next' ) {
      $the_id = next_post_link_plus( array('return' => 'id') );
      $the_daynumber = wpdtrt_elapsedday_get_post_daynumber($the_id);
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