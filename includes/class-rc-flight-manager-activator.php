<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */
class RC_Flight_Manager_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Importing the upgrade.php which includes the dbDelta function to update the DB
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );		
		
		// Create schedule table
		RC_Flight_Manager_Activator::create_schedule_table();
		// Create logging table
		RC_Flight_Manager_Activator::create_logging_table();

		// Scheduling CRON job to send notification emails
		if ( ! wp_next_scheduled( 'rcfm_scheduled_notifications' ) ) {
			wp_schedule_event( time(), 'hourly', 'rcfm_scheduled_notifications' );
		}
	}

	static function create_schedule_table() {
		// init WP DB API
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		// Define table name
		if ( ! (defined( 'RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME' ) ) ) {
			return -1;
		}
		$schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
		
		// Creating a Table with the following fields
	    // | schedule_id | date | user_id | comment |
		$create_schedule_table_sql = 
			"CREATE TABLE $schedule_table_name (
				schedule_id mediumint(9) NOT NULL AUTO_INCREMENT,
				date date DEFAULT '0000-00-00' NOT NULL,
	        	user_id mediumint(9),
				comment varchar(140),
				change_id mediumint(9),
				UNIQUE KEY schedule_id (schedule_id)
	    	) 
			$charset_collate;";
		
		// Now execute SQL
	    dbDelta( $create_schedule_table_sql );
	}

	static function create_logging_table() {
		// init WP DB API
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// Define table name
		if ( ! (defined( 'RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME' ) ) ) {
			return -1;
		}
		$logging_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME;
		
		// Creating a Table with the following fields
		// | change_id | date | by_admin | schedule_id | old_user_id | new_user_id | mail_sent |
		
		$create_logging_table_sql = 
			"CREATE TABLE $logging_table_name (
				change_id mediumint(9) NOT NULL AUTO_INCREMENT,
				date date DEFAULT '0000-00-00' NOT NULL,
				by_admin enum('Yes', 'No') DEFAULT 'No' NOT NULL,
				schedule_id mediumint(9) NOT NULL,
				old_user_id mediumint(9),
				new_user_id mediumint(9) NOT NULL,
				mail_sent enum('Yes', 'No') DEFAULT 'No' NOT NULL,
				UNIQUE KEY change_id (change_id)
			) $charset_collate;";

		// Now execute SQL
		dbDelta( $create_logging_table_sql );
	}
}
