<?php
/**
 * The template part for displaying the tour length in days
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 */
?>

<?php
	$tourlengthdays = $this->get_tourlengthdays( $term_id, $text_before, $text_after );
  	echo $tourlengthdays;
?>
