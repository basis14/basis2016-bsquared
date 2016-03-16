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
 * The MIT License (MIT)

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
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */

class FormsManager
{
    public static function getProfileForm()
    {
        ?>
        <br>
        <form action="profile.php" method="post" enctype="multipart/form-data" id="profile">
            <fieldset>
                <legend>Profile</legend>
                <p><label for="firstname">First Name: </label></p>
                <input type="text" name="firstname" id="firstname"><br><br>

                <p><label for="lastname">Last Name:</label></p>
                <input type="text" name="lastname" id="lastname"><br><br>

                <p><label for="picture">Upload Photo: (Please Upload .PNG or .JPG) 140X100</label></p>
                <input type="file" name="picture" id="picture"><br><br>

                <p><label for="aboutme">About Me</label></p>
                <textarea name="aboutme" id="aboutme" rows="5" cols="100"></textarea><br><br

                <p><input type="submit" name="submit" value="Update Profile" /></p>
            </fieldset>
        </form>
    <?php
    }

    public static function getStatementForm()
    {
    ?>
        <br>
        <form action="statement.php" method="post" enctype="multipart/form-data" id="statement">
            <fieldset>
                <legend>Statement</legend>

                <p><label for="statement">Short Statement: </label></p>

                <!-- Placeholder call from the database so user can see last input -->
                <p><input type="text" name="statement" id="statement"></p><br>

                <p><label for="backgroundImg">Background Image: (Please Upload .PNG or .JPG) 800X500</label></p>

                <!-- Background Image File Upload-->
                <p><input type="file" name="backgroundImg" id="backgroundImg"></p><br><br>

                <p><input type="submit" name="submit" id="submit" value="Update Profile"></p>
            </fieldset>
        </form>
        <?php
    }

    public static function getAboutForm()
    {
        ?>
        <br>
        <form id="about" action="about.php" method="post" enctype="multipart/form-data">

            <fieldset>
                <legend>About Overview</legend>
                <p><label for="overview">Overview: </label></p>

                <p><textarea cols="100" rows="5" name="overview" id="overview"></textarea></p>
                <p><input type="submit" name="submit_overview" id="submit_overview" value="Update Overview"></p><br>
            </fieldset>
            <br>

            <fieldset>
                <legend>About Me Segments</legend>

                <p><label for="destination_id">About Me Number:</label></p>
                <p><select name="destination_id" id="destination_id">
                        <option value="1" selected="selected">About Me #1</option>
                        <option value="2">About Me #2</option>
                        <option value="3">About Me #3</option>
                    </select></p>
                <br>

                <p><label for="aboutImage">Upload an Image:(PNG or JPG Only) Dimensions:140X140</label></p>
                <p><input type="file" name="aboutImage" id="aboutImage"></p><br>
                <p><label for="label">Label Name</label>
                    <input type="text" name="label" id="label"></p><br>



                <p><label for="column_text">Column Text</label></p>
                <p><textarea rows="4" cols="50" name="column_text" id="column_text"></textarea></p><br>

                <input type="submit" name="submit_about" id="submit_about" value="Update About Me Segment">
            </fieldset>
            <br>
        </form>
        <?php
    }

    public static function getSkillForm()
    {
        //Finish making this singular, where we show again what the user is playing with to trigger the destination.
    ?>
    <br>
    <!--suppress HtmlUnknownTarget -->
        <form id="skills" action="skills.php" method="post" enctype="multipart/form-data">

        <fieldset id="skillsForm">
        <legend>Skills Section</legend>
            <p><label for="destination_id">Skills Number: </label></p>
            <p><select name="destination_id" id="destination_id">
                    <option value="1" selected="selected">Skill #1</option>
                    <option value="2">Skill #2</option>
                    <option value="3">Skill #3</option>
                </select></p><br>

            <!-- Script updates the form based off the value changed in the select drop down.
            How do I keep the values if the user changes this last.
            -->

            <p><label for="label">Label One</label></p>
            <p><input type="text" name="label" id="label"></p><br>

            <p><label for="iconImg">Upload an Icon:(PNG or JPG Only) Dimensions:48X48 </label></p><br>
            <p><input type="file" name="iconImg" id="iconImg"></p><br>

            <p><label for="column_text">Column Text</label></p>
            <p><textarea rows="4" cols="50" name="column_text" id="column_text"></textarea></p><br>

            <p><input type="submit" name="submit_skills" id="submit_skills" value="Update Skills"></p>
        </fieldset>
        <br>
        <!--
        Resume Upload... PDF only
        -->
        <fieldset id="resumeForm">
            <legend>Resume Upload</legend>
            <p><label for="">Resume: (PDF only!)</label></p>

            <p><input type="file" name="resume" id="resume"></p><br>
            <input type="submit" name="submit_resume" id="submit_resume" value="Submit Resume">

            <!--Maybe add a resume preview here on the bottom based of the users previous input ( keeps design similar.)
            if(get the path of the resume is not not null then)
            display the page
            else
            display not yet uploaded.
            -->
        </fieldset>
    </form>
    <?php
    }

