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

///////////////////////////////////////////CONNECTIONS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
include_once  $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once 'db_connect_visitor.php';
include_once 'db_connect_admin.php';
include_once 'db_connect_member.php';
define("MAX_COLUMNS", 4);
///////////////////////////////////////////END CONNECTIONS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

///////////////////////////////////////ADMINISTRATIVE CONTROLS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


/**
 * Name: secureSessionStart
 * Purpose: Starts the secure session for the pages and carries over the session for all pages.
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function secureSessionStart()
{
    $sessionName = 'sec_session_id';   // Set a custom session name
    $secure = SECURE_MEMBER;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE)
    {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($sessionName);
    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}


/**
 * Name: loginMember
 * Purpose: General Login Members
 * @param $email
 * @param $password
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  loginMember($email, $password, $db)
{
    // Using prepared statements means that SQL injection is not possible.
    /** @noinspection PhpUndefinedMethodInspection */
    if ($result = $db->execute("SELECT userID, username, password, salt
        FROM portfolio_members
        WHERE email = ?
        LIMIT 1", DatabaseManager::TYPE_SELECT, array($email))[0])
    {

        // get variables from result.
        $userID = $result['userID'];
        $username = $result['username'];
        $dbPassword = $result['password'];
        $salt = $result['salt'];


        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if (count($userID) > 0)
        {
            // If the user exists we check if the account is locked
            // from too many login attempts

            if (checkBruteMember($userID, $db) == true)
            {
                // Account is locked
                // Send an email to user saying their account is locked
                echo "Account Locked";
                return false;
            }
            else
            {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($dbPassword == $password)
                {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $userBrowser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $userID = preg_replace("/[^0-9]+/", "", $userID);
                    $_SESSION['user_id'] = $userID;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "",
                        $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',
                        $password . $userBrowser);
                    // Login successful.
                    return true;
                }

                // Password is not correct
                // We record this attempt in the database
                echo "Incorrect Password";
                $now = time();
                /** @noinspection PhpUndefinedMethodInspection */
                $db->execute("INSERT INTO login_attempts(userID, time)
                                  VALUES (?, '$now')", DatabaseManager::TYPE_INSERT, array($userID));
                return false;
            }
        }
    }
    return false;
}


/**
 * Name: loginAdmin
 * Purpose: General Login Admin
 * @param $email
 * @param $password
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  loginAdmin($email, $password, $db)
{
    // Using prepared statements means that SQL injection is not possible.
    /** @noinspection PhpUndefinedMethodInspection */
    if ($result = $db->execute("SELECT userID, username, password, salt
        FROM admin
       WHERE email = ?
        LIMIT 1", DatabaseManager::TYPE_SELECT,array($email))
    )
    {


        // get variables from result.
        $userID = $result[0]['userID'];
        $username = $result[0]['username'];
        $dbPassword = $result[0]['password'];
        $salt = $result[0]['salt'];

        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if (count($result) == 1)
        {
            // If the user exists we check if the account is locked
            // from too many login attempts

            if (checkBruteAdmin($userID, $db) == true)
            {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            }
            else
            {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($dbPassword == $password)
                {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $userBrowser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $userID = preg_replace("/[^0-9]+/", "", $userID);
                    $_SESSION['user_id'] = $userID;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "",
                        $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',
                        $password . $userBrowser);
                    // Login successful.
                    return true;
                }

                // Password is not correct
                // We record this attempt in the database
                $now = time();
                $db->execute("INSERT INTO login_attempts_admin(userId, time)
                                  VALUES (?, '$now')",
                                  DatabaseManager::TYPE_INSERT, array($userID));
                return false;
            }
        }
        else
        {
            // No user exists.
            return false;
        }
    }
    return false;
}


/**
 * Name: checkBrute_Member
 * Purpose: Checks occurrences in failed login attempts to member login.
 * @param $userID
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  checkBruteMember($userID, $db)
{
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $validAttempts = $now - (2 * 60 * 60);

    /** @noinspection PhpUndefinedMethodInspection */
    if ($result = $db->execute("SELECT time
                                FROM login_attempts
                                WHERE userId = ?
                                AND time > '$validAttempts'",
                                DatabaseManager::TYPE_SELECT,array($userID)))
    {

        // If there have been more than 5 failed login attempts
        if (intval($result['time']) > 5)
        {
            return true;
        }
    }
    return false;
}

/**
 * Name: checkBrute_admin
 * Purpose: Check occurrences in failed login attempts to admin login.
 * @param $userID
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  checkBruteAdmin($userID, $db)
{
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $validAttempts = $now - (2 * 60 * 60);

    /** @noinspection PhpUndefinedMethodInspection */
    if ($result = $db->execute("SELECT time
                             FROM login_attempts_admin
                             WHERE userID = ?
                            AND time > '$validAttempts'",
                            DatabaseManager::TYPE_SELECT,array($userID))
    )
    {

        // If there have been more than 5 failed logins
        if (count($result) > 5)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    return false;
}


/**
 * Name: loginCheckMember
 * Purpose: Used to check if the member is logged in for display purposes (Navigation)
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  loginCheckMember($db)
{
    // Check if all session variables are set
    if (isset($_SESSION['user_id'],
        $_SESSION['username'],
        $_SESSION['login_string']))
    {

        $userID = $_SESSION['user_id'];
        $loginString = $_SESSION['login_string'];

        /** @noinspection PhpUndefinedMethodInspection */
        if ($result = $db->execute("SELECT password
                                    FROM portfolio_members
                                    WHERE userId = ? LIMIT 1",
                                    DatabaseManager::TYPE_SELECT, array($userID)))
        {
            if (count($result) == 1)
            {

                // Get the user-agent string of the user.
                $userBrowser = $_SERVER['HTTP_USER_AGENT'];
                // If the user exists get variables from result.
                $password = $result[0]['password'];
                $loginCheck = hash('sha512', $password . $userBrowser);

                if ($loginCheck == $loginString)
                {
                    // Logged In!!!!
                    return true;
                }
                else
                {
                    // Not logged in
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}


/**
 * Name: loginCheckAdmin
 * Purpose: Checks if the user logged in is an administrative user.
 * @param $db
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  loginCheckAdmin($db)
{
    // Check if all session variables are set

    if (isset($_SESSION['user_id'],
        $_SESSION['username'],
        $_SESSION['login_string']))
    {

        $userID = $_SESSION['user_id'];
        $loginString = $_SESSION['login_string'];

        /** @noinspection PhpUndefinedMethodInspection */
        if ($result = $db->execute("SELECT password
                                      FROM admin
                                      WHERE userID = ? LIMIT 1",
                                      DatabaseManager::TYPE_SELECT,array($userID)))
        {
            if (count($result) == 1)
            {
                // Get the user-agent string of the user.
                $userBrowser = $_SERVER['HTTP_USER_AGENT'];

                // If the user exists get variables from result.
                $password = $result[0]['password'];

                $loginCheck = hash('sha512', $password . $userBrowser);

                if ($loginCheck == $loginString)
                {
                    // Logged In!!!!
                    return true;
                }
            }
        }
    }
    return false;
}


