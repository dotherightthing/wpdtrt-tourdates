<?php
/**
 * Permalink - Placeholders
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
 * Support Custom Field %placeholders% in Custom Post Type permalinks
 * 	This replacement is only applied when the permalink is generated
 * 	eg on an archive listing or wpadmin edit page
 *	NOT in the rewrite rules / when the page is loaded
 *
 * @param $permalink See WordPress function options
 * @param $post See WordPress function options
 * @param $leavename See WordPress function options
 * @return $permalink
 *
 * @example
 * 	// wpdtrt-dbth/library/register_post_type_tourdiaries.php
 * 	'rewrite' => array(
 * 		'slug' => 'tourdiaries/%tours%/%wpdtrt_tourdates_cf_daynumber%'
 * 		'with_front' => false
 * 	)
 *
 * @see http://shibashake.com/wordpress-theme/add-custom-taxonomy-tags-to-your-wordpress-permalinks
 * @see http://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2#conflict
 * @see https://stackoverflow.com/questions/7723457/wordpress-custom-type-permalink-containing-taxonomy-slug
 * @see https://kellenmace.com/edit-slug-button-missing-in-wordpress/
 * @see http://kb.dotherightthing.dan/php/wordpress/missing-permalink-edit-button/
 */
//add_filter('post_link', 		'wpdtrt_tourdates_cf_permalink_placeholders', 10, 3); // Regular post
add_filter('post_type_link', 	'wpdtrt_tourdates_cf_permalink_placeholders', 10, 3); // Custom Post Type

function wpdtrt_tourdates_cf_permalink_placeholders($permalink, $post, $leavename) {

	// Get post
	$post_id = $post->ID;

	// extract all %placeholders% from the permalink
	// https://regex101.com/
	preg_match_all('/(?<=\/%wpdtrt_tourdates_cf_).+?(?=%\/)/', $permalink, $placeholders, PREG_OFFSET_CAPTURE);

	// placeholders in an array of taxonomy/term arrays
	foreach ( $placeholders[0] as $placeholder ) {

		$placeholder_name = 'wpdtrt_tourdates_cf_' . $placeholder[0];

		if ( metadata_exists( 'post', $post_id, $placeholder_name ) ) {
			$replacement = get_post_meta( $post_id, $placeholder_name, true );
			$permalink = str_replace( ( '%' . $placeholder_name . '%' ), $replacement, $permalink);
		}
	}

	return $permalink;
}

?>