<?php
/**
 * The template part for displaying the next/previous post navigation
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Elapsedday
 * @subpackage  Wpdtrt_Elapsedday/partials
 */
?>

<div class="wpdtrt-elapsedday-navigation">
  <nav>
    <ul>
      <li class="wpdtrt-elapsedday-navigation--previous">
        <?php echo $previous; ?>
      </li>
      <li class="wpdtrt-elapsedday-navigation--current">
        <strong class="wpdtrt-elapsedday-navigation--text">
          <span class="says">Current page: Day <?php echo $daynumber; ?></span>
          <span class="icon-directions_bike"></span>
        </strong>
      </li>
      <li class="wpdtrt-elapsedday-navigation--next">
        <?php $next; ?>
      </li>
    </ul>
  </nav>
</div>
<!-- stack-navigation -->