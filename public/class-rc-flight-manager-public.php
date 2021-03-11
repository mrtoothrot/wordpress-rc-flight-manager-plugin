<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/public
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */
class RC_Flight_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RC_Flight_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RC_Flight_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rc-flight-manager-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RC_Flight_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RC_Flight_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/**   Loading the JS and defining the admin_url is done in shortcode function
		 *    Effect: Script is only loaded on page if shortcode is active on the page! 
		 * Last Parameter = true => Load script in footer!
		 * wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		 * Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		 *wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		 */
	}

	/**
	 * Implement the CRON job for sending notification mails
	 *
	 * @since    1.0.0
	 */
	public function rcfm_send_notifications() {

		/**
		 * Defines the CRON to send notification mails.
		 */

		// Test cron-job execution by calling url: http://<wordpress-url>/wp-cron.php
		//error_log("RC_Flight_Manager_Public :: rcfm_send_notifications called!");
		
		// Send Test email
		wp_mail( "mrtoothrot@gmail.com", "Test from WP-CRONJOB", "Hourly mail from cronjob!");

	}

	/**
	 * Finding out ajaxurl needed to call requests against WP ajax-endpoint!
	 * ajaxurl is then used by Java Script code
	 * This function is loaded every time the wp-header is loaded
	 * 	
	 * @since    1.0.0
	 */
	//function set_ajaxurl() {
	//
	//    echo '<script type="text/javascript">
	//               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	//    </script>';
	//}
	

	/**
	 * Register the shortcode for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode('rc-flight-manager-schedule', array( $this, 'shortcode_rc_flight_manager_schedule') );
		add_shortcode('rc_flight_slot_reservation', array( $this, 'shortcode_rc_flight_slot_reservation') );
		//add_shortcode( 'shortcode', array( $this, 'shortcode_function') );
		//add_shortcode( 'anothershortcode', array( $this, 'another_shortcode_function') );
	  }


	public function shortcode_rc_flight_slot_reservation( $atts = [], $content = null) {
		// Last Parameter = true => Load script in footer, so that jQuery can do the action bindings
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		// Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		$content = "";

		// Check if user is logged in
		if ( ! ( is_user_logged_in() ) ) {
			// If user is not logged in, he is not allowed to see the schedule
		 	return "<p><b>Mitgliederbereich! Bitte anmelden um den Dienstplan zu sehen!</b></p>";
		}

		// Update flightslots database with new slots for next 7 days if neccessary
		$today = date_i18n("Y-m-d");
		//do_action( "qm/debug", "Today is ${today}!" );
		$day = $today;
		for ($x = 0; $x <= 6; $x++) {
			RC_Flight_Manager_Flightslot::init_flightslots($day);		
			$day = date_i18n("Y-m-d", strtotime("$day +1 day"));
			//do_action( "qm/debug", "Day is ${day}!" );
		}

		// Get flightslots starting today
		$slots = RC_Flight_Manager_Flightslot::get_flightslots($today);

		// Preparing the table
	    $table = '<table id="table_rc_flight_manager_flightslots">';
	    $table .= '<colgroup>';
	    $table .= '<col>';
	    $table .= '<col span="3">';
	    $table .= '</colgroup>';
	    $header = <<<EOT
			<tr>
			    <th><p align="center">Zeit</p></th>
			    <th><p align="center">Buchungen</p></th>
				<th></th>
			</tr>
			EOT;

		$lastDay = "";
		foreach ( $slots as $s ) {
			//do_action( "qm/debug", $s );
			// Create a sub-header row for each day
			$row = "";
			if ($s->date != $lastDay) {
				$headline_date = date_i18n("l, d. F", strtotime("$s->date"));
				$row .= "<tr>";
				$row .= "<th style=\"background-color: #5388b4; color: #ffffff\" colspan=\"3\">$headline_date</th>"; // TODO:  Better format using Theme CSS later
				$row .= "</tr>";
				$row .= $header;
			}
			// append row to table
			$table .= $row;

			// Create a data row for each service
			$row_id = "table_row_reservation_id_" . $s->reservation_id;
			$row = "";
			$row .= "<tr id=$row_id>";
			$row .= $s->getTableData();
			$row .= "</tr>";

			$table .= $row;
			$lastDay = $s->date;
		}

		// end table
		$table .= '</table>';
	
		// add table to content
		$content .= $table;
		
		// return content
		return $content;
	}


	public function shortcode_rc_flight_manager_schedule( $atts = [], $content = null) {
		//error_log("RC_Flight_Manager_Public :: shortcode_rc_flight_manager_schedule called!");
		//do_action( 'qm/warning', "RC_Flight_Manager_Public :: shortcode_rc_flight_manager_schedule called!" );
		// wp_enqueue_script loads the JS code if shortcode is active
		// (see https://kinsta.com/de/blog/wp-enqueue-scripts/)
		// Last Parameter = true => Load script in footer, so that jQuery can do the action bindings
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		// Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		$content = "";

		// Check if user is logged in
		if ( ! ( is_user_logged_in() ) ) {
			// If user is not logged in, he is not allowed to see the schedule
		 	return "<p><b>Mitgliederbereich! Bitte anmelden um den Dienstplan zu sehen!</b></p>";
		}
		
		// Get information about current user
		//$current_user = wp_get_current_user();
		//$content .= "Hallo " . $current_user->user_firstname . $current_user->user_lastname;

		// Testing
		//$sched = new RC_Flight_Manager_Schedule(1, "2021-01-01", 42, "test");
		//$content .= "sched: " . $sched->schedule_id . $sched->date . $sched->user_id . $sched->comment . "<br>";
		//$content .= "<p><b>TEST ERROR LOG Hello World</b></p>";
		//$content .= "<p>$sched->test</p>";

		// Test error log:
		//error_log("Called shortcode function!");
		//error_log( 'Hello World!' );

		// Test CRON
		//if ( ! wp_next_scheduled( 'rcfm_scheduled_notifications' ) ) {
		//	$content .= "<p>Cron not scheduled!</p>";
		//}
		//else {
		//	$timestamp = wp_next_scheduled( 'rcfm_scheduled_notifications' );
		//	$time = strftime  ("%d/%m/%Y %H:%M:%S", $timestamp);
		//	$content .= "<p>Cron is scheduled:</p>";
		//	$content .= "<p>Next run: $time (unix: $timestamp)</p>";
		//	
		//}
		// List all cronjobs
		//$crons = _get_cron_array();
		//	$content .= "<p>";
		//	foreach ($crons as $c) {
		//		$content .= "<b>Cron entry:</b><br>";
		//		$content .= print_r($c, true);
		//		$content .= '<br>';
		//	}
		//	$content .= "</p>";
		
		// send an email now!
		//wp_mail( "mrtoothrot@gmail.com", "Flight Manager schedule table loaded!","This is just a test!");

		// Load all schedules from DB
		$schedules = RC_Flight_Manager_Schedule::getServiceList();
		
	    // Preparing the table
	    $table = '<table id="table_rc_flight_manager_schedule">';
	    $table .= '<colgroup>';
	    $table .= '<col>';
	    $table .= '<col span="3">';
	    $table .= '</colgroup>';
	    $header = <<<EOT
			<tr>
				<th>Datum</th>
			    <th>Flugleiter/in</th>
			    <th></th>
			</tr>
			EOT;

		$lastMonth = "";
		// Filling table with data
		foreach ( $schedules as $s ) {
			
			//$buttonTakeoverDutyId = "buttonTakeoverDutyId_" . $d->id;
			//$buttonSwapDutyId = "buttonSwapDutyId_" . $d->id;
			//$buttonCancelSwapDutyId = "buttonCancelSwapDutyId_" . $d->id;
			//$buttonProposeSwapDutyId = "buttonProposeSwapDutyId_" . $d->id;
			//$textareaDutyId = "textareaDutyId_" . $d->id;
			//$dutySelectionId = "dutySelectionId_" . $d->id;
		
			$date = strtotime( $s->date );
			//$formatedDate = date_i18n("D j. M", $date);
			$currentMonth = date_i18n("F", $date);
			//$userObj = get_userdata($s->user_id);
		
			// Create a sub-header row for each month
			$row = "";
			if ($currentMonth != $lastMonth) {
				$row .= "<tr>";
				$row .= "<th style=\"background-color: #5388b4; color: #ffffff\" colspan=\"3\">$currentMonth</th>"; // TODO:  Better format using Theme CSS later
				$row .= "</tr>";
				$row .= $header;
			}
			// append row to table
			$table .= $row;

			// Create a data row for each service
			$row_id = "table_row_schedule_id_" . $s->schedule_id;
			$row = "";
			$row .= "<tr id=$row_id>";
			$row .= $s->getTableData();
			$row .= "</tr>";

			$table .= $row;



			//$name = "";
			//if ( $userObj ) {
			//	$name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
			//}
			//if ( $s->user_id == 0 ) { 
			//	// if no user is entered, offer to take over this service!
			//	// append row to table
			//	$table .= $s->getTableData($schedule_id, $formatedDate, $name, $s->comment, $s->getTakeoverButtonHtml());
			//} 
			//else {
			//	$table .= $s->getTableData($schedule_id, $formatedDate, $name, $s->comment, "");
			//}
			//$row .= "<tr id=$schedule_id>";
			//$row .= '<td><p align="center"><b>' . $formatedDate . '</b></p><p align="center" style="background-color: #FF0000; color: #ffffff">' . $s->comment . '</p></td>';
			//if ( $userObj ) {
			//	$row .= "<td>" . esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname ) . "</td>";
			//}
			//else {
			//	$row .= "<td></td>";
			//}

			//if ( $s->user_id == 0 ) { 
			//	// if no user is entered, offer to take over this service!
			//	$row .= "<td>" . $s->getTakeoverButtonHtml() . "</td>";
			//} 
//			elseif ($d->userid == $current_user->ID) {
//				if ($d->swap == "False") {
//					$row .= "<td>" . $d->getSwapButtonHtml() . "</td>";
//				}
//				else {
//					$row .= "<td>" . $d->getCancelSwapButtonHtml() . "</td>";
//				}
//			}
//			elseif ($d->swap == "True") {
//			
//				// get List of own duties
//				$ownDuties = FlightManagerService::getServiceList($current_user->ID);
//			
//				$row .= "<td>" . $d->getProposeSwapButtonHtml($ownDuties) . "</td>";
//			}
			//else {
			//	$row .= "<td></td>";
			//}
			//$row .= "</tr>";
		
			// append row to table
			//$table .= $row;
		
			$lastMonth = $currentMonth;
		}
		// end table
		$table .= '</table>';
	
		// add table to content
		$content .= $table;

		// return content
		return $content;
	}


	function button_takeover() {
		//error_log("RC_Flight_Manager_Public :: button_takeover called!");
		
		// Read ID from HTTP request
	    $schedule_id = $_POST["schedule_id"];
	    //$comment = $_POST["comment"];
	    //$swap = $_POST["swap"];

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Update Duty with current user
	    $s->updateUser($current_user->ID);
	    //$s->saveToDatabase();
	
	    // Calculate date entry
	    //$mysqldate = strtotime( $s->date );
	    //$dutydate = date_i18n("D j. M", $mysqldate);

	    // Prepare new table row
		//$row = "";
		//$row .= $s->getTableData();
	    //$row .= "<td>$dutydate</td>";
	    //$row .= "<td>" . esc_html( $current_user->user_firstname ) . " " . esc_html( $current_user->user_lastname ) . "</td>";
	    //$row .= "<td>" . '$s->getSwapButtonHtml()' . "</td>";
	
	    // return new table data
		echo $s->getTableData();
		//echo "SUCCESSFUL! $schedule_id";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	 function button_handover() {
		//error_log("RC_Flight_Manager_Public :: button_handover called!");
		
		// Read ID from HTTP request
	    $schedule_id = $_POST["schedule_id"];
	    $new_user_id = $_POST["new_user"];
	    
	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get userdata for $new_user_id
	    $new_user_obj = get_userdata($new_user_id);

	    // Update Duty with current user
	    $s->updateUser($new_user_id);
	    //$s->saveToDatabase();
	
	    // Calculate date entry
	    //$mysqldate = strtotime( $s->date );
	    //$dutydate = date_i18n("D j. M", $mysqldate);

	    // Prepare new table row
		//$row = "";
		//$row .= $s->getTableData();
	    //$row .= "<td>$dutydate</td>";
	    //$row .= "<td>" . esc_html( $current_user->user_firstname ) . " " . esc_html( $current_user->user_lastname ) . "</td>";
	    //$row .= "<td>" . '$s->getSwapButtonHtml()' . "</td>";
	
	    // return new table data
		echo $s->getTableData();
		//echo "SUCCESSFUL! $schedule_id";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_assign() {
		//error_log("RC_Flight_Manager_Public :: button_assign called!");
		//do_action( 'qm/warning', "RC_Flight_Manager_Public :: button_assign called!" );
		
		// Read ID from HTTP request
	    $schedule_id = $_POST["schedule_id"];
	    $new_user_id = $_POST["new_user"];
	    
	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get userdata for $new_user_id
	    $new_user_obj = get_userdata($new_user_id);

	    // Update Duty with current user
	    $s->updateUser($new_user_id, "Yes"); // Second parameter = "Yes" => Change done by admin!
	    //$s->saveToDatabase();
	
	    // Calculate date entry
	    //$mysqldate = strtotime( $s->date );
	    //$dutydate = date_i18n("D j. M", $mysqldate);

	    // Prepare new table row
		//$row = "";
		//$row .= $s->getTableData();
	    //$row .= "<td>$dutydate</td>";
	    //$row .= "<td>" . esc_html( $current_user->user_firstname ) . " " . esc_html( $current_user->user_lastname ) . "</td>";
	    //$row .= "<td>" . '$s->getSwapButtonHtml()' . "</td>";
	
	    // return new table data
		echo $s->getTableData();
		//echo "SUCCESSFUL! $schedule_id";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_swap() {
		//error_log("RC_Flight_Manager_Public :: button_swap called!");
		
		// Read ID from HTTP request
	    $schedule_id = $_POST["schedule_id"];
	    $swap_schedule_id = $_POST["swap_schedule_id"];
	    
	    // Load Duty from DB
		// Current user who is giving away a service
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
		// Service which will be swapped
		$s2 = RC_Flight_Manager_Schedule::getServiceById($swap_schedule_id);
	
	    // Get userdata for $new_user_id
	    //$s_user_obj = get_userdata($s->user_id);
		//$s2_user_obj = get_userdata($s2->user_id);

	    // Update Duty with new user
		$temp = $s->user_id;
	    $s->updateUser($s2->user_id);
		$s2->updateUser($temp);
	    //$s->saveToDatabase();
	
	    // Calculate date entry
	    //$mysqldate = strtotime( $s->date );
	    //$dutydate = date_i18n("D j. M", $mysqldate);

	    // Prepare new table row
		//$row = "";
		//$row .= $s->getTableData();
	    //$row .= "<td>$dutydate</td>";
	    //$row .= "<td>" . esc_html( $current_user->user_firstname ) . " " . esc_html( $current_user->user_lastname ) . "</td>";
	    //$row .= "<td>" . '$s->getSwapButtonHtml()' . "</td>";
	
	    // return new table data
		echo $s->getTableData();
		echo ":SEP:";
		echo $s2->getTableData();

		//echo "SUCCESSFUL! $schedule_id";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_book_flightslot() {
		// Read ID from HTTP request
	    $reservation_id = $_POST["reservation_id"];

		// Get Flightslot
		$slot = RC_Flight_Manager_Flightslot::get_flightslot($reservation_id);
		$arrlength = count($slot->bookings);
		if ($arrlength >= RC_FLIGHT_MANAGER_FLIGHTSLOT_MAX_RESERVATIONS ) {
			do_action( "qm/error", "Max reservations reached!" );
			// Return existing table data
			echo $slot->getTableData();
		}

	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

		// Book slot
	    $slot->book($current_user->ID);

	    // return new table data
		echo $slot->getTableData();
		//echo "Test";// ${reservation_id}";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_cancel_flightslot() {
		// Read ID from HTTP request
	    $reservation_id = $_POST["reservation_id"];

		// Get Flightslot
		$slot = RC_Flight_Manager_Flightslot::get_flightslot($reservation_id);

	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

		// Book slot
	    $slot->cancel($current_user->ID);

	    // return new table data
		echo $slot->getTableData();
		//echo "Test";// ${reservation_id}";
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

}