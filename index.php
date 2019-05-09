<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.220/styles/kendo.common-material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.220/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.220/styles/kendo.material.mobile.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://kendo.cdn.telerik.com/2019.1.220/js/kendo.all.min.js"></script>

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Earthquakes Mapper/DateTime Picker</title>
</head>
<body>

    <!-- Form handling --> 

    <?php 

    $mapDataSourceErr = $googleMapKeyErr = "";
    $mapDataSource = "http://earthquake.usgs.gov/fdsnws/event/1/query?format=geojson";
    $googleMapKey = "AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["mapDataSource"])) {
            $mapDataSourceErr = "* Map is required";
        } else {
            $mapDataSource = test_input($_POST["mapDataSource"]);
        }
    
        if (empty($_POST["googleMapKey"])) {
            $googleMapKeyErr = "* Key is required";
        } else {
            $googleMapKey = test_input($_POST["googleMapKey"]);
        }
    }

    function test_input($data) {
        $data = trim($data);
       return $data; 
    }

    ?>

    <!-- Fixed Top Navbar: DateTimePicker, Home & Options section -->  

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class= "container">
            <a href="#datePickerModalCenter" role="button" class="btn" data-toggle="modal">
                <span class="fas fa-calendar-alt mr-2"></span>
            </a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</a>
                    <form method="post" class="dropdown-menu p-3" action="<?php echo trim($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="googleMapKey">Google Map Key</label>
                            <input type="text" name="googleMapKey" class="form-control" value="<?php echo $googleMapKey ?>">
                        </div>
                        <div class="form-group">
                            <label for="mapDataSource">Map Data Source</label>
                            <input type="text" name="mapDataSource" class="form-control" value="<?php echo $mapDataSource ?>">
                        </div>

                        <button type="submit" name="submit" value="Submit" class="btn btn-primary">Update</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- DateTimePicker: Select a date range by choosing a date on Start Date and End Date input fields -->  
    
    <div class="modal fade" id="datePickerModalCenter" tabindex="-1" role="dialog" aria-labelledby="datePickerModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datePickerModalLongTitle">Date Picker</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h6 class="modal-title mb-2" id="startDateFormTitle">Start Date</h6>
                    <div class="input-group date mb-4" id="startDateInputGroup" data-target-input="nearest">     
                        <input id="startDateTimePicker" title="startDateTimePicker" style="width: 100%;" />
                    </div>

                    <h6 class="modal-title mb-2" id="endDateFormTitle">End Date</h6>
                    <div class="input-group date mb-4" id="endDateInputGroup" data-target-input="nearest">
                        <input id="endDateTimePicker" title="endDateTimePicker" style="width: 100%;" />
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="update" type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <!-- Functionality for Google Maps and Date Time Picker -->  
    <!-- Search limit for GeoJson is 20000 -->

    <script>

    var map;
    var markers = [];
    var latitd;
    var longtd;
    var titleName; 
    var startDate = new Date(2000, 10, 10);
    var endDate = new Date(2000, 10, 30);

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-26, 140),
            zoom: 3
        });
    }

    function createMarker(latlng) {
        var marker = new google.maps.Marker({
            position: latlng,
            map: map
        });

        markers.push(marker);
    }

    function displayMarker() {
        // console.log('Markers: ' + latitd + ':' + longtd + '\n')
        var latlng = new google.maps.LatLng(latitd, longtd);
        createMarker(latlng);
    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

    function showMarkers() {
        setMapOnAll(map);
    }

    function clearMarkers() {
        setMapOnAll(null);
    }

    function deleteMarkers() {
        clearMarkers();
        markers = [];
    }

    function eqfeed_callback(data) {
        map.data.addGeoJson(data);
    }

    $(document).ready(function() {

        $("#startDateTimePicker").kendoDatePicker({
            format: "yyyy-MMM-dd",
            value: startDate
        })
                        .closest(".k-widget")
                        .attr("id", "datepicker_wrapper");

        var startDateTimePicker = $("#startDateTimePicker").data("kendoDatePicker");
        
        $("#endDateTimePicker").kendoDatePicker( {
            format: "yyyy-MMM-dd",
            value: endDate
        })
                        .closest(".k-widget")
                        .attr("id", "datepicker_wrapper");

        var endDateTimePicker = $("#endDateTimePicker").data("kendoDatePicker");

        $("#update").click(function() {
            startDate = kendo.toString(kendo.parseDate(startDateTimePicker.value()), 'yyyy-MM-dd');
            endDate = kendo.toString(kendo.parseDate(endDateTimePicker.value()), 'yyyy-MM-dd');
            
            updateMap();
        });    

        function updateMap() {
            deleteMarkers();
            getQuakes();
            showMarkers();
        }

        function getQuakes() {
            // console.log('StartDate: ' + startDate + ', EndDate: ' + endDate + '\n');
            $.ajax({
                url: "<?php echo $mapDataSource ?>" + '&starttime=' + startDate + '&endtime=' + endDate,
                dataType : 'json'
            })
                .done(function(data) {

                // console.log('data.features: '+ data.features);
                $.each(data.features, function(key, val) {
                    var coord = val.geometry.coordinates;
                    locationD = {
                        latd: coord[0],
                        lngd: coord[1]
                    };
                    latitd = locationD.latd;
                    longtd = locationD.lngd;
                    // console.log(latitd, longtd);
                    displayMarker();        
                });
            })
                .fail(function(e){
                console.log(e);
            })
                .always(function(){
                console.log('ajax executed');
            });
        }  
    });
    
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src= "https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapKey ?>&libraries=places&callback=initMap" async defer></script>
  </body>
</html>