var map;

function LoadMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: new google.maps.LatLng(-33.863276, 151.207977),
      zoom: 12
    });

    var script = document.createElement('script');
    script.setAttribute(
        'src',
        'https://storage.googleapis.com/mapsdevsite/json/quakes.geo.json'
    );

    script.firstChild.nodeValue.

    document.getElementsByTagName('head')[0].appendChild(script);
}

function eqfeed_callback(data) {
    map.data.addGeoJson(data);
  }

