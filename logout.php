<?php
session_start();
//Destroying the session if the user is logged in and clicks on the logout button
if($_SESSION['user'])
{
    session_destroy();
}
    header("Location: homePage.php");
?>