/**
 * Name: escURL
 * Purpose: returns a url without the directory information.
 * @param $url
 * @return mixed|string
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  escURL($url)
{

    if ('' == $url)
    {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '',
            $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string)$url;

    $count = 1;
    while (!empty($count))
    {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/')
    {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    }
    else
    {
        return $url;
    }
}


/**
 * Name: checkEmail
 * Purpose: Check the e-mail of a user and return the results as an assoc array. ['email']
 * @param $email
 * @param $db
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  checkEmail($email, $db)
{
    $error = array('status'=>false,'userId'=>0);

    if (isset($email) && trim($email) != '')
    {
        if ($sql = $db->execute("SELECT userId
                                 FROM portfolio_members
                                 WHERE email = ?",
          DatabaseManager::TYPE_SELECT, array($email)))
        {
            $userId = NULL;
            foreach($sql as $row)
            {
                $userId = $row['userId'];
            }
            $numRows = count($sql);
            if ($numRows >= 1) if (!empty($userId))
            {
                return array('status'=>true,'userID'=>$userId);
            }
        }
    }
    return $error;
}


/**
 * Name: getRandomPassword
 * Purpose: Generates a random password to the user for password reset.
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getRandomPassword()
{
    $specialChars  =
        ["!", "@", "#", "$", "%", "^", "&", "*",
        "(", ")", "-","_", "~", "`"];

    $specialLength = count($specialChars);
    $alpha         = array_merge(range('A','Z'), range('a', 'z'), range(0,100));
    $alphaLength   = count($alpha); // Returns 153 stored in array.
    $generatedPass = "";

    for($i = 0; $i< 4; $i++)
    {
        $n = rand(0, $alphaLength-1); // returns a value from the array based on the length.
        $j = rand(0, $specialLength-1); // returns a value from the array bases on the length.
        $generatedPass .= $alpha[$n]; // Concatenates the value selected by random.
        $generatedPass .= $specialChars[$j];
    }
    return $generatedPass;
}


/**
 * Name: sendPasswordEmail
 * Purpose: Sends the password in an e-mail to the user.
 * @param $userId
 * @param $db
 * @return mixed|void
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  sendPasswordEmail($userId, $db)
{
    $headers = ""; // Initialization of headers.
    $error =  "
      <script>
          alert(\"there was an issue uploading your file! please try again! \")
      </script>";

    /** @noinspection PhpUndefinedMethodInspection */
    if($db->execute("SELECT `username`,`email`,`password`
      FROM `portfolio_members`
      WHERE `userId` = ?",
      DatabaseManager::TYPE_SELECT, array($userId)))
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $db->execute("SELECT `username`, `email`
          FROM portfolio_members
          WHERE userId = ?",
          DatabaseManager::TYPE_SELECT, array($userId));

        // Initialize the variables.
        $username = "";
        $email = "";

        // Collect the results.
        foreach ($result as $row)
        {
            $username = $row["username"];
            $email = $row["email"];
        }

        if (isset($username) && isset($email))
        {
            $randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $randomPassword = getRandomPassword(); // Returns a random password.
            $seenPass = $randomPassword;
            $randomPassword = hash('sha512', $randomPassword . $randomSalt);

            /** @noinspection PhpUndefinedMethodInspection */
            if ($result = $db->execute("UPDATE portfolio_members
                                        SET password = ?, salt = ?
                                        WHERE email = ?",
                DatabaseManager::TYPE_UPDATE, array($randomPassword, $randomSalt, $email)))
            {
                // Execute the prepared query.
                if (!$result)
                {
                    //header('Location: ../error.php?err=Registration failure: INSERT');
                }
            }

            // Create the message and headers below.
            $message = "Dear $username,\r\n";
            $message .= "Please return to the login and use the password provided:\r\n";
            $message .= "-----------------------\r\n";
            $message .= "$seenPass\r\n";
            $message .= "-----------------------\r\n";
            $message .= "Please be sure to copy the entire password for your return.
                                 The password will expire after 3 days for security reasons.\r\n\r\n";
            $message .= "Thanks,\r\n";
            $message .= "-- Our site team";

            $headers .= "From: BASIS b[squared] " . WEBMASTER_EMAIL . " \n";
            $headers .= "To-Sender: \n";
            $headers .= "X-Mailer: PHP\n"; // mailer
            $headers .= "Reply-To: " . WEBMASTER_EMAIL . "\n"; // Reply address
            $headers .= "Return-Path: " . WEBMASTER_EMAIL . "\n"; //Return Path for errors
            $headers .= "Content-Type: text/html; charset=iso-8859-1"; //Enc-type
            $subject = "Your Lost Password";
            mail($email, $subject, $message, $headers);
            echo str_replace("<br>\r\n", "<br/ >", $message);
            //echo '<script>alert("You will be sent your reset link through the e-mail provided");
            // window.location = "../member/login.php";</script>';
            return str_replace("\r\n", "<br/ >", $message);

        }
        else
        {
            return $error;
        }
    }
    return $error;
}

/**
 * Name: getUserName
 * Purpose: Returns a user name.
 * @param $userId
 * @param $db
 * @return
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getUserName($userId, $db)
{
    /** @noinspection PhpUndefinedMethodInspection */
    if ($sql = $db->execute("SELECT `username`
      FROM `portfolio_members` WHERE `userId` = ?",
        DatabaseManager::TYPE_SELECT, array($userId)));
    {
        return $sql['username'];
    }
}


/**
 * Name: getHoverContent
 * Purpose: Retrieves opening information on the index.php page regarding each user.
 * @param $db
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getHoverContent($db, $userID)
{
    /** @noinspection PhpUndefinedMethodInspection */
    $result = $db->execute("SELECT firstName, lastName
      FROM portfolio_profiles
      WHERE userId =?", DatabaseManager::TYPE_SELECT, array($userID));
    if (count($result) > 0)
    {
        // output data of each row
        foreach($result as $row)
        {
            echo "Name: " . $row["firstName"] . " " . $row['lastName'] . "<br>";
        }
    }
    else
    {
        echo "b[squared] Future Member";
    }
}

