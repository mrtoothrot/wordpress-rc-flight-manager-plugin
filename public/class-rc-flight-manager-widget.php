<?php

/**
 * The RC_Flight_Manager_Widget class.
 *
 * This is used to ...
 *
 * @since      1.0.0
 * @package    RC_Flight_Manager_Widget
 * @subpackage RC_Flight_Manager_Widget/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */

class RC_Flight_Manager_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
        // widget ID
        'RC_Flight_Manager_Widget',
        // widget name to be displayed in Dashboard Widget UI
        __('RC Flight Manager Widget', 'rc-flight-manager'),
        // widget description
        array ( 'description' => __( 'Displays the current flight manager on duty in the sidebar', 'rc-flight-manager' ), )
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        // Get current date
        $today = date_i18n("Y-m-d");
        $todays_service = RC_Flight_Manager_Schedule::getServiceByDate($today);

        if (! is_null($todays_service)){
            // Get name of user who's on duty today
            $userObj = get_userdata($todays_service->user_id);
            $name = "";
            if ($userObj) {
                $name = esc_html( $userObj->user_firstname ) . " " . esc_html( $userObj->user_lastname );
            }
            else {
                $name = __('No flight manager assigned!', 'rc-flight-manager');
            }
        }
        else {
            $name = __('No flight manager service today!', 'rc-flight-manager');
        }
        echo "<p style='color:#5388b4'><b>$name</b></p>"; // TODO: Move formating to CSS
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'rc-flight-manager' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'rc-flight-manager' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }    
}

add_action( 'widgets_init', 'rcfm_register_widget' );
 
/**
 * Register the new widget.
 *
 * @see 'widgets_init'
 */
function rcfm_register_widget() {
    register_widget( 'RC_Flight_Manager_Widget' );
}
