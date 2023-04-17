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

//storing the form data into variables
if(isset($_POST['save']))
{
    $oldUserEmail = mysqli_real_escape_string($db, $_POST['oldusername']);
    $newUserEmail = mysqli_real_escape_string($db, $_POST['newusername']);
    $userPassword = mysqli_real_escape_string($db, $_POST['password']);
    $confirm = mysqli_real_escape_string($db, $_POST['confirmPassword']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    $id = mysqli_real_escape_string($db,$_POST['userID']);
    $name = mysqli_real_escape_string($db,$_POST['fullName']);
    $storePassword = $userPassword;

    //Checking the data for errors
    if(!filter_var($oldUserEmail, FILTER_VALIDATE_EMAIL))
    {
        $oldUserError = "Please insert a valid email";
    }
    else if(!filter_var($newUserEmail, FILTER_VALIDATE_EMAIL))
    {
        $newUserError = "Please insert a valid email";
    }

    else if(strlen($userPassword)< 8)
    {
        $passError = "Please ensure the password has a minimum of 8 characters ";
    }

    else if($userPassword != $confirm)
    {
        $passError = "Error: The two passwords do not match";
    } 
    else if (strlen($id)!=6)
    {
        $IDError = "Please ensure that the ID has 6 characters";
    }
    //If the data is ok then check for the specified old credentials
    else if(filter_var($oldUserEmail,FILTER_VALIDATE_EMAIL))
    {
        $storedData = mysqli_query($db, "SELECT Username,ID FROM systemusers WHERE Username = '" . $oldUserEmail . "'");
        $user = mysqli_fetch_array($storedData);
        $userName = $user['Username'];
        $personOldID = $user['ID'];
        $storedID = mysqli_query($db, "SELECT ID FROM systemusers WHERE ID = '" . $id . "'");
        $foundID = mysqli_fetch_array($storedID);
        $otherID = $foundID['ID'];
        //Update the details if the old credentials were found
        if($oldUserEmail == $userName)
        {
            if($personOldID == $id || $id != $otherID)
            {
            $storePassword = md5($userPassword);

            mysqli_query($db, "UPDATE systemusers SET Username = '" . $newUserEmail . "', Password ='" . $storePassword . "' , Role ='" . $role . "', ID ='".$id."', fullName ='".$name."' WHERE Username ='" . $oldUserEmail . "'");
            echo '<script>alert("The user information has been successfully updated")</script>';
            echo "<meta http-equiv='refresh' content='0'>";
            }
            //Check if the new ID belongs to an already existing user who isn't the user with the specified old credentials that are to be updated
            else if($personOldID != $id && $id == $otherID){
                echo '<script>alert("Could not update the information")</script>';
                $IDError = "This ID is already being used by a different user";
            }
        }
        //Return an error if the specified old credentials are not found 
        else
        {
            echo '<script>alert("Could not update the information")</script>';
            $oldUserError = "This username could not be found and as such cannot be updated";
        }
    }
}

?>

<html>
    <head>
        <title>Update user information</title>
        <link rel="stylesheet" href="updateRecord.css">
    </head>
    <body>
        <!--form section-->
       <div class="main">
        <h1> Update User Details </h1>
        <p> Please insert the old username, new username , new password ,new role, ID and full name for the user: </p> 

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="oldUserEmail">
                <label>Old username</label>
                <br>
                <input type="email" name="oldusername" class="inputField" value = "<?php if(isset($_POST["oldusername"])) echo $_POST["oldusername"];?>"required/>
                <br>
                <span> <?php if(isset($oldUserError)) echo $oldUserError; ?></span>
            </div>
            <br>
            <div class="newUserEmail">
                <label>New username</label>
                <br>
                <input type="email" name="newusername" class="inputField"value = "<?php if(isset($_POST["newusername"])) echo $_POST["newusername"];?>" required/>
                <br>
                <span> <?php if(isset($newUserError)) echo $newUserError; ?></span>
            </div>
            <br>

            <div class="userPassword">
                <label>New password</label>
                <br>
                <input type="password" name="password" class="inputField" required/>
                <br>
                <span><?php if(isset($passError)) echo $passError; ?></span>
            </div>
            <br>

            <div class="confirmPassword">
                <label>Confirm new password</label>
                <br>
                <input type="password" name="confirmPassword" class="inputField" required/>
                <br>
                <span><?php if(isset($passError)) echo $passError; ?></span>
            </div>
            <br>

            <div class="userRole">
                <label>User's new role</label>
                <br>
                <select name="role" id="role">
                    <option value="User" class="inputField"> User </option>
                    <option value= "Admin" class="inputField"> Admin </option>
                </select>
                
            </div>
            <div class ="userID">
                <label>User ID </label>
                <br>
                <input type="number" name="userID" class="inputField" value = "<?php if(isset($_POST["userID"])) echo $_POST["userID"];?>" required/>
                <br>
                <span> <?php if(isset($IDError)) echo $IDError; ?></span>
            </div>
            <br>
            <div class ="fullName">
                <label>Full Name </label>
                <br>
                <input type="text" name="fullName" class="inputField" value = "<?php if(isset($_POST["fullName"])) echo $_POST["fullName"];?>" required/>
                <br>
            </div>

            <br>
            <div class="send">
                <input type="submit" name="save" id="update" value="Update user details"/>
                <br>
                <input type ="button" name="back" id="back" value = "Back to main page" onclick="window.location.href='adminHomePage.php';"/>
            </div>
        </div>
    </body>
</html>