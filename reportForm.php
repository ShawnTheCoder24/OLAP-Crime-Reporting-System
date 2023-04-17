<?php
session_start();
//connect to the database
define ('DBSERVER','localhost');
define ('DBUSERNAME','root');
define ('DBPASSWORD','');
define ('DBNAME','olapsystemdb');

$db = mysqli_connect(DBSERVER,DBUSERNAME,DBPASSWORD,DBNAME);

//check connection
if($db === false)
{
    die("Error: Could not connect".mysqli_connect_error());
}

//Automating the caseID variable so as to avoid having officers manually inputting the case ID which could lead to errors
$caseNumbers = mysqli_query($db, "SELECT COUNT(incident) FROM casetable HAVING COUNT(incident)>0");
$totalCaseNumber = mysqli_fetch_array($caseNumbers);
$specificID = $totalCaseNumber["COUNT(incident)"];
$currentID = $specificID + 1;

//storing data into variables once submitted from the form
if(isset($_POST['submit']))
{
   $caseID = mysqli_real_escape_string($db, $_POST['caseId']); 
   $crimeName = mysqli_real_escape_string($db, $_POST['crime']);
   $reportDate = mysqli_real_escape_string($db, $_POST['reportDate']);
   $occurDate = mysqli_real_escape_string($db, $_POST['occurenceDate']);
   $location = mysqli_real_escape_string($db, $_POST['location']);
   $citizenRole = mysqli_real_escape_string($db, $_POST['role']);
   $citizenFirstName = mysqli_real_escape_string($db, $_POST['firstName']);
   $citizenLastName = mysqli_real_escape_string($db, $_POST['lastName']);
   $citizenID = mysqli_real_escape_string($db, $_POST['citizenID']);
   $citizenPhoneNumber = mysqli_real_escape_string($db, $_POST['phoneNumber']);
   $citizenEmail = mysqli_real_escape_string($db, $_POST['email']);
   $officerReport = mysqli_real_escape_string($db, $_POST['officerDescription']);
   $citizenStatement = mysqli_real_escape_string($db, $_POST['personDescription']);

// Obtain the oficer's name and ID from the database using the session details
   $officerEmail = $_SESSION['user'];

   $pick = mysqli_query($db,"SELECT ID,fullName FROM systemUsers WHERE Username = '".$officerEmail."'");
   $dataFound = mysqli_fetch_array($pick);
   $currentOfficerID = $dataFound['ID'];
   $officerFullName = $dataFound['fullName'];

   //Error checking
   if (strlen((string)$citizenID)!= 8)
   {
     $citizenIDError = "The ID value must have 8 characters";
   }
   else if (!filter_var($citizenEmail, FILTER_VALIDATE_EMAIL))
   {
      $emailError = "Please insert a valid email";
   }
   else if (strlen((string)$citizenPhoneNumber)< 10)
   {
     $phoneError = "Please insert a valid phone number";
   }
   else if($occurDate > $reportDate)
   {
     $dateError = "Date of occurence cannot be beyond the date of reporting";
   }

//if the data is fine then insert all data into the correct tables within the database
   else
   {
     $submit =mysqli_query($db, "INSERT INTO citizentable  VALUES ('".$citizenID."','".$citizenFirstName."','".$citizenLastName."','".$citizenPhoneNumber."','".$citizenEmail."')");
     $save= mysqli_query($db, "INSERT INTO officertable VALUES ('".$currentOfficerID."','".$officerFullName."')");
     $send = mysqli_query($db, "INSERT INTO casetable  VALUES ('".$caseID."','".$crimeName."','".$reportDate."','".$occurDate."','".$location."','".$currentOfficerID."','".$citizenID."' )");
     $reportSave = mysqli_query($db, "INSERT INTO citizenreporttable VALUES ('".$caseID."','".$citizenID."','".$citizenRole."','".$citizenStatement."')");
     $officerReportSave= mysqli_query($db, "INSERT INTO officerreporttable VALUES ('".$caseID."','".$currentOfficerID."','".$officerReport."')");
     echo '<script>alert("The data has successfully been stored")</script>';
     echo "<meta http-equiv='refresh' content='0'>";
   }
}
?>

<html>
    <head>
        <title> Report Page </title>
        <link rel="stylesheet" href="reportForm.css">
    </head>
    <body>
     <!-- nav bar section-->   
    <div id="logoHeader">
            <img src="policeLogo.png" class="logo">
             <div id="navigation">
                <a href="homePage.php" id="homePage">Home</a>
                <a href="aboutPage.php"id="more">About</a>
                <a class = "current" href="reportForm.php" >Write Report</a>
                <a href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <?php if(!$_SESSION) { ?>
                <button class="login" onclick= "window.location.href='login.php';">Login</button>
                <?php } else { ?>
                    <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
                    <?php }?>
             </div>
    </div>
