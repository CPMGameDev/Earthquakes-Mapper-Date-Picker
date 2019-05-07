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
    <title>Using PHP with Google Maps</title>
</head>
<body>
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
                    <form class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
                        <div class="form-group">
                            <label for="mapDataSourceForm">Map Data Source</label>
                            <input type="text" class="form-control" id="mapDataSourceForm" placeholder="Enter Source Here...">
                        </div>
                        <div class="form-group">
                            <label for="googleMapKeyForm">Google Map Key</label>
                            <input type="text" class="form-control" id="googleMapKeyForm" placeholder="Enter Key Here...">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    
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
                    <div class="input-group date mb-4" id="startDateInputGroup" data-target-input="nearest">     
                        <input id="startDateTimePicker" title="startDateTimePicker" style="width: 100%;" />
                    </div>
    
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

    <?php
        $googleMapAPI = "https://maps.googleapis.com/maps/api/js?key=";
        $googleMapKey = "AIzaSyCgW1aVXMAw8od0VT8DnflvrMIVJFofqB4";
        $googleMapLibrary = "&libraries=places&callback=LoadMap";

        $mapDataSource = "https://storage.googleapis.com/mapsdevsite/json/quakes.geo.json";
    ?>

    <script>  

    var map;
    var latitd;
    var longtd;
    var titleName; 
    var startDate = new Date();
    var endDate = new Date();

    function LoadMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-26, 140),
            zoom: 3
        });

/*         var script = document.createElement('script');
        script.setAttribute(
            'src',
            ""
        );

        document.getElementsByTagName('head')[0].appendChild(script);

        map.data.setStyle(function(feature) {
            var mag = Math.exp(parseFloat(feature.getProperty('mag'))) * 0.1;
            return /* @type {google.maps.Data.StyleOptions} */ /* ({
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: mag,
                fillColor: '#f00',  
                fillOpacity: 0.35,
                strokeWeight: 0
                }
            });
        }); */
    }

    function CreateMarker(latlng) {
        var marker = new google.maps.Marker({
            map: map,
            position: latlng
        });
    }

    function DisplayMarker() {
        var latlng = new google.maps.LatLng(latitd, longtd);
        CreateMarker(latlng);
    }

    function eqfeed_callback(data) {
        map.data.addGeoJson(data);
    }

    $(document).ready(function() {
        $("#startDateTimePicker").kendoDatePicker({
            format: "yyyy-MMM-dd",
            value: new Date(2000, 10, 10)
        })
                        .closest(".k-widget")
                        .attr("id", "datepicker_wrapper");

        var startDateTimePicker = $("#startDateTimePicker").data("kendoDatePicker");
        
        $("#endDateTimePicker").kendoDatePicker( {
            format: "yyyy-MMM-dd",
            value: new Date(2000, 10, 10)
        })
                        .closest(".k-widget")
                        .attr("id", "datepicker_wrapper");

        var endDateTimePicker = $("#endDateTimePicker").data("kendoDatePicker");

        $("#update").click(function() {
            startDate = kendo.toString(kendo.parseDate(new Date()), 'yyyy-MM-dd-');
            endDate = kendo.toString(kendo.parseDate(new Date()), 'yyyy-MM-dd-');
            GetQuakes();
        });    

        function GetQuakes() {
            
            $.ajax({
                        url: 'http://earthquake.usgs.gov/fdsnws/event/1/query?format=geojson&starttime=' + startDate + '&endtime=' + endDate,
                        dataType : 'json'
                    })
                        .done(function(data) {
                        $.each(data.features, function(key, val) {
                            var coord = val.geometry.coordinates;
                            locationD = {
                                latd: coord[0],
                                lngd: coord[1]
                            };
                            latitd = locationD.latd;
                            longtd = locationD.lngd;
                            DisplayMarker();        
                        });
                    })

            console.log("Start date is: " + startDate);
        }
    });

/* $(document).ready(function() {

            // create DateTimePicker from input HTML element


            $("#endDateTimePicker").kendoDateTimePicker({
                format: "MM/dd/yyyy",
                value: new Date(2000, 10, 10, 10, 0, 0),
                dateInput: true
            });

            var datepicker = $("#startDateTimePicker").data("kendoDatePicker");

            $("#update").click(function() {
                alert(datepicker.value());
            });
        }) ; */
    </script>   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src= "https://maps.googleapis.com/maps/api/js?key=AIzaSyDrhmCE5YeH0r9Kkeq-v4ZXBd87UvwCOrw&callback=LoadMap" async defer></script>
  </body>
</html>