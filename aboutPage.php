<?php
session_start();
?>

<html>
    <head>
        <title>About Page</title>
        <link rel="stylesheet" href="aboutPage.css">
    </head>
    <body>
        <!-- nav bar section-->
        <div id="logoHeader">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a href="homePage.php">Home</a>
                <a class="current" href="aboutPage.php"id="more">About</a>
                <a href="reportForm.php" id="reportWriting">Write Report</a>
                <a href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <?php if(!$_SESSION) { ?>
                <button class="login" onclick= "window.location.href='login.php';">Login</button>
                <?php } else { ?>
                    <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
                    <?php }?>
            </div>
        </div>
        <!-- main section-->
        <div id="content">
           <div id="welcome">
            <img src="about.jpg">
            <h1><u>Welcome to the About Page</u></h1>
            <p> Here you will learn more concerning the system and the various interactions that users can take part in.<br><br>
             Please note that different users have different actions that they can perform, therefore not all users will be allowed to perform some of the functions discussed on this page.<br><br>
             The main categories of users who are expected to use the system are citizens residing in Nairobi, police officers and system admins. <br><br>
             The actions and functions designated to these three roles are as discussed below: </p>
            </div>
            <div id="otherUsers">

            <div id="normalUsers">
                <img src="Kenyans.jpg">
                <h2> Nairobi Citizens </h2>
                <hr>
                <p> The citizens residing in Nairobi make up one of the three categories of users who are expected to interact with the system.
                 As a citizen, you are not allowed to log in, only system admins and police officers are allowed to log in. <br><br>
                 Citizens are also not allowed to create crime reports since to make a crime report, one needs to log in and be verified as an authorized police officer. <br>
                 This is done so as to ensure that only legitimate records that can be accounted for by police officers are created. <br><br>
                 This in turn ensures that the data used for reports and displaying on the map are less biased or falsified.
                 Citizens are however free to view the crime data represented on the geo map as well as view the system generated reports. <br><br>
                 This will allow citizens to have the general knowledge of the crime situation around Nairobi. To access said information, 
                 you only need to click on the "View Crime Analytics" option at the top on the page right next to the login button. </p>
            </div>

            <div id="policeOfficers">
                <img src="police.jpg" class="police">
                <h2> Police Officers </h2>
                <hr>
                <p> Police officers make up the next category of expected users of the system. They are allowed to log into the system so as to create the crime reports <br><br>
                To log in as a police officer, please click on the login button at the top right of the page. Once you are redirected to the log in page, insert your credentials. <br><br>
                Your credentials are generally your username and password. Once logged in, click on the "Write Report" option at the top section of the page and proceed to fill in the details.<br><br>
                Police officers are also allowed to view the geo map with crime information as well as the system generated reports.<br><br>
                To do so, you should click on the "View Crime Analytics" options on the top right section of the page right next to the logout button.<br><br> </p>
            </div>

            </div>
            <div id="finalUser">

            <div id="admins">
            <img src="admin.jpg" class="police">
                <h2>System Administrators </h2>
                <hr>
                <p> System administrators, otherwise referred to as admins, receive a different page compared to the officer's page with different functionalities. <br>
                To access this page however, an admin is required to log in by clicking on the login button at the top right section of the page and filling in his or her correct credentials. <br><br>
                Once logged in, the admin will be introduced to a dashboard populated with a table holding data concerning all reported crimes. <br>
                By clicking on the second option located at the top left side of the screen labeled "View Users", the admin will be able to view all of the registered or authorized system users. <br><br>
                Admins also have the right to create new authorized user records as well as to update and delete both new and old records.<br>
                To perform any of these actions, the admin is only required to click on the options provided based on what they want to do.
                For example, if an admin wants to create a user record, then he or she should click on the "Create User" option.<br><br>
                The admin is also allowed to view the geo map with crime information as well as the system generated reports similar to the other user categories.<br>
                To do so, the admin should click on the "View Crime Analytics" option which is the last option located right next to the logout button. </p> 
            </div>
        </div>
        
        </div>
        <div id="footer"></div>
    </body>
</html>