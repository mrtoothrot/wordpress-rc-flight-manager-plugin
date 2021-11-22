<?php

/**
 * The file that defines the rc-flight-manager-schedule class 
 *
 * A class definition that defining the "schedule" objects
 *
 * @link       https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
 *
 * @package    RC_Flight_Manager_Schedule
 * @subpackage RC_Flight_Manager_Schedule/includes
 */

/**
 * The RC_Flight_Manager_Schedule class.
 *
 * This is used to ...
 *
 * @package    RC_Flight_Manager_Schedule
 * @subpackage RC_Flight_Manager_Schedule/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */

class RC_Flight_Manager_Schedule {
	// Properties
    public $schedule_id;
    public $date;
    public $user_id;
	public $comment;

	//public $test = RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME;

	// Methods
    function __construct($schedule_id, $date, $user_id, $comment, $change_id) {
        $this->schedule_id = $schedule_id;
        $this->date = $date;
        $this->user_id = $user_id;
        $this->comment = $comment;
        $this->change_id = $change_id;
	}

    function logToDatabase($old_user_id, $by_admin = 'No' ) {
        global $wpdb;
        // Update logging table entry
        $logging_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME;
        $wpdb->insert( 
            $logging_table_name, 
            array( 
                //'timestamp'          => MySQL CURRENT_TIMESTAMP is used
                'by_admin'      => $by_admin,
                'schedule_id'   => $this->schedule_id,
                'old_user_id'   => $old_user_id,
                'new_user_id'   => $this->user_id,
                'mail_sent'     => "No"
            ), 
            array( 
                //'%s',
                '%s', 
                '%d', 
                '%d',
                '%d',
                '%s'
            ) 
        );
        return($wpdb->insert_id);
    }

    function saveToDatabase() {
        global $wpdb;
        // Update schedule table entry
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        $wpdb->replace( 
            $schedule_table_name, 
            array( 
                'schedule_id'       => $this->schedule_id,
                'date'              => $this->date, 
                'user_id'           => $this->user_id,
                'comment'           => $this->comment,
                'change_id'         => $this->change_id
            ), 
            array( 
                '%d',
                '%s', 
                '%d', 
                '%s',
                '%d'
            ) 
        );
    }

    function delete() {
        global $wpdb;
        // Add schedule table entry for given date
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;

        $wpdb->delete(
            $schedule_table_name, 
            array(
                'schedule_id' => $this->schedule_id // Where clause
            ),
            array(
                '%d' // format of value being targeted for deletion
            )
        );
    }

	// Public static methods
    public static function addServiceDate($date) {
        global $wpdb;
        // Add schedule table entry for given date
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;

        $checkIfExists = $wpdb->get_var("SELECT schedule_id FROM $schedule_table_name WHERE date = '$date'");
        if ($checkIfExists == NULL) {

            $wpdb->insert( 
                $schedule_table_name, 
                array( 
                    'date'              => $date, 
                    'user_id'           => NULL,
                    'comment'           => "",
                    'change_id'         => NULL
                ), 
                array( 
                    '%s', 
                    '%d', 
                    '%s',
                    '%d'
                )  
            );
            return($wpdb->insert_id);
        }
        else {
            throw new Exception($date . esc_html__(' already exists!', 'rc-flight-manager'));
            return(NULL);
        }
    }


    public static function addServiceDateRange($fromdate, $todate, $weekdays) {
        $results = array();
        $interval = DateInterval::createFromDateString('1 day');
        try {
            #$begin = date_i18n("Y-m-d", strtotime($fromdate));
            $begin = new DateTime($fromdate);
            $end = new DateTime($todate);
            // Add one day to $end, to make end date inclusive
            $end->add($interval);
            #$end = date_i18n("Y-m-d", strtotime($todate));
        }
        catch(Exception $e) {
            $results[] = esc_html__('Invalid time range!', 'rc-flight-manager');
            return($results);
        }
        
        $period = new DatePeriod($begin, $interval, $end);
    
        
        foreach ($period as $date) {
            $wd = $date->format("N") - 1;
            if ($weekdays[$wd] === true) {
                #$results[] = "HIT";
                try {
                    self::addServiceDate($date->format("Y-m-d"));
                }
                catch (Exception $e) {
                    $results[] = $e->getMessage();
                }
            }
        } 
        return($results);
    }

