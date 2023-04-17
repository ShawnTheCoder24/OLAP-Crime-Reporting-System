<?php
session_start();
//connect to the database
define ('DBSERVER','localhost');
define ('DBUSERNAME','root');
define ('DBPASSWORD','');
define ('DBNAME','olapsystemdb');

$db = mysqli_connect(DBSERVER,DBUSERNAME,DBPASSWORD,DBNAME);

//check on the connection status
if($db === false)
{
    die("Error: Could not connect".mysqli_connect_error());
}
// get all data from the table with reported case data
$reportedCases = mysqli_query($db, "SELECT * FROM casetable");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Page</title>
        <link rel="stylesheet" href="adminHomePage.css">
    </head>
    <body>
      <!-- nav bar section-->
    <div id="dashboard">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a class="current" href="adminHomePage.php">Dashboard</a>
                <a href="viewUsers.php"id="seeUsers">View users</a>
                <a href="createUser.php"id="makeUsers">Create user</a>
                <a href="updateRecord.php" id="updateRecords">Update user</a>
                <a href ="deleteUser.php" id="deleteRecord">Delete user</a>
                <a href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
    </div>
</div>
<!-- Display a table of all the reported crime cases -->
<div id="header">
  <h1> Reported Crimes </h1>
</div>

    <?php
    if (mysqli_num_rows($reportedCases)>0){
    ?>
    <table>
      <tr>
        <th>Case ID</th>
        <th>Incident</th>
        <th>Date Reported</th>
        <th>Date of occurence</th>
        <th>Crime Location </th>
        <th>Officer ID</th>
        <th>Citizen ID</th>
    </tr>
    <?php
    while($row = mysqli_fetch_array($reportedCases)){
      ?>
      <tr>
        <td><?php echo $row["caseID"]; ?></td>
        <td><?php echo $row["incident"]; ?></td>
        <td><?php echo $row["reportDate"]; ?></td>
        <td><?php echo $row["occurDate"]; ?></td>
        <td><?php echo $row["crimeLocation"]; ?></td>
        <td><?php echo $row["officerID"]; ?></td>
        <td><?php echo $row["citizenID"]; ?></td>
    </tr>
    <?php }?>
    </table>
    <?php }
    else{
      echo "No results were found";
    }
    ?>
        
    
</body>
</html>
                    