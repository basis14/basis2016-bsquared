<?php
/**
 * Aaron Young, Megan Palmer, Lucas Mathis, Peter Atwater, Sherri Miller
 * Bob Fisher, Kathy Pratt, James Gibbs, Tanya Ulrich, Kyle Cleven, Jason Kessler-Holt
 * Source for Navigation: http://cssmenumaker.com/
 * Source for Hashing Algorithm: http://pajhome.org.uk/crypt/md5/sha512.html
 * Source for Back-End(Tutorial):http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
 * Source for Login Page: http://www.24psd.com/css3-login-form-template/
 * Inspired by: http://www.noahglushien.com/
 * FAQ Source: http://www.snyderplace.com/demos/collapsible.html
 *
 * CREATIVE COMMONS- All social media link and images used fall under CC
 * http://creativecommons.org/licenses/by/3.0/legalcode
 *
 *
 *    The MIT License (MIT)

 * Copyright (c) 2016-Present b[squared]

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/change_password.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/FormsManager.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/db_connect_member.php';

//secureSessionStart(); // call to functions.php secure session.
$logged = checkIfLoggedIn($db);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="description" content="BASIS Olympic College"/>
    <title>b[squared] Change Password</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/JavaScript" src="../js/sha512.min.js"></script>
    <script type="text/JavaScript" src="../js/forms.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../js/jquery-ui-1.7.custom.min.js" type="text/javascript"></script>
    <script src="../js/change_password_dialog.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('form:first :input[type="password"]:first').focus();
        });
    </script>
    <script>
    $(document).ready(function(){
       $(".nav ul li a").each(function(){
          $(".active").removeClass("active");
       })
    });  
    </script>
    <script>
    function activeFunction() { 
    var currentPage = document.getElementById('updatePortfolioPageActive');
       currentPage.className += ' active';
    }
    </script>
</head>
<body onload="activeFunction()">
<!-- Registration form to be output if the POST variables are not
set or if the registration script caused an error. -->

<div id="main" class="container">

    <?php
    getMemberNavigation($db, $logged);
    ?>
    <br><br><br>
    <?php if (loginCheckMember($db) == true) : ?>
    <?php

        $type   = "member";
        $table  = "portfolio_members";
        $username = htmlentities($_SESSION['username']);
        $email = getEmail($db, $username, $table);
    ?>
    <p class="userNameDisplay"><?php echo $username; ?>!</p>


    <?php
    FormsManager::getChangePasswordForm($type, $email);
    if (!empty($error_msg))
    {
        echo $error_msg;
    }
    ?>
    <?php else : ?>
        <p>
            <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
        </p>
    <?php endif; ?>
    <?php
    getFooter();
    ?>
    </div>
</body>
</html>