<?php
/**
 * Unit tests, using PHPUnit, wp-cli, WP_UnitTestCase
 *
 * The plugin is 'active' within a WP test environment
 * 	so the plugin class has already been instantiated
 * 	with the options set in wpdtrt-tourdates.php
 *
 * Only function names prepended with test_ are run.
 * $debug logs are output with the test output in Terminal
 * A failed assertion may obscure other failed assertions in the same test.
 *
 * @package wpdtrt_tourdates
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 * @see http://richardsweeney.com/testing-integrations/
 * @see https://gist.github.com/benlk/d1ac0240ec7c44abd393 - Collection of notes on WP_UnitTestCase
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes//factory/
 * @see https://stackoverflow.com/questions/35442512/how-to-use-wp-unittestcase-go-to-to-simulate-current-pageclass-wp-unittest-factory-for-term.php
 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
 */

/**
 * WP_UnitTestCase unit tests for wpdtrt_tourdates
 */
class TourdatesTest extends WP_UnitTestCase {

    /**
     * SetUp
     * Automatically called by PHPUnit before each test method is run
     */
    public function setUp() {
  		// Make the factory objects available.
        parent::setUp();

        $this->taxonomy = $this->create_taxonomy();
        $this->create_customposttype();

        $this->plugin = $this->taxonomy->get_plugin();

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

		// create legs in alphabetical order so we can test resorting into date order

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

	    $this->post_id_1 = $this->create_post( array(
	    	'post_title' => 'The First Tour Day',
	    	'post_date' => '2015-09-21 23:00:00',
	    	'term_ids' => array(
	    		$this->region_term_id,
	    		$this->tour_term_id,
	    		$this->tour_leg_term_id_2
	    	)
	    ) );

	    $this->post_id_2 = $this->create_post( array(
	    	'post_title' => 'The Second Tour Day',
	    	'post_date' => '2015-09-22 23:00:00',
	    	'term_ids' => array(
	    		$this->region_term_id,
	    		$this->tour_term_id,
	    		$this->tour_leg_term_id_2
	    	)
	    ) );

	    $this->post_id_3 = $this->create_post( array(
	    	'post_title' => 'The Third Tour Day',
	    	'post_date' => '2015-09-23 23:00:00',
	    	'term_ids' => array(
	    		$this->region_term_id,
	    		$this->tour_term_id,
	    		$this->tour_leg_term_id_2
	    	)
	    ) );

	    // https://github.com/dotherightthing/wpdtrt-tourdates/issues/12
	    $this->post_id_4_malformed = $this->create_post( array(
	    	'post_title' => 'The Fourth Tour Day',
	    	'post_date' => '2015-09-24 23:00:00',
	    	'term_ids' => array(
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

    	wp_delete_post( $this->post_id_1, true );
    	wp_delete_post( $this->post_id_2, true );
    	wp_delete_post( $this->post_id_3, true );
    	wp_delete_post( $this->post_id_4_malformed, true );
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

        //global $debug;
        //$debug->log('Created post ' . $post_title . ' with id of ' . $post_id);

 		// test the state of things after the 'save_post' action
 		// https://github.com/dotherightthing/wpdtrt-tourdates/issues/12
		$this->assertFalse(
			$this->plugin->post_has_required_terms( $post_id ),
			$post_title . ' should not be assigned terms until wp_set_object_terms()'
		);

 		// test the state of things after the 'save_post' action
 		// https://github.com/dotherightthing/wpdtrt-tourdates/issues/12
		$this->assertTrue(
			$this->plugin->post_has_required_posttype( $post_id ),
			$post_title . ' does not have the required posttype'
		);

		// set the terms
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
		
		$this->assertTrue(
			is_object( $this->taxonomy->get_plugin() ),
			'Taxonomy is not an object'
		);

		$this->assertTrue(
			is_string( $this->taxonomy->get_name() ),
			'Taxonomy name is not a string'
		);

		$this->assertEquals(
			$this->taxonomy->get_name(),
			'wpdtrt_tourdates_taxonomy_tour',
			'Taxonomy name is set to the wrong value'
		);

		$this->assertTrue(
			taxonomy_exists( $this->taxonomy->get_name() ),
			'Taxonomy does not exist'
		);

		$this->assertTrue(
			is_array( $this->taxonomy->get_instance_options() ),
			'Instance options is not an array'
		);

		$this->assertEquals(
			$this->taxonomy->get_instance_options(),
			array(),
			'Instance options is not an empty array'
		);

		$this->assertTrue(
			is_array( $this->taxonomy->get_labels() ),
			'Labels is not an array'
		);
		$this->assertEquals(
			$this->taxonomy->get_labels(),
			array(
	          'slug' => 'tours',
	          'singular' => 'Tour',
	          'plural' => 'Tours',
	          'description' => 'Multiday rides',
	          'posttype' => 'tourdiaries'
	        ),
	        'Labels array contains the wrong values'
		);
	}

	/**
	 * Test shortcodes
	 * 	trim() removes line break added by WordPress
	 *
	 * @todo wpdtrt_tourdates_shortcode_navigation
	 * @todo wpdtrt_tourdates_shortcode_thumbnail
	 * @todo Refactor wpdtrt_tourdates_shortcode_summary so that it is easier to test
	 */
	public function test_shortcodes() {

		// todo https://github.com/dotherightthing/wpdtrt-plugin/issues/43
		$this->assertEquals(
			trim( do_shortcode( '[wpdtrt_tourdates_shortcode_tourlengthdays term_id="' . $this->tour_leg_term_id_1 .'" text_before="" text_after=" days"]' ) ),
			'9 days',
			'wpdtrt_tourdates_shortcode_tourlengthdays does not return the correct tourlength'
		);

		$this->go_to(
			get_post_permalink( $this->post_id_2 )
		);

		$this->assertEquals(
			trim( do_shortcode( '[wpdtrt_tourdates_shortcode_daynumber]' ) ),
			'21',
			'wpdtrt_tourdates_shortcode_daynumber does not return the correct daynumber'
		);

		$this->assertEquals(
			trim( do_shortcode( '[wpdtrt_tourdates_shortcode_daytotal]' ) ),
			'298',
			'wpdtrt_tourdates_shortcode_daytotal does not return the correct daytotal'
		);

/*
		$term_summary = do_shortcode( '[wpdtrt_tourdates_shortcode_summary term_id="' . $this->tour_leg_term_id_5 . '"]' );
		$this->assertEquals( trim( $term_summary ), '<div class="entry-summary-wrapper">
<div class="entry-date"></div>
<div class="entry-summary">
	<p>
		Camping my way around Hong Kong.<br/>
This tour leg lasted 57 days.  		</p>
</div>
</div>
Camping my way around Hong Kong.' );
*/
	}

	/**
	 * Test tourdiaries post type navigation
	 * 	Note: ambrosite-nextprevious-post-link-plus is loaded in bootstrap.php
	 */
	public function test_post_navigation() {

		$this->go_to(
			get_post_permalink( $this->post_id_2 )
		);

		$this->assertEquals(
			trim( $this->plugin->render_navigation_link('previous', 'tourdiaries') ),
			'<a href="http://example.org/?tourdiaries=the-first-tour-day" rel="prev" title="Previous: Day 20."><span class="stack--navigation--text says">Previous: Day 20</span> <span class="icon-arrow-left stack--navigation--icon"></span></a>',
			'Previous rendered navigation link not correct'
		);

		$this->assertEquals(
			trim( $this->plugin->render_navigation_link('next', 'tourdiaries') ),
			'<a href="http://example.org/?tourdiaries=the-third-tour-day" rel="next" title="Next: Day 22."><span class="stack--navigation--text says">Next: Day 22</span> <span class="icon-arrow-right stack--navigation--icon"></span></a>',
			'Next rendered navigation link not correct'
		);
	}

	/**
	 * Test tourdiaries post type (plural)
	 *
	 * @todo $this->plugin->render_permalink_placeholders()
	 */
	public function test_posts() {

		$post_ids = array(
			$this->post_id_1,
			$this->post_id_2,
			$this->post_id_3,
			// $this->post_id_4_malformed - see test_post_missing_terms()
		);

		foreach( $post_ids as $post_id ) {

			// plugin calculations

			$this->assertEquals(
				$this->plugin->get_term_start_date( $post_id, 'tour' ),
				'2015-9-2 00:01:00',
				'Wrong start date returned, for tour assigned to post ' . $post_id
			);

			$this->assertEquals(
				$this->plugin->get_term_end_date( $post_id, 'tour' ),
				'2016-6-25 00:01:00',
				'Wrong end date returned, for tour assigned to post ' . $post_id
			);
		}
	}

	/**
	 * Test tourdiaries post type which is missing the 3 required terms
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-tourdates/issues/12
	 */
	public function test_post_missing_terms() {

		$this->assertEquals(
			$this->plugin->get_term_id( $this->post_id_4_malformed, 'tour' )->get_error_message(),
			'Tour Dates plugin error: Please assign all three "Tour" levels to $post id = ' . $this->post_id_4_malformed,
			'No term_id expected, < 3 terms assigned to post ' . $this->post_id_4_malformed
		);		

		$this->assertEquals(
			$this->plugin->get_term_start_date( $this->post_id_4_malformed, 'tour' ),
			'',
			'Start date not expected, < 3 terms assigned to post ' . $this->post_id_4_malformed
		);

		$this->assertEquals(
			$this->plugin->get_term_end_date( $this->post_id_4_malformed, 'tour' ),
			'',
			'End date not expected, < 3 terms assigned to post ' . $this->post_id_4_malformed
		);

		$this->assertEquals(
			$this->plugin->get_term_id( $this->post_id_4_malformed, 'tour_leg' ),
			$this->tour_leg_term_id_2,
			'Wrong term_id returned, for tour assigned to post ' . $this->post_id_4_malformed
		);	
	}

	/**
	 * Test tourdiaries post type
	 *
	 * @todo $this->plugin->render_permalink_placeholders()
	 */
	public function test_post() {

		// plugin calculations

		$this->assertEquals(
			$this->plugin->get_post_daynumber( $this->post_id_2 ),
			21,
			'Wrong daynumber returned, for post ' . $this->post_id_2
		);

		// https://github.com/dotherightthing/wpdtrt-tourdates/issues/1
		$this->assertEquals(
			$this->plugin->get_daytotal( $this->post_id_2, 'tour' ),
			298,
			'Wrong daytotal returned, relative to tour assigned to post ' . $this->post_id_2
		);

		$this->assertEquals(
			$this->plugin->get_term_id( $this->post_id_2, 'tour_leg' ),
			$this->tour_leg_term_id_2,
			'Wrong term_id returned, for tour_leg assigned to post ' . $this->post_id_2
		);

		$this->assertEquals(
			$this->plugin->get_term_id( $this->post_id_2, 'tour' ),
			$this->tour_term_id,
			'Wrong term_id returned, for tour assigned to post ' . $this->post_id_2
		);

		$this->assertEquals(
			$this->plugin->get_term_id( $this->post_id_2, 'tour_leg' ),
			$this->tour_leg_term_id_2,
			'Wrong term_id returned, for tour_leg assigned to post ' . $this->post_id_2
		);	

		// todo
		$this->assertEquals(
			$this->plugin->filter_post_title_add_day('Post title', $this->post_id_2),
			'Post title',
			'Post title filter does not add day to title of post ' . $this->post_id_2
		);
	}

	/**
	 * Test taxonomy
	 */
	public function test_taxonomy() {

		$this->assertEquals(
			$this->plugin->get_the_taxonomy(),
			$this->taxonomy_name,
			'Taxonomy has the wrong name'
		);
	}

	/**
	 * Test location
	 */
	public function todo_location() {

		// plugin calculations
		/*
		$key = '';
		$this->assertEquals(
			$this->plugin->get_html_latlng( $key ),
			12345,
			'Wrong lat/lng returned for ' . $key
		);
		*/
	}

	/**
	 * Test region term
	 */
	public function test_region_term() {

		// term meta, queried directly

		$this->assertEquals(
			get_term_meta( $this->region_term_id, 'term_type', true ),
			'region',
			'region has the wrong term_type, when queried directly'
		);

		// term meta, queried via plugin

		$this->assertEquals(
			$this->plugin->get_meta_term_type( $this->region_term_id ),
			'region',
			'region has the wrong term_type, when queried by plugin'
		);
	}

	/**
	 * Test tour term
	 */
	public function test_tour_term() {

		// term meta, queried directly

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'term_type', true ),
			'tour',
			'tour has the wrong term_type, when queried directly'
		);

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'start_date', true ),
			'2015-9-2',
			'tour has the wrong start date, when queried directly'
		);

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'end_date', true ),
			'2016-6-25',
			'tour has the wrong end date, when queried directly'
		);

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'first_visit', true ),
			'',
			'tour has the wrong first_visit state, when queried directly'
		);

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'leg_count', true ),
			6,
			'tour has the wrong leg count, when queried directly'
		);

		$this->assertEquals(
			get_term_meta( $this->tour_term_id, 'thumbnail_id', true ),
			'',
			'tour has the wrong thumbnail_id, when queried directly'
		);

		// term meta, queried via plugin

		$this->assertEquals(
			$this->plugin->get_meta_term_type( $this->tour_term_id ),
			'tour',
			'tour has the wrong term_type, when queried by plugin'
		);

		$this->assertEquals(
			$this->plugin->get_meta_term_start_date( $this->tour_term_id ),
			'2015-9-2 00:01:00',
			'tour has the wrong start date, when queried by plugin'
		);

		$this->assertEquals(
			$this->plugin->get_meta_term_end_date( $this->tour_term_id ),
			'2016-6-25 00:01:00',
			'tour has the wrong end date, when queried by plugin'
		);

		$this->assertEquals(
			$this->plugin->get_meta_tour_category_leg_count( $this->tour_term_id ),
			6,
			'tour has the wrong leg count, when queried by plugin'
		);

		$this->assertEquals(
			$this->plugin->get_meta_thumbnail_id( $this->tour_term_id ),
			'',
			'tour has the wrong thumbnail_id, when queried by plugin'
		);

		// plugin calculations

		$this->assertEquals(
			$this->plugin->get_term_start_date( $this->tour_term_id, 'tour' ),
			'2015-9-2 00:01:00',
			'tour has the wrong start date'
		);

		$this->assertEquals(
			$this->plugin->get_term_end_date( $this->tour_term_id, 'tour' ),
			'2016-6-25 00:01:00',
			'tour has the wrong end date'
		);

		$this->assertEquals(
			$this->plugin->get_term_days_elapsed(
				$this->plugin->get_term_start_date( $this->tour_term_id, 'tour' ),
				$this->plugin->get_term_end_date( $this->tour_term_id, 'tour' )
			),
			298,
			'tour has the wrong number of elapsed days (length)'
		);

		$this->assertEquals(
			$this->plugin->get_tourlengthdays( $this->tour_term_id ),
			298,
			'tour has the wrong tour length in days'
		);

		$this->assertEquals(
			$this->plugin->get_term_start_day( $this->tour_term_id ),
			1,
			'tour has the wrong start day'
		);

		$this->assertEquals(
			$this->plugin->get_term_start_month( $this->tour_term_id ),
			'September 2015',
			'tour has the wrong start month'
		);

		$this->assertEquals(
			$this->plugin->get_term_end_month( $this->tour_term_id ),
			'June 2016',
			'tour has the wrong end month'
		);

		// todo test with NZ legs
		$this->assertEquals(
			$this->plugin->get_term_leg_count( $this->tour_term_id ),
			6,
			'tour has the wrong leg count'
		);

		$this->assertEquals(
			$this->plugin->get_term_leg_name( 'east-asia' ),
			'East Asia (2015-2016)',
			'tour has the wrong name'
		);

		$this->assertEquals(
			$this->plugin->get_term_leg_id( 'east-asia' ),
			$this->tour_term_id,
			'tour has the wrong id'
		);

		$this->assertEquals(
			get_term_children( $this->tour_term_id, $this->taxonomy_name ),
			[
				$this->tour_leg_term_id_1,
				$this->tour_leg_term_id_4,
				$this->tour_leg_term_id_5,
				$this->tour_leg_term_id_6,
				$this->tour_leg_term_id_3,
				$this->tour_leg_term_id_7,
				$this->tour_leg_term_id_2
			],
			'tour has the wrong tour legs'
		);

		$this->assertEquals(
			$this->plugin->helper_order_tour_terms_by_date(
				[
					$this->tour_leg_term_id_1,
					$this->tour_leg_term_id_4,
					$this->tour_leg_term_id_5,
					$this->tour_leg_term_id_6,
					$this->tour_leg_term_id_3,
					$this->tour_leg_term_id_7,
					$this->tour_leg_term_id_2
				]
			),
			[
				$this->tour_leg_term_id_1,
				$this->tour_leg_term_id_2,
				$this->tour_leg_term_id_3,
				$this->tour_leg_term_id_4,
				$this->tour_leg_term_id_5,
				$this->tour_leg_term_id_6,
				$this->tour_leg_term_id_7
			],
			'tour has tour legs in the wrong order'
		);
	}

	/**
	 * Test tour_leg terms
	 */
	public function test_tour_leg_terms() {

		$tour_leg_ids = array(
			$this->tour_leg_term_id_1,
			$this->tour_leg_term_id_4
		);

		foreach( $tour_leg_ids as $tour_leg_id ) {

			// term meta, queried directly

			$this->assertEquals(
				get_term_meta( $tour_leg_id, 'term_type', true ),
				'tour_leg',
				'tour_leg has wrong term_type'
			);

			$this->assertEquals(
				get_term_meta( $tour_leg_id, 'leg_count', true ),
				'',
				'tour_leg has wrong leg_count'
			);

			// todo this shouldn't exist yet
			$this->assertEquals(
				get_term_meta( $tour_leg_id, 'thumbnail_id', true ),
				926,
				'tour_leg has wrong thumbnail_id'
			);

			// term meta, queried via plugin

			$this->assertEquals(
				$this->plugin->get_meta_term_type( $tour_leg_id ),
				'tour_leg',
				'tour_leg has wrong term_type'
			);

			$this->assertEquals(
				$this->plugin->get_meta_tour_category_leg_count( $tour_leg_id ),
				'',
				'tour_leg has wrong leg_count'
			);

			// todo this shouldn't exist yet
			$this->assertEquals(
				$this->plugin->get_meta_thumbnail_id( $tour_leg_id ),
				926,
				'tour_leg has wrong thumbnail_id'
			);

			// plugin calculations

			$this->assertEquals(
				$this->plugin->get_term_start_date( $tour_leg_id, 'tour' ),
				'2015-9-2 00:01:00',
				'tour has wrong start date, when queried from tour leg'
			);

			// todo test with NZ legs
			$this->assertEquals(
				$this->plugin->get_term_leg_count( $tour_leg_id ),
				'',
				'tour_leg has wrong calculated leg_count'
			);
		}
	}

	/**
	 * Test tour_leg term #1
	 */
	public function test_tour_leg_term() {

		// term meta, queried directly

		// 1
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_1, 'start_date', true ),
			'2015-9-2',
			'tour_leg has wrong start_date, when queried directly'
		);

		// 4
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_4, 'start_date', true ),
			'2015-11-29',
			'tour_leg has wrong start_date, when queried directly'
		);

		// 1
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_1, 'end_date', true ),
			'2015-9-10',
			'tour_leg has wrong end_date, when queried directly'
		);

		// 4
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_4, 'end_date', true ),
			'2016-1-17',
			'tour_leg has wrong end_date, when queried directly'
		);

		// 1
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_1, 'first_visit', true ),
			true,
			'tour_leg has wrong value for first_visit, when queried directly'
		);

		// 4
		$this->assertEquals(
			get_term_meta( $this->tour_leg_term_id_4, 'first_visit', true ),
			0,
			'tour_leg has wrong value for first_visit, when queried directly'
		);

		// term meta, queried via plugin

		// 1
		$this->assertEquals(
			$this->plugin->get_meta_term_start_date( $this->tour_leg_term_id_1 ),
			'2015-9-2 00:01:00',
			'tour_leg has wrong start_date, when queried by plugin'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_meta_term_start_date( $this->tour_leg_term_id_4 ),
			'2015-11-29 00:01:00',
			'tour_leg has wrong start_date, when queried by plugin'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_meta_term_end_date( $this->tour_leg_term_id_1 ),
			'2015-9-10 00:01:00',
			'tour_leg has wrong end_date, when queried by plugin'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_meta_term_end_date( $this->tour_leg_term_id_4 ),
			'2016-1-17 00:01:00',
			'tour_leg has wrong end_date, when queried by plugin'
		);

		// plugin calculations

		// 1
		$this->assertEquals(
			$this->plugin->get_term_start_date( $this->tour_leg_term_id_1, 'tour_leg' ),
			'2015-9-2 00:01:00',
			'tour_leg has wrongly calculated start date'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_start_date( $this->tour_leg_term_id_4, 'tour_leg' ),
			'2015-11-29 00:01:00',
			'tour_leg has wrongly calculated start date'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_end_date( $this->tour_leg_term_id_1, 'tour_leg' ),
			'2015-9-10 00:01:00',
			'tour_leg has wrongly calculated end date'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_end_date( $this->tour_leg_term_id_4, 'tour_leg' ),
			'2016-1-17 00:01:00',
			'tour_leg has wrongly calculated end date'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_days_elapsed(
				$this->plugin->get_term_start_date( $this->tour_leg_term_id_1, 'tour_leg' ),
				$this->plugin->get_term_end_date( $this->tour_leg_term_id_1, 'tour_leg' )
			),
			9,
			'tour_leg has wrong number of days elapsed'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_days_elapsed(
				$this->plugin->get_term_start_date( $this->tour_leg_term_id_4, 'tour_leg' ),
				$this->plugin->get_term_end_date( $this->tour_leg_term_id_4, 'tour_leg' )
			),
			50,
			'tour_leg has wrong number of days elapsed'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_tourlengthdays( $this->tour_leg_term_id_1 ),
			9,
			'tour_leg has wrong length in days'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_tourlengthdays( $this->tour_leg_term_id_4 ),
			50,
			'tour_leg has wrong length in days'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_start_month( $this->tour_leg_term_id_1 ),
			'September 2015',
			'tour_leg has wrong start month'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_start_month( $this->tour_leg_term_id_4 ),
			'November 2015',
			'tour_leg has wrong start month'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_end_month( $this->tour_leg_term_id_1 ),
			'September 2015',
			'tour_leg has wrong end month'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_end_month( $this->tour_leg_term_id_4 ),
			'January 2016',
			'tour_leg has wrong end month'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_leg_name( 'china-1' ),
			'China (Part 1)',
			'tour_leg has wrong name'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_leg_name( 'china-2' ),
			'China (Part 2)',
			'tour_leg has wrong name'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_leg_id( 'china-1' ),
			$this->tour_leg_term_id_1,
			'tour_leg has wrong ID'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_leg_id( 'china-2' ),
			$this->tour_leg_term_id_4,
			'tour_leg has wrong ID'
		);

		// 1
		$this->assertEquals(
			$this->plugin->get_term_start_day( $this->tour_leg_term_id_1 ),
			1,
			'tour_leg has wrong start day'
		);

		// 4
		$this->assertEquals(
			$this->plugin->get_term_start_day( $this->tour_leg_term_id_4 ),
			89,
			'tour_leg has wrong start day'
		);
	}
}