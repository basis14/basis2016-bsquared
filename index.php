<?php

include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php'; // functions.php
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/db_connect_visitor.php'; // visitor_database_connect
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/UserModules.php';

secureSessionStart();
$logged = checkIfLoggedIn($db);
?>

<!DOCTYPE html>
<!--
  Aaron Young, Megan Palmer, Lucas Mathis, Peter Atwater, Sherri Miller
  Bob Fisher, Kathy Pratt, James Gibbs, Tanya Ulrich, Kyle Cleven, Jason Kessler-Holt
  Source for Navigation: http://cssmenumaker.com/
  Source for Hashing Algorithm: http://pajhome.org.uk/crypt/md5/sha512.html
  Source for Back-End(Tutorial):http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
  Source for Login Page: http://www.24psd.com/css3-login-form-template/
  Inspired by: http://www.noahglushien.com/
  FAQ Source: http://www.snyderplace.com/demos/collapsible.html

  CREATIVE COMMONS- All social media link and images used fall under CC
  http://creativecommons.org/licenses/by/3.0/legalcode


  The MIT License (MIT)

  Copyright (c) 2016-Present b[squared]

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
-->
<html lang="en">
<head>
  <title>b[squared] Portfolio | Home</title>
  <meta charset="utf-8">

  <meta name="description" content="This is the Home page for the Bachelor of Applied Science Information Systems (BAS IS) 
  	2016 graduating cohort at Olympic College. Here you are welcomed by the cohort members and invited to review their 
  	portfolios by selecting their image or using the easy-to-use menu navigation. This portfolio website offers highlights 
  	about each graduate, their skills, and project successes. Visit the FAQ and Portfolios pages to learn more about this 
  	cohort and purpose of the website. This group of individuals includes Information Technology (IT) and Information 
  	System (IS) professionals with job experience in the following fields: heath care, web design, security, software 
  	development, education, and more. The Portfolios page also includes a form to contact individuals for employment 
  	opportunities, resumes or an overview describing their employment experience. Take this opportunity to be introduced 
  	to possibly your future employee, collaborator, or teammate. This group is prepared to deliver creativity, collaboration, 
  	innovation, security, and more to every project your organization presents them.">

  <meta name="keywords" content="Bachelor of Applied Science, Information Systems, Information Technology, IT, IS, Olympic College, 
      Bremerton, BAS IS 2014, portfolio, 2016 graduate, 2014 cohort, b[squared], bsquared, web development, security, software 
      development, networking">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
    $(document).ready(function(){
       $(".nav ul li a").each(function(){
          $(".active").removeClass("active");
       })
    });  
    </script>
  <script>
    function activeFunction() {
      var currentPage = document.getElementById('homePageActive');
      currentPage.className += ' active';
    }
    function openingState()
    {
      var openingStatement =
          'Please select a photo above to learn more about the person in the photo.'+
          'If you would like to learn more about the BAS IS program'+'<a href="../faq.php">view our FAQ.</p>';

      return $("#descripPar").html("").append(openingStatement);
    }
  </script>
  <?php UserModules::getPageScripts() ?>

</head>
<body onload="activeFunction()" class="container">

<?php UserModules::generateNavigation($db, $logged)?>

<!-- Opening Statement  -->
<p class="topStatement ocLink"><span class="logo">Welcome to b<span class="logoBlack">[</span>squared<span
        class="logoBlack">]</span></span>
  <br>
  <a  href="http://www.olympic.edu/information-systems-bachelor-applied-science-bas" target="_blank">
    Olympic College Bachelors of Applied Science Information Systems</a><br>
  <span class="logoBlack">Cohort 2014-2016 </span>
</p>
<hr id="indexDivider">

<!-- Portraits -->
<?php MakeOpeningSplash($db); ?>

<!-- Closing statement -->
<hr id="indexDivider">
<div class="container">
  <p id="descripPar ocLink" class="descripPar"><span class="logoBlack">Want to know more about the program?</span>
    <a href="faq.php"><br>view the FAQ.</a>
  </p>
</div>

<!-- Footer -->
<?php getFooter(); ?>
</body>
</html>