<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
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
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
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
	 */
	public function rcfm_send_daily_flightmanager_notification_email() {
		//error_log("RC_Flight_Manager_Public::rcfm_send_daily_flightmanager_notification_email() called!");
		/**
		 * Defines the CRON to send notification mails.
		 */
		
		// Exit if email notification is turned off in options page
		$options = get_option( 'rcfm_settings');
		if (!isset($options['enable_email_notification_field'])) {
			return;
		}
		
		// Calculating dates
		$today = date_i18n("Y-m-d");
		$in_2_days = date_i18n("Y-m-d", strtotime("$today +2 days"));
		$in_14_days = date_i18n("Y-m-d", strtotime("$today +14 days"));
		
		// Get the service in two days
		$services = array(RC_Flight_Manager_Schedule::getServiceByDate($in_2_days), RC_Flight_Manager_Schedule::getServiceByDate($in_14_days));

		foreach($services as $service){
			if ( !is_null($service) ) {
				// Get User data
				$userObj = get_userdata($service->user_id);

				if ($userObj) {
					$name = esc_html( $userObj->user_firstname );// . " " . esc_html( $userObj->user_lastname );

					// Prepare recipient list
					$email_receipients = array();

					if (is_email($userObj->user_email) && ($options['notify_flightmanagers_email_field'])) {
						array_push($email_receipients, $userObj->user_email);
					}
					if (is_email($options['notify_additional_email_field'])) {
						array_push($email_receipients, $options['notify_additional_email_field']);
					}

					$date = date_i18n("d. F", strtotime($service->date));
					
					// Define placeholder replacements
					$replace_from = array('[flightmanager-duty-date]', '[flightmanager-name]');
					$replace_to = array($date, $name);

					// Construct E-Mail Subject line
					$email_subject = str_replace($replace_from, $replace_to, $options['notification_email_subject_field'] );
					
					// Set E-Mail headers and construct E-Mail body
					$email_headers = array('Content-Type: text/html; charset=UTF-8');
					$email_body = str_replace($replace_from, $replace_to, $options['notification_email_body_field'] );

					// Finally sending the email
					if (count($email_receipients) > 0) {
						wp_mail($email_receipients, $email_subject, $email_body, $email_headers);
					}
				}
			}
		}
	}


	/**
	 * Register the shortcode for the public-facing side of the site.
	 *
	 */
	public function register_shortcodes() {
		add_shortcode('rc-flight-manager-schedule', array( $this, 'shortcode_rc_flight_manager_schedule') );
		add_shortcode('rc-flight-slot-reservation', array( $this, 'shortcode_rc_flight_slot_reservation') );
	}

	/**
	public function shortcode_rc_flight_manager_debug( $atts = [], $content = null) {
		// Last Parameter = true => Load script in footer, so that jQuery can do the action bindings
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		// Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		
		// Return content
		return($content);
	} 
	*/

	public function shortcode_rc_flight_slot_reservation( $atts = [], $content = null) {
		/**
		 * Implementing the RF flight slot reservation table
		 */
		if (!current_user_can( 'read' ) ) {
			// If user doesn't have the read capability, he is not allowed to see the reservation system
			// Users must have at least 'subscriber' role to see the reservation system!
			$message = esc_html__('Members only! Please login!', 'rc-flight-manager');
		 	return '<p><b>' . $message . '</b></p>';
		}

		// Last Parameter = true => Load script in footer, so that jQuery can do the action bindings
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		// Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security_nonce' => wp_create_nonce('rcfm-security-nonce') ) );

		$content = "";

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
	    //$header = '<tr><th><p align="center">' . esc_html__('Time', 'rc-flight-manager') . '</p></th>' . 
		//			  '<th><p align="center">' . esc_html__('Bookings', 'rc-flight-manager') . '</p></th><th></th></tr>';

		$lastDay = "";
		foreach ( $slots as $s ) {
			//do_action( "qm/debug", $s );
			// Create a sub-header row for each day
			$row = "";
			if ($s->date != $lastDay) {
				$headline_date = date_i18n("l, d. F", strtotime("$s->date"));
				$row .= "<tr>";
				$row .= "<th class=\"rcfm-table-headers\" colspan=\"3\">$headline_date</th>";
				$row .= "</tr>";
				//$row .= $header;
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


	public function shortcode_rc_flight_manager_schedule( $atts = [], $content = null, $tag = '') {
		/**
		 * Implementing the RF flight manager scheduling table
		 */
		//do_action( 'qm/debug', 'shortcode_rc_flight_manager_schedule() called!' );

		 // Checking if user has the 'read' capability
		 if (!current_user_can( 'read' ) ) {
			// If user doesn't have the read capability, he is not allowed to see the schedule
			// Users must have at least 'subscriber' role to see the roster!
			$message = esc_html__('Members only! Please login!', 'rc-flight-manager');
		 	return '<p><b>' . $message . '</b></p>';
		}
		
		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		// Get attribute 'months':
		$display_months = NULL;
		if (array_key_exists('months', $atts)) {
			$display_months = (int)$atts['months']; // Casting $atts['months'] to int, getServiceList handles string and int arguments differently
		}

		// Get attribute 'year':
		$display_year = NULL;
		if (array_key_exists('year', $atts)) {
			$display_year = (int)$atts['year']; // Casting $atts['year'] to int, getServiceList handles string and int arguments differently
		}

		// wp_enqueue_script loads the JS code if shortcode is active
		// (see https://kinsta.com/de/blog/wp-enqueue-scripts/)
		// Last Parameter = true => Load script in footer, so that jQuery can do the action bindings
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-public.js', array( 'jquery' ), $this->version, true );
		// Defining ajax_url: (see https://wordpress.stackexchange.com/questions/223331/using-ajax-in-frontend-with-wordpress-plugin-boilerplate-wppb-io)
		wp_localize_script( $this->plugin_name, 'rc_flight_manager_vars', array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security_nonce' => wp_create_nonce('rcfm-security-nonce') ) );
		
		// Load all schedules from DB
		$schedules = RC_Flight_Manager_Schedule::getServiceList($display_months, $display_year);
		
		// Begin enclosing <div>
		$content .= '<div id="schedule" class="rcfm-schedule">';
		
		// Define a container for the AJAX retrieved modals
		$content .= '<div id="modal-container" class="rcfm-schedule-modal-container">';
		$content .= '</div>';

	    // Preparing the function buttons on top of the table
		if (current_user_can( 'edit_posts' ) ) {
			// Display buttons

			$content .= '<p align="left">';
			$content .= '   <button id="add_date_btn">' . esc_html__('Add entry', 'rc-flight-manager') . '</button>';
			$content .= '   <button id="add_date_range_btn">' . esc_html__('Add series of entries', 'rc-flight-manager') . '</button>';
			$content .= '</p>';

			// Defining the add_date_btn Modal
			$content .= '<div id="add_date_btn_modal" class="modal">';
			// Modal content
			$content .= '   <div class="modal-content">';
			$content .= '   <span class="close">&times;</span>';
			$content .= '   <p align="center"><label for="addDateField">' . esc_html__('Select date', 'rc-flight-manager') . ':</label>'
			          . '   <input type="date" id="addDateField" name="date" min="' . date_i18n("Y-m-d") . '"></p>';
			$content .= '   <p align="center"><button type="button" id="add_date_btn_ok" class="modal_ok">' . esc_html__('Ok', 'rc-flight-manager') . '</button>';
			$content .= '   <button type="button" id="add_date_btn_abort" class="modal_abort">' . esc_html__('Cancel', 'rc-flight-manager') . '</button></p>';
			$content .= '   </div>';
			$content .= '</div>';
	
			// Defining the add_date__range_btn Modal
			$content .= '<div id="add_date_range_btn_modal" class="modal">';
			// Modal content
            $content .= '   <div class="modal-content">';
			$content .= '   <span class="close">&times;</span>';
            $content .= '   <p align="left">' . esc_html__('From when to when do you need flight manager services?', 'rc-flight-manager') . '<br><br>'
			          . '   <label for="fromDateField">' . esc_html__('From:', 'rc-flight-manager') .'</label> <input type="date" id="fromDateField" name="date" min="' . date_i18n("Y-m-d") . '">'
                      . '   <label for="toDateField">' . esc_html__('To:', 'rc-flight-manager') . '</label> <input type="date" id="toDateField" name="date" min="' . date_i18n("Y-m-d") . '"></p>';
			$content .= '   <p>' . esc_html__('On which weekdays do you need flight manager services?', 'rc-flight-manager') . '</p>';
			$content .= '   <p>'
                      . '   <input type="checkbox" class="weekdayselect" id="0" name="monday" value="' . esc_html__('Monday', 'rc-flight-manager') . '">'
                      . '   <label for="monday"> ' . esc_html__('Monday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="1" name="tuesday" value="' . esc_html__('Tuesday', 'rc-flight-manager') . '">'
                      . '   <label for="tuesday"> ' . esc_html__('Tuesday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="2" name="wednesday" value="' . esc_html__('Wednesday', 'rc-flight-manager') . '">'
                      . '   <label for="wednesday"> ' . esc_html__('Wednesday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="3" name="thursday" value="' . esc_html__('Thursday', 'rc-flight-manager') . '">'
                      . '   <label for="thursday"> ' . esc_html__('Thursday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="4" name="friday" value="' . esc_html__('Friday', 'rc-flight-manager') . '">'
                      . '   <label for="friday"> ' . esc_html__('Friday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="5" name="saturday" value="' . esc_html__('Saturday', 'rc-flight-manager') . '" checked>'
                      . '   <label for="saturday"> ' . esc_html__('Saturday', 'rc-flight-manager') . '</label><br>'
                      . '   <input type="checkbox" class="weekdayselect" id="6" name="sunday" value="' . esc_html__('Sunday', 'rc-flight-manager') . '" checked>'
                      . '   <label for="sunday"> ' . esc_html__('Sunday', 'rc-flight-manager') . '</label><br>'
					  . '   </p>';
            $content .= '   <p align="center"><button type="button" id="add_date_range_btn_ok" class="modal_ok">'. esc_html__('Ok', 'rc-flight-manager') .'</button>';
			$content .= '   <button type="button" id="add_date_range_btn_abort" class="modal_abort">'. esc_html__('Cancel', 'rc-flight-manager') .'</button></p>';
            $content .= '   </div>';
			$content .= '</div>';
		}

		// Preparing the table
		$table = '';
	    $table .= '<table id="table_rc_flight_manager_schedule">';
	    $table .= '<colgroup>';
	    $table .= '<col>';
	    $table .= '<col span="3">';
	    $table .= '</colgroup>';
	    //$header = '<tr><th><p align="center">' . esc_html__('Date', 'rc-flight-manager') . '</p></th>' .
		//		      '<th><p align="center">' . esc_html__('Assigned Flight-Manager', 'rc-flight-manager') .'</p>' .
		//			  '<th><p align="center"></p></th>';

		$lastMonth = "";
		$today = date_i18n("d.m.Y");
		// Filling table with data
		foreach ( $schedules as $s ) {
			$date = strtotime( $s->date );
			$currentMonth = date_i18n("F Y", $date);
		
			// Create a sub-header row for each month
			$row = "";
			if ($currentMonth != $lastMonth) {
				$row .= '<tr>';
				$row .= '<th class="rcfm-table-headers" colspan="3"><div align="center">' . $currentMonth . '</div>' 
				      . '<div class="rcfm-table-header-date" align="right">' . esc_html__('as of', 'rc-flight-manager') . ' ' . $today . '</div></th>';
				$row .= '</tr>';
				//$row .= $header;
			}
			// append row to table
			$table .= $row;

			// Create a data row for each service
			$row_id = "table_row_schedule_id_" . $s->schedule_id;
			$row = "";
			$now = strtotime(date_i18n("d.m.Y"));
			if ($date == $now) {
				$row .= '<tr id="' . $row_id . '" class="rcfm-service-today">';
			}
			else{
				$row .= '<tr id="' . $row_id . '">';
			}
			
			$row .= $s->getTableData();
			$row .= "</tr>";
			
			// Appending row to table
			$table .= $row;
			$lastMonth = $currentMonth;
		}
		// end table
		$table .= '</table>';
	
		// add table to content
		$content .= $table;

		// End enclosing <div>
		$content .= '</div>';

		// return content
		return $content;
	}


	function button_takeover() {
		//error_log("RC_Flight_Manager_Public :: button_takeover called!");
		//do_action( 'qm/debug', 'botton_takeover() called!' );

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

		if (! is_null($schedule_id)) {
		    // Load Duty from DB
		    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
			
		    // Get current Wordpress User
		    $current_user = wp_get_current_user();

		    // Update Duty with current user
			$s->updateUser($current_user->ID, "No");

			// return new table data
			//echo $s->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $s->getTableData()));
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Schedule ID must not be NULL!', 'rc-flight-manager'),
									'result'  => ''));
		}
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_delete() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Update Duty with current user
		if (current_user_can( 'edit_posts' ) ) {
			$s->delete();
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to delete schedules!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();
		}
	
	    // return new table data
		echo json_encode(array(	'success' => true, 
								'message' => '',
								'result'  => ''));
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_update_comment() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Send HTML for dialog modal if current user can edit_posts
		if (current_user_can( 'edit_posts' ) ) {
			// Defining the update_comment_btn_modal Modal
			$modal = '<div id="update_comment_btn_modal" class="modal">';
			// Modal content
			$modal .= '	<div class="modal-content">';
			$modal .= '	<span class="close">&times;</span>';
			$modal .= '	<p align="center"><label for="addCommentField">' . esc_html__('Enter label', 'rc-flight-manager') . ':</label>'
			          . '   <input type="text" id="addCommentField" name="addCommentField" value="' . $s->comment . '"></p>';
			$modal .= '   <p align="center"><button type="button" id="update_comment_btn_ok" class="modal_ok">' . esc_html__('Save', 'rc-flight-manager') . '</button>';
			$modal .= '   <button type="button" id="update_comment_btn_abort" class="modal_abort">' . esc_html__('Cancel', 'rc-flight-manager') . '</button></p>';
			$modal .= '	</div>';
			$modal .= '</div>';
			//echo $modal;
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $modal));
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to update comments!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();
		}
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}


	function button_handover() {
		//error_log("RC_Flight_Manager_Public :: button_handover called!");
		
		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Send HTML for dialog modal
		// Defining the handover_btn_modal Modal
		$modal = '<div id="handover_btn_modal" class="modal">';
		// Modal content
		$modal .= '   <div class="modal-content">';
		$modal .= '  	 <span class="close">&times;</span>';
		$modal .= '  	 <label for="userSelectionField">' . esc_html__('Member to handover service to', 'rc-flight-manager') . ':</label>';
		$modal .= '      <select id="userSelectionField" name="userSelectionField">';
        $users = get_users();
        foreach ( $users as $u) {
            if (($u->ID != NULL) && ($u->user_id != $current_user->ID)) {
                $name = esc_html( $u->user_firstname ) . " " . esc_html( $u->user_lastname );
                //$date = date_i18n("D j. M", strtotime( $s->date ));
                $modal .= '<option value="' . $u->ID . '">' . $name . '</option>';
            }
        }
		$modal .= '</select>';
		// Disclaimer
        $modal .= '      <p><input type="checkbox" id="handover_disclaimer" class="disclaimer" value="' . $schedule_id . '">';
        $modal .= '      <label for="handover_disclaimer">' . esc_html__('The selected person agreed to take over this duty! ', 'rc-flight-manager') . '</label></p>';
		$modal .= '      <p align="center"><button type="button" id="handover_btn_ok" class="modal_ok" disabled>' . esc_html__('Save', 'rc-flight-manager') . '</button>';
		$modal .= '      <button type="button" id="handover_btn_abort" class="modal_abort">' . esc_html__('Cancel', 'rc-flight-manager') . '</button></p>';
		$modal .= '   </div>';
		$modal .= '</div>';
		//echo $modal;
		echo json_encode(array(	'success' => true, 
								'message' => '',
								'result'  => $modal));
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function handover() {
		//error_log("RC_Flight_Manager_Public :: button_handover called!");

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
		$schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);
		$new_user_id = $this->validate_rcfm_user_id($_POST["new_user"]);

		if (! is_null($schedule_id) and ! is_null($new_user_id)) {
			// Load Duty from DB
			$s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
			
			// Get userdata for $new_user_id
			$new_user_obj = get_userdata($new_user_id);
			
			// Update Duty with current user
			$s->updateUser($new_user_id);
			
			// return new table data
			//echo $s->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $s->getTableData()));
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Schedule or user ID does not exist!', 'rc-flight-manager'),
									'result'  => ''));
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}


	function button_assign() {
		//error_log("RC_Flight_Manager_Public :: button_assign called!");
		
		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
		// Get year of selected duty: Needed to calculate how many services are already assigned to a user
		$display_year = date_i18n("Y", strtotime( $s->date ));
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Send HTML for dialog modal if current user can edit_posts
		if (current_user_can( 'edit_posts' ) ) {
			// Defining the assign_btn_modal Modal
			$modal = '<div id="assign_btn_modal" class="modal">';
			// Modal content
			$modal .= '   <div class="modal-content">';
			$modal .= '  	 <span class="close">&times;</span>';
			$modal .= '  	 <label for="userSelectionField">' . esc_html__('Member to assign to this service', 'rc-flight-manager') . ':</label>';
			$modal .= '      <select id="userSelectionField" name="userSelectionField">';
			$modal .= '      <option value="nobody">' . esc_html__('Nobody', 'rc-flight-manager') . '</option>';
			$users = get_users();
			foreach ( $users as $u) {
				if ($u->ID) {
					$no_of_duties = RC_Flight_Manager_Schedule::get_no_of_duties($u->ID, $display_year);
					$name = " (" . esc_html( $no_of_duties) . ") - " . esc_html( $u->user_firstname ) . " " . esc_html( $u->user_lastname );
					//$date = date_i18n("D j. M", strtotime( $s->date ));
					$modal .= '      <option value="' . $u->ID . '">' . $name . '</option>';
				}
			}
			$modal .= '</select>';
			$modal .= '      <p align="left">' . esc_html__('* Number in parentheses shows how many services are already assigned to this user!', 'rc-flight-manager') . '</p>';
			$modal .= '      <p align="center"><button type="button" id="assign_btn_ok" class="modal_ok">' . esc_html__('Save', 'rc-flight-manager') . '</button>';
			$modal .= '      <button type="button" id="assign_btn_abort" class="modal_abort">' . esc_html__('Cancel', 'rc-flight-manager') . '</button></p>';
			$modal .= '   </div>';
			$modal .= '</div>';
			//echo $modal;
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $modal));
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to assign users!', 'rc-flight-manager'),
									'result'  => ''));
		}
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_swap() {
		//error_log("RC_Flight_Manager_Public :: button_swap called!");
		
		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);

	    // Load Duty from DB
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
		// Get year of selected duty: Because only duties in the same year are valid for swapping!
		$display_year = date_i18n("Y", strtotime( $s->date ));
	
	    // Get current Wordpress User
	    $current_user = wp_get_current_user();

	    // Send HTML for dialog modal
		// Defining the swap_btn_modal Modal
		$modal = '<div id="swap_btn_modal" class="modal">';
		// Modal content
		$modal .= '   <div class="modal-content">';
		$modal .= '  	 <span class="close">&times;</span>';
		$modal .= '  	 <label for="serviceSelectionField">' . esc_html__('Member to swap service with', 'rc-flight-manager') . ':</label>';
		$modal .= '      <select id="serviceSelectionField" name="serviceSelectionField">';
		$schedules = RC_Flight_Manager_Schedule::getServiceList('+', $display_year);
        foreach ( $schedules as $s) {
            if (($s->user_id != NULL) && ($s->user_id != $current_user->ID)) {
                $userObj = get_userdata($s->user_id);
                $name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
                $date = date_i18n("D j. M", strtotime( $s->date ));
                $modal .= '<option value="' . $s->schedule_id . '">' . $name . ' ' . esc_html__('on', 'rc-flight-manager') .' ' . $date . '</option>';
            }
        }
		$modal .= '</select>';
		// Disclaimer
        $modal .= '      <p><input type="checkbox" id="swap_disclaimer" class="disclaimer" value="' . $schedule_id . '">';
        $modal .= '      <label for="swap_disclaimer">' . esc_html__('The selected person and me agreed on swaping our duties! ', 'rc-flight-manager') . '</label></p>';
		$modal .= '      <p align="center"><button type="button" id="swap_btn_ok" class="modal_ok" disabled>' . esc_html__('Save', 'rc-flight-manager') . '</button>';
		$modal .= '      <button type="button" id="swap_btn_abort" class="modal_abort">' . esc_html__('Cancel', 'rc-flight-manager') . '</button></p>';
		$modal .= '   </div>';
		$modal .= '</div>';
		echo json_encode(array(	'success' => true, 
								'message' => '',
								'result'  => $modal));
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function swap() {
		//error_log("RC_Flight_Manager_Public :: button_swap called!");
		
		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);
	    $swap_schedule_id = $this->validate_rcfm_schedule_id($_POST["swap_schedule_id"]);
	    
	    // Load Duty from DB
		// Current user who is giving away a service
	    $s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
		// Service which will be swapped
		$s2 = RC_Flight_Manager_Schedule::getServiceById($swap_schedule_id);
	
	    // Update Duty with new user
		$temp = $s->user_id;
	    $s->updateUser($s2->user_id);
		$s2->updateUser($temp);
	
	    // return new table data
		echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $s->getTableData() . ":SEP:" . $s2->getTableData()));
		//echo $s->getTableData();
		//echo ":SEP:";
		//echo $s2->getTableData();

	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_book_flightslot() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $reservation_id = $this->validate_rcfm_reservation_id($_POST["reservation_id"]);

		if (! is_null($reservation_id)) {
			// Read options
			$options = get_option( 'rcfm_settings');
			
			// Get Flightslot
			$slot = RC_Flight_Manager_Flightslot::get_flightslot($reservation_id);
			$arrlength = count($slot->bookings);
			if ($arrlength >= $options['reservation_red_limit_field'])  {
				// Return existing table data
				//echo $slot->getTableData();
				echo json_encode(array(	'success' => false, 
										'message' => esc_html__('Max number of reservations reached!', 'rc-flight-manager'),
										'result'  => $slot->getTableData()));
			}

		    // Get current Wordpress User
		    $current_user = wp_get_current_user();

			// Book slot
		    $slot->book($current_user->ID);

		    // return new table data
			//echo $slot->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $slot->getTableData()));

		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Reservation id does not exist!', 'rc-flight-manager'),
									'result'  => ''));
		}
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function button_cancel_flightslot() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $reservation_id = $this->validate_rcfm_reservation_id($_POST["reservation_id"]);

		if (! is_null($reservation_id)) {
			// Get Flightslot
			$slot = RC_Flight_Manager_Flightslot::get_flightslot($reservation_id);

	    	// Get current Wordpress User
	    	$current_user = wp_get_current_user();

			// Book slot
	    	$slot->cancel($current_user->ID);

	    	// return new table data
			//echo $slot->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $slot->getTableData()));
			
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Reservation id does not exist!', 'rc-flight-manager'),
									'result'  => ''));
		}
	
	    wp_die(); // this is required to terminate immediately and return a proper response
	}

	function add_schedule_date() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $date = sanitize_text_field($_POST["date"]);

		if (current_user_can( 'edit_posts' ) ) {
			try {
				RC_Flight_Manager_Schedule::addServiceDate($date);
			}
			catch(Exception $e) {
				//echo $e->getMessage();
				echo json_encode(array(	'success' => false, 
									'message' => $e->getMessage(),
									'result'  => ''));
				wp_die(); // this is required to terminate immediately and return a proper response
			}
			//echo "OK";
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => ''));
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to add schedule dates!', 'rc-flight-manager'),
									'result'  => ''));
		}
	    // Return
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	function add_schedule_date_range() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $fromdate = sanitize_text_field($_POST["fromdate"]);
		$todate = sanitize_text_field($_POST["todate"]);
		$weekdays = $this->validate_rcfm_weekday_array($_POST["weekdays"]);

		if (current_user_can( 'edit_posts' ) ) {
			$results = RC_Flight_Manager_Schedule::addServiceDateRange($fromdate, $todate, $weekdays);
			if (empty($results)) {
				echo json_encode(array(	'success' => true, 
										'message' => '',
										'result'  => ''));
			}
			else {
				//foreach($results as $r){
				//	echo "$r\n";
				//}
				echo json_encode(array(	'success' => false, 
										'message' => $results,
										'result'  => ''));
			}
		}
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to add schedule date ranges!', 'rc-flight-manager'),
									'result'  => ''));
		}
	    // Return
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	function update_comment() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);
		$comment = sanitize_text_field($_POST["comment"]);

		if (current_user_can( 'edit_posts' ) ) {
			$s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
			$s->updateComment($comment);
			//echo $s->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $s->getTableData()));
		}	
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to update comments!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();
		}
		
	    // Return
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	function assign_user() {

		// Security nonce check
		if ( ! check_ajax_referer( 'rcfm-security-nonce', 'security_nonce', false ) ) {	
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('Security check failed (rcfm-security-nonce)!', 'rc-flight-manager'),
									'result'  => ''));
			wp_die();	  
		}

		// Read ID from HTTP request
	    $schedule_id = $this->validate_rcfm_schedule_id($_POST["schedule_id"]);
		$user_id = $this->validate_rcfm_user_id($_POST["user_id"]);

		if (current_user_can( 'edit_posts' ) ) {
			$s = RC_Flight_Manager_Schedule::getServiceById($schedule_id);
			if ($user_id == "nobody") {
				$s->updateUser(NULL, "Yes");
			}
			else {
				$s->updateUser($user_id, "Yes");
			}
			//echo $s->getTableData();
			echo json_encode(array(	'success' => true, 
									'message' => '',
									'result'  => $s->getTableData()));
		}	
		else {
			echo json_encode(array(	'success' => false, 
									'message' => esc_html__('You are not allowed to assign users!', 'rc-flight-manager'),
									'result'  => ''));
		}
		
	    // Return
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/**
	 * Custom validation functions
	 *
	 */
	function validate_rcfm_schedule_id( $id ) {
		/**
		 * Validate a RCFM schedule_id
		 */
		// Make sure that $id is numeric
		$safe_id = intval($id);
		//error_log("validate_rcfm_schedule_id: id=${id}, safe_id=${safe_id}");

		if (! $safe_id) {
			error_log("validate_rcfm_schedule_id: ${id} is not numeric!");
			$safe_id = NULL;
		}
		
		return $safe_id;
	}

	function validate_rcfm_reservation_id( $id ) {
		/**
		 * Validate a RCFM reservation
		 */
		// Make sure that $id is numeric
		$safe_id = intval($id);
		//error_log("validate_rcfm_reservation_id: id=${id}, safe_id=${safe_id}");

		if (! $safe_id) {
			error_log("validate_rcfm_reservation_id: ${id} is not numeric!");
			$safe_id = NULL;
		}
		
		return $safe_id;
	}

	function validate_rcfm_user_id( $user_id ) {
		/**
		 * Validate a RCFM user_id
		 */
		// Make sure that $user_id is numeric
		$safe_user_id = intval($user_id);
		//error_log("validate_rcfm_user_id: user_id=${user_id}, safe_user_id=${safe_user_id}");
		
		if (! $safe_user_id) {
			error_log("validate_rcfm_user_id: ${user_id} is not numeric!");
			$safe_user_id = NULL;
		}
		else {
			// Make sure that wordpress user with this ID exists
			$user = get_userdata( $safe_user_id );
			if ( $user === false ) { //user id does not exist
				error_log("validate_rcfm_user_id: ${safe_user_id} does not exit in WP!");
				$safe_user_id = NULL;
			}
		}
		return $safe_user_id;
	}

	function validate_rcfm_weekday_array( $days ) {
		/** 
		 * Validate a weekday array
		 */
		// Make sure that $days is an array
		if (! is_array($days))
		{
			return false;
		}
		// Make sure that each element only contains true or false
		#foreach($days as $d){
		foreach(array_keys($days) as $key) {
			if ($days[$key] == 'false') { $days[$key] = false; }
			elseif ($days[$key] == 'true') { $days[$key] = true; }
			else {
				return false;
			}
		}
		return $days;
	}
}

