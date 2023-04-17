<?php
//retrieve data on all crimes and the locations they were reported to have occured in from the specified database table that is holding this data
$crimeInfo = mysqli_query($db,"SELECT incident,crimeLocation FROM casetable");
?>

<script>
//using leaflet js to create a map  that will visualize the data
//Center the map on Nairobi
var map = L.map('map').setView([-1.286389, 36.817223],13);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', 
{
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

//Geocode the location names into latitudes and longitudes
$(document).ready(function(){
    geocoder = new L.Control.Geocoder.Nominatim();
   <?php while($row3 = mysqli_fetch_assoc($crimeInfo)){
    ?>

var testLocation = "<?php echo $row3["crimeLocation"] ?>,Nairobi";

geocoder.geocode(testLocation,function(results){
    latlng = new L.LatLng(results[0].center.lat, results[0].center.lng);
    //Place a circle on those specified coordinates
    circle = new L.circle (latlng,{
        color:'blue',
        fillColor:'#f03',
        fillOpacity: 0.5,
        radius: 400
    }).addTo(map);

   //Add a popup that gives specific crime that occured in the highlighted area 
   circle.bindPopup("<?php echo addslashes($row3["incident"]) ?>");

});
<?php }?>
});

</script>

