<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/boostrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script type="text/javascript" src="js/googlemap.js"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Using PHP with Google Maps</title>
</head>
<body>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
    </div>   

    <div id="map"></div>

    <div id="infowindow-content">
        <span id="place-name" class="title"></span><br>
        <strong>Place ID:</strong> <span id="place-id"></span><br>
        <span id="place-address"></span>
    </div>

    <script>      
      var map;

      function LoadMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-26, 140),
            zoom: 3
            });

            var script = document.createElement('script');
            script.setAttribute(
                'src',
                'https://storage.googleapis.com/mapsdevsite/json/quakes.geo.json'
            );

            document.getElementsByTagName('head')[0].appendChild(script);

            map.data.setStyle(function(feature) {
                var mag = Math.exp(parseFloat(feature.getProperty('mag'))) * 0.1;
                return /** @type {google.maps.Data.StyleOptions} */ ({
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: mag,
                    fillColor: '#f00',
                    fillOpacity: 0.35,
                    strokeWeight: 0
                    }
                });
            });

        var input = document.getElementById('pac-input');

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // Specify just the place data fields that you need.
        autocomplete.setFields(['place', 'geometry', 'mag']);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);

        var marker = new google.maps.Marker({map: map});

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        autocomplete.addListener('place_changed', function() {
            infowindow.close();

            var place = autocomplete.getPlace();

            if (!place.geometry) {
            return;
            }

            if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
            } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
            }

            // Set the position of the marker using the place ID and location.
            marker.setPlace({
                placeId: place.place_id,
                location: place.geometry.location
            });

            marker.setVisible(true);

            infowindowContent.children['place-name'].textContent = place.name;
            infowindow.open(map, marker);
        });
      }

      function eqfeed_callback(data) {
            map.data.addGeoJson(data);
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4&libraries=places&callback=LoadMap"
        async defer></script>
  </body>
</html>