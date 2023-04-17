<?php
session_start();
//Connecting to the database
define ('DBSERVER','localhost');
define ('DBUSERNAME','root');
define ('DBPASSWORD','');
define ('DBNAME','olapsystemdb');

$db = mysqli_connect(DBSERVER,DBUSERNAME,DBPASSWORD,DBNAME);

//confirming the connection
if($db === false)
{
    die("Error: Could not connect".mysqli_connect_error());
}

//Error checking if the admin tries to delete a record that he or she has specified
if(isset($_POST['delete']))
{
    $user = mysqli_real_escape_string($db, $_POST['username']);
    if(!filter_var($user, FILTER_VALIDATE_EMAIL))
    {
        $userError = "Please insert a valid email";
    } 
    else 
    {
        $checker = mysqli_query($db, "SELECT Username FROM systemusers WHERE Username = '" . $user . "'");
        $userCheck = mysqli_fetch_array($checker);
        $foundUser = $userCheck['Username'];
        //If the email is found in the specified database table then it is deleted
        if ($foundUser == $user) 
        {
            $delete = mysqli_query($db, "DELETE FROM systemusers  WHERE Username = '" . $user . "'");
            echo '<script>alert("The user has been deleted from the system")</script>';
        } 
        //if the email is not found in the specified database table then an error is returned in the form of a Javascript echo
        else 
        {
            echo '<script>alert("This username could not be found: User may have already been deleted")</script>';
        }
    }
}
?>

<html>
    <head>
        <title> Delete a user account </title>
        <link rel="stylesheet" href="deleteUser.css">
    </head>
    <body>
        <!-- deletion form-->
        <div class="main">
            <h1> Delete a User </h1>
            <p> Please insert the username for the account you want to delete: </p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
             <div class="userEmail">
                <label>Username</label>
                <br>
                <input type="email" class="inputField" name="username" required/>
                <br>
                <span> <?php if(isset($userError)) echo $userError; ?></span>
             </div>
             <br>
             <div class="delete">
                <input type="submit" name="delete" id="delete" value="Delete User"/>
                <br>
                <input type ="button" name="back" id="back" value = "Back to main page" onclick="window.location.href='adminHomePage.php';"/>
            </div>
