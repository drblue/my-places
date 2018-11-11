(function( $ ) {
	'use strict';

	var mymap,
		mymarkers = [],
		watchID;

	function initMap() {
		mymap = new google.maps.Map(document.getElementById('my-places-map'), {
			center: {
				lat: Number(my_places_obj.google_maps_latitude),
				lng: Number(my_places_obj.google_maps_longitude),
			},
			zoom: Number(my_places_obj.google_maps_zoom),
		});

		initGeolocation();
		addMapMarkers();
	}

	function initGeolocation() {
		if ("geolocation" in navigator) {
			/* geolocation is available */
			navigator.geolocation.getCurrentPosition(setCurrentPosition);
			watchID = navigator.geolocation.watchPosition(setCurrentPosition);
		} else {
			/* geolocation IS NOT available */
		}
	}

	function setCurrentPosition(position) {
		mymap.setCenter({
			lat: position.coords.latitude,
			lng: position.coords.longitude,
		});
	}

	function addMapMarkers(placetypes = false) {
		// check if we have any markers already on the map
		if (mymarkers.length > 0) {
			$.each(mymarkers, function(index, mapmarker) {
				mapmarker.marker.setMap(null);		// remove marker from map
				mapmarker.marker = null;			// remove marker from memory
			});
		}
		mymarkers = [];

		// send request to WordPress and fetch available places
		$.post(
			my_places_obj.ajax_url,
			{
				action: 'get_places',
				placetypes: placetypes,
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

	// add click handler to filter button
	$('#my-placetypes input').on('click', function(e) {
		var placetypes = [];
		// get all checkboxes for placetypes
		var inputs = $('#my-placetypes input');
		$.each(inputs, function(index, input) {
			if ($(input).prop('checked')) {
				placetypes.push($(input).data('id'));
			}
		});
		console.log("selected placetypes", placetypes);
		addMapMarkers(placetypes);
	});

	// initialize map
	initMap();

})( jQuery );
