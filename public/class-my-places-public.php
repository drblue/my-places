<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://whatsthepoint.se
 * @since      1.0.0
 *
 * @package    My_Places
 * @subpackage My_Places/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    My_Places
 * @subpackage My_Places/public
 * @author     Johan Nordström <johan@digitalvillage.se>
 */
class My_Places_Public {

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
	 * The Google Maps API key for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $google_maps_api_key    The Google Maps API key for this plugin.
	 */
	private $google_maps_api_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->google_maps_api_key = get_option('my-places_google_maps_api_key');

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-places-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		// enqueue google maps javascript api library
		wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->google_maps_api_key, [], true);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-places-public.js', array( 'jquery', 'google-maps' ), $this->version, true );

		wp_localize_script($this->plugin_name, 'my_places_obj', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'google_maps_latitude' => get_option('my-places_google_maps_latitude'),
			'google_maps_longitude' => get_option('my-places_google_maps_longitude'),
			'google_maps_zoom' => get_option('my-places_google_maps_zoom'),
		]);

	}

}
