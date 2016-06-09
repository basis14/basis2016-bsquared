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


/**
 * Class UserModules
 *
 */

class UserModules
{

    public static function doOpeningStatement()
    {
        ?>
        <p id="descripPar" class="descripPar">
            Welcome to b[squared]!<br>
            Home of<br> <a href="http://www.olympic.edu/information-systems-bachelor-applied-science-bas">
                Olympic College Bachelors of Applied Science Information Systems(BAS IS)</a>
            <br><span>2014-2016</span> cohort. Please select a photo above to learn more about the person in photo! If you
            would like to
            learn more about the BAS IS program <a href="faq.php"><br> view the FAQ.</a>
        </p>
        <?php
    }

    /**
     * Name: UserModules::doWorksModalModule
     * @param $modalAmount
     * @param $db
     * @param $userID
     * @uses showWorksTitle() Returns title of the users work.
     * @uses showWorksPreviewPhoto() Returns preview within the modal of the users work photo.
     * @uses showWorksProjectDescription() Returns description of the project.
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doWorksModalModule($modalAmount, $db, $userID){
        ?>
        <?php
            for($i = 1, $k=25; $i < $modalAmount; $i++, $k++)
            {
                ?>
                <div class="modal fade" id="modal<?php echo $i ?>" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php showWorksTitle($db, $i, $userID) ?></h4>
                            </div>
                            <div id="myModal<?php echo $i ?>" class="modal-body">
                                <img id="modImage<?php echo $i ?>"
                                     src="<?php showWorksPreviewPhoto($db, $k, $userID); ?>"
                                     alt="project preview">
                                <p><?php showWorksProjectDescription($db, $i, $userID); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button button--nina button--text-thick button--text-upper button--size-l"
                                        data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
        }
        ?>
        <?php
    }

    /**
     * Name: UserModules::doSkillsModule
     * @param $skillsAmount
     * @param $db
     * @param $userID
     * @uses getSkillsPhoto()
     * @uses getSkillsLabel()
     * @uses getSkillsColumn()
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doSkillsModule($skillsAmount, $db, $userID)
    {

        for($i = 1, $k=4; $i<$skillsAmount; $i++, $k++)
        {
            ?>
            <div class="col-sm-4">
                <img src="<?php getSkillsPhoto($db, $userID, $i);?>" alt="my skill">
                <h3 class="skillHeader"><?php getSkillsLabel($db, $userID, $i);?><h3>
                        <p><?php getSkillsColumn($db, $userID, $k);?></p>
            </div>
            <?php
        }

    }

    /**
     * Name: UserModules::doAboutModule()
     * @param $db
     * @param $userID
     * @uses getAboutPhoto()
     * @uses getAboutLabel()
     * @uses getAboutColumn()
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doAboutModule($db, $userID)
    {

        for ($i = 7, $k=22; $i < 10; $i++, $k++)
        {
            ?>
            <div class="col-sm-4">
                <img src="<?php getAboutPhoto($k, $db, $userID);?>" alt="about me">
                <h3><?php getAboutLabel($k, $db, $userID); ?></h3>
                <p><?php getAboutColumn($i, $db, $userID); ?></p>
            </div>
            <?php
        }

    }

    /**
     * Name: UserModules::doOverviewModule
     * Purpose: prepare the style script like php preparation.
     * @param $userID
     * @param $db
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doOverviewModule($userID, $db)
    {
        ?>
        <style>
            .addBG {
                background-color: #eee;
                background: url('<?php getPortfolioBackground($userID, $db)?>');
                background-repeat: no-repeat;
                background-position: center center;
                width: 100%;
                max-width: 940px;
            }
        </style>
        <?php

    }

    /**
     * Name: UserModules::doCarouselIndicator()
     * Purpose: Prepare the slides for the portfolio page.
     * Ver 1.0
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doCarouselIndicator()
    {
        ?>
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
            <li data-target="#myCarousel" data-slide-to="4"></li>
        </ol>
        <?php
    }

    /**
     * Name: UserModules::doCarouselClick()
     * Purpose: Makes links to change the slider (btn)
     * Ver 1.0
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function doCarouselClick()
    {
        ?>

        <a id="leftCarouselClick" class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a id="rightCarouselClick" class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <?php

    }

    /**
     * Name: UserModules::getContactForm()
     * Purpose: Sets contact form for user pages.
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.0.0
     */
    public static function getContactForm()
    {
        ?>
        <div id="faqForm" class="container">
            <p>If you have not found an answer to a question, please don't hesitate to contact us by filling out the form below. Thanks!</p>
            <form id="contact" method="post" action="/includes/send.php">
                <input type="text" id="name" name="name" placeholder="Name">
                <br>
                <input type="text" id="email" name="email" placeholder="Email">
                <br>
                <input type="text" name="subject" placeholder="Subject">
                <br>
                <textarea name="content" placeholder="Your Message"></textarea>
                <br><br>
                <button class="button button--nina button--text-thick button--text-upper button--size-l"
                        data-text="Send Mail"> <!--onclick="this.form.submit()"-->
                    <span>S</span><span>e</span><span>n</span><span>d</span>
                    <span>M</span><span>a</span><span>i</span><span>l</span>
                </button>
            </form>
        </div>
        <?php
    }

    /**
     * Name: UserModules::doProjectModule()
     * Purpose: Display the projects in an array. Used for the portfolio page.
     * @uses DatabaseManager::TYPE_SELECT()
     * @author Jason Kessler-Holt
     * @author Lucas Mathis <mathis.lucas10@gmail.com>
     * @author Aaron Young <mustarddevelopment@gmail.com>
     * @version 1.2
     * @param $db
     * @param $userID
     */
    public static function doProjectModule($db, $userID)
    {
        $maxColumns = 3;
        $maxRows   = 3;
        $gridNumber = 10;
        $count = 1;

        for($row = 1; $row <$maxRows+1; $row++)
        {
            echo "<div class='row'>";
            for($column =1; $column<$maxColumns+1; $column++)
            {
                echo '<div class="col-sm-4">';

                $imageSource = $db->execute("SELECT path FROM portfolio_paths WHERE userID = ?
                    AND destination_id='".$gridNumber."'",
                    DatabaseManager::TYPE_SELECT,array($userID));


                //$title = getWorksTitles($db, $userID, $gridNumber);

                //var_dump($title);

                // Add a default image if there was no project.
                if(!empty($imageSource))
                {
                    $imageSource = $imageSource[0]['path'];
                }
                else
                {
                    $imageSource = "../graphics/member_uploads/project_uploads/project_preview_default.png";
                }

                echo '<img src="'.$imageSource.'" type="button" data-toggle="modal" data-target="#modal'.$count.'"';
                echo 'onmouseover="$("#worksTitle").html("").append("")"';
                echo 'onmouseout=""';
                echo 'height="130" width="130" alt="works">';

                echo '</div>';
                $gridNumber++;
                $count++;
            }
            echo "</div>";
        }
    }

    public static function generateNavigation($db, $logged)
    {
        if($logged == "in")
        {
            getMemberNavigation($db, $logged);
        }
        elseif($logged == "admin")
        {
            getAdminNavigation($db, $logged);
        }
        else
        {
            getVisitorNavigation($db);
        }
    }

    public static function getPageScripts()
    {
        ?>
        
        <?php
    }
}