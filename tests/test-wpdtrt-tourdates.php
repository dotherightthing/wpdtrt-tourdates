<?php
/**
 * Unit tests, using PHPUnit and wp-cli.
 *
 * @package wpdtrt_tourdates
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 * @see http://richardsweeney.com/testing-integrations/
 * @see https://gist.github.com/benlk/d1ac0240ec7c44abd393 - Collection of notes on WP_UnitTestCase
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes//factory/class-wp-unittest-factory-for-term.php
 */

/**
 * TourdatesTest unit tests, using PHPUnit, wp-cli, WP_UnitTestCase
 * Note that the plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-tourdates.php
 * Note: only function names prepended with test_ are run
 * $debug logs are output with the test output in Terminal
 */
class TourdatesTest extends WP_UnitTestCase {

    /**
     * SetUp
     * Automatically called by PHPUnit before each test method is run
     *
     * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
     */
    public function setUp() {
  		// Make the factory objects available.
        parent::setUp();

        $this->taxonomy = $this->create_taxonomy();

		// store results as properties, for sharing between test methods
		$this->taxonomy_name = 'wpdtrt_tourdates_taxonomy_tour'; // $taxonomy->get_name()

	 	$this->region_term_id = $this->mock_region_term( array(
	 		'name' => 'Asia',
	 		'slug' => 'asia',
			'description' => '',
	 		'term_type' => 'region'
	 	) );

		$this->tour_term_id = $this->mock_tour_term( array(
			'name' => 'East Asia (2015-2016)',
			'slug' => 'east-asia',
			'description' => 'Russia - Mongolia - China - Hong Kong -Japan - New Zealand (298 days)',
			'term_type' => 'tour',
			'start_date' => '2015-9-2',
			'end_date' => '2016-6-25',
			'first_visit' => '',
			'leg_count' => '6',
			'thumbnail_id' => ''
		) );

		// create legs in alphabetical order
		// so can test sorting into date order

		$this->tour_leg_term_id_1 = $this->mock_tour_leg_term( array(
			'name' => 'China (Part 1)',
			'slug' => 'china-1',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2015-9-2',
			'end_date' => '2015-9-10',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => 926
		) );

		$this->tour_leg_term_id_4 = $this->mock_tour_leg_term( array(
			'name' => 'China (Part 2)',
			'slug' => 'china-2',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2015-11-29',
			'end_date' => '2016-1-17',
			'first_visit' => 0,
			'leg_count' => '',
			'thumbnail_id' => 926
		) );

		$this->tour_leg_term_id_5 = $this->mock_tour_leg_term( array(
			'name' => 'Hong Kong',
			'slug' => 'hong-kong',
			'description' => 'Camping my way around Hong Kong.',
			'term_type' => 'tour_leg',
			'start_date' => '2016-1-18',
			'end_date' => '2016-3-14',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => ''
		) );

		$this->tour_leg_term_id_6 = $this->mock_tour_leg_term( array(
			'name' => 'Japan',
			'slug' => 'japan',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2016-3-15',
			'end_date' => '2016-6-12',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => ''
		) );

		$this->tour_leg_term_id_3 = $this->mock_tour_leg_term( array(
			'name' => 'Mongolia',
			'slug' => 'mongolia',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2015-10-6',
			'end_date' => '2015-11-28',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => ''
		) );

		$this->tour_leg_term_id_7 = $this->mock_tour_leg_term( array(
			'name' => 'New Zealand',
			'slug' => 'new-zealand-asia',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2016-6-13',
			'end_date' => '2016-6-25',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => ''
		) );

		$this->tour_leg_term_id_2 = $this->mock_tour_leg_term( array(
			'name' => 'Russia',
			'slug' => 'Russia',
			'description' => '',
			'term_type' => 'tour_leg',
			'start_date' => '2015-9-11',
			'end_date' => '2015-10-5',
			'first_visit' => 1,
			'leg_count' => '',
			'thumbnail_id' => ''
		) );
    }

    /**
     * TearDown
     * Automatically called by PHPUnit after each test method is run
     *
     * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories     
     */
    public function tearDown() {

    	// prevents error presumably due to existing terms being added again
    	wp_delete_term( $this->region_term_id, $this->taxonomy_name );
    	wp_delete_term( $this->tour_term_id, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_1, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_2, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_3, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_4, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_5, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_6, $this->taxonomy_name );
    	wp_delete_term( $this->tour_leg_term_id_7, $this->taxonomy_name );
    }