/**
 * Name: getUserID
 * Purpose: Returns username
 * @param $db
 * @param $username
 * @return mixed
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getUserID($db, $username)
{

    try
    {
        $sql = $db->execute("SELECT `username` FROM `portfolio_members` WHERE `userId` = ?",
          DatabaseManager::TYPE_SELECT, array($username));
        {
            return $sql['userId'];
        }
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        echo '<script>alert("$e->getMessage();")</script>';
        return $e;
    }
}

/**
 * Name: getEmail
 * Purpose: Returns the e-mail of a specified user based off username.
 * @param $db
 * @param $username
 * @param $table
 * @return string
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function getEmail($db, $username, $table)
{
    $email = "";
    $result = $db->execute("SELECT email FROM $table WHERE username=?",
        DatabaseManager::TYPE_SELECT, array($username));

    foreach($result as $row)
    {
        $email = $row['email'];
    }

    return $email;
}


/**
 * Name: performRegistration
 * Purpose: To perform the registration functions for administrative users. This function creates,
 * the member role & the admin role.
 * @param $type
 * @param $db
 * @param $username
 * @param $email
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function performRegistration($type, $db, $username, $email)
{
    $error_msg = "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
        echo $error_msg;
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128)
    {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
        echo $error_msg;
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //


    $result = $db->execute("SELECT userID FROM $type WHERE email = ? LIMIT 1",
        DatabaseManager::TYPE_SELECT, array($email));

    // check existing email
    if (count($result) == 1)
    {
        // A user with this email address already exists
        $error_msg .= '<p class="error">A user with this email address already exists.</p>';
        echo $error_msg;
    }

    // check existing username
    $result = $db->execute("SELECT userID FROM $type WHERE username = ? LIMIT 1",
        DatabaseManager::TYPE_SELECT, array($username));

    if (count($result) == 1)
    {
        // A user with this username already exists
        $error_msg .= '<p class="error">A user with this username already exists</p>';
        echo $error_msg;
    }

    if (empty($error_msg))
    {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

        // Create salted password
        $password = hash('sha512', $password . $random_salt);

        // Insert the new user into the database
        if ($result = $db->execute("INSERT INTO $type (username, email, password, salt) VALUES (?, ?, ?, ?)",
            DatabaseManager::TYPE_INSERT, array($username, $email, $password, $random_salt)))
        {
            // Execute the prepared query.
            if (!$result)
            {
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }
        header('Location: ../admin/register.php');
    }
}


/**
 * Name: changePassword
 * Purpose: function used for users to change their passwords.
 * @param $table
 * @param $db
 * @param $email
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function changePassword($table, $db, $email)
{

    $error_msg = '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        // Not a valid email
        // Make a dialog box for this case.
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128)
    {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        // Make a dialog box for this case.
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //

    $result = $db->execute("SELECT userID FROM $table WHERE email = ? LIMIT 1",
        DatabaseManager::TYPE_SELECT,array($email));

    // check existing email
    if (count($result) < 1)
    {
        // A user with this email address already exists
        // Make a dialog box for this case.
        $error_msg .= '<p class="error">A user with this email address already exists.</p>';
    }

    if (empty($error_msg))
    {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

        // Create salted password
        $password = hash('sha512', $password . $random_salt);

        // Insert the new user into the database
        if ($result = $db->execute("UPDATE $table SET password = ?, salt = ? WHERE email = ?",
            DatabaseManager::TYPE_UPDATE,array($password, $random_salt, $email)))
        {
            if (!$result)
            {
                $responseArray['status'] = 'error';
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }
        if($table == "portfolio_members")
        {

            try
            {
                $responseArray['status'] = 'success';
                header('Location: ../member/change_password.php');
            }
            catch(Exception $e)
            {
                $responseArray['status'] = 'error';
                header('Location: ../member/change_password.php');
            }
        }
        else
        {
            try
            {
                $responseArray['status'] = 'success';
                header('Location: ../admin/change_password.php');
            }
            catch(Exception $e)
            {
                $responseArray['status'] = 'error';
                header('Location: ../admin/change_password.php');
            }

        }
    }
}

/**
 * Name: checkIfLoggedIn
 * Purpose: checks to see if a user is logged in. For navigational purposes (possible duplicate)
 * @param $db
 * @return string
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function checkIfLoggedIn($db){

    if(loginCheckMember($db) === true){
        $logged = "in";
        return $logged;
    }
    elseif(loginCheckAdmin($db)===true)
    {
        $logged = "admin";
        return $logged;
    }
    else{
        $logged="out";
        return $logged;
    }
}

/**
 * Name: sendEmail
 * Purpose: sendsEmail to portfolio members
 * @param $db
 * @param $userID
 * @param $email
 * @param $name
 * @param $content
 * @param $subject
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function sendEmail($db, $userID, $email, $name, $content, $subject)
{

    $result = $db->execute("SELECT email
                                FROM portfolio_members
                                WHERE userID = ?",
        DatabaseManager::TYPE_SELECT, array($userID));


    if (!empty($result))
    {
        foreach ($result as $row)
        {
            $mail = $row['email'];
        }
    }
    else
    {
        $mail = WEBMASTER_EMAIL;
    }


    if (isset($_POST['message']) && isset($mail))
    {
        $message = "<h2>Hello here is a message from " . $_SERVER['SERVER_NAME'] . "</h2><hr>
					<p><strong>Name:</strong> " . $name . "</p>
					<p><strong>Email:</strong> " . $email . "</p>
					<p><strong>Message:</strong> " . $content . "</p>";
        try
        {
            mail($mail, $subject, $message, "Content-type: text/html; charset=utf-8 \r\n");
            echo "<script>alert('message sent!');</script>";
        }
        catch (Exception $e)
        {
            echo "<script>alert('Seems the message didn\'t send please try later.');</script>";
        }
    }
}

/**
 * Name: getListUsers
 * Purpose: Get a full list of users for the administrator to view.
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function getListUsers($db)
{
    $result = $db->execute("SELECT * FROM portfolio_members");
    echo "<table style='width: 40%;
                           border: 1px solid black;
                           border-collapse: collapse;
                           text-align: left;'><tr><th>User Id</th><th>Username</th>";
    foreach($result as $row)
    {
        echo "<tr>
                    <td>".$row['userId']."</td>".
            "<td>".$row['username']."</td>".
            "</tr>";
    }
    echo "</table>";
}

/**
 * Name: deleteFromUsers
 * Purpose: Delete a user throught the administrator view.
 * @param $db
 * @param $userID
 * @uses databaseTables()
 */
function deleteFromUsers($db)
{
    $userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
    echo $userID;
}

///////////////////////////////////////END ADMINISTRATIVE CONTROLS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


/////////////////////////////////////////////LOOKUP TABLES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

/**
 * Name: destinationIDS
 * Purpose: Creates <K,V> store of the values for tables to verify portions.
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  destinationIDS()
{
    $destinationIDs = array(
        1 => "skills_label_1",
        2 => "skills_label_2",
        3 => "skills_label_3",
        4 => "skills_column_1",
        5 => "skills_column_2",
        6 => "skills_column_3",
        7 => "about_column_1",
        8 => "about_column_2",
        9 => "about_column_3",
        10 => "works_thumb_1",
        11 => "works_thumb_2",
        12 => "works_thumb_3",
        13 => "works_thumb_4",
        14 => "works_thumb_5",
        15 => "works_thumb_6",
        16 => "works_thumb_7",
        17 => "works_thumb_8",
        18 => "works_thumb_9",
        19 => "profile",
        20 => "about",
        21 => "statement",
        22 => "about_label_1",
        23 => "about_label_2",
        24 => "about_label_3",
        25 => "works_preview_1",
        26 => "works_preview_2",
        27 => "works_preview_3",
        28 => "works_preview_4",
        29 => "works_preview_5",
        30 => "works_preview_6",
        31 => "works_preview_7",
        32 => "works_preview_8",
        33 => "works_preview_9",
        35 => "resume",
        36 => "portfolio_pictures",
    );

    return $destinationIDs;
}

/**
 * Name: getDatabaseTables
 * Purpose: returns an array with all the tables.
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function getDatabaseTables()
{
    return  ["portfolios_about", "portfolios_statement", "portfolio_columns",
        "portfolio_labels", "portfolio_paths", "portfolio_profiles", "portfolio_works",
        "portfolio_members"];
}
///////////////////////////////////////////END LOOKUP TABLE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\





//////////////////////////////////////////USER UPLOADS & CALLS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


/**
 * Name: moveUpdateFile
 * Purpose: Creates the logic to prepare a move of the file into the server/database.
 * @param $db
 * @param $userID
 * @param $fileTempName
 * @param $fileName
 * @param $table
 * @param $columnName
 * @param $destinationID
 * @param $portionSelected
 * @param $formName
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  moveUpdateFile($db, $userID, $fileTempName, $fileName, $table, $columnName, $destinationID,
                         $portionSelected, $formName)
{
    if(isset($_POST["$portionSelected"]))
    {
        $portionSelected = $_POST[$portionSelected];
    }

    if(isset($portionSelected) && $portionSelected!=NULL)
    {
        $destinationID = formNamePortions($portionSelected, $formName, $columnName);

        try
        {
            unlinkFile($db, $destinationID, $columnName, $table, $userID, $fileTempName, $fileName);
        }
        catch(Exception $e)
        {
            // Add message here.
        }
    }
    else
    {
        try
        {
            unlinkFile($db, $destinationID, $columnName, $table, $userID, $fileTempName, $fileName);
        }
        catch(Exception $e)
        {
            // Add message here.
        }
    }
}

/**
 * Name: unlinkFile
 * Purpose: takes the information prepared by moveUpdateFile and performs php file operations & C&U operations.
 * @param $db
 * @param $destinationID
 * @param $columnName
 * @param $table
 * @param $userID
 * @param $fileTempName
 * @param $fileName
 */
