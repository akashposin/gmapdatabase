
var map;
function getlocations() {
               // alert("hiiiii");
    var id = "1";

    $.ajax({
        type: "GET",
        headers: { 'X-CSRF-TOKEN' : csrf_token },
        url: "home/googlemap",
        // data: {id: id},
        dataType: 'json',


        success: function (results) {
                   // console.log(results);

            for (key in results) {
                  // alert("key = "+ key);
                // console.log(results);
                var temp = results[key];
                // console.log(temp);

                var myLatlng = new google.maps.LatLng(temp.lat,temp.lng);
                console.log(myLatlng);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map:map
                });


// To add the marker to the map, call setMap();
                marker.setMap(map);
            }

        }
    });
}

function initialize() {
                   // alert("hiii");
    var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
    var mapOptions = {
        zoom: 4,
        center: myLatlng
    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);

    getlocations();
    }
