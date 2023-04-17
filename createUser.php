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

//Saving details that are being submitted from the form in the body section into variables
if(isset($_POST['save']))
{
    $userEmail = mysqli_real_escape_string($db, $_POST['username']);
    $userPassword = mysqli_real_escape_string($db, $_POST['password']);
    $confirm = mysqli_real_escape_string($db, $_POST['confirmPassword']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    $userID = mysqli_real_escape_string($db,$_POST['userID']);
    $userFullName = mysqli_real_escape_string($db,$_POST['fullName']);
    $storePassword = $userPassword;
    //validate and check on the values given on the form
    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL))
    {
        $userError = "Please insert a valid email";
    }

    else if(strlen($userPassword)< 8)
    {
        $passError = "Please ensure the password has a minimum of 8 characters ";
    }
    else if (strlen($userID)!=6)
    {
        $IDError = "Please ensure that the ID has 6 characters";
    }

    else if($userPassword != $confirm)
    {
        $passError = "Error: The two passwords do not match";
    } 
    else if(filter_var($userEmail,FILTER_VALIDATE_EMAIL))
    {
        //Further checking for errors based on data already stored in the database
        $storedData = mysqli_query($db, "SELECT Username FROM systemusers WHERE Username = '" . $userEmail . "'");
        $storedID = mysqli_query($db, "SELECT ID FROM systemusers WHERE ID = '" . $userID . "'");
        $user = mysqli_fetch_array($storedData);
        $foundID = mysqli_fetch_array($storedID);
        $userName = $user['Username'];
        $presentID = $foundID['ID'];
        
        if($userEmail == $userName)
        {
            echo '<script>alert("Could not save the data")</script>';
            $userError = "This username already has an account";
        }
        else if ($userID==$presentID)
        {
            echo '<script>alert("Could not save the data")</script>';
            $IDError = "This ID is already in use";
        } 
        else
        {
            //If all the data given are ok, the password is encrypted then all the data are stored in a table within the database
            $storePassword = md5($userPassword);

            $send = mysqli_query($db, "INSERT INTO systemusers (Username,Password,Role,ID,fullName) VALUES ('" . $userEmail . "','" . $storePassword . "','" . $role . "', '".$userID."','".$userFullName."')");
            // A javascript echo code to help in informing the admin that the user record has been made
            echo '<script>alert("The user has been successfully created")</script>';
            echo "<meta http-equiv='refresh' content='0'>";
        }  
    }
}

?>

<html>
    <head>
        <title>Add a user</title>
        <link rel="stylesheet" href="createUser.css">
    </head>
    <body>
        <!-- The user record creation form-->
       <div class="main">
        <h1> Add User </h1>
        <p> Please insert the username,password, role, ID and full name for the user: </p> 

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="userEmail">
                <label>Username</label>
                <br>
                <input type="email" name="username" class="inputField" value = "<?php if(isset($_POST["username"])) echo $_POST["username"];?>" required/>
                <br>
                <span> <?php if(isset($userError)) echo $userError; ?></span>
            </div>
            <br>
            <div class="userPassword">
                <label>Password</label>
                <br>
                <input type="password" name="password" class="inputField" required/>
                <br>
                <span><?php if(isset($passError)) echo $passError; ?></span>
                
            </div>
            <br>
            <div class="confirmPassword">
                <label>Confirm password</label>
                <br>
                <input type="password" name="confirmPassword" class="inputField" required/>
                <br>
                <span><?php if(isset($passError)) echo $passError; ?></span>
            </div>
            <br>
            <div class="userRole">
                <label>User's role</label>
                <br>
                <select name="role" id="role" class="options">
                    <option value="User" class="inputField"> User </option>
                    <option value= "Admin" class="inputField"> Admin </option>
                </select>
                
            </div>
            <div class ="userID">
                <label>User ID </label>
                <br>
                <input type="number" name="userID" class="inputField" value = "<?php if(isset($_POST["userID"])) echo $_POST["userID"];?>"required/>
                <br>
                <?php if(isset($IDError)) echo $IDError; ?></span>
            </div>
            <br>
            <div class ="fullName">
                <label>Full Name </label>
                <br>
                <input type="text" name="fullName" class="inputField" value = "<?php if(isset($_POST["fullName"])) echo $_POST["fullName"];?>"required/>
                <br>
            </div>
            <br>
            <div class="send">
                <input type="submit" name="save" value="Create User" id="create"/>
                <br>
                <input type ="button" name="back" value = "Back to main page" id="back" onclick="window.location.href='adminHomePage.php';"/>
            </div>
        </div>
    </body>
</html>