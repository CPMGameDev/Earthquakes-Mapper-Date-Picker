<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script type="text/javascript" src="googlemap.javascript"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Using PHP with Google Maps</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class= "container">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</a>
                    <form class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
                        <div class="form-group">
                            <label for="googleMapAPIForm">Google Map API</label>
                            <input type="text" class="form-control" id="googleMapAPIForm" placeholder="Enter API Here...">
                        </div>
                        <div class="form-group">
                            <label for="googleMapKeyForm">Google Map Key</label>
                            <input type="text" class="form-control" id="googleMapKeyForm" placeholder="Enter Key Here...">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
    </div>   

    <div id="map"></div>

    <div id="infowindow-content">
        <span id="place-name" class="title"></span><br>
        <strong>Place ID:</strong> <span id="place-id"></span><br>
        <span id="place-address"></span>
    </div>

    <?php
        $googleMapAPI = "https://maps.googleapis.com/maps/api/js?key=";
        $googleMapKey = "AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4";
        $googleMapLibrary = "&libraries=places&callback=LoadMap";

        $mapDataSource = "https://storage.googleapis.com/mapsdevsite/json/quakes.geo.json";
    ?>

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
                "<?php echo $mapDataSource ?>"
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
        autocomplete.setFields(['place_id', 'geometry', 'name']);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);

        var marker = new google.maps.Marker({map: map});

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        autocomplete.addListener('click', function() {
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

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src= "<?php echo $googleMapAPI . $googleMapKey . $googleMapLibrary ?>" async defer></script>
    <script src="googlemap.js"></script>
  </body>
</html>