    public function create_taxonomy() {
		$taxonomy = wpdtrt_tourdates_taxonomy_tour_init();

		return $taxonomy;
    }

    // ########## MOCK DATA ########## //

 	/**
	 * Mock a custom post type
	 *
	 * @todo ...
	 */

 	/**
	 * Mock a region
	 *
	 * @return number $term_id
	 */
	public function mock_region_term( $options ) {

		$name = null;
		$slug = null;
		$description = null;
		$term_type = null;
		$start_date = null;
		$end_date = null;
		$first_visit = null;
		$leg_count = null;
		$thumbnail_id = null;

		extract( $options, EXTR_IF_EXISTS );

		$term_id = $this->factory->term->create([
			'name' => $name,
			'taxonomy' => $this->taxonomy_name,
			'slug' => $slug,
			'description' => $description
		]);

		update_term_meta($term_id, 'term_type', $term_type);

		return $term_id;
	}

	/**
	 * Mock a tour
	 *
	 * @return number $term_id
	 */
	public function mock_tour_term( $options ) {

		$name = null;
		$slug = null;
		$description = null;
		$term_type = null;
		$start_date = null;
		$end_date = null;
		$first_visit = null;
		$leg_count = null;
		$thumbnail_id = null;

		extract( $options, EXTR_IF_EXISTS );

		$term_id = $this->factory->term->create([
			'taxonomy' => $this->taxonomy_name,
			'name' => $name,
			'slug' => $slug,
			'parent' => $this->region_term_id,
			'description' => $description
		]);

		update_term_meta($term_id, 'term_type', $term_type);
		update_term_meta($term_id, 'start_date', $start_date);
		update_term_meta($term_id, 'end_date', $end_date);
		update_term_meta($term_id, 'first_visit', $first_visit);
		update_term_meta($term_id, 'leg_count', $leg_count);
		update_term_meta($term_id, 'thumbnail_id', $thumbnail_id);

		return $term_id;
	}

	/**
	 * Mock a tour_leg
	 *
	 * @return number $term_id
	 */
	public function mock_tour_leg_term( $options ) {

		$name = null;
		$slug = null;
		$description = null;
		$term_type = null;
		$start_date = null;
		$end_date = null;
		$first_visit = null;
		$leg_count = null;
		$thumbnail_id = null;

		extract( $options, EXTR_IF_EXISTS );

		$term_id = $this->factory->term->create([
			'taxonomy' => $this->taxonomy_name,
			'name' => $name,
			'slug' => $slug,
			'parent' => $this->tour_term_id,
			'description' => $description
		]);

		update_term_meta($term_id, 'term_type', $term_type);
		update_term_meta($term_id, 'start_date', $start_date);
		update_term_meta($term_id, 'end_date', $end_date);
		update_term_meta($term_id, 'first_visit', $first_visit);
		update_term_meta($term_id, 'leg_count', $leg_count);
		update_term_meta($term_id, 'thumbnail_id', $thumbnail_id); // todo

		return $term_id;
	}

    // ########## TEST ########## //

	/**
	 * Test set_foo()
	 * Checks that we are dealing with the expected config
	 */
	public function test_config() {
		$taxonomy = 		$this->taxonomy;
		$name = 			$taxonomy->get_name();	
		$instance_options = $taxonomy->get_instance_options();
		$labels = 			$taxonomy->get_labels();	
		$plugin = 			$taxonomy->get_plugin();	

		$this->assertTrue( is_string($name) );
		$this->assertEquals( $name, 'wpdtrt_tourdates_taxonomy_tour' );
		$this->assertTrue( taxonomy_exists( $name ) );

		$this->assertTrue( is_array($instance_options) );
		$this->assertEquals( $instance_options, array() );

		$this->assertTrue( is_array($labels) );
		$this->assertEquals( $labels, array(
          'slug' => 'tours',
          'singular' => 'Tour',
          'plural' => 'Tours',
          'description' => 'Multiday rides',
          'posttype' => 'tourdiaries'
        ) );

		$this->assertTrue( is_object($plugin) );
	}

