<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Network Topology</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
// This example creates an interactive map which constructs a
// polyline based on user clicks. Note that the polyline only appears
// once its path property contains two LatLng coordinates.

var poly;
var map;

function initialize() 
{
    var mapOptions = {
        zoom: 5,
        // Center the map on Chicago, USA.
        center: new google.maps.LatLng(21.843452, 82.779175)
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var polyOptions = {
        strokeColor: '#000000',
        strokeOpacity: 1.0,
        strokeWeight: 1,
        clickable: true,
        dragable: true,
        geodesic: true,
        editable: false
    };
    poly = new google.maps.Polyline(polyOptions);
    poly.setMap(map);
    // Add a listener for the click event
    google.maps.event.addListener(map, 'click', getjsonstatic);
}

/**
 * Handles click events on a map, and adds a new point to the Polyline.
 * @param {google.maps.MouseEvent} event
 */
function getjsondb()
{
    <?php

        $db = mysqli_connect("localhost","root","root","router");
        $sql = "SELECT * FROM mapping_host group by State";

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        //echo 'Total results: ' . $result->num_rows;
        $result=mysqli_query($con,$sql);
        while(!$result)
        {
            echo $result;
        }
        for(var i=0;i<count($result);i++)
        {
          var path = poly.getPath();
          // Because path is an MVCArray, we can simply append a new coordinate
          // and it will automatically appear.
          var lt=latLng[i].split(",");
          var myLatlng = new google.maps.LatLng($result[0], $result[1]);
          path.push(myLatlng);
          // Add a new marker at the new plotted point on the polyline.
          var marker = new google.maps.Marker({ position: myLatlng, title: '#' + path.getLength(), map: map});
        }

    ?>
}

function unique (arr) {
    var hash = {}, result = [];
    for (var i = 0; i < arr.length; i++)
      if (!(arr[i] in hash)) { //it works with objects! in FF, at least
        hash[arr[i]] = true;
        result.push(arr[i]);
      }
    return result;
}
function getjsonstatic(event) 
{
  /*var path = poly.getPath();
  // Because path is an MVCArray, we can simply append a new coordinate
  // and it will automatically appear.
  path.push(event.latLng);
  alert(event.latLng);
  // Add a new marker at the new plotted point on the polyline.
  var marker = new google.maps.Marker({
    position: event.latLng,
    title: '#' + path.getLength(),
    map: map
  });*/
  var latLng = new Array("19.082352, 72.881204","21.843452, 82.779175","28.645726, 77.090757","13.034053, 80.206921","23.020396, 72.579742","22.676352, 88.3680555","9.927339, 76.266854");
  //alert(latLng);
  for(var i=0;i<6;i++)
  {
    var path = poly.getPath();
    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.
    var lt=latLng[i].split(",");
    var myLatlng = new google.maps.LatLng(lt[0], lt[1]);
    path.push(myLatlng);
    // Add a new marker at the new plotted point on the polyline.
    var marker = new google.maps.Marker({ position: myLatlng, title: '#' + path.getLength(), map: map});
  }
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>