function unlinkFile($db, $destinationID, $columnName, $table, $userID, $fileTempName, $fileName)
{
    for ($i = 0; $i < count($destinationID); $i++)
    {

        $result = $db->execute("SELECT $columnName[$i]
                              FROM $table[$i]
                              WHERE userID = ?
                              AND destination_id = '" . $destinationID[$i] . "'",
            DatabaseManager::TYPE_SELECT, array($userID));

        $resultCount = count($result);

        if(!empty($result))
        {
            foreach($result as $row)
            {
                $oldFile= $row['path'];
                if (file_exists($oldFile))
                {
                    try
                    {
                        unlink($oldFile);
                        move_uploaded_file($fileTempName, $fileName);
                    }
                    catch(Exception $e)
                    {
                         // Add message here.
                    }
                }
            }
        }
        else
        {
            try
            {
                move_uploaded_file($fileTempName, $fileName);
            }
            catch(Exception $e)
            {
                // Add message here.
            }
        }

        if ($resultCount > 0)
        {
            //  6a. Profile already exists.. update
            $db->execute("UPDATE $table[$i]
                          SET  $columnName[$i] = '" . $fileName . "'
                          WHERE userID    = ?
                          AND destination_id='" . $destinationID[$i]."'",
                DatabaseManager::TYPE_UPDATE, array($userID));
        }
        else
        {
            //  6b. Profile doesn't exists.. insert.
            $db->execute("INSERT INTO $table[$i] (userID, $columnName[$i], destination_id)
                        VALUES (?, '" . $fileName . "', '" . $destinationID[$i] . "')",
                DatabaseManager::TYPE_INSERT, array($userID));

            move_uploaded_file($fileTempName, $fileName);
        }
    }
}


/**
 * Name: formNamePortions
 * Purpose: Algorithm for choosing destination_ids.
 * @param $portionSelected
 * @param $formName
 * @param $possibleInput
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  formNamePortions ($portionSelected, $formName, $possibleInput)
{

    $destination = []; // Initialize the destination for storage.
    $destinationID = []; // Initialize array destination_id for return.
    $fields = []; // Initialize the array for the fields to be posted.

    if(isset($_POST["destination_id"]))
    {
        $portionSelected = $_POST["destination_id"];
    }

    for($i=0; $i<sizeof($possibleInput); $i++)
    {
        if(!empty($_POST[$possibleInput[$i]]) || isset($_FILES))
        {
            $inputs = $possibleInput[$i];
            array_push($fields, $inputs);
            unset($inputs);
        }
    }

    foreach($fields as $field)
    {
        if($field == "label")
        {
            //pushes the value to be searched based off the input values only.
            array_push($destination,$formName."_".$field."_".$portionSelected);
        }
        elseif($field == "column_text")
        {
            //pushes the value to be searched based off the input values only.
            array_push($destination,$formName."_column_".$portionSelected);
        }
        else
        {
            //pushes the value to be searched based off else statement since the fields[$i] is a path.
            if($formName == "works" && $field == "path")
            {
                array_push($destination,$formName."_thumb_".$portionSelected);
            }
            else
            {
                array_push($destination,$formName."_label_".$portionSelected);
            }
        }
    }

    for($i = 0; $i<count($fields); $i++)
    {
        $value = array_search($destination[$i], destinationIDS()); // Search the value/store through function.
        array_push($destinationID, $value); // push the value to destination_id array.
    }

    return $destinationID;
}


/**
 * Name: inputValues
 * Purpose: Inserts the values of text input into the database.
 * @param $db
 * @param $userID
 * @param $inputName
 * @param $table
 * @param $columns
 * @param $destinationID
 * @param $portionSelected
 * @param $formName
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  inputValues($db, $userID, $inputName, $table, $columns, $destinationID, $portionSelected, $formName)
{

    if(isset($_POST["$portionSelected"]))
    {
        $portionSelected = $_POST["$portionSelected"];
    }

    if(isset($portionSelected)&& $portionSelected!=NULL)
    {
        if($formName != "works")
        {
            $destinationID = formNamePortions($portionSelected, $formName, $columns);

            for ($i = 0; $i < count($columns); $i++)
            {
                //  Post the input from the users based of the iteration, check to see what was posted.
                if (!empty($_POST["$inputName[$i]"]))
                {
                    $input = $_POST["$inputName[$i]"];

                    /** @noinspection PhpUndefinedMethodInspection */
                    $numberRows = $db->execute("SELECT $columns[$i] FROM $table[$i]
                                                WHERE userID = ?
                                                AND destination_id = $destinationID[$i]",
                        DatabaseManager::TYPE_SELECT, array($userID));

                    //  If the number of rows returned are greater than zero, update the existing information.
                    if (count($numberRows) > 0)
                    {
                        /** @noinspection PhpUndefinedMethodInspection */
                        $db->execute("UPDATE $table[$i]
                                SET $columns[$i] = ?
                                WHERE userID = ?
                                AND destination_id = $destinationID[$i]",
                            DatabaseManager::TYPE_UPDATE, array($input, $userID));
                    }
                    else
                    {
                        /** @noinspection PhpUndefinedMethodInspection */
                        $db->execute("INSERT INTO $table[$i] (userID, $columns[$i], destination_id) VALUES (?, ?, ?)",
                            DatabaseManager::TYPE_INSERT, array($userID, $input, $destinationID[$i]));
                    }
                }
            }
        }
        else
        {
            for ($i = 0; $i < count($columns); $i++)
            {
                // This would be the place to start checking if a portion was selected. Need to add the variable through
                // The function.

                //  Check to see if the inputName is set and not empty.
                if(isset($_POST["$inputName[$i]"]) && !empty($_POST["$inputName[$i]"]))
                {
                    //  Clean the input.

                    if($inputName[$i] == "work_link")
                    {
                        $input = "http://";
                        $site = filter_input(INPUT_POST, $inputName[$i], FILTER_SANITIZE_STRING);
                        $input .=$site;
                    }
                    else
                    {
                        $input = filter_input(INPUT_POST, $inputName[$i], FILTER_SANITIZE_STRING);
                    }
                    
                    $numberRows = $db->execute("SELECT * FROM $table
                                                WHERE userID = ?
                                                AND worksID = $portionSelected",
                        DatabaseManager::TYPE_SELECT, array($userID));

                    //  Check the query based on the number of rows returned. If they are greater than zero. (Change to 1)
                    if (count($numberRows) > 0)
                    {
                        // Update the table with the column information.
                        $db->execute("UPDATE $table
                                      SET $columns[$i] = ?
                                      WHERE userID = ?
                                      AND worksID = $portionSelected",
                            DatabaseManager::TYPE_UPDATE, array($input, $userID));
                    }
                    else
                    {
                        // Insert the information into the table Since no information was found in the query.
                        $db->execute("INSERT INTO $table (userID, worksID, $columns[$i])
                                      VALUES (?,'".$portionSelected."', ?)",
                            DatabaseManager::TYPE_INSERT, array($userID, $input));
                    }
                }
            }
        }
    }
    else
    {
        //checks to see if the destination_id is null or zero is set based off the parameters passed.

        if ($destinationID == NULL || sizeof($destinationID)==0 )
        {

            //  Insert/Update through the iterations of columns provided.
            for ($i = 0; $i < count($columns); $i++)
            {
                // This would be the place to start checking if a portion was selected. Need to add the variable through
                // The function.


                //  Check to see if the inputName is set and not empty.
                if (isset($_POST["$inputName[$i]"]) && !empty($_POST["$inputName[$i]"]))
                {
                    //  Clean the input.
                    $input = $_POST["$inputName[$i]"];

                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $db->execute("SELECT * FROM $table
                                            WHERE userID = ?",
                        DatabaseManager::TYPE_SELECT, array($userID));

                    //  Check the query based on the number of rows returned. If they are greater than zero. (Change to 1)
                    if (count($result) > 0)
                    {
                        // Update the table with the column information.
                        try
                        {
                            /** @noinspection PhpUndefinedMethodInspection */
                            $db->execute("UPDATE $table SET $columns[$i] = ? WHERE userID = ?",
                                DatabaseManager::TYPE_UPDATE, array($input, $userID));
                        }
                        catch(Exception $e)
                        {
                            echo $e->getMessage();
                            echo '<script>alert("$e->getMessage();")</script>';
                        }
                    }
                    else
                    {

                        try
                        {
                            // Insert the information into the table Since no information was found in the query.
                            /** @noinspection PhpUndefinedMethodInspection */
                            $db->execute("INSERT INTO $table (userID, $columns[$i])
                                          VALUES (?, ?)",
                                DatabaseManager::TYPE_SELECT, array($userID, $input));
                        }
                        catch(Exception $e)
                        {
                            echo $e->getMessage();
                            echo '<script>alert("$e->getMessage();")</script>';
                        }
                    }

                }
            }
        }
        else
        {
            for ($i = 0; $i < count($columns); $i++)
            {
                //  Post the input from the users based of the iteration, check to see what was posted.
                if (isset($_POST["$inputName[$i]"]) && !empty($_POST["$inputName[$i]"]))
                {
                    //  Clean the input.
                    $input = $_POST["$inputName[$i]"];

                    $numberRows = $db->execute("SELECT $columns[$i] FROM $table
                                                WHERE userID = ?
                                                AND destination_id = '" . $destinationID . "'",
                        DatabaseManager::TYPE_SELECT, array($userID));

                    //  If the number of rows returned are greater than zero, update the existing information.
                    if (count($numberRows) > 0)
                    {
                        $db->execute("UPDATE $table
                                      SET $columns[$i] = ?
                                      WHERE userID = ?
                                      AND destination_id = '" . $destinationID . "'",
                            DatabaseManager::TYPE_UPDATE, array($input, $userID));
                    }
                    else
                    {
                        $db->execute("INSERT INTO $table (userID, $columns[$i])
                                      VALUES ('" . $userID . "', '" . $input . "')", DatabaseManager::TYPE_INSERT);
                    }
                }
            }
        }
    }
}


