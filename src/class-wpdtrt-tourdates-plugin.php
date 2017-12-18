<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_tourdates
 * @since       1.0.0
 * @version 	1.0.0
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since       1.0.0
 * @version 	1.0.0
 */
class WPDTRT_TourDates_Plugin extends DoTheRightThing\WPPlugin\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_TourDates_Plugin
     *
     * @param     array $settings Plugin options
     *
     * @version   1.1.0
     * @since     1.0.0
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-blocks here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    //// START WORDPRESS INTEGRATION \\\\

    /**
     * Initialise plugin options ONCE.
     *
     * @param array $default_options
     *
     * @since 1.0.0
     *
     * @todo update
     * @todo support this function in child plugin
     */
    protected function wp_setup() {
		add_action( 'post_type_link', 	[$this, 'render_permalink_placeholders'], 10, 3 ); // Custom Post Type
		add_action( 'init', 			[$this, 'set_rewrite_rules'] );
		add_action( 'save_post', 		[$this, 'save_post_daynumber'], 10, 3 );
		add_filter( 'the_title', 		[$this, 'filter_post_title_add_day'] );
    }

    //// END WORDPRESS INTEGRATION \\\\

    //// START SETTERS AND GETTERS \\\\

	/**
	 * Get the value of the leg_count metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID
	 * @return string $start_date The start date
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 * @todo calculate this instead, allowing for only unique legs
	 */
	public function get_meta_tour_category_leg_count( $term_id ) {

	  $leg_count = get_term_meta( $term_id, 'leg_count', true );

	  return $leg_count;
	}

	/**
	 * Get the value of the start_date metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID
	 * @return string $start_date Y-n-j 00:01:00 (e.g. 2017-12-25 00:01:00)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_meta_term_start_date( $term_id ) {

	  $start_date = get_term_meta( $term_id, 'start_date', true );
	  $start_date .= ' 00:01:00';

	  return $start_date;
	}

	/**
	 * Get the value of the end_date metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID
	 * @return string $end_date Y-n-j 00:01:00 (e.g. 2017-12-25 00:01:00)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_meta_term_end_date( $term_id ) {

	  $end_date = get_term_meta( $term_id, 'end_date', true );
	  $end_date .= ' 00:01:00';

	  return $end_date;
	}

	/**
	 * Get the value of the thumbnail_id metadata/field attached to a particular term/tour
	 *
	 * @param number $term_id The Term ID
	 * @return string $term_thumbnail_id The thumbnail ID
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 * @todo Add a media library button
	 */
	public function get_meta_thumbnail_id( $term_id ) {

	  $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

	  return $thumbnail_id;
	}

	/**
	 * Get the value of the tour_type metadata/field attached to a particular term/tour
	 *
	 *  Used to calculate date offsets.
	 *
	 * @param number $id The ID of the term
	 * @return string $term_type Term type (tour|tour_leg)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_meta_term_type( $term_id ) {

	  $term_type = get_term_meta( $term_id, 'term_type', true );

	  return $term_type;
	}

	/**
	 * Get the taxonomy
	 * 	Different from get_taxonomy()
	 *
	 * @return string $taxonomy The taxonomy
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_taxonomy
	 */
	public function get_the_taxonomy() {

		$taxonomy = get_query_var( 'taxonomy' );

		// if called from a unit test
		if ( !isset( $taxonomy ) || ( $taxonomy === '' ) ) {
			$taxonomy = 'wpdtrt_tourdates_taxonomy_tour';
		}

		return $taxonomy;
	}

	/**
	 * Get the term tour ID for a specific post so that we can in turn get the tour daynumber
	 *
	 * @param int $post_id The post ID
	 * @param string $term_type Term type (tour|tour_leg)
	 * @return number $term_id The term tour ID || false
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 * @todo This is now a category level option rather than ACF
	 */
	public function get_post_term_id( $post_id, $term_type ) {

	  $term_id = false;
	  $taxonomy = $this->get_the_taxonomy();

	  // get associated taxonomy_terms
	  // get_the_category() doesn't work with custom post type taxonomies
	  $terms = get_the_terms( $post_id, $taxonomy );

	  if ( is_array( $terms ) ) {

	  	// sort terms into hierarchical order
	  	// to get the tour rather than the tour_leg
	    //$terms = $this->helper_order_tour_terms_by_hierarchy( $terms );

	    //if ( !is_wp_error( $terms ) ) {
	      foreach ( $terms as $term ) {
	      //  if ( !empty( $term ) && is_object( $term ) ) {

	          $term_id = $term->term_id;
	          $term_term_type = $this->get_meta_term_type( $term_id );

	          if ( $term_term_type === $term_type ) {
	            break;
	          }
	        //}
	      }
	    }
	  //}

	  return $term_id;
	}

	/**
	 * Get the number of a tour day, relative to the tour start date
	 * Note that the post has to be published on (for) the target date,
	 * else this will show the creation date
	 * @param number $post_id The post ID
	 * @return number $post_daynumber The day number
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_post
	 * @todo Consider rewriting into a shortcode
	 */
	public function get_post_daynumber($post_id) {

		$post_daynumber = false;
		$term_type = 'tour';

		$tour_start_date = $this->get_term_start_date( $post_id, $term_type );

		if ( ! $tour_start_date ) {
			return $post_daynumber;
		}

		$post_date = get_the_date( "Y-n-j 00:01:00", $post_id );
		$post_daynumber = $this->get_term_days_elapsed( $tour_start_date, $post_date );

		return $post_daynumber;
	}

	/**
	 * Get the term ID
	 *
	 * @param number $id The ID of the post OR term
	 * @param string $term_type Term type (tour|tour_leg)
	 * @return number $term_id || false
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @see TourdatesTest\test_post
	 * @todo TourdatesTest\test_tour_term
	 * @todo TourdatesTest\test_tour_leg_term
	 */
	public function get_term_id($id, $term_type) {

		$taxonomy = $this->get_the_taxonomy();
		$term_id = false;

		// if $id is the ID of a term in the $taxonomy
		// then this is a tour leg
		// and we are getting the tour leg date range
		// term_exists( $term, $taxonomy, $parent )
		if ( term_exists( $id, $taxonomy ) ) {

			$term_term_type = $this->get_meta_term_type( $id );

			// if the supplied ID uses the supplied type, then use the supplied ID
			if ( $term_type === $term_term_type ) {
				$term_id = $id;
			}
			// else use the hierarchical parent's ID (tour_leg -> tour)
			else {
				$term = get_term_by( 'id', $id, $taxonomy );
				$parent_term_id = $term->parent;
				$term_id = $parent_term_id;
			}
		}
		// else if it isn't then the $id is the ID of a tour day post
		// and we are getting the start date for term_days_elapsed daynumber
		else {
			// when this is called by add_filter( 'the_title', 'wpdtrt_tourdates_post_title_add_day' )
			// then the term is not passed
			$term_id = $this->get_post_term_id( $id, $term_type );
		}

		return $term_id;
	}

	/**
	 * Get the first date in a tour
	 *
	 * @param number $id The ID of the post OR term
	 * @param string $term_type Term type (tour|tour_leg)
	 * @param string $date_format An optional date format
	 * @return string $term_start_date The date when the tour started (Y-n-j 00:01:00)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_start_date($id, $term_type, $date_format=null) {

		$term_start_date = false;
		$term_id = $this->get_term_id( $id, $term_type );

		$term_start_date = $this->get_meta_term_start_date( $term_id );

		if ( $date_format !== null ) {
			$date = new DateTime($term_start_date);
			$term_start_date = date_format($date, $date_format);
		}

		return $term_start_date;
	}

	/**
	 * Get the first day in a tour type
	 * If this is a tour leg, calculate how many days it starts,
	 * after the tour starts
	 *
	 * @param number $term_id The term ID
	 * @return number $tour_start_day The day when the tour started
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_start_day( $term_id ) {

		$taxonomy = $this->get_the_taxonomy();
		$term = get_term_by( 'id', $term_id, $taxonomy );
		$term_type = $this->get_meta_term_type( $term_id );

		if ( $term_type === 'tour' ) {
			$tour_start_day = 1;
		}
		else if ( $term_type === 'tour_leg' ) {
			$parent_term_id = $term->parent;
			$tour_start_date =      $this->get_term_start_date( $parent_term_id, 'tour' );
			$tour_leg_start_date =  $this->get_term_start_date( $term_id, $term_type );
			$tour_start_day =       $this->get_term_days_elapsed( $tour_start_date, $tour_leg_start_date );
		}

		// TODO: this assumes that it has been successfully set
		return $tour_start_day;
	}

	/**
	 * Get the last date in a tour
	 *
	 * @param number $id Post ID or Term ID
	 * @param string $term_type Term type (tour|tour_leg)
	 * @param string $date_format An optional PHP date format
	 * @return string $tour_end_date The date when the tour ended (Y-n-j 00:01:00)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_end_date($id, $term_type, $date_format=null) {

		$term_id = $this->get_term_id( $id, $term_type );

		$tour_end_date = $this->get_meta_term_end_date( $term_id );

		if ( $date_format !== null ) {
			$date = new DateTime($tour_end_date);
			$tour_end_date = date_format($date, $date_format);
		}

		return $tour_end_date;
	}

	/**
	 * Get the start month & year in a tour
	 *
	 * @param number $term_id The term ID
	 * @return string $tour_leg_start_month The month when the tour started (Month YYYY)
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_start_month( $term_id ) {
		$term_type = $this->get_meta_term_type( $term_id );
		$tour_leg_start_month = $this->get_term_start_date($term_id, $term_type, 'F Y');

		return $tour_leg_start_month;
	}

	/**
	 * Get the end month & year in a tour
	 *
	 * @param number $term_id The term ID
	 * @return string $tour_leg_end_month The month when the tour ended (Month YYYY)
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_end_month( $term_id ) {
		$term_type = $this->get_meta_term_type( $term_id );
		$tour_leg_end_month = $this->get_term_end_date($term_id, $term_type, 'F Y');

		return $tour_leg_end_month;
	}

	/**
	 * Get the number of unique tour legs
	 *
	 * @param number $term_id The Term ID
	 * @param string $text_before Text to display if more than one leg
	 * @param string $text_after Text to display if more than one leg
	 * @return string $tour_leg_count The number of unique tour legs
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 * @todo wpdtrt_tourdates_acf_tour_category_leg_count can be determined from filtering child categories to wpdtrt_tourdates_acf_tour_category_first_visit
	 */
	public function get_term_leg_count($term_id, $text_before='', $text_after='') {
		$tour_leg_count = $this->get_meta_tour_category_leg_count( $term_id );

		if ( $tour_leg_count > 1 ) {
			$str = $text_before . $tour_leg_count . $text_after;
			$tour_leg_count = $str;
		}
		else {
			$tour_leg_count = ''; // new zealand tour legs are tours
		}

		return $tour_leg_count;
	}

	/**
	 * Get days elapsed since tour started
	 *
	 * @param number $start_date The start date
	 * @param number $end_date The end date
	 * @return number $tour_days_elapsed Days elapsed
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see http://www.timeanddate.com/date/durationresult.html?d1=2&m1=9&y1=2015&d2=30&m2=6&y2=2016
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_days_elapsed($start_date, $end_date) {
		// http://stackoverflow.com/a/3923228
		$date1 = new DateTime($start_date);
		$date2 = new DateTime($end_date);

		if ( $date1 === $date2 ) {
		$tour_days_elapsed = 1;
		}
		else {
		$interval = $date1->diff($date2);
		$tour_days_elapsed = $interval->format("%r%a"); // ->d only gets days in the same month
		}

		return $tour_days_elapsed + 1;
	}

	/**
	 * Get the name of a tour leg
	 *
	 * @param string $tour_leg_slug The slug of the tour leg
	 * @return string $tour_leg_name The name of the tour leg
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
	 * @see https://codex.wordpress.org/Function_Reference/get_term_by
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_leg_name($tour_leg_slug) {

		$tour_leg_name = '';
		$taxonomy = $this->get_the_taxonomy();
		$tour_leg = get_term_by('slug', $tour_leg_slug, $taxonomy);
		$tour_leg_name = $tour_leg->name;

		return $tour_leg_name;
	}

	/**
	 * Get the ID of a tour leg
	 *
	 * @param string $tour_leg_slug The slug of the tour leg
	 * @return string $tour_leg_id The ID of the tour leg
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see https://wordpress.stackexchange.com/questions/16394/how-to-get-a-taxonomy-term-name-by-the-slug
	 * @see https://codex.wordpress.org/Function_Reference/get_term_by
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_term_leg_id($tour_leg_slug) {

		$taxonomy = $this->get_the_taxonomy();
		$tour_leg = get_term_by('slug', $tour_leg_slug, $taxonomy);
		$tour_leg_id = $tour_leg->term_id;

		return $tour_leg_id;
	}

	/**
	 * Get the total number of days in a tour
	 *
	 * @param int $post_id Optional Post ID for testing
	 * @param string $term_type Term type (tour|tour_leg)
	 * @return string
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_post
	 */
	public function get_daytotal( $post_id=null, $term_type ) {

		if ( isset( $post_id ) ) {
			$post = get_post( $post_id );
		}
		else {
			global $post;
			$post_id = $post->ID;			
		}

		$tour_start_date = $this->get_term_start_date( $post_id, $term_type );
		$tour_end_date = $this->get_term_end_date( $post_id, $term_type );
		$day_total = $this->get_term_days_elapsed( $tour_start_date, $tour_end_date );

		return $day_total;
	}

	/**
	 * Get the coordinates of a map location
	 *
	 * @param string $key
	 *    The key of the JSON object.
	 * @return      string "lat,lng" | ""
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\todo_location
	 */
	public function get_html_latlng( $key ) {

		// if options have not been stored, exit
		$wpdtrt_tourdates_options = get_option('wpdtrt_tourdates');

		if ( $wpdtrt_tourdates_options === '' ) {
			return '';
		}

		// the data set
		$wpdtrt_tourdates_data = $wpdtrt_tourdates_options['wpdtrt_tourdates_data'];

		// user - map block
		if ( isset( $wpdtrt_tourdates_data[$key]->{'address'} ) ) :

			$lat = $wpdtrt_tourdates_data[$key]->{'address'}->{'geo'}->{'lat'};
			$lng = $wpdtrt_tourdates_data[$key]->{'address'}->{'geo'}->{'lng'};

			$str = $lat . ',' . $lng;

		else:

			$str = '';

		endif;

		return $str;
	}

	/**
	 * Get tour length in days
	 *
	 * @param number $term_id The term ID
	 * @param string $text_before Translatable text displayed before the tour length
	 * @param string $text_after Translatable text displayed after the tour length
	 * @return string $tour_length_days The length of the tour
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 *
	 * @see TourdatesTest\test_tour_term
	 * @see TourdatesTest\test_tour_leg_term
	 */
	public function get_tourlengthdays( $term_id, $text_before='', $text_after='' ) {
		// convert shortcode argument to a number
		if ( isset( $term_id ) ) {
			$term_id = (int)$term_id;
		}

		$term_type = $this->get_meta_term_type( $term_id );

		$tour_start_date = $this->get_term_start_date( $term_id, $term_type );
		$tour_end_date = $this->get_term_end_date( $term_id, $term_type );
		$tour_length_days = $this->get_term_days_elapsed($tour_start_date, $tour_end_date);

		return $text_before . $tour_length_days . $text_after;
	}

	/**
	 * Create a custom field when a post is saved,
	 * which can be queried by the next/previous_post_link_plus plugin
	 * and used in the Yoast page title via %%cf_wpdtrt_tourdates_daynumber%%,
	 * and used in the permalink slug 'tourdiaries/%tours%/%wpdtrt_tourdates_cf_daynumber%' (wpdtrt-dbth)
	 *
	 * Use the Query Monitor plugin to view the Post type
	 *
	 * @param int $post_id The post ID.
	 * @param post $post The post object.
	 * @param bool $update Whether this is an existing post being updated or not.
	 *
	 * @link wpdtrt/library/permalink-placeholders.php
	 * @link wpdtrt-dbth/library/register_post_type_tourdiaries
	 * @see https://wordpress.org/support/topic/set-value-in-custom-field-using-post-by-email/
	 * @see https://wordpress.stackexchange.com/questions/61148/change-slug-with-custom-field
	 * @todo meta_key workaround requires each post to be resaved/updated, this is not ideal
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/save_post#Custom_Post_Type:_.27book.27
	 * @see TourdatesTest\test_post
	 */
	public function save_post_daynumber( $post_id, $post, $update ) {

		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		if ( $post_type !== 'tourdiaries' ) {
			return;
		}

		$daynumber = $this->get_post_daynumber( $post_id );

		if ( $daynumber ) {
			// update_post_meta also runs add_post_meta, if the $meta_key does not already exist
			update_post_meta( $post_id, 'wpdtrt_tourdates_cf_daynumber', $daynumber );
		}

		// note: https://developer.wordpress.org/reference/functions/get_post_meta/#comment-1894
		//$test = get_post_meta($post_id, 'wpdtrt_tourdates_cf_daynumber', true); // true = return single value
	}

    //// END SETTERS AND GETTERS \\\\

    //// START RENDERERS \\\\

	/**
	 * Render a previous/next navigation bar
	 *
	 * @version 1.0.0
	 * @since 0.1.0
	 */
	public function render_navigation() {

		// post object to get info about the post in which the shortcode appears
		global $post;
		$post_id = $post->ID;

		$posttype = $this->get_posttype(); // shortcode option
		$taxonomy = $this->get_taxonomy(); // shortcode option

		// vars to pass to template partial
		$previous =   $this->get_navigation_link('previous', $posttype, $taxonomy);
		$next =       $this->get_navigation_link('next', $posttype, $taxonomy);
		$daynumber =  $this->get_post_daynumber($post_id);

		/**
		* ob_start — Turn on output buffering
		* This stores the HTML template in the buffer
		* so that it can be output into the content
		* rather than at the top of the page.
		*/
		ob_start();

		require(WPDTRT_TOURDATES_PATH . 'template-parts/content-navigation.php');

		/**
		* ob_get_clean — Get current buffer contents and delete current output buffer
		*/
		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Link to next/previous post
	 * @requires http://www.ambrosite.com/plugins/next-previous-post-link-plus-for-wordpress
	 * @param $direction string previous|next
	 * @param $posttype
	 * @param $taxonomy
	 * @todo Update to limit to the daycontroller category
	 */
	public function render_navigation_link($direction, $posttype, $taxonomy) {

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
			'post_type' => '"' . $posttype . '"',
			'meta_key' => 'wpdtrt_tourdates_cf_daynumber',
			'loop' => false,
			'max_length' => 9999,
			'format' => '%link',
			'link' => '<span class="stack--navigation--text says">' . $tooltip_prefix . ': Day DAY_NUMBER</span> <span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span>',
			'tooltip' => $tooltip_prefix . ': Day DAY_NUMBER.',
			'in_same_tax' => $taxonomy,
			'echo' => false
		);

		$current_daynumber = $this->get_post_daynumber($post->ID);

		if ( $direction == 'previous' ) {
			$the_id = previous_post_link_plus( array('return' => 'id') );
			$adjacent_daynumber = $this->get_post_daynumber($the_id);

			// Prevent navigation between different tours
			if ( ( $adjacent_daynumber > 0 ) && ( $adjacent_daynumber < $current_daynumber ) ) {
				$the_link = previous_post_link_plus( $config );
				$the_link = str_replace('DAY_NUMBER', $adjacent_daynumber, $the_link);
			}
		}
		else if ( $direction == 'next' ) {
			$the_id = next_post_link_plus( array('return' => 'id') );
			$adjacent_daynumber = $this->get_post_daynumber($the_id);

			// Prevent navigation between different tours
			if ( ( $adjacent_daynumber > 0 ) && ( $adjacent_daynumber > $current_daynumber ) ) {
			$the_link = next_post_link_plus( $config );
			$the_link = str_replace('DAY_NUMBER', $adjacent_daynumber, $the_link);
			}
		}

		if ( !$the_link ) {
			$the_link = '<span class="a"><span class="icon-arrow-' . $icon . ' stack--navigation--icon"></span></span>';
		}

		return $the_link;
	}

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
	public function render_permalink_placeholders($permalink, $post, $leavename) {

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

    //// END RENDERERS \\\\

    //// START FILTERS \\\\

	/**
	 * Add the ACF day to the post title
	 * @see https://wordpress.org/support/topic/the_title-filter-only-for-page-title-display
	 * @todo: this is outputting into the Primary Navigation menu, need to check !if_menu
	 * @see TourdatesTest\test_post
	 */

	public function filter_post_title_add_day( $title, $id = NULL ) {

		// http://php.net/manual/en/functions.arguments.php
		//if ( is_null($id) ) {
		//  $day = get_field('acf_daynumber');
		// }
		//else {
		// $day = get_post_field('acf_daynumber', $id);
		//}

		global $post;
		$id = $post->ID;

		$day = $this->get_post_daynumber($id);

		$day_html = '<span class="wpdtrt-tourdates-day theme-text_secondary"><span class="wpdtrt-tourdates-day--day">Day </span><span class="wpdtrt-tourdates-day--number">' . $day . '</span><span class="wpdtrt-tourdates-day--period">, </span></span>';
		$title_html = '<span class="wpdtrt-tourdates-day--title">' . $title . '</span>';
		$simple_title_html = '<span class="wpdtrt-tourdates-day--title">' . $title . '</span>';

		// if in the loop / rendering the post
		// || is_active_widget(false, 'widget_recent_entries')
		if ( $day && in_the_loop() && is_single() && ! is_admin() && ( !is_active_widget() || is_active_widget(false,'widget_recent_entries') ) ) {
			return $day_html . $title_html;
		}
		// if is category listings or similar
		else if ( $day && in_the_loop() && ( is_archive() || is_search() || is_home() ) && ( !is_active_widget() || is_active_widget(false,'widget_recent_entries') ) ) {
			if ( is_admin() ) { // excludes media library
				return $title;
			}
			else {
				return $day_html . $title_html;
			}
		}
		// else if the dashboard etc
		else {
			if ( is_admin() ) {
				return $title;
			}
			else {
				return $simple_title_html;
			}
		}
	}

	/**
	 * 
	 */
	  public function filter_attachment_title_remove_day( $attachment_title = '', $fallback = '' ) {

	    $regex = '/<span class="wpdtrt-tourdates-day theme-text_secondary">.*<\/span><span class="wpdtrt-tourdates-day--title">/';
	    $output = preg_replace($regex, '<span class="wpdtrt-tourdates-day--title">', $attachment_title);
	    $html_len = strlen('<span class="wpdtrt-tourdates-day--title"></span>');
	    $output = trim($output);

	    if ( ( strlen($output) - $html_len ) === 0 ) {
	      $output = $fallback;
	    }

	    return $output;
	  }

	/**
	 * 
	 */
	public function filter_attachment_title_add_day( $attachment_title, $parent_title, $parent_id ) {

		// http://php.net/manual/en/functions.arguments.php
		//if ( is_null($id) ) {
		//  $day = get_field('acf_daynumber');
		// }
		//else {
		// $day = get_post_field('acf_daynumber', $id);
		//}

		global $post;

		$parent_day = $this->get_post_daynumber($parent_id);
		$attachment_title = wpdtrt_tourdates_attachment_title_remove_day( $attachment_title );
		$title_text = 'Gallery image';

		if ( $attachment_title ) {
			$title_text .= ': ' . $attachment_title;
		}

		$day_html = '<span class="wpdtrt-tourdates-day theme-text_secondary"><span class="wpdtrt-tourdates-day--day">Day </span><span class="wpdtrt-tourdates-day--number">' . $parent_day . ': ' . $parent_title . '</span><span class="wpdtrt-tourdates-day--period">. </span></span>';
		$title_html = '<span class="wpdtrt-tourdates-day--title">' . $title_text . '</span>';

		return $day_html . $title_html;
	}

    //// END FILTERS \\\\

    //// START HELPERS \\\\

	/**
	 * Sort terms into hierarchical order
	 * 	Sort terms into hierarchical order,
	 * 	so that we can get the tour rather than the tour_leg.
	 *
     * Has parent: $term->parent === n
     * No parent: $term->parent === 0
     *
	 * @param {array} $tour_terms Array of terms (e.g. tour legs)
	 * @return {array} $tour_terms Sorted terms
	 *
     * @see https://developer.wordpress.org/reference/functions/get_the_terms/
     * @see https://wordpress.stackexchange.com/questions/172118/get-the-term-list-by-hierarchy-order
     * @see https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
     * @see https://wpseek.com/function/_get_term_hierarchy/
     * @see https://wordpress.stackexchange.com/questions/137926/sorting-attributes-order-when-using-get-the-terms
     * @uses WPDTRT helpers/permalinks.php
	 *
	 * @todo TourdatesTest\test_tour_term
	 */
	function helper_order_tour_terms_by_hierarchy( $tour_terms ) {

		// usort: Sort an array with a user-defined comparison function
		// uasort: and maintain index association (not reqd & fails comparison unit test as keys are shuffled)
		// @usort: suppress PHP Warning: usort(): Array was modified by the user comparison function
		@usort( $tour_term, function( $term_a, $term_b ) {

			$term_a_parent = $term_a->parent;
			$term_b_parent = $term_b->parent;

			// compare strings using a 'natural order' algorithm
			return strnatcmp( $term_a_parent, $term_b_parent );
		});

		return $tour_term_ids;
	}

	/**
	 * Sort term IDs by start date
	 * @param {array} $tour_term_ids Array of term IDs (e.g. tour legs)
	 * @return {array} $tour_term_ids Sorted terms
	 * @see https://stackoverflow.com/a/22231045/6850747
	 * @see TourdatesTest\test_tour_term
	 */
	function helper_order_tour_terms_by_date( $tour_term_ids ) {

		// usort: Sort an array with a user-defined comparison function
		// uasort: and maintain index association (not reqd & fails comparison unit test as keys are shuffled)
		// @usort: suppress PHP Warning: usort(): Array was modified by the user comparison function
		@usort( $tour_term_ids, function( $term_a_id, $term_b_id ) {

			$term_type_a = $this->get_meta_term_type( $term_a_id );
			$term_type_b = $this->get_meta_term_type( $term_b_id );

			$term_a_start_date = $this->get_term_start_date( $term_a_id, $term_type_a );
			$term_b_start_date = $this->get_term_start_date( $term_b_id, $term_type_b );

			// compare strings using a 'natural order' algorithm
			return strnatcmp( $term_a_start_date, $term_b_start_date );
		});

		return $tour_term_ids;
	}

	/**
	 * Add custom rewrite rules
	 * WordPress allows theme and plugin developers to programmatically specify new, custom rewrite rules.
	 *
	 * @see http://clivern.com/how-to-add-custom-rewrite-rules-in-wordpress/
	 * @see https://www.pmg.com/blog/a-mostly-complete-guide-to-the-wordpress-rewrite-api/
	 * @see https://www.addedbytes.com/articles/for-beginners/url-rewriting-for-beginners/
	 * @see http://codex.wordpress.org/Rewrite_API
	 *
	 * @since       1.0.0
	 * @version     1.0.0
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
	     */
	    $wp_rewrite->add_rewrite_tag(
	        '%wpdtrt_tourdates_cf_daynumber%',
	        '([^/]+)', // get one or more of any character except slash
	        'wpdtrt_tourdates_cf_daynumber='
	    );
	}

    //// END HELPERS \\\\
}

?>