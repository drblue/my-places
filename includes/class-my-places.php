<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://whatsthepoint.se
 * @since      1.0.0
 *
 * @package    My_Places
 * @subpackage My_Places/includes
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
 * @package    My_Places
 * @subpackage My_Places/includes
 * @author     Johan Nordström <johan@digitalvillage.se>
 */
class My_Places {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      My_Places_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'my-places';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();
		$this->register_custom_post_types();
		$this->register_custom_taxonomies();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - My_Places_Loader. Orchestrates the hooks of the plugin.
	 * - My_Places_i18n. Defines internationalization functionality.
	 * - My_Places_Admin. Defines all hooks for the admin area.
	 * - My_Places_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-places-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-places-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-my-places-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-my-places-public.php';

		$this->loader = new My_Places_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the My_Places_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new My_Places_i18n();

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

		$plugin_admin = new My_Places_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// add options page for this plugin
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );

		// add ajax action for getting places
		$this->loader->add_action( 'wp_ajax_get_places', $this, 'ajax_get_places' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new My_Places_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add ajax action for getting places
		$this->loader->add_action( 'wp_ajax_nopriv_get_places', $this, 'ajax_get_places' );
	}

	/**
	 * Register all of the shortcodes related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		/*
		$plugin_public = new My_Places_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		*/

		/**
		 * @todo move this logic to a separate class
		 */
		if (!shortcode_exists('my_places_map')) {
			add_shortcode('my_places_map', ['My_Places', 'shortcode_my_places_map']);
		}

		/**
		 * @todo move this logic to a separate class
		 */
		if (!shortcode_exists('my_places_form')) {
			add_shortcode('my_places_form', ['My_Places', 'shortcode_my_places_form']);
		}
	}

	/**
	 * @todo move this logic to a separate class
	 */
	public static function shortcode_my_places_map() {
		$output = "";
		$output .= '<div id="my-places-map-wrapper">';
		$output .= '  <div id="my-placetypes">';
		$output .= '    <ul>';

		// loop over all terms in taxonomy my_placetype and echo out a checkbox for each term
		$placetypes = get_terms(['taxonomy' => 'my_placetype']);
		foreach($placetypes as $placetype) {
			$output .= '<li><input type="checkbox" id="placetype_' . $placetype->term_id . '" data-id="' . $placetype->term_id . '" checked="checked"> <label for="placetype_' . $placetype->term_id . '">' . $placetype->name . '</label></li>';
		}

		$output .= '    </ul>';
		//$output .= '    <button id="my-placetypes-filter">Filter</button>';
		$output .= '  </div>';
		$output .= '  <div id="my-places-map"><i>Loading map...</i></div>';
		$output .= '</>';

		return $output;
	}

	/**
	 * @todo move this logic to a separate class
	 */
	public static function shortcode_my_places_form() {
		return "I am very friendly form! Please fill me out! Me lonely.";
	}

	/**
	 * @todo move this logic to a separate class
	 */
	public static function ajax_get_places() {
		$placetypes = $_POST['placetypes'];

		$query_parameters = [
			'post_type' => 'my_place',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		];

		if ($placetypes != false) {
			$query_parameters['tax_query'] = [
				[
					'taxonomy' => 'my_placetype',
					'field' => 'ID',
					'terms' => $placetypes,
				]
			];
		}

		$places = new WP_Query($query_parameters);

		if ($places->have_posts()) {
			$data = [];
			while ($places->have_posts()) {
				$places->the_post();

				$content = "";
				if (get_the_title()) {
					$content .= "<p><b>" . get_the_title() . "</b></p>";
				}
				if (get_field('address') && get_field('city')) {
					$content .= "<p>" . get_field('address') . ", " . get_field('city') . "</p>";
				}

				// add placetype-terms this post has
				$placetypes = [];
				$placetype_terms = get_the_terms(get_the_ID(), 'my_placetype');
				foreach ($placetype_terms as $term) {
					array_push($placetypes, $term->name);
				}
				if (count($placetypes) > 0) {
					$content .= "<p><i>" . implode(", ", $placetypes) . "</i></p>";
				}

				array_push($data, [
					'latitude' => floatval(get_field('lat')),
					'longitude' => floatval(get_field('lng')),
					'content' => $content,
				]);
			}
			wp_send_json_success($data);
		} else {
			wp_send_json_error(['message' => 'No places matching your selected criteria found.']);
		}
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
	 * @return    My_Places_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Register Custom Post Types.
	 *
	 * @todo move this to it's own class
	 *
	 * @return void
	 */
	public function register_custom_post_types() {
		add_action( 'init', function() {
			/**
			 * Post Type: Places.
			 */

			$labels = array(
				"name" => __( "Places", "hestia" ),
				"singular_name" => __( "Place", "hestia" ),
			);

			$args = array(
				"label" => __( "Places", "hestia" ),
				"labels" => $labels,
				"description" => "",
				"public" => true,
				"publicly_queryable" => true,
				"show_ui" => true,
				"show_in_rest" => false,
				"rest_base" => "",
				"has_archive" => false,
				"show_in_menu" => true,
				"show_in_nav_menus" => false,
				"exclude_from_search" => true,
				"capability_type" => "post",
				"map_meta_cap" => true,
				"hierarchical" => false,
				"rewrite" => array( "slug" => "my_place", "with_front" => true ),
				"query_var" => true,
				"menu_icon" => "dashicons-location",
				"supports" => array( "title", "editor" ),
			);

			register_post_type( "my_place", $args );
		});

	}

	/**
	 * Register Custom Taxonomies.
	 *
	 * @todo move this to it's own class
	 *
	 * @return void
	 */
	public function register_custom_taxonomies() {
		add_action( 'init', function() {
			/**
			 * Taxonomy: Place Types.
			 */

			$labels = array(
				"name" => __( "Place Types", "hestia" ),
				"singular_name" => __( "Place Type", "hestia" ),
			);

			$args = array(
				"label" => __( "Place Types", "hestia" ),
				"labels" => $labels,
				"public" => true,
				"hierarchical" => true,
				"label" => "Place Types",
				"show_ui" => true,
				"show_in_menu" => true,
				"show_in_nav_menus" => false,
				"query_var" => true,
				"rewrite" => array( 'slug' => 'my_placetype', 'with_front' => true, ),
				"show_admin_column" => false,
				"show_in_rest" => false,
				"rest_base" => "my_placetype",
				"show_in_quick_edit" => false,
			);
			register_taxonomy( "my_placetype", array( "my_place" ), $args );
		});
	}
}
