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
      <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #panel {
        position: absolute;
        top: 5px;
        left: 35%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
// This example creates an interactive map which constructs a
// polyline based on user clicks. Note that the polyline only appears
// once its path property contains two LatLng coordinates.

var poly_router;
var poly_state;
var map;
var state=0;
var router=0;
var markers_all=new Array();
var marker=new Array();
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
    poly_state = new google.maps.Polyline(polyOptions);
    poly_state.setMap(map);
    poly_router = new google.maps.Polyline(polyOptions);
    poly_router.setMap(map);
    // Add a listener for the click event
    //google.maps.event.addListener(map, 'click', getjson);
}

/**
 * Handles click events on a map, and adds a new point to the Polyline.
 * @param {google.maps.MouseEvent} event
 */
function showStatesMarkers()
{
    if(state==0)
    {
    <?php
        $json="";

        $obj = json_decode($json);
        $db = mysqli_connect("localhost","root","root","router");
        $sql = "SELECT State,count(State) as count,State_latlon FROM `mapping_host` group by State;";

        // Check connection
        if (mysqli_connect_errno($db))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        //echo 'Total results: ' . $result->num_rows;
        $result=mysqli_query($db,$sql);
        $num_rows =$result->num_rows;
        
        //echo $num_rows;
        for($i=0;$i<=$num_rows;$i++)
        {
            echo "marker[$i] = new google.maps.Marker({ map: map});\n\n";    
        }
        $i=0;
        echo "var latlon= new google.maps.LatLng(0,0);\n\n";
        
        while($row = $result->fetch_assoc()) 
        {

            echo "latlon = new google.maps.LatLng(".$row['State_latlon'].");\n";
            echo "marker[".$i."].setPosition(latlon);\n";
            echo "marker[".$i."].setTitle('Total:$row[count]');\n";            
            echo "marker[".$i++."].setIcon('router.png');\n";
            //echo stripslashes($row['State']);    
        }
      
        $obj = json_decode($json);
        //print_r($obj->{'nodes'});
        //print_r($obj['nodes']);
        $i=0;
        $a="";
        $lines = 0;
        $Source_routers = array();
        $Target_routers = array();
        foreach ($obj->{'links'} as $link) 
        {
            $Source = trim($link->{'Source'});
            $Target = trim($link->{'Target'});   

            
            $Target_latlon = "";
            $Source_latlon = "";                     
            $Target_State = "";
            $Source_State = "";     
            
            if(strcmp($Target, "External Link")!=0)
            {
                $sql = "SELECT State,State_latlon FROM `mapping_host` where Hostname='$Target'";
                //echo $sql."\n\n";
                $result=mysqli_query($db,$sql);            
                while($row = $result->fetch_assoc()) 
                {
                    $Target_latlon = $row['State_latlon'];
                    $Target_State = $row['State'];
                }

                $sql = "SELECT State,State_latlon FROM `mapping_host` where Hostname='$Source'";
                //echo $sql."\n\n";
                //echo "$Source";
                $result=mysqli_query($db,$sql);            
                while($row = $result->fetch_assoc()) 
                {
                    $Source_latlon = $row['State_latlon'];
                    $Source_State = $row['State'];
                }
                                
                if(strcmp($Target_latlon,"")!=0)
                {   
                    $in1 = $Target_State.$Source_State;
                    $in2 = $Source_State.$Target_State;
                    if(!isset($Source_routers[$in1]) or !isset($Source_routers[$in2]))
                    {
                        echo "var path = poly_state.getPath();\n";
                        echo "var myLatlng = new google.maps.LatLng($Source_latlon);\n";
                        echo "path.push(myLatlng);\n";
                        echo "var path = poly_state.getPath();\n";
                        echo "var myLatlng = new google.maps.LatLng($Target_latlon);\n";
                        echo "path.push(myLatlng);\n";                           
                        $Source_routers[$Source_State.$Target_State] = 1;
                        $a = $a.$Source."=>".$Target."\n";
                        $lines++;
                    }
                }
            }
            //var_dump($Source_routers);
        }
        echo "console.log('".$lines."');";
        mysqli_close($db);
    ?>
        poly_state.setMap(map);
        state=1;
    }
    else
    {
        for(var i=0;i<5;i++)
        {
            marker[i].setVisible(true);           
        }
        poly_state.setMap(map);    
    }
}
function hideStatesMarkers()
{    
    for(var i=0;i<5;i++)
    {
        marker[i].setVisible(false);           
    }
    poly_state.setMap(null);
}
function showRoutersMarkers()
{
    if(router==0)
    {
    <?php
        $db = mysqli_connect("localhost","root","root","router");
        
        // Check connection
        if (mysqli_connect_errno($db))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $sql = "SELECT * FROM `mapping_host`";
        $result=mysqli_query($db,$sql);
        $i=0;
        echo "var latlon= new google.maps.LatLng(0,0);\n\n";
        
        while($row = $result->fetch_assoc()) 
        {
            echo "latlon = new google.maps.LatLng(".$row['Latlon'].");\n";
            echo "markers_all[$i] = new google.maps.Marker({ map: map});\n"; 
            echo "markers_all[".$i."].setPosition(latlon);\n";
            echo "markers_all[".$i."].setTitle('".$row['Hostname']."');\n";
            //echo "markers_all[".$i++."].setIcon('router.png');\n\n";
            //echo "markers_all[".$i++."].setVisible(true);\n\n";
            //echo "markers_all[".$i++."].setMap('null');\n";
            $obj = json_decode($json);
            $rat="";
            foreach($obj->{'nodes'} as $node)
            {
                if(strcmp(trim($node->{'Hostname'}), trim($row['Hostname']))==0)
                {
                    $rat = $node->{'RAT'};
                    break;
                }
            }

            if(strcmp(trim($rat),"fail")==0)
            {
                echo "markers_all[".$i++."].setIcon('router_fail.png');\n";
            }
            else
                echo "markers_all[".$i++."].setIcon('router_pass.png');\n";
        }

        $obj = json_decode($json);
        //print_r($obj->{'nodes'});
        //print_r($obj['nodes']);
        $i=0;
        $a="";
        $lines = 0;
        $Source_routers = array();
        $Target_routers = array();
        foreach ($obj->{'links'} as $link) 
        {
            $Source = trim($link->{'Source'});
            $Target = trim($link->{'Target'});   
            
            $Target_latlon = "";
            $Source_latlon = "";                     
            $Target_State = "";
            $Source_State = "";     
            
            if(strcmp($Target, "External Link")!=0)
            {
                $sql = "SELECT City, Latlon FROM `mapping_host` where Hostname='$Target'";
                //echo $sql."\n\n";
                $result=mysqli_query($db,$sql);            
                while($row = $result->fetch_assoc()) 
                {
                    $Target_latlon = $row['Latlon'];
                    $Target_State = $row['City'];
                }

                $sql = "SELECT City, Latlon FROM `mapping_host` where Hostname='$Source'";
                //echo $sql."\n\n";
                //echo "$Source";
                $result=mysqli_query($db,$sql);            
                while($row = $result->fetch_assoc()) 
                {
                    $Source_latlon = $row['Latlon'];
                    $Source_State = $row['City'];
                }
                                
                if(strcmp($Target_latlon,"")!=0)
                {   
                    $in1 = $Target_State.$Source_State;
                    $in2 = $Source_State.$Target_State;
                    //if(!isset($Source_routers[$in1]) or !isset($Source_routers[$in2]))
                    {
                        echo "var path = poly_router.getPath();\n";
                        echo "var myLatlng = new google.maps.LatLng($Source_latlon);\n";
                        echo "path.push(myLatlng);\n";
                        echo "var path = poly_router.getPath();\n";
                        echo "var myLatlng = new google.maps.LatLng($Target_latlon);\n";
                        echo "path.push(myLatlng);\n";                           
                        $Source_routers[$Source_State.$Target_State] = 1;
                        $a = $a.$Source."=>".$Target."\n";
                        $lines++;
                    }
                }
            }
            //var_dump($Source_routers);
        }

        echo "console.log('".$lines."');";
        mysqli_close($db);
    ?>
    poly_router.setMap(map);
    router=1;
    }
    else
    {
        for(var i=0;i<34;i++)
        {
            markers_all[i].setVisible(true);               
        }
        poly_router.setMap(map);
    }
}
function hideRoutersMarkers()
{        
    for(var i=0;i<34;i++)
    {
        markers_all[i].setVisible(false);               
    }
    poly_router.setMap(null);
}
function hideAllMarkers()
{
    for(var i=0;i<5;i++)
    {
        marker[i].setVisible(false);           
    }
    poly_state.setMap(null);
    for(var i=0;i<34;i++)
    {
        markers_all[i].setVisible(false);               
    }
    poly_router.setMap(null);
}
function addLatLng(event) 
{ 
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
   <div id="panel">
      <input onclick="showStatesMarkers();" type=button value="Show States Markers">
      <input onclick="hideStatesMarkers();" type=button value="Hide States Markers">      
      <input onclick="showRoutersMarkers();" type=button value="Show Routers Markers">
      <input onclick="hideRoutersMarkers();" type=button value="Hide Routers Markers">
      <input onclick="hideAllMarkers();" type=button value="Hide All Markers">      

    </div>
    <div id="map-canvas"></div>
  </body>
</html>