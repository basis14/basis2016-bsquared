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

include_once '../includes/db_connect_member.php';
include_once '../includes/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/FormsManager.php';

secureSessionStart();// call to functions.php secure session.
$logged = checkIfLoggedIn($db);
?>
<!DOCTYPE html>
<html lang="en">

<!--Standard head for all bsquared member pages. -->
<head>
    <title>b[squared] Member Site</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
<div id="main" class="container">

    <?php
    getMemberNavigation($db, $logged);
    ?>

    <br><br><br>

    <!-- If statement that personalizes the experience by displaying the username in the page. -->
    <?php if (loginCheckMember($db) == true) : ?>
        <p>Update works <?php echo htmlentities($_SESSION['username']); ?>!</p><br>
        <p>
            <?php
            $user_id = $_SESSION['user_id'];// retrieve the userID from the session.
            FormsManager::getWorkForm();// Call the function from content_forms.php
            ?>
        </p>
    <?php else : ?>
        <p>
            <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
        </p>
    <?php endif; ?>
    <?php
    getFooter();
    ?>
</div>

<?php

if (isset($_POST['doWorks']))
{
    $form_name = "works";
    $portion_selected = "destination_id";

    if (isset($_FILES["projectThumb"]) && !empty($_FILES["projectThumb"]))
    {
        $projectThumb = htmlentities($_FILES['projectThumb']['name']);
        $file_temp_name = $_FILES['projectThumb']['tmp_name'];
        $extension_thumb = strtolower(substr($projectThumb, strpos($projectThumb, '.')));

        if ($extension_thumb == '.png' || $extension_thumb == '.jpg' || $extension_thumb == '.jpeg')
        {
            if(!empty($_POST["destination_id"]))
            {
                $works_number=$_POST["destination_id"];
            }
            else
            {
                $works_number =1;
            }

            $file_name = "../graphics/member_uploads/works/" . "works_thumb_table_" . // File name
                $works_number . "_" . "user_" . $user_id . $extension_thumb;

            $table_name = ['portfolio_paths'];
            $columnName = ['path'];
            $destination_id = NULL;

            try
            {
                moveUpdateFile($db, $user_id, $file_temp_name, $file_name, $table_name, $columnName,
                    $destination_id, $portion_selected, $form_name);
            }
            catch (Exception $e)
            {
                echo "<script>alert(\"there was an issue uploading your file! please try again! \")</script>";
            }

            unset($file_name_location, $columnName, $projectThumb, $file_temp_name, $extension_thumb, $destination);
        }
    }

    if (isset($_FILES["previewDestination"]) && !empty($_FILES["projectThumb"]))
    {
        // Set the variables for the file name's
        $projectPreview = htmlentities($_FILES['previewDestination']['name']);
        $file_temp_name = $_FILES['previewDestination']['tmp_name'];

        // takes the file extension for logic checking.
        $extension_preview = strtolower(substr($projectPreview, strpos($projectPreview, '.')));

        if ($extension_preview == '.png' || $extension_preview == '.jpeg' || $extension_preview == '.jpg')
        {
            // Size of the project preview is 348 X 210;
            list($img_width, $img_height) = getimagesize($file_temp_name); // Get width & height for  image
            if($img_width == 348 && $img_height == 210)
            {
                // Set the variables for the function move update file.

                if(!empty($_POST["destination_id"]))
                {
                    // Set the worksID
                    $works_number = $_POST["destination_id"];
                }
                else
                {
                    $works_number = 1;
                    echo "<script>alert(\"there was an issue uploading your file! please try again! \")</script>";

                }

                // Set the file path.
                $file_name_location = "../graphics/member_uploads/project_uploads/" .
                    "works_table_" . $works_number . "_preview_user_" . $user_id . $extension_preview;

                $table_name = ['portfolio_paths'];// Set the table.

                $columnName = ['path']; // Set the column name for the file path.

                $destination_id = NULL;

                try
                {
                    moveUpdateFile($db, $user_id, $file_temp_name, $file_name_location, $table_name, $columnName,
                        $destination_id, $portion_selected, $form_name);
                }
                catch (Exception $e)
                {
                    echo "<script>alert(\"there was an issue uploading your file! please try again! \")</script>";
                }
            }
        }
    }


    $fields = ["title", "destination_id", "projectDescription", "work_link"];
    $table_name = "portfolio_works";
    $inputName = [];
    $columns = [];
    $destination_id = NULL;
    $portion_selected = "destination_id";

    for ($i = 0; $i < count($fields); $i++)
    {
        if (!empty($_POST[$fields[$i]]))
        {
            if($fields[$i]!="destination_id") // Check to see if the fields are not destination_id
            {
                array_push($inputName, $fields[$i]);
                array_push($columns, $fields[$i]);
            }
            else
            {
                // since the form is destination_id for consistency the column name is really worksID.
                $field_column_transition = $fields[$i];
                array_push($inputName, $fields[$i]);
                array_push($columns, "worksID");

            }
        }
    }

    try
    {
        //Input values function that inputs the table deliverable.
        // This will be changed to handle multiple destinations
        inputValues($db, $user_id, $inputName, $table_name, $columns,
            $destination_id, $portion_selected, $form_name);
    }
    catch (Exception $e)
    {
        echo "<script>alert(\"there was an issue uploading your information! please try again! \")</script>";
    }
}
?>
</body>
</html>