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

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/db_connect_admin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/DatabaseManager.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/functions.php';

secureSessionStart();
$logged = checkIfLoggedIn($db);
?>
<!DOCTYPE html>
<html>
<head>
    <title>B[Squared] Administrator Page</title>
    <meta charset="UTF-8">
    <meta
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="icon" type="image/ico" href="favicon.ico">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
    <div id="main" class="container">
        <?php getMemberNavigation($db, $logged); ?>
        <br><br><br>
        <?php if (loginCheckMember($db) === true) : ?>
        <p><?php echo htmlentities($_SESSION['username']); ?>, Your account has been deleted.
            Thank you for being apart of Olympic College's Cohort 2016 website project!</p>
        <p>
            <?php
                if(isset($_POST['submit']) && is_numeric($_SESSION['user_id']))
                {
                    if($_POST['submit'] == "Delete User")
                    {   
                        $tables = getDatabaseTables();
                        
                        for($i = 0; $i < count($tables); $i++)                        
                        {
                            $result = $db->execute("DELETE FROM $tables[$i] WHERE UserId = ?",
                                DatabaseManager::TYPE_DELETE, array($_SESSION['user_id']));
                        }                        
                    }
                }
            ?>
        </p>
        
        <?php else : ?>
        <p>
            <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
        </p>
        <?php endif; ?>
    </div>
<?php getFooter()?>
</body>
</html>
