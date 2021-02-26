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
		error_log("RC_Flight_Manager_Public :: rcfm_send_notifications called!");
		
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
	function set_ajaxurl() {
	
	    echo '<script type="text/javascript">
	               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	    </script>';
	}
	

	/**
	 * Register the shortcode for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode('rc-flight-manager-schedule', array( $this, 'shortcode_rc_flight_manager_schedule') );
		//add_shortcode( 'shortcode', array( $this, 'shortcode_function') );
		//add_shortcode( 'anothershortcode', array( $this, 'another_shortcode_function') );
	  }
	
	public function shortcode_rc_flight_manager_schedule( $atts = [], $content = null) {
		error_log("RC_Flight_Manager_Public :: shortcode_rc_flight_manager_schedule called!");

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
				$row .= "<th style=\"background-color: #5388b4; color: #ffffff\" colspan=\"6\">$currentMonth</th>"; // TODO:  Better format using Theme CSS later
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
		};
		// end table
		$table .= '</table>';
	
		// add table to content
		$content .= $table;

		// return content
		return $content;
	}


	function button_takeover() {
		error_log("RC_Flight_Manager_Public :: button_takeover called!");
		
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
}