/**
 * Name: getJSON
 * Purpose: gets a JSON document based off the member file being used, to create ajax content.
 * @param $db
 * @param $userID
 * @param $fields
 * @param $formID
 * @param $tableNames
 * @return bool
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function getJSON($db, $userID, $fields, $formID, $tableNames)
{

    $mergedArrays = mergeArrays($fields, $tableNames);
    for($i = 0, $k=0; $i<count($mergedArrays); $i++)
    {

        try{
            /** @noinspection PhpUndefinedMethodInspection */
            $result = $db->execute("SELECT $mergedArrays[$i] FROM $mergedArrays[$k] WHERE userID = ?",
                DatabaseManager::TYPE_SELECT, array($userID));
            return json_encode($result); // Returns JSON document for later jQUERY input field values.

            // i.e.: {"firstName": "Aaron"}
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            echo '<script>alert("$e->getMessage();")</script>';
        }
    }
    return "";
}


/**
 * Name: mergeArrays
 * Purpose: Used for the JSON document and ajax to ensure data returns.
 * @param $fields
 * @param $tableNames
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function mergeArrays($fields, $tableNames)
{
    // Populate the two separate arrays for conditions.
    //$createKeyValues_1 = countKeys($fields);
    //$createKeyValues_2 = countKeys($tableNames);
    //$preMergedArray = []; // <K,V>:<field => table>
    //$valueKey1 = defineKeyValues($createKeyValues_1, "_");
    //$valueKey2 = defineKeyValues($createKeyValues_2, "-");

    //var_dump($createKeyValues_1);
    //var_dump($createKeyValues_2);
    //var_dump($valueKey1);
    //var_dump($valueKey2);

    return array();
}


/**
 * Name: defineKeyValues
 * Purpose: defines the key values in the mergeArrays function.
 * @param $createKeyValues
 * @param $searchChar
 * @return string
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function defineKeyValues($createKeyValues, $searchChar)
{
    $valueKey = "";
    for($i = 0; $i<count($createKeyValues); $i++)
    {
        $needle = strpos($createKeyValues[$i], $searchChar ,0); // Finds character provided.
        $valueKey[$i] = substr($createKeyValues[$i],$needle+1); // Writes the value to the value key array.
    }
    return $valueKey;
}


/**
 * Name: countKeys
 * Purpose: counts the keys in merge arrays.
 * @param $values
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function countKeys($values)
{
    $createValuesKeys=[];

    for($i =0; $i<count($values); $i++)
    {
        try{
            $createValuesKeys[$i] = $values[$i];
        }
        catch(RangeException $r)
        {
            echo $r->getMessage();
            echo '<script>alert("$e->getMessage();")</script>';
        }
    }
    return $createValuesKeys;
}


/**
 * Name:doFileOperations
 * Purpose: Standardize the function for collecting images based off there specified properties.
 * @param $db
 * @param $userID
 * @param $fileTempName
 * @param $fileName
 * @param $portionSelected
 * @param $destinationID
 * @param $formName
 * @param $extension
 * @param $imageW
 * @param $imageH
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function doFileOperations($db, $userID, $fileTempName, $fileName,
                          $portionSelected, $destinationID, $formName, $extension, $imageW, $imageH)
{
    // If the extension is a PNG, JPG or JPEG then go to then true.
    if ($extension == '.png' || $extension == '.jpg' || $extension == '.jpeg' && isset($_FILES))
    {
        list($imgWidth, $imgHeight) = getimagesize($fileTempName); // Get width & height for user image.
        if ($imgWidth === $imageW && $imgHeight === $imageH)
        {
            $table = ['portfolio_paths']; // Set the table where path is stored.
            $columnName = ['path']; // Set the column to where the path is stored.
            try
            {
                moveUpdateFile($db, $userID, $fileTempName, $fileName, $table, $columnName,
                    $destinationID, $portionSelected, $formName);
            }
            catch (Exception $e)
            {
                echo "<script>alert(\"there was an issue uploading your file! please try again! \")</script>";
            }
        }
    }
}

/**
 * Name:prepInputPortions
 * Purpose: create function that standardizes the packaging of general CU (CRUD) actions.
 * @param $db
 * @param $userID
 * @param $destinationID
 * @param $portionSelected
 * @param $formName
 * @param $fieldsColumns
 * @param $fieldsTables
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function prepInputPortions($db, $userID, $destinationID, $portionSelected, $formName,
                           $fieldsColumns, $fieldsTables)
{
    $columns = [];
    $table = [];
    $inputName = [];

    // For each of the columns provided.
    for($i = 0; $i<count($fieldsColumns); $i++)
    {
        // If the posted value of that column is not empty.
        if(!empty($_POST[$fieldsColumns[$i]]))
        {
            // Push the value into the respective array.
            array_push($inputName, $fieldsColumns[$i]);
            array_push($columns, $fieldsColumns[$i]);
            array_push($table, $fieldsTables[$i]);
        }
    }

    try
    {
        // Input variables from above.
        inputValues($db, $userID, $inputName, $table, $columns,
            $destinationID, $portionSelected, $formName);
    }
    catch (Exception $e)
    {
        // Check to see if the error check is working.
        echo "<script>alert(\"there was an issue uploading your profile! please try again! \")</script>";
    }
}

/**
 * Name:performInsetUpdateOperations
 * Purpose: The portion the prepares the input from member pages between input/and files.
 * @param $db
 * @param $userID
 * @param $destinationID
 * @param $portionSelected
 * @param $formName
 * @param $fieldsColumns
 * @param $fieldsTables
 * @param $imageMaxWidth
 * @param $imageMaxHeight
 * @param $formFieldName
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function performInsertUpdateOperations($db, $userID, $destinationID, $portionSelected, $formName, $fieldsColumns,
                                       $fieldsTables, $imageMaxWidth, $imageMaxHeight, $formFieldName)
{
    // Takes defaulted values, spins them for preparation and then passed to F17 (inputValues)
    try
    {
        prepInputPortions($db, $userID, $destinationID, $portionSelected, $formName, $fieldsColumns, $fieldsTables);
    }
    catch(Exception $error)
    {
        echo "<script>alert(\"there was an issue uploading your information! please try again! \")</script>";
    }

    if (isset($_FILES))
    {
        //Prepare the variables to move the file.
        $name = htmlentities($_FILES[$formFieldName]['name']);
        $fileTempName = $_FILES[$formFieldName]['tmp_name'];
        $extension = strtolower(substr($name, strpos($name, '.')));
        $imageW = $imageMaxWidth;
        $imageH = $imageMaxHeight;

        if(!empty($_POST["destination_id"]))
        {
            $portion = $_POST["destination_id"];// Return the portion that will be affixed to the file name.
        }
        else
        {
            $portion = 1;
            echo "<script>alert(\"there was an issue uploading your file! please try again! \")</script>";
        }

        // Path to the file.
        $fileName = "../graphics/member_uploads/".$formName."/".$formName."_image".
            $portion."_user_".$userID.$extension;

        doFileOperations($db, $userID, $fileTempName, $fileName, $portionSelected, $destinationID, $formName,
            $extension, $imageW, $imageH);
    }
}

//////////////////////////////////////////END USER UPLOADS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\




//////////////////////////////////////// HTML BASED CLIENT FUNCTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

/**
 * Name: getSkillsColumn
 * Purpose: retuns the skills information for user modules class.
 * @param $db
 * @param $userID
 * @param $k
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getSkillsColumn($db, $userID, $k)
{
    $result = $db->execute("SELECT column_text 
                            FROM portfolio_columns
                            WHERE userID =?
                            AND destination_id='".$k."'",
        DatabaseManager::TYPE_SELECT, array($userID));
    foreach($result as $row)
    {
        echo $row['column_text'];
    }
}

trait GetSkillColumnText{
    private $result = null;

    function getSkillsColumn($db, $userID, $k){
        $result = $db->execute("SELECT column_text FROM portfolio_columns
                                                  WHERE userID =?
                                                  AND destination_id='".$k."'",
            DatabaseManager::TYPE_SELECT, array($userID));
        
        foreach($result as $row) {
            return $row['column_text'];
        }
    }
}


/**
 * Name: getSkillsLabel
 * Purpose: returns a skill label for user modules class.
 * @param $db
 * @param $userID
 * @param $i
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getSkillsLabel($db, $userID, $i)
{
    $result = $db->execute("SELECT label FROM portfolio_labels
                                      WHERE userID =?
                                      AND destination_id='".$i."'", DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['label'];
        }
    }
    else
    {
        echo "Coming Soon!";
    }

}


/**
 * Name: getSkillsPhoto
 * Purpose: returns the photo for skills page based on user module class.
 * @param $db
 * @param $userID
 * @param $i
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  getSkillsPhoto($db, $userID, $i)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                                                  WHERE userID = ?
                                                  AND destination_id='".$i."'",DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['path'];
        }
    }
    else
    {
        echo '../graphics/member_uploads/default_profile.png';
    }

}


/**
 * Name: showWorksDisplayPicture
 * Purpose: Returns the works display picture via User Modules.
 * @param $db
 * @param $j
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Lucas Mathis <mathis.lucas10@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  showWorksDisplayPicture($db, $j, $userID)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                            WHERE userID = ?
                            AND destination_id=$j",DatabaseManager::TYPE_SELECT,array($userID));


        if(!empty($result))
        {
            foreach($result as $row)
            {
                echo $row['path'];
            }

        }
        else
        {
            echo '../graphics/member_uploads/default_profile.png';
        }
}


/**
 * Name: showWorksProjectDescription
 * Purpose: Creates works project description based off User Modules Class.
 * @param $db
 * @param $i
 * @param $userID
 * @version 1.0.0
 * @author Jason Kessler-Holt <>
 */
