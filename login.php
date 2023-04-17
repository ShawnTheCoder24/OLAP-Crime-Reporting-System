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

//check on errors from the data received from the login form
if(isset($_POST['login']))
{
    $userEmail = mysqli_real_escape_string($db, $_POST['username']);
    $userPassword = mysqli_real_escape_string($db, $_POST['password']);
    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL))
    {
        $userError = "Please insert a valid email";
    }

    if(strlen($userPassword)< 8)
    {
        $passError = "Please ensure the password has a minimum of 8 characters ";
    }

    $storedData = mysqli_query($db, "SELECT * FROM systemusers WHERE Username = '" . $userEmail . "' and Password = '" . md5($userPassword) . "'");

   if( $data = mysqli_fetch_array($storedData))
    {
        $userRole = mysqli_query($db, "SELECT * FROM systemusers WHERE Username ='" . $userEmail . "'");
        $Role = mysqli_fetch_array($userRole);

        $roleFound = (string) $Role['Role'];
        $expectedRole = "User";

        //Redirect users to different pages based on their role
        if($roleFound == $expectedRole)
        {
            header("Location: homePage.php");
        }
        else 
        {
            header("Location: adminHomePage.php");
        }
    }
    //If the given credentials are not found then return an error to the user
    else
    {
        $loginError = "Incorrect email or password";
    }
   
}
?>

<html>
    <head>
        <title>Login Page</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <!-- login form-->
       <div class="main">
        <h1> Login Page </h1>
        <p> Please insert your username and password to progress: </p> 

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
        $_SESSION['user'] = $_POST['username'];
        
        }
        ?>
        <span> <?php if(isset($loginError)) echo $loginError; ?></span>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <br>
            <div class="email">
                <label>Username</label>
                <br>
                <input type="email" name="username" class="inputBar" value = "<?php if(isset($_POST["username"])) echo $_POST["username"];?>" required/>
                <br>
                <span> <?php if(isset($userError)) echo $userError; ?></span>
            </div>
             <br>
            <div class="password">
                <label>Password</label>
                <br>
                <input type="password" name="password" class="inputBar"  required/>
                <br>
                <span><?php if(isset($passError)) echo $passError; ?></span>
                <br>
            </div>
            <br>
            <div class="send">
                <input type="submit" name="login" value="Login" class="loginButton"/>
            </div>
        </div>
    </body>
</html>