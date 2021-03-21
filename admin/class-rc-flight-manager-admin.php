<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/admin
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */
class RC_Flight_Manager_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rc_flight_manager    The ID of this plugin.
	 */
	private $rc_flight_manager;

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
	 * @param      string    $rc_flight_manager       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rc_flight_manager, $version ) {

		$this->rc_flight_manager = $rc_flight_manager;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->rc_flight_manager, plugin_dir_url( __FILE__ ) . 'css/rc-flight-manager-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->rc_flight_manager, plugin_dir_url( __FILE__ ) . 'js/rc-flight-manager-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the settings page 'rcfm_settings_page' for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function register_rcfm_settings() {
		register_setting(
			'rcfm_settings',
			'rcfm_settings',
			'validate_rcfm_settings'
		  );
		
		  add_settings_section(
			'Notifications',
			'Notifications',
			'RC_Flight_Manager_Admin::rcfm_section_notification',
			'rc_flight_manager'
		  );
		
		  add_settings_field(
			'enable_email_notification_field',
			'Switch on E-Mail notifications',
			'RC_Flight_Manager_Admin::rcfm_render_enable_email_notification_field',
			'rc_flight_manager',
			'Notifications'
		  );

		  add_settings_field(
			'notify_flightmanagers_email_field',
			'Flight Managers should be notified by E-Mail',
			'RC_Flight_Manager_Admin::rcfm_render_notify_flightmanagers_email_field',
			'rc_flight_manager',
			'Notifications'
		  );
		
		  add_settings_field(
			'notify_additional_email_field',
			'Additional E-Mail address to send notifications to:',
			'RC_Flight_Manager_Admin::rcfm_render_notify_additional_email_field',
			'rc_flight_manager',
			'Notifications'
		  );

		  add_settings_section(
			'Notification E-Mail Template',
			'Notification E-Mail Template',
			'RC_Flight_Manager_Admin::rcfm_section_notification_email',
			'rc_flight_manager'
		  );

		  add_settings_field(
			'notification_email_subject_field',
			'Subject for the notification E-Mail:',
			'RC_Flight_Manager_Admin::rcfm_render_notification_email_subject_field',
			'rc_flight_manager',
			'Notification E-Mail Template'
		  );
		  
		  add_settings_field(
			'notification_email_body_field',
			'Notification E-Mail template:',
			'RC_Flight_Manager_Admin::rcfm_render_notification_email_body_field',
			'rc_flight_manager',
			'Notification E-Mail Template'
		  );
		  
		  add_settings_section(
			'Flight Slot Reservation Limits',
			'Flight Slot Reservation Limits',
			'RC_Flight_Manager_Admin::rcfm_section_reservation_limits',
			'rc_flight_manager'
		  );

		  add_settings_field(
			'reservation_red_limit_field',
			'Max reservation limit (no more reservations possible):',
			'RC_Flight_Manager_Admin::rcfm_render_reservation_red_limit_field',
			'rc_flight_manager',
			'Flight Slot Reservation Limits'
		  );

		  add_settings_field(
			'reservation_yellow_limit_field',
			'Reservation limit on which flight slot turns yellow:',
			'RC_Flight_Manager_Admin::rcfm_render_reservation_yellow_limit_field',
			'rc_flight_manager',
			'Flight Slot Reservation Limits'
		  );

		// Configure Plugin default options
		$email_template = <<<EOT
<html>
<body>
<p>Hi [flightmanager-name]!</p>
<p>You are assigned as flight manager on duty for [flightmanager-duty-date]! This is a reminder! Don't forget your service!</p>
<p></p>
<p>This notification E-Mail was sent by RC Flight Manager</p>
</body>
</html>
EOT;
		$defaults = array(
			'notify_additional_email_field' => '',
			'notification_email_subject_field' => 'Please mind your flight manager service on [flightmanager-duty-date]!',
			'notification_email_body_field' => $email_template,
			'reservation_red_limit_field' => 5,
			'reservation_yellow_limit_field' => 3,
		);
		
		// Initialize Plugin options
		$options = get_option( 'rcfm_settings');
		if (!$options) {
			add_option('rcfm_settings', $defaults);
			$options = get_option( 'rcfm_settings');
		}
	}

	public function validate_rcfm_settings( $input ) {
		//$output['some_text_field']      = sanitize_text_field( $input['some_text_field'] );
		//$output['another_number_field'] = absint( $input['another_number_field'] );
		// ...
		//return $output;
		return TRUE;
	}

	public static function rcfm_section_notification() {
		echo '<p>Configure E-Mail notification for scheduled flight managers.</p>';
	}
	public static function rcfm_section_reservation_limits() {
		echo '<p>Configure limits for flight slot reservations.</p>';
	}
	
	public static function rcfm_section_notification_email() {
		echo '<p>You can use the following placeholders in the subject line and body of the notification E-Mail:</p>';
		echo '<p><b>[flightmanager-duty-date]</b> = date on which the notified user is assigned flight manager</p>';
		echo '<p><b>[flightmanager-name]</b> = Name of the user who is assigned flight manager</p>';
	}
	  
	public static function rcfm_render_enable_email_notification_field() {
		$options = get_option( 'rcfm_settings' );
		?>
		<input type='checkbox' name='rcfm_settings[enable_email_notification_field]' <?php checked( $options['enable_email_notification_field'], 1 ); ?> value='1'>
		<?php
	}
	
	public static function rcfm_render_notify_flightmanagers_email_field() {
		$options = get_option( 'rcfm_settings' );
		?>
		<input type='checkbox' name='rcfm_settings[notify_flightmanagers_email_field]' <?php checked( $options['notify_flightmanagers_email_field'], 1 ); ?> value='1'>
		<?php
	}
	  
	public static function rcfm_render_notify_additional_email_field() {
		$options = get_option( 'rcfm_settings');
		printf(
		  '<input type="email" name="%s" value="%s" size="40"/>',
		  esc_attr( 'rcfm_settings[notify_additional_email_field]' ),
		  esc_attr( $options['notify_additional_email_field'] )
		);
	}

	public static function rcfm_render_notification_email_subject_field() {
		$options = get_option( 'rcfm_settings');
		printf(
		  '<input type="text" name="%s" value="%s" size="100"/>',
		  esc_attr( 'rcfm_settings[notification_email_subject_field]' ),
		  esc_attr( $options['notification_email_subject_field'] )
		);
	}

	public static function rcfm_render_notification_email_body_field() {
		$options = get_option( 'rcfm_settings');
		?><textarea cols='100' rows='10' name='rcfm_settings[notification_email_body_field]'><?php echo isset( $options['notification_email_body_field'] ) ?  $options['notification_email_body_field'] : false; ?></textarea><?php

	}

	public static function rcfm_render_reservation_red_limit_field() {
		$options = get_option( 'rcfm_settings' );
		printf(
		  '<input type="number" name="%s" value="%s" />',
		  esc_attr( 'rcfm_settings[reservation_red_limit_field]' ),
		  esc_attr( $options['reservation_red_limit_field'] )
		);
	}

	public static function rcfm_render_reservation_yellow_limit_field() {
		$options = get_option( 'rcfm_settings' );
		printf(
		  '<input type="number" name="%s" value="%s" />',
		  esc_attr( 'rcfm_settings[reservation_yellow_limit_field]' ),
		  esc_attr( $options['reservation_yellow_limit_field'] )
		);
	}

	public static function render_rcfm_settings_page() {
		?>
		<h2>RC Flight Manager Settings</h2>
		<form action="options.php" method="post">
		  <?php 
		  settings_fields( 'rcfm_settings' );
		  do_settings_sections( 'rc_flight_manager' );
		  ?>
		  <input
			type="submit"
			name="submit"
			class="button button-primary"
			value="<?php esc_attr_e( 'Save Changes' ); ?>"
		  />
		</form>
	  <?php
	}


	public function add_rcfm_settings_page() {
		/**
		 *  Adding the RC Flight Manager settings page to WP Dashboard->Settings
		 */
		if ( current_user_can( 'manage_options' ) ) {
			add_options_page(
				'RC Flight Manager Settings',
				'RC Flight Manager',
				'manage_options',
				'rc-flight-manager',
				'RC_Flight_Manager_Admin::render_rcfm_settings_page'
			);
		}
	}
}