function  showWorksProjectDescription($db, $i, $userID)
{
    $result = $db->execute("SELECT projectDescription, work_link FROM portfolio_works
                                                   WHERE userID = ?
                                                   AND worksID='".$i."'",DatabaseManager::TYPE_SELECT,array($userID));
    if(!empty($result))
    {
        foreach($result as $row)
        {
            if($row['projectDescription']=='NULL' || $row['projectDescription']=='')
            {
                echo 'This project is not determined, however it will be amazing! So Come Back!';
            }
            else
            {
                echo $row['projectDescription'];
            }

            if($row['work_link'] != 'NULL' || $row['work_link']!= '')
            {
                echo "<p id='viewProjP'>";
                echo "<a href='" . $row['work_link'] . " ' target=\"_Blank\">Check this project out live!</a>";
                echo "</p>";
            }
        }
    }
}


/**
 * Name: showWorksPreviewPhoto
 * Purpose: Creates the works preview based off user modules class.
 * @param $db
 * @param $k
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  showWorksPreviewPhoto($db, $k, $userID)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                                          WHERE userID = ?
                                          AND destination_id='".$k."'", DatabaseManager::TYPE_SELECT,array($userID));
    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['path'];
        }
    }
    else
    {
        echo '../graphics/member_uploads/project_uploads/project_preview_default.png';
    }

}


/**
 * Name: showWorksTitle
 * Purpose: Creates the works title based off user Module class.
 * @param $db
 * @param $i
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  showWorksTitle($db, $i, $userID)
{
    $result = $db->execute("SELECT title FROM portfolio_works
                                            WHERE userID = ?
                                            AND worksID='".$i."'", DatabaseManager::TYPE_SELECT, array($userID));
    if(!empty($result))
    {
        foreach($result as $row)
        {

            echo $row['title'];
        }
    }
    else
    {
        echo 'Project Coming Soon!';
    }
}


/**
 * Name: truncateString
 * Purpose:
 * @param $text
 * @param $length
 * @return string
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  truncateString($text, $length)
{
    if(strlen($text)>$length)
    {
        $text = substr($text, 0, strpos($text, ' ', $length));
    }
    $rep = "...";
    $text= $text.$rep;
    return $text;
}


/**
 * Name: displayOverviewAbout
 * Purpose:
 * @param $db
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  displayOverviewAbout($db, $userID)
{
    $result = $db->execute("SELECT overview FROM portfolios_about
                                  WHERE userID = ?", DatabaseManager::TYPE_SELECT, array($userID));

    foreach($result as $row)
    {
        echo $row['overview'];
    }
}


/**
 * Name: getAboutPhoto
 * Purpose:
 * @param $k
 * @param $db
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  getAboutPhoto($k, $db, $userID)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                            WHERE userID = ?
                            AND destination_id='".$k."'",
        DatabaseManager::TYPE_SELECT, array($userID));


    if(!empty($result))
    {
        foreach($result as $value)
        {
            echo $value['path'];
        }
    }
    else
    {
        echo '../graphics/member_uploads/default_profile.png';
    }
}


/**
 * Name: getAboutLabel
 * Purpose:
 * @param $k
 * @param $db
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getAboutLabel($k, $db, $userID)
{
    $result = $db->execute("SELECT label FROM portfolio_labels
                                      WHERE userID =?
                                      AND destination_id='".$k."'", DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['label'];
        }
    }
    else
    {
        echo "Coming soon!";
    }

}


/**
 * Name: getAboutColumn
 * Purpose:
 * @param $i
 * @param $db
 * @param $userID
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getAboutColumn($i, $db, $userID)
{
    $result = $db->execute("SELECT column_text FROM portfolio_columns
                                      WHERE userID =?
                                      AND destination_id='".$i."'", DatabaseManager::TYPE_SELECT, array($userID));
    foreach($result as $row)
    {
        echo $row['column_text'];
    }
}


/**
 * Name: getWorksTitles
 * Purpose: Returns the title of the members work.
 * @param $db
 * @param $userID
 * @param $worksNumber
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getWorksTitles($db, $userID, $worksNumber)
{
    return $db->execute("SELECT title FROM portfolio_works
                              WHERE userID = ?
                              AND worksID = '".$worksNumber."'",DatabaseManager::TYPE_SELECT,array($userID));
}


/**
 * Name: getPortfoliosNavigation
 * Purpose:
 * @param $db
 * @return array
 * @version 1.0.0
 * @author Jason Kessler-Holt <>
 */
function  getPortfoliosNavigation($db)
{
    // Script used to remove the use of $_GET.
    ?>
    <script>
        function getUserProfile(userID)
        {
            var form = document.createElement('form');
            form.setAttribute('method', 'POST');
            form.setAttribute('action', '../portfolio.php');

            var input = document.createElement('input');
            input.setAttribute('name', 'userID');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', userID);

            form.appendChild(input);

            document.body.appendChild(form);

            form.submit();
        }
    </script>
    <?php
    if ($result = $db->execute("SELECT userID, firstName, lastName FROM portfolio_profiles ORDER BY firstName LIMIT 15 ")) ;
    foreach($result as $row)
    {
        echo "<li><a href='#' onclick='getUserProfile(".$row['userID'].')\'>' . strtoupper($row['firstName']) . " " .
        strtoupper($row['lastName']) . "</a></li>";
    }
?>
<?php
}


/**
 * Name: getProfilePictures
 * Purpose: returns the photos path of each user.
 * @param $userID
 * @param $db
 * @return array
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getProfilePicturesPath($userID, $db)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                            WHERE userID = ?
                            AND destination_id=36",DatabaseManager::TYPE_SELECT, array($userID));



    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['path'];
        }
    }
    else
    {
        echo '../graphics/member_uploads/default_profile.png';
    }
}


/**
 * Name: getFNameLName
 * Purpose:
 * @param $userID
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getFNameLName($userID, $db)
{
    $result = $db->execute("SELECT firstName,lastName FROM portfolio_profiles
                            WHERE userID = ?", DatabaseManager::TYPE_SELECT,array($userID));
    foreach($result as $row)
    {
        echo $row['firstName']." ".$row['lastName'];
    }
}


/**
 * Name: getMemberNavigation
 * Purpose:
 * @param $db
 * @param $logged
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Lucas Mathis <mathis.lucas10@gmail.com>
 */
function  getMemberNavigation($db, $logged)
{
    ?>
    <!--Standard Header for all b[squared] pages. ********************************* -->
    <header>
        <nav class="navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="../index.php"><img id="logo" src="../graphics/logo4.png" alt="b[squared]"></a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="homePageActive"><a href="../index.php">HOME</a></li>
                        <li id="portfolioPageActive" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="../portfolio.php">PORTFOLIOS
                                <span class="caret"></span></a>
                            <ul id="navList" class="dropdown-menu">
                                <?php
                                    getPortfoliosNavigation($db);
                                ?>
                            </ul>
                        </li>
                        <li id="updatePortfolioPageActive" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">UPDATE PORTFOLIO
                                <span class="caret"></span></a>
                            <ul id="navList" class="dropdown-menu">
                                <li><a href="../member/change_password.php">CHANGE PASSWORD</a></li>
                                <li><a href="../member/profile.php">PROFILE</a></li>
                                <li><a href="../member/statement.php">STATEMENT</a></li>
                                <li><a href="../member/about.php">ABOUT</a></li>
                                <li><a href="../member/skills.php">SKILLS</a></li>
                                <li><a href="../member/works.php">WORKS</a></li>
                            </ul>
                        </li>
                        <li id="faqPageActive"><a href="../faq.php">FAQ</a></li>
                        <?php
                            if($logged=='in'){
                            echo '<li><a href="../includes/logout_member.php">LOG OUT</a></li>';
                            }
                            else{
                            echo '<li><a href="../member/login.php">LOG IN</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!--END Standard Header for all b[squared] pages. ********************************* -->
<?php
}


/**
 * Name: getAdminNavigation
 * Purpose:
 * @param $db
 * @param $logged
 * @version 1.0.0
 * @author Lucas Mathis <mathis.lucas10@gmail.com>
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  getAdminNavigation($db, $logged)
{
    ?>
    <!-- Admin Navigation -->
    <header>
        <nav class="navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="../index.php"><img id="logo" src="../graphics/logo4.png" alt="b[squared]"></a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="homePageActive"><a href="../index.php">HOME</a></li>
                        <li id="portfolioPageActive" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="../portfolio.php">
                                PORTFOLIOS<span class="caret"></span></a>
                            <ul id="navList" class="dropdown-menu">
                                <?php
                                getPortfoliosNavigation($db);
                                ?>
                            </ul>
                        </li>
                        <li id="adminPageActive" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                ADMINISTRATIVE<span class="caret"></span></a>
                            <ul id="navList" class="dropdown-menu">
                                <li><a href="../admin/change_password.php">CHANGE PASSWORD</a> </li>
                                <li><a href="../admin/user_list.php">MANAGE USERS</a></li>
                                <li><a href="../admin/register.php">ADD USERS</a></li>
                            </ul>
                        </li>
                        <?php
                        if($logged=='admin'){
                            echo '<li><a href="../includes/logout_admin.php">LOG OUT</a></li>';
                        }
                        else{
                            echo '<li><a href="../admin/login.php">LOG IN</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php
}


/**
 * Name: getFooter
 * Purpose:
 * @version 1.0.0
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Robert Fisher <@gmail.com>
 * @author Lucas Mathis <mathis.lucas10@gmail.com>
 */
function  getFooter()
{
    ?>
    <!--Footer-->
    <footer class="container navbar-fixed-bottom">
       <p id="socialMediaFooter">
          <a target ="_blank" title="follow me on facebook" href="https://www.facebook.com/groups/641262162679997/">
             <img src="../graphics/icons/FB2.png"
                  width="20" height="20" alt="follow bsquared on facebook"  border=0>
          </a>
          <a target ="_blank" href="https://www.linkedin.com/pub/binary-beasts/112/994/819" >
             <img src="../graphics/icons/linkedin-square-social-media2.png"
                  width="20" height="20" alt="View Binary Beasts's LinkedIn profile"  border="0">
          </a>
          <a target="_blank" href="https://github.com/basis14/basis2016-bsquared">
             <img src="../graphics/icons/github-square-social-media2.jpg"
                  width="20" height="20" alt="View this site with github" border="0">
          </a>
       </p>
       <p><a href="../faq.php">b<span>[</span>squared<span>]</span></a>  &nbsp; <span>&#9672;</span> &nbsp;
                &copy; <span id="footerYear"></span> &nbsp; <span>&#9672;</span>&nbsp;
                <a target="_blank " href="../privacy/b%5Bsquared%5D_privacy_policy.pdf">Privacy Policy</a>
       </p>
    </footer>
<?php
}


/**
 * Name: getVisitorNavigation
 * Purpose: Get the main navigation for visitors
 * @param $db
 * @version 1.0.0
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Robert Fisher <@gmail.com>
 * @author Lucas Mathis <mathis.lucas10@gmail.com>
 */
function  getVisitorNavigation($db)
{
    ?>
    <!-- Visitor Navigation -->
    <header>
        <nav class="navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="../index.php"><img id="logo" src="../graphics/logo4.png" alt="b[squared]"></a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="homePageActive"><a href="../index.php">HOME</a></li>
                        <li id="portfolioPageActive" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="../portfolio.php">PORTFOLIOS<span class="caret"></span></a>
                            <ul id="navList" class="dropdown-menu">
                                <?php
                                    getPortfoliosNavigation($db);
                                ?>
                            </ul>
                        </li>
                        <li id="faqPageActive"><a href="../faq.php">FAQ</a></li>
                        <li id="loginPageActive"><a href="../member/login.php">LOGIN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
<?php
}

/**
 * Name: getOpeningSplashDBView
 * Purpose: returns the DB view for the front page.
 * @param $db
 * @return mixed
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function getOpeningSplashDBView($db)
{
    return $db->execute("SELECT portfolio_profiles.userID, firstName, lastName, portfolio_paths.path FROM portfolio_profiles
                        INNER JOIN portfolio_paths
                        ON portfolio_profiles.userID = portfolio_paths.userID
                        WHERE firstName IS NOT NULL
                        AND portfolio_paths.destination_id=36
                        AND lastName IS NOT NULL
                        ORDER BY userID");
}

/**
 * Name: MakeOpeningSplash
 * Purpose: starts the cycle for displaying opening page portrait splash.
 * @param $db
 * @return int
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function MakeOpeningSplash($db)
{
    $profiles = getOpeningSplashDBView($db); // Returns the profiles from the db view.
    $countProfiles = count($profiles); // Stores the count of the number returned.

    if($countProfiles == 0 || $countProfiles == null) // If there are no profiles loaded. Return 0.
    {
        return $countProfiles;
    }
    else
    {
        // Profiles were returned from the view, prepare the splash.
        try
        {
            prepareOpeningSplash($countProfiles, $profiles);
        }
        catch(Exception $e)
        {
            // For some type of failure, return profiles to zero.
            return $countProfiles;
        }
    }
}

/**
 * Name: prepareOpeningSplash
 * Purpose: Bulk of the algorithm for producing upside-down pyramid.
 * @param $countProfiles
 * @param $profiles
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function prepareOpeningSplash($countProfiles, $profiles)
{
    $rowCount = 0;
    echo '<div id="profileImgDiv" class="container allImgHolder">';

    for($i = 1; $countProfiles>=MAX_COLUMNS; $countProfiles--, $i++)
    {
        $users = [];
        $countProfiles = $countProfiles-MAX_COLUMNS;
        $rowCount++;
        echo "<div id='imgRow$i' class='row imgRow'>"; // Top Level Div Element.

        for($j =0; $j<MAX_COLUMNS; $j++)
        {
            array_push($users, array_shift($profiles));
        }

        fullRowPortraitRow($users);

        echo "</div>"; // Close Top Level Div after return.
    }
    if($countProfiles<MAX_COLUMNS)
    {
        $users = [];
        echo "<div id='imgRow$i' class='row imgRow'>"; // Top Level Div Element.
        $x = count($profiles);

        for($x; $x>0; $x--){
            array_push($users, array_shift($profiles));
        }
        partialPortraitRow($countProfiles, $users);

        echo "</div>"; // Close Top Level Div after return.
    }
    echo "</div>"; // Close Top Level Div after return.
}

/**
 * Name: fullRowPortraitRow
 * Purpose: Generates a complete row for the splash.
 * @param $users
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function fullRowPortraitRow($users)
{
    for($i = 0; $i<MAX_COLUMNS; $i++) // Do the max, since this is a full row.
    {
        $user = array_shift($users); // shift one of the users during an iteration.
        generateRow($user, 3);
    }
}

/**
 * Name: partialPortraitRow
 * Purpose: Generates the
 * @param $difference
 * @param $users
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function partialPortraitRow($difference, $users) // Do the difference, since this is a partial row.
{
    for($i = 0; $i<$difference; $i++)
    {
        $user = array_shift($users);  // shift one of the users during an iteration.
        generateRow($user, 4);
    }
}

/**
 * Name: generateRow
 * Purpose: Standard HTML within a row on the index page.
 * @param $user
 * @param $bootstrapSize
 * @version 1.1
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function generateRow($user, $bootstrapSize)
{
    $userID    = $user['userID'];
    $firstName = $user['firstName'];
    $lastName  = $user['lastName'];
    $path      = $user['path'];

    $onMouseOverHTML = $firstName." ".$lastName;

    echo "<div class='col-sm-$bootstrapSize nameLink'>";
    echo "<a href='#' onclick='getUserProfile($userID)'>";
    echo "<img class='hoverTransition' onmouseover='document.getElementById(\"descripPar\").innerHTML=\"$onMouseOverHTML\"'";
    echo "onmouseout='openingState()'";
    echo "src='$path'>";
    echo "<span class='userFullName' id='userFullName'>$firstName $lastName</span></a>";
    echo "</div>";
}

/**
 * Name: getPortfolioBackground
 * Purpose:
 * @param $userID
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 * @author Sherri Miller <sherrimiller3397@gmail.com>
 */
function  getPortfolioBackground($userID,$db)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                            WHERE userID = ?
                            AND destination_id = 19", DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['path'];
        }
    }
    else
    {
        echo '../graphics/member_uploads/member_backgrounds/member_background_default.jpg';
    }

}


