<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.larrypettitrealestate.com
 * @since      1.0.0
 *
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pettit_Realestate
 * @subpackage Pettit_Realestate/includes
 * @author     Mike Puglisi <mikepuglisi@gmail.com>
 */
class Pettit_Realestate_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pettit-realestate',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
