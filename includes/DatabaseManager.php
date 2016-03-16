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
 * Class DatabaseManager
 * @author Jason Kessler-Holt
 */
class DatabaseManager
{

    // Main PDO object
    private $connection;

    // Properties of database connection
    private $host;
    private $port;
    private $database;
    private $user;
    private $password;

    // Constants used for selecting query type
    const TYPE_SELECT = 'select';
    const TYPE_INSERT = 'insert';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';
    const TYPE_ALTER = 'alter';
    const TYPE_DROP = 'drop';
    
    // Constant for error control
    const ERROR_PDO = 'PDO_ERROR';

    public function __construct($host, $port, $database, $user, $password)
    {
        $this->connection = null;
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
    }

    public function execute($statement, $queryType = DatabaseManager::TYPE_SELECT, $parameters = null)
    {
        $result = null;

        try
        {
            $this->openConnection(); // Begin a new connection to the database
            /** @noinspection PhpUndefinedMethodInspection */
            $statement = $this->connection->prepare($statement); // Set the statement

            switch($queryType)
            {
                case $this::TYPE_ALTER:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->execute($parameters); // Run the query
                    break;

                case $this::TYPE_DELETE:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->execute($parameters); // Run the query
                    break;

                case $this::TYPE_DROP:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->execute($parameters); // Run the query
                    break;

                case $this::TYPE_INSERT:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->execute($parameters); // Run the query
                    break;

                case $this::TYPE_UPDATE:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->execute($parameters); // Run the query
                    break; 

                case $this::TYPE_SELECT:
                    /** @noinspection PhpUndefinedMethodInspection */
                    $statement->execute($parameters); // Run the query
                    /** @noinspection PhpUndefinedMethodInspection */
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Grab result of execution
                    break;

                default:
                    $result = "Function->execute() - Unknown query type.";
                    break;
            }

            $this->closeConnection(); // Close connection to database once query is complete.
        }
        catch(PDOException $err)
        {
            $result = $this::ERROR_PDO . $err->getMessage();
        }

        return $result; // Return the results.
    }


    /**
     * Instantiates a new PDO object to be used to communicate with the database.
     * The function will return a null if no errors, else returns PDOException.
     * @return null|string
     */
    private function openConnection()
    {
        $result = null;

        $connectionString = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database;

        try
        {
            $this->connection = new PDO($connectionString, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets PDO to throw PDOExeptions if something fails.
        }
        catch(PDOException $err)
        {
            $result = $this::ERROR_PDO . $err->getMessage();
        }

        return $result; // Returns null if everything was fine, else returns PDO exception thrown.
    }

    /**
     * Closes the current $connection PDO object.
     */
    private function closeConnection()
    {
        $this->connection = null;
    }
}
