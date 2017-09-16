<?php
/**
 * Rewrite Rules
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
 * Add rewrite rules in case another plugin flushes rules
 */
add_action('init', 'wpdtrt_tourdates_rewrite_rules');

/**
 * Add custom rewrite rules
 * WordPress allows theme and plugin developers to programmatically specify new, custom rewrite rules.
 *
 * @see http://clivern.com/how-to-add-custom-rewrite-rules-in-wordpress/
 * @see https://www.pmg.com/blog/a-mostly-complete-guide-to-the-wordpress-rewrite-api/
 * @see https://www.addedbytes.com/articles/for-beginners/url-rewriting-for-beginners/
 * @see http://codex.wordpress.org/Rewrite_API
 * @see https://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2
 */
function wpdtrt_tourdates_rewrite_rules() {

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

?>