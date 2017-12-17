<?php
/**
 * The template part for displaying the background tour thumbnail
 *
 * @link        http://dotherightthing.co.nz
 * @since       1.0.0
 * @see 		http://www.mikejohnsondesign.com/add-wordpress-featured-image-as-background-image/
 * @see 		https://wordpress.org/support/topic/insert-featured-image-in-div-style-background
 * @see 		https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
 *
 * @package     WPDTRT_Tourdates
 */
?>

<?php

    $image_id = $this->get_meta_thumbnail_id( $term_id, $taxonomy );
    $featured_image_src = wp_get_attachment_image_src( $image_id, 'thumbnail', true, '' );

	if ( isset( $featured_image_src ) ):
?>

	<style id="style-post-<?php echo $term_id ? $term_id : ''; ?>">
		#post-<?php echo $term_id; ?> .stack--wrapper {
			background-image: url(<?php echo $featured_image_src[0]; ?> );
		}
	</style>

<?php
	endif;
?>
