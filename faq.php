<?php

//require ('config.php');
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php'; // functions.php
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/db_connect_visitor.php'; // visitor_database_connect
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/UserModules.php';
include_once  $_SERVER['DOCUMENT_ROOT'].'/includes/FormsManager.php';
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
  <title> b[squared] Portfolio | FAQ </title>
    <meta charset="utf-8">
    <meta name="description" content="This is the FAQ page for the Bachelor of Applied Science Information Systems (BAS IS)
        2016 graduating cohort at Olympic College. Here you are able to learn more about the cohort, the BAS IS program,
        Olympic College, and ask questions that are not answered for you by using the contact form. The easy-to-use menu
        navigates you through the portfolio website, offering highlights about each graduate, their skills, and project successes.
        Visit the Home and Portfolios pages to learn more about this cohort. This group of individuals includes Information
        Technology (IT) and Information System (IS) professionals with job experience in the following fields: heath care,
        web design, security, software development, education, and more. The Portfolios page also includes a form to contact
        individuals for employment opportunities, resumes or an overview describing their employment experience. Take this
        opportunity to be introduced to possibly your future employee, collaborator, or teammate. This group is prepared to
        deliver creativity, collaboration, innovation, security, and more to every project your organization presents them.">
      <meta name="keywords" content="Bachelor of Applied Science, Information Systems, Information Technology, IT, IS, Olympic College, 
        Bremerton, BAS IS 2014, portfolio, 2016 graduate, 2014 cohort, b[squared], bsquared, web development, security, software 
        development, networking">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/buttons.css" />
    <link rel="stylesheet" href="css/styles.css" type="text/css" property="">
    <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script>
    $(document).ready(function(){
       $(".nav ul li a").each(function(){
          $(".active").removeClass("active");
       })
    });  
    </script>
    <script>
    function activeFunction() { 
    var currentPage = document.getElementById('faqPageActive');
       currentPage.className += ' active';
    }
    </script>

</head>
<body onload="activeFunction()">
<?php UserModules::generateNavigation($db, $logged)?>


<div id="faqDiv" class="container">
    <div class="row">
      <div class="col-sm-12">
         <h1>F.A.Q.</h1>
      </div>
    </div>
    <div class="panel-group" id="accordion">
        <div class="faqHeader">General Questions</div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">What is b[squared]?</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    Actually, b[squared] represents the name "Binary Beasts," which is our team and cohort name for the duration of the Bachelor of Applied Science Information Systems (BAS IS) program at Olympic College (OC). 
                    We chose the name, in part, because we are all deeply interested in technology and are continually awed by the fact, that all computers truly do is commicate with each other using binary digits. Also, the name Binary
                    Beasts sounds cool!
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">When will b[squared] members be graduating with their BAS in Information Systems?</a>
                </h4>
            </div>
            <div id="collapseTen" class="panel-collapse collapse">
                <div class="panel-body">
                    The b[squared] cohort will be graduating together in SPRING 2016. We are proud to be a member of the first cohort of students in Olympic College's BAS IS program.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">How do I get in contact with a b[squared] member?</a>
                </h4>
            </div>
            <div id="collapseEleven" class="panel-collapse collapse">
                <div class="panel-body">
                    By selecting the Portfolio link in the navigation on the top of any page, a dropdown menu will appear showing each b[squared] member. Click
                    on the member you would like to contact. Next, scroll through the portfolio using the side arrows until you reach "Get In Touch". Complete the form, press "Send Mail"
                    and the cohort member will e-mail you within 24 hours.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">How often will new portfolio items be added to the website?</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    We will be continually working on new projects. We can't fit all of our work in the Portfolio section, but will display the items we think
                    best represents our accomplishments. Each b[squared] member has personally chosen the project examples being displayed.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Can I become a member of the website?</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    Unfortunately, this website is only for current students of the Olympic College BAS Information Systems program.
                    Learn more about the <a href="http://www.olympic.edu/information-systems-bachelor-applied-science-bas" class="faqLink" target="_blank">BAS IS program at OC</a>.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">What courses are part of the BAS IS program?</a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse">
                <div class="panel-body">
                    BAS IS students at Olympic College take a wide range of courses, which are listed <a href="http://www.olympic.edu/computer-information-systems/information-systems-bachelor-applied-science/class-schedule" class="faqLink" target="_blank">here</a>.
                    <br />
                </div>
            </div>
        </div>
    </div>
</div>
   <!-- FAQ Contact Form -->
<div id="faqForm" class="container">
   <?php FormsManager::getFAQContactForm(); ?>
</div>
    <!-- Footer Call -->
<?php
   getFooter();
?>
<script>
    $(document).ready(function(){
       $('button').click(function(){
           $("form").valid();
       })
    });
</script>
</body>
</html>

<?php



if(isset($_POST['submit']))
{
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $unCleanEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $email = filter_var($unCleanEmail, FILTER_VALIDATE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    $message = "<h2>Hello here is a message from ".$_SERVER['SERVER_NAME']."</h2><hr>
					<p><strong>Name:</strong> ".$name."</p>
					<p><strong>Email:</strong> ".$email."</p>
					<p><strong>Message:</strong> ".$content."</p>";

    try{
        mail("binarybeasts@gmail.com",$subject, $message, "Content-type: text/html; charset=utf-8 \r\n");
        echo "<script>alert('message sent!');</script>";
    }
    catch(Exception $e)
    {
        // Message here.
    }
}
?>