	public static function getServiceList($months = NULL, $selected_year = NULL) {
        // Prepare table name
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        
        // Get start of current year
        if (is_null($selected_year)) {
            $year = date_i18n("Y");
        }
        else {
            $year = $selected_year;
        }
        $start_year = date_i18n("Y-m-d", strtotime($year . "-01-01"));
        $end_year = date_i18n("Y-m-d", strtotime($year . "-12-31"));
        $today = date_i18n("Y-m-d");

        // Checking input parameters
        if (is_null($months)) {
            //do_action( "qm/debug", "months is NULL: " . $months);
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE date >= '$start_year' and date < '$end_year' ORDER BY date", OBJECT );
        }
        elseif (is_string($months)){
            //do_action( "qm/debug", "months is string: " . $months);
            if ($months == "+") { # Return only services today and in the future
                $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE date >= '$start_year' and date >= '$today' and date < '$end_year' ORDER BY date", OBJECT );    
            }
            else {
                return FALSE;
            }
        }
        elseif (is_numeric($months)) {
            //do_action( "qm/debug", "months is numeric: " . $months);
            // Calculating current date
            //do_action( "qm/debug", "year is: " . $year);
            if ($year == date_i18n("Y")) {  # Now we calculate a plan for the current year
                //do_action( "qm/debug", "Calculating for current year!");
                $this_month = date_i18n("Y-m");
                $start_this_month = date_i18n("Y-m-d", strtotime($this_month . "-01"));
                $end_month = date_i18n("Y-m-d", strtotime("$this_month + $months months"));
            } 
            else # Now we calculate plan for past or future year
            {
                //do_action( "qm/debug", "Calculating for future/past year!");
                $start_this_month = date_i18n("Y-m-d", strtotime($year . "-01-01"));
                $end_month = date_i18n("Y-m-d", strtotime("$start_this_month + $months months"));
            }
            //do_action( "qm/debug", "start_this_month is: " . $start_this_month);
            //do_action( "qm/debug", "end_month is: " . $end_month);
            // Getting the list
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE date >= '$start_this_month' and date < '$end_month' ORDER BY date", OBJECT );
        }
        else {
            return FALSE;
        }

        // Creating the schedule array
        $schedules = array();
        foreach ( $list as $x ) {
            // Create new schedule object
            $s = new RC_Flight_Manager_Schedule($x->schedule_id, $x->date, $x->user_id, $x->comment, $x->change_id);
            // Check if user_id exists in wordpress
            if (! get_userdata($s->user_id)) {
                $s->user_id = NULL;
                $s->saveToDatabase();
            }
            // append new Duty to list
            array_push($schedules, $s);
        };
        return $schedules;
    }


    public static function getServiceById($schedule_id) {
        // Return NULL if no schedule_id was passed
        if (is_null($schedule_id)) {
            return NULL;
        }
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        $result = $wpdb->get_row( "SELECT * FROM $schedule_table_name WHERE schedule_id=$schedule_id", OBJECT );
        if ( !is_null($result) ) {
            $s = new RC_Flight_Manager_Schedule($result->schedule_id, $result->date, $result->user_id, $result->comment, $result->change_id);
            return $s;
        }
        else {
            return NULL;
        }
    }

    public static function getServiceByDate($date) {
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        $result = $wpdb->get_row( "SELECT * FROM $schedule_table_name WHERE date='$date'", OBJECT );
        if ( !is_null($result) ) {
            $s = new RC_Flight_Manager_Schedule($result->schedule_id, $result->date, $result->user_id, $result->comment, $result->change_id);
            return $s;
        }
        else {
            return NULL;
        }
    }

    public static function get_no_of_duties($user = NULL, $selected_year = NULL) {
        if ( is_null($user) ) {
            throw new Exception(esc_html__('function get_no_of_duties: No user specified!', 'rc-flight-manager'));
        }
        if ( is_null($selected_year) ) {
            throw new Exception(esc_html__('function get_no_of_duties: No year selected!', 'rc-flight-manager'));
        }
        
        $start_year = date_i18n("Y-m-d", strtotime($selected_year . "-01-01"));
        $end_year = date_i18n("Y-m-d", strtotime($selected_year . "-12-31"));
        
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;	
        //SELECT COUNT(*) FROM `wp_rcfm_schedule` WHERE user_id='42' and date >= '$start_year' and date < '$end_year'
        //SELECT COUNT(*) FROM `wp_rcfm_schedule` WHERE user_id='42' and date >= '2022-01-01' and date <= '2022-12-31';
        $result = $wpdb->get_var( "SELECT COUNT(*) FROM $schedule_table_name WHERE user_id='$user' and date >= '$start_year' and date <= '$end_year'");
        //$result = $wpdb->get_var( "SELECT COUNT(*) FROM $schedule_table_name WHERE user_id='$user'" );
        if ( is_null($result) ) {
            return 0;
        }
        else {
            return $result;
        }
    }

