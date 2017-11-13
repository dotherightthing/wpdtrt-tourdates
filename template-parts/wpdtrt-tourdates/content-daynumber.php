<?php
/**
 * The template part for displaying the daynumber
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/partials
 */
?>

<?php
	global $post;
	$post_id = $post->ID;
	$daynumber = $this->get_post_daynumber($post_id);
	echo $daynumber;
?>
