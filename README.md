![bsquared_logo](https://github.com/basis14/bsquared/blob/master/graphics/logo4.png)


#B Squared, the Portfolio Website for the Bachelors of Applied Science Information Systems (BASIS) 2014 cohort
<a href="https://codeclimate.com/repos/56d25d21c7d8f80436000cc6/feed"><img src="https://codeclimate.com/repos/56d25d21c7d8f80436000cc6/badges/8f6cd342417b10c91428/gpa.svg" /></a>
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/basis14/bsquared/badges/quality-score.png?b=staged_branch&s=cbdd93890c51c913d7fcdfc90746ff2f020126ee)](https://scrutinizer-ci.com/g/basis14/bsquared/?branch=staged_branch)

##Purpose:
The b[Squared] portfolio website was built for the Bachelors of Applied Science Information Systems Cohort of 2014 - 2016. This portfolio management website allows for cohort to upload and display information to future employers or peers regarding themselves and any work they would like to display. The design was built for a LAMP stack, and is a database driven design that allows each member access into the website.

###Requirements:
1. XAMPP/WAMP SERVER
2. PHP
3. MySQL

##Configuration:

1. Clone the bsquared repository on you local server, typically xampp/htdocs/[bsquared]
2. Import the schema.sql database from the data/sql directory to the MySQL service.
3. The administrative password is T3chnology!! and the e-mail is set to youradmin@example.com.
4. Ensure the user's are made in the MySQL Service. The website is set for an config.ini file to be placed outside the root in a folder that will store your passwords.
    three users accounts were made for use on the database, one for visitors, one for members and one for administrators. You can view the separation of control in the includes that connect to the database.
5. Browse to the localhost where your bsquared portfolio is running.
6. phpmyadmin was used in the creation of the database and maybe used to ensure a successful configuration, not recommended for production use.
7. The bsquared cohort default user authentication scheme is set but will be removed for public release. Once you are logged into the website under the admin, go to the add user under administrative, you may add as many users to get started.
8. If there are any questions regarding the configuration of this portfolio website contact Aaron Young, mustarddevelopment@gmail.com




###The MIT License (MIT)

Copyright (c) 2016-Present b[Squared]

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

### SHA 512 Hash Algorithm
A JavaScript implementation of the Secure Hash Algorithm, SHA-512, as defined
in FIPS 180-2 Version 2.2 Copyright Anonymous Contributor, Paul Johnston 2000 - 2009.
Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
Distributed under the BSD License See http://pajhome.org.uk/crypt/md5 for details.
