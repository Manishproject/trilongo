var map;
var markerArray = [];
var dirService = new google.maps.DirectionsService();
var dirRenderer = new google.maps.DirectionsRenderer({
    'draggable': false,
    'hideRouteList': true,
    'suppressBicyclingLayer': true
});
function initialize() {
    var mapOptions = {
        zoom: 8,
        center: new google.maps.LatLng(-34.397, 150.644),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

// initializeTextbox text google auto complete
function initializeTextbox(textbox) {
    var options = {
        componentRestrictions: {country: country_code}
    };
    var autocomplete = new google.maps.places.Autocomplete(textbox, options);
    if ($(textbox).attr("id") == "ToTextBox") {
        return google.maps.event.addListener(autocomplete, 'place_changed', function () {
            getDirections();
        });
    }
}


// validate the text fields
function validateInput(value) {
    if ($.trim(value) == '' || value == 'Enter a location') {
        return false;
    }
    if (value.indexOf('#') != -1 || value.indexOf(':') != -1) {
        alert('Please enter from location without special characters');
        reset();
        return false;
    }
    return true;
}

function showSteps(directionResult) {

    var myRoute = directionResult.routes[0];
    $("#warnings_panel").html("<b>" + myRoute.warnings + "</b>");
    overview_path = myRoute.overview_path;
    var totalWaypoints = myRoute.overview_path.length;
    var waypointsToDisplay = 4;
    var arrayToGeocode = [];
    var step = parseInt(totalWaypoints / (waypointsToDisplay + 1));
    for (var i = step; arrayToGeocode.length < waypointsToDisplay; i += step) {
        var marker = new google.maps.Marker({
            position: myRoute.overview_path[i]
        });
        arrayToGeocode.push(marker);
    }
    for (var i = 0; i < arrayToGeocode.length; i++) {
        marker = arrayToGeocode[i];
        codeLatLng(marker);
        markerArray.push(marker);
    }

}
function codeLatLng(marker) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                if (results[1].formatted_address.toLowerCase() == $("#FromTextBox").val())
                    return;
                else if (results[1].formatted_address.toLowerCase() == $("#ToTextBox").val())
                    return;
                if ($("#StopOverList > li#" + results[1].address_components[0].long_name).get(0)) {
                    return;
                }
                marker.setMap(map);
                var text = results[1].formatted_address;
                if (text == '') {
                    text = results[1].address_components[0].long_name;
                }
                attachInstructionText(marker, text);
                var html = '<li title="' + results[1].formatted_address
                    + '" id="' + results[1].address_components[0].long_name + '">' + results[1].address_components[0].long_name + '</li>';
                $("#StopOverList").append(html);
            } else {
                alert("No results found");
            }
        }
    });
}
function attachInstructionText(marker, text) {
    google.maps.event.addListener(marker, 'click', function () {
        // Open an info window when the marker is clicked on,
        // containing the text of the step.
        markerArray.push(marker);
//        stepDisplay.setContent(text);
//        stepDisplay.open(map, marker);
    });
}
function computeTotalDistanceAndTime(dirResult, routeIndex) {
    var distance = 0; // distance in meters
    var duration = 0; // duration in seconds
    var route;
    if (routeIndex < dirResult.routes.length) {
        route = dirResult.routes[routeIndex];
    } else {
        route = dirResult.routes[0];
    }
    for (var i = 0; i < route.legs.length; i++) {
        distance += route.legs[i].distance.value;
        duration += route.legs[i].duration.value;
    }
    $("#TotalDistanceValue").val(distance);
    $("#TotalDurationValue").val(duration);
    html = '<br /><div class="TitleText"><span>Driving Directions</span></div>';
    html += '<div class="direction_detail"><table id="directions" cellspacing="0" cellpadding="2" width="100%">';
    for (i = 0; i < route.legs.length; i++) {
        var steps = route.legs[i].steps;
        for (j = 0; j < steps.length; j++) {
            var nextSegment = steps[j].path;
            html += '<tr><td width="94%"><li>' + steps[j].instructions;
            var dist_dur = "";
            if (steps[j].distance && steps[j].distance.text)
                dist_dur += "&nbsp;" + steps[j].distance.text;
            if (steps[j].duration && steps[j].duration.text)
                dist_dur += "&nbsp;" + steps[j].duration.text;
            if (dist_dur != "")
                html += "<span>(" + dist_dur + ")</span></li></td></tr>";
            else
                html += "</li></td></tr>";
        }
    }
    html += '</table></div><br />';
    if ($(window).width() > 921) {
        $("#DirectionsDetails").css('display', 'block');
        $('#warnings_panel').css('display', 'block');
        $("#DirectionsDetails").html(html);
    }
    var div = $("#DistanceDisplay");
    if (div) {
        var kms = Math.round(distance / 1000);
        // var meters = distance % 1000;
        // var str = kms.toFixed(0) + " km " + meters.toFixed(0) + " mtrs";
        var str = "<b>" + kms + " kms</b>"; //  + meters.toFixed(0) + " mtrs";
        var miles = distance * 0.000621371192;
        str += '&nbsp;<small><i>(' + Math.round(miles.toFixed(2)) + ' miles.)</i></small>';
        $('#DistanceDisplay').html(str);
        $("#map_estimated_distance_hidden").val(kms);
    }
    div = $("#TimeDisplay");
    if (div) {
        var hours = duration / 3600;
        duration = duration % 3600;
        var mins = duration / 60;
        $("#TimeDisplay").html("<b>" + Math.floor(hours).toFixed(0) + " hour(s)</b><small><i> " + mins.toFixed(0) + " min(s).</i></small>");
        $("#map_estimated_hours_hidden").val( Math.floor(hours).toFixed(0));
        $("#map_estimated_min_hidden").val( mins.toFixed(0));

    }
}

