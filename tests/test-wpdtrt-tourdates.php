<?php
/**
 * Unit tests, using PHPUnit and wp-cli.
 *
 * @package wpdtrt_tourdates
 * @see https://developer.wordpress.org/cli/commands/
 * @see https://phpunit.de/manual/current/en/appendixes.assertions.html
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://pippinsplugins.com/unit-tests-wordpress-plugins-writing-tests/
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ for setup
 */

/**
 * WeatherTest unit tests, using PHPUnit and wp-cli.
 * Note that the plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-weather.php
 * Note: only function names prepended with test_ are run
 */
class TourdatesTest extends WP_UnitTestCase {

	/**
	 * Test get_plugin_options()
	 * Tests that retrieved plugin options match what was entered
	 */
	function test_sample() {

		// note that $debug logs are output with the test output in Terminal :)
		//$this->setup(); // not required as the plugin has initialised already
		global $debug, $wpdtrt_tourdates;

	    $this->assertTrue( true );
	}
}