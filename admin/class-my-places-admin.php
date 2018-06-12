<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://whatsthepoint.se
 * @since      1.0.0
 *
 * @package    My_Places
 * @subpackage My_Places/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    My_Places
 * @subpackage My_Places/admin
 * @author     Johan Nordström <johan@digitalvillage.se>
 */
class My_Places_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in My_Places_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Places_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-places-admin.css', array(), $this->version, 'all' );

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
		 * defined in My_Places_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Places_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-places-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_options_page() {
		add_submenu_page(
			'tools.php',						// menu slug to add submenu to
			'My Places Options',				// option page title
			'My Places',						// menu title
			'manage_options',					// capability required for this menu option to be accessible
			'my-places',						// slug for our options page
			['My_Places_Admin', 'options_page']	// function that outputs our options
		);
	}

	public function options_page() {
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
			<div class="wrap">
				<h1><?= esc_html(get_admin_page_title()); ?></h1>

				<form action="options.php" method="post">
					<?php
					// output security fields for the registered setting "my-places_options"
					settings_fields('my-places_options');

					// output setting sections and their fields
					// (sections are registered for "my-places", each field is registered to a specific section)
					do_settings_sections('my-places');

					// output save settings button
					submit_button('Save Settings');
					?>
				</form>
			</div>
		<?php
	}

	public function admin_init() {
		add_settings_section("my-places_maps_settings", "Google Maps Settings", null, "my-places");

		add_settings_field(
			"my-places_google_maps_api_key",					// option slug
			"Google Maps API Key",								// label
			['My_Places_Admin', 'option_google_maps_api_key'],	// method to call for displaying option field
			"my-places",										// slug to options page
			"my-places_maps_settings"							// settings section slug
		);
		register_setting("my-places_options", "my-places_google_maps_api_key");
	}

	public function option_google_maps_api_key() {
		?>
			<input type="text" name="my-places_google_maps_api_key" id="my-places_google_maps_api_key" value="<?php echo get_option('my-places_google_maps_api_key'); ?>" />
		<?php
	}

}
