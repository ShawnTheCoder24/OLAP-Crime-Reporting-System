<?php
session_start();
//connect to the database
define ('DBSERVER','localhost');
define ('DBUSERNAME','root');
define ('DBPASSWORD','');
define ('DBNAME','olapsystemdb');

$db = mysqli_connect(DBSERVER,DBUSERNAME,DBPASSWORD,DBNAME);

//confirm connection
if($db === false)
{
    die("Error: Could not connect".mysqli_connect_error());
}
//selecting the required data from the database tables
$crimes = mysqli_query($db, "SELECT incident,crimeLocation FROM casetable");
$crimeAreas = mysqli_query($db, "SELECT crimeLocation,COUNT(incident) FROM casetable GROUP BY crimeLocation ORDER BY COUNT(incident) DESC LIMIT 10");
$unlimitedCrimeAreas =mysqli_query($db, "SELECT crimeLocation,COUNT(incident) FROM casetable GROUP BY crimeLocation ORDER BY COUNT(incident) DESC");
$countedCrimes = mysqli_query($db,"SELECT incident, COUNT(incident) FROM casetable GROUP BY incident  HAVING COUNT(incident)>0 ORDER BY COUNT(incident) DESC");

$nameArray = array();
$countArray = array();
$limitCountArray = array();
$areaArray = array();

//Obtaining data based on role
if($_SESSION)
{
$currentUser = $_SESSION['user'];

$userRole = mysqli_query($db, "SELECT * FROM systemusers WHERE Username ='" . $currentUser . "'");
$Role = mysqli_fetch_array($userRole);

$roleFound = (string) $Role['Role'];
$adminRole = "Admin";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Crime Analytics and Reports</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <link rel="stylesheet" href="mapDisplayTrial.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <?php
        //determining the nav bar to display based on the user's role
        if(!$_SESSION)
        {?>
         <div id="logoHeader">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a href="homePage.php">Home</a>
                <a href="aboutPage.php"id="more">About</a>
                <a href="reportForm.php" id="reportWriting">Write Report</a>
                <a class="current" href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <?php if(!$_SESSION) { ?>
                <button class="login" onclick= "window.location.href='login.php';">Login</button>
                <?php } else { ?>
                    <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
                    <?php }?>
            </div>
        </div>
        
        <?php }  
        else if($roleFound == $adminRole)
        {?>
        <div id="dashboard">
            <img src="policeLogo.png" class="logo">
            <div id="navigationAdmin">
                <a href="adminHomePage.php">Dashboard</a>
                <a href="viewUsers.php"id="seeUsers">View users</a>
                <a href="createUser.php"id="makeUsers">Create user</a>
                <a href="updateRecord.php" id="updateRecords">Update user</a>
                <a href ="deleteUser.php" id="deleteRecord">Delete user</a>
                <a class="current" href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
            </div>
        </div> 
        <?php }
        else if($roleFound == "User"){ ?>
         <div id="logoHeader">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a href="homePage.php">Home</a>
                <a href="aboutPage.html"id="more">About</a>
                <a href="reportForm.php" id="reportWriting">Write Report</a>
                <a class="current" href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <?php if(!$_SESSION) { ?>
                <button class="login" onclick= "window.location.href='login.php';">Login</button>
                <?php } else { ?>
                    <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
                    <?php }?>
            </div>
        </div>
        <?php }?>
        <!-- map section-->
        <div id="mapIntro">
            <h1>Crime Geo Map </h1>
        </div>
    
        <div id = "map">
           <?php include("mapDisplay.php")?>
        </div>
        <br>
        <!-- reports section-->
        <div id="reports">
            <h1> Reports </h1>
            <hr>

<!-- Table for crimes and number of times reported-->
<br>
<h2> Crimes ranked by the number of times they have been reported </h2>
<?php
    if (mysqli_num_rows($countedCrimes)>0){
?>
    <table>
    <tr>
    <th style="width:40%"><b>Crime Name</b></th>
    <th style="width:40%"><b>No.of Times Reported</b></th>
    </tr>

    <?php
    while($row2 = mysqli_fetch_array($countedCrimes)){
        $nameArray[] = $row2["incident"];
        $countArray[] = $row2["COUNT(incident)"];
      ?>
      <tr>
        <td><?php echo $row2["incident"]; ?></td>
        <td><?php echo $row2["COUNT(incident)"]; ?></td>
      </tr>
    <?php }?>
    </table>

    <?php }

    else{
      echo "No results were found";
    }
    ?>
    <br>
    <hr>
    <br>

    <!-- Bar graph for the above using ChartJS -->
    <h2> A Bar graph of the crimes ranked by the number of times reported</h2>
    <canvas id="crimeBarGraph"></canvas>
    <script>
       
        new Chart("crimeBarGraph",{
            type: "bar",
            data:{
                labels: <?php echo json_encode($nameArray);?>,
                datasets:[{
                    backgroundColor: "blue",
                    data: <?php echo json_encode($countArray);?>
                }]
            },
            options:{
                legend:{display:false},
                
                title:{
                    display:true,
                    text: "Crimes Reported"
                }
            }
        });
    </script>
    <hr>
    <br>

    <!-- Bar graph for locations with the highest crime-->
    <h2> A Bar graph on the top 10 areas with the highest number of crimes reported </h2>
    <?php
    while($row4 = mysqli_fetch_array($crimeAreas)){
        $areaArray[] = $row4["crimeLocation"];
        $limitCountArray[]=$row4['COUNT(incident)'];
    }
    ?>
    <canvas id="crimeAreasBarGraph"></canvas>
    <script>
       
       new Chart("crimeAreasBarGraph",{
           type: "bar",
           data:{
               labels: <?php echo json_encode($areaArray);?>,
               datasets:[{
                   backgroundColor: "red",
                   data: <?php echo json_encode($limitCountArray);?>
               }]
           },
           options:{
               legend:{display:false},
               
               title:{
                   display:true,
                   text: "Top 10 areas with high number of reported crimes"
               }
           }
       });
   </script>
   <br>
   <hr>
   <br>

   <h2> The number of crimes reported in all areas </h2>
   <?php
    if (mysqli_num_rows($unlimitedCrimeAreas)>0){
   ?>
    <table>
    <tr>
    <th style="width:40%"><b>Crime Location</b></th>
    <th style="width:40%"><b>No.of Crimes Reported</b></th>
    </tr>

    <?php
    while($row5 = mysqli_fetch_array($unlimitedCrimeAreas)){
      ?>
      <tr>
        <td><?php echo $row5["crimeLocation"]; ?></td>
        <td><?php echo $row5["COUNT(incident)"]; ?></td>
      </tr>
    <?php }?>
    </table>

    <?php }

    else{
      echo "No results were found";
    }
    ?>
    <br>
    <hr>
    <br>

<h2> Crimes and their locations </h2>

<!-- Table for the crimes reported and their location -->
<?php
if (mysqli_num_rows($crimes)>0){
 ?>
<table>
<tr>
<th style="width:40%"><b>Crime Name</b></th>
<th style="width:40%"><b>Crime Location</b></th>
</tr>

<?php
while($row = mysqli_fetch_array($crimes)){
?>
<tr>
<td><?php echo $row["incident"]; ?></td>
<td><?php echo $row["crimeLocation"]; ?></td>
</tr>
<?php }?>
</table>

<?php }

else{
echo "No results were found";
}
?>

    </div>
    </body>
</html>