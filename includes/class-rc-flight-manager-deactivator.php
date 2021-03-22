<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */
class RC_Flight_Manager_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Unscheduling CRON job to send notification emails
		// Get timestamp of next planed execution
		$timestamp = wp_next_scheduled( 'rcfm_send_daily_flightmanager_notification' );
		// Unscheduling next and all following occurences
		wp_unschedule_event( $timestamp, 'rcfm_send_daily_flightmanager_notification' );
	}

}
