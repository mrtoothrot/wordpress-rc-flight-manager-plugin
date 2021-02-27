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
        $current_user = wp_get_current_user();
        $html = "";

        $id = $this->schedule_id;
        $button_id = "button_swap_schedule_id_" . $this->schedule_id;
        $disclaimer_id = "swap_disclaimer_id_" . $this->schedule_id;
        $div_id = "service_div_id_" . $this->schedule_id;
        $selection_id = "service_selection_id_" . $this->schedule_id;
        $ok_button_id = "swap_ok_button_id_" . $this->schedule_id;
        $abort_button_id = "swap_abort_button_id_" . $this->schedule_id;
        $class = "button_swap_schedule";
        $divclass = "div_swap_schedule";

        // Swap Button
        $html .= "<button type=\"button\" id=\"$button_id\" class=\"$class\" data-schedule_id=\"$this->schedule_id\">Mit jemandem tauschen</button>";
        $html .= "<div id=\"$div_id\" class =\"$divclass hidden\"><p><select id=\"$selection_id\">";
        $schedules = RC_Flight_Manager_Schedule::getServiceList();
        foreach ( $schedules as $s) {
            if (($s->user_id != 0) && ($s->user_id != $current_user->ID)) {
                $userObj = get_userdata($s->user_id);
                $name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
                $date = date_i18n("D j. M", strtotime( $s->date ));
                $html .= "<option value=\"$s->schedule_id\">Ich tausche mit $name am $date.</option>";
            }
        }
        $html .= "</select></p>";
        // Disclaimer
        $html .= "<input type=\"checkbox\" id=\"$disclaimer_id\" class=\"swap_disclaimer\" value=\"$id\">";
        $html .= "<label for=\"$disclaimer_id\">Ich habe das mit der ausgewählten Person abgesprochen!</label><br>";
        // Ok / Abort buttons
        $html .= "<button type=\"button\" id=\"$ok_button_id\" class=\"swap_ok_button ok_button\" value=\"$id\" disabled>OK</button>";
        $html .= "<button type=\"button\" id=\"$abort_button_id\" class=\"abort_button\">Abbruch</button>";
        $html .= "</div>";

        return($html);
    }

    public function getHandoverButtonHtml() {
        $current_user = wp_get_current_user();
        $html = "";

        $id = $this->schedule_id;
        $button_id = "button_handover_schedule_id_" . $this->schedule_id;
        $disclaimer_id = "handover_disclaimer_id_" . $this->schedule_id;
        $div_id = "user_div_id_" . $this->schedule_id;
        $selection_id = "user_selection_id_" . $this->schedule_id;
        $ok_button_id = "handover_ok_button_id_" . $this->schedule_id;
        $abort_button_id = "handover_abort_button_id_" . $this->schedule_id;
        $class = "button_handover_schedule";
        $divclass = "div_handover_schedule";

        // Handover Button
        $html .= "<button type=\"button\" id=\"$button_id\" class=\"$class\" data-schedule_id=\"$this->schedule_id\">Dienst an jemanden übergeben</button>";
        $html .= "<div id=\"$div_id\" class =\"$divclass hidden\"><p><select id=\"$selection_id\">";
        $users = get_users();
        foreach ( $users as $u) {
            if (($u->ID != 0) && ($u->user_id != $current_user->ID)) {
                $name = esc_html( $u->user_firstname ) . " " . esc_html( $u->user_lastname );
                //$date = date_i18n("D j. M", strtotime( $s->date ));
                $html .= "<option value=\"$u->ID\">$name</option>";
            }
        }
        $html .= "</select> übernimmt diesen Dienst für mich.</p>";
        // Disclaimer
        $html .= "<input type=\"checkbox\" id=\"$disclaimer_id\" class=\"handover_disclaimer\" value=\"$id\">";
        $html .= "<label for=\"$disclaimer_id\">Ich habe das mit der ausgewählten Person abgesprochen!</label><br>";
        // Ok / Abort buttons
        $html .= "<button type=\"button\" id=\"$ok_button_id\" class=\"handover_ok_button ok_button\" value=\"$id\" disabled>OK</button>";
        $html .= "<button type=\"button\" id=\"$abort_button_id\" class=\"abort_button\">Abbruch</button>";
        $html .= "</div>";

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
            $row .= "<td>". $this->getSwapButtonHtml() . "<br>" . $this->getHandoverButtonHtml() . "</td>";
        }
        else {
            $row .= "<td></td>";
        }

		return $row;
	}
}