    public static function getWorkForm()
    {
        /*
         * Will update with the placeholders. Treating the placeholders as a feature rather than a requirement.
         */

    ?>
    <!--suppress HtmlUnknownTarget -->
        <form action="works.php" method="post" enctype="multipart/form-data" id="works">
        <fieldset id="worksForm">
            <legend>Update Works</legend>

            <p><label for="destination_id">Works Number:</label></p>

            <p><select name="destination_id" id="destination_id">
                    <option value="1" selected="selected">Works 1</option>
                    <option value="2">Works 2</option>
                    <option value="3">Works 3</option>
                    <option value="4">Works 4</option>
                    <option value="5">Works 5</option>
                    <option value="6">Works 6</option>
                    <option value="7">Works 7</option>
                    <option value="8">Works 8</option>
                    <option value="9">Works 9</option>
                </select></p><br>

            <p><label for="title">Project Title:</label></p>
            <p><input type="text" name="title" id="title" placeholder=""></p><br>

            <p><label for="projectThumb">Project Thumbnail: (Please Upload .PNG or .JPG) 130X130</label></p>
            <p><input type="file" name="projectThumb" id="projectThumb"></p><br>

            <p><label for="projectDescription">Project Description: </label></p>
            <p><textarea rows="4" cols="50" name="projectDescription" id="projectDescription"></textarea></p><br>

            <p><label for="previewDestination">Preview Destination: (Please Upload .PNG or .JPG) 348X210 </label></p>
            <p><input type="file" name="previewDestination" id="previewDestination"></p><br>

            <p><label for="work_link">Project Link:(www.example.com) </label></p>
            <p><input type="text" name="work_link" id="work_link"></p><br>

            <input type="submit" name="doWorks" id="doWorks" value="Update Works">
        </fieldset>
    </form>
    <?php
    }

    public static function getForgotPasswordForm()
    {
        ?>
        <div id="wrapper">
            <div class="user-icon-forgot"></div>
            <form action="<?php echo escURL($_SERVER['PHP_SELF']); ?>" method="post" name="login_form" class="login-form">

                <div class="header">
                    <h1 id="loginHeader">b[squared] Password Recovery</h1>
                    <p>Enter your E-mail Address and an e-mail will be sent to reset your password.</p>
                </div>

                <div class="content">
                    <input  type="text" name="email" autocomplete="off" class="input email" value="email@bsquared.com"
                            onfocus="this.value=''"/>
                </div>

                <div class="footer">
                    <input type="submit" class="button" value="Reset" />
                    <br>
                    <br>
                </div>
            </form>
        </div>
        <?php
    }

    public static function getChangePasswordForm($type, $email)
    {
        ?>

        <div id="dialogMessage" title="Change Password" style="display: none;">
            <p class="success">
                <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;">
                    Password Changed Successfully!
                </span>
            </p>
            <p class="error">
                <span class="message">Error</span>
                Ahh.. Seems like there was an error:
            </p>
        </div>
        <fieldset id="changePasswordForm">
            <legend>Change Password</legend>
            <ul>
                <li>Passwords must be at least 6 characters long</li>
                <li>Passwords must contain
                    <ul>
                        <li>At least one upper case letter (A..Z)</li>
                        <li>At least one lower case letter (a..z)</li>
                        <li>At least one number (0..9)</li>
                    </ul>
                </li>
                <li>Your password and confirmation must match exactly</li>
            </ul><br>
            <form action="<?php echo '../includes/change_password.inc.php'; ?>" method="post"
                  name="change_password_form">

                <p><label for="email">Email:</label></p>
                <p><input type="email" name="email" id="email" value="<?php echo $email?>" /><br></p>

                <p><label for="role">User Role:</label></p>
                <p><input type="text" id="role" name="role" value="<?php echo $type?>"><br></p>

                <p><label for="password">New Password:</label>
                <p><input type="password" name="password" id="password" autocomplete="off"/><br></p>

                <p><label for="confirmpwd">Confirm Password:</label>
                <p><input type="password" name="confirmpwd" id="confirmpwd" autocomplete="off" /><br></p>

                <br><p><input type="button"
                       value="Change Password"
                       onclick="return changepasswordhash(this.form,
                                                  this.form.password,
                                                  this.form.confirmpwd);" /></p>
            </form>
        </fieldset>

