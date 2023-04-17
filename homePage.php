<?php
session_start();
?>

<html>
    <head>
        <title>OLAP Crime Reporting System</title>
        <link rel="stylesheet" href="homePage.css">
    </head>
    <body>
        <!-- nav bar section-->
        <div id="logoHeader">
            <img src="policeLogo.png" class="logo">
            <div id="navigation">
                <a class="current" href="homePage.php">Home</a>
                <a href="aboutPage.php"id="more">About</a>
                <a href="reportForm.php" id="reportWriting">Write Report</a>
                <a href="mapDisplayTrial.php" id="analysis">View Crime Analytics</a>
                <?php if(!$_SESSION) { ?>
                <button class="login" onclick= "window.location.href='login.php';">Login</button>
                <?php } else { ?>
                    <button class="logout" onclick= "window.location.href='logout.php';">Logout</button>
                    <?php }?>
            </div>
        </div>
        
        <br>
        <!-- main content section-->
        <div id="introduction">
            <img src="geoMap.jfif" class="firstImage">
            <img src="reportForm.webp" class="secondImage">
            <img src="policePicture.jpg" class="thirdImage">
            <img src="crimeImage.jpg" class="fourthImage">
            
            <div id="nextPages">
                <h1><b>Welcome to the Nairobi OLAP Crime Reporting System</b></h1>
            <p class="introductionMessage"> 
                Hello. Please select what you would like to do from the options at the top or from the three buttons below:
            </p>
            <button id="report" onclick= "window.location.href='reportForm.php';">Write a report</button>
            <button id="information" onclick= "window.location.href='aboutPage.php';">Know more about the website</button>
            <button id="analytics" onclick= "window.location.href='mapDisplayTrial.php';">View the analytics of crimes in Nairobi</button>
            </div>
        </div>
        <br>
    <div id="guide">
        <div id="makeReport">
            <h2>Write Report</h2>
            <p>This page will allow police officers to write reports for the various crimes reported by citizens.
                <br>
                <br> Please note that you must be logged in if you want to create a report.
                <br>
                <br> To access this page, please click on the <a href="#reportWriting"class="link">Write Report</a> section at the top of the page.
                <br>
                <br> Alternatively, you could click on the "Write a report" button above.
            </p>
        </div>

        <div id="moreInfo">
            <h2>About</h2>
            <p>This page will allow you to view more information concerning the website such as the functioning of the system and the various actions that users can perform.
                <br>
                <br> To access this page, please click on the <a href="#more" class="link">About</a> section at the top of the page.
                <br>
                <br> Alternatively, you could click on the "Know more about the website" button above.
            </p>
        </div>

        <div id="analysisResults">
            <h2>View Crime Analytics</h2>
            <p>This page will allow you to view the crimes that are most often reported and which part of Nairobi they tend to be reported from.
                <br>
                <br> To access this page, please click on the <a href="#analysis" class="link">View Crime Analytics</a> section at the top of the page.
                <br>
                <br> Alternatively, you could click on the "View the analytics of crimes in Nairobi" button above.
            </p>
        </div>
    </div>
    
    <div id="footer"></div>

    </body>
</html>