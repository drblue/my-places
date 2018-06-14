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
		mymarkers = [
			{
				latitude: 55.7050242,
				longitude: 13.1942046,
				content: '<h4>First marker</h4>Very improved info window! yey! Much <b>HTML!</b>'
			},
			{
				latitude: 55.7150242,
				longitude: 13.1742046,
				content: 'This is second marker improved info window! very wow!'
			},
		];

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
		// loop over mymarkers array and add a marker for each object in the array
		$.each(mymarkers, function(index, marker){
			addMapMarker(marker.latitude, marker.longitude, marker.content);
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
			infoWindow.open(mymap, marker);
		});
	}

	// initialize map
	initMap();

})( jQuery );
