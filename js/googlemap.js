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