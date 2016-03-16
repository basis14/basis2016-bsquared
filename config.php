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

 // This file needs to be on the outside of the web server.

$configuration = parse_ini_file('config.ini', true); // This will be changed to where you keep your config.ini file.



define("WEBMASTER_EMAIL", "webmaster@localhost");

define('ROOT_DIR', dirname(__FILE__));

define("HOST_ADMIN", $configuration['bsquared']['bsquared.cfg.HOST']);     // The host you want to connect to.
define("PORT_ADMIN", $configuration['bsquared']['bsquared.cfg.PORT']); // default port to connect to
define("USER_ADMIN", $configuration['bsquared']['bsquared.cfg.USER_ADMIN']);    // The database username.
define("PASSWORD_ADMIN", $configuration['bsquared']['bsquared.cfg.PASS_ADMIN']);    // The database password.
define("DATABASE_ADMIN", $configuration['bsquared']['bsquared.cfg.DATABASE']);    // The database name.


define("HOST_MEMBER", $configuration['bsquared']['bsquared.cfg.HOST']);     // The host you want to connect to.
define("PORT_MEMBER", $configuration['bsquared']['bsquared.cfg.PORT']); // default port to connect to
define("USER_MEMBER", $configuration['bsquared']['bsquared.cfg.USER_MEMBER']);    // The database username.
define("PASSWORD_MEMBER", $configuration['bsquared']['bsquared.cfg.PASS_MEMBER']);    // The database password.
define("DATABASE_MEMBER", $configuration['bsquared']['bsquared.cfg.DATABASE']);    // The database name.
define("CAN_REGISTER_MEMBER", "any");
define("DEFAULT_ROLE_MEMBER", "member");
define("SECURE_MEMBER", FALSE);    // FOR DEVELOPMENT ONLY!!!!

define("HOST_CUSTOMER", $configuration['bsquared']['bsquared.cfg.HOST']);     // The host you want to connect to.
define("PORT_CUSTOMER", $configuration['bsquared']['bsquared.cfg.PORT']); // default port to connect to
define("USER_CUSTOMER", $configuration['bsquared']['bsquared.cfg.USER_VISITOR']);    // The database username.
define("PASSWORD_CUSTOMER", $configuration['bsquared']['bsquared.cfg.PASS_VISITOR']);    // The database password.
define("DATABASE_CUSTOMER", $configuration['bsquared']['bsquared.cfg.DATABASE']);    // The database name.
define("CAN_REGISTER_CUSTOMER", "any");
define("DEFAULT_ROLE_CUSTOMER", "member");
define("SECURE_CUSTOMER", FALSE);    // FOR DEVELOPMENT ONLY!!!!
