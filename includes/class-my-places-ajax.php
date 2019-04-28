<?php

/**
 * Define the asyncronous functionality
 *
 * Loads and defines the asyncronous functionality for this plugin.
 *
 * @link       https://whatsthepoint.se
 * @since      1.0.0
 *
 * @package    My_Places
 * @subpackage My_Places/includes
 */

/**
 * Define the asyncronous functionality
 *
 * Loads and defines the asyncronous functionality for this plugin.
 *
 * @since      1.0.0
 * @package    My_Places
 * @subpackage My_Places/includes
 * @author     Johan Nordström <johan@digitalvillage.se>
 */
class My_Places_Ajax {

	/**
	 * Get places (HTML output).
	 *
	 * @since	1.0.0
	 */
	public static function get_places() {
		$placetypes = isset($_POST['placetypes']) ? $_POST['placetypes'] : false;

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
	 * Get places (JSON output).
	 *
	 * @since	1.0.0
	 */
	public static function get_places_json() {
		$placetypes = isset($_POST['placetypes']) ? $_POST['placetypes'] : false;

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
				$place = [
					'id' => intval(get_the_ID()),
					'latitude' => floatval(get_field('lat')),
					'longitude' => floatval(get_field('lng')),
					'name' => null,
					'address' => null,
					'city' => null,
					'categories' => [],
				];

				if ($name = get_the_title()) {
					$place['name']  = $name;
				}
				if ($address = get_field('address')) {
					$place['address']  = $address;
				}
				if ($city = get_field('city')) {
					$place['city']  = $city;
				}

				// add placetype-terms this post has
				$placetype_terms = get_the_terms(get_the_ID(), 'my_placetype');
				foreach ($placetype_terms as $term) {
					array_push($place['categories'], $term->name);
				}

				array_push($data, $place);
			}
			wp_send_json_success($data);
		} else {
			wp_send_json_error(['message' => 'No places matching your selected criteria found.']);
		}
	}

}