        <?php
    }

    public static function getLoginForm($type, $logged)
    {

        ?>
        <div id="wrapper">
            <!-- SLIDE-IN ICONS-->
            <div class="user-icon"></div>
            <div class="pass-icon"></div>
            <!-- END SLIDE-->
        <form action="<?php echo "../includes/process_login_".$type?>.php" method="post" name="login_form"
              class="login-form">
            <div class="header">
                <h1>b[squared] <?php echo ucwords($type); ?> Login<br>
                    <?php
                    if (isset($_POST['error']))
                    {
                        echo '<p class="error">Incorrect E-mail or Password!</p>';
                    }
                    ?></h1>
            </div>

            <div class="content">
                <input type="text" name="email" autocomplete="off" class="input email" value="email@bsquared.com" onfocus="this.value=''"/>
                <input type="password" name="password" autocomplete="off" id="password" class="input password" value="password" onfocus="this.value=''"/>
            </div>
            <div class="footer">
                <input type="button" class="button" value="Login" onclick="formhash(this.form, this.form.password);" />
                <p>You are currently logged <?php echo $logged ?>.</p>
                <br>
                <br>
                <?php
                if($logged == "in")
                {
                    ?>
                    <p class="logout_paragraph">If you are done, please
                        <a href="../includes/logout_<?php echo $type?>.php">log out</a></p><br>
                    <?php
                }
                ?>
                <?php
                    if($type == "member")
                    {
                        echo '<p class="passwordReset"><a href="forgot_password.php">Forgot Password?</a></p>';
                    }
                ?>
            </div>
        </form>
        </div>
        <div class="gradient"></div>
        <?php

    }

    public static function getRegisterForm()
    {
        ?>

        <fieldset id="registrationForm">
            <legend>Registration Form</legend>
            <ul>
                <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
                <li>Emails must have a valid email format</li>
                <li>Passwords must be at least 6 characters long</li>
                <li>Passwords must contain
                    <ul>
                        <li>At least one upper case letter (A..Z)</li>
                        <li>At least one lower case letter (a..z)</li>
                        <li>At least one number (0..9)</li>
                    </ul>
                </li>
                <li>Your password and confirmation must match exactly</li>
            </ul>
            <form action="<?php echo '../includes/register.inc.php'?>" method="post" name="registration_form">

                <br><p><label for="username">Username:</label></p>
                <p><input type="text" name="username" id="username"/></p><br>

                <p><label for="role">User Role:</label></p>
                <p><select id="role" name="role">
                    <option value="portfolio_members">Member</option>
                    <option value="admin">Administrator</option>
                </select></p><br>

                <p><label for="email">E-Mail:</label></p>
                <p><input type="email" name="email" id="email"/></p><br>

                <p><label for="password">Password:</label></p>
                <p><input type="password" name="password" id="password"/></p><br>

                <p><label for="confirmpass">Confirm Password</label>
                <p><input type="password" name="confirmpass" id="confirmpass"/></p><br>

                <p><input type="button"
                       value="Register"
                       onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpass);"/></p>
            </form>
        </fieldset>
        <?php
    }

    public static function getDeleteUserForm()
    {
        ?>
        <br>
        <form name="delete_user" id="delete_user" method="post" action="user_list.php">
            <fieldset>
                <legend>Choose a userID from above to delete a user (VERY POWERFUL)</legend>
                <p><input type="text" id="userID" name="userId" placeholder="userID"><br></p>
                <input type="submit" name="submit" id="submit" value="Delete User">
            </fieldset>
        </form>
        <?php
    }

    public static function getDeleteMemberAccountForm()
    {
        ?>
        <br>        
        <form name="delete_member" id="delete_user" method="post" action="delete_account.php" onsubmit="return confirm('You are deleting this account. There is no take backsies once you\'ve deleted your account. Are you sure?');">
            <fieldset>
                <legend>Delete this Account</legend>
                <p><label for="userID">This will permanently remove this account from the website. You will not be able to recover this account once the account has been deleted.</label></p>
                <p><input type="hidden" id="userID" name="userId" placeholder="userID"><br></p>
                <input type="submit" name="submit" id="submit" value="Delete User">
            </fieldset>
        </form>
        <?php
    }

    public static function getFAQContactForm()
    {
        ?>
        <p>If you have not found an answer to a question, please don't hesitate to contact us by
            filling out the form below with your name, e-mail, a subject, and a message.
            You will be contacted by e-mail within 24 hours. Thanks!
        </p>

        <form id="contact" method="post" action="faq.php">
            <input type="text" id="name" name="name" placeholder="Name" required="required">
            <br>
            <input type="email" id="email" name="email" placeholder="Email" required="required">
            <br>
            <input type="text" name="subject" placeholder="Subject" required="required">
            <br>
            <textarea name="content" placeholder="Your Message" required="required"></textarea>
            <br><br>
            <button class="button button--nina button--text-thick button--text-upper button--size-l"
                    data-text="Send Mail"
                    name="submit"
                    onclick="this.form.submit();">
                <span>S</span><span>e</span><span>n</span><span>d</span>
                <span>M</span><span>a</span><span>i</span><span>l</span>
            </button>
        </form>
        <?php
    }



}