<!-- countercheck if the user is logged in since only the police are meant to access the form-->
    <?php if(!$_SESSION) { ?>
      <style>
        body{
          background-color: #d3d3d3;
        }
      </style>
        <div id="content">
        <p>To access the crime report form, you must be logged in </p>
        <p> Please log in from the login button above or from the one below: </p>
                <button class="loginButton" onclick= "window.location.href='login.php';">Login</button>
        </div>
    <?php } else { ?>
      <!-- form content-->
            <div id="reportContent">
                    <p> Please insert the following details (Aside from Case ID): </p>
                    <form method = "POST" action="">
                    <label>Case ID: </label>
                     <br>
                     <input type="text" name="caseId" class="inputField" value=<?php echo $currentID; ?> readonly/>
                     <br>
                     <label>Incident: </label>
                     <br>
                     <input type="text" name="crime" class="inputField" value = "<?php if(isset($_POST["crime"])) echo $_POST["crime"]; ?>" required/>
                     <br>
                     <label>Date of reporting: </label>
                     <br>
                     <input type="date" name="reportDate" class="inputField" value = "<?php if(isset($_POST["reportDate"])) echo $_POST["reportDate"];?>" required/>
                     <br>
                     <span> <?php if(isset($reportDateError)) echo $reportDateError; ?></span>
                     <br>
                     <label>Date of occurence: </label>
                     <br>
                     <input type="date" name="occurenceDate" value = "<?php if(isset($_POST["occurenceDate"])) echo $_POST["occurenceDate"];?>" class="inputField" required/>
                     <br>
                     <span> <?php if(isset($dateError)) echo $dateError; if(isset($occurDateError)) echo $occurDateError; ?></span>
                     <br>
                     <label>Location: </label>
                     <br>
                     <input type="text" name="location" class="inputField" value = "<?php if(isset($_POST["location"])) echo $_POST["location"];?>" required/>
                     <br>
                     <p> What is the reporting citizen's role? </p>
                     <input type = "radio" id="witness" name="role" value="Witness" checked>
                     <label for ="witness">Witness</label>
                     <input type = "radio" id="victim" name="role" value="Victim">
                     <label for ="victim">Victim</label>
                     <input type = "radio" id="friend" name="role" value="Friend">
                     <label for ="friend">Friend</label>
                     <input type = "radio" id="colleague" name="role" value="Colleague">
                     <label for ="colleague">Colleague</label>
                     <input type = "radio" id="parent" name="role" value="Parent">
                     <label for ="parent">Parent</label>
                     <input type = "radio" id="guardian" name="role" value="Guardian">
                     <label for ="guardian">Guardian</label>
                     <input type = "radio" id="relative" name="role" value="Relative">
                     <label for ="relative">Relative</label>
                     <br>
                     <p> Victim/Citizen's details: </p>
                     <label>First name: </label>
                     <br>
                     <input type="text" name="firstName" class="inputField" value = "<?php if(isset($_POST["firstName"])) echo $_POST["firstName"];?>" required/>
                     <br>
                     <label>Last name: </label>
                     <br>
                     <input type="text" name="lastName" class="inputField" value = "<?php if(isset($_POST["lastName"])) echo $_POST["lastName"];?>" required/>
                     <br>
                     <label>ID number: </label>
                     <br>
                     <input type="number" name="citizenID" class="inputField" value = "<?php if(isset($_POST["citizenID"])) echo $_POST["citizenID"];?>" required/>
                     <br>
                     <span> <?php if(isset($citizenIDError)) echo $citizenIDError; ?></span>
                     <br>
                     <label>Phone Number: </label>
                     <br>
                     <input type="number" name="phoneNumber" class="inputField" value = "<?php if(isset($_POST["phoneNumber"])) echo $_POST["phoneNumber"];?>" required/>
                     <br>
                     <span> <?php if(isset($phoneError)) echo $phoneError; ?></span>
                     <br>
                     <label>Email: </label>
                     <br>
                     <input type="email" name="email" class="inputField" value = "<?php if(isset($_POST["email"])) echo $_POST["email"];?>" required/>
                     <br>
                     <span> <?php if(isset($emailError)) echo $emailError; ?></span>
                     <br>
                     <p> Description of the crime: </p>
                     <label>Reporting Officer's Analysis: </label>
                     <br>
                     <textarea name="officerDescription" class="inputDescription" required> <?php if(isset($_POST["officerDescription"])) echo $_POST["officerDescription"];?> </textarea>
                     <br>
                     <label>Victim/Citizen's Statement: </label>
                     <br>
                     <textarea name="personDescription" class="inputDescription" required> <?php if(isset($_POST["personDescription"])) echo $_POST["personDescription"];?> </textarea>
                     <br><br>
                     <input type="submit" name="submit" value="Send Data" class="storeData">

                    <?php }?>
         </div>
    </body>
    </html>
