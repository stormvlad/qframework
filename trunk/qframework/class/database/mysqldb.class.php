<?php

    include_once("framework/class/database/adodb/adodb.inc.php" );
    include_once("framework/class/database/db.class.php" );
    include_once("framework/class/database/dbparams.class.php" );

    /**
     * Provides a singleton for accessing the db.
     */
    class MysqlDb extends Db
    {
        function MysqlDb()
        {
            $this->Db();
        }

        function &_getDb($dbParams)
        {
            static $db;

            if (!isset($db))
            {
                $db = NewADOConnection("mysql");

                $host     = $dbParams->getValue("db_host");
                $username = $dbParams->getValue("db_username");
                $password = $dbParams->getValue("db_password");
                $dbname   = $dbParams->getValue("db_database");

                if (!$db->PConnect($host, $username, $password, $dbname))
                {
                    throw(new Exception("MysqlDb::_getDb: Could not connect to the database!"));
                    die();
                }

                $db->SetFetchMode(ADODB_FETCH_ASSOC);
            }

            return $db;
        }

        /**
         * Prepares a string for an SQL query by escaping apostrophe
         * characters. If the PHP configuration setting 'magic_quotes_gpc'
         * is set to ON, it will first strip the added slashes. Apostrophe
         * characters are doubled, conforming with the ANSI SQL standard.
         * The SQL parser makes sure that the escape token is not entered
         * in the database so there is no need to modify the data when it
         * is read from the database.
         *
         * @param  string $string
         * @return string
         * @access public
         */
        function qstr($string) {

            if (get_magic_quotes_gpc) {
                $string = stripslashes($string);
            }

            $string = str_replace("'", "''", $string);

            return $string;
        }
    }
?>
