function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        mapTypeControl: false, // Hide the map type control
        streetViewControl: false, // Hide the Street View control
        fullscreenControl: false, // Hide the fullscreen control
        zoomControl: false
    });

    function addMarker(location, title, draggable) {
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: title,
            draggable: draggable
        });

        marker.addListener('click', function(event) {
            updateLocation(event.latLng);
        });

        return marker;
    }

    function updateLocation(newLocation) {
        map.setCenter(newLocation);

        map.markers.forEach(function(marker) {
            marker.setMap(null);
        });

        var newMarker = addMarker(newLocation, 'Tu ubicación actual', true);
        map.markers = [newMarker];

        document.getElementById('cx').value = newLocation.lat();
        document.getElementById('cy').value = newLocation.lng();
    }

    function isMobile() {
        return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
    }

    var clickEvent = isMobile() ? 'tap' : 'dblclick';

    google.maps.event.addListener(map, clickEvent, function(event) {
        updateLocation(event.latLng);
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLatLng = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(userLatLng);
            var draggableMarker = addMarker(userLatLng, 'Tu ubicación actual', true);
            map.markers = [draggableMarker];

            document.getElementById('cx').value = userLatLng.lat;
            document.getElementById('cy').value = userLatLng.lng;

            navigator.geolocation.watchPosition(function(position) {
                var newLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                updateLocation(newLocation);
            }, function() {
                handleLocationError(true, map.getCenter());
            });
        }, function() {
            handleLocationError(true, map.getCenter());
        });
    } else {
        handleLocationError(false, map.getCenter());
    }
}
