<?php

/**
 * The file that defines the rc-flight-manager-flightslot class 
 *
 * A class definition that defining the "flightslot" objects
 *
 * @link       https://github.com/mrtoothrot/wordpress-rc-flight-manager-plugin
 *
 * @package    RC_Flight_Manager_Flightslot
 * @subpackage RC_Flight_Manager_Flightslot/includes
 */

/**
 * The RC_Flight_Manager_Flightslot class.
 *
 * This is used to ...
 *
 * @package    RC_Flight_Manager_Flightslot
 * @subpackage RC_Flight_Manager_Flightslot/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */

class RC_Flight_Manager_Flightslot {
	// Properties
    public $reservation_id;
    public $date;
    public $time;

	// Methods
    function __construct($reservation_id, $date, $time) {
        $this->reservation_id = $reservation_id;
        $this->date = $date;
        $this->time = $time;
        $this->bookings = $this->get_bookings($reservation_id);
        //do_action( "qm/debug", "Bookings in constructor: " . $this->bookings[1]);
	}

	// Public static methods
    public static function init_flightslots( $date ) {
        global $wpdb;
        // Insert flightslots for each hour on $date
        $flightslot_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_TABLE_NAME;
        
		// Check if there are already entries for today
		$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $flightslot_table_name WHERE date = '${date}'");
		//do_action( "qm/debug", "Rows for ${date}: ${rowcount}" );

		// Insert one entry for each our between 8am and 20pm
		if ($rowcount == 0) {
			for ($x = 8; $x <= 20; $x++) {
				$hour = "${x}:00";
				$wpdb->insert( 
        		    $flightslot_table_name, 
        		    array( 

        		        'date'      => $date,
        		        'time'   	=> $hour
        		    ), 
        		    array( 
        		        '%s', 
        		        '%s'
        		    ) 
        		);
			}
		}
    }

    public static function get_flightslots( $date ) {
        global $wpdb;
        // Insert flightslots for each hour on $date
        $flightslot_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_TABLE_NAME;
        
		// Get flightslots starting from $date
		$list = $wpdb->get_results( "SELECT * FROM $flightslot_table_name WHERE date >= '$date'", OBJECT );
		//do_action( "qm/debug", "Rows for ${date}: ${rowcount}" );

        $slots = array();
        foreach ( $list as $x ) {
            $s = new RC_Flight_Manager_Flightslot($x->reservation_id, $x->date, $x->time);
            // append new slot to list
            array_push($slots, $s);
        };
        return($slots);
    }

    public static function get_flightslot($reservation_id) {
        global $wpdb;
        $flightslot_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_TABLE_NAME;		
        $result = $wpdb->get_row( "SELECT * FROM $flightslot_table_name WHERE reservation_id=$reservation_id", OBJECT );
        if ( !is_null($result) ) {
            $s = new RC_Flight_Manager_Flightslot($result->reservation_id, $result->date, $result->time);
            return $s;
        }
        else {
            return NULL;
        }
        return $s;
    }

    public static function get_bookings($reservation_id) {
        global $wpdb;
        $flightslot_reservations_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_RESERVATIONS_TABLE_NAME;		
        $list = $wpdb->get_results( "SELECT user_id FROM $flightslot_reservations_table_name WHERE reservation_id = '$reservation_id'", OBJECT );
        $ids = array();
        foreach ( $list as $x ) {
            array_push($ids, $x->user_id);
        }
        return($ids);
    }

    // Public methods
    public function book( $user_id ) {
        global $wpdb;
        // Insert flightslots for each hour on $date
        $flightslot_reservations_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_RESERVATIONS_TABLE_NAME;
        
		// Check if there are already entries for today
		//$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $flightslot_reservations_table_name WHERE reservation_id = '$this->reservation_id'");
        //if ($rowcount >= 3){
        //    return;
        //}

		$wpdb->insert( 
            $flightslot_reservations_table_name, 
            array( 
                'reservation_id'    => $this->reservation_id,
                'user_id'   	    => $user_id
            ), 
            array( 
                '%d', 
                '%d'
            ) 
        );

        $this->bookings[] = $user_id;
    }

