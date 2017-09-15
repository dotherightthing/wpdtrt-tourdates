<?php
/**
 * Taxonomies - Terms
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     WPDTRT_Tourdates
 * @subpackage  WPDTRT_Tourdates/app
 */

if ( !function_exists( 'wpdtrt_tourdates_order_tour_terms_by_date' ) ) {

	/**
	 * Sort term objects by start date
	 * @param {array} $tour_terms Array of terms (e.g. tour legs)
	 * @return {array} $tour_terms Sorted terms
	 */
	function wpdtrt_tourdates_order_tour_terms_by_date( $tour_terms ) {

		uasort ( $tour_terms , function ( $term_a, $term_b ) {
			$term_a_id = $term_a->term_id;
			$term_a_start_date = wpdtrt_tourdates_get_term_start_date( $term_a_id );

			$term_b_id = $term_b->term_id;
			$term_b_start_date = wpdtrt_tourdates_get_term_start_date( $term_b_id );

			return strnatcmp( $term_a_start_date, $term_b_start_date );
		});

		return $tour_terms;
	}
}

?>
