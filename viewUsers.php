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
//obtain all data from the database
$systemUsers = mysqli_query($db, "SELECT * FROM systemusers");
?>

<html>
    <head>
        <title> View Users </title>
        <link rel="stylesheet" href="viewUsers.css">
</head>
<body>
  <!-- nav bar section-->
<div id="dashboard">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a href="adminHomePage.php">Dashboard</a>
                <a class="current" href="viewUsers.php"id="seeUsers">View users</a>
                <a href="createUser.php"id="makeUsers">Create user</a>
                <a href="updateRecord.php" id="updateRecords">Update user</a>
                <a href ="deleteUser.php" id="deleteRecord">Delete user</a>
                <a href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
    </div>
</div>
<br>
<br>
<!-- display all the obtained data on a table-->
<?php
    if (mysqli_num_rows($systemUsers)>0){
    ?>
    <table>
      <tr>
        <th>Username</th>
        <th>Password</th>
        <th>Role</th>
        <th>ID</th>
        <th>Full Name </th>
    </tr>
    <?php
    while($row = mysqli_fetch_array($systemUsers)){
      ?>
      <tr>
        <td><?php echo $row["Username"]; ?></td>
        <td><?php echo $row["Password"]; ?></td>
        <td><?php echo $row["Role"]; ?></td>
        <td><?php echo $row["ID"]; ?></td>
        <td><?php echo $row["fullName"]; ?></td>
    </tr>
    <?php }?>
    </table>
    <?php }
    //Return a message if no data is found in the database table
    else{
      echo "No results were found";
    }
    ?>
    </body>
    </html>