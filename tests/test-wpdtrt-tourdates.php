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
        $this->create_customposttype();

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

	    $this->post_id = $this->create_post( array(
	    	'post_title' => 'The Hitchhiker',
	    	'post_date' => '2015-09-22 23:00:00',
	    	'term_ids' => array(
	    		$this->region_term_id,
	    		$this->tour_term_id,
	    		$this->tour_leg_term_id_2
	    	)
	    ) );
    }

    /**
     * TearDown
     * Automatically called by PHPUnit after each test method is run
     *
     * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories     
     */
    public function tearDown() {

    	parent::tearDown();

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

    	wp_delete_post( $this->post_id, true );
    }

    /**
	 * Create the 'tour' taxonomy
	 */
    public function create_taxonomy() {
		$taxonomy = wpdtrt_tourdates_taxonomy_tour_init();

		return $taxonomy;
    }

    /**
     * Create the 'tourdiaries' posttype used by wpdtrt-dbth
	 * add_action('init', 'wpdtrt_register_post_type_tourdiaries');
     */
    public function create_customposttype() {

		if ( !post_type_exists( 'tourdiaries' ) ) {

			$labels = array(
				'name' => _x( 'Tour Diaries', 'post type general name', 'wpdtrt-tourdates' ),
			);

			$args = array(
				'labels' => $labels,
				'description' => 'Tour Diary entries',
				'public' => true,
				'exclude_from_search' => false,
				'capability_type' => 'post',
				'supports' => array(
					'title',
					'editor',
					'excerpt',
					'custom-fields',
					'thumbnail',
					'comments',
					'revisions'
				),
				'has_archive' => 'tourdiaries',
				'rewrite' => array(
					'slug' => 'tourdiaries/%wpdtrt_tourdates_taxonomy_tour%/%wpdtrt_tourdates_cf_daynumber%',
					'with_front' => false,
					'pages' => false
				)
			);

			register_post_type(
				'tourdiaries',
				$args
			);
		}
    }

    /**
     * Create post
     *
     * @param string $post_title Post title
     * @param string $post_date Post date
     * @param array $term_ids Taxonomy term IDs
     * @return number $post_id
     *
     * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
     * @see https://wordpress.stackexchange.com/questions/37163/proper-formatting-of-post-date-for-wp-insert-post
     * @see https://codex.wordpress.org/Function_Reference/wp_update_post
     */
    public function create_post( $options ) {

    	$post_title = null;
    	$post_date = null;
    	$term_ids = null;

    	extract( $options, EXTR_IF_EXISTS );

 		$post_id = $this->factory->post->create([
           'post_title' => $post_title,
           'post_date' => $post_date,
           'post_type' => 'tourdiaries',
           'post_status' => 'publish'
        ]);

        wp_set_object_terms( $post_id, $term_ids, $this->taxonomy_name );

        // republish post - see https://github.com/dotherightthing/wpdtrt-tourdates/issues/1
        //wp_update_post( array(
        // 	'ID' => $post_id
        //) );

        return $post_id;
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
		
		$plugin = 			$taxonomy->get_plugin();	
		$this->assertTrue( is_object($plugin) );

		$name = 			$taxonomy->get_name();	
		$this->assertTrue( is_string($name) );
		$this->assertEquals( $name, 'wpdtrt_tourdates_taxonomy_tour' );
		$this->assertTrue( taxonomy_exists( $name ) );

		$instance_options = $taxonomy->get_instance_options();
		$this->assertTrue( is_array($instance_options) );
		$this->assertEquals( $instance_options, array() );

		$labels = 			$taxonomy->get_labels();	
		$this->assertTrue( is_array($labels) );
		$this->assertEquals( $labels, array(
          'slug' => 'tours',
          'singular' => 'Tour',
          'plural' => 'Tours',
          'description' => 'Multiday rides',
          'posttype' => 'tourdiaries'
        ) );
	}

	/**
	 * Test shortcodes
	 * 	trim() removes line break added by WordPress
	 */
	public function test_shortcodes() {

		// todo https://github.com/dotherightthing/wpdtrt-plugin/issues/43
		$term_id = $this->tour_leg_term_id_1;
		$tour_leg_term_id_1_tourlengthdays = do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $term_id .'" text_before="" text_after=" days"]' );
		$this->assertEquals( trim( $tour_leg_term_id_1_tourlengthdays ), '9 days' );
	}

	/**
	 * Test tourdiaries post type
	 *
	 * @see https://stackoverflow.com/questions/35442512/how-to-use-wp-unittestcase-go-to-to-simulate-current-page
	 */
	public function test_post() {

		$post_id = $this->post_id;
	    $post_permalink = get_post_permalink( $post_id );
		$term_id = $this->region_term_id;
		$taxonomy = $this->taxonomy;
		$plugin = $taxonomy->get_plugin();

		// plugin calculations

		$plugin_daynumber = $plugin->get_post_daynumber( $post_id );
		$this->assertEquals( $plugin_daynumber, 21 );

		// https://github.com/dotherightthing/wpdtrt-tourdates/issues/1
		$plugin_daytotal = $plugin->get_daytotal( $post_id, 'tour' );
		$this->assertEquals( $plugin_daytotal, 298 );

		$term_id = $plugin->get_term_id( $post_id, 'tour_leg' );
		$this->assertEquals( $term_id, $this->tour_leg_term_id_2 );

		$post_term_id = $plugin->get_post_term_id( $post_id, 'tour_leg' );
		$this->assertEquals( $post_term_id, $this->tour_leg_term_id_2 );

		$formatted_title = $plugin->filter_post_title_add_day('Post title', $post_id);
		$this->assertEquals( $formatted_title, '<span class="wpdtrt-tourdates-day--title">Post title</span>' );

		// todo
		//$permalink_placeholders = $plugin->render_permalink_placeholders();
	}

	/**
	 * Test taxonomy
	 */
	public function test_taxonomy() {
		$term_id = $this->region_term_id;
		$taxonomy = $this->taxonomy;
		$taxonomy_name = $this->taxonomy_name;
		$plugin = $taxonomy->get_plugin();

		$tax = $plugin->get_the_taxonomy();
		$this->assertEquals( $tax, $taxonomy_name );
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
		$this->assertEquals( $meta_term_type, 'tour' );

		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$this->assertEquals( $meta_start_date, '2015-9-2' );

		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$this->assertEquals( $meta_end_date, '2016-6-25' );

		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$this->assertEquals( $meta_first_visit, '' );

		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$this->assertEquals( $meta_leg_count, 6 );

		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
		$this->assertEquals( $meta_thumbnail_id, '' );

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$this->assertEquals( $plugin_meta_term_type, 'tour' );

		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$this->assertEquals( $plugin_meta_start_date, '2015-9-2 00:01:00' );

		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$this->assertEquals( $plugin_meta_end_date, '2016-6-25 00:01:00' );

		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$this->assertEquals( $plugin_meta_leg_count, 6 );

		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );
		$this->assertEquals( $plugin_meta_thumbnail_id, '' );

		// plugin calculations

		$plugin_start_date = $plugin->get_term_start_date( $term_id, 'tour' );
		$this->assertEquals( $plugin_start_date, '2015-9-2 00:01:00' );

		$plugin_end_date = $plugin->get_term_end_date( $term_id, 'tour' );
		$this->assertEquals( $plugin_end_date, '2016-6-25 00:01:00' );

    	$plugin_tour_length_days = $plugin->get_term_days_elapsed( $plugin_start_date, $plugin_end_date );
		$this->assertEquals( $plugin_tour_length_days, 298 );

    	$plugin_tour_length = $plugin->get_tourlengthdays( $term_id );
		$this->assertEquals( $plugin_tour_length, 298 );

    	$plugin_start_day = $plugin->get_term_start_day( $term_id );
		$this->assertEquals( $plugin_start_day, 1 );

    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
		$this->assertEquals( $plugin_start_month, 'September 2015' );

    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
		$this->assertEquals( $plugin_end_month, 'June 2016' );

    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id );
		$this->assertEquals( $plugin_tour_leg_count, 6 ); // todo test with NZ legs

    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'east-asia' );
		$this->assertEquals( $plugin_tour_leg_name, 'East Asia (2015-2016)' );

    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'east-asia' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );

    	$plugin_tour_leg_ids = get_term_children( $term_id, $this->taxonomy_name );
		$this->assertEquals( $plugin_tour_leg_ids, [
			$this->tour_leg_term_id_1,
			$this->tour_leg_term_id_4,
			$this->tour_leg_term_id_5,
			$this->tour_leg_term_id_6,
			$this->tour_leg_term_id_3,
			$this->tour_leg_term_id_7,
			$this->tour_leg_term_id_2
		]);

  		$plugin_tour_leg_ids_ordered = $plugin->helper_order_tour_terms_by_date( $plugin_tour_leg_ids );
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
		$this->assertEquals( $meta_term_type, 'tour_leg' );

		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$this->assertEquals( $meta_start_date, '2015-9-2' );

		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$this->assertEquals( $meta_end_date, '2015-9-10' );

		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$this->assertEquals( $meta_first_visit, true );

		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$this->assertEquals( $meta_leg_count, '' );

		// todo this shouldn't exist yet
		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
		$this->assertEquals( $meta_thumbnail_id, 926 );

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$this->assertEquals( $plugin_meta_term_type, 'tour_leg' );

		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$this->assertEquals( $plugin_meta_start_date, '2015-9-2 00:01:00' );

		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$this->assertEquals( $plugin_meta_end_date, '2015-9-10 00:01:00' );

		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$this->assertEquals( $plugin_meta_leg_count, '' );

		// todo this shouldn't exist yet
		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );
		$this->assertEquals( $plugin_meta_thumbnail_id, 926 );

		// plugin calculations

		$plugin_start_date = $plugin->get_term_start_date( $term_id, 'tour' );
		$this->assertEquals( $plugin_start_date, '2015-9-2 00:01:00' );

		$plugin_start_date_tour_leg = $plugin->get_term_start_date( $term_id, 'tour_leg' );
		$this->assertEquals( $plugin_start_date_tour_leg, '2015-9-2 00:01:00' );

		$plugin_end_date = $plugin->get_term_end_date( $term_id, 'tour_leg' );
		$this->assertEquals( $plugin_end_date, '2015-9-10 00:01:00' );

    	$plugin_tour_length_days = $plugin->get_term_days_elapsed( $plugin_start_date, $plugin_end_date );
		$this->assertEquals( $plugin_tour_length_days, 9 );
    	
    	$plugin_tour_length = $plugin->get_tourlengthdays( $term_id );
		$this->assertEquals( $plugin_tour_length, 9 );

    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
		$this->assertEquals( $plugin_start_month, 'September 2015' );

    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
		$this->assertEquals( $plugin_end_month, 'September 2015' );

		// todo test with NZ legs
    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id );
		$this->assertEquals( $plugin_tour_leg_count, '' );

    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'china-1' );
		$this->assertEquals( $plugin_tour_leg_name, 'China (Part 1)' );

    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'china-1' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );

    	$plugin_start_day = $plugin->get_term_start_day( $term_id );
		$this->assertEquals( $plugin_start_day, 1 );
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
		$this->assertEquals( $meta_term_type, 'tour_leg' );

		$meta_start_date = get_term_meta( $term_id, 'start_date', true );
		$this->assertEquals( $meta_start_date, '2015-11-29' );

		$meta_end_date = get_term_meta( $term_id, 'end_date', true );
		$this->assertEquals( $meta_end_date, '2016-1-17' );

		$meta_first_visit = get_term_meta( $term_id, 'first_visit', true );
		$this->assertEquals( $meta_first_visit, 0 );

		$meta_leg_count = get_term_meta( $term_id, 'leg_count', true );
		$this->assertEquals( $meta_leg_count, '' );

		$meta_thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
		$this->assertEquals( $meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// term meta, queried via plugin

		$plugin_meta_term_type = $plugin->get_meta_term_type( $term_id );
		$this->assertEquals( $plugin_meta_term_type, 'tour_leg' );

		$plugin_meta_start_date = $plugin->get_meta_term_start_date( $term_id );
		$this->assertEquals( $plugin_meta_start_date, '2015-11-29 00:01:00' );

		$plugin_meta_end_date = $plugin->get_meta_term_end_date( $term_id );
		$this->assertEquals( $plugin_meta_end_date, '2016-1-17 00:01:00' );

		$plugin_meta_leg_count = $plugin->get_meta_tour_category_leg_count( $term_id );
		$this->assertEquals( $plugin_meta_leg_count, '' );

		$plugin_meta_thumbnail_id = $plugin->get_meta_thumbnail_id( $term_id );
		$this->assertEquals( $plugin_meta_thumbnail_id, 926 ); // this shouldn't exist yet

		// plugin calculations

		$plugin_start_date_tour = $plugin->get_term_start_date( $term_id, 'tour' );
		$this->assertEquals( $plugin_start_date_tour, '2015-9-2 00:01:00' );

		$plugin_start_date_tour_leg = $plugin->get_term_start_date( $term_id, 'tour_leg' );
		$this->assertEquals( $plugin_start_date_tour_leg, '2015-11-29 00:01:00' );

		$plugin_end_date_tour_leg = $plugin->get_term_end_date( $term_id, 'tour_leg' );
		$this->assertEquals( $plugin_end_date_tour_leg, '2016-1-17 00:01:00' );

    	$plugin_tour_leg_length_days = $plugin->get_term_days_elapsed( $plugin_start_date_tour_leg, $plugin_end_date_tour_leg );
		$this->assertEquals( $plugin_tour_leg_length_days, 50 );

    	$plugin_tour_leg_length = $plugin->get_tourlengthdays( $term_id );
		$this->assertEquals( $plugin_tour_leg_length, 50 );

		// todo test with NZ legs
    	$plugin_tour_leg_count = $plugin->get_term_leg_count( $term_id );
		$this->assertEquals( $plugin_tour_leg_count, '' );

    	$plugin_tour_leg_name = $plugin->get_term_leg_name( 'china-2' );
		$this->assertEquals( $plugin_tour_leg_name, 'China (Part 2)' );

    	$plugin_tour_leg_id = $plugin->get_term_leg_id( 'china-2' );
		$this->assertEquals( $plugin_tour_leg_id, $term_id );

    	$plugin_start_day = $plugin->get_term_start_day( $term_id );
		$this->assertEquals( $plugin_start_day, 89 );

    	$plugin_start_month = $plugin->get_term_start_month( $term_id );
		$this->assertEquals( $plugin_start_month, 'November 2015' );

    	$plugin_end_month = $plugin->get_term_end_month( $term_id );
		$this->assertEquals( $plugin_end_month, 'January 2016' );
	}
}