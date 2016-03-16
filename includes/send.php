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

include_once('../includes/functions.php');
include_once('../includes/db_connect_visitor.php');
?>
<!doctype html>
<html>
<head>
    <title>b[squared] Portfolio | FAQ Contact</title>
    
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>
    
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div id="main">
    <?php getVisitorNavigation($db);?>
</div>
<div class="clr"></div>
<br>
<hr>
<section>

    <?php
        // Send mail
        if(isset($_POST['submit']))
        {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
            $unCleanEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $email = filter_var($unCleanEmail, FILTER_VALIDATE_EMAIL);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

            try{
                sendEmail($db, $userID, $email, $name, $content, $subject);
                echo '<p class="descripPar contactReturn">Message Sent! Return to<a href="../index.php"> the Portfolio</a></p>';

            }
            catch(Exception $e)
            {
                echo '<p class="descripPar contactReturn">Message has not been sent.
                Return to<a href="../index.php"> the Portfolio</a></p>';
            }
        }
        ?>
    
</section>
<?php getFooter(); ?>
</body>
</html>

