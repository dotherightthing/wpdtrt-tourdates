/**
 * @file DTRT Tour dates frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_tourdates_config`.
 * @version 0.0.1
 * @since   0.7.0
 */

/* global jQuery */
/* eslint-disable func-names, camelcase */

/**
 * @namespace wpdtrtTourdatesUi
 */
const wpdtrtTourdatesUi = {

    /**
     * @summary Initialise front-end scripting
     * @since 0.0.1
     */
    init: () => {
        console.log('wpdtrtTourdatesUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(() => {
    const config = wpdtrt_tourdates_config; // eslint-disable-line
    wpdtrtTourdatesUi.init();
});
