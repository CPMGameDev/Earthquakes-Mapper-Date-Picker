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
<body id="map-container">
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
    </div>
    

    <div id="map"></div>

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
        }

        function eqfeed_callback(data) {
            map.data.addGeoJson(data);
        }
    </script>


    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4&callback=LoadMap"> 
    </script>  
</body>
</html>