    public function cancel( $user_id ) {
        global $wpdb;
        // Insert flightslots for each hour on $date
        $flightslot_reservations_table_name = $wpdb->prefix . RC_FLIGHT_MANAGER_FLIGHTSLOT_RESERVATIONS_TABLE_NAME;
        
		$wpdb->delete( 
            $flightslot_reservations_table_name, 
            array( 
                'reservation_id'    => $this->reservation_id,
                'user_id'   	    => $user_id
            ), 
            array( 
                '%d', 
                '%d'
            ) 
        );

        // Removing the bookings array entry for user_id
        $i = array_search($user_id, $this->bookings);
        unset($this->bookings[$i]);
        // Re-indexing the bookings array
        $this->bookings = array_values($this->bookings);
    }

    public function getBookButtonHtml() {
        $id = "button_book_slot_id_" . $this->reservation_id;
        $class = "button_book_flightslot";
        $button_text = esc_html__('Book slot', 'rc-flight-manager');
        // Button
        $html = '<button type="button" id="' . $id . '" class="' . $class . '" data-reservation_id="' . $this->reservation_id . '">' . $button_text . '</button>';
        return($html);
    }

    public function getCancellationButtonHtml() {
        $id = "button_cancel_slot_id_" . $this->reservation_id;
        $class = "button_cancel_flightslot";
        $button_text = esc_html__('Cancel booking', 'rc-flight-manager');
        // Button
        $html = '<button type="button" id="' . $id . '" class="' . $class . '" data-reservation_id="' . $this->reservation_id . '">'. $button_text . '</button>';
        return($html);
    }


    public function getTableData() {
		// Load options
        $options = get_option( 'rcfm_settings');
        
        // Preparation
        $no_of_reservations = count($this->bookings);
        if ($no_of_reservations == 0) {
            $class = "";
        }
        elseif ($no_of_reservations < $options['reservation_yellow_limit_field']) { # green
            $class = "rcfm-limit-color-green";
        }
        elseif ($no_of_reservations < $options['reservation_red_limit_field']) { # yellow
            $class = "rcfm-limit-color-yellow";
        }
        elseif ($no_of_reservations >= $options['reservation_red_limit_field']) { # red
            $class = "rcfm-limit-color-red";
        }

        $time = date_i18n("H:i", strtotime($this->time));
        //$userObj = get_userdata($this->user_id);
        $current_user = wp_get_current_user();
        $name = "";
		//if ( $userObj ) {
		//	$name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
		//}

        // Constructing the row
        $row = "";
        // Time collumn
		$row .= "<td><p align='center'>" . $time . "</p></td>";
		
        // Bookings collumn
        $bookings = "";        
        $no_of_reservations = count($this->bookings);
        //do_action( "qm/debug", "array length: ${no_of_reservations}" );
        for($x = 0; $x < $no_of_reservations; $x++) {
            //do_action( "qm/debug", "array length: " . $this->bookings[$x] );
            $userObj = get_userdata($this->bookings[$x]);
            if ($userObj) {
                if ($this->bookings[$x] == $current_user->ID){
                    $bookings .= "<p align='left' class='rcfm-highlighted-user'>" . esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname ) . "</p>";
                }
                else {
                    $bookings .= "<p align='left'>" . esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname ) . "</p>";
                }
            }
        }
        $row .= "<td class='${class}'>" . $bookings . "</td>";
        
        // Button collumn
        $no_of_reservations = count($this->bookings);
        if (( $no_of_reservations < $options['reservation_red_limit_field']) && (! in_array( $current_user->ID, $this->bookings))) {
            $row .= "<td>" . $this->getBookButtonHtml() . "</td>";
        }
        elseif (in_array( $current_user->ID, $this->bookings)) {
            $row .= "<td>" . $this->getCancellationButtonHtml() . "</td>";
        }
        else {
            $row .= "<td></td>";
        }

		return $row;
	}
}
