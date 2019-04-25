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

    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4&callback=LoadMap"> 
    </script>  
</body>
</html>