/**
 * Name: getFirstName
 * Purpose:
 * @param $userID
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getFirstName($userID, $db)
{
    $result = $db->execute("SELECT firstName FROM portfolio_profiles
                                  WHERE userID=?", DatabaseManager::TYPE_SELECT, array($userID));
    foreach($result as $row)
    {
        if($row['firstName']=='NULL'|| $row['firstName']=='')
        {
            echo ' ';
        }
        else
        {
            echo $row['firstName']."'s ";
        }
    }
}


/**
 * Name: getPortfolioStatement
 * Purpose:
 * @param $userID
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getPortfolioStatement($userID, $db)
{
    $result = $db->execute("SELECT statement FROM portfolios_statement
                                  WHERE userID=?", DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['statement'];
        }
    }
    else
    {
        echo 'Welcome,<br>View My Profile!';
    }

}


/**
 * Name: getPortfolioResume
 * Purpose:
 * @param $userID
 * @param $db
 * @version 1.0.0
 * @author Aaron Young <mustarddevelopment@gmail.com>
 */
function  getPortfolioResume($userID, $db)
{
    $result = $db->execute("SELECT path FROM portfolio_paths
                                  WHERE userID=?
                                  AND destination_id= 35", DatabaseManager::TYPE_SELECT, array($userID));

    if(!empty($result))
    {
        foreach($result as $row)
        {
            echo $row['path'];
        }
    }
    else
    {
        echo '../index.php';
    }

}
//////////////////////////////////////// HTML BASED CLIENT FUNCTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
