<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 *
 * @package    RC_Flight_Manager
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Removing our plugin settings
$option_name = 'rcfm_settings';
delete_option($option_name);

// Removing our plugins database tables 
// Verify constants with table names in rc-flight-manager.php
//define( 'RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME', 'rcfm_schedule');
//define( 'RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME', 'rcfm_logging');
//define( 'RC_FLIGHT_MANAGER_FLIGHTSLOT_TABLE_NAME', 'rcfm_slots');
//define( 'RC_FLIGHT_MANAGER_FLIGHTSLOT_RESERVATIONS_TABLE_NAME', 'rcfm_slotreservations');
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}rcfm_schedule");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}rcfm_logging");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}rcfm_slots");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}rcfm_slotreservations");
