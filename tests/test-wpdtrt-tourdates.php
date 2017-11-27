<?php
/**
 * Unit tests, using PHPUnit and wp-cli.
 *
 * @package wpdtrt_tourdates
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 */

/**
 * TourdatesTest unit tests, using PHPUnit and wp-cli.
 * Note that the plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-tourdates.php
 * Note: only function names prepended with test_ are run
 * $debug logs are output with the test output in Terminal
 */
class TourdatesTest extends WP_UnitTestCase {

	/**
	 * Test set_foo()
	 * Checks that we are dealing with the expected config
	 */
	function test_setters_and_getters() {
		$tour_tax = wpdtrt_tourdates_taxonomy_tour_init();
		$name = 			$tour_tax->get_name();	
		$instance_options = $tour_tax->get_instance_options();
		$labels = 			$tour_tax->get_labels();	
		$plugin = 			$tour_tax->get_plugin();	

		$this->assertTrue( is_string($name) );
		$this->assertEquals( $name, 'wpdtrt_tourdates_taxonomy_tour' );

		$this->assertTrue( is_array($instance_options) );
		$this->assertEquals( $instance_options, array() );

		$this->assertTrue( is_array($labels) );
		$this->assertEquals( $labels, array(
          'slug' => 'tours',
          'singular' => __('Tour', 'wpdtrt-tourdates'),
          'plural' => __('Tours', 'wpdtrt-tourdates'),
          'description' => __('Multiday rides', 'wpdtrt-tourdates'),
          'posttype' => 'post' // 'tourdiaries'
        ) );

		$this->assertTrue( is_object($plugin) );
	}

	/**
	 * Test register_taxonomy()
	 * Checks that the taxonomy exists
	 * @todo This would be better as a WPPlugin test, with a mock Taxonomy
	 */
	function test_register_taxonomy() {
		$tour_tax = wpdtrt_tourdates_taxonomy_tour_init();
		$name = $tour_tax->get_name();

		$this->assertTrue( taxonomy_exists( $name ) );
	}
}