<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    RC_Flight_Manager
 * @subpackage RC_Flight_Manager/includes
 * @author     Mr Toothrot <mrtoothrot@gmail.com>
 */
class RC_Flight_Manager {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      RC_Flight_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'RC_FLIGHT_MANAGER_VERSION' ) ) {
			$this->version = RC_FLIGHT_MANAGER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'rc-flight-manager';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - RC_Flight_Manager_Loader. Orchestrates the hooks of the plugin.
	 * - RC_Flight_Manager_i18n. Defines internationalization functionality.
	 * - RC_Flight_Manager_Admin. Defines all hooks for the admin area.
	 * - RC_Flight_Manager_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rc-flight-manager-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rc-flight-manager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rc-flight-manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rc-flight-manager-public.php';

		/**
		 * The class responsible for handling of schedule objects including persistence in DB
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rc-flight-manager-schedule.php';

		/**
		 * The class responsible for handling of flightslot objects including persistence in DB
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rc-flight-manager-flightslot.php';

		$this->loader = new RC_Flight_Manager_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the RC_Flight_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new RC_Flight_Manager_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new RC_Flight_Manager_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new RC_Flight_Manager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		// Add hook for cron??
		//$this->loader->add_action( 'rcfm_scheduled_notifications', $plugin_public, 'rcfm_send_notifications' );
		$this->loader->add_action( 'rcfm_send_daily_flightmanager_notification', $plugin_public, 'rcfm_send_daily_flightmanager_notification_email' );

		// Add hook for AJAX calls
		$this->loader->add_action( 'wp_ajax_nopriv_button_takeover', $plugin_public, 'button_takeover' ); // for ALL users
		$this->loader->add_action( 'wp_ajax_button_takeover', $plugin_public, 'button_takeover' );        // for admins only: Call the same function
		$this->loader->add_action( 'wp_ajax_nopriv_button_handover', $plugin_public, 'button_handover' ); // for ALL users
		$this->loader->add_action( 'wp_ajax_button_handover', $plugin_public, 'button_handover' );        // for admins only: Call the same function
		$this->loader->add_action( 'wp_ajax_nopriv_button_assign', $plugin_public, 'button_assign' );     // for ALL users
		$this->loader->add_action( 'wp_ajax_button_assign', $plugin_public, 'button_assign' );            // for admins only: Call the same function
		$this->loader->add_action( 'wp_ajax_nopriv_button_swap', $plugin_public, 'button_swap' );         // for ALL users
		$this->loader->add_action( 'wp_ajax_button_swap', $plugin_public, 'button_swap' );                // for admins only: Call the same function
		$this->loader->add_action( 'wp_ajax_nopriv_button_book_flightslot', $plugin_public, 'button_book_flightslot' );         // for ALL users
		$this->loader->add_action( 'wp_ajax_button_book_flightslot', $plugin_public, 'button_book_flightslot' );                // for admins only: Call the same function
		$this->loader->add_action( 'wp_ajax_nopriv_button_cancel_flightslot', $plugin_public, 'button_cancel_flightslot' );         // for ALL users
		$this->loader->add_action( 'wp_ajax_button_cancel_flightslot', $plugin_public, 'button_cancel_flightslot' );                // for admins only: Call the same function
		
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    RC_Flight_Manager_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
