<?php

/**
 * Plugin Name: Call to Action
 * Plugin URI: https://github.com/WordPress/pettit-realestate
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 1.1.0
 * Author: the Gutenberg Team
 *
 * @package pettit-realestate
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load all translations for our plugin from the MO file.
*/
add_action( 'init', 'call_to_action_load_textdomain' );

function call_to_action_load_textdomain() {
	load_plugin_textdomain( 'pettit-realestate', false, basename( __DIR__ ) . '/languages' );
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function call_to_action_register_block() {

	// automatically load dependencies and version
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');

	wp_register_script(
		'call-to-action',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_register_style(
		'call-to-action',
		plugins_url( 'style.css', __FILE__ ),
		array( ),
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);

	register_block_type( 'pettit-realestate/call-to-action', array(
		'style' => 'call-to-action',
		'editor_script' => 'call-to-action',
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'call-to-action', 'pettit-realestate' );
  }

}
add_action( 'init', 'call_to_action_register_block' );
