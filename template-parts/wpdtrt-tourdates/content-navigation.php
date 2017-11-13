<?php
/**
 * The template part for displaying the next/previous post navigation
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/partials
 */
?>

<div class="wpdtrt-tourdates-navigation">
  <nav>
    <ul>
      <li class="wpdtrt-tourdates-navigation--previous">
        <?php echo $previous; ?>
      </li>
      <li class="wpdtrt-tourdates-navigation--current">
        <strong class="wpdtrt-tourdates-navigation--text">
          <span class="says">Current page: Day <?php echo $daynumber; ?></span>
          <span class="icon-directions_bike"></span>
        </strong>
      </li>
      <li class="wpdtrt-tourdates-navigation--next">
        <?php echo $next; ?>
      </li>
    </ul>
  </nav>
</div>
<!-- stack-navigation -->