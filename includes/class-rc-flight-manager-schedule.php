<?php

/**
 * The file that defines the rc-flight-manager-schedule class 
 *
 * A class definition that defining the "schedule" objects
 *
 * @link       https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
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
                //'date'          => date(),   MySQL CURRENT_TIMESTAMP is used
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

	// Public static methods
	public static function getServiceList($months = NULL) {
        // Calculating current date
        $today = date_i18n("Y-m-d");
        $this_month = date_i18n("Y-m");
        $start_this_month = $this_month . "-01";
        $end_month = date_i18n("Y-m-d", strtotime("$this_month + $months months"));

        global $wpdb;
        $schedule_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_SCHEDULE_TABLE_NAME;		
        if ($months == NULL){
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE date >= '$today' ORDER BY date", OBJECT );
        }
        else {
            $list = $wpdb->get_results( "SELECT * FROM $schedule_table_name WHERE date >= '$today' and date < '$end_month' ORDER BY date", OBJECT );
        }
        $schedules = array();
        foreach ( $list as $x ) {
            $s = new RC_Flight_Manager_Schedule($x->schedule_id, $x->date, $x->user_id, $x->comment, $x->change_id);
            // append new Duty to list
            array_push($schedules, $s);
        };
        return $schedules;
    }


    public static function getServiceById($schedule_id) {
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

    // Public methods
    public function updateUser( $new_id, $by_admin = 'No' ) {
        //do_action( 'qm/debug', "by_admin = $by_admin" );
        $old_user_id = $this->user_id;
        $this->user_id = $new_id;
        $this->change_id = $this->logToDatabase($old_user_id, $by_admin);
        $this->saveToDatabase();
    }

    
    public function getTakeoverButtonHtml() {
        $id = "button_takeover_schedule_id_" . $this->schedule_id;
        $class = "button_takeover_schedule";
        $button_text = __('Assign to me', 'rc-flight-manager');
        // Button
        $html = '<button type="button" id="' . $id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</button>';
        return($html);
    }

    public function getAssignButtonHtml() {
        $current_user = wp_get_current_user();
        $html = "";

        $id = $this->schedule_id;
        $button_id = "button_assign_schedule_id_" . $this->schedule_id;
        $div_id = "user_div_id_" . $this->schedule_id;
        $selection_id = "user_selection_id_" . $this->schedule_id;
        $ok_button_id = "assign_ok_button_id_" . $this->schedule_id;
        $abort_button_id = "assign_abort_button_id_" . $this->schedule_id;
        $class = "button_assign_schedule";
        $button_text = __('Assign', 'rc-flight-manager');
        $divclass = "div_assign_schedule";

        // Assign Button
        $html .= '<button type="button" id="' . $button_id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</button>';
        $html .= '<div id="' . $div_id . '" class ="' . $divclass . ' hidden"><p>'
               . __('Member to assign to this service:', 'rc-flight-manager')
               . '<br><select id="' . $selection_id. '">';
        $users = get_users();
        foreach ( $users as $u) {
            if ($u->ID != 0) {
                $name = esc_html( $u->user_firstname ) . " " . esc_html( $u->user_lastname );
                //$date = date_i18n("D j. M", strtotime( $s->date ));
                $html .= "<option value=\"$u->ID\">$name</option>";
            }
        }
        $html .= '</select></p>';
        // Ok / Abort buttons
        $html .= '<button type="button" id="' . $ok_button_id . '" class="assign_ok_button ok_button" value="' . $id . '">' . __('Ok', 'rc-flight-manager') . '</button>&nbsp;&nbsp;';
        $html .= '<button type="button" id="' . $abort_button_id .'" class="abort_button">' . __('Cancel', 'rc-flight-manager') . '</button>';
        $html .= '</div>';

        return($html);
    }

    # TODO:
    #
    # Continue with i18n here!
    #
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
        $button_text = __('Swap', 'rc-flight-manager');
        $divclass = "div_swap_schedule";

        // Swap Button
        $html .= '<button type="button" id="' . $button_id . '" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</button>';
        $html .= '<div id="' . $div_id . '" class ="' . $divclass . ' hidden"><p>'
               . __('Member to swap service with:', 'rc-flight-manager')
               . '<br><select id="' . $selection_id . '">';
        $schedules = RC_Flight_Manager_Schedule::getServiceList();
        foreach ( $schedules as $s) {
            if (($s->user_id != 0) && ($s->user_id != $current_user->ID)) {
                $userObj = get_userdata($s->user_id);
                $name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
                $date = date_i18n("D j. M", strtotime( $s->date ));
                $html .= '<option value="' . $s->schedule_id . '">' . $name . ' ' . __('on', 'rc-flight-manager') .' ' . $date . '</option>';
            }
        }
        $html .= "</select></p>";
        // Disclaimer
        $html .= '<input type="checkbox" id="' . $disclaimer_id. '" class="swap_disclaimer" value="' . $id . '">';
        $html .= '<label for="' . $disclaimer_id . '">' . __('The selected person and me agreed on swaping our duties! ', 'rc-flight-manager') . '</label><br>';
        // Ok / Abort buttons
        $html .= '<br><button type="button" id="' . $ok_button_id .'" class="swap_ok_button ok_button" value="'. $id . '" disabled>' . __('Ok', 'rc-flight-manager') . '</button>&nbsp;&nbsp;';
        $html .= '<button type="button" id="' . $abort_button_id . '" class="abort_button">' . __('Cancel', 'rc-flight-manager') . '</button>';
        $html .= '</div>';

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
        $button_text = __('Handover', 'rc-flight-manager');
        $class = "button_handover_schedule";
        $divclass = "div_handover_schedule";

        // Handover Button
        $html .= '<button type="button" id="' . $button_id .'" class="' . $class . '" data-schedule_id="' . $this->schedule_id . '">' . $button_text . '</button>';
        $html .= '<div id="' . $div_id . '" class ="' . $divclass . ' hidden"><p>'
             . __('Member to handover service to:', 'rc-flight-manager')    
             . '<br><select id="' . $selection_id . '">';
        $users = get_users();
        foreach ( $users as $u) {
            if (($u->ID != 0) && ($u->user_id != $current_user->ID)) {
                $name = esc_html( $u->user_firstname ) . " " . esc_html( $u->user_lastname );
                //$date = date_i18n("D j. M", strtotime( $s->date ));
                $html .= "<option value=\"$u->ID\">$name</option>";
            }
        }
        $html .= '</select></p>';
        // Disclaimer
        $html .= '<input type="checkbox" id="' . $disclaimer_id. '" class="handover_disclaimer" value="' . $id . '">';
        $html .= '<label for="' . $disclaimer_id . '">' . __('The selected person agreed to take over this duty! ', 'rc-flight-manager') . '</label><br>';
        // Ok / Abort buttons
        $html .= '<br><button type="button" id="' . $ok_button_id . '" class="handover_ok_button ok_button" value="' . $id . '" disabled>' . __('Ok', 'rc-flight-manager') . '</button>&nbsp;&nbsp;';
        $html .= '<button type="button" id="' . $abort_button_id . '" class="abort_button">' . __('Cancel', 'rc-flight-manager') . '</button>';
        $html .= '</div>';

        return($html);
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

        // Constructing the row
        $row = "";
		if ($this->comment) {
            $row .= '<td height="80px"><p align="center"><b>' . $formated_date . '</b></p><p align="center" style="background-color: #FF0000; color: #ffffff">' . $this->comment . '</p></td>';
        }
        else
        {
            $row .= '<td height="80px"><p align="center"><b>' . $formated_date . '</b></p></td>';
        }
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
            // If no user is assigned to the duty and current user has 'edit_posts' capability (=> Contributor Role), display the assign duty button
            if (current_user_can( 'edit_posts' ) ) {
                $row .= "<td align='center' style='min-width:300px;text-align:center'>". $this->getAssignButtonHtml() . "<br>" . $this->getTakeoverButtonHtml() . "</td>";
            }
            // if no user is assigned to the duty, display the take over button!
            else {
                $row .= "<td align='center' style='min-width:300px;text-align:center'>" . $this->getTakeoverButtonHtml() . "</td>";
            }
        }
        elseif ( $this->user_id == $current_user->ID ) {
            // If current user is assigned to the duty and current user has 'edit_posts' capability (=> Contributor Role), display the assign duty button
            if (current_user_can( 'edit_posts' ) ) {
                $row .= "<td align='center' style='min-width:300px;text-align:center'>". $this->getAssignButtonHtml() . "<br>" . $this->getSwapButtonHtml() . "<br>" . $this->getHandoverButtonHtml() . "</td>";
            }
            // Else, only show the swap button
            else {
                $row .= "<td align='center' style='min-width:300px;text-align:center'>". $this->getSwapButtonHtml() . "<br>" . $this->getHandoverButtonHtml() . "</td>";
            }
        }
        elseif (current_user_can( 'edit_posts' ) ) {
            # If current user has 'edit_posts' capability (=> Contributor Role), display the assign duty button
            $row .= "<td align='center' style='min-width:300px;text-align:center'>". $this->getAssignButtonHtml() . "</td>";
        }
        else {
            $row .= "<td style='min-width:300px;text-align:center'></td>";
        }
		return $row;
	}
}
