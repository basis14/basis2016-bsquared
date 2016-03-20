<?php

$userID = $_POST['userID'];

require       'config.php';
include_once  ROOT_DIR.'/includes/db_connect_visitor.php';
include_once  ROOT_DIR.'/includes/functions.php';
include_once  ROOT_DIR.'/includes/UserModules.php';

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
  <title>b[squared] Portfolio | <?php getFirstName($userID,$db)?>Portfolio</title> 
  <meta charset="utf-8">
  <meta name="description" content="This is the Portfolios page for the Bachelor of Applied Science Information Systems (BAS IS) 
        2016 graduating cohort at Olympic College. Here you are able to learn more about the each cohort member, including highlights 
        about their skills, project successes, and review resumes or an overview describing their employment experience. You may contact 
        any one of them regarding employment opportunities by using the contact form. The easy-to-use menu navigates you through 
        the portfolio website, offering additional information about the BAS IS program, Olympic College, welcome message, and the ability
        to ask questions by visiting the Home and FAQ pages. This group of individuals includes Information Technology (IT) and 
        Information System (IS) professionals with job experience in the following fields: heath care, web design, security, 
        software development, education, and more. Take this opportunity to be introduced to possibly your future employee, 
        collaborator, or teammate. This group is prepared to deliver creativity, collaboration, innovation, security, and more 
        to every project your organization presents them.">
  <meta name="keywords" content="Bachelor of Applied Science, Information Systems, Information Technology, IT, IS, Olympic College, 
        Bremerton, BAS IS 2014, portfolio, 2016 graduate, 2014 cohort, b[squared], bsquared, web development, security, software 
        development, networking">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/portfolio_styles.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.css" />
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

  <!-- Stops carousel from automatically changing slides ---- Move Script to JS file in future -- LM -->
  <script>
      $(document).ready(function() {      
         $('.carousel').carousel('pause');

          $("form").valid();

          $(".nav ul li a").each(function(){
              $(".active").removeClass("active");
          })
      });
  </script>
    <script>
    function activeFunction() { 
    var currentPage = document.getElementById('portfolioPageActive');
       currentPage.className += ' active';
    }
    </script>

    <?php UserModules::doOverviewModule($userID, $db);?>
</head>
<body  onload="activeFunction()" class="container">

<?php UserModules::generateNavigation($db, $logged)?>
<br>
<div id="portImgHolder" class="container">
   <div class="row">
      <img class="memberPhoto" src="<?php getProfilePicturesPath($userID, $db)?>"
           alt="<?php getFNameLName($userID, $db)?>">
   </div>
</div>


<div id="myCarousel" class="carousel slide" >

  <!-- Indicators -->
  <?php UserModules::doCarouselIndicator();?>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

    <div id="slide1" class="item active addBG">
      <p><?php getPortfolioStatement($userID, $db) ?></p>
    </div>

    <div id="slide2" class="item">
      <p id="aboutPortfolio"><?php displayOverviewAbout($db, $userID); ?></p>

      <?php UserModules::doAboutModule($db, $userID); ?>
    </div>

    <div id="slide3" class="item">
      <div class="row skillsColumns">
         <?php UserModules::doSkillsModule(4, $db, $userID) ?>
      </div>
      
      <div id="resumeDiv">
      
         <a href='<?php getPortfolioResume($userID, $db) ?>' target="_blank">
            <button class="button button--nina button--text-thick button--text-upper button--size-l" 
                           data-text="Resumé"> <!--onclick="this.form.submit()"-->
                   <span>R</span><span>e</span><span>s</span><span>u</span><span>m</span><span>é</span>
            </button>
         </a>
      </div>
      
    </div>

    <div id="slide4" class="item">
      <h2 id="worksTitle">Select a Project!</h2>
      <p id="descriptionWorks">Hover over a project to gather a brief description, or click the image to see the
          specs!</p>
        <?php UserModules::doProjectModule($db, $userID); ?>
    </div>

    <div id="slide5" class="item">
      <div class="row">
         <div class="col-sm-12">
            <h2>Get in Touch</h2>
            <div id="faqForm" class="container">
               <p>If you would like to contact me, please complete the form below with your name,
                  email address, subject, and a brief message. I will contact you within 24 hours. Thank you.</p>
               <form id="contact" method="post" action="/includes/send.php">
                   <input type="text" id="name" name="name" placeholder="Name" required="required">
                   <br>
                   <input type="email" id="email" name="email" placeholder="Email" required="required">
                   <br>
                   <input type="text" name="subject" placeholder="Subject" required="required">
                   <br>
                   <input type="text" name="userID" value="<?php echo $userID ?>" style="display: none">
                   <textarea name="content" placeholder="Your Message" required="required"></textarea>
                   <br><br>
                   <button class="button button--nina button--text-thick button--text-upper button--size-l"
                           data-text="Send Mail"
                           name="submit"
                           onclick="this.form.submit()">
                       <span>S</span><span>e</span><span>n</span><span>d</span>
                       <span>M</span><span>a</span><span>i</span><span>l</span>
                   </button>
               </form>
            </div>
         </div>
      </div>
    </div>
  </div>

  <!-- Left and right controls -->
    <?php UserModules::doCarouselClick() ?>

  <!-- MODALS -->
  <?php UserModules::doWorksModalModule(10, $db, $userID); ?>

  <!-- Footer Call -->
  <?php getFooter(); ?>

</body>
</html>

