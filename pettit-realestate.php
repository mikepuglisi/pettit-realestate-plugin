<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.larrypettitrealestate.com
 * @since             1.0.0
 * @package           Pettit_Realestate
 *
 * @wordpress-plugin
 * Plugin Name:       Pettit Real Estate
 * Plugin URI:        https://www.larrypettitrealestate.com/
 * Description:       Pettit Real Estate customization code independent of any theme choice.
 * Version:           1.0.1
 * Author:            Mike Puglisi
 * Author URI:        https://www.larrypettitrealestate.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pettit-realestate
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PETTIT_REALESTATE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pettit-realestate-activator.php
 */
function activate_pettit_realestate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pettit-realestate-activator.php';
  Pettit_Realestate_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pettit-realestate-deactivator.php
 */
function deactivate_pettit_realestate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pettit-realestate-deactivator.php';
	Pettit_Realestate_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pettit_realestate' );
register_deactivation_hook( __FILE__, 'deactivate_pettit_realestate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pettit-realestate.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pettit_realestate() {

  include 'blocks/call-to-action/index.php';
	$plugin = new Pettit_Realestate();
	$plugin->run();

}
run_pettit_realestate();