	/**
	 * Test tourdiaries post type
	 */
	public function todo_test_post() {

		$term_id = $this->region_term_id;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// plugin calculations

		// $post_id = '';
		$plugin->set_daynumber();
		$plugin_daynumber = $plugin->get_post_daynumber( $post_id );
		$plugin_daytotal = $plugin->get_daytotal();
		$post_title_incl_day = $plugin->filter_post_title_add_day('My title');

		$this->assertEquals( $plugin_daynumber, 12345 );
		$this->assertEquals( $plugin_daytotal, 12345 );
		$this->assertEquals( $post_title_incl_day, '12345' );
	}

	/**
	 * Test location
	 */
	public function todo_location() {

		$term_id = $this->region_term_id;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// plugin calculations
		$key = '';
		$plugin_html_latlng = $plugin->get_html_latlng( $key );

		$this->assertEquals( $plugin_html_latlng, 12345 );
	}

	/**
	 * Test region term
	 */
	public function test_region_term() {

		$term_id = $this->region_term_id;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// term meta, queried directly

		$meta_term_type = get_term_meta( $term_id, 'term_type', true );

		$this->assertEquals( $meta_term_type, 'region' );

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );

		$this->assertEquals( $plugin_meta_term_type, 'region' );
	}

	/**
	 * Test tour term
	 */
	public function test_tour_term() {

		$term_id = $this->tour_term_id;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// term meta, queried directly

		$meta_term_type = get_term_meta( $term_id, 'term_type', true );
		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

		$this->assertEquals( $meta_term_type, 'tour' );
		$this->assertEquals( $meta_start_date, '2015-9-2' );
		$this->assertEquals( $meta_end_date, '2016-6-25' );
		$this->assertEquals( $meta_first_visit, '' );
		$this->assertEquals( $meta_leg_count, 6 );
		$this->assertEquals( $meta_thumbnail_id, '' );

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );

		$this->assertEquals( $plugin_meta_term_type, 'tour' );
		$this->assertEquals( $plugin_meta_start_date, '2015-9-2 00:01:00' );
		$this->assertEquals( $plugin_meta_end_date, '2016-6-25 00:01:00' );
		$this->assertEquals( $plugin_meta_leg_count, 6 );
		$this->assertEquals( $plugin_meta_thumbnail_id, '' );

		// plugin calculations

		$plugin_start_date = $plugin->get_term_start_date( $term_id ); // todo test other parameters
		$plugin_end_date = $plugin->get_term_end_date( $term_id ); // todo test other parameters
    	$plugin_tour_length_days = $plugin->get_term_days_elapsed( $plugin_start_date, $plugin_end_date );
    	$plugin_tour_length = $plugin->get_tourlengthdays( $term_id );
    	$plugin_start_day = $plugin->get_term_start_day( $term_id );
    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id );
    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'east-asia' );
    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'east-asia' );
    	$plugin_tour_leg_ids = get_term_children( $term_id, $this->taxonomy_name );
  		$plugin_tour_leg_ids_ordered = $plugin->helper_order_tour_terms_by_date( $plugin_tour_leg_ids );

		$this->assertEquals( $plugin_start_date, '2015-9-2 00:01:00' );
		$this->assertEquals( $plugin_end_date, '2016-6-25 00:01:00' );
		$this->assertEquals( $plugin_tour_length_days, 298 );
		$this->assertEquals( $plugin_tour_length, 298 );
		$this->assertEquals( $plugin_start_day, 1 );
		$this->assertEquals( $plugin_start_month, 'September 2015' );
		$this->assertEquals( $plugin_end_month, 'June 2016' );
		$this->assertEquals( $plugin_tour_leg_count, 6 ); // todo test with NZ legs
		$this->assertEquals( $plugin_tour_leg_name, 'East Asia (2015-2016)' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );
		$this->assertEquals( $plugin_tour_leg_ids, [
			$this->tour_leg_term_id_1,
			$this->tour_leg_term_id_4,
			$this->tour_leg_term_id_5,
			$this->tour_leg_term_id_6,
			$this->tour_leg_term_id_3,
			$this->tour_leg_term_id_7,
			$this->tour_leg_term_id_2
		]);
		$this->assertEquals( $plugin_tour_leg_ids_ordered, [
			$this->tour_leg_term_id_1,
			$this->tour_leg_term_id_2,
			$this->tour_leg_term_id_3,
			$this->tour_leg_term_id_4,
			$this->tour_leg_term_id_5,
			$this->tour_leg_term_id_6,
			$this->tour_leg_term_id_7
		]);
	}

	/**
	 * Test tour_leg term #1
	 */
	public function test_tour_leg_term_1() {

		$term_id = $this->tour_leg_term_id_1;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// term meta, queried directly

		$meta_term_type = get_term_meta( $term_id, 'term_type', true );
		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

		$this->assertEquals( $meta_term_type, 'tour_leg' );
		$this->assertEquals( $meta_start_date, '2015-9-2' );
		$this->assertEquals( $meta_end_date, '2015-9-10' );
		$this->assertEquals( $meta_first_visit, true );
		$this->assertEquals( $meta_leg_count, '' );
		$this->assertEquals( $meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );

		$this->assertEquals( $plugin_meta_term_type, 'tour_leg' );
		$this->assertEquals( $plugin_meta_start_date, '2015-9-2 00:01:00' );
		$this->assertEquals( $plugin_meta_end_date, '2015-9-10 00:01:00' );
		$this->assertEquals( $plugin_meta_leg_count, '' );
		$this->assertEquals( $plugin_meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// plugin calculations

		$plugin_start_date = $plugin->get_term_start_date( $term_id ); // todo test other parameters
		$plugin_end_date = $plugin->get_term_end_date( $term_id ); // todo test other parameters
    	$plugin_tour_length_days = $plugin->get_term_days_elapsed( $plugin_start_date, $plugin_end_date );
    	$plugin_tour_length = $plugin->get_tourlengthdays( $term_id );
    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id ); // todo test with NZ legs
    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'china-1' );
    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'china-1' );
    	$plugin_start_day = $plugin->get_term_start_day( $term_id );

		$this->assertEquals( $plugin_start_date, '2015-9-2 00:01:00' );
		$this->assertEquals( $plugin_end_date, '2015-9-10 00:01:00' );
		$this->assertEquals( $plugin_tour_length_days, 9 );
		$this->assertEquals( $plugin_tour_length, 9 );
		$this->assertEquals( $plugin_start_month, 'September 2015' );
		$this->assertEquals( $plugin_end_month, 'September 2015' );
		$this->assertEquals( $plugin_tour_leg_count, '' );
		$this->assertEquals( $plugin_tour_leg_name, 'China (Part 1)' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );
		$this->assertEquals( $plugin_start_day, 1 );

		// plugin calculations - buggy
	}

	/**
	 * Test tour_leg term #4
	 */
	public function test_tour_leg_term_4() {

		$term_id = $this->tour_leg_term_id_4;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// term meta, queried directly

		$meta_term_type = get_term_meta( $term_id, 'term_type', true );
		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

		$this->assertEquals( $meta_term_type, 'tour_leg' );
		$this->assertEquals( $meta_start_date, '2015-11-29' );
		$this->assertEquals( $meta_end_date, '2016-1-17' );
		$this->assertEquals( $meta_first_visit, 0 );
		$this->assertEquals( $meta_leg_count, '' );
		$this->assertEquals( $meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );

		$this->assertEquals( $plugin_meta_term_type, 'tour_leg' );
		$this->assertEquals( $plugin_meta_start_date, '2015-11-29 00:01:00' );
		$this->assertEquals( $plugin_meta_end_date, '2016-1-17 00:01:00' );
		$this->assertEquals( $plugin_meta_leg_count, '' );
		$this->assertEquals( $plugin_meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// plugin calculations

		$plugin_start_date = $plugin->get_term_start_date( $term_id ); // todo test other parameters
		$plugin_end_date = $plugin->get_term_end_date( $term_id ); // todo test other parameters
    	$plugin_tour_length_days = $plugin->get_term_days_elapsed( $plugin_start_date, $plugin_end_date );
    	$plugin_tour_length = $plugin->get_tourlengthdays( $term_id );
    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id ); // todo test with NZ legs
    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'china-2' );
    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'china-2' );
    	$plugin_start_day = $plugin->get_term_start_day( $term_id );

		$this->assertEquals( $plugin_start_date, '2015-11-29 00:01:00' );
		$this->assertEquals( $plugin_end_date, '2016-1-17 00:01:00' );
		$this->assertEquals( $plugin_tour_length_days, 50 );
		$this->assertEquals( $plugin_tour_length, 50 );
		$this->assertEquals( $plugin_start_month, 'November 2015' );
		$this->assertEquals( $plugin_end_month, 'January 2016' );
		$this->assertEquals( $plugin_tour_leg_count, '' );
		$this->assertEquals( $plugin_tour_leg_name, 'China (Part 2)' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );
		$this->assertEquals( $plugin_start_day, 89 );

		// plugin calculations - buggy
	}
}