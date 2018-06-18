(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	var mymap,
		mymarkers;

	function initMap() {
		mymap = new google.maps.Map(document.getElementById('my-places-map'), {
			center: {
				lat: Number(my_places_obj.google_maps_latitude),
				lng: Number(my_places_obj.google_maps_longitude),
			},
			zoom: Number(my_places_obj.google_maps_zoom),
		});

		addMapMarkers();
	}

	function addMapMarkers() {
		mymarkers = [];

		// send request to WordPress and fetch available places
		$.post(
			my_places_obj.ajax_url,
			{
				action: 'get_places',
			}
		)
		.done(function(response) {
			console.log("Great success get_places!", response);
			if (!response.success) {
				alert("Sorry, failed to get map markers. The error message was: " + response.data.message);
				console.error(error);

				return;
			}

			if (response.data && response.data.length > 0) {
				// loop over response.data array and add a marker for each object in the array
				$.each(response.data, function (index, marker) {
					var mapmarker = addMapMarker(marker.latitude, marker.longitude, marker.content);

					mymarkers.push(mapmarker);
				});
			}

			console.log("mymarkers is", mymarkers);

		})
		.fail(function(error) {
			alert("Sorry, failed to get map markers.");
			console.error(error);
		});
	}

	function addMapMarker(latitude, longitude, infoWindowContent) {
		var marker = new google.maps.Marker({
			position: {
				lat: latitude,
				lng: longitude,
			},
			map: mymap,
		});

		// add InfoWindow to first marker
		var infoWindow = new google.maps.InfoWindow({
			content: infoWindowContent,
		});

		// add first infoWindow to first marker
		marker.addListener('click', function () {
			closeAllInfoWindows();
			infoWindow.open(mymap, marker);
		});

		return {
			marker: marker,
			infoWindow: infoWindow,
		};
	}

	function closeAllInfoWindows() {
		// loop over mymarkers
		// for each mymarker, call infoWindow.close()
		$.each(mymarkers, function(index, mymarker) {
			mymarker.infoWindow.close();
		});
	}

	// initialize map
	initMap();

})( jQuery );
