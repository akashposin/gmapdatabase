
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
                // console.log(myLatlng);

                // create markers
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map:map,
                    title: 'Click for more details',
                    info: temp
                });


// To add the marker to the map, call setMap();
                marker.setMap(map);


                var infowindow = new google.maps.InfoWindow({

                    // content: contentString
                });

                // console.log(key);
                google.maps.event.addListener(marker, 'click', function() {
                    // console.log(temp.name);

                        // console.log(temp.name);
                        // console.log(key);
                        infowindow.setContent('<b><h3 style="text-align: center; color: blueviolet">'+this.info.name+'</h3></b>'
                            +'<b style="color: orangered">Name :</b> '+this.info.name+'<br>'
                            +'<b style="color: orangered">Description :</b> '+this.info.description+'<br>'
                            +'<b style="color: orangered">Address :</b>'+this.info.address+'<br>'
                            +'<b style="color: orangered">Latitude :</b>'+this.info.lat+'<br>'
                            +'<b style="color: orangered">Longitude :</b>'+this.info.lng);
                        infowindow.open(map, this);

                });

            }

        }
    });
}

// function to load map
function initialize() {
                   // alert("hiii");
    var myLatlng = new google.maps.LatLng(-25.363882,131.044922);

    // map set-up
    var mapOptions = {
        zoom: 4,
        center: myLatlng
    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);

    getlocations();
    }