function showDirections(dirResult, dirStatus) {
    var message = '';
    if (dirStatus == google.maps.DirectionsStatus.NOT_FOUND) {
        message = 'Unable to find either the source or destination or a stop over point. Please try a nearby location or City and try again.';
    } else if (dirStatus == google.maps.DirectionsStatus.ZERO_RESULTS) {
        message = 'Unable to find a possible route between source and destination. Please try a nearby location or City and try again.';
    } else if (dirStatus == google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
        message = 'Sorry the server might be too busy. Please wait and try again after some time';
    } else if (dirStatus == google.maps.DirectionsStatus.MAX_WAYPOINTS_EXCEEDED) {
        message = 'You have added the maximum allowable waypoints. Please reduce the number of waypoints.';
    } else if (dirStatus != google.maps.DirectionsStatus.OK) {
        message = 'Directions failed: ' + dirStatus;
    }
    if (message != '') {
        alert(message);
        reset();
        return;
    }
    // Show directions
    dirRenderer.setMap(map);
    dirRenderer.setDirections(dirResult);
    dirRenderer.setRouteIndex(parseInt($('#RouteIndex').val()));
    showSteps(dirResult);
    return true;
}

// click on go button

$(document).ready(function () {
    $('#GoButton').click(getDirections);
    initializeTextbox($('#FromTextBox')[0]);
    initializeTextbox($('#ToTextBox')[0]);





    // for taxi
    $('.end_date_time_taxi_picker').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm TT',
        minDate: getFormattedDate(new Date()),
        onClose: function(selectedDate) {
            var start_date = $(this).closest("form").find(".start_date_time_picker").val();
            if (start_date == "") {
                $(".end_date_time_taxi_picker").val('');
                alert("Please fill start date");
            } else {
                compare_date_for_taxi(selectedDate, start_date);
            }
        }
    });


    $('.end_date_time_picker').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm TT',
        minDate: getFormattedDate(new Date()),
        onClose: function(selectedDate) {
            var start_date = $(this).closest("form").find(".start_date_time_picker").val();
            if (start_date == "") {
                $(".end_date_time_picker").val('');
                alert("Please fill start date");
            } else {
                compare_date(selectedDate, start_date);
            }
        }
    });

    $('.start_date_time_picker').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm TT',
        minDate: getFormattedDate(new Date()),
        onSelect: function(selectedDate) {

        },
        beforeShow: function() {

        }
    });






//
});
$(window).load(function ($) {
    var to = document.getElementById("ToTextBox").value;
    var form = document.getElementById("FromTextBox").value;
        if (form && to){
            getDirections();



        }
});

function getDirections() {
    var wait = document.getElementById("wait_next_reservation");
    wait.style.display = 'none';
    var next = document.getElementById("next_reservation");
    next.style.display = 'block';
    // First, remove any existing markers from the map.
    for (i = 0; i < markerArray.length; i++) {
        markerArray[i].setMap(null);
    }
    // Now, clear the array itself.
    markerArray = [];

    var fromLocation = $('#FromTextBox').val();
    var toLocation = $('#ToTextBox').val();
    if (!validateInput(fromLocation)) {
        alert('Please enter the start location without any special characters');
        return false;
    }
    if (!validateInput(toLocation)) {
        alert('Please enter the end location without any special characters');
        return false;
    }

    // Everything is valid now make call to the geocoder service to check if location exists
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': fromLocation }, function (fromResults, fromStatus) {
        if (fromStatus == google.maps.GeocoderStatus.OK) {
            geocoder.geocode({ 'address': toLocation }, function (toResults, toStatus) {
                if (toStatus == google.maps.GeocoderStatus.OK) {
                    var latLng = fromResults[0].geometry.location; // This could be changed for Did you mean feature.
                    latLng = toResults[0].geometry.location;
                    var str = "";
                    $.each(fromResults, function () {
                        $.each(this.address_components, function () {
                            str += this.types.join(", ") + ": " + this.long_name + "$";
                        });
                    });
                    var dirRequest = {
                        origin: fromLocation,
                        destination: toLocation,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING,
                        unitSystem: google.maps.DirectionsUnitSystem.METRIC,
                        provideRouteAlternatives: true
                    };
                    google.maps.event.addListener(dirRenderer, 'directions_changed', function () {
                        computeTotalDistanceAndTime(dirRenderer.directions, $('#RouteIndex').val());
                    });

                    dirService.route(dirRequest, showDirections);

                } else {
                    alert('Location ' + toLocation + ' Not Found.');
                }
            });
        } else {
            alert('Location ' + fromLocation + ' Not Found.');
        }
    });
    return false;
}


