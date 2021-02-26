<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
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
		$timestamp = wp_next_scheduled( 'rcfm_scheduled_notifications' );
		// Unscheduling next and all following occurences
		wp_unschedule_event( $timestamp, 'rcfm_scheduled_notifications' );
	}

}
