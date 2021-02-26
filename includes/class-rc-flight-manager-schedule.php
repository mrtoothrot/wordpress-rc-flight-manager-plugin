<?php

/**
 * The file that defines the rc-flight-manager-schedule class
 *
 * A class definition that defining the "schedule" objects
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager_Schedule
 * @subpackage RC_Flight_Manager_Schedule/includes
 */

/**
 * The RC_Flight_Manager_Schedule class.
 *
 * This is used to ...
 *
 * @since      1.0.0
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
    function __construct($schedule_id, $date, $user_id, $comment) {
        $this->schedule_id = $schedule_id;
        $this->date = $date;
        $this->user_id = $user_id;
        $this->comment = $comment;
	}

    function logToDatabase($old_user_id) {
        global $wpdb;
        // Update logging table entry
        $logging_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_LOGGING_TABLE_NAME;
        $wpdb->insert( 
            $logging_table_name, 
            array( 
                //'date'          => date(),   MySQL CURRENT_TIMESTAMP is used
                'by_admin'      => "No",
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
                'comment'           => $this->comment
            ), 
            array( 
                '%d',
                '%s', 
                '%d', 
                '%s'
            ) 
        );
    }

	// Public static methods
	public static function getServiceList($userid = NULL) {
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        if ($userid == NULL){
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name", OBJECT );
        }
        else {
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE userid=$userid", OBJECT );
        }
        $schedules = array();
        foreach ( $list as $x ) {
            $s = new RC_Flight_Manager_Schedule($x->schedule_id, $x->date, $x->user_id, $x->comment);
            // append new Duty to list
            array_push($schedules, $s);
        };
        return $schedules;
    }


    public static function getServiceById($schedule_id) {
        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        $result = $wpdb->get_row( "SELECT * FROM $schedule_table_name WHERE schedule_id=$schedule_id", OBJECT );
        $s = new RC_Flight_Manager_Schedule($result->schedule_id, $result->date, $result->userid, $result->comment);
        return $s;
    }

    // Public methods
    public function updateUser($new_id) {
        $old_user_id = $this->user_id;
        $this->user_id = $new_id;
        $this->logToDatabase($old_user_id);
        $this->saveToDatabase();
    }

    

    public function getTakeoverButtonHtml() {
        $id = "button_takeover_schedule_id_" . $this->schedule_id;
        $class = "button_takeover_schedule";
        // Button
        //$html .= "<button type=\"button\" id=\"$id\" class=\"$class\" name=\"id\" value=\"$this->schedule_id\">Dienst übernehmen</button>";
        $html = "<button type=\"button\" id=\"$id\" class=\"$class\" data-schedule_id=\"$this->schedule_id\">Dienst übernehmen</button>";
        return($html);
    }

    public function getSwapButtonHtml() {
        $id = "button_swap_schedule_id_" . $this->schedule_id;
        $class = "button_swap_schedule";
        // Button
        //$html .= "<button type=\"button\" id=\"$id\" class=\"$class\" name=\"id\" value=\"$this->schedule_id\">Dienst übernehmen</button>";
        $html = "<button type=\"button\" id=\"$id\" class=\"$class\" data-schedule_id=\"$this->schedule_id\">Tauschen</button>";
        return($html);
    }

    public function getTableData() {
		// Preparation
        $formated_date = date_i18n("D j. M", strtotime( $this->date ));
        $row_id = "table_row_schedule_id_" . $this->schedule_id;
        $userObj = get_userdata($this->user_id);
        $current_user = wp_get_current_user();
        $name = "";
		if ( $userObj ) {
			$name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
		}

        // Constructing the row
        $row = "";
		$row .= '<td><p align="center"><b>' . $formated_date . '</b></p><p align="center" style="background-color: #FF0000; color: #ffffff">' . $this->comment . '</p></td>';
		if ($this->user_id == $current_user->ID) {
            // Own name highlighted in red
            $row .= '<td style="color:#ff0000">' . $name . '</td>';
        }
        else
        {
            // All other names in default color
            $row .= '<td>' . $name . '</td>';
        }

        if ( $this->user_id == 0 ) { 
            // if no user is entered, offer to take over this service!
            $row .= "<td>" . $this->getTakeoverButtonHtml() . "</td>";
        } 
        elseif ( $this->user_id == $current_user->ID ) {
            $row .= "<td>". $this->getSwapButtonHtml() ."</td>";
        }
        else {
            $row .= "<td></td>";
        }
		return $row;
	}
}
