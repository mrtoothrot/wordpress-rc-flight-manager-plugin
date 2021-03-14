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
			'section_one',
			'Section One',
			'RC_Flight_Manager_Admin::rcfm_section_one_text',
			'rc_flight_manager'
		  );
		
		  add_settings_field(
			'some_text_field',
			'Some Text Field',
			'RC_Flight_Manager_Admin::rcfm_render_some_text_field',
			'rc_flight_manager',
			'section_one'
		  );
		
		  add_settings_field(
			'another_number_field',
			'Another Number Field',
			'RC_Flight_Manager_Admin::rcfm_render_another_number_field',
			'rc_flight_manager',
			'section_one'
		  );
	}

	public function validate_rcfm_settings( $input ) {
		//$output['some_text_field']      = sanitize_text_field( $input['some_text_field'] );
		//$output['another_number_field'] = absint( $input['another_number_field'] );
		// ...
		//return $output;
		return TRUE;
	}

	public static function rcfm_section_one_text() {
		echo '<p>This is the first (and only) section in my settings.</p>';
	}
	  
	public static function rcfm_render_some_text_field() {
		$options = get_option( 'rcfm_settings' );
		printf(
		  '<input type="text" name="%s" value="%s" />',
		  esc_attr( 'rcfm_settings[some_text_field]' ),
		  esc_attr( $options['some_text_field'] )
		);
	}
	  
	public static function rcfm_render_another_number_field() {
		$options = get_option( 'rcfm_settings' );
		printf(
		  '<input type="number" name="%s" value="%s" />',
		  esc_attr( 'rcfm_settings[another_number_field]' ),
		  esc_attr( $options['another_number_field'] )
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
			value="<?php esc_attr_e( 'Save' ); ?>"
		  />
		</form>
	  <?php
	}


	public function add_rcfm_settings_page() {

		/**
		 */

		add_options_page(
			'RC Flight Manager Settings',
			'RC Flight Manager',
			'manage_options',
			'rc-flight-manager',
			'RC_Flight_Manager_Admin::render_rcfm_settings_page'
		  );

	}

}