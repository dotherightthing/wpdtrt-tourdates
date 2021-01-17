<?php
/**
 * Plugin sub class.
 *
 * @package WPDTRT_Tourdates
 * @since   0.7.17 DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Extend the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since   1.0.0
 */
class WPDTRT_Tourdates_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_7_9\Plugin {

	/**
	 * Supplement plugin initialisation.
	 *
	 * @param     array $options Plugin options.
	 * @since     1.0.0
	 * @version   1.1.0
	 */
	public function __construct( $options ) { // phpcs:ignore

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * ====== WordPress Integration ======
	 */

	/**
	 * Supplement plugin's WordPress setup.
	 * Note: Default priority is 10. A higher priority runs later.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference Action order
	 */
	protected function wp_setup() {

		// edit here.
		parent::wp_setup();

		// add actions and filters here.
		add_action( 'init', array( $this, 'set_rewrite_rules' ) );
		add_action( 'save_post', array( $this, 'save_post_daynumber' ), 10, 3 );
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * Get the total number of days in a tour
	 *
	 * @param int    $post_id Optional Post ID for testing.
	 * @param string $term_type Term type (tour|tour_leg).
	 * @return string
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_daytotal( $post_id = null, $term_type ) {

		if ( isset( $post_id ) ) {
			$post = get_post( $post_id );
		} else {
			global $post;
			$post_id = $post->ID;
		}

		$tour_start_date = $this->get_term_start_date( $post_id, $term_type );
		$tour_end_date   = $this->get_term_end_date( $post_id, $term_type );
		$day_total       = $this->get_term_days_elapsed( $tour_start_date, $tour_end_date );

		return $day_total;
	}

	/**
	 * Get the coordinates of a map location
	 *
	 * @param string $key The key of the JSON object.
	 * @return string "lat,lng" | ""
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_html_latlng( $key ) {

		// if options have not been stored, exit.
		$wpdtrt_tourdates_options = get_option( 'wpdtrt_tourdates' );

		if ( '' === $wpdtrt_tourdates_options ) {
			return '';
		}

		// the data set.
		$wpdtrt_tourdates_data = $wpdtrt_tourdates_options['wpdtrt_tourdates_data'];

		// user - map block.
		if ( isset( $wpdtrt_tourdates_data[ $key ]->{'address'} ) ) :

			$lat = $wpdtrt_tourdates_data[ $key ]->{'address'}->{'geo'}->{'lat'};
			$lng = $wpdtrt_tourdates_data[ $key ]->{'address'}->{'geo'}->{'lng'};
			$str = $lat . ',' . $lng;

		else :

			$str = '';

		endif;

		return $str;
	}

	/**
	 * Get the value of the disabled metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return mixed $disabled The disabled checkbox state (checked:1, unchecked:'')
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_meta_term_disabled( $term_id ) {

		$disabled = get_term_meta( $term_id, 'disabled', true );

		return $disabled;
	}

	/**
	 * Get the value of the end_date metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return string $end_date Y-n-j 00:01:00 (e.g. 2017-12-25 00:01:00)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_meta_term_end_date( $term_id ) {

		$end_date  = get_term_meta( $term_id, 'end_date', true );
		$end_date .= ' 00:01:00';

		return $end_date;
	}

	/**
	 * Get the value of the first_visit metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return mixed $first_visit The first_visit checkbox state (checked:1, unchecked:'')
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_meta_term_first_visit( $term_id ) {

		$first_visit = get_term_meta( $term_id, 'first_visit', true );

		return $first_visit;
	}

	/**
	 * Get the value of the start_date metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return string $start_date Y-n-j 00:01:00 (e.g. 2017-12-25 00:01:00)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_meta_term_start_date( $term_id ) {

		$start_date  = get_term_meta( $term_id, 'start_date', true );
		$start_date .= ' 00:01:00';

		return $start_date;
	}

	/**
	 * Get the value of the tour_type metadata/field attached to a particular term/tour.
	 * Used to calculate date offsets.
	 *
	 * @param number $term_id The ID of the term.
	 * @return string $term_type Term type (tour|tour_leg)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_meta_term_type( $term_id ) {

		$term_type = get_term_meta( $term_id, 'term_type', true );

		return $term_type;
	}

	/**
	 * Get the value of the thumbnail_id metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return string $term_thumbnail_id The thumbnail ID
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 * @todo Add a media library button
	 */
	public function get_meta_thumbnail_id( $term_id ) {

		$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

		return $thumbnail_id;
	}

	/**
	 * Get the value of the content_id metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return string $term_content_id The content ID
	 * @version 1.0.0
	 * @since 1.1.4
	 * @see TourdatesTest
	 */
	public function get_meta_content_id( $term_id ) {

		$content_id = get_term_meta( $term_id, 'content_id', true );

		return $content_id;
	}

	/**
	 * Get the value of the leg_count metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID.
	 * @return string $start_date The start date
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 * @todo calculate this instead, allowing for only unique legs
	 */
	public function get_meta_tour_category_leg_count( $term_id ) {

		$leg_count = get_term_meta( $term_id, 'leg_count', true );

		return $leg_count;
	}

	/**
	 * Get the number of a tour day, relative to the tour start date
	 * Note that the post has to be published on (for) the target date,
	 * else this will show the creation date
	 *
	 * @param number $post_id The post ID.
	 * @return mixed $post_daynumber The day number | false
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 * @todo Consider rewriting into a shortcode
	 */
	public function get_post_daynumber( $post_id ) {

		$post_daynumber  = false;
		$taxonomy        = $this->get_the_taxonomy();
		$term_type       = 'tour';
		$tour_start_date = $this->get_term_start_date( $post_id, $term_type );

		if ( ! $tour_start_date ) {
			return $post_daynumber;
		}

		$post_date      = get_the_date( 'Y-n-j 00:01:00', $post_id );
		$post_daynumber = $this->get_term_days_elapsed( $tour_start_date, $post_date );

		return $post_daynumber;
	}

	/**
	 * Get days elapsed since tour started
	 *
	 * @param number $start_date The start date.
	 * @param number $end_date The end date.
	 * @return number $tour_days_elapsed Days elapsed
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @see http://www.timeanddate.com/date/durationresult.html?d1=2&m1=9&y1=2015&d2=30&m2=6&y2=2016
	 * @see TourdatesTest
	 */
	public function get_term_days_elapsed( $start_date, $end_date ) {
		// http://stackoverflow.com/a/3923228.
		$date1 = new DateTime( $start_date );
		$date2 = new DateTime( $end_date );

		if ( $date1 === $date2 ) {
			$tour_days_elapsed = 1;
		} else {
			$interval          = $date1->diff( $date2 );
			$tour_days_elapsed = $interval->format( '%r%a' ); // ->d only gets days in the same month
		}

		return $tour_days_elapsed + 1;
	}

	/**
	 * Get the term ID
	 *
	 * @param number $id The ID of the post OR term.
	 * @param string $term_type Term type (tour|tour_leg).
	 * @return number $term_id || 0
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_term_id( $id, $term_type ) {

		$taxonomy = $this->get_the_taxonomy();

		// if $id is the ID of a term in the $taxonomy
		// then this is a tour leg
		// and we are getting the tour leg date range
		// term_exists( $term, $taxonomy, $parent ).
		if ( term_exists( $id, $taxonomy ) ) { // phpcs:ignore

			$term_term_type = $this->get_meta_term_type( $id );

			// if the supplied ID uses the supplied type, then use the supplied ID.
			if ( $term_term_type === $term_type ) {
				$term_id = $id;
			} else {
				// else use the hierarchical parent's ID (tour_leg -> tour)
				// else use the hierarchical parent's ID (tour -> region).
				$term = get_term_by( 'id', $id, $taxonomy );

				if ( isset( $term->parent ) ) {
					$parent_term_id = $term->parent;
					$term_id        = $parent_term_id;
				}
			}
		} elseif ( $this->helper_post_exists( $id ) ) {

			// else if it isn't then the $id is the ID of a tour day post
			// and we are getting the start date for term_days_elapsed daynumber
			//
			// https://github.com/dotherightthing/wpdtrt-tourdates/issues/14.
			if ( get_post_type( $id ) !== 'tourdiaries' ) {
				$term_id = false;
			}

			// get associated taxonomy_terms
			// get_the_category() doesn't work with custom post type taxonomies.
			$terms = get_the_terms( $id, $taxonomy );

			// if one or more terms were assigned.
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {

					$tested_term_id = $term->term_id;
					$term_term_type = $this->get_meta_term_type( $tested_term_id );

					if ( $term_term_type === $term_type ) {
						$term_id = $tested_term_id;
						break;
					}
				}
			}
		}

		if ( ! isset( $term_id ) ) {

			$messages                   = $this->get_messages();
			$post_terms_missing_message = $messages['post_terms_missing'];
			$term_id                    = new WP_Error(
				'terms',
				str_replace( 'N', $id, $post_terms_missing_message )
			);
		}

		return $term_id;
	}

	/**
	 * Get the last date in a tour
	 *
	 * @param number $id Post ID or Term ID.
	 * @param string $term_type Term type (tour|tour_leg).
	 * @param string $date_format An optional PHP date format.
	 * @return string $tour_end_date The date when the tour ended (Y-n-j 00:01:00)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_term_end_date( $id, $term_type, $date_format = null ) {

		$term_id = $this->get_term_id( $id, $term_type );

		if ( is_wp_error( $term_id ) ) {
			error_log( $term_id->get_error_message() );
			$tour_end_date = '';
		} else {
			$tour_end_date = $this->get_meta_term_end_date( $term_id );

			if ( null !== $date_format ) {
				$date          = new DateTime( $tour_end_date );
				$tour_end_date = date_format( $date, $date_format );
			}
		}

		return $tour_end_date;
	}

	/**
	 * Get the end month & year in a tour
	 *
	 * @param number $term_id The term ID.
	 * @return string $tour_leg_end_month The month when the tour ended (Month YYYY)
	 * @see TourdatesTest
	 */
	public function get_term_end_month( $term_id ) {
		$term_type          = $this->get_meta_term_type( $term_id );
		$tour_leg_end_month = $this->get_term_end_date( $term_id, $term_type, 'F Y' );

		return $tour_leg_end_month;
	}

	/**
	 * Get the number of unique tour legs
	 *
	 * @param number $term_id The Term ID.
	 * @param string $text_before Text to display if more than one leg.
	 * @param string $text_after Text to display if more than one leg.
	 * @return string $tour_leg_count The number of unique tour legs
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 * @todo wpdtrt_tourdates_acf_tour_category_leg_count can be determined from filtering child categories to wpdtrt_tourdates_acf_tour_category_first_visit
	 */
	public function get_term_leg_count( $term_id, $text_before = '', $text_after = '' ) {
		$tour_leg_count = $this->get_meta_tour_category_leg_count( $term_id );

		if ( $tour_leg_count > 1 ) {
			$str            = $text_before . $tour_leg_count . $text_after;
			$tour_leg_count = $str;
		} else {
			$tour_leg_count = ''; // new zealand tour legs are tours.
		}

		return $tour_leg_count;
	}

	/**
	 * Get the name of a tour leg
	 *
	 * @param string $tour_leg_slug The slug of the tour leg.
	 * @return string $tour_leg_name The name of the tour leg
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
	 * @see https://codex.wordpress.org/Function_Reference/get_term_by
	 * @see TourdatesTest
	 */
	public function get_term_leg_name( $tour_leg_slug ) {

		$tour_leg_name = '';
		$taxonomy      = $this->get_the_taxonomy();
		$tour_leg      = get_term_by( 'slug', $tour_leg_slug, $taxonomy );
		$tour_leg_name = $tour_leg->name;

		return $tour_leg_name;
	}

	/**
	 * Get the ID of a tour leg
	 *
	 * @param string $tour_leg_slug The slug of the tour leg.
	 * @return string $tour_leg_id The ID of the tour leg
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
	 * @see https://codex.wordpress.org/Function_Reference/get_term_by
	 * @see TourdatesTest
	 */
	public function get_term_leg_id( $tour_leg_slug ) {

		$taxonomy    = $this->get_the_taxonomy();
		$tour_leg    = get_term_by( 'slug', $tour_leg_slug, $taxonomy );
		$tour_leg_id = $tour_leg->term_id;

		return $tour_leg_id;
	}

	/**
	 * Get the first date in a tour
	 *
	 * @param number $id The ID of the post OR term.
	 * @param string $term_type Term type (tour|tour_leg).
	 * @param string $date_format An optional date format.
	 * @return string $term_start_date The date when the tour started (Y-n-j 00:01:00)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_term_start_date( $id, $term_type, $date_format = null ) {

		$term_id = $this->get_term_id( $id, $term_type );

		if ( is_wp_error( $term_id ) ) {
			error_log( $term_id->get_error_message() );
			$term_start_date = '';
		} else {
			$term_start_date = $this->get_meta_term_start_date( $term_id );

			if ( null !== $date_format ) {
				$date            = new DateTime( $term_start_date );
				$term_start_date = date_format( $date, $date_format );
			}
		}

		return $term_start_date;
	}

	/**
	 * Get the first day in a tour type
	 * If this is a tour leg, calculate how many days it starts,
	 * after the tour starts
	 *
	 * @param number $term_id The term ID.
	 * @return number $tour_start_day The day when the tour started
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_term_start_day( $term_id ) {

		$taxonomy  = $this->get_the_taxonomy();
		$term      = get_term_by( 'id', $term_id, $taxonomy );
		$term_type = $this->get_meta_term_type( $term_id );

		if ( 'tour' === $term_type ) {
			$tour_start_day = 1;
		} elseif ( 'tour_leg' === $term_type ) {
			$parent_term_id      = $term->parent;
			$tour_start_date     = $this->get_term_start_date( $parent_term_id, 'tour' );
			$tour_leg_start_date = $this->get_term_start_date( $term_id, $term_type );
			$tour_start_day      = $this->get_term_days_elapsed( $tour_start_date, $tour_leg_start_date );
		}

		// TODO: this assumes that it has been successfully set.
		return $tour_start_day;
	}

	/**
	 * Get the start month & year in a tour
	 *
	 * @param number $term_id The term ID.
	 * @return string $tour_leg_start_month The month when the tour started (Month YYYY)
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_term_start_month( $term_id ) {
		$term_type            = $this->get_meta_term_type( $term_id );
		$tour_leg_start_month = $this->get_term_start_date( $term_id, $term_type, 'F Y' );

		return $tour_leg_start_month;
	}

	/**
	 * Get the taxonomy
	 * Different from get_taxonomy()
	 *
	 * @return string $taxonomy The taxonomy
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_the_taxonomy() {

		$taxonomy = get_query_var( 'taxonomy' );

		// if called from a unit test.
		if ( ! isset( $taxonomy ) || ( '' === $taxonomy ) ) {
			$taxonomy = 'wpdtrt_tourdates_taxonomy_tour';
		}

		return $taxonomy;
	}

	/**
	 * Get tour length in days
	 *
	 * @param number $term_id The term ID.
	 * @param string $text_before Translatable text displayed before the tour length.
	 * @param string $text_after Translatable text displayed after the tour length.
	 * @return string $tour_length_days The length of the tour
	 * @version 1.0.0
	 * @since 1.0.0
	 * @see TourdatesTest
	 */
	public function get_tourlengthdays( $term_id, $text_before = '', $text_after = '' ) {
		// convert shortcode argument to a number.
		if ( isset( $term_id ) ) {
			$term_id = (int) $term_id;
		}

		$term_type        = $this->get_meta_term_type( $term_id );
		$tour_start_date  = $this->get_term_start_date( $term_id, $term_type );
		$tour_end_date    = $this->get_term_end_date( $term_id, $term_type );
		$tour_length_days = $this->get_term_days_elapsed( $tour_start_date, $tour_end_date );

		return $text_before . $tour_length_days . $text_after;
	}

	/**
	 * Only process posts which use our custom post type
	 *
	 * @param number $post_id The Post ID.
	 * @return boolean
	 * @see TourdatesTest
	 */
	public function post_has_required_posttype( $post_id ) {

		$post_type = get_post_type( $post_id );

		if ( 'tourdiaries' !== $post_type ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * The get_post_daynumber() call stack requires access to $term_id.
	 *  this requires that 3 terms are assigned: region, tour, tour_leg
	 *
	 * @param number $post_id The Post ID.
	 * @return boolean
	 * @see https://github.com/dotherightthing/wpdtrt-tourdates/issues/12
	 * @see TourdatesTest
	 */
	public function post_has_required_terms( $post_id ) {

		$taxonomy        = $this->get_the_taxonomy();
		$post_terms      = wp_get_post_terms( $post_id, $taxonomy );
		$post_term_types = array();

		foreach ( $post_terms as $term ) {
			$term_type                     = $this->get_meta_term_type( $term->term_id );
			$post_term_types[ $term_type ] = true;
		}

		if ( array_key_exists( 'tour_leg', $post_term_types ) && array_key_exists( 'tour', $post_term_types ) && array_key_exists( 'region', $post_term_types ) ) {
			$required_terms = true;
		} elseif ( array_key_exists( 'tour', $post_term_types ) && array_key_exists( 'region', $post_term_types ) ) {
			$required_terms = true;
		} else {
			$required_terms = false;
		}

		return $required_terms;
	}

	/**
	 * Create a custom field when a post is saved, updated, or updated via Quick Edit
	 * which can be queried by the next/previous_post_link_plus plugin
	 * and used in the Yoast page title via %%cf_wpdtrt_tourdates_daynumber%%,
	 * and used in the permalink slug 'tourdiaries/%tours%/%wpdtrt_tourdates_cf_daynumber%' (wpdtrt-dbth/library/register_post_type_tourdiaries.php)
	 *
	 * Use the Query Monitor plugin to view the Post type
	 *
	 * @param int  $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 * @link wpdtrt/library/permalink-placeholders.php
	 * @link wpdtrt-dbth/library/register_post_type_tourdiaries
	 * @see https://wordpress.org/support/topic/set-value-in-custom-field-using-post-by-email/
	 * @see https://wordpress.stackexchange.com/questions/61148/change-slug-with-custom-field
	 * @todo meta_key workaround requires each post to be resaved/updated, this is not ideal
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Custom_Post_Type:_.27book.27
	 * @see TourdatesTest
	 */
	public function save_post_daynumber( $post_id, $post, $update ) {

		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		if ( ! $this->post_has_required_posttype( $post_id ) ) {
			return;
		}

		// terms might not be added until later (save > edit > update > edit > update..).
		if ( ! $this->post_has_required_terms( $post_id ) ) {
			return;
		}

		$daynumber = $this->get_post_daynumber( $post_id );

		if ( $daynumber ) {
			// update_post_meta also runs add_post_meta, if the $meta_key does not already exist.
			update_post_meta( $post_id, 'wpdtrt_tourdates_cf_daynumber', $daynumber );
		}

		// note: https://developer.wordpress.org/reference/functions/get_post_meta/#comment-1894
		// $test = get_post_meta($post_id, 'wpdtrt_tourdates_cf_daynumber', true); // true = return single value.
	}

	/**
	 * ===== Renderers =====
	 */

	/**
	 * Link to next/previous post
	 * Used by $wpdtrt_tourdates_shortcode_navigation
	 *
	 * @param string $direction previous|next.
	 * @param string $posttype Post type.
	 * @return string HTML hyperlink | ''
	 * @uses http://www.ambrosite.com/plugins/next-previous-post-link-plus-for-wordpress
	 * @see TourdatesTest
	 */
	public function render_navigation_link( $direction, $posttype ) {

		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		// plugin dependency.
		if ( ! function_exists( 'previous_post_link_plus' ) ) {
			return;
		}

		$post_id  = $post->ID;
		$taxonomy = $this->get_the_taxonomy();
		$the_link = '';

		if ( 'previous' === $direction ) {
			$tooltip_prefix = 'Previous';
			$icon           = 'left';
		} elseif ( 'next' === $direction ) {
			$tooltip_prefix = 'Next';
			$icon           = 'right';
		}

		$config = array(
			'order_by'    => 'meta_key',
			'post_type'   => '"' . $posttype . '"',
			'meta_key'    => 'wpdtrt_tourdates_cf_daynumber', // phpcs:ignore
			'loop'        => false,
			'max_length'  => 9999,
			'format'      => '%link',
			'link'        => '<span class="stack--navigation__text says">' . $tooltip_prefix . ': Day DAY_NUMBER</span> <span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span>',
			'tooltip'     => $tooltip_prefix . ': Day DAY_NUMBER.',
			'in_same_tax' => $taxonomy,
			'echo'        => false,
		);

		$current_daynumber = $this->get_post_daynumber( $post_id );

		if ( 'previous' === $direction ) {
			$the_id             = previous_post_link_plus( array( 'return' => 'id' ) );
			$adjacent_daynumber = $this->get_post_daynumber( $the_id );

			// Prevent navigation between different tours.
			if ( ( $adjacent_daynumber > 0 ) && ( $adjacent_daynumber < $current_daynumber ) ) {
				$the_link = previous_post_link_plus( $config );
				$the_link = str_replace( 'DAY_NUMBER', $adjacent_daynumber, $the_link );
			}
		} elseif ( 'next' === $direction ) {
			$the_id             = next_post_link_plus( array( 'return' => 'id' ) );
			$adjacent_daynumber = $this->get_post_daynumber( $the_id );

			// Prevent navigation between different tours.
			if ( ( $adjacent_daynumber > 0 ) && ( $adjacent_daynumber > $current_daynumber ) ) {
				$the_link = next_post_link_plus( $config );
				$the_link = str_replace( 'DAY_NUMBER', $adjacent_daynumber, $the_link );
			}
		}

		if ( ! $the_link ) {
			$the_link = '<span class="a"><span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span></span>';
		}

		return $the_link;
	}

	/**
	 * ===== Filters =====
	 */

	/**
	 * Add the day to an attachment image page
	 *
	 * @param   string $attachment_title Attachment title.
	 * @param   string $parent_title Parent title.
	 * @param   number $parent_id Parent ID.
	 * @deprecated Not currently used
	 */
	public function filter_attachment_title_add_day( $attachment_title, $parent_title, $parent_id ) {

		// http://php.net/manual/en/functions.arguments.php
		// if ( is_null($id) ) {
		// $day = get_field('acf_daynumber');
		// }
		// else {
		// $day = get_post_field('acf_daynumber', $id);
		// }.
		global $post;

		$parent_day       = $this->get_post_daynumber( $parent_id );
		$attachment_title = wpdtrt_tourdates_attachment_title_remove_day( $attachment_title );
		$title_text       = 'Gallery image';

		if ( $attachment_title ) {
			$title_text .= ': ' . $attachment_title;
		}

		$day_html   = '<span class="wpdtrt-tourdates-day"><span class="wpdtrt-tourdates-day__day">Day </span><span class="wpdtrt-tourdates-day__number">' . $parent_day . ': ' . $parent_title . '</span><span class="wpdtrt-tourdates-day__period">. </span></span>';
		$title_html = '<span class="wpdtrt-tourdates-day__title">' . $title_text . '</span>';

		return $day_html . $title_html;
	}

	/**
	 * Remove the day from an attachment image page
	 *
	 * @param string $attachment_title Attachment title.
	 * @param string $fallback Fallback.
	 * @deprecated Not currently used
	 */
	public function filter_attachment_title_remove_day( $attachment_title = '', $fallback = '' ) {

		$regex    = '/<span class="wpdtrt-tourdates-day">.*<\/span><span class="wpdtrt-tourdates-day__title">/';
		$output   = preg_replace( $regex, '<span class="wpdtrt-tourdates-day__title">', $attachment_title );
		$html_len = strlen( '<span class="wpdtrt-tourdates-day__title"></span>' );
		$output   = trim( $output );

		if ( ( strlen( $output ) - $html_len ) === 0 ) {
			$output = $fallback;
		}

		return $output;
	}

	/**
	 * ===== Helpers =====
	 */

	/**
	 * Sort term IDs by start date
	 *
	 * @param {array} $tour_term_ids Array of term IDs (e.g. tour legs).
	 * @return {array} $tour_term_ids Sorted terms
	 * @see https://stackoverflow.com/a/22231045/6850747
	 * @see TourdatesTest
	 */
	public function helper_order_tour_terms_by_date( $tour_term_ids ) {

		// usort: Sort an array with a user-defined comparison function
		// uasort: and maintain index association (not reqd & fails comparison unit test as keys are shuffled)
		// @usort: suppress PHP Warning: usort(): Array was modified by the user comparison function.
		@usort( $tour_term_ids, function( $term_a_id, $term_b_id ) { // phpcs:ignore

			$term_type_a       = $this->get_meta_term_type( $term_a_id );
			$term_type_b       = $this->get_meta_term_type( $term_b_id );
			$term_a_start_date = $this->get_term_start_date( $term_a_id, $term_type_a );
			$term_b_start_date = $this->get_term_start_date( $term_b_id, $term_type_b );

			// compare strings using a 'natural order' algorithm.
			return strnatcmp( $term_a_start_date, $term_b_start_date );
		});

		return $tour_term_ids;
	}

	/**
	 * Sort terms into hierarchical order
	 * Sort terms into hierarchical order,
	 * so that we can get the tour rather than the tour_leg.
	 *
	 * Has parent: $term->parent === n
	 * No parent: $term->parent === 0
	 *
	 * @param {array} $tour_terms Array of terms (e.g. tour legs).
	 * @return {array} $tour_terms Sorted terms
	 * @see https://developer.wordpress.org/reference/functions/get_the_terms/
	 * @see https://wordpress.stackexchange.com/questions/172118/get-the-term-list-by-hierarchy-order
	 * @see https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
	 * @see https://wpseek.com/function/_get_term_hierarchy/
	 * @see https://wordpress.stackexchange.com/questions/137926/sorting-attributes-order-when-using-get-the-terms
	 * @uses WPDTRT helpers/permalinks.php
	 * @see TourdatesTest
	 */
	public function helper_order_tour_terms_by_hierarchy( $tour_terms ) {

		// usort: Sort an array with a user-defined comparison function
		// uasort: and maintain index association (not reqd & fails comparison unit test as keys are shuffled)
		// @usort: suppress PHP Warning: usort(): Array was modified by the user comparison function.
		@usort( $tour_term, function( $term_a, $term_b ) { // phpcs:ignore

			$term_a_parent = $term_a->parent;
			$term_b_parent = $term_b->parent;

			// compare strings using a 'natural order' algorithm.
			return strnatcmp( $term_a_parent, $term_b_parent );
		});

		return $tour_term_ids;
	}

	/**
	 * Determines if a post, identified by the specified ID, exist
	 * within the WordPress database.
	 *
	 * @param    int $post_id The ID of the post to check.
	 * @return   bool
	 * @since    1.0.0
	 * @uses     https://tommcfarlin.com/wordpress-post-exists-by-id/
	 */
	public function helper_post_exists( $post_id ) {
		return is_string( get_post_status( $post_id ) );
	}

	/**
	 * Add custom rewrite rules
	 * WordPress allows theme and plugin developers to programmatically specify new, custom rewrite rules.
	 *
	 * @since       1.0.0
	 * @version     1.0.0
	 * @see http://clivern.com/how-to-add-custom-rewrite-rules-in-wordpress/
	 * @see https://www.pmg.com/blog/a-mostly-complete-guide-to-the-wordpress-rewrite-api/
	 * @see https://www.addedbytes.com/articles/for-beginners/url-rewriting-for-beginners/
	 * @see http://codex.wordpress.org/Rewrite_API
	 * @see https://www.daggerhart.com/wordpress-rewrite-api-examples/
	 */
	public function set_rewrite_rules() {

		global $wp_rewrite;

		/**
		 * Separate out our custom field, to prevent it from breaking the %tourdiaries% CPT regex
		 * When regex is broken, monkeyman-rewrite-analyzer reports 'Regex is empty!'
		 *
		 * @param $tag %tagname%
		 * @param $regex A regex to validate the value of the tag
		 * @param $query Append query to queryreplace property array (optional)
		 * @see https://codex.wordpress.org/Rewrite_API/add_rewrite_tag
		 * @see $this->save_post_daynumber()
		 */
		$wp_rewrite->add_rewrite_tag(
			'%wpdtrt_tourdates_cf_daynumber%',
			'([^/]+)', // get one or more of any character except slash.
			'wpdtrt_tourdates_cf_daynumber='
		);
	}
}