    // Public methods
    public function updateUser( $new_id, $by_admin = 'No' ) {
        //do_action( 'qm/debug', "by_admin = $by_admin" );
        $old_user_id = $this->user_id;
        $this->user_id = $new_id;
        $this->change_id = $this->logToDatabase($old_user_id, $by_admin);
        $this->saveToDatabase();
    }

    public function updateComment( $new_comment ) {
        //do_action( 'qm/debug', "by_admin = $by_admin" );
        $this->comment = $new_comment;
        $this->saveToDatabase();
    }

    public function getTableData() {
		// Preparation
        $formated_date = date_i18n("D j. M", strtotime( $this->date ));
        $userObj = get_userdata($this->user_id);
        $current_user = wp_get_current_user();
        $name = "";
		if ( $userObj ) {
			$name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
		}

        // Constructing the rows
        // 1. Date field
        $row = "";
		if ($this->comment) {
            $row .= '<td><p align="center"><b>' . $formated_date . '</b></p><p class="rcfm-event-label">' . $this->comment . '</p></td>';
        }
        else
        {
            $row .= '<td><p align="center"><b>' . $formated_date . '</b></p></td>';
        }

        // 2. Flight Manager field
		if ($this->user_id == $current_user->ID) {
            // Own name highlighted in red
            $row .= '<td class="rcfm-highlighted-user">' 
                  . '<p>' . $name . '</p>'
                  . '</td>';
        }
        else
        {
            // All other names in default color
            $row .= '<td>'
                  . '<p>' . $name . '</p>'
                  . '</td>';
        }
        
        // 3. Button field
        $row .= '<td>'
              .  $this->getChangeDropdownMenu()
              . '</td>';

		return $row;
	}

    function getChangeDropdownMenu() {
        $current_user = wp_get_current_user();
        
        $buttons = array();
        if ( $this->user_id == NULL ) { 
            $id = "takeover_btn_" . $this->schedule_id;
            $class = "rcfm_takeover_btn";
            $button_text = esc_html__('Assign to me', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');
        }
        elseif ( $this->user_id == $current_user->ID ) {
            // Swap button
            $id = "swap_btn_" . $this->schedule_id;
            $class = "rcfm_swap_btn";
            $button_text = esc_html__('Swap duty', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');

            // Handover
            $id = "handover_btn_" . $this->schedule_id;
            $class = "rcfm_handover_btn";
            $button_text = esc_html__('Handover duty', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');
        }

        if (current_user_can( 'edit_posts' ) ) {
            // Assign button
            $id = "assign_btn_" . $this->schedule_id;
            $class = "rcfm_assign_btn";
            $button_text = esc_html__('Assign duty', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');
            
            // Add comment button
            $id = "update_comment_btn_" . $this->schedule_id;
            $class = "rcfm_update_comment_btn";
            $button_text = esc_html__('Update label', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');

            // Delete button
            $id = "delete_btn_" . $this->schedule_id;
            $class = "rcfm_delete_btn";
            $button_text = esc_html__('Delete Date', 'rc-flight-manager');
            array_push($buttons, '<a href="javascript:void(0)" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</a>');
        }
        
        if (count($buttons) == 0) { // Don't show a dropdown if user is not allowed to do anything
            return('');
        }
        //elseif (count($buttons) == 1) { 
            // TODO: IMPLEMENT LATER: Directly display respective button if only one is possible
            //$button = '<button id="' . 'takeover_btn_' . $this->schedule_id . '" class="dropbtn" data-schedule_id="' . $this->schedule_id . '">' . esc_html__('Test', //'rc-flight-manager') . '</button>';
            //return($button);
        //}
        else {
            $id = $this->schedule_id;
            $button_id = "button_change_id_" . $id;
            $dropdown_id = "dropdown_id_" . $id;
            $menu = '';
            $menu .= '<div class="dropdown">';
            $menu .= '  <button id="' . $button_id . '" class="dropbtn" data-schedule_id="' . $id . '">' . esc_html__('Change', 'rc-flight-manager') . '</button>'
                   . '  <div id="' . $dropdown_id . '" class="dropdown-content">';
            for($x = 0; $x < count($buttons); $x++) {
                $menu .= $buttons[$x];
            }
            $menu .= '  </div>'
                   . '</div>';
            return($menu);
        }
    }
}
