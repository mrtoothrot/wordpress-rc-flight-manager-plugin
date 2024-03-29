<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
 * @package           RC_Flight_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       RC Flight Manager
 * Plugin URI:        https://wordpress.org/plugins/rc-flight-manager
 * Description:       A Flight Manager Scheduling System for Modell Airfield Clubs
 * Version:           1.1.0
 * Author:            Mr Toothrot
 * Author URI:        https://github.com/mrtoothrot
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rc-flight-manager
 * Domain Path:       /languages
 */

# To keep plugin translation strings in PO-files generated by poedit:
$temp = esc_html__('RC Flight Manager', 'rc-flight-manager');
$temp = esc_html__('https://wordpress.org/plugins/rc-flight-manager', 'rc-flight-manager');
$temp = esc_html__('Mr Toothrot', 'rc-flight-manager');
$temp = esc_html__('https://github.com/mrtoothrot', 'rc-flight-manager');
$temp = esc_html__('A Flight Manager Scheduling System for Modell Airfield Clubs', 'rc-flight-manager');

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RC_FLIGHT_MANAGER_VERSION', '1.1.0' );
define( 'RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME', 'rcfm_schedule');
define( 'RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME', 'rcfm_logging');
define( 'RC_FLIGHT_MANAGER_FLIGHTSLOT_TABLE_NAME', 'rcfm_slots');
define( 'RC_FLIGHT_MANAGER_FLIGHTSLOT_RESERVATIONS_TABLE_NAME', 'rcfm_slotreservations');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rc-flight-manager-activator.php
 */
function activate_rc_flight_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rc-flight-manager-activator.php';
	RC_Flight_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rc-flight-manager-deactivator.php
 */
function deactivate_rc_flight_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rc-flight-manager-deactivator.php';
	RC_Flight_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rc_flight_manager' );
register_deactivation_hook( __FILE__, 'deactivate_rc_flight_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rc-flight-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function run_rc_flight_manager() {

	$plugin = new RC_Flight_Manager();
	$plugin->run();

}
run_